<?php

namespace App\Controller;

use App\Entity\Post;
use App\Middleware\JWT;
use Framework\HTTP\JsonResponse;
use Framework\HTTP\Request;
use Framework\HTTP\Response;
use Framework\ORM\EntityManagerInterface;
use Framework\ORM\Query;
use Framework\Routing\Attributes\Route;
use Framework\Routing\Controllers\BaseController;
use Framework\Validation\Validator;

class PostController extends BaseController
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    private function checkJwt(Request $req, JWT $jwt)
    {
        if ($jwt->user === null) {
            return new JsonResponse(["message" => "Unauthorized"], 401);
        }

        if ($jwt->user->getRole() !== 'ROLE_USER') {
            return new JsonResponse(["message" => "Forbidden: Insufficient role"], 403);
        }
    }

    #[Route(name: "post-get-all", path: "/post", methods: [Request::METHOD_GET])]
    public function getAll(Request $req, JWT $jwt): Response
    {
        $checkResponse = $this->checkJwt($req, $jwt);
        if ($checkResponse) {
            return $checkResponse;
        }

        return new JsonResponse($this->em->getRepository(Post::class)->findAll());
    }

    #[Route(name: "post-get-by-id", path: "/post/{id}", methods: [Request::METHOD_GET])]
    public function getById(Request $req, string $id, JWT $jwt): Response
    {
        $checkResponse = $this->checkJwt($req, $jwt);
        if ($checkResponse) {
            return $checkResponse;
        }

        return new JsonResponse($this->em->getRepository(Post::class)->find($id));
    }

    #[Route(name: "post-create", path: "/post", methods: [Request::METHOD_POST])]
    public function create(Request $request, JWT $jwt): Response
    {
        $checkResponse = $this->checkJwt($request, $jwt);
        if ($checkResponse) {
            return $checkResponse;
        }

        $body = $request->getContent();

        if (!isset($body["title"], $body["content"], $body["category"])) {
            return new JsonResponse(["message" => "Missing fields in body"], 400);
        }

        $v = new Validator();

        if (!$v->for($body["title"])
            ->not()->empty()
            ->type("string")
            ->check()
        ) {
            return new JsonResponse(["message" => "Invalid title"], 400);
        }

        if (!$v->for($body["content"])
            ->not()->empty()
            ->type("string")
            ->check()
        ) {
            return new JsonResponse(["message" => "Invalid content"], 400);
        }

        if (!$v->for($body["category"])
            ->not()->empty()
            ->type("string")
            ->check()
        ) {
            return new JsonResponse(["message" => "Invalid category"], 400);
        }

        $post = new Post();
        $post->setTitle($body["title"])
            ->setContent($body["content"])
            ->setCategory($body["category"]);

        $this->em->persist($post);
        $this->em->flush();

        $criteria = $post->getStateSnapshot();
        unset($criteria[$post->getIdColumn()]);

        return new JsonResponse($this->em->getRepository(Post::class)->findOneBy($criteria, orderBy: [
            $post->getIdColumn(),
            Query::ORDER_DESC
        ]));
    }

    #[Route(name: "posts-get-by-category", path: "/posts-by-category/{category}", methods: [Request::METHOD_GET])]
    public function getAllPostsByCategory(Request $req, string $category, JWT $jwt): Response
    {
        $checkResponse = $this->checkJwt($req, $jwt);
        if ($checkResponse) {
            return $checkResponse;
        }

        $posts = $this->em->getRepository(Post::class)->findBy([
            'category' => $category
        ]);

        if (empty($posts)) {
            return new JsonResponse(["message" => "No posts found in this category"], 404);
        }

        return new JsonResponse($posts);
    }
}
