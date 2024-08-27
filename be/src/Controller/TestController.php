<?php
namespace App\Controller;

use Framework\HTTP\Request;
use Framework\HTTP\Response;
use Framework\Routing\Attributes\Route;
use Framework\Routing\Controllers\BaseController;

class TestController extends BaseController
{
    #[Route(
        name: "test",
        path: "/test",
        methods: [Request::METHOD_GET]
    )]
    public function test(): Response
    {
        return new Response("Hello from test controller", 200);
    }

    #[Route(
        name: "test-by-id",
        path: "/test/{id}",
        methods: [Request::METHOD_GET]
    )]
    #[Route(
        name: "test-by-id-for-admin",
        path: "/test/{id}",
        methods: [Request::METHOD_GET, Request::METHOD_PATCH]
    )]

    public function testById(string $id): Response
    {
        return new Response("Hello from test controller 2 here is some id {$id}");
    }

    #[Route(
        name: "test-by-id-with-result",
        path: "/test/{testId}/result/{resultId}",
        methods: [Request::METHOD_GET]
    )]
    public function testByIdWithResult(string $testId, string $resultId): Response
    {
        return new Response("Hello from test $testId with Result $resultId");
    }
}