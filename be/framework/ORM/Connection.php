<?php

namespace Framework\ORM;

use Closure;
use Exception;
use PDO;

class Connection implements ConnectionInterface
{
    /**
     * @var PDO|null
     */
    public ?PDO $pdo;


    /**
     *
     */
    public function __construct()
    {
        $this->pdo = null;
        $this->connect();
    }

    /**
     * @return bool
     */
    public function isConnected() : bool
    {
        return $this->pdo !== null;
    }

    /**
     * @return void
     */
    public function connect() : void
    {
        $options =  [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ];

        $this->pdo = new PDO($_ENV["DB_URL"], $_ENV["DB_USER"], $_ENV["DB_PASSWORD"], $options);
    }

    /**
     * @param Query $q
     * @return array
     * @throws Exception
     */
    public function execute(Query $q, ?string $entityClass = null) : array
    {
        if(!$this->isConnected()){
            throw new Exception("Database connection is not initialized");
        }

        $sqlGenerator = new SQLGenerator();
        $sth = $this->pdo->prepare($sqlGenerator->getSQL($q));

        foreach ($q->getParams() as $key => $value) {
            $sth->bindValue($key, $value);
        }

        $sth->execute();

        $result = $sth->fetchAll();

        if($entityClass !== null && is_subclass_of($entityClass, BaseEntity::class)){
            $result = array_map(
                function ($data) use ($entityClass){
                    $entity = new $entityClass();
                    $entity->fromQueryResult($data);
                    return $entity;
                },
                $result);
        }

        return $result;
    }
}