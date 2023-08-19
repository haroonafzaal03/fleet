<?php

namespace App\Model;

class WarehouseStock extends \SilverStripe\ORM\DataObject
{
    private static $db = [
        'CalculatedStock' => 'Int'
    ];

    private static $has_one = [
        "InventoryItem" => InventoryItem::class,
        "Warehouse" => Warehouse::class
    ];

    private static $summary_fields = [
        'ID' => 'ID',
        'CalculatedStock' => 'QTY',
        "InventoryItem.Title" => 'Inventory Item',
        "Warehouse.Title" => 'Warehouse'
    ];
}
