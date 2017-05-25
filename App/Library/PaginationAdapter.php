<?php

namespace App\Library;

use Pagerfanta\Adapter\AdapterInterface;
use Phalcon\Paginator\Adapter\QueryBuilder;

/**
 * Class PaginationAdapter
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 * @package App\Library
 */
class PaginationAdapter implements AdapterInterface
{
    /** @var QueryBuilder $builder */
    protected $builder;

    /** @var \stdClass $paginateObject */
    protected $paginateObject;

    public function __construct(QueryBuilder $queryBuilder = null, \stdClass $paginateObject = null)
    {
        if (is_null($paginateObject)) {
            $paginateObject = $queryBuilder->getPaginate();
        }
        $this->paginateObject = $paginateObject;
        $this->builder = $queryBuilder;
    }

    /**
     * Returns the number of results.
     *
     * @return integer The number of results.
     */
    public function getNbResults()
    {
        return $this->paginateObject->total_items;
    }

    /**
     * Returns an slice of the results.
     *
     * @param integer $offset The offset.
     * @param integer $length The length.
     *
     * @return array|\Traversable The slice.
     */
    public function getSlice($offset, $length)
    {
        return $this->paginateObject->items;
    }
}
