<?php

namespace TND\ElasticSearch\Decorators;

use TND\ElasticSearch\Decorator;
use TND\ElasticSearch\ElasticArrayList;
use TND\ElasticSearch\Queries\ElasticSelect;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\ORM\ArrayList;
use SilverStripe\View\ArrayData;

/**
 * Class ElasticArrayListDecorator
 * @package TND\ElasticSearch\Decorators
 */
class ElasticArrayListDecorator extends Decorator
{
    public function decorate($context, $response, $totalHits, $aggs = null)
    {
        $result = ElasticArrayList::create();
        foreach ($response as $document) {
            $result->push(
                $this->convertDocument($context, $document)
            );
        }

        $result->setHits($totalHits);

        // TODO: handle AGGS list
        return $result;
    }

    /**
     * @param ElasticSelect $context
     * @param array $document
     * @return ArrayData
     */
    public function convertDocument($context, $document)
    {
        foreach ($document as $key => $data) {

            if ($context->getFrom()->isExcluded($key)) {
                continue;
            }

            if (is_array($data)) {
                if (empty($data)) {
                    $document[$key] = ArrayList::create();

                } elseif (is_array(current($data))) {

                    if (sizeof($data) > 1) {
                        /**
                         * array of arrays - has_many, many_many relation
                         */
                        $document[$key] = $this->handleArrayOfArrays($context, $data);
                    } else {
                        $document[$key] = $this->handleObject($context, $document, $key, current($data));
                        // handle a special case where has_many relation has only one record, failing the handleObject method
                        if ($document[$key] == NULL) {
                            $document[$key] = $this->handleArrayOfArrays($context, $data);
                        }
                    }

                } else {
                    /**
                     * object - has_one relation
                     */
                    $document[$key] = $this->handleObject($context, $document, $key, $data);
                }
            }
        }

        /**
         * convert document to ArrayData (object/node)
         */

        $dataObjectClass = $context->getFrom()->getSource();
        if ($dataObjectClass) {
            return Injector::inst()->create($dataObjectClass, $document);
        }
        return ArrayData::create($document);
    }

    /**
     * @param ElasticSelect $context
     * @param $data
     * @return ArrayList
     */
    protected function handleArrayOfArrays($context, $data)
    {
        /**
         * array of arrays - has_many, many_many relation
         */
        $list = ArrayList::create();
        foreach ($data as $subItem) {
            $list->push(ArrayData::create($subItem));
        }

        return $list;
    }

    /**
     * @param ElasticSelect $context
     * @param array $document
     * @param string $key
     * @param array $data
     * @return ArrayData
     */
    protected function handleObject($context, $document, $key, $data)
    {
        return ArrayData::create($data);
    }
}
