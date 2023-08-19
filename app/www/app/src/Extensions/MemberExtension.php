<?php

namespace App\Extensions;

class MemberExtension extends \SilverStripe\ORM\DataExtension
{

    private static $db = [
        'Address' => 'Varchar',
        'NTN_STN' => 'Varchar',
        'ContractDetails' => 'Varchar',
        'ContractExpiry' => 'Date',
        'ContractExpiryAlert' => 'Boolean',
        'CustomerProductDetails' => 'Varchar',
        'CustomerPartyDetails' => 'Varchar',
        'ProductWiseFreightList' => 'Varchar',
        'PricesChangeAlert' => 'Boolean',
        'FreightHistoryArchive' => 'Varchar',
        'CustomerLedge' => 'Varchar',
    ];

}
