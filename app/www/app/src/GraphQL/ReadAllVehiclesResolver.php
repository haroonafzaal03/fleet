<?php

namespace App\GraphQL;

use App\Model\Customer;
use App\Model\Vehicle;
use GraphQL\Type\Definition\ResolveInfo;
use SilverStripe\GraphQL\Schema\Plugin\PaginationPlugin;
use TND\ElasticSearch\Queries\ElasticSelect;

/**
 * Class ReadAllTripsResolver
 * @package App\GraphQL
 */
class ReadAllVehiclesResolver
{
    /**
     * @param mixed $object
     * @param array $args
     * @param mixed $context array(3) { ["currentUser"]=> NULL ["token"]=> NULL ["httpMethod"]=> string(4) "POST" }
     */
    public function resolve($object, $args, $context, ResolveInfo $info)
    {
        $limit = $args['limit'] ?? ElasticSelect::DEFAULT_LIMIT;
        $offset = $args['offset'] ?? ElasticSelect::DEFAULT_OFFSET;


        $ElasticList = Vehicle::get();

        if (isset($args['q']) && !empty($args['q'])) {
            $ElasticList = $ElasticList->filter([
                'FirstName:PartialMatch' => $args['q'],
                'Surname:PartialMatch' => $args['q'],
            ]);
        }

        return PaginationPlugin::createPaginationResult($ElasticList->count(), $ElasticList, $limit, $offset);
    }
}
