<?php

namespace Framework;

use Exception;
use Framework\Config\ConfigParser;
use Framework\DependencyInjection\DependencyManager;
use Framework\Handling\Handler;
use Framework\Handling\MiddlewareInterface;
use Framework\HTTP\Request;
use Framework\HTTP\RequestParser;
use Framework\HTTP\Response;
use Framework\ORM\Connection;
use Framework\Routing\Router;

class Core
{
    public DependencyManager $dependencyManager;
    public array $handlers;

    public function __construct()
    {
        ConfigParser::parseConfig();
        $this->dependencyManager = new DependencyManager();
        $this->handlers = [];
        $this->use(\Framework\HTTP\RequestParser::class);
    }

    public function use(string $classname): void
    {
        if (!is_subclass_of($classname, MiddlewareInterface::class)) {
            throw new Exception("$classname is not an implementation of " . MiddlewareInterface::class);
        }

        $handler = new Handler($classname, $this->dependencyManager);

        if (count($this->handlers) > 0) {
            /** @var Handler $last */
            $last = end($this->handlers);
            $last->setNext(fn() => $handler->execute());
        }

        $this->handlers[] = $handler;
    }

    public function handle(): string
    {
        $this->use(\Framework\Routing\Router::class);
        $this->handlers[0]->execute();

        return (string)$this->dependencyManager->getDependency(Response::class);
    }

}