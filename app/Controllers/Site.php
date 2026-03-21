<?php

namespace Controllers;

use Debug\DebugTools;
use Models\User;
use Src\Request;
use Models\Post;
use Src\View;
use Src\Auth\Auth;

class Site
{
    public function index(): string
    {
        $posts = Post::all();
        return (new View())->render('site.post', ['posts' => $posts]);
    }

    public function show(Request $request): string
    {
        $posts = Post::where('id', $request->id)->get();
        return (new View())->render('site.post', ['posts' => $posts]);
    }

    public function hello(): string
    {
        return new View('site.hello', ['message' => 'hello working']);
    }

    public function signup(Request $request): string
    {
        $user = Auth::user();
        if (!$user) {
            app()->route->redirect('/login');
        }
        if ($request->method === 'POST') {
            if (User::all()->count() === 0) {
                Auth::login(User::create(array_merge($request->all(), ['role_id' => 1])));
            } else {
                Auth::login(User::create(array_merge($request->all(), ['role_id' => ($user->role_id === 1 ? 2 : 3)])));
            }
            app()->route->redirect('/go');
        }
        return new View('site.create-user');
    }

    public function login(Request $request): string
    {
        //Если просто обращение к странице, то отобразить форму
        if ($request->method === 'GET') {
            return new View('site.login');
        }
        //Если удалось аутентифицировать пользователя, то редирект
        if (Auth::attempt($request->all())) {
            app()->route->redirect('/hello');
        }
        //Если аутентификация не удалась, то сообщение об ошибке
        return new View('site.login', ['message' => 'Неправильные логин или пароль']);
    }

    public function logout(): void
    {
        Auth::logout();
        app()->route->redirect('/hello');
    }
}