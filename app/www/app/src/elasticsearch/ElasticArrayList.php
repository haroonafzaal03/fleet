<?php

namespace TND\ElasticSearch;

use TND\ElasticSearch\Queries\ElasticSelect;
use SilverStripe\ORM\ArrayList;

/**
 * Class ElasticArrayList
 * @package TND\ElasticSearch
 */
class ElasticArrayList extends ArrayList
{
    protected $hits = 0;

    public function count()
    {
        return $this->getHits();
    }

    public function setHits($hits)
    {
        $this->hits = $hits;
    }

    public function getHits()
    {
        return $this->hits;
    }

    public function limit($length, $offset = 0)
    {
        /**
         * Apply limit and offset while querying Elastic server @see ElasticSelect
         */
        return $this;
    }
}
