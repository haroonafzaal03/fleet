<?php

namespace App\Model;

use SilverStripe\Assets\File;
use SilverStripe\ORM\DataObject;
use SilverStripe\Security\Member;

class Freight extends DataObject
{
    /** @var string */
    private static $table_name = 'Freight';

    /** @var string */
    private static $singular_name = 'Freight';

    /** @var string[] */
    private static $db = [
        'LicenseExpiry' => 'Date',
    ];

    private static $has_one = [
        'Photo' => File::class,
        'Receiving' => File::class
    ];

    private static $has_many = [
        'Documents' => File::class
    ];
    private static $summary_fields  = [
        'ID' => 'ID',
        'LicenseExpiry' => 'License Expiry',

    ];

}
