<?php
namespace App\Controller;

use Framework\HTTP\Response;

class TestController
{
    public function test(): Response
    {
        return new Response("Hello from test controller", 200);
    }
    public function testById(string $id): Response
    {
        return new Response("Hello from test controller 2 here is some id {$id}");
    }
    public function testByIdWithResult(string $testId, string $resultId): Response
    {
        return new Response("Hello from test $testId with Result $resultId");
    }
}