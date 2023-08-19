<?php

namespace App\Model;

use SilverStripe\Assets\File;
use SilverStripe\Security\Member;

class Customer extends Member
{
    /** @var string */
    private static $table_name = 'Customer';

    /** @var string */
    private static $singular_name = 'Customer';

    /** @var string[] */
    private static $db = [
        'NTN' => 'Varchar',
        'STN' => 'Varchar',
        'ContactDetails' => 'Varchar',
        'ExpiryAlert' => 'Varchar',
        'LicenseExpiry' => 'Date',
        'CustomerProduct' => 'Varchar',
        'CustomerParty' => 'Varchar',
        'FreightCharges' => 'Double',
        'ExternalItemID' => 'Varchar'
    ];

    private static $has_one = [
        'Photo' => File::class
    ];

    private static $has_many = [
        'Documents' => File::class,
    ];

}
