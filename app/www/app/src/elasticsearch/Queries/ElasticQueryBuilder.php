<?php

namespace TND\ElasticSearch\Queries;

use TND\ElasticSearch\AbstractIndex;
use SilverStripe\Core\Config\Configurable;
use SilverStripe\Core\Extensible;
use SilverStripe\Core\Injector\Injectable;

/**
 * Class ElasticQueryBuilder
 * @package TND\ElasticSearch\Queries
 */
class ElasticQueryBuilder
{
    use Extensible;
    use Configurable;
    use Injectable;

    /**
     * By default passes full _source. In order to get limited fields pass an array of fields
     *  e.g. ['Title', 'Categories.Title']
     * @var string
     */
    protected $select = "*";
    protected $from = null;
    protected $mustFilters = [];
    protected $mustCustomFilters = [];

    protected $mustNotFilters = [];
    protected $shouldFilters = [];

    protected $mustRangeFilters = [];
    protected $shouldRangeFilters = [];

    protected $mustFuzzyFilters = [];

    protected $shouldMatchPhrasePrefix = [];

    protected $shouldMultiMatchPrefixFilters = [];

    protected $nestedQueries = [];

    protected $customQueries = [];


    protected $limit = ElasticSelect::DEFAULT_LIMIT;
    protected $offset = ElasticSelect::DEFAULT_OFFSET;
    protected $sort = [];
    protected $customSortQuery = [];

    protected $aggs = [];

    public function __construct()
    {
    }

    /**
     * @return string|array
     */
    public function getSelect()
    {
        return $this->select;
    }

    /**
     * @param array|string $select
     */
    public function setSelect($select): void
    {
        $this->select = $select;
    }

    /**
     * @return AbstractIndex
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @param AbstractIndex $from
     */
    public function setFrom($from)
    {
        $this->from = $from;
    }

    /**
     * @return array
     */
    public function getMustFilters(): array
    {
        return $this->mustFilters;
    }

    /**
     * @param array $mustFilters
     */
    public function setMustFilters(array $mustFilters): void
    {
        $this->mustFilters = $mustFilters;
    }

    public function addMustFilter($field, $data)
    {
        $this->mustFilters[$field] = $data;
    }

    public function getMustCustomFilters(): array
    {
        return $this->mustCustomFilters;
    }

    /**
     * @param array $mustCustomFilters
     */
    public function setMustCustomFilters(array $mustCustomFilters): void
    {
        $this->mustCustomFilters = $mustCustomFilters;
    }

    public function addMustCustomFilter($filter)
    {
        $this->mustCustomFilters[] = $filter;
    }

    public function addMustNestedFilter($nestedSubQuery)
    {
        $this->mustFilters[] = $nestedSubQuery;
    }

    /**
     * @return array
     */
    public function getMustRangeFilters(): array
    {
        return $this->mustRangeFilters;
    }

    /**
     * @param array $mustRangeFilters
     */
    public function setMustRangeFilters(array $mustRangeFilters): void
    {
        $this->mustRangeFilters = $mustRangeFilters;
    }

    public function addMustRangeFilter($field, $data)
    {
        $this->mustRangeFilters[$field] = $data;
    }


    public function setMustFuzzyFilters(array $mustFuzzyFilters): void
    {
        $this->mustFuzzyFilters = $mustFuzzyFilters;
    }

    public function addMustFuzzyFilter($field, $data)
    {
        $this->mustFuzzyFilters[$field] = $data;
    }

    public function getMustFuzzyFilters()
    {
        return $this->mustFuzzyFilters;
    }

    /**
     * @return array
     */
    public function getShouldRangeFilters(): array
    {
        return $this->shouldRangeFilters;
    }

    /**
     * @param array $filters
     */
    public function setShouldRangeFilters(array $filters): void
    {
        $this->shouldRangeFilters = $filters;
    }

    /**
     * @param $field
     * @param $data
     */
    public function addShouldRangeFilter($field, $data)
    {
        $this->shouldRangeFilters[$field] = $data;
    }

    /**
     * @return array
     */
    public function getMustNotFilters(): array
    {
        return $this->mustNotFilters;
    }

    /**
     * @param array $mustNotFilters
     */
    public function setMustNotFilters(array $mustNotFilters): void
    {
        $this->mustNotFilters = $mustNotFilters;
    }

    public function addMustNotFilter($field, $data)
    {
        $this->mustNotFilters[$field] = $data;
    }

    /**
     * @return array
     */
    public function getShouldFilters(): array
    {
        return $this->shouldFilters;
    }

    /**
     * @param array $shouldFilters
     */
    public function setShouldFilters(array $shouldFilters): void
    {
        $this->shouldFilters = $shouldFilters;
    }

    public function setShouldMatchPrefixFilters(array $shouldFilters): void
    {
        $this->shouldMatchPhrasePrefix = $shouldFilters;
    }

    public function addShouldMatchPrefixFilters($field, $data): void
    {
        $this->shouldMatchPhrasePrefix[$field] = $data;
    }

