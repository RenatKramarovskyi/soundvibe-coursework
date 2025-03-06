<?php

namespace App\Controller;

use Framework\Files\Image;
use Framework\HTTP\JsonResponse;
use Framework\HTTP\Request;
use Framework\HTTP\Response;
use Framework\Routing\Attributes\Route;
use Framework\Routing\Controllers\BaseController;

class PicsController extends BaseController
{

    #[Route(name: "pics-index", path: "/pics", methods: [Request::METHOD_POST])]
    public function index(Request $req): Response
    {
        var_dump($req->getHeaders());

        if(!isset($req->getFiles()["pic"])) {
            return new JsonResponse(["message" => "pic is not provided", 400]);

        }
        $image = Image::upload($req->getFiles()["pic"], "pic.jpeg", [400, 200]);
        return new JsonResponse($image);
    }
}