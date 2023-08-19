<?php

namespace App\Model;

use SilverStripe\ORM\DataObject;

class Warehouse extends DataObject
{
    private static $db = [
        'Name' => 'Varchar'
    ];

    private static $has_one = [
        "Location" => Location::class
    ];

    private static $has_many = [
        'WarehouseStock' => WarehouseStock::class
    ];
}
