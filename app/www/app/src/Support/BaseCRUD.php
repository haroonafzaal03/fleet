<?php

namespace App\Support\Service;

use App\Support\Traits\CRUDTrait;
use Exception;
use SilverStripe\Core\Config\Configurable;
use SilverStripe\Core\Injector\Injectable;
use SilverStripe\ORM\DataList;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\ValidationException;
use SilverStripe\Security\Member;
use SilverStripe\Security\Security;

/**
 * Class BaseCRUDService
 * @property  Member $member
 *
 */
class BaseCRUD
{
    use Configurable;
    use Injectable;
    use CRUDTrait;

    /** @var Member */
    protected $member;


    /**
     * VenueCRUDService constructor.
     * @param Member|null $member
     * @throws Exception
     */
    public function __construct(Member $member = null)
    {
//        if (!$member) {
//            $member = Security::getCurrentUser();
//        }
//        $this->setMember($member);


    }


    /**
     * @return Member
     */
    public function getMember()
    {
        return $this->member;
    }

    /**
     * @param Member $member
     */
    public function setMember(Member $member)
    {
        $this->member = $member;
    }

    /**
     * @param $name
     * example:
     * $name = createOrUpdateTable()
     * $name = removeTable()
     * $name = getAllTable()
     * $name = getOneTable()
     * @param array $arguments
     * @return DataList|void|DataObject
     * @throws ValidationException
     */
    public function __call($name, array $arguments)
    {
        $methodCalled = $name;
        if (str_contains($methodCalled, 'getAll')) {

            $className = $this->extractClassNameFromMethod($methodCalled, 'getAll');
            return $this->handleGetAll($className, $arguments);

        } elseif (str_contains($methodCalled, 'getOne')) {

            $className = $this->extractClassNameFromMethod($methodCalled, 'getOne');
            return $this->handleGetOne($className, $arguments);

        } elseif (str_contains($methodCalled, 'createOrUpdate')) {

            $className = $this->extractClassNameFromMethod($methodCalled, 'createOrUpdate');
            return $this->handleCreateOrUpdate($className, $arguments);

        } elseif (str_contains($methodCalled, 'remove')) {

            $className = $this->extractClassNameFromMethod($methodCalled, 'remove');
            $this->handleRemove($className, $arguments);
        } elseif (str_contains($methodCalled, 'sort')) {

            $className = $this->extractClassNameFromMethod($methodCalled, 'sort');
            $this->handleSort($className, $arguments);
        } else {
            // exception throw method not found
            throw new Exception("Method Not Found!");
        }
    }

    /**
     * @param $methodName
     * @param $identifier
     * @return mixed|void
     */
    protected function extractClassNameFromMethod($methodName, $identifier)
    {
        $methodName = ucfirst(str_replace($identifier, '', $methodName));
        if (isset($this->allowedClasses[$methodName])) {
            return $this->allowedClasses[$methodName];
        }

        // TODO: throw exception
    }

    /**
     * @param $class
     * @param array $arguments
     * @return DataList
     * @throws Exception
     */
    protected function handleGetAll($class, array $arguments)
    {
        $this->onBeforeGetAllAction($class, $arguments);
        if (!$this->validateViewPermission($class, $this->member)) {
            throw new Exception("Permission denied");
        }

        $list = self::getAll($class, $arguments);

        $this->onAfterAction($list, $class, $arguments);
        return $list;
    }

    /**
     * @param $class
     * @param $arguments
     */
    protected function onBeforeGetAllAction(&$class, &$arguments)
    {
//        $arguments = array_merge($defaultArgument, $arguments[0] ?? []);
//        // override in respective service classes to provide custom functionality
    }

    /**
     * @param $list
     * @param $class
     * @param $arguments
     */
    protected function onAfterAction($list, $class, $arguments)
    {
        // override in respective service classes to provide custom functionality
    }

    /**
     * @param $class
     * @param array $arguments
     * @return DataList
     * @throws Exception
     */
    protected function handleGetOne($class, array $arguments)
    {
        $this->onBeforeGetOneAction($class, $arguments);
//        if (!$this->validateViewPermission($class, $this->member)) {
//            throw new Exception("Permission denied");
//        }
        $list = self::getAll($class, $arguments);

        $this->onAfterAction($list, $class, $arguments);
        return $list;
    }

    /**
     * @param $class
     * @param $arguments
     */
    protected function onBeforeGetOneAction(&$class, &$arguments)
    {

//        $defaultArgument['StoreID'] = $this->storeID;
//        $arguments = array_merge($defaultArgument, $arguments[0] ?? []);

        // override in respective service classes to provide custom functionality
    }

    /**
     * @param $class
     * @param array $arguments
     * @return DataObject
     * @throws ValidationException
     * @throws Exception
     */
    protected function handleCreateOrUpdate($class, array $arguments)
    {
        $this->onBeforeCreateUpdateAction($class, $arguments);
        if (!$this->validateUpdatePermission($class, $this->member) || !$this->validateCreatePermission($class, $this->member)) {
            throw new Exception("Permission denied");
        }
//        print_r($arguments);die();
        $list = self::createOrUpdate($class, $arguments);

        $this->onAfterAction($list, $class, $arguments);
        return $list;
    }

    /**
     * @param $class
     * @param $arguments
     */
    protected function onBeforeCreateUpdateAction(&$class, &$arguments)
    {
        // override in respective service classes to provide custom functionality
    }

    /**
     * @param $class
     * @param array $arguments
     * @throws ValidationException
     * @throws Exception
     */
    protected function handleRemove($class, array $arguments)
    {
        $this->onBeforeRemoveAction($class, $arguments);
        if (!$this->validateDeletePermission($class, $this->member)) {
            throw new Exception("Permission denied");
        }
        self::remove($class, $arguments['ID']);

    }

    /**
     * @param $class
     * @param $arguments
     */
    protected function onBeforeRemoveAction(&$class, &$arguments)
    {
        // override in respective service classes to provide custom functionality
    }

    /**
     * @param $class
     * @param array $arguments
     * @return DataObject|null
     * @throws ValidationException
     */
    protected function handleSort($class, array $arguments)
    {
        $this->onBeforeAction($class, $arguments);
        if (!$this->validateDeletePermission($class, $this->member)) {
            throw new Exception("Permission denied");
        }
        $list = self::sortOrder($class, $arguments);

        return $list;

    }

    /**
     * @param $class
     * @param $arguments
     */
    protected function onBeforeAction(&$class, &$arguments)
    {
//        $defaultArgument['StoreID'] = $this->storeID;
//        $arguments = array_merge($defaultArgument, $arguments[0] ?? []);

        // override in respective service classes to provide custom functionality
    }

}
