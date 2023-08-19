<?php

namespace App\Support\Service;

use App\Model\Customer;
use App\Model\Location;
use App\Model\Product;
use App\Model\Trip;
use App\Model\Vehicle;
use App\Model\Vendor;

class ImportTripService extends BaseCRUD
{
    /**
     * @var array
     */
    protected array $allowedClasses = [
        'Trip' => Trip::class,
        'Customer' => Customer::class,
        'Vendor' => Vendor::class,
        'Vehicle' => Vehicle::class,
        'Product' => Product::class,
        'Location' => location::class,
    ];

}
