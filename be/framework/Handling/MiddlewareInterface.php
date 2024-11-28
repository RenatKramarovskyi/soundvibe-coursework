<?php

namespace Framework\Handling;

use Closure;
use Framework\Context;

interface MiddlewareInterface
{
    /**
     * @param Context $context
     * @param Closure $next
     * @return void
     */
    public static function middleware(Context $context, Closure $next): void;
}