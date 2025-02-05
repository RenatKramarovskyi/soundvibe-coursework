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

class UserController extends BaseController
{

    /**
     * @param EntityManagerInterface $em
     */
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

        $user = new User();
        $user->setUsername($body["username"])
            ->setSex($body["sex"]);

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