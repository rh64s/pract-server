<?php

namespace Middlewares;

use Src\Auth\Auth;
use Src\Request;
use Src\View;
use Src\Auth\TokenWorker;

class TokenMiddleware
{
    public function handle(Request $request, callable $next)
    {
        $headerAuth = $_SERVER['HTTP_AUTHORIZATION'] ?? getallheaders()['Authorization'] ?? '';

        if (preg_match('/Bearer\s(\S+)/', $headerAuth, $matches)) {
            $token = $matches[1];
//            $user = User::find(Manager::table('sessions')->where('token', $token)->first()->user_id);
            $user = TokenWorker::user($token);
            if ($user) {
                Auth::login($user);
                return $next ? $next($request) : $request;
            }
        }

        http_response_code(401);
        (new View())->toJSON(['message' => 'Unauthorized.']);
        exit();
    }
}