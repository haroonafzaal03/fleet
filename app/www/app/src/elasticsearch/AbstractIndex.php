<?php

namespace TND\ElasticSearch;

use SilverStripe\Core\ClassInfo;
use SilverStripe\Core\Config\Configurable;
use SilverStripe\Core\Extensible;
use SilverStripe\Core\Injector\Injectable;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\ORM\ArrayList;
use SilverStripe\ORM\DataList;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\SS_List;

/**
 * Class AbstractIndex
 * @package TND\ElasticSearch
 */
abstract class AbstractIndex
{
    use Extensible;
    use Configurable;
    use Injectable;

    /**
     * Elastic Search index Name
     * @var string
     */
    private static $name = '';

    /**
     * Holds the main source DataObject class e.g. Member, Group
     *
     * @var string|null
     */
    private static $source = null;

    /**
     * @var array
     */
    private static $dependant_classes = [];

    /** Defines index mapping
     * @var array
     */
    private static $mapping = [];

    /**
     * Total number of shards
     *
     * @var int
     */
    private static $number_of_shards = 3;

    /**
     * Total number of replicas
     *
     * @var int
     */
    private static $number_of_replicas = 1;

    private static $index_settings = [
        'index.blocks.read_only_allow_delete' => false
    ];

    /**
     * To exclude any fields in response source
     * @var array
     */
    private static $is_excluded = [

    ];

    /**
     * @var ElasticSearchService
     */
    protected $service;

    public function __construct()
    {
        $this->service = Injector::inst()->create(ElasticSearchService::class);
    }

    public function isExcluded($fieldTitle)
    {
        return in_array($fieldTitle, $this->config()->get('is_excluded'));
    }

    /**
     * @return string
     */
    public function getIndexName()
    {
        return $this->config()->get('name');
    }

    /**
     * @return string
     */
    public function getSource()
    {
        $source = $this->config()->get('source');
        if ($source && ClassInfo::exists($source)) {
            return $source;
        } else {
            /**
             * Get source data object class from registered indexes
             */
            $service = ElasticSearchService::singleton();
            if ($service) {
                return $service->getRegisteredClassForIndex(static::class);
            } else {
                return null;
            }

        }
    }

    /**
     * @return int
     */
    public function getNumberOfShards()
    {
        return $this->config()->get('number_of_shards');
    }

    /**
     * @return int
     */
    public function getNumberOfReplicas()
    {
        return $this->config()->get('number_of_replicas');
    }

    /**
     * @return int|null
     */
    public function getRefreshInterval()
    {
        return $this->config()->get('refresh_interval');

    }

    /**
     * @return ElasticSearchService
     */
    public function getService()
    {
        return $this->service;
    }

    public function getMapping()
    {
        return $this->config()->get('mapping');
    }

    public function getDependantClasses()
    {
        return $this->config()->get('dependant_classes');
    }

    public function getLocaleFields()
    {
        return $this->config()->get('locale_fields');
    }

    /**
     * @return bool
     */
    public function exists()
    {
        return $this->getService()->existsIndex(
            $this->getIndexName()
        );
    }

    public function createIndex()
    {
        $indexName = $this->getIndexName();
        $properties = $this->compileParameters();
        $elasticSearch = $this->getService();

        return $elasticSearch->createIndex($indexName, $properties);
    }

    /**
     * @return bool
     */
    public function updateIndex()
    {
        $indexName = $this->getIndexName();

        $mappings = $this->compileMappingParameters();
        $settings = $this->compileSettingParameters();

        // unset non dynamic settings
        if (isset($settings['number_of_shards'])) {
            unset($settings['number_of_shards']);
        }

        if (isset($mappings['_source'])) {
            unset($mappings['_source']);
        }

        $elasticSearch = $this->getService();

        $elasticSearch->updateIndexSettings($indexName, $settings);
        $elasticSearch->updateIndexMapping($indexName, $mappings);

        return true;
    }

