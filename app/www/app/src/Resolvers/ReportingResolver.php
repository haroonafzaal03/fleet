<?php

namespace App\Resolvers;

use App\GraphQL\ReadAllCustomersResolver;
use App\GraphQL\ReadAllProductsResolver;
use App\GraphQL\ReadAllTripsResolver;
use App\GraphQL\ReadAllVehiclesResolver;
use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use SilverStripe\Core\Injector\Injector;

class ReportingResolver
{
    public static function resolveReadAllTrips($context): Closure
    {
        return function ($list, array $args, array $context, ResolveInfo $info) {
            $provider = Injector::inst()->create(ReadAllTripsResolver::class);
            return $provider->resolve($list, $args, $context, $info);
        };
    }

    public static function resolveReadAllCustomers($context): Closure
    {
        return function ($list, array $args, array $context, ResolveInfo $info) {
            $provider = Injector::inst()->create(ReadAllCustomersResolver::class);
            return $provider->resolve($list, $args, $context, $info);
        };
    }

    public static function resolveReadAllProducts($context): Closure
    {
        return function ($list, array $args, array $context, ResolveInfo $info) {
            $provider = Injector::inst()->create(ReadAllProductsResolver::class);
            return $provider->resolve($list, $args, $context, $info);
        };
    }

    public static function resolveReadAllVehicle($context): Closure
    {
        return function ($list, array $args, array $context, ResolveInfo $info) {
            $provider = Injector::inst()->create(ReadAllVehiclesResolver::class);
            return $provider->resolve($list, $args, $context, $info);
        };
    }
}
