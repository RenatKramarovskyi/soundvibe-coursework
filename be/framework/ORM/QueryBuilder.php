<?php

namespace Framework\ORM;

use Exception;

class QueryBuilder
{

    /**
     * @var Query
     */
    private Query $query;

    /**
     * @var int
     */
    private int $paramsCount;

    /**
     *
     */
    public function __construct()
    {
        $this->query = new Query();
        $this->paramsCount = 1;
    }

    /**
     * Resets Builder to default state
     * @return $this Query Builder
     */
    public function reset(): self
    {
        $this->query = new Query();
        $this->paramsCount = 1;
        return $this;
    }

    /**
     * @return Query Resulting query object
     */
    public function getQuery(): Query
    {
        return $this->query;
    }

    /**
     * @param string $from Table name to select data from
     * @param string|null $alias Alias for table (optional)
     * @param array $columns Columns to select ("*" by default)
     * @return $this Query Builder
     * @throws Exception
     */
    public function select(string $from, ?string $alias = null, array $columns = ["*"]): self
    {
        $this->query->setType(Query::TYPE_SELECT)
            ->setTable($from)
            ->setColumns($columns);

        if($alias !== null){
            $this->query->addAlias($from, $alias);
        }

        return $this;
    }

    /**
     * @param string $from Table name to delete data from
     * @param string|null $alias Alias for table (optional)
     * @return $this Query Builder
     * @throws Exception
     */
    public function delete(string $from, ?string $alias = null): self
    {
        $this->query->setType(Query::TYPE_DELETE)
            ->setTable($from);

        if($alias !== null){
            $this->query->addAlias($from, $alias);
        }

        return $this;
    }

    /**
     * @param string $into Table name to insert data into
     * @param array $columns Columns to insert (empty by default, order is important)
     * @return $this Query Builder
     * @throws Exception
     */
    public function insert(string $into, array $columns = []): self
    {
        $this->query->setType(Query::TYPE_INSERT)
            ->setTable($into)
            ->setColumns($columns);

        return $this;
    }

    /**
     * @param string $table Table to update data in
     * @param array $columns Columns to update (order is important)
     * @return $this Query Builder
     * @throws Exception
     */
    public function update(string $table, array $columns): self
    {
        $this->query->setType(Query::TYPE_UPDATE)
            ->setTable($table)
            ->setColumns($columns);

        return $this;
    }

    /**
     * Condition is a logical SQL expression. Use params instead of direct values! F.e., "id = :id"
     * @param string $condition New condition
     * @return $this Query Builder
     */
    public function where(string $condition): self
    {
        $this->query->andCondition($condition);
        return $this;
    }

    /**
     * Condition is a logical SQL expression. Use params instead of direct values! F.e., "id = :id"
     * @param string $condition New condition
     * @return $this Query Builder
     */
    public function andWhere(string $condition): self
    {
        $this->query->andCondition($condition);
        return $this;
    }

    /**
     * Condition is a logical SQL expression. Use params instead of direct values! F.e., "id = :id"
     * @param string $condition New condition
     * @return $this Query Builder
     */
    public function orWhere(string $condition): self
    {
        $this->query->orCondition($condition);
        return $this;
    }

    /**
     * @param array ...$valueSets Value sets. Each value set is an array like [value1, value2, ..., valueX]
     * @return $this Query Builder
     */
    public function values(array ...$valueSets): self
    {
        foreach ($valueSets as $valueSet){
            $this->addValues($valueSet);
        }
        return $this;
    }

    /**
     * Receives a value set, adds it to Query value sets and automatically generates params for each value
     * @param array $valueSet Value set - an array like [value1, value2, ..., valueX]
     * @return $this Query Builder
     */
    public function addValues(array $valueSet): self
    {
        $preparedSet = [];

        foreach ($valueSet as $value) {
            $param = "QB_VALUE_" . $this->paramsCount;

            $this->setParams([$param => $value]);
            $preparedSet[] = ":$param";

            $this->paramsCount += 1;
        }

        $this->query->addValueSet($preparedSet);
        return $this;
    }

    /**
     * @param string ...$groupBy Columns to group data by
     * @return $this Query Builder
     */
    public function groupBy(string ...$groupBy): self
    {
        foreach ($groupBy as $column){
            $this->query->addGroupBy($column);
        }
        return $this;
    }

    /**
     * @param array ...$orderBy OrderBy's - arrays like [columnToOrderBy, orderingDirection]
     * @return $this Query Builder
     * @throws Exception
     */
    public function orderBy(array ...$orderBy): self
    {
        foreach ($orderBy as [$column, $direction]){
            $this->query->addOrderBy($column, $direction);
        }
        return $this;
    }

    /**
     * @param int $limit Limit
     * @return $this Query Builder
     */
    public function limit(int $limit): self
    {
        $this->query->setLimit($limit);
        return $this;
    }

    /**
     * @param int $offset Offset
     * @return $this Query Builder
     */
    public function offset(int $offset): self
    {
        $this->query->setOffset($offset);
        return $this;
    }

    /**
     * Binds values to params names
     * @param array $values Associative array like [paramName=>paramValue]
     * @return $this Query Builder
     */
    public function setParams(array $values): self
    {
        foreach ($values as $key => $value) {
            $this->query->addParam($key, $value);
        }
        return $this;
    }
}