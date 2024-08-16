<?php

namespace Framework\Routing;

use App\Controller\TestController;
use Framework\Context;
use Framework\HTTP\Request;

class Router
{

    public static function execute(): void
    {

        $routes = [
            "test" => new Route(
                "test",
                "/test",
                [Request::METHOD_GET],
                TestController::class,
                "test"
            ),
            "testById" => new Route(
                "test-2",
                "/test/{id}",
                [Request::METHOD_GET],
                TestController::class,
                "testById"
            ),
            "testByIdWithResult" => new Route(
                "test-3",
                "/test/{testId}/result/{resultId}",
                [Request::METHOD_GET],
                TestController::class,
                "testByIdWithResult"
            ),
        ];

//        var_dump($routes);
            foreach ($routes as $route){
            if($route->matches(Context::$request)){
                Context::$response = $route->execute(Context::$request);
                return;
            }

        }

    }
}