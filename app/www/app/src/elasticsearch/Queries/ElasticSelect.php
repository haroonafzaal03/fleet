<?php

namespace TND\ElasticSearch\Queries;

use TND\ElasticSearch\AbstractIndex;
use TND\ElasticSearch\Decorator;
use TND\ElasticSearch\ElasticArrayList;
use SilverStripe\Core\ClassInfo;
use SilverStripe\Core\Config\Configurable;
use SilverStripe\Core\Extensible;
use SilverStripe\Core\Injector\Injectable;
use SilverStripe\Core\Injector\Injector;

/**
 * Class ElasticSelect
 * @package TND\ElasticSearch\Queries
 */
class ElasticSelect
{
    use Configurable;
    use Injectable;
    use Extensible;

    public const DEFAULT_OFFSET = 0;
    public const DEFAULT_LIMIT = 100;

    protected $decorator = null;

    /**
     * @var ElasticQueryBuilder
     */
    protected $queryBuilder;

    public function __construct()
    {
        $this->queryBuilder = Injector::inst()->create(ElasticQueryBuilder::class);
    }

    /**
     * @return null|Decorator
     */
    public function getDecorator()
    {
        return $this->decorator;
    }

    /**
     * @param string $decorator
     * @return $this
     * @throws \ReflectionException
     */
    public function setDecorator($decorator)
    {
        if (
            ClassInfo::exists($decorator) &&
            (new \ReflectionClass($decorator))->isSubclassOf(Decorator::class)
        ) {
            $this->decorator = Injector::inst()->get($decorator);
        } else {
            throw new \InvalidArgumentException('Decorator needs to be a subclass of ' . Decorator::class);
        }

        return $this;
    }

    /**
     * @return array|string
     */
    public function getSelect(): array
    {
        return $this->queryBuilder->getSelect();
    }

    /**
     * @param array|string $select
     * @return $this
     */
    public function setSelect($select)
    {
        $this->queryBuilder->setSelect($select);
        return $this;
    }

    /**
     * @return AbstractIndex
     */
    public function getFrom()
    {
        return $this->queryBuilder->getFrom();
    }

    /**
     * @param $from
     * @return $this
     */
    public function setFrom($from)
    {
        if (ClassInfo::exists($from)) {
            $index = Injector::inst()->get($from);
            if ($index instanceof AbstractIndex) {

                $this->queryBuilder->setFrom($index);
                return $this;
            } else {
                throw new \InvalidArgumentException(sprintf("Class `%s` needs to be a sub class of %s", $from, AbstractIndex::class));
            }
        } else {
            throw new \InvalidArgumentException(sprintf("Class `%s` not found", $from));
        }
    }

    /**
     * @return array
     */
    public function getMustFilters(): array
    {
        return $this->queryBuilder->getMustFilters();
    }

    /**
     * @param array $mustFilters
     * @return $this
     */
    public function setMustFilters(array $mustFilters)
    {
        $this->queryBuilder->setMustFilters($mustFilters);
        return $this;
    }

    /**
     * @param $field
     * @param $data
     * @return $this
     */
    public function addMustFilter($field, $data)
    {
        $this->queryBuilder->addMustFilter($field, $data);
        return $this;
    }

    public function addMustNestedFilter($nestedSubQuery)
    {
        $this->queryBuilder->addMustNestedFilter($nestedSubQuery);
        return $this;
    }

    /**
     * @return array
     */
    public function getMustCustomFilters(): array
    {
        return $this->queryBuilder->getMustCustomFilters();
    }

    /**
     * @param array $mustFilters
     * @return $this
     */
    public function setMustCustomFilters(array $mustFilters)
    {
        $this->queryBuilder->setMustCustomFilters($mustFilters);
        return $this;
    }

    /**
     * @param $filter
     * @return $this
     */
    public function addMustCustomFilter($filter)
    {
        $this->queryBuilder->addMustCustomFilter($filter);
        return $this;
    }


    /**
     * @return array
     */
    public function getMustRangeFilters(): array
    {
        return $this->queryBuilder->getMustRangeFilters();
    }

    /**
     * @param array $mustRangeFilters
     * @return $this
     */
    public function setMustRangeFilters(array $mustRangeFilters)
    {
        $this->queryBuilder->setMustRangeFilters($mustRangeFilters);
        return $this;
    }

    /**
     * e.g. field = Column__gt, Column__lt
     * @param $field
     * @param $data
     * @return $this
     */
    public function addMustRangeFilter($field, $data)
    {
        $this->queryBuilder->addMustRangeFilter($field, $data);
        return $this;
    }

    /**
     * @param $field
     * @param $data
     * @return $this
     */
    public function addMustFuzzyFilter($field, $data)
    {
        $this->queryBuilder->addMustFuzzyFilter($field, $data);
        return $this;
    }


    /**
     * @return array
     */
    public function getShouldFilters(): array
    {
        return $this->queryBuilder->getShouldFilters();
    }

    /**
     * @param array $shouldFilters
     * @return $this
     */
    public function setShouldFilters(array $shouldFilters)
    {
        $this->queryBuilder->setShouldFilters($shouldFilters);
        return $this;
    }

