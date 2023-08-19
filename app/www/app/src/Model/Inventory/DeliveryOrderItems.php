<?php

namespace App\Model;

use App\Support\Inventory\InventoryProvider;
use SilverStripe\Assets\File;

class DeliveryOrderItems extends \SilverStripe\ORM\DataObject
{
    private static $db = [
        'QTY' => 'Int',
        'Notes' => 'Varchar',
    ];

    private static $has_one = [
        "DeliveryOrder" => DeliveryOrder::class,
        "InventoryItem" => InventoryItem::class
    ];

    public function onAfterWrite()
    {
        parent::onAfterWrite();

        InventoryProvider::singleton()->decreaseInventory($this);
    }
}
