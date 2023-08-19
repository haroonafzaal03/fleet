<?php

namespace App\Model;

use SilverStripe\Assets\File;

class DeliveryOrder extends \SilverStripe\ORM\DataObject
{
    private static $db = [
        'Name' => 'Varchar',
        'Description' => 'Varchar',
        'Notes' => 'Varchar',
        'Dated' => 'Date'
    ];

    private static $has_one = [
        "File" => File::class,
        "Warehouse" => Warehouse::class,
        "Vendor" => Vendor::class
    ];

    private static $has_many = [
        "DeliveryOrderItems" => DeliveryOrderItems::class
    ];

    private static $summary_fields = [
        'ID' => 'ID',
        'Name' => 'Name',
        'Description' => 'Description',
        'Notes' => 'Notes',
        'Dated' => 'Dated',
    ];

}
