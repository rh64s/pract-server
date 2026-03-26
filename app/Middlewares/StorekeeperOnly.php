<?php

namespace Middlewares;

use Src\Auth\Auth;
use Src\Request;
use Src\View;

class StorekeeperOnly
{
    public function handle(Request $request)
    {
        $role_id = Auth::user()->role_id;
        if ($role_id !== 3) {
            return (new View)->render('errors.403');
        }
    }

}
