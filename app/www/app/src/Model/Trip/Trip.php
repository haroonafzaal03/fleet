<?php

namespace App\Model;

use SilverStripe\Assets\File;
use SilverStripe\ORM\DataObject;

class Trip extends DataObject
{
    /** @var string */
    private static $singular_name = 'Trip';

    /** @var string[] */
    private static $db = [
        'Status' => 'Enum(array("New", "Completed", "InProcess"), "New")',
        'LoadDate' => 'Date',
        'UnloadDate' => 'Date',
        'LoadWeight' => 'Decimal',
        'UnloadWeight' => 'Decimal',
        'CalculatedShortage' => 'Decimal',
        'DeliveryChallanNumber' => 'Varchar',
        'CommissionToForm' => 'Decimal',
        'ExternalItemID' => 'Varchar'
    ];

    private static $has_one = [
        'Vehicle' => Vehicle::class,
        'SendingParty' => Customer::class,
        'ReceivingParty' => Customer::class,
        'Product' => Product::class,
        'OriginLocation' => Location::class,
        'DestinationLocation' => Location::class,
        'LoadUOM' => UnitOfMeasurement::class,
        'UnloadUOM' => UnitOfMeasurement::class
    ];

    private static $has_many = [
        'Documents' => File::class,
        'DeliveryChalan' => File::class,
    ];

    private static $summary_fields = [
        'ID' => 'ID',
        'LoadDate' => 'Load Date',
        'UnloadDate' => 'Unload Date',
        'Status' => 'Status',
        'Vehicle.RegistrationNumber' => 'Vehicle',
        'SendingParty.Title' => 'Sending Party',
        'ReceivingParty.Title' => 'Receiving Party',
        'Product.Title' => 'Product',
        'OriginLocation.Title' => 'Origin Location',
        'DestinationLocation.Title' => 'Destination Location',
    ];

}
