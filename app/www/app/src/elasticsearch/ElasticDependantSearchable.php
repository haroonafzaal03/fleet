<?php

namespace TND\ElasticSearch;

use SilverStripe\Core\ClassInfo;
use SilverStripe\Core\Injector\Injector;
use Symbiote\QueuedJobs\Services\QueuedJobService;

/**
 * Class ElasticDependantSearchable
 *
 * @package TND\ElasticSearch
 */
class ElasticDependantSearchable extends ElasticSearchable
{
    public function onAfterWrite()
    {

        if ($this->queueEnabled()) {
            $job = new ElasticReindexParentQueueJob($this->getOwner()->getClassName(), $this->getOwner()->ID);
            QueuedJobService::singleton()->queueJob($job);

        } else {
            $this->reIndexParentIndexes();
        }
    }

    public function onBeforeDelete()
    {
        if ($this->queueEnabled()) {
            $job = new ElasticReindexParentQueueJob($this->getOwner()->getClassName(), $this->getOwner()->ID);
            QueuedJobService::singleton()->queueJob($job);

        } else {
            $this->reIndexParentIndexes();
        }
    }

    /***
     * Reindex parent Documents
     *
     * ***Find the parent indexes
     * ** Search for the documents where this exists as nested/object
     * ** update/reindex the documents
     *
     * @throws \ReflectionException
     */
    public function reIndexParentIndexes()
    {
        //find all parent indexes
        $parentIndexes = $this->findParentIndexes();

        foreach ($parentIndexes as $parentIndex) {

            $searchQuery = $this->buildIndexSearchQuery($parentIndex['field'], $parentIndex['fieldtype']);

            if ($searchQuery) {

                $elasticIndex = Injector::inst()->create($parentIndex['class']);

                //search elastic index
                $result = $elasticIndex->search($searchQuery);

                //TODO:: read result from a Response mapper

                if ($result && isset($result['hits']['hits']) && is_array($result['hits']['hits'])) {

                    $resultItems = $result['hits']['hits'];

                    //TODO:: implement batch update of documents
                    foreach ($resultItems as $resultItem) {
                        $elasticIndex->reIndexDocument((int)$resultItem['_id']);
                    }
                }
            }
        }
    }

    /**
     * Find parent indexes
     *
     * @return array
     * @throws \ReflectionException
     */
    public function findParentIndexes()
    {

        $dependantIndexes = [];

        //get all indexes
        $classes = ClassInfo::subclassesFor(AbstractIndex::class, false);

        foreach ($classes as $indexName => $indexClass) {

            $dependant = $this->getDependantFor($indexClass);

            if ($dependant) {
                $dependantIndexes[] = [
                    'class' => $indexClass,
                    'field' => $dependant,
                    'fieldtype' => Injector::inst()->get($indexClass)->getFieldMappingType($dependant)
                ];
            }

        }

        return $dependantIndexes;
    }

    /**
     * Find the Dependant filed if this is dependant of the index
     *
     * @param $indexClass
     * @return false|int|string|null
     */
    public function getDependantFor($indexClass)
    {
        // get dependant classes of each index
        $dependantClasses = Injector::inst()->get($indexClass)->getDependantClasses();

        $dependant = array_search($this->getOwner()->getClassName(), $dependantClasses);

        // Search If the class ancestors has index
        if (!$dependant) {
            $ancestors = array_intersect($dependantClasses, ClassInfo::ancestry($this->getOwner()->getClassName()));
            $dependant = array_keys($ancestors)[0] ?? null;
        }

        return $dependant;
    }

    /**
     * Build search query to find the Parent documents to be updated
     *
     * @param $field
     * @param $fieldType
     * @return array|null
     */
    public function buildIndexSearchQuery($field, $fieldType)
    {
        $recordID = $this->getOwner()->ID ?? 0;

        if ($recordID) {

            if ($fieldType == 'nested') {

                // { "_source" : false, "query": { "nested": { "path": "FIELD", "query": { "bool": { "must": [ { "match": { "FIELD.ID": FIELD_VALUE }} ] } } } } }
                $query = [
                    '_source' => false,
                    'query' => [
                        'nested' => [
                            'path' => $field,
                            'query' => [
                                'bool' => [
                                    'must' => [
                                        'match' => [
                                            "{$field}.ID" => $recordID
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ];
            } else {
                if ($fieldType == 'object') {

                    // { "_source" : false, "query": { "bool": { "must": [ { "match": { "FIELD.ID": FIELD_VALUE } } ] } } }
                    $query = [
                        '_source' => false,
                        'query' => [
                            'bool' => [
                                'must' => [
                                    'match' => [
                                        "{$field}.ID" => $recordID
                                    ]
                                ]
                            ]
                        ]
                    ];
                }
            }

            return $query ?? null;
        }

        return null;
    }


}
