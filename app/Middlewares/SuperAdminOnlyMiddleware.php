<?php

namespace Middlewares;

use Src\Auth\Auth;
use Src\Request;
use Src\View;

class SuperAdminOnlyMiddleware
{
    public function handle(Request $request)
    {
        if (Auth::user()->role_id !== 1) {
            return (new View)->render('errors.403');
        }
    }
}