    public function deleteIndex()
    {
        $indexName = $this->getIndexName();

        $elasticSearch = $this->getService();

        return $elasticSearch->deleteIndex($indexName);
    }

    public function createOrUpdateIndex()
    {
        if ($this->exists()) {
            return $this->updateIndex();
        } else {
            return $this->createIndex();
        }
    }

    /**
     * @param DataObject|int $param
     * @return array
     * @throws \Exception
     */
    public function reIndexDocument($param)
    {
        if (is_int($param)) {
            $dataObjectClass = $this->getSource();

            if ($dataObjectClass && ClassInfo::exists($dataObjectClass)) {
                $object = DataObject::get($dataObjectClass)->byID($param);
                if (!$object) {
                    throw new \Exception(sprintf("Reindex failed. Object of type %s with ID = %s not found.", $dataObjectClass, $param));
                }
            } else {
                throw new \Exception(sprintf("No Registered Data Object class found for index = %s", static::class));
            }

        } else {

            $object = $param;
        }

        // validation rules can be applied for each sub-index class
        if ($this->isIndexable($object)) {
            $data = $this->compileDocumentData($object);

            /**
             * Push to Elastic search
             */
            return $this->getService()->indexDocument(
                $this->getIndexName(),
                $object->ID,
                $data
            );
        } else {
            return;
        }
    }

    /**
     * @param DataList $list
     * @return array|void
     * @throws \Exception
     */
    public function reIndexDocuments($list)
    {
        // validation rules can be applied for each sub-index class
        if ($list->exists()) {
            $data = [];
            foreach ($list as $item) {

                $data['body'][] = [
                    'index' => [
                        '_index' => $this->getIndexName(),
                        '_id' => $item->ID
                    ]
                ];
                $data['body'][] = $this->compileDocumentData($item);
            }

            /**
             * Push to Elastic search
             */
            return $this->getService()->indexDocuments(
                $this->getIndexName(),
                $data
            );
        } else {
            return;
        }
    }

    /**
     * @param int|DataObject $context
     * @param array $extraParams
     * @param bool $sourceOnly
     * @return array
     */
    public function getDocument($context, array $extraParams = [], $sourceOnly = FALSE)
    {
        if ($sourceOnly) {
            return $this->getService()->getDocumentSource(
                $this->getIndexName(),
                $context->ID ?? $context,
                $extraParams
            );

        } else {

            return $this->getService()->getDocument(
                $this->getIndexName(),
                $context->ID ?? $context,
                $extraParams
            );
        }
    }

    /**
     * @param array|string $ids
     * @param array $extraParams
     * @param bool $sourceOnly
     * @return array|callable
     */
    public function getDocuments($ids, array $extraParams = [], $sourceOnly = FALSE)
    {
        if ($sourceOnly) {
            return $this->getService()->getDocumentsSource(
                $this->getIndexName(),
                $ids,
                $extraParams
            );

        } else {

            return $this->getService()->getDocuments(
                $this->getIndexName(),
                $ids,
                $extraParams
            );
        }
    }

    /**
     * @param int|DataObject $param
     * @return array
     */
    public function deleteDocument($param)
    {
        return $this->getService()->deleteDocument(
            $this->getIndexName(),
            $param->ID ?? $param
        );
    }

    /**
     * delete a document by query
     * for example; for deleting whole a document, body/query will be
     *  {"query": {"match_all":{}}}
     * @param $body
     * @return array|callable
     */
    public function deleteDocumentByQuery($body)
    {
        return $this->getService()->deleteDocumentByQuery(
            $this->getIndexName(),
            $body
        );
    }

    /**
     * @param array $body
     * @param bool $sourceOnly
     * @return array
     */
    public function search($body = [], $sourceOnly = FALSE)
    {
        if ($sourceOnly) {
            return $this->getService()->searchSource(
                $this->getIndexName(),
                $body
            );

        } else {
            return $this->getService()->search(
                $this->getIndexName(),
                $body
            );
        }
    }

