<?php

namespace Framework\ORM;

interface JsonSerializable
{
    /**
     * @return array
     */
    public function jsonSerialize() : array;
}