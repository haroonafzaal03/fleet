<?php

namespace App\Extensions;

use App\Model\Freight;
use App\Model\Trip;
use App\Model\Vehicle;
use SilverStripe\Assets\File;

/**
 * Class FileExtension
 * @see File
 */
class FileExtension extends \SilverStripe\ORM\DataExtension
{

    private static $has_one = [
        'Vehicle' => Vehicle::class,
        'Driver' => Vehicle::class,
        'Freight' => Freight::class,
        'Trip' => Trip::class,
    ];
}
