<?php

namespace App\Controller;

use App\Entity\User;
use Framework\HTTP\JsonResponse;
use Framework\HTTP\Request;
use Framework\HTTP\Response;
use Framework\ORM\EntityManagerInterface;
use Framework\ORM\Query;
use Framework\Routing\Attributes\Route;
use Framework\Routing\Controllers\BaseController;
use Framework\Validation\Validator;

class UserController extends BaseController
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    #[Route(name: "user-get-all", path: "/user", methods: [Request::METHOD_GET])]
    public function getAll(): Response
    {
        return new JsonResponse($this->em->getRepository(User::class)->findAll());
    }

    #[Route(name: "user-get-by-id", path: "/user/{id}", methods: [Request::METHOD_GET])]
    public function getById(string $id): Response
    {
        return new JsonResponse($this->em->getRepository(User::class)->find($id));
    }

    #[Route(name: "user-create", path: "/user", methods: [Request::METHOD_POST])]
    public function create(Request $request): Response
    {
        $body = $request->getContent();
        if (!isset($body["username"], $body["email"], $body["password"], $body["sex"])) {
            return new JsonResponse(["message" => "Missing fields in body"], 400);
        }

        $v = new Validator();

        if (!$v->for($body["username"])
            ->not()->empty()
            ->type("string")
            ->check()
        ) {
            return new JsonResponse(["message" => "Invalid username"], 400);
        }

        if (!$v
            ->for($body["password"])
            ->password()
            ->check()
        ) {
            return new JsonResponse(["message" => "Invalid password"], 400);
        }

        if ($v->for($body["email"])
            ->email()
            ->check()
        ) {
            return new JsonResponse(["message" => "Invalid email"], 400);
        }

        if (!is_bool($body["sex"])) {
            return new JsonResponse(["message" => "Invalid sex, must be true or false"], 400);
        }

        $userCandidate = $this->em->getRepository(User::class)->findOneBy(["username" => $body["username"]]);
        if ($userCandidate) {
            return new JsonResponse(["message" => "User with this username already exists"], 409);
        }

        $userCandidate = $this->em->getRepository(User::class)->findOneBy(["email" => $body["email"]]);
        if ($userCandidate) {
            return new JsonResponse(["message" => "User with this email already exists"], 409);
        }

        $user = new User();
        $user->setUsername($body["username"])
            ->setEmail($body["email"])
            ->setSex($body["sex"])
            ->setRole(User::ROLE_USER);

        $hashedPassword = hash_hmac("sha256", $body["password"], $_ENV["PASSWORD_SECRET_KEY"]);
        $user->setPassword($hashedPassword);

        $this->em->persist($user);
        $this->em->flush();

        $criteria = $user->getStateSnapshot();
        unset($criteria[$user->getIdColumn()]);

        return new JsonResponse($this->em->getRepository(User::class)->findOneBy($criteria, orderBy: [
            $user->getIdColumn(),
            Query::ORDER_DESC
        ]));
    }
}
