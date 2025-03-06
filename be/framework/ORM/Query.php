<?php

namespace Framework\ORM;

use Exception;

class Query
{
    public const TYPE_EMPTY = 'EMPTY';
    public const TYPE_SELECT = 'SELECT';
    public const TYPE_INSERT = 'INSERT';
    public const TYPE_UPDATE = 'UPDATE';
    public const TYPE_DELETE = 'DELETE';
    public const VALID_TYPES = [
        self::TYPE_SELECT,
        self::TYPE_INSERT,
        self::TYPE_UPDATE,
        self::TYPE_DELETE
    ];
    public const ORDER_ASC = "ASC";
    public const ORDER_DESC = "DESC";
    public const ORDER_DIRECTIONS = [
        self::ORDER_ASC,
        self::ORDER_DESC
    ];


    /**
     * @var string
     */
    public string $type = self::TYPE_EMPTY;

    /**
     * @var string|null
     */
    public ?string $table = null;

    /**
     * @var array
     */
    public array $aliases = [];

    /**
     * @var array
     */
    public array $columns = [];

    /**
     * @var array
     */
    public array $conditions = [];

    /**
     * @var array
     */
    public array $valueSets = [];

    /**
     * @var array
     */
    public array $groupBy = [];
    /**
     * @var array
     */
    public array $orderBy = [];

    /**
     * @var int|null
     */
    public ?int $limit = null;

    /**
     * @var int|null
     */
    public ?int $offset = null;

    /**
     * @var array
     */
    public array $params = [];


    /**
     * @return string Query type
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type Query type
     * @return $this Query object
     * @throws Exception
     */
    public function setType(string $type): self
    {

        if(!in_array($type, self::VALID_TYPES)) {
            throw new Exception("Invalid query type" . $type);
        }

        $this->type = $type;
        return $this;
    }


    /**
     * @return string|null Table name (if provided)
     */
    public function getTable(): ?string
    {
        return $this->table;
    }

    /**
     * @param string|null $table Table name
     * @return $this Query object
     */
    public function setTable(?string $table): self
    {
        $this->table = $table;
        return $this;
    }

    /**
     * @return array Associative array of aliases which contains [fullName => alias] key-value pairs
     */
    public function getAliases(): array
    {
        return $this->aliases;
    }

    /**
     * @param array $aliases Associative array of aliases which contains [fullName => alias] key-value pairs
     * @return $this Query object
     */
    public function setAliases(array $aliases): self
    {
        $this->aliases = $aliases;
        return $this;
    }

    /**
     * @param string $table Full table name
     * @param string $alias Alias
     * @return $this Query object
     */
    public function addAlias(string $table, string $alias): self
    {
        $this->aliases[$table] = $alias;
        return $this;
    }

    /**
     * @return array Array of all columns used in the query
     */
    public function getColumns(): array
    {
        return $this->columns;
    }

    /**
     * @param array $columns Array of all columns
     * @return $this Query object
     */
    public function setColumns(array $columns): self
    {
        $this->columns = $columns;
        return $this;
    }

    /**
     * @return array Array of conditions. The elements are treated as a condition groups and will be joined by OR operator
     */
    public function getConditions(): array
    {
        return $this->conditions;
    }

    /**
     * @param array $conditions Array of conditions
     * @return $this Query object
     */
    public function setConditions(array $conditions): self
    {
        $this->conditions = $conditions;
        return $this;
    }

    /**
     * Automatically adds new condition to the last one (if exists) by joining them with AND operator
     * @param string $condition New condition
     * @return $this Query object
     */
    public function andCondition(string $condition): self
    {
        if(empty($this->conditions)){
            $this->conditions[] = "";
        }
        $key = array_key_last($this->conditions);

        if($this->conditions[$key] !== ""){
            $this->conditions[$key] .= " AND ";
        }

        $this->conditions[$key] .= $condition;

        return $this;
    }

    /**
     * Adds new condition to conditions array
     * @param string $condition New condition
     * @return $this Query object
     */
    public function orCondition(string $condition): self
    {
        $this->conditions[] = $condition;
        return $this;
    }

    /**
     * @return array Array of value sets. Each value set is an array like [value1, value2, ..., valueX]
     */
    public function getValueSets(): array
    {
        return $this->valueSets;
    }


    /**
     * @param array $valueSets Array of value sets. Each value set is an array like [value1, value2, ..., valueX]
     * @return $this Query object
     */
    public function setValueSets(array $valueSets): self
    {
        $this->valueSets = $valueSets;
        return $this;
    }

    /**
     * @param array $valueSet Value set: an array like [value1, value2, ..., valueX]
     * @return $this Query object
     */
    public function addValueSet(array $valueSet): self
    {
        $this->valueSets[] = $valueSet;
        return $this;
    }


    /**
     * @return array Array of column names for groupBy
     */
    public function getGroupBy(): array
    {
        return $this->groupBy;
    }

    /**
     * @param array $groupBy Array of column names for groupBy
     * @return $this Query object
     */
    public function setGroupBy(array $groupBy): self
    {
        $this->groupBy = $groupBy;
        return $this;
    }

    /**
     * @param string $groupBy New column to group results by
     * @return $this Query object
     */
    public function addGroupBy(string $groupBy): self
    {
        $this->groupBy[] = $groupBy;
        return $this;
    }

    /**
     * @return array Array of orderBy's - arrays like [columnName, direction]
     */
    public function getOrderBy(): array
    {
        return $this->orderBy;
    }

    /**
     * @param array $orderBy Array of orderBy's - arrays like [columnName, direction]
     * @return $this Query object
     */
    public function setOrderBy(array $orderBy): self
    {
        $this->orderBy = $orderBy;
        return $this;
    }

    /**
     * @param string $column Column to order results by
     * @param string $direction Direction of ordering
     * @return $this Query object
     * @throws Exception
     */
    public function addOrderBy(string $column, string $direction): self
    {

        if(!in_array($direction, self::ORDER_DIRECTIONS)) {
            throw new Exception("Invalid order directions" . $direction);
        }

        $this->orderBy[] = [$column, $direction];
        return $this;
    }

    /**
     * @return int|null limit (if provided)
     */
    public function getLimit(): ?int
    {
        return $this->limit;
    }

    /**
     * @param int|null $limit limit
     * @return $this Query object
     */
    public function setLimit(?int $limit): self
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * @return int|null offset (if provided)
     */
    public function getOffset(): ?int
    {
        return $this->offset;
    }

    /**
     * @param int|null $offset offset
     * @return $this Query object
     */
    public function setOffset(?int $offset): self
    {
        $this->offset = $offset;
        return $this;
    }

    /**
     * @return array Associative array of params like [paramName => paramValue]
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * @param array $params Associative array of params like [paramName => paramValue]
     * @return $this Query object
     */
    public function setParams(array $params): self
    {
        $this->params = $params;
        return $this;
    }

    /**
     * @param string $name Param name
     * @param mixed $value Param value
     * @return $this Query object
     */
    public function addParam(string $name, mixed $value): self
    {
        $this->params[$name] = $value;
        return $this;
    }

}