    /**
     * @return array
     */
    protected function compileParameters()
    {
        $properties = [];
        $properties['body']['settings'] = $this->compileSettingParameters();
        $properties['body']['mappings'] = $this->compileMappingParameters();

        return $properties;
    }

    protected function compileMappingParameters()
    {
        $mapping = new IndexMapping($this->getMapping());
        return $mapping->compile();
    }

    protected function compileSettingParameters()
    {
        $properties = static::config()->get('index_settings');

        if (
            $this->getNumberOfShards() ||
            $this->getNumberOfReplicas() ||
            $this->getRefreshInterval()
        ) {
            if ($this->getNumberOfShards()) {
                $properties['number_of_shards'] = $this->getNumberOfShards();
            }

            if ($this->getNumberOfReplicas()) {
                $properties['number_of_replicas'] = $this->getNumberOfReplicas();
            }

            if ($this->getRefreshInterval()) {
                $properties['refresh_interval'] = $this->getRefreshInterval();
            }
        }

        return $properties;
    }

    public function getFieldMappingType($field)
    {
        $mapping = $this->getMapping();

        if (is_array($mapping[$field])) {
            return $mapping[$field]['fieldtype'] ?? NULL;

        } else if (IndexMapping::singleton()->isValidType($mapping[$field])) {
            return $mapping[$field];
        }
    }

    /**
     * @param DataObject $object
     * @return array
     * @throws \Exception
     */
    protected function compileDocumentData(DataObject $object)
    {
        $mapping = $this->getMapping();
        return $this->processDocumentItem($object, $mapping);
    }

    /**
     * @param $fieldTitle
     * @param array $mapping
     * @param DataObject $object
     * @return array
     * @throws \Exception
     */
    protected function processFieldData($fieldTitle, $mapping, $object)
    {
        $method = 'get' . ucfirst($fieldTitle);

        if (ClassInfo::hasMethod($object, $method)) {
            $data = $object->{$method}();
        } else if (ClassInfo::hasMethod($object, $fieldTitle)) {
            $data = $object->{$fieldTitle}();
        } else {
            throw  new \Exception(sprintf("Method %s not found on class %s", $fieldTitle, $object->getClassname()));
        }

        if ($data instanceof SS_List) {
            return $this->processDocumentList($data, $mapping);

        } elseif ($data instanceof DataObject) {
            return $this->processDocumentItem($data, $mapping);

        } else {
            /**
             * TODO
             */
        }

    }

    /**
     * @param $data
     * @param $mapping
     * @return array
     * @throws \Exception
     */
    protected function processDocumentList($data, $mapping)
    {
        $response = [];
        foreach ($data as $item) {
            $response[] = $this->processDocumentItem($item, $mapping);
        }

        return $response;
    }

    /**
     * @param DataObject $data
     * @param array $mapping
     * @return array
     * @throws \Exception
     */
    protected function processDocumentItem($data, $mapping)
    {
        $response = [];
        foreach ($mapping as $fieldTitle => $fieldData) {

            if ($fieldTitle == 'fieldtype') {
                continue;
            }

            if (is_string($fieldData)) {
                $response[$fieldTitle] = IndexMapping::singleton()->getFieldValue($data, $fieldTitle, $fieldData);

            } elseif (is_array($fieldData)) {

                /**
                 * for nested and object type
                 */
                if (isset($fieldData['fields'])) {
                    $response[$fieldTitle] = $this->processFieldData($fieldTitle, $fieldData['fields'], $data);

                } elseif (IndexMapping::singleton()->isValidType($fieldData['fieldtype'])) {
                    $response[$fieldTitle] = IndexMapping::singleton()->getFieldValue($data, $fieldTitle, $fieldData['fieldtype']);
                } else {
                    throw new \Exception('Nested and Object type requires `fields`.');
                }
            }
        }

        return $response;
    }

    /**
     * @param DataObject $object
     * @return bool
     */
    public function isIndexable($object)
    {
        return true;
    }
}
