<?php

namespace Framework\ORM;

use Framework\ORM\Attributes\Column;
use ReflectionClass;
use ReflectionProperty;

class BaseEntity
{
    /**
     * @param array $data
     * @return void
     * @throws \ReflectionException
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
}