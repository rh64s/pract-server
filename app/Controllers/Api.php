<?php
namespace Controllers;

use Models\Product;
use Src\Auth\Auth;
use Src\Auth\TokenWorker;
use Src\Request;
use Src\View;

class Api
{
    public function index(): void
    {
        $products = Product::all()->toArray();

        (new View())->toJSON($products);
    }

    public function echo(Request $request): void
    {
        (new View())->toJSON($request->all());
    }

    public function login(Request $request): void
    {
        if (Auth::attempt($request->all())) {
            (new View())->toJSON(['token' => TokenWorker::generateToken(Auth::user())]);
        }
    }

    public function profile(Request $request): void
    {
        (new View())->toJSON(Auth::user()->toArray());
    }
}