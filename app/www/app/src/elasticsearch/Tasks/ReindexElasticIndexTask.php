<?php

namespace TND\ElasticSearch;

use SilverStripe\Control\Director;
use SilverStripe\Core\ClassInfo;
use SilverStripe\Dev\BuildTask;
use SilverStripe\Security\Permission;
use SilverStripe\Security\Security;
use SilverStripe\Versioned\Versioned;

/**
 * Reindex all documents
 * Task is restricted to users with administrator rights or running through CLI.
 *
 *
 * This task accepts 2 parameters:
 *
 * - class : Index to class to reindex
 * - updateMapping: whether to update the mapping of the index or not
 *
 */
class ReindexElasticIndexTask extends BuildTask
{
    private static $segment = 'ReindexElasticIndexTask';

    protected $title = 'Reindex Elastic Search Index';

    protected $description = 'Reindex Elastic Search Index';

    public function run($request)
    {
        if (!Permission::check('ADMIN') && !Director::is_cli()) {
            $response = Security::permissionFailure();
            if ($response) {
                $response->output();
            }
            die;
        }

        if (Director::is_cli()) {
            $printFunc = function ($message) {
                echo $message . "\n";
            };
        } else {
            $printFunc = function ($message) {
                echo $message . "<br />";
            };
        }

        $indexClass = $request->requestVar('class');

        $updateMapping = $request->requestVar('updateMapping') ?? true;

        $offset = $request->requestVar('offset') ?? 0;

        if (ClassInfo::exists($indexClass)) {

            if ($updateMapping) {
                $printFunc("Defining Mapping For $indexClass");

                $indexClass::singleton()->createOrUpdateIndex();
            }

            $service = ElasticSearchService::singleton();
            if (!$service) {
                return;
            }

            $dataObjectClass = $service->getRegisteredClassForIndex($indexClass);

            if ($dataObjectClass && ClassInfo::exists($dataObjectClass)) {

                if (class_exists(ElasticSearchable::class) && $dataObjectClass::has_extension(ElasticSearchable::class)) {

                    // set versioned mode to LIVE
                    $currentOldMode = Versioned::get_reading_mode();
                    Versioned::set_reading_mode('Stage.Live');

                    // Get the dataobject (use chunkedFetch so we don't destroy our DB)
                    // Sort by ID so we can view where we are easily
                    $list = $dataObjectClass::get()->sort('ID')->chunkedFetch(2000);
                    foreach ($list as $item) {
                        $printFunc("Reindexing item $dataObjectClass with ID = " . $item->ID);
                        $item->reIndex();
                    }

                    // set versioned mode to old mode
                    Versioned::set_reading_mode($currentOldMode);

                } else {
                    $printFunc(sprintf("In order to make data object '%s' searchable, assign %s extension first.", $dataObjectClass, ElasticSearchable::class));
                    die();
                }


            } else {
                $printFunc("Data Object '$dataObjectClass' class linked to '$indexClass' not found.");
                die();
            }
        } else {
            $printFunc("Class '$indexClass' not found.");
            die();
        }

        $printFunc('Task Finished.');
    }
}
