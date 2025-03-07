<?php
namespace App\Controller;

use App\Entity\Comment;
use App\Entity\User;
use App\Middleware\JWT;
use Framework\HTTP\JsonResponse;
use Framework\HTTP\Request;
use Framework\HTTP\Response;
use Framework\ORM\EntityManagerInterface;
use Framework\ORM\Query;
use Framework\Routing\Attributes\Route;
use Framework\Routing\Controllers\BaseController;
use Framework\Validation\Validator;

class CommentController extends BaseController
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

    #[Route(name: "comment-get-all", path: "/comments", methods: [Request::METHOD_GET])]
    public function getAll(Request $req, JWT $jwt): Response
    {
        $checkResponse = $this->checkJwt($req, $jwt);
        if ($checkResponse) {
            return $checkResponse;
        }

        return new JsonResponse($this->em->getRepository(Comment::class)->findAll());
    }

    #[Route(name: "comment-get-by-id", path: "/comment/{id}", methods: [Request::METHOD_GET])]
    public function getById(Request $req, int $id, JWT $jwt): Response
    {
        $checkResponse = $this->checkJwt($req, $jwt);
        if ($checkResponse) {
            return $checkResponse;
        }

        return new JsonResponse($this->em->getRepository(Comment::class)->find($id));
    }

    #[Route(name: "comment-create", path: "/comment", methods: [Request::METHOD_POST])]
    public function create(Request $request, JWT $jwt): Response
    {
        $checkResponse = $this->checkJwt($request, $jwt);
        if ($checkResponse) {
            return $checkResponse;
        }

        $body = $request->getContent();

        if (!isset($body["content"], $body["postId"])) {
            return new JsonResponse(["message" => "Missing fields in body"], 400);
        }

        $v = new Validator();

        if (!$v->for($body["content"])
            ->not()->empty()
            ->type("string")
            ->check()
        ) {
            return new JsonResponse(["message" => "Invalid content"], 400);
        }

        if (!$v->for($body["postId"])
            ->not()->empty()
            ->type("string")
            ->check()
        ) {
            return new JsonResponse(["message" => "Invalid postId"], 400);
        }



        $comment = new Comment();
        $comment
            ->setContent($body["content"])
            ->setPostId((int) $body["postId"])
            ->setAuthorId($jwt->user->getId())
            ->setCreatedAt(new \DateTime());

        $this->em->persist($comment);
        $this->em->flush();

        $criteria = $comment->getStateSnapshot();
        unset($criteria[$comment->getIdColumn()]);

//        var_dump($this->em->getRepository(Comment::class)->findOneBy([
//            'postId' => $body["postId"]
//        ]));

        return new JsonResponse($this->em->getRepository(Comment::class)->findOneBy($criteria, orderBy: [
            $comment->getIdColumn(),
            Query::ORDER_DESC
        ]));
    }

    #[Route(name: "comments-get-by-post", path: "/comments/{postId}", methods: [Request::METHOD_GET])]
    public function getCommentsByPost(Request $req, int $postId, JWT $jwt): Response
    {
        $checkResponse = $this->checkJwt($req, $jwt);
        if ($checkResponse) {
            return $checkResponse;
        }

        $comments = $this->em->getRepository(Comment::class)->findBy(['postId' => $postId]);

        if (empty($comments)) {
            return new JsonResponse(["message" => "No comments found for this post"], 404);
        }


        $commentsData = array_map(function ($comment) {
            $author = $this->em->getRepository(User::class)->find($comment->getAuthorId());
            return [
                'id' => $comment->getId(),
                'content' => $comment->getContent(),
                'createdAt' => $comment->getCreatedAt()->format("Y-m-d H:i:s"),
                'author' => [
                    'id' => $author->getId(),
                    'username' => $author->getUsername(),
                ],
            ];
        }, $comments);

        return new JsonResponse($commentsData);
    }

}
