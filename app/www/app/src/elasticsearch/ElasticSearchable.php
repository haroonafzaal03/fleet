<?php

namespace TND\ElasticSearch;

use SilverStripe\Core\ClassInfo;
use SilverStripe\Core\Config\Config;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\ORM\DataExtension;
use SilverStripe\ORM\DataObject;
use SilverStripe\Versioned\Versioned;
use Symbiote\QueuedJobs\Services\QueuedJobService;

/**
 * Class ElasticSearchable
 * @package TND\ElasticSearch
 */
class ElasticSearchable extends DataExtension
{

    private static $queueable = true;

    private static $bulk_index_enabled = false;

    public function queueEnabled()
    {
        return ClassInfo::exists(QueuedJobService::class) && Config::inst()->get($this->getOwner()->getClassName(), 'queueable');
    }

    public function bulkIndexEnabled()
    {
        return ClassInfo::exists(QueuedJobService::class) && Config::inst()->get($this->getOwner()->getClassName(),'bulk_index_enabled' );
    }

    /**
     * only reindex if not versioned. For versioned data objects @see ElasticSearchable::onAfterPublish()
     */
    public function onAfterWrite()
    {
        $isVersioned = DataObject::has_extension($this->getOwner()->getClassName(), Versioned::class);
        if (!$isVersioned) {
            $this->handleReindex();
        }
    }

    /**
     * To handle versioned data objects. Only reindex if published
     */
    public function onAfterPublish()
    {
        $this->handleReindex();
    }

    /**
     * Pre-delete the document as record will be deleted in database (ORM)
     */
    public function onBeforeDelete()
    {
        try {
            $this->deleteDocument();
        } catch (\Exception $exception) {

        }
    }

    /**
     * for versioned data objects. Remove document on unpublish.
     */
    public function onAfterUnpublish()
    {
        try {
            $this->deleteDocument();
        } catch (\Exception $exception) {

        }
    }

    public function handleReindex()
    {
        if ($this->queueEnabled()) {
            if ($this->bulkIndexEnabled()) {
                $this->enqueueInBulkBatch();
            } else {
                $this->enqueueReindexJob();
            }
        } else {
            $this->getOwner()->reIndex();
        }
    }

    public function enqueueReindexJob()
    {
        $service = ElasticSearchService::singleton();
        if ($service) {
            $job = new ElasticReindexItemQueueJob($this->getOwner()->getClassName(), $this->getOwner()->ID);
            QueuedJobService::singleton()->queueJob($job);
        }
    }

    public function enqueueInBulkBatch()
    {
        $service = ElasticSearchService::singleton();
        if ($service) {
            $indexClass = $service->getIndexFor($this->getOwner()->getClassName());
            ElasticQueuedData::singleton()->enqueue($this->getOwner(), $indexClass);
        }
    }

    public function reIndex()
    {
        $service = ElasticSearchService::singleton();
        if ($service) {
            $indexClass = $service->getIndexFor($this->getOwner()->getClassName());
            if ($indexClass && ClassInfo::exists($indexClass)) {
                $elasticIndex = Injector::inst()->create($indexClass);
                $object = DataObject::get($this->getOwner()->getClassName())->find('ID', $this->getOwner()->ID);
                return $elasticIndex->reIndexDocument($object);
            }
        }
        return false;
    }

    public function deleteDocument()
    {
        $service = ElasticSearchService::singleton();
        if ($service) {
            $elasticIndexClass = $service->getIndexFor($this->getOwner()->getClassName());
            if (ClassInfo::exists($elasticIndexClass)) {
                $elasticIndex = Injector::inst()->create($elasticIndexClass);

                return $elasticIndex->deleteDocument($this->getOwner()->ID);
            }
        }
        return false;
    }
}
