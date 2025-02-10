<?php

namespace App\Middleware;

use Closure;
use Framework\DependencyInjection\DependencyManagerInterface;
use Framework\Handling\MiddlewareInterface;
use Framework\HTTP\Request;
use ReallySimpleJWT\Decode;
use ReallySimpleJWT\Parse;
use ReallySimpleJWT\Token;

class JWT implements MiddlewareInterface
{
    public array $data;

    public function __construct(Request $req)
    {
        $this->data = [];

        if(!isset($req->getHeaders()["Authorization"]) || !str_starts_with($req->getHeaders()["Authorization"], "Bearer ")){
            return;
        }

        $token = str_replace("Bearer ", "", $req->getHeaders()["Authorization"]);

        if(!$this->validateToken($token)){
            return;
        }

        $this->data["token"] = $token;

        $jwt = new \ReallySimpleJWT\Jwt($token);
        $parse = new Parse($jwt, new Decode());
        $this->data["payload"] = $parse->parse()->getPayload();
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