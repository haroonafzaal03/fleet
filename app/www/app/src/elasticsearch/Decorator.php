<?php

namespace TND\ElasticSearch;

abstract class Decorator
{
    /**
     * @param $context
     * @param $response
     * @param $totalHits
     * @param $aggs
     * @return array
     */
    abstract public function decorate($context, $response, $totalHits, $aggs = null);
}
