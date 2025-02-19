<?php

namespace App\Middleware;

use App\Entity\User;
use Closure;
use Framework\DependencyInjection\DependencyManagerInterface;
use Framework\Handling\MiddlewareInterface;
use Framework\HTTP\Request;
use Framework\ORM\EntityManagerInterface;
use ReallySimpleJWT\Decode;
use ReallySimpleJWT\Parse;
use ReallySimpleJWT\Token;

class JWT implements MiddlewareInterface
{
    public ?string $token = null;
    public ?array $payload = null;
    public ?User $user = null;

    public function __construct(Request $req, EntityManagerInterface $em)
    {

        if(!isset($req->getHeaders()["Authorization"]) || !str_starts_with($req->getHeaders()["Authorization"], "Bearer ")){
            return;
        }

        $token = str_replace("Bearer ", "", $req->getHeaders()["Authorization"]);

        if(!$this->validateToken($token)){
            return;
        }

        $this->token = $token;

        $jwt = new \ReallySimpleJWT\Jwt($token);
        $parse = new Parse($jwt, new Decode());
        $this->payload = $parse->parse()->getPayload();

        $this->user = $em->getRepository(User::class)->find($this->payload["id"]);
    }

    public function validateToken(string $token): bool
    {
        return Token::validate($token, $_ENV["JWT_KEY"]) && Token::validateExpiration($token);
    }

    public function generateToken(array $data, int $exp = 172800): string
    {
        $now = time();
        $data["iat"] = $now;
        $data["exp"] = $now + $exp;

        return Token::customPayload($data, $_ENV["JWT_KEY"]);
    }

    public static function middleware(DependencyManagerInterface $dependencyManager, Closure $next): void
    {
        $dependencyManager->getDependency(self::class);
        $next();
    }

}