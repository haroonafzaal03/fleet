<?php

namespace TND\ElasticSearch;

use Elasticsearch\ClientBuilder;
use Elasticsearch\Common\Exceptions\Missing404Exception;
use Elasticsearch\Common\Exceptions\NoNodesAvailableException;
use SilverStripe\Core\ClassInfo;
use SilverStripe\Core\Config\Configurable;
use SilverStripe\Core\Extensible;
use SilverStripe\Core\Injector\Injectable;

/**
 * Class ElasticSearchService
 * @package TND\ElasticSearch
 */
class ElasticSearchService
{
    use Injectable;
    use Extensible;
    use Configurable;

    /**
     * @var \Elasticsearch\Client
     */
    protected $elasticBuilder;

    /**
     * @var ElasticSearchConfig
     */
    protected $config;

    /**
     * @var array
     */
    private static $registered_indexes = [];

    /**
     * ElasticSearchService constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->config = new ElasticSearchConfig();
        if (
            $this->config->getHost() === NULL ||
            $this->config->getUsername() === NULL ||
            $this->config->getPassword() === NULL
        ) {
            throw new \Exception("ElasticSearch required configuration not found.");
        }

        try {
            $this->elasticBuilder = ClientBuilder::fromConfig($this->config->getConfig(), TRUE);
        } catch (NoNodesAvailableException $noNodes) {
            return null;

        } catch (\Exception $exception) {
            return null;
        }
    }

    /**
     * Returns list of all registered indexes
     *
     * @return array
     */
    public function getRegisteredIndexes()
    {
        return self::config()->get('registered_indexes');
    }

    /**
     * Finds index class registered for Data object class
     *
     * @param $className
     * @return false|string
     */
    public function getIndexFor($className)
    {
        $registeredIndexes = $this->getRegisteredIndexes();
        $index = array_search($className, $registeredIndexes);

        // Search If the class ancestors has index
        if (!$index) {
            $ancestors = array_intersect($registeredIndexes, ClassInfo::ancestry($className));
            $index = array_keys($ancestors)[0] ?? null;
        }

        return $index;
    }


    /**
     * Finds registered data object class for given index
     *
     * @param $index
     * @return string|null
     */
    public function getRegisteredClassForIndex($index)
    {
        $registeredIndexes = $this->getRegisteredIndexes();
        return $registeredIndexes[$index] ?? null;
    }

    /**
     * Index exists
     *
     * @param $indexName
     * @return bool
     */
    public function existsIndex($indexName)
    {
        $indexParams = [
            'index' => $indexName,
        ];

        return $this->elasticBuilder->indices()->exists($indexParams);
    }

    /**
     * Get index
     *
     * @param $indexName
     * @return array
     */
    public function getIndex($indexName)
    {
        $indexParams = [
            'index' => $indexName,
        ];

        return $this->elasticBuilder->indices()->get($indexParams);
    }

    /**
     * Get index
     *
     * @param $indexName
     * @return array
     */
    public function getIndexSettings($indexName)
    {
        $indexParams = [
            'index' => $indexName,
        ];

        return $this->elasticBuilder->indices()->getSettings($indexParams);
    }

    /**
     * Create index
     *
     * @param $indexName
     * @param array $properties
     * @return array
     *
     * @link https://www.elastic.co/guide/en/elasticsearch/client/php-api/current/index_management.html#_create_an_index_advanced_example
     */
    public function createIndex($indexName, $properties = [])
    {

        $indexParams = [
            'index' => $indexName,
        ];

        if (!empty($properties)) {
            $indexParams = array_merge($indexParams, $properties);
        }

        return $this->elasticBuilder->indices()->create($indexParams);
    }

    /**
     * Update index settings
     *
     * @param $indexName
     * @param $settings ['number_of_shards' => 1, 'number_of_replicas' => 1, 'analysis' => []]
     * @return array
     *
     * @link https://www.elastic.co/guide/en/elasticsearch/client/php-api/current/index_management.html#_put_settings_api
     */
    public function updateIndexSettings($indexName, $settings = [])
    {
        $indexParams = [
            'index' => $indexName,
            'body' => [],
        ];

        if (!empty($settings)) {
            $indexParams['body']['settings'] = $settings;
        }

        return $this->elasticBuilder->indices()->putSettings($indexParams);
    }

    /**
     *  Update index mapping
     *
     * @param $indexName
     * @param array $properties
     * @return array
     *
     * @link https://www.elastic.co/guide/en/elasticsearch/client/php-api/current/index_management.html#_put_mappings_api
     */
    public function updateIndexMapping($indexName, $properties = [])
    {
        $indexParams = [
            'index' => $indexName,
            'body' => $properties,
        ];

        return $this->elasticBuilder->indices()->putMapping($indexParams);
    }

    /**
     * Delete an index
     *
     * @param $indexName
     * @return array
     */
    public function deleteIndex($indexName)
    {
        $indexParams = [
            'index' => $indexName,
        ];

        return $this->elasticBuilder->indices()->delete($indexParams);
    }

