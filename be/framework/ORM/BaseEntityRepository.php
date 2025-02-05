<?php

namespace Framework\ORM;

use Exception;
use Framework\ORM\Attributes\Entity;
use Framework\ORM\Attributes\Repository;
use ReflectionClass;

class BaseEntityRepository
{

    /**
     * @var EntityManager
     */
    protected EntityManager $entityManager;

    /**
     * @var string
     */
    protected string $entityClass;

    /**
     * @var string
     */
    protected string $entityTable;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;

        $repositoryAttribute = current((new ReflectionClass($this::class))->getAttributes(Repository::class));
        if ($repositoryAttribute === false) {
            throw new Exception("Repository attribute not found in \"" . $this::class . "\"");
        }
        $this->entityClass = $repositoryAttribute->newInstance()->getEntity();

        $entityAttribute = current((new ReflectionClass($this->entityClass))->getAttributes(Entity::class));
        if ($entityAttribute === false) {
            throw new Exception("Entity attribute not found in \"" . $this->entityClass . "\"");
        }
        $this->entityTable = $entityAttribute->newInstance()->getTable();
    }

    /**
     * @param string $id
     * @return mixed
     */
    public function find(string $id): mixed
    {
        $idColumn = (new $this->entityClass())->getIdColumn();
        return current($this->findBy([$idColumn => $id], 1));
    }

    /**
     * @param int|null $limit
     * @param int|null $offset
     * @param array|null $orderBy
     * @return array
     */
    public function findAll(?int $limit = null, ?int $offset = null, ?array $orderBy = null): array
    {
        return $this->findBy([], $limit, $offset, $orderBy);
    }

    /**
     * @param array $criteria
     * @param int|null $limit
     * @param int|null $offset
     * @param array|null $orderBy
     * @return array
     * @throws Exception
     */
    public function findBy(array $criteria, ?int $limit = null, ?int $offset = null, ?array $orderBy = null): array
    {
        $qb = new QueryBuilder();
        $qb->select($this->entityTable);


        foreach ($criteria as $key => $value) {
            $operator = "=";
            $specialOperators = [
                "LIKE",
                ">",
                ">=",
                "<",
                "<=",
                "<>"
            ];
            foreach ($specialOperators as $specialOperator) {
                if (str_starts_with($value, $specialOperator)) {
                    $operator = $specialOperator;
                    $value = str_replace("$specialOperator ", "");
                    break;
                }
            }

            $condition = "$key $operator :$key";
            $qb->andWhere($condition)->setParams([$key => $value]);
        }

        $limit !== null && $qb->limit($limit);
        $offset !== null && $qb->offset($offset);

        $orderBy !== null && $qb->orderBy($orderBy);

        $q = $qb->getQuery();

        $entities = $this->entityManager->connection->execute($q, $this->entityClass);

        foreach ($entities as $entity){
            $this->entityManager->track($entity);
        }

        return $entities;
    }

    /**
     * @param array $criteria
     * @param int|null $offset
     * @param array|null $orderBy
     * @return mixed
     * @throws Exception
     */
    public function findOneBy(array $criteria, ?int $offset = null, ?array $orderBy = null): mixed
    {
        return current($this->findBy($criteria, 1, $offset, $orderBy));
    }

}