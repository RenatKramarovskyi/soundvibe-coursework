<?php

namespace Framework\ORM;

use Exception;
use Framework\DependencyInjection\DependencyManagerInterface;
use Framework\ORM\Attributes\Entity;
use ReflectionClass;

class EntityManager implements EntityManagerInterface
{

    public const STATE_NOT_PERSISTED = ["NOT_PERSISTED"];
    public const STATE_NOT_EXISTS    = ["NOT_EXISTS"];
    public const STATE_DEFAULT       = [
        "before" => self::STATE_NOT_EXISTS,
        "after"  => self::STATE_NOT_PERSISTED,
    ];

    /**
     * @var array
     */
    private array $states;

    /**
     * @var ConnectionInterface
     */
    public ConnectionInterface $connection;
    /**
     * @var DependencyManagerInterface
     */
    public DependencyManagerInterface $dependencyManager;

    /**
     * @param DependencyManagerInterface $dependencyManager
     * @param ConnectionInterface $connection
     */
    public function __construct(DependencyManagerInterface $dependencyManager, ConnectionInterface $connection)
    {
        $this->states = [];
        $this->dependencyManager = $dependencyManager;
        $this->connection = $connection;
    }

    /**
     * @return void
     */
    public function clearStates(): void
    {
        $this->states = [];
    }

    /**
     * @param BaseEntity $entity
     * @return void
     * @throws Exception
     */
    public function track(BaseEntity $entity): void
    {
        $key = $entity->getEntityId();

        $this->states[$key] = [
            "before" => $entity->getStateSnapshot(),
            "after"  => self::STATE_NOT_PERSISTED
        ];
    }

    /**
     * @param BaseEntity $entity
     * @return void
     * @throws Exception
     */
    public function persist(BaseEntity $entity): void
    {
        $key = $entity->getEntityId();

        $this->states[$key] = array_merge(
            $this->states[$key] ?? self::STATE_DEFAULT,
            [
                "after" => $entity->getStateSnapshot()
            ]
        );
    }

    /**
     * @param BaseEntity $entity
     * @return void
     * @throws Exception
     */
    public function remove(BaseEntity $entity): void
    {
        $key = $entity->getEntityId();

        $this->states[$key] = array_merge(
            $this->states[$key] ?? self::STATE_DEFAULT,
            [
                "after" => self::STATE_NOT_EXISTS
            ]
        );
    }

    /**
     * @return void
     */
    public function flush(): void
    {
        foreach ($this->states as $key => $state) {
            $query = $this->getQueryForStateSet($key, $state);

            if ($query === null) {
                continue;
            }

            $this->connection->execute($query);
        }

        $this->clearStates();
    }

    /**
     * @param string $key
     * @param array $states
     * @return Query|null
     */
    private function getQueryForStateSet(string $key, array $states): ?Query
    {
        if ($states["before"] === self::STATE_NOT_EXISTS && $this->isValidState($states["after"])) {
            return $this->getInsertQuery($key, $states);
        } else if ($this->isValidState($states["before"]) && $states["after"] === self::STATE_NOT_EXISTS) {
            return $this->getDeleteQuery($key, $states);
        } else if ($this->isValidState($states["before"]) && $this->isValidState($states["after"])) {
            if (empty(BaseEntity::getStateDifference($states["after"], $states["before"]))) {
                return null;
            }
            return $this->getUpdateQuery($key, $states);
        }

        return null;
    }

    /**
     * @param string $key
     * @param array $states
     * @return Query
     * @throws Exception
     */
    private function getDeleteQuery(string $key, array $states): Query
    {
        $entityData = $this->parseRecordId($key);

        $qb = new QueryBuilder();
        $qb->delete($entityData["table"])
            ->where($entityData["id-column"] . " = :" . $entityData["id-column"])
            ->setParams([
                $entityData["id-column"] => $entityData["id-value"]
            ]);

        return $qb->getQuery();
    }

    /**
     * @param string $key
     * @param array $states
     * @return Query
     * @throws Exception
     */
    private function getInsertQuery(string $key, array $states): Query
    {
        $entityData = $this->parseRecordId($key);

        $values = $states["after"];
        $values[$entityData["id-column"]] = null;

        $qb = new QueryBuilder();
        $qb->insert($entityData["table"])
            ->addValues($values);

        return $qb->getQuery();
    }

    /**
     * @param string $key
     * @param array $states
     * @return Query
     * @throws Exception
     */
    private function getUpdateQuery(string $key, array $states): Query
    {
        $entityData = $this->parseRecordId($key);

        $changes = BaseEntity::getStateDifference($states["before"], $states["after"]);

        $qb = new QueryBuilder();
        $qb->update($entityData["table"], array_keys($changes))
            ->addValues($changes)
            ->where($entityData["id-column"] . " = :" . $entityData["id-column"])
            ->setParams([
                $entityData["id-column"] => $entityData["id-value"]
            ]);

        return $qb->getQuery();
    }

    /**
     * @param array $state
     * @return bool
     */
    private function isValidState(array $state): bool
    {
        return !in_array($state, [
            self::STATE_NOT_PERSISTED,
            self::STATE_NOT_EXISTS
        ]);
    }

    /**
     * @param string $id
     * @return array
     * @throws Exception
     */
    private function parseRecordId(string $id): array
    {
        $parts = explode("@", $id, 2);
        $entityClass = $parts[0];

        return [
            "class"     => $entityClass,
            "table"     => BaseEntity::getEntityTable($entityClass),
            "id-column" => (new $entityClass())->getIdColumn(),
            "id-value"  => $parts[1]
        ];
    }

    /**
     * @param string $entityClass
     * @return BaseEntityRepository
     * @throws \ReflectionException
     */
    public function getRepository(string $entityClass): BaseEntityRepository
    {
        $entityAttribute = current((new ReflectionClass($entityClass))->getAttributes(Entity::class));
        if ($entityAttribute === false) {
            throw new Exception("Entity attribute not found in \"" . $entityClass . "\"");
        }
        $repositoryClass = $entityAttribute->newInstance()->getRepository();

        return $this->dependencyManager->createObject($repositoryClass, ["entityManager" => $this]);
    }

}
