<?php

namespace App\Model;

use SilverStripe\Assets\Image;

class InventoryItem extends \SilverStripe\ORM\DataObject
{
    private static $db = [
        'Title' => 'Varchar',
        'SKU' => 'Varchar',
        'Type' => 'Varchar',
        'Description' => 'Varchar'
    ];

    private static $has_one = [
        'Image' => Image::class,
        'UOM' => UnitOfMeasurement::class,
        'ItemCategory' => InventoryCategory::class,
        "Vendor" => Vendor::class
    ];

    private static $has_many = [
        'WarehouseStock' => WarehouseStock::class
    ];

    private static $summary_fields = [
        'ID' => 'ID',
        'Title' => 'Title',
        'SKU' => 'SKU',
        'UOM.Title' => 'UOM',
        'ItemCategory.Title' => 'Item Category',
    ];

}
