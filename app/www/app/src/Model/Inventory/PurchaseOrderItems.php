<?php

namespace App\Model;

use App\Support\Inventory\InventoryProvider;
use SilverStripe\Assets\File;

class PurchaseOrderItems extends \SilverStripe\ORM\DataObject
{
    private static $db = [
        'QTY' => 'Int',
        'Notes' => 'Varchar',
    ];

    private static $has_one = [
        "PurchaseOrder" => PurchaseOrder::class,
        "InventoryItem" => InventoryItem::class
    ];

    public function onAfterWrite()
    {
        parent::onAfterWrite();

        InventoryProvider::singleton()->increaseInventory($this);
    }
}
