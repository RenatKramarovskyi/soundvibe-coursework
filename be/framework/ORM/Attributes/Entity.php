<?php

namespace Framework\ORM\Attributes;
use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Entity
{
    /**
     * @var string
     */
    public string $repository;
    /**
     * @var string
     */
    public string $table;

    /**
     * @param string $repository
     * @param string $table
     */
    public function __construct(string $repository, string $table)
    {
        $this->repository = $repository;
        $this->table = $table;
    }

    /**
     * @return string
     */
    public function getRepository(): string
    {
        return $this->repository;
    }

    /**
     * @return string
     */
    public function getTable(): string
    {
        return $this->table;
    }


}