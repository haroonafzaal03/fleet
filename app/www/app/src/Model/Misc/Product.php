<?php

namespace App\Model;

use SilverStripe\ORM\DataObject;

class Product extends DataObject
{
    private static $db = [
        'Name' => 'Varchar',
        'Description' => 'Varchar',
    ];

    private static $has_one = [
        "UnitOfMeasurement" => UnitOfMeasurement::class
    ];
    private static $summary_fields = [
        'ID'=> 'Sr#',
        'Name' => 'Name',
        'Description' => 'Description',
    ];
}
