<?php

namespace App\Admin;

use App\Model\DeliveryOrder;
use App\Model\InventoryCategory;
use App\Model\InventoryItem;
use App\Model\Location;
use App\Model\PurchaseOrder;
use App\Model\UnitOfMeasurement;
use App\Model\Vendor;
use App\Model\Warehouse;
use App\Model\WarehouseStock;
use SilverStripe\Admin\ModelAdmin;

/**
 * Class MiscAdmin
 *
 */
class InventoryAdmin extends ModelAdmin
{

    private static $menu_title = 'Inventory';

    private static $url_segment = 'inventory';

    private static $menu_priority = 0.6;

    private static $managed_models = [
        PurchaseOrder::class,
        DeliveryOrder::class,
        InventoryItem::class,
        WarehouseStock::class,
        Warehouse::class,
        Vendor::class
    ];

    public function getEditForm($id = null, $fields = null)
    {
        $form = parent::getEditForm($id, $fields);
        return $form;
    }
}