    /**
     * @param string $indexName
     * @param int $id
     * @param array $properties
     * @param string $type
     * @return array
     */
    public function indexDocument($indexName, $id, $properties, $type = '_doc')
    {
        $indexParams = [
            'index' => $indexName,
            'id' => $id,
            'type' => $type,
            'body' => $properties,
        ];

        return $this->elasticBuilder->index($indexParams);
    }

    /**
     * @param string $indexName
     * @param int $id
     * @param array $properties
     * @param string $type
     * @return array
     */
    public function indexDocuments($indexName, $properties, $type = '_doc')
    {
        return $this->elasticBuilder->bulk($properties);
    }

    /**
     * @param string $indexName
     * @param int $id
     * @param array $extraParams
     * @param string $type
     * @return array
     */
    public function getDocument($indexName, $id, $extraParams = [], $type = '_doc')
    {

        $indexParams = array_merge($extraParams,
            [
                'index' => $indexName,
                'type' => $type,
                'id' => $id,
            ]
        );

        try {
            $response = $this->elasticBuilder->get($indexParams);
        } catch (Missing404Exception $documentNotFound) {
            $response = [
            ];
        }

        return $response;
    }

    public function getDocumentSource($indexName, $id, $extraParams = [], $type = '_doc')
    {
        $response = $this->getDocument($indexName, $id, $extraParams, $type);
        $source = [];
        if (!empty($response)) {
            $source = array_merge(['ID' => $response['_id']], $response['_source']);
        }

        return $source;
    }

    public function getDocuments($indexName, array $ids, $extraParams = [], $type = '_doc')
    {
        $indexParams = array_merge($extraParams,
            [
                'index' => $indexName,
                'type' => $type,
                'body' => [
                    'ids' => $ids
                ]
            ]
        );

        return $this->elasticBuilder->mget($indexParams);
    }

    public function getDocumentsSource($indexName, array $ids, $extraParams = [], $type = '_doc')
    {
        $response = $this->getDocuments($indexName, $ids, $extraParams, $type);
        $source = [];
        if (!empty($response) && isset($response['docs']) && !empty($response['docs'])) {
            foreach ($response['docs'] as $doc) {
                if (isset($doc['_source'])) {
                    $doc['_source']['ID'] = $doc['_id'];
                    /** Set ID value explicitly */
                    $source[$doc['_id']] = $doc['_source'];
                }
            }
        }

        return $source;
    }

    /**
     * @param string $indexName
     * @param int $id
     * @param string $type
     * @return array
     */
    public function deleteDocument($indexName, $id, $type = '_doc')
    {
        $indexParams = [
            'index' => $indexName,
            'id' => $id,
            'type' => $type,
        ];

        return $this->elasticBuilder->delete($indexParams);
    }

    /**
     * @param $indexName
     * @param $body
     * @param string $type
     * @return array|callable
     */
    public function deleteDocumentByQuery($indexName, $body, $type = '_doc')
    {
        $indexParams = [
            'index' => $indexName,
            'body' => $body,
            'type' => $type,
        ];

        return $this->elasticBuilder->deleteByQuery($indexParams);
    }

    /**
     * @param string $indexName
     * @param array|string $body
     * @return array
     */
    public function search($indexName, $body)
    {
        $params = [
            'index' => $indexName,
            'body' => $body
        ];

        return $this->elasticBuilder->search($params);
    }

    public function searchSource($indexName, $body)
    {
        $response = $this->search($indexName, $body);

        list($source, $totalHits, $aggs) = $this->extractSearchSource($response);
        return $source;
    }

    public function extractSearchSource($response)
    {
        $source = [];
        $totalHits = 0;
        $aggs = [];

        if (!empty($response) && isset($response['hits']['hits']) && !empty($response['hits']['hits'])) {
            $totalHits = $response['hits']['total']['value'];
            foreach ($response['hits']['hits'] as $doc) {
                if (isset($doc['_source'])) {

                    $doc['_source']['ID'] = $doc['_id'];
                    /** Set ID value explicitly */
                    $source[$doc['_id']] = $doc['_source'];
                }

                /**
                 * check for nested hits
                 */

                if (isset($doc['inner_hits'])) {
                    foreach ($doc['inner_hits'] as $nested => $nestedSource) {
                        if (
                        isset($nestedSource['hits']['hits'])
                        ) {
                            $source[$doc['_id']][$nested] = [];
                            foreach ($nestedSource['hits']['hits'] as $innerHit) {
                                if (isset($innerHit['_source'])) {
                                    $source[$doc['_id']][$nested][] = $innerHit['_source'];
                                }
                            }
                        }
                    }
                }
            }
        }

        if (!empty($response) && isset($response['aggregations']) && !empty($response['aggregations'])) {
            $aggs = $response['aggregations'];
        }

        return [$source, $totalHits, $aggs];
    }

    public function extractSuggestSource($response)
    {
        $suggestData = [];
        if (!empty($response) && isset($response['suggest']) && !empty($response['suggest'])) {
            $suggestData = $response['suggest'];
        }

        return $suggestData;
    }
}
