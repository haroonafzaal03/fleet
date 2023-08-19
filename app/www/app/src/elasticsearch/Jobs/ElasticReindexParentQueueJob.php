<?php


namespace TND\ElasticSearch;


use SilverStripe\Core\ClassInfo;
use SilverStripe\Versioned\Versioned;
use Symbiote\QueuedJobs\Services\AbstractQueuedJob;
use Symbiote\QueuedJobs\Services\QueuedJob;

class ElasticReindexParentQueueJob extends AbstractQueuedJob
{
    /**
     * ElasticReindexParentQueueJob constructor.
     * @param string $className
     * @param int $objectID
     */
    public function __construct($className = null, $objectID = null)
    {
        if ($className && $objectID) {
            $this->className = $className;
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
        return sprintf("Reindexing %s Parents with ID = %s", $this->className, $this->objectID);
    }

    public function process()
    {
        $this->totalSteps++;
        if (ClassInfo::exists($this->className)) {


            // set versioned mode to LIVE
            $currentOldMode = Versioned::get_reading_mode();
            Versioned::set_reading_mode('Stage.Live');

            /**
             * Find object
             */
            $className = $this->className;
            $object = $className::get()->byID($this->objectID);
            $this->totalSteps++;

            if ($object && ClassInfo::hasMethod($object, 'reIndexParentIndexes')) {
                $object->reIndexParentIndexes();

                $this->totalSteps++;
            }

            // set versioned mode to old mode
            Versioned::set_reading_mode($currentOldMode);

        }

        $this->isComplete = true;
        return;
    }


}
