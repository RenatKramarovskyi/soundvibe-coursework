<?php
namespace App\Controller;

use Framework\Context;
use Framework\HTTP\JsonResponse;
use Framework\HTTP\Request;
use Framework\HTTP\Response;
use Framework\ORM\QueryBuilder;
use Framework\Routing\Attributes\Route;
use Framework\Routing\Controllers\BaseController;

class UserController extends BaseController
{
    #[Route(
        name: "get-all-users",
        path: "/user",
        methods: [Request::METHOD_GET]
    )]
    public function getAllUser(Context $context): Response
    {
        $qb = new QueryBuilder();
        $qb->select("user");

        $users = $context->connection->execute($qb->getQuery());
        return new JsonResponse($users);
    }
    #[Route(
        name: "create-user",
        path: "/user",
        methods: [Request::METHOD_POST]
    )]
    public function createUser(Context $context): Response
    {
        $body = $context->request->getContent();
        $user = [
            "username" => $body["username"],
            "sex" => $body["sex"] ? 1 : 0
        ];

        $qb = new QueryBuilder();
        $qb->insert("user", ["username", "sex"])->addValues([":username",":sex"])->setParams($user);


        $users = $context->connection->execute($qb->getQuery());
        return new JsonResponse($user);
    }
}