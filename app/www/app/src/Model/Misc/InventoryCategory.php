<?php

namespace App\Model;

use SilverStripe\ORM\DataObject;

class InventoryCategory extends DataObject
{
    private static $db = [
        'Name' => 'Varchar',
        'Description' => 'Varchar',
    ];

    private static $has_one = [
        "UnitOfMeasurement" => UnitOfMeasurement::class
    ];

}
