<?php

namespace TND\ElasticSearch\Decorators;

use TND\ElasticSearch\Queries\ElasticSelect;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\View\ArrayData;
use TractorCow\Fluent\State\FluentState;

/**
 * Class GraphQLDecorator
 * @package TND\ElasticSearch\Decorators
 */
class GraphQLDecorator extends ElasticArrayListDecorator
{

    public function decorate($context, $response, $totalHits, $aggs = null)
    {
        $localeFields = $context->getFrom()->getLocaleFields();

        if (!empty($localeFields)) {
            /*
             * Handle locale fields
             * Change locale key name to graphQL friendly name
             */
            $currentLocale = FluentState::singleton()->getLocale();
            if ($currentLocale) {
                foreach ($response as $key => $document) {

                    // replace values with correct locale
                    foreach ($localeFields as $field) {
                        $localisedFieldName = $field . '__' . $currentLocale;
                        if (
                            isset($document[$field]) &&
                            isset($document[$localisedFieldName])
                        ) {
                            $response[$key][$field] = $response[$key][$localisedFieldName];
                        }
                    }
                }
            }
        }

        return parent::decorate($context, $response, $totalHits);
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
        /**
         * Cast into has_one type e.g. Member, Product etc
         */
        if ($context instanceof ElasticSelect) {
            /**
             * get index and linked DataObject
             */
            $dataObjectClass = $context->getFrom()->getSource();
            if ($dataObjectClass) {
                $hasOneRelations = Injector::inst()->get($dataObjectClass)->hasOne();
                if (!empty($hasOneRelations) && isset($hasOneRelations[$key])) {

                    return Injector::inst()->createWithArgs($hasOneRelations[$key], [$data]);
                }
            }
        }

        return null;
    }
}
