<?php

namespace App\Support\Service;

use App\Model\Customer;
use App\Model\Location;
use App\Model\Product;
use App\Model\Trip;
use App\Model\Vehicle;
use SilverStripe\Assets\File;
use SilverStripe\Control\Director;
use SilverStripe\Core\Config\Configurable;
use SilverStripe\Core\Injector\Injectable;
use SilverStripe\Dev\CSVParser;


class CSVImportReader
{
    use Configurable;
    use Injectable;

    public $columnMap = [
        'Sr.#' => 'ExternalItemID',
        'Tanker No.' => 'VehicleID', // ok
        'Ownership Status' => 'OwnershipStatus',
        'Commision  To/From' => 'CommissionToForm',
        'Receivable' => 'Receivable',
        'Sending Party' => 'SendingPartyID', // ok
        'Receiving Party' => 'ReceivingPartyID', // ok
        'Product' => 'ProductID', //ok
        'Destination From' => 'OriginLocationID', //ok
        'Destination To' => 'DestinationLocationID', //ok
        'Loading Date' => 'LoadDate',
        'UnLoad Date' => 'UnloadDate',
        'Trip Days' => 'TripDays',
        'Weight' => 'LoadWeight',
        'Unloading Weight' => 'UnloadWeight',
        'Shortages' => 'CalculatedShortage',
        'DC #' => 'DeliveryChallanNumber',
        'Rate Basis' => 'RateBasis',
        'Total Freight' => 'TotalFreight',
        'HSD' => 'HSD',
        'Mobil Oil' => 'MobilOil',
        'Tyres' => 'Tyres',
        'Spare parts' => 'SpareParts',
        'Cash Advance/Salary' => 'CashAdvanceSalary',
        'Other Expense' => 'OtherExpense'
    ];

    public function readCSV(File $file)
    {
        $assetsFolder = Director::publicFolder() . '/assets/';
        $filePath = $assetsFolder . $file->FileFilename;
        echo $filePath;

        if ($file && Director::fileExists($filePath)) {

            // load file
            $csvRows = new CSVParser($filePath);
//            print_r($csvRows);


            foreach ($csvRows as $index => $csvRow) {
                //print_r($columnMap[$index]);
                $this->processRow($csvRow);
//                die();
            }

        }
//        die();
        // Set the required headers

    }

    public function processRow($row)
    {
        $data = [];
        $service = ImportTripService::singleton();
        foreach ($row as $index => $item) {

            echo PHP_EOL . "============================" . PHP_EOL;
            echo $this->columnMap[ucfirst(trim(ltrim($index)))] ?? "WWAJA " . $index;


            if ($this->columnMap[ucfirst(trim(ltrim($index)))] == "VehicleID") {
                $vehicle = null;
                $vehicle = $service->getAll(Vehicle::class, ['ExternalItemID' => $item])->first();
                $vehicle = $service->createOrUpdate(Vehicle::class, ['ExternalItemID' => $item], $vehicle->ID ?? 0);
                $data[$this->columnMap[ucfirst(trim(ltrim($index)))]] = $vehicle->ID ?? -1;
            } else if ($this->columnMap[ucfirst(trim(ltrim($index)))] == "SendingPartyID") {
                $vehicle = null;
                $vehicle = $service->getAll(Customer::class, ['ExternalItemID' => $item])->first();
                $vehicle = $service->createOrUpdate(Customer::class, ['ExternalItemID' => $item], $vehicle->ID ?? 0);
                $data[$this->columnMap[ucfirst(trim(ltrim($index)))]] = $vehicle->ID ?? -1;
            } else if ($this->columnMap[ucfirst(trim(ltrim($index)))] == "ReceivingPartyID") {
                $vehicle = null;
                $vehicle = $service->getAll(Customer::class, ['ExternalItemID' => $item])->first();
                $vehicle = $service->createOrUpdate(Customer::class, ['ExternalItemID' => $item], $vehicle->ID ?? 0);
                $data[$this->columnMap[ucfirst(trim(ltrim($index)))]] = $vehicle->ID ?? -1;
            } else if ($this->columnMap[ucfirst(trim(ltrim($index)))] == "OriginLocationID") {
                $vehicle = null;

                $vehicle = $service->getAll(Location::class, ['Name' => $item])->first();
                $vehicle = $service->createOrUpdate(Location::class, ['Name' => $item], $vehicle->ID ?? 0);
                $data[$this->columnMap[ucfirst(trim(ltrim($index)))]] = $vehicle->ID ?? -1;
            } else if ($this->columnMap[ucfirst(trim(ltrim($index)))] == "DestinationLocationID") {
                $vehicle = null;
                $vehicle = $service->getAll(Location::class, ['Name' => $item])->first();
                $vehicle = $service->createOrUpdate(Location::class, ['Name' => $item], $vehicle->ID ?? 0);
                $data[$this->columnMap[ucfirst(trim(ltrim($index)))]] = $vehicle->ID ?? -1;
            } else if ($this->columnMap[ucfirst(trim(ltrim($index)))] == "ProductID") {
                $vehicle = null;
                $vehicle = $service->getAll(Product::class, ['Name' => $item])->first();
                $vehicle = $service->createOrUpdate(Product::class, ['Name' => $item], $vehicle->ID ?? 0);
                $data[$this->columnMap[ucfirst(trim(ltrim($index)))]] = $vehicle->ID ?? -1;
            } else {
                $data[$this->columnMap[ucfirst(trim(ltrim($index)))]] = $item;
            }
            if ($this->columnMap[ucfirst(trim(ltrim($index)))] == 'UnloadDate' && $item) {
                $data[$this->columnMap[ucfirst(trim(ltrim($index)))]] = date('Y-m-d', strtotime($item));
            }
            if ($this->columnMap[ucfirst(trim(ltrim($index)))] == 'LoadDate' && $item) {
                $data[$this->columnMap[ucfirst(trim(ltrim($index)))]] = date('Y-m-d', strtotime($item));
            }


            echo PHP_EOL . $item;
            echo PHP_EOL . "============================" . PHP_EOL;
        }
        $service->createOrUpdate(Trip::class, $data);
    }
}
