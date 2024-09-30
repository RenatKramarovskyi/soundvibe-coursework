<?php
namespace App\Controller;

use Framework\Context;
use Framework\HTTP\JsonResponse;
use Framework\HTTP\Request;
use Framework\HTTP\Response;
use Framework\Routing\Attributes\Route;
use Framework\Routing\Controllers\BaseController;

class UserController extends BaseController
{
    #[Route(
        name: "get-all-users",
        path: "/user",
        methods: [Request::METHOD_GET]
    )]
    public function getAllUser(): Response
    {
        $sql = "SELECT * FROM user";

        $users = Context::$connection->execute($sql);
        return new JsonResponse($users);
    }
    #[Route(
        name: "create-user",
        path: "/user",
        methods: [Request::METHOD_POST]
    )]
    public function createUser(): Response
    {
        $body = Context::$request->getContent();
        $user = [
            "username" => $body["username"],
            "sex" => $body["sex"] ? 1 : 0
        ];

        $sql = "INSERT INTO user (username, sex) VALUES (:username, :sex)";

        $users = Context::$connection->execute($sql, $user);
        return new JsonResponse($user);
    }
}