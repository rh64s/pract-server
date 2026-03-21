<?php

namespace Middlewares;

use Illuminate\Auth\Access\AuthorizationException;
use Src\Auth\Auth;
use Src\Request;

class AdminOnlyMiddleware
{
    public function handle(Request $request)
    {
        $role_id = Auth::user()->role_id;
        if ($role_id !== 1 || $role_id !== 2) {
            app()->route->redirect('/login');
        }
    }

}
