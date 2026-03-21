<?php

namespace Middlewares;

use Illuminate\Auth\Access\AuthorizationException;
use Src\Auth\Auth;
use Src\Request;

class SuperAdminOnlyMiddleware
{
    public function handle(Request $request)
    {
        if (Auth::user()->role_id !== 1) {
            throw new AuthorizationException();
        }
    }
}
