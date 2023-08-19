<?php

namespace App\Model;

use SilverStripe\Assets\File;
use SilverStripe\ORM\DataObject;

class Vehicle extends DataObject
{
    /** @var string */
    private static $table_name = 'Vehicle';

    /** @var string */
    private static $singular_name = 'Vehicle';

    /** @var string[] */
    private static $db = [
        'RegistrationNumber' => 'Varchar',
        'Make' => 'Varchar',
        'Model' => 'Varchar',
        'AxleSize' => 'Varchar',
        'BowserCapacity' => 'Varchar',
        'VehicleTracking' => 'Varchar',
        'BowserType' => 'Varchar',
        'LicenseExpiry' => 'Date',
        'IsOwned' => 'Boolean',
        'ExternalItemID' => 'Varchar'
    ];
    private static $summary_fields = [
        'ID' => 'ID',
        'RegistrationNumber' => 'Registration',
        'Make' => 'Make',
        'Model' => 'Model',
        'ExternalItemID' => 'Import Name',
        'IsOwned.Nice' => 'IsOwned',
    ];
    private static $has_one = [
        'Photo' => File::class
    ];

    private static $has_many = [
        'Documents' => File::class
    ];

}
