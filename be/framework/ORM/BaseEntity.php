<?php

namespace Framework\ORM;

use Exception;
use Framework\ORM\Attributes\Column;
use Framework\ORM\Attributes\Entity;
use Framework\ORM\Attributes\Id;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;

class BaseEntity
{
    /**
     * @param array $data
     * @return void
     * @throws ReflectionException
     */
    public function fromQueryResult(array $data) : void
    {
        $reflectionClass = new ReflectionClass($this::class);

        $props = array_filter($reflectionClass->getProperties(),
            fn($prop) => !empty($prop->getAttributes(Column::class))
        );

        foreach ($props as $prop) {
            /** @var Column $columnAttr */
            $columnAttr = current($prop->getAttributes(Column::class))->newInstance();

            $parser = $columnAttr->getType()->sqlToPhp();

            @$prop->setValue($this, $parser($data[$columnAttr->getColumn()]));
        }
    }

    public function getEntityId() : string
    {
        $reflectionClass = new ReflectionClass($this::class);

        $props = array_filter($reflectionClass->getProperties(),
            fn($prop) => !empty($prop->getAttributes(Id::class))
        );

        if(empty($props)) {
            throw new Exception("Prop with ID attribute not found in " . $this::class);
        }

        $id = current($props)->getValue($this) ?? uniqid("NEW_ENTITY_");
        return $this::class . "@" . $id;
    }

    public function getIdColumn() : string
    {
        $reflectionClass = new ReflectionClass($this::class);

        $props = array_filter($reflectionClass->getProperties(),
            fn($prop) => !empty($prop->getAttributes(Id::class))
        );

        if(empty($props)) {
            throw new Exception("Prop with ID attribute not found in " . $this::class);
        }

        /** @var ReflectionAttribute $columnAttr */
        $columnAttr = current(current($props)->getAttributes(Column::class));

        if($columnAttr === false) {
            throw new Exception("Column attribute not found for Id property in " . $this::class);
        }

        return $columnAttr->newInstance()->getColumn();
    }

    public function getStateSnapshot() : array
    {
        $result = [];

        $reflectionClass = new ReflectionClass($this::class);

        $props = array_filter($reflectionClass->getProperties(),
            fn($prop) => !empty($prop->getAttributes(Column::class))
        );

        foreach ($props as $prop) {
            /** @var Column $columnAttr */
            $columnAttr = current($prop->getAttributes(Column::class))->newInstance();

            $parser = $columnAttr->getType()->phpToSql();

            $result[$columnAttr->getColumn()] = $parser($prop->getValue($this));
        }

        return $result;

    }

    public static function getEntityTable(string $entityClass) : string
    {
        $entityAttribute = current((new ReflectionClass($entityClass))->getAttributes(Entity::class));

        if ($entityAttribute === false) {
            throw new Exception("Entity attribute not found in \"" . $entityClass . "\"");
        }

        return $entityAttribute->newInstance()->getTable();

    }

    public static function getStateDifference(array $a, array $b) : array
    {
        return array_udiff_assoc($b, $a, fn($a, $b) => json_encode($a) === json_encode($b) ? 0 : 1);

    }


}