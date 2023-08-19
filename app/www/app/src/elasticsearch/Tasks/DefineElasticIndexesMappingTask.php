<?php

namespace TND\ElasticSearch;

use SilverStripe\Control\Director;
use SilverStripe\Core\ClassInfo;
use SilverStripe\Dev\BuildTask;
use SilverStripe\Security\Permission;
use SilverStripe\Security\Security;

/**
 * Defines the mapping of all the indexes
 * Task is restricted to users with administrator rights or running through CLI.
 */
class DefineElasticIndexesMappingTask extends BuildTask
{
    private static $segment = 'DefineElasticIndexesMappingTask';

    protected $title = 'Defines Elastic Search Indexes Mapping';

    protected $description = 'Defines Elastic Search Indexes Mapping';

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


        $indexes = ClassInfo::subclassesFor(AbstractIndex::class);
        array_shift($indexes);
        foreach ($indexes as $indexKey => $indexClass) {

            $printFunc("Defining Mapping For $indexClass");

            $indexClass::create()->createOrUpdateIndex();
        }

        $printFunc('Task Finished.');
    }
}
