<?php

namespace Framework\ORM;



use Exception;

class SQLGenerator
{
    /**
     * @param Query $q
     * @return string
     * @throws Exception
     */
    public function getSQL(Query $q) : string
    {
        return match ($q->getType()){
            Query::TYPE_SELECT => $this->select($q),
            Query::TYPE_INSERT => $this->insert($q),
            Query::TYPE_UPDATE => $this->update($q),
            Query::TYPE_DELETE => $this->delete($q),
            default => throw new Exception("invalid query type: " . $q->getType())
        };
    }

    /**
     * @param Query $q
     * @return string
     */
    public function select(Query $q) : string
    {
        $sql = "SELECT " . implode(", ", $q->getColumns()) . " FROM " . $q->getTable() . $this->alias($q, $q->getTable());

        $sql .= $this->where($q) . $this->groupBy($q) . $this->orderBy($q) . $this->limit($q) . $this->offset($q);

        return $sql;
    }

    /**
     * @param Query $q
     * @return string
     */
    public function insert(Query $q) : string
    {
        $sql = "INSERT INTO " . $q->getTable();

        if (!empty($q->getColumns())) {
            $sql .= " (" . implode(", ", $q->getColumns()) . ")";
        }

        $sql .= " VALUES ";

        $valueSets = [];
        foreach ($q->getValueSets() as $valueSet) {
            $valueSets[] .= "(" . implode(", ", $valueSet) . ")";
        }
        $sql .= implode(", ", $valueSets);
        return $sql;
    }

    /**
     * @param Query $q
     * @return string
     */
    public function update(Query $q) : string
    {
        $sql = "UPDATE " . $q->getTable() . " SET ";

        $values = [];
        foreach (current($q->getValueSets()) as $index => $value) {
            $values[] = $q->getColumns()[$index] . " = " . $value;
        }

        $sql .= implode(", ", $values);

        $sql .= $this->where($q) . $this->orderBy($q) . $this->limit($q);

        return $sql;
    }

    /**
     * @param Query $q
     * @return string
     */
    public function delete(Query $q) : string
    {
        $sql = "DELETE FROM " . $q->getTable();

        $sql .= $this->where($q) . $this->orderBy($q) . $this->limit($q);

        return $sql;
    }

    /**
     * @param Query $q
     * @param string $table
     * @return string
     */
    public function alias(Query $q, string $table) : string
    {
        $alias = @$q->getAliases()[$table];
        return $alias === null ? "" : " AS $alias";
    }

    /**
     * @param Query $q
     * @return string
     */
    public function where(Query $q) : string
    {
        if (empty($q->getConditions())){
            return "";
        }

        return  " WHERE " . implode(" OR ", $q->getConditions());
    }

    /**
     * @param Query $q
     * @return string
     */
    public function groupBy(Query $q) : string
    {
        if (empty($q->getGroupBy())){
            return "";
        }

        return  " GROUP BY " . implode(", ", $q->getGroupBy());
    }

    /**
     * @param Query $q
     * @return string
     */
    public function orderBy(Query $q) : string
    {
        if (empty($q->getOrderBy())){
            return "";
        }
        $orderBys = [];
        foreach ($q->getOrderBy() as $orderBy){
            $orderBys[] .= implode(" ", $orderBy);
        }

        return " ORDER BY " . implode(", ", $orderBys);
    }

    /**
     * @param Query $q
     * @return string
     */
    public function limit(Query $q) : string
    {
        return is_null($q->getLimit()) ? "" : " LIMIT " . $q->getLimit();
    }

    /**
     * @param Query $q
     * @return string
     */
    public function offset(Query $q) : string
    {
        return $q->getOffset() === null ? "" : " OFFSET " . $q->getOffset();
    }
}