<?php

namespace TND\ElasticSearch;

use SilverStripe\Core\Injector\Injectable;
use TractorCow\Fluent\State\FluentState;

/**
 * Class IndexMapping
 * @package TND\ElasticSearch
 */
class IndexMapping
{
    use Injectable;

    private static $available_types = [
        'integer' => 'integer',
        'text' => 'text',
        'date' => 'date',
        'keyword' => 'keyword',
        'long' => 'long',
        'double' => 'double',
        'float' => 'float',
        'boolean' => 'boolean',
        'ip' => 'ip',
        'nested' => 'nested',
        'object' => 'object',
        'geo_point' => 'geo_point',
        'completion' => 'completion'
    ];

    private static $cast_types = [
        'integer' => 'int',
        'text' => 'string',
        'date' => 'string',
        'keyword' => 'string',
        'long' => 'double',
        'double' => 'double',
        'boolean' => 'bool',
        'ip' => 'string',
    ];

    private static $ignore_casting = [
        'geo_point',
        'float',
        'completion'
    ];

    public $response = [
        '_source' => [
            'enabled' => true
        ]
    ];

    private $mapping;

    /**
     * IndexMapping constructor.
     * @param array $mapping
     */
    public function __construct($mapping = array())
    {
        $this->mapping = $mapping;
    }

    /**
     * @return array
     */
    public function getMapping()
    {
        return $this->mapping;
    }

    /**
     * @param array $mapping
     */
    public function setMapping($mapping)
    {
        $this->mapping = $mapping;
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function compile()
    {
        if (!empty($this->mapping)) {
            $mappingData = $this->processMappingList($this->getMapping());
            $this->response['properties'] = $mappingData['properties'];
            return $this->response;
        } else {
            throw new \Exception('Index mapping not found.');
        }
    }

    /**
     * @param $type
     * @return bool
     */
    public function isValidType($type)
    {
        return in_array($type, self::$available_types);
    }

    /**
     * @param $list
     * @return array
     */
    protected function processMappingList($list)
    {
        $data = [
            'properties' => []
        ];

        foreach ($list as $field => $type) {
            if ($field && $type) {
                $data['properties'][$field] = $this->processMappingItem($field, $type);
            }
        }

        return $data;
    }

    /**
     *
     * @param $field
     * @param $type
     * @return array
     */
    protected function processMappingItem($field, $type)
    {
        if (is_string($type)) {
            $type = [
                'fieldtype' => $type
            ];
        }

        if (is_array($type) && !empty($type)) {
            $itemData = $type;
            $itemData['type'] = $itemData['fieldtype'];
            unset($itemData['fieldtype']);

            if ($itemData['type'] === 'nested') {
                $itemData = $this->processMappingList($itemData['fields']);
                $itemData['type'] = 'nested';
                return $itemData;

            } elseif ($itemData['type'] === 'object') {

                return $this->processMappingList($itemData['fields']);

            } elseif ($itemData['type'] === 'date') {
                $itemData['format'] = "yyyy-MM-dd HH:mm:ss||yyyy-MM-dd||epoch_millis";
                $itemData['ignore_malformed'] = true;
                return $itemData;

            } elseif (IndexMapping::singleton()->isValidType($itemData['type'])) {
                return $itemData;

            } else {
                throw new \InvalidArgumentException("Elastic index type " . $itemData['type'] . " not supported.");
            }

        } else {
            throw new \InvalidArgumentException("Invalid field data provided.");
        }
    }

    /**
     * Cast SilverStripe data types to elastic friendly types
     * @param $value
     * @param $type
     * @return array|string
     */
    public function castDataType($value, $type)
    {
        // special check for completion type field
        if ($type === 'completion') {
            return [
                'input' => $value
            ];
        }

        if (!in_array($type, self::$ignore_casting)) {

            /** ignore default casting if value is in array for the keyword field */
            if($type == 'keyword' && is_array($value)) {
                settype($value, 'array');
            } else {
                settype($value, self::$cast_types[$type]);
            }
        }
        return $value;
    }

    public function getFieldValue($context, $fieldTitle, $fieldType)
    {
        // Locale check
        if (strpos($fieldTitle, '__') !== false) {
            list($fieldTitle, $Locale) = explode('__', $fieldTitle);
            $fieldValue = null;
            FluentState::singleton()->withState(
                function (FluentState $newState) use (&$fieldValue, $context, $fieldTitle, $Locale, $fieldType) {
                    $newState->setLocale($Locale);
                    $className = $context->getClassName();
                    $localRecord = $className::get()->byID($context->ID);
                    FluentState::singleton()->getLocale();

                    $fieldValue = IndexMapping::singleton()->castDataType($localRecord->{$fieldTitle}, $fieldType);
                }
            );

            return $fieldValue;

        } else {
            return IndexMapping::singleton()->castDataType($context->{$fieldTitle}, $fieldType);
        }
    }
}