    public function getShouldMatchPrefixFilters(): array
    {
        return $this->shouldMatchPhrasePrefix;
    }

    public function addShouldMultiMatchFilters(array $fields, $data): void
    {
        $this->shouldMultiMatchPrefixFilters[] = [
            'fields' => $fields,
            'data' => $data
        ];
    }


    public function getShouldMultiMatchFilters()
    {
        return $this->shouldMultiMatchPrefixFilters;
    }


    /**
     * @param $field
     * @param $data
     */
    public function addShouldFilter($field, $data)
    {
        $this->shouldFilters[$field] = $data;
    }

    /**
     * @return array
     */
    public function getNestedQueries(): array
    {
        return $this->nestedQueries;
    }

    /**
     * @param array $nestedQueries
     */
    public function setNestedQueries(array $nestedQueries): void
    {
        $this->nestedQueries = $nestedQueries;
    }

    /**
     * @param $field
     * @param $data
     */
    public function addNestedQuery($field, $data)
    {
        $this->nestedQueries[$field] = $data;
    }


    /**
     * @return array
     */
    public function getCustomQueries(): array
    {
        return $this->customQueries;
    }

    /**
     * @param array $customQueries
     */
    public function setCustomQueries(array $customQueries): void
    {
        $this->customQueries = $customQueries;
    }

