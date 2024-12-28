<?php

namespace Framework\Handling;

use Closure;
use Framework\DependencyInjection\DependencyManagerInterface;

class Handler
{
    /**
     * @var string
     */
    public string $middlewareClass;
    /**
     * @var DependencyManagerInterface
     */
    public DependencyManagerInterface $dependencyManager;
    /**
     * @var array
     */
    public array $args;
    /**
     * @var Closure
     */
    public Closure $next;

    /**
     * @param string $middlewareClass
     * @param DependencyManagerInterface $dependencyManager
     * @param array $args
     * @param Closure|null $next
     */
    public function __construct(string $middlewareClass, DependencyManagerInterface $dependencyManager, array $args = [], ?Closure $next = null)
    {
        $this->middlewareClass = $middlewareClass;
        $this->dependencyManager = $dependencyManager;
        $this->args = $args;
        $this->next = $next ?? fn () => 0;
    }

    /**
     * @return string
     */
    public function getMiddlewareClass(): string
    {
        return $this->middlewareClass;
    }

    /**
     * @param string $middlewareClass
     * @return $this
     */
    public function setMiddlewareClass(string $middlewareClass): self
    {
        $this->middlewareClass = $middlewareClass;
        return $this;
    }

    /**
     * @return array
     */
    public function getArgs(): array
    {
        return $this->args;
    }

    /**
     * @param array $args
     * @return $this
     */
    public function setArgs(array $args): self
    {
        $this->args = $args;
        return $this;
    }

    /**
     * @return Closure
     */
    public function getNext(): Closure
    {
        return $this->next;
    }

    /**
     * @param Closure $next
     * @return $this
     */
    public function setNext(Closure $next): self
    {
        $this->next = $next;
        return $this;
    }

    /**
     * @return void
     */
    public function execute() : void
    {
        $this->dependencyManager->callFunction("middleware", $this->middlewareClass,  [...$this->args, "next" => $this->next]);
    }
}