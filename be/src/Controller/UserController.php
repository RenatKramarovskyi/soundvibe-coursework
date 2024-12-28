<?php
namespace App\Controller;

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

    #[Route(
        name: "get-all-users",
        path: "/user",
        methods: [Request::METHOD_GET]
    )]
    public function getAllUser(): Response
    {
        $qb = new QueryBuilder();
        $qb->select("user");

        $users = $this->connection->execute($qb->getQuery());
        return new JsonResponse($users);
    }

    #[Route(
        name: "get-user-by-id",
        path: "/user/{id}",
        methods: [Request::METHOD_GET]
    )]
    public function getById(Request $request, string $id): Response
    {
        $qb = new QueryBuilder();
        $qb->select("user")->where("id = :id")->setParams(["id" => $id]);
        $user = $this->connection->execute($qb->getQuery());
        return new JsonResponse($user);
    }

    #[Route(
        name: "create-user",
        path: "/user",
        methods: [Request::METHOD_POST]
    )]
    public function createUser(Request $request): Response
    {
        $body = $request->getContent();
        $user = [
            "username" => $body["username"],
            "sex" => $body["sex"] ? 1 : 0
        ];

        $qb = new QueryBuilder();
        $qb->insert("user", ["username", "sex"])->addValues([":username",":sex"])->setParams($user);


        $users = $this->connection->execute($qb->getQuery());
        return new JsonResponse($user);
    }
}