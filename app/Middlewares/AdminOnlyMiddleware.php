<?php

namespace Middlewares;

use Illuminate\Auth\Access\AuthorizationException;
use Src\Auth\Auth;
use Src\Request;
use Src\View;

class AdminOnlyMiddleware
{
    public function handle(Request $request)
    {
        $role_id = Auth::user()->role_id;
        if ($role_id !== 1 && $role_id !== 2) {
            return (new View)->render('errors.403');
        }
    }

}
