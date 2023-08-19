<?php

namespace TND\ElasticSearch\Admin;

use TND\ElasticSearch\ElasticQueuedData;
use SilverStripe\Admin\ModelAdmin;
use SilverStripe\Forms\GridField\GridFieldAddNewButton;

/**
 * Class ElasticSearchAdmin
 * @package TND\ElasticSearch
 */
class ElasticSearchAdmin extends ModelAdmin
{

    private static $menu_title = 'Elastic Queue Data';

    private static $url_segment = 'ElasticQueueData';

    private static $menu_priority = 0.4;

    private static $managed_models = [
        ElasticQueuedData::class
    ];

    public function getEditForm($id = null, $fields = null)
    {
        $form = parent::getEditForm($id, $fields);
        // get grid field
        $gridField = $form->Fields()->dataFieldByName($this->sanitiseClassName($this->modelClass));
        $gridFieldConfig = $gridField->getConfig();

        // remove add new button
        $gridFieldConfig->removeComponentsByType(GridFieldAddNewButton::class);

        return $form;
    }
}
