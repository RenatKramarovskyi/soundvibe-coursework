<?php

namespace Framework\ORM\Attributes;
use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Repository
{
    /**
     * @var string
     */
    public string $entity;

    /**
     * @param string $entity
     */
    public function __construct(string $entity)
    {
        $this->entity = $entity;
    }

    /**
     * @return string
     */
    public function getEntity(): string
    {
        return $this->entity;
    }
}