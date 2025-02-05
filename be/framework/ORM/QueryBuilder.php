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
     * @return $this
     */
    public function reset() : self
    {
        $this->query = new Query();
        $this->paramsCount = 1;
        return $this;
    }

    /**
     * @return Query
     */
    public function getQuery(): Query
    {
        return $this->query;
    }

    /**
     * @param string $from
     * @param string|null $alias
     * @param array $columns
     * @return $this
     * @throws \Exception
     */
    public function select(string $from, ?string $alias = null, array $columns = ["*"]) : self
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
     * @param string $from
     * @param string|null $alias
     * @return $this
     * @throws \Exception
     */
    public function delete(string $from, ?string $alias = null) : self
    {
        $this->query->setType(Query::TYPE_DELETE)
            ->setTable($from);

        if($alias !== null){
            $this->query->addAlias($from, $alias);
        }

        return $this;
    }

    /**
     * @param string $table
     * @param string|null $alias
     * @return $this
     */
    public function from(string $table, ?string $alias = null) : self
    {

        return $this;
    }

    /**
     * @param string $into
     * @param array $columns
     * @return $this
     * @throws \Exception
     */
    public function insert(string $into, array $columns = []) : self
    {
        $this->query->setType(Query::TYPE_INSERT)
            ->setTable($into)
            ->setColumns($columns);

        return $this;
    }

    /**
     * @param string $table
     * @param array $columns
     * @return $this
     * @throws Exception
     */
    public function update(string $table, array $columns) : self
    {
        $this->query->setType(Query::TYPE_UPDATE)
            ->setTable($table)
            ->setColumns($columns);

        return $this;
    }

    /**
     * @param string $condition
     * @return $this
     */
    public function where(string $condition) : self
    {
        $this->query->andCondition($condition);
        return $this;
    }

    /**
     * @param string $condition
     * @return $this
     */
    public function andWhere(string $condition) : self
    {
        $this->query->andCondition($condition);
        return $this;
    }

    /**
     * @param string $condition
     * @return $this
     */
    public function orWhere(string $condition) : self
    {
        $this->query->orCondition($condition);
        return $this;
    }

    /**
     * @param array ...$valueSets
     * @return $this
     */
    public function values(array ...$valueSets) : self
    {
        foreach ($valueSets as $valueSet){
            $this->addValues($valueSet);
        }
        return $this;
    }

    /**
     * @param array $valueSet
     * @return $this
     */
    public function addValues(array $valueSet) : self
    {
        $preparedSet = [];

        foreach ($valueSet as $value){
            $param = "QB_VALUE_".$this->paramsCount;

            $this->setParams([$param => $value]);
            $preparedSet[] = ":$param";

            $this->paramsCount += 1;
        }

        $this->query->addValueSet($preparedSet);
        return $this;
    }

    /**
     * @param string ...$groupBy
     * @return $this
     */
    public function groupBy(string ...$groupBy) : self
    {
        foreach ($groupBy as $column){
            $this->query->addGroupBy($column);
        }
        return $this;
    }

    /**
     * @param array ...$orderBy
     * @return $this
     * @throws \Exception
     */
    public function orderBy(array ...$orderBy) : self
    {
        foreach ($orderBy as [$column, $directions]) {
            $this->query->addOrderBy($column, $directions);
        }
        return $this;
    }

    /**
     * @param int $limit
     * @return $this
     */
    public function limit(int $limit) : self
    {
        $this->query->setLimit($limit);
        return $this;
    }

    /**
     * @param int $offset
     * @return $this
     */
    public function offset(int $offset) : self
    {
        $this->query->setOffset($offset);
        return $this;
    }

    /**
     * @param array $values
     * @return $this
     */
    public function setParams(array $values) : self
    {
        foreach ($values as $key => $value){
            $this->query->addParam($key, $value);
        }
        return $this;
    }

}