    /**
     * @param $type
     * @param $query
     */
    public function addCustomQuery($type, $query)
    {
        $this->customQueries[$type] = $query;
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * @param int $limit
     */
    public function setLimit(int $limit): void
    {
        $this->limit = $limit;
    }

    /**
     * @return int
     */
    public function getOffset(): int
    {
        return $this->offset;
    }

    /**
     * @param int $offset
     */
    public function setOffset(int $offset): void
    {
        $this->offset = $offset;
    }

    /**
     * @return array
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * @param array $sort
     */
    public function setSort($sort): void
    {
        $this->sort = $sort;
    }

    /**
     * @return array
     */
    public function getCustomSortQuery()
    {
        return $this->customSortQuery;
    }

    /**
     * @param array $customSortQuery
     */
    public function setCustomSortQuery($customSortQuery): void
    {
        $this->customSortQuery = $customSortQuery;
    }


    /**
     * @return array
     */
    public function getAggs()
    {
        return $this->aggs;
    }

    /**
     * @param string|array $aggs
     */
    public function setAggs($aggs)
    {
        if (is_string($aggs)) {
            // assuming it's valid JSON
            $aggs = json_decode($aggs, 1);
        }

        $this->aggs = $aggs;
    }

    /**
     * @return array
     */
    public function compileQuery()
    {
        $query = [
            'size' => $this->getLimit() ?? ElasticSelect::DEFAULT_LIMIT,
            'from' => $this->getOffset() ?? ElasticSelect::DEFAULT_OFFSET,
            '_source' => $this->getSelect(),
            'sort' => $this->compileSortQuery(),
            'query' => $this->compileFilterQuery(),
        ];

        $aggs = $this->getAggs();
        if (!empty($aggs)) {
            $query['aggs'] = $aggs;
        }


        return $query;
    }

    protected function compileSortQuery()
    {
        if (empty($this->getSort()) && empty($this->getCustomSortQuery())) {
            return ["_score"];
        }

        $sortQuery = [];

        if (count($this->getSort()) > 0) {
            foreach ($this->getSort() as $column => $sortOrder) {
                $sortQuery[] = [
                    "$column" => [
                        "order" => $sortOrder
                    ]
                ];
            }
        }

        if (!empty($this->getCustomSortQuery())) {
            $sortQuery = $this->getCustomSortQuery();
        }
        return $sortQuery;
    }

    /**
     * @return array
     */
    protected function compileFilterQuery()
    {
        if (
            empty($this->getShouldFilters())
            && empty($this->getMustFilters())
            && empty($this->getNestedQueries())
            && empty($this->getShouldRangeFilters())
            && empty($this->getMustRangeFilters())
            && empty($this->getMustNotFilters())
            && empty($this->getMustFuzzyFilters())
            && empty($this->getShouldMatchPrefixFilters())
            && empty($this->getShouldMultiMatchFilters())
            && empty($this->getMustCustomFilters())
            && empty($this->getCustomQueries())
        ) {
            return [
                'match_all' => new \stdClass()
            ];
        }

        $query = [
            'bool' => []
        ];

        if (!empty($this->getShouldFilters())) {

            $query['bool']['should'] = $query['bool']['should'] ?? [];
            foreach ($this->getShouldFilters() as $column => $columnData) {
                if (is_array($columnData) && $this->arrayContainTerms($columnData)) {
                    if (!empty($columnData)) {
                        $query['bool']['should'][] = [
                            'terms' => [
                                $column => $columnData
                            ]
                        ];
                    }

                } else {
                    $query['bool']['should'][] = [
                        'match' => [
                            $column => $columnData
                        ]
                    ];
                }
            }
        }

        if (!empty($this->getMustFilters())) {

            $query['bool']['must'] = $query['bool']['must'] ?? [];
            foreach ($this->getMustFilters() as $column => $columnData) {
                if (is_array($columnData)) {

                    if (is_numeric($column)) {
                        // nested query object
                        $query['bool']['must'][] = $columnData;
                        continue;
                    }

                    if (!empty($columnData)) {
                        $query['bool']['must'][] = [
                            'bool' => [
                                'minimum_should_match' => 1,
                                'should' => [
                                    array_map(function ($itemValue) use ($column) {
                                        return [
                                            'match' => [
                                                $column => $itemValue
                                            ]
                                        ];
                                    }, $columnData)
                                ]
                            ]
                        ];
                    }
                } else {
                    $query['bool']['must'][] = [
                        'match' => [
                            $column => $columnData
                        ]
                    ];
                }
            }
        }

        if (!empty($this->getMustCustomFilters())) {

            $query['bool']['must'] = $query['bool']['must'] ?? [];
            foreach ($this->getMustCustomFilters() as $filter) {
                $query['bool']['must'][] = $filter;
            }
        }

        if (!empty($this->getMustNotFilters())) {

            $query['bool']['must_not'] = $query['bool']['must_not'] ?? [];
            foreach ($this->getMustNotFilters() as $column => $columnData) {
                if (is_array($columnData)) {
                    if (!empty($columnData)) {
                        $query['bool']['must_not'][] = [
                            'terms' => [
                                $column => $columnData
                            ]
                        ];
                    }

                } else {
                    $query['bool']['must_not'][] = [
                        'match' => [
                            $column => $columnData
                        ]
                    ];
                }
            }
        }

        if (!empty($this->getMustRangeFilters())) {
            $query['bool']['must'] = $query['bool']['must'] ?? [];
            foreach ($this->getMustRangeFilters() as $column => $columnData) {

                list($field, $operator) = $this->translateRange($column);

                $query['bool']['must'][] = [
                    'range' => [
                        $field => [
                            $operator => $columnData
                        ]
                    ]
                ];
            }
        }

        if (!empty($this->getShouldRangeFilters())) {
            $query['bool']['should'] = $query['bool']['should'] ?? [];
            foreach ($this->getShouldRangeFilters() as $column => $columnData) {

                list($field, $operator) = $this->translateRange($column);

                $query['bool']['should'][] = [
                    'range' => [
                        $field => [
                            $operator => $columnData
                        ]
                    ]
                ];
            }
        }

        if (!empty($this->getShouldMatchPrefixFilters())) {
            $query['bool']['should'] = $query['bool']['should'] ?? [];
            foreach ($this->getShouldMatchPrefixFilters() as $column => $columnData) {

                $query['bool']['should'][] = [
                    'match_phrase_prefix' => [
                        $column => $columnData
                    ]
                ];
            }
        }

        if (!empty($this->getShouldMultiMatchFilters())) {
            $query['bool']['should'] = $query['bool']['should'] ?? [];
            foreach ($this->getShouldMultiMatchFilters() as $key => $data) {

                $query['bool']['should'][] = [
                    'multi_match' => [
                        'fields' => $data['fields'],
                        'query' => $data['data'],
                        "type" => "phrase_prefix"
                    ]
                ];
            }
        }


        if (!empty($this->getNestedQueries())) {
            $query['bool']['filter'] = [];
            foreach ($this->getNestedQueries() as $nestedQuery) {
                $query['bool']['filter'][] = ['nested' => $nestedQuery];
            }
        }

        /**
         * Must fuzzy query
         */
        if (!empty($this->getMustFuzzyFilters())) {

            $query['bool']['must'] = $query['bool']['must'] ?? [];
            foreach ($this->getMustFuzzyFilters() as $column => $columnData) {
                $query['bool']['must'][] = [
                    'fuzzy' => [
                        $column => [
                            'value' => $columnData,
                            "fuzziness" => "AUTO"
                        ]
                    ]
                ];
            }
        }

        /**
         * add Custom query
         */
        if (!empty($this->getCustomQueries())) {
            foreach ($this->getCustomQueries() as $type => $data) {
                $query['bool'][$type] = $query['bool'][$type] ?? [];
                $query['bool'][$type][] = $data;
            }
        }


        /**
         * minimum should match condition
         */
        if (
            isset($query['bool']['should']) &&
            !empty($query['bool']['should'])
        ) {
            $query['bool']["minimum_should_match"] = 1;
        }

        return $query;
    }

    protected function translateRange($column)
    {
        $operator = 'gt';
        if (strpos($column, '__')) {
            list($column, $op) = explode('__', $column);

            switch (strtoupper($op)) {
                case 'LT':
                    $operator = 'lt';
                    break;

                case 'LTE':
                    $operator = 'lte';
                    break;

                case 'GT':
                    $operator = 'gt';
                    break;

                case 'GTE':
                    $operator = 'gte';
                    break;
            }
        }

        return [
            $column,
            $operator
        ];
    }

    protected function arrayContainTerms(array $data)
    {
        return count(array_filter(array_keys($data), 'is_string')) == 0;
    }
}
