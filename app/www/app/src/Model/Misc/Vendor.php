<?php

namespace App\Model;

use SilverStripe\Assets\File;
use SilverStripe\Security\Member;

class Vendor extends Member
{
    /** @var string */
    private static $table_name = 'Vendor';

    /** @var string */
    private static $singular_name = 'Vendor';

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

    ];

    private static $has_one = [
        'Photo' => File::class
    ];

    private static $has_many = [
        'Documents' => File::class,
        'PurchaseOrder' => PurchaseOrder::class,
        'DeliveryOrder' => DeliveryOrder::class,
        'InventoryItems' => InventoryItem::class,
    ];

}
