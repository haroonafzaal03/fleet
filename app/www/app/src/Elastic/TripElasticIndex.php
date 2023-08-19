<?php

namespace App\Elastic;

use SilverStripe\Core\Flushable;
use TND\ElasticSearch\AbstractIndex;

class TripElasticIndex extends AbstractIndex implements Flushable
{

    /**
     * @inheritDoc
     */
    public static function flush()
    {
        // TODO: Implement flush() method.
    }
}