    /**
     * @param $field
     * @param $data
     * @return $this
     */
    public function addShouldFilter($field, $data)
    {
        $this->queryBuilder->addShouldFilter($field, $data);
        return $this;
    }

    /**
     * @return array
     */
    public function getShouldRangeFilters(): array
    {
        return $this->queryBuilder->getShouldRangeFilters();
    }

    /**
     * @param array $filters
     * @return $this
     */
    public function setShouldRangeFilters(array $filters)
    {
        $this->queryBuilder->setShouldRangeFilters($filters);
        return $this;
    }

    /**
     * @param $field
     * @param $data
     * @return $this
     */
    public function addShouldRangeFilter($field, $data)
    {
        $this->queryBuilder->addShouldRangeFilter($field, $data);
        return $this;
    }

    /**
     * @return array
     */
    public function getMustNotFilters(): array
    {
        return $this->queryBuilder->getMustNotFilters();
    }

    /**
     * @param array $filters
     * @return $this
     */
    public function setMustNotFilters(array $filters)
    {
        $this->queryBuilder->setMustNotFilters($filters);
        return $this;
    }

    /**
     * @param $field
     * @param $data
     * @return $this
     */
    public function addMustNotFilter($field, $data)
    {
        $this->queryBuilder->addMustNotFilter($field, $data);
        return $this;
    }

    /**
     * @return array
     */
    public function getShouldMatchPrefixFilters(): array
    {
        return $this->queryBuilder->getShouldMatchPrefixFilters();
    }

    /**
     * @param array $filters
     * @return $this
     */
    public function setShouldMatchPrefixFilters(array $filters)
    {
        $this->queryBuilder->setShouldMatchPrefixFilters($filters);
        return $this;
    }

    /**
     * @param $field
     * @param $data
     * @return $this
     */
    public function addShouldMatchPrefixFilter($field, $data)
    {
        $this->queryBuilder->addShouldMatchPrefixFilters($field, $data);
        return $this;
    }

    public function getShouldMultiMatchPrefixFilters(): array
    {
        return $this->queryBuilder->getShouldMultiMatchFilters();
    }


    public function addShouldMultiMatchPrefixFilters(array $fields, $data)
    {
        $this->queryBuilder->addShouldMultiMatchFilters($fields, $data);
        return $this;
    }

    public function getNestedQueries()
    {
        return $this->queryBuilder->getNestedQueries();
    }

    public function setNestedQueries(array $nestedQueries)
    {
        $this->queryBuilder->setNestedQueries($nestedQueries);
        return $this;
    }

    /**
     * @param $field
     * @param $data
     * @return $this
     */
    public function addNestedQuery($field, $data)
    {
        $this->queryBuilder->addNestedQuery($field, $data);
        return $this;
    }

    public function getCustomQueries()
    {
        return $this->queryBuilder->getCustomQueries();
    }

    public function setCustomQueries(array $customQueries)
    {
        $this->queryBuilder->setCustomQueries($customQueries);
        return $this;
    }

    /**
     * @param $type
     * @param $query
     * @return $this
     */
    public function addCustomQuery($type, $query)
    {
        $this->queryBuilder->addCustomQuery($type, $query);
        return $this;
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->queryBuilder->getLimit();
    }

    /**
     * @param int $limit
     * @return $this
     */
    public function setLimit(int $limit)
    {
        $this->queryBuilder->setLimit($limit);
        return $this;
    }

    /**
     * @return int
     */
    public function getOffset(): int
    {
        return $this->queryBuilder->getOffset();
    }

    /**
     * @param int $offset
     * @return $this
     */
    public function setOffset(int $offset)
    {
        $this->queryBuilder->setOffset($offset);
        return $this;
    }

    /**
     * @return null
     */
    public function getSort()
    {
        return $this->queryBuilder->getSort();
    }

    /**
     * @param $sort
     * @return $this
     */
    public function setSort($sort)
    {
        $this->queryBuilder->setSort($sort);
        return $this;
    }

    /**
     * @return null
     */
    public function getCustomSortQuery()
    {
        return $this->queryBuilder->getCustomSortQuery();
    }

    /**
     * @param $sortQuery
     * @return $this
     */
    public function setCustomSortQuery($sortQuery)
    {
        $this->queryBuilder->setCustomSortQuery($sortQuery);
        return $this;
    }

    public function getAggs()
    {
        return $this->queryBuilder->getAggs();
    }

    public function setAggs($aggs)
    {
        $this->queryBuilder->setAggs($aggs);
        return $this;
    }

    /**
     * @return ElasticArrayList
     */
    public function execute()
    {
        $query = $this->queryBuilder->compileQuery();
        $index = $this->getFrom();
        $response = $index->search($query, false);

        list($source, $totalHits, $aggs) = $index->getService()->extractSearchSource($response);

        return $this->decorateResponse($source, $totalHits, $aggs);
    }

    protected function decorateResponse($response, $totalHits, $aggs)
    {
        $decorator = $this->getDecorator();
        if ($decorator) {
            return $decorator->decorate($this, $response, $totalHits, $aggs);
        }

        /**
         * by default returns associative array
         */
        return $response;
    }
}
