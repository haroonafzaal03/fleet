<?php

namespace App\Model;

class WarehouseStockLogs extends \SilverStripe\ORM\DataObject
{
    private static $db = [
        'QTY' => 'Int', // Use QTY to indicate stock, positive shows an increase in stock, negative shows a decrease
    ];

    private static $has_one = [
        "InventoryItem" => InventoryItem::class,
        "Warehouse" => Warehouse::class,
        "PurchaseOrderItem" => PurchaseOrderItems::class,
        "DeliveryOrderItem" => DeliveryOrderItems::class
    ];
}
