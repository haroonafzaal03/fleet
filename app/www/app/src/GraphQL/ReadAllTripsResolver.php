<?php

namespace App\GraphQL;

use App\Elastic\TripElasticIndex;
use GraphQL\Type\Definition\ResolveInfo;
use SilverStripe\GraphQL\Schema\Plugin\PaginationPlugin;
use SilverStripe\ORM\ArrayList;
use SilverStripe\View\ArrayData;
use TND\ElasticSearch\Decorators\ElasticArrayListDecorator;
use TND\ElasticSearch\Queries\ElasticSelect;

/**
 * Class ReadAllTripsResolver
 * @package App\GraphQL
 */
class ReadAllTripsResolver
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

        $mustFilters = [];
        $query = ElasticSelect::create()
            ->setSelect("*")
            ->setFrom(TripElasticIndex::class)
            ->setLimit($limit)
            ->setOffset($offset)
            ->setDecorator(ElasticArrayListDecorator::class);

        if (isset($args['Filters']['StartDate']) && !empty($args['Filters']['StartDate'])) {
//            $date = date("Y-m-d", strtotime($args['Filters']['StartDate']));
//            $query->addMustRangeFilter('StartDate__lte', $date);
        }

        if (isset($args['Filters']['EndDate']) && !empty($args['Filters']['EndDate'])) {
//            $query->addMustRangeFilter('EndDate__gte', $args['Filters']['EndDate']);
        }

        if (isset($args['Filters']['VehicleID']) && !empty($args['Filters']['VehicleID'])) {
            $mustFilters['Vehicle.ID'] = $args['Filters']['VehicleID'];
        }

        if (isset($args['Filters']['CustomerID']) && !empty($args['Filters']['CustomerID'])) {
            $mustFilters['Customer.ID'] = $args['Filters']['CustomerID'];
        }

        if (isset($args['Filters']['ProductID']) && !empty($args['Filters']['ProductID'])) {
            $mustFilters['Product.ID'] = $args['Filters']['ProductID'];
        }

        $query->setMustFilters($mustFilters);
        $list = $query->execute();

        /**
         * Set stock to use correct data
         */
        $ElasticList = ArrayList::create();
        foreach ($list as $product) {
            $ElasticList->push(ArrayData::create($product->toMap()));
        }

        return PaginationPlugin::createPaginationResult($list->getHits(), $ElasticList, $limit, $offset);
    }
}
