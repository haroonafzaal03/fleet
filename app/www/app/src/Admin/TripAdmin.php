<?php

namespace App\Admin;

use App\Model\Customer;
use App\Model\DeliveryOrder;
use App\Model\Driver;
use App\Model\InventoryCategory;
use App\Model\InventoryItem;
use App\Model\Location;
use App\Model\Product;
use App\Model\PurchaseOrder;
use App\Model\Trip;
use App\Model\UnitOfMeasurement;
use App\Model\Vehicle;
use App\Model\Warehouse;
use App\Model\WarehouseStock;
use SilverStripe\Admin\ModelAdmin;

/**
 * Class MiscAdmin
 *
 */
class TripAdmin extends ModelAdmin
{

    private static $menu_title = 'Trip';

    private static $url_segment = 'trip';

    private static $menu_priority = 0.7;

    private static $managed_models = [
        Trip::class,
        Customer::class,
        Vehicle::class,
        Driver::class,
        Product::class,
    ];

    public function getEditForm($id = null, $fields = null)
    {
        $form = parent::getEditForm($id, $fields);
        return $form;
    }
}
