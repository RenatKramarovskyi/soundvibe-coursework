<?php

namespace Framework\ORM;

interface EntityManagerInterface
{
    /**
     * @return void
     */
    public function clearStates() : void;

    /**
     * @param BaseEntity $entity
     * @return void
     */
    public function track(BaseEntity $entity) : void;

    /**
     * @param BaseEntity $entity
     * @return void
     */
    public function persist(BaseEntity $entity) : void;

    /**
     * @param BaseEntity $entity
     * @return void
     */
    public function remove(BaseEntity $entity) : void;

    /**
     * @return void
     */
    public function flush() : void;

    /**
     * @param string $entityClass
     * @return BaseEntityRepository
     */
    public function getRepository(string $entityClass) : BaseEntityRepository;
}