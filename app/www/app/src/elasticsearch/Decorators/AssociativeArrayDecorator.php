<?php

namespace TND\ElasticSearch\Decorators;

use TND\ElasticSearch\Decorator;

/**
 * Class AssociativeArrayDecorator
 * @package TND\ElasticSearch\Decorators
 */
class AssociativeArrayDecorator extends Decorator
{
    public function decorate($context, $response, $totalHits, $aggs = null)
    {
        return [
            'hits' => $totalHits,
            'data' => $response,
            'aggs' => $aggs
        ];
    }
}
