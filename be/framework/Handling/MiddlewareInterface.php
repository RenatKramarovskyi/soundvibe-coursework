<?php

namespace Framework\Handling;

use Closure;
use Framework\DependencyInjection\DependencyManagerInterface;

interface MiddlewareInterface
{
    /**
     * @param DependencyManagerInterface $dependencyManager
     * @param Closure $next
     * @return void
     */
    public static function middleware(DependencyManagerInterface $dependencyManager, Closure $next): void;
}