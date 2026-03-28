<?php

namespace Controllers;

use Debug\DebugTools;
use Src\Request;
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
            return $this->hello();
        }
        //Если аутентификация не удалась, то сообщение об ошибке
        return new View('site.login', ['message' => 'Неправильный логин или пароль']);
    }

    public function logout(): string
    {
        Auth::logout();
        app()->route->redirect('/hello');
        return $this->hello();
    }
}