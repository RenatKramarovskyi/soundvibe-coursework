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
        self::TYPE_DELETE,
    ];
    public const ORDER_ASC = "ASC";
    public const ORDER_DESC = "DESC";
    public const  ORDER_DIRECTIONS = [
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
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return $this
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
     * @return string|null
     */
    public function getTable(): ?string
    {
        return $this->table;
    }

    /**
     * @param string|null $table
     * @return $this
     */
    public function setTable(?string $table): self
    {
        $this->table = $table;
        return $this;
    }

    /**
     * @return array
     */
    public function getAliases(): array
    {
        return $this->aliases;
    }

    /**
     * @param array $aliases
     * @return $this
     */
    public function setAliases(array $aliases): self
    {
        $this->aliases = $aliases;
        return $this;
    }

    /**
     * @param string $table
     * @param string $alias
     * @return $this
     */
    public function addAlias(string $table, string $alias) : self
    {
        $this->aliases[$table] = $alias;
        return $this;
    }

    /**
     * @return array
     */
    public function getColumns(): array
    {
        return $this->columns;
    }

    /**
     * @param array $columns
     * @return $this
     */
    public function setColumns(array $columns): self
    {
        $this->columns = $columns;
        return $this;
    }

    /**
     * @return array
     */
    public function getConditions(): array
    {
        return $this->conditions;
    }

    /**
     * @param array $conditions
     * @return $this
     */
    public function setConditions(array $conditions): self
    {
        $this->conditions = $conditions;
        return $this;
    }

    /**
     * @param string $condition
     * @return $this
     */
    public function andCondition(string $condition) : self
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
     * @param string $condition
     * @return $this
     */
    public function orCondition(string $condition) : self
    {
        $this->conditions[] = $condition;
        return $this;
    }

    /**
     * @return array
     */
    public function getValueSets(): array
    {
        return $this->valueSets;
    }


    /**
     * @param array $valueSets
     * @return $this
     */
    public function setValueSets(array $valueSets): self
    {
        $this->valueSets = $valueSets;
        return $this;
    }

    /**
     * @param array $valueSet
     * @return $this
     */
    public function addValueSet(array $valueSet): self
    {
        $this->valueSets[] = $valueSet;
        return $this;
    }


    /**
     * @return array
     */
    public function getGroupBy(): array
    {
        return $this->groupBy;
    }

    /**
     * @param array $groupBy
     * @return $this
     */
    public function setGroupBy(array $groupBy): self
    {
        $this->groupBy = $groupBy;
        return $this;
    }

    /**
     * @param string $groupBy
     * @return $this
     */
    public function addGroupBy(string $groupBy): self
    {
        $this->groupBy[] = $groupBy;
        return $this;
    }

    /**
     * @return array
     */
    public function getOrderBy(): array
    {
        return $this->orderBy;
    }

    /**
     * @param array $orderBy
     * @return $this
     */
    public function setOrderBy(array $orderBy): self
    {
        $this->orderBy = $orderBy;
        return $this;
    }

    /**
     * @param string $column
     * @param string $direction
     * @return $this
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
     * @return int|null
     */
    public function getLimit(): ?int
    {
        return $this->limit;
    }

    /**
     * @param int|null $limit
     * @return $this
     */
    public function setLimit(?int $limit): self
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getOffset(): ?int
    {
        return $this->offset;
    }

    /**
     * @param int|null $offset
     * @return $this
     */
    public function setOffset(?int $offset): self
    {
        $this->offset = $offset;
        return $this;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * @param array $params
     * @return $this
     */
    public function setParams(array $params): self
    {
        $this->params = $params;
        return $this;
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return $this
     */
    public function addParam(string $name, mixed $value) : self
    {
        $this->params[$name] = $value;
        return $this;
    }

}