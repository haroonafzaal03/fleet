<?php

namespace App\Admin;

use App\Model\Freight;
use App\Model\InventoryCategory;
use App\Model\Location;
use App\Model\UnitOfMeasurement;
use SilverStripe\Admin\ModelAdmin;

/**
 * Class MiscAdmin
 *
 */
class MiscAdmin extends ModelAdmin
{

    private static $menu_title = 'Miscellaneous';

    private static $url_segment = 'Miscellaneous';

    private static $menu_priority = 0.5;

    private static $managed_models = [
        Location::class,
        UnitOfMeasurement::class,
        InventoryCategory::class,
        Freight::class,
    ];

    public function getEditForm($id = null, $fields = null)
    {
        $form = parent::getEditForm($id, $fields);
        return $form;
    }
}
