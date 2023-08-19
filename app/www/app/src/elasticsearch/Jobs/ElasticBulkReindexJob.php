<?php

namespace TND\ElasticSearch;

use SilverStripe\Core\ClassInfo;
use SilverStripe\ORM\DB;
use SilverStripe\ORM\FieldType\DBDatetime;
use SilverStripe\Versioned\Versioned;
use Symbiote\QueuedJobs\Services\AbstractQueuedJob;
use Symbiote\QueuedJobs\Services\QueuedJob;
use Symbiote\QueuedJobs\Services\QueuedJobService;

/**
 * Class ElasticBulkReindexJob
 * @package TND\ElasticSearch
 */
class ElasticBulkReindexJob extends AbstractQueuedJob
{
    const DELAY_BETWEEN_JOBS = 2; // 5 seconds

    /**
     * ElasticBulkReindexJob constructor.
     * @param string $className
     * @param int $objectID
     */
    public function __construct($className = null)
    {
        if ($className) {
            $this->className = $className;
        }
    }

    /**
     * @return string
     */
    public function getJobType()
    {
        return QueuedJob::LARGE;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return sprintf("Bulk Reindexing Elastic");
    }

    public function process()
    {
        $this->totalSteps++;
        $indexes = [];
        if ($this->className && ClassInfo::exists($this->className)) {
            $indexes[] = $this->className;
        } else {
            $indexes = array_values(ClassInfo::subclassesFor(AbstractIndex::class, false));
        }

        // set versioned mode to LIVE
        $currentOldMode  = Versioned::get_reading_mode();
        Versioned::set_reading_mode('Stage.Live');

        /**
         * Process
         */
        $elasticQueueDataFilter = [
            'Status' => ElasticQueuedData::QUEUE_NEW,
        ];

        $rows = ElasticQueuedData::get()->filter($elasticQueueDataFilter)->chunkedFetch();

        foreach($rows as $elasticQueuedData) {
            try {
                $dataObjectClass = $elasticQueuedData->DataObjectClass;
                $dataObjectID = $elasticQueuedData->DataObjectID;

                echo "Getting $dataObjectClass with ID = " . $dataObjectID . "\n";

                $item = $dataObjectClass::get()->byID($dataObjectID);
                if (!$item) {
                    // Delete something that doesn't exist
                    $elasticQueuedData->delete();
                    echo "> [ERROR]: Record not found\n";
                    continue;
                }

                echo "> Reindexing item $dataObjectClass with ID = " . $item->ID . "\n";
                $item->reIndex();

                $elasticQueuedData->delete();
            } catch (\Exception $exception) {
                // TODO: handle exception
                echo "> " . $exception->getMessage() . "\n";

                $elasticQueuedData->Status = ElasticQueuedData::QUEUE_FAILED;
                $elasticQueuedData->write();
            }
        }

        $this->totalSteps++;

        // set versioned mode to old mode
        Versioned::set_reading_mode($currentOldMode);

        // re-enqueue the JOB
        $this->isComplete = true;
        $job = new ElasticBulkReindexJob();

        // enqueue to run later if we still have stuff
        if (ElasticQueuedData::get()->filter($elasticQueueDataFilter)->exists()) {
            QueuedJobService::singleton()->queueJob(
                $job,
                DBDatetime::create()->setValue(DBDatetime::now()->getTimestamp() + self::DELAY_BETWEEN_JOBS)->Rfc2822()
            );
        }

        return true;
    }
}
