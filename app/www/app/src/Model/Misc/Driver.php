<?php

namespace App\Model;

use SilverStripe\Assets\File;
use SilverStripe\Security\Member;

class Driver extends Member
{
    /** @var string */
    private static $table_name = 'Driver';

    /** @var string */
    private static $singular_name = 'Driver';

    /** @var string[] */
    private static $db = [
        'License' => 'Varchar',
        'LicenseExpiry' => 'Date',
    ];

    private static $has_one = [
        'Photo' => File::class
    ];

    private static $has_many = [
        'Documents' => File::class,
    ];

}
