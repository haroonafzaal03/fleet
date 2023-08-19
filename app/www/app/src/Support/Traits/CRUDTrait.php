<?php

namespace App\Support\Traits;

use Exception;
use SilverStripe\ORM\DataList;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\ValidationException;
use SilverStripe\Versioned\Versioned;

trait CRUDTrait
{

    /**
     * @param $className
     * @param array $data
     * @param null $ID
     * @return DataObject|null
     * @throws ValidationException
     * @throws Exception
     */
    public function createOrUpdate($className, array $data, $ID = null)
    {
        $record = null;
        /**
         * ID = -1 in-case a new item is added
         */
        if ($ID && $ID > 0) {
            if (!$record = $this->getOne($className, $ID)) {
                throw new Exception("Not found in DB");
            }
        } else {
            $record = $className::create();
        }

        foreach ($data as $index => $field) {
            $record->{$index} = $field;
        }
        $recordID = $record->write();

        if ($record->hasExtension(Versioned::class)) {
            $record->publishSingle();
        }
        return $record;
    }

    /**
     * @param $className
     * @param null $ID
     * @return bool
     */
    public function remove($className, $ID)
    {
        /**
         * @var Versioned|DataObject $classSNG
         */
        $classSNG = $className::singleton();
        $record = $this->getOne($className, $ID);

        if ($classSNG->hasExtension(Versioned::class)) {
            $record->doUnpublish();
            $record->deleteFromStage('Stage');
        } else {
            $record->delete();
        }
        return true;
    }

    /**
     * @param $className
     * @param $ID
     * @return DataObject|null
     */
    public function getOne($className, $ID)
    {
        return $this->getAll(
            $className,
            [
                'ID' => $ID
            ]
        )->first();
    }

    /**
     * @param $className
     * @param array|null $where
     * @return DataList
     */
    public function getAll($className, array $where = null)
    {
        $records = DataObject::get($className);
        if ($where) {
            $records = $records->filter($where);
        }


        return $records;
    }

    /**
     * @param $className
     * @param $member
     * @return boolean
     */
    public function validateCreatePermission($className, $member): bool
    {
        $checkPermission = $className::singleton();
        if ($checkPermission->canCreate($member)) {
            return true;
        }
        return false;
    }

    /**
     * @param $className
     * @param $member
     * @return boolean
     */
    public function validateUpdatePermission($className, $member): bool
    {
        $checkPermission = $className::singleton();
        if ($checkPermission->canEdit($member)) {
            return true;
        }
        return false;
    }

    /**
     * @param $className
     * @param $member
     * @return boolean
     */
    public function validateDeletePermission($className, $member): bool
    {
        $checkPermission = $className::singleton();
        if ($checkPermission->canDelete($member)) {
            return true;
        }
        return false;
    }

    /**
     * @param $className
     * @param $member
     * @return boolean
     */
    public function validateViewPermission($className, $member): bool
    {
        $checkPermission = $className::singleton();
        if (($checkPermission->canView($member))) {
            return true;
        }
        return false;
    }

//    public function sortOrder($className, $data)
//    {
//        /**
//         * ID = -1 in-case a new item is added
//         */
//        $obj = DataObject::get($className);
//        $ID = $data['ID'] ?? null;
//        if ($ID && $ID > 0) {
//            if (!$obj->filter(['ID' => $ID, 'StoreID' => $this->storeID])->exists()) {
//                throw new Exception("Category Not available for ID = " . $ID);
//            }
//            $record = $obj->byID($ID);
//            unset($data['ID']);
//            foreach ($data as $index => $field) {
//                $record->{$index} = $field;
//            }
//            $recordID = $record->write();
//
//            if ($record->hasExtension(Versioned::class)) {
//                $record->publishSingle();
//            }
//            return $record;
//        }
//    }
}
