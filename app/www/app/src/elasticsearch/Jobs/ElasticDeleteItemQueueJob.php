<?php

namespace TND\ElasticSearch;

use SilverStripe\Core\ClassInfo;
use Symbiote\QueuedJobs\Services\AbstractQueuedJob;
use Symbiote\QueuedJobs\Services\QueuedJob;

/**
 * Class ElasticDeleteItemQueueJob
 * @package TND\ElasticSearch
 */
class ElasticDeleteItemQueueJob extends AbstractQueuedJob
{
    /**
     * ElasticDeleteItemQueueJob constructor.
     * @param string $indexClassName
     * @param int $objectID
     */
    public function __construct($indexClassName = null, $objectID = null)
    {
        if ($indexClassName && $objectID) {
            $this->className = $indexClassName;
            $this->objectID = $objectID;
        }
    }

    /**
     * @return string
     */
    public function getJobType()
    {
        return QueuedJob::IMMEDIATE;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return sprintf("Deleting Document from index = %s with ID = %s", $this->className, $this->objectID);
    }

    public function process()
    {
        $this->totalSteps++;
        if (ClassInfo::exists($this->className)) {

            $className = $this->className;
            singleton($className)->deleteDocument($this->objectID);
            $this->totalSteps++;
        }

        $this->isComplete = true;
        return;
    }
}
