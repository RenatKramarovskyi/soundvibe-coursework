<?php

namespace Framework\ORM;

interface ConnectionInterface
{
    /**
     * @return bool
     */
    public function isConnected() : bool;

    /**
     * @return void
     */
    public function connect() : void;

    /**
     * @param Query $q
     * @return array
     */
    public function execute(Query $q) : array;
}