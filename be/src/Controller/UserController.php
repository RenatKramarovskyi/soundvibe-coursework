<?php

namespace App\Controller;

use App\Entity\User;
use Framework\HTTP\JsonResponse;
use Framework\HTTP\Request;
use Framework\HTTP\Response;
use Framework\ORM\ConnectionInterface;
use Framework\ORM\QueryBuilder;
use Framework\Routing\Attributes\Route;
use Framework\Routing\Controllers\BaseController;

class UserController extends BaseController
{

    /**
     * @param ConnectionInterface $connection
     */
    public function __construct(private ConnectionInterface $connection)
    {
    }


    #[Route(name: "user-get-all", path: "/user", methods: [Request::METHOD_GET])]
    public function getAll(): Response
    {
        $qb = new QueryBuilder();

        $qb->select("user");
        $users = $this->connection->execute($qb->getQuery(), User::class);

        return new JsonResponse($users);
    }

    #[Route(name: "user-get-by-id", path: "/user/{id}", methods: [Request::METHOD_GET])]
    public function getById(Request $request, string $id): Response
    {
        $qb = new QueryBuilder();
        $qb->select("user")->where("id = :id")->setParams(["id"=>$id]);
        $user = $this->connection->execute($qb->getQuery());
        return new JsonResponse($user);
    }

    #[Route(name: "user-create", path: "/user", methods: [Request::METHOD_POST])]
    public function create(Request $request): Response
    {
        $body = $request->getContent();

        $user = [
            "username" => $body["username"],
            "sex" => $body["sex"]
        ];

        $qb = new QueryBuilder();
        $qb->insert("user", ["username", "sex"])->addValues([":username", ":sex"])->setParams($user);

        $this->connection->execute($qb->getQuery());

        return new JsonResponse($user);
    }
}