<?php

namespace Framework\DependencyInjection;


use Closure;

interface DependencyManagerInterface
{
    /**
     * @param string $dependency
     * @return mixed
     */
    public function getDependency(string $dependency): mixed;

    /**
     * @param string $dependency
     * @param mixed $value
     * @return void
     */
    public function setDependency(string $dependency, mixed $value): void;

    /**
     * @param string $classname
     * @param array $args
     * @return mixed
     */
    public function createObject(string $classname, array $args = []): mixed;

    /**
     * @param string|Closure $function
     * @param mixed $objectOrClass
     * @param array $args
     * @return mixed
     */
    public function callFunction(string|Closure $function, mixed $objectOrClass, array $args = []): mixed;
}