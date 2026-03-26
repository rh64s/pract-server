<?php

namespace Controllers;

use Debug\DebugTools;
use Models\Role;
use Models\User;
use Src\Request;
use Models\Post;
use Src\Validator\Validator;
use Src\View;
use Src\Auth\Auth;

class AuthController
{
    public function hello(): string
    {
        $user = Auth::user();
        return new View('site.hello', ['user' => $user]);
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