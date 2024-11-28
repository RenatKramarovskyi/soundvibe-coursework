<?php

namespace Framework;

use Exception;
use Framework\Handling\Handler;
use Framework\Handling\MiddlewareInterface;
use Framework\HTTP\JsonResponse;
use Framework\HTTP\Request;
use Framework\HTTP\RequestParser;
use Framework\HTTP\Response;
use Framework\ORM\Connection;
use Framework\Routing\Router;

class Core
{
    public Context $context;
    public array $handlers;

    public function __construct()
    {
        $this->context = new Context();
        $this->handlers = [];

        $this->use(RequestParser::class);
        $this->use(Connection::class);
        $this->use(Router::class);
    }

    public function use(string $classname): void
    {
        if (!is_subclass_of($classname, MiddlewareInterface::class)) {
            throw new Exception("$classname is not an implementation of " . MiddlewareInterface::class);
        }

        $handler = new Handler($classname, [
            "context" => $this->context,
        ]);

        if (count($this->handlers) > 0) {
            /** @var Handler $last */
            $last = end($this->handlers);
            $last->setNext(fn() => $handler->execute());
        }

        $this->handlers[] = $handler;
    }

    public function handle(): string
    {
        $this->handlers[0]->execute();

        return (string)$this->context->response;
    }
}