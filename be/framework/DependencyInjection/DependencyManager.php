<?php

namespace Framework\DependencyInjection;

use Closure;
use Exception;
use ReflectionClass;
use ReflectionFunction;
use ReflectionMethod;

class DependencyManager implements DependencyManagerInterface
{
    /**
     * @var DependencyManager[]
     */
    private array $registry;
    /**
     * @var array|mixed
     */
    private array $services;

    /**
     *
     */
    public function __construct()
    {
        $this->registry = [
            "Framework\DependencyInjection\DependencyManagerInterface" => $this
        ];
        $this->services = @yaml_parse_file(__DIR__."/Config/services.yaml")["services"] ?: [];

    }

    /**
     * @param string $dependency
     * @return mixed
     * @throws \ReflectionException
     */
    public function getDependency(string $dependency) : mixed
    {
        if(!array_key_exists($dependency, $this->services)){
            throw new Exception("Unknown dependency");
        }

        if(isset($this->registry[$dependency])){
            return $this->registry[$dependency];
        }

        $className = @$this->services[$dependency]["class"] ?: $dependency;
        $reflectionClass = new ReflectionClass($className);

        $constructReflection = $reflectionClass->getConstructor();
        $params = [];

        if($constructReflection !== null) {
            foreach ($constructReflection->getParameters() as $reflectionParameter) {

                $name = $reflectionParameter->getName();

                if(array_key_exists($name, @$this->services[$dependency]["arguments"])){
                    $params[$name] = $this->services[$dependency]["arguments"][$name];
                    continue;
                }

                $type = $reflectionParameter->getType()?->getName();
                if(array_key_exists($type, $this->services)) {
                    $params[$name] = $this->getDependency($type);
                }
            }
        }
        $instance = $reflectionClass->newInstanceArgs($params);
        $this->registry[$dependency] = $instance;

        return $instance;
    }

    /**
     * @param string $dependency
     * @param mixed $value
     * @return void
     */
    public function setDependency(string $dependency, mixed $value): void
    {
        $this->registry[$dependency] = $value;
    }


    /**
     * @param string $className
     * @param array $args
     * @return mixed
     * @throws \ReflectionException
     */
    public function createObject(string $className, array $args = []) : mixed
    {
        $reflectionClass = new ReflectionClass($className);
        $constructReflection = $reflectionClass->getConstructor();
        $params = [];

        if($constructReflection !== null) {
            foreach ($constructReflection->getParameters() as $reflectionParameter) {

                $name = $reflectionParameter->getName();
                $type = $reflectionParameter->getType()?->getName();
                if(array_key_exists($type, $this->services)) {
                    $params[$name] = $this->getDependency($type);
                }
            }
        }

        return $reflectionClass->newInstanceArgs([...$params, ...$args]);
    }


    /**
     * @param string|Closure $function
     * @param mixed $objectOrClass
     * @param array $args
     * @return mixed
     * @throws \ReflectionException
     */
    public function callFunction(string|Closure $function, mixed $objectOrClass, array $args = []): mixed
    {
        if($objectOrClass === null){
            $reflection = new ReflectionFunction($function);
        } else {
            $reflection = new ReflectionMethod($objectOrClass, $function);
            $instance = $objectOrClass;
            if(is_string($objectOrClass)){
                $instance = $reflection->isStatic() ? null : $this->createObject($objectOrClass);
            }
        }
        $params = [];
        foreach ($reflection->getParameters() as $reflectionParameter)
        {
            $name = $reflectionParameter->getName();
            $type = $reflectionParameter->getType()?->getName();
            if(array_key_exists($type, $this->services)){
                $params[$name] = $this->getDependency($type);
            }
        }
        if($objectOrClass === null){
            return $reflection->invokeArgs([...$params, ...$args]);
        } else {
            return $reflection->invokeArgs($instance, [...$params, ...$args]);
        }
    }
}