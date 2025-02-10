<?php

namespace App\Controller;

use App\Middleware\JWT;
use Framework\HTTP\JsonResponse;
use Framework\HTTP\Request;
use Framework\HTTP\Response;
use Framework\Routing\Attributes\Route;
use Framework\Routing\Controllers\BaseController;

class LoginController extends BaseController
{
    #[Route(name: "login", path: "/login", methods: [Request::METHOD_POST])]
    public function login(Request $req, JWT $jwt) : Response
    {
        $body = $req->getContent();
        if (!isset($body["username"], $body["password"])) {
            return new JsonResponse(["message" => "Missing credentials"], 400);
        }

        $payload = [
            "id" => uniqid(),
            "username" => $body["password"],
            "role" => "ROLE_USER"
        ];

        $token = $jwt->generateToken($payload);

        return new JsonResponse(["token" => $token], 200);
    }

    #[Route(name: "check-token", path: "/check", methods: [Request::METHOD_GET])]
    public function check(JWT $jwt) : Response
    {
        return new JsonResponse($jwt->data, 200);
    }

}