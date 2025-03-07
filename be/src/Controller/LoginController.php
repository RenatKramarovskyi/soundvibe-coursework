<?php

namespace App\Controller;

use App\Entity\User;
use App\Middleware\JWT;
use Framework\HTTP\JsonResponse;
use Framework\HTTP\Request;
use Framework\HTTP\Response;
use Framework\ORM\EntityManagerInterface;
use Framework\Routing\Attributes\Route;
use Framework\Routing\Controllers\BaseController;

class LoginController extends BaseController
{
    #[Route(name: "login", path: "/login", methods: [Request::METHOD_POST])]
    public function login(Request $req, JWT $jwt, EntityManagerInterface $em) : Response
    {
        $body = $req->getContent();
        if (!isset($body["email"], $body["password"])) {
            return new JsonResponse(["message" => "Missing credentials"], 400);
        }

        $user = $em->getRepository(User::class)->findOneBy(["email"=> $body["email"]]);

        if (!isset($user)) {
            return new JsonResponse(["Error" => "Error message: User with such username was not found"], 401);
        }

        $hashedPassword = hash_hmac("sha256", $body["password"], $_ENV["PASSWORD_SECRET_KEY"]);

        /** @var User $user */
        if ($user->getPassword() !== $hashedPassword) {
            return new JsonResponse(["Error" => "Error message: Incorrect password"], 401);
        }

        $payload = [
            "id" => $user->getId(),
            "role" => $user->getRole()
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