<?php

namespace Framework\ORM;

use Exception;
use PDO;

class Connection
{
    public ?PDO $pdo;


    public function __construct()
    {
        $this->pdo = null;
    }

    public function isConnected() : bool
    {
        return $this->pdo !== null;
    }

    public function connect() : void
    {
        $options =  [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ];

        $this->pdo = new PDO($_ENV["DB_URL"], $_ENV["DB_USER"], $_ENV["DB_PASSWORD"], $options);
    }

    public function execute(string $query, array $params = []) : array
    {
        if(!$this->isConnected()){
            throw new Exception("Database connection is not initialized");
        }

        $sth = $this->pdo->prepare($query);

        foreach ($params as $key => $value) {
            $sth->bindValue($key, $value);
        }
        $sth->execute();

        return $sth->fetchAll();
    }
}