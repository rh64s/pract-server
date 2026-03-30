<?php

namespace Controllers;

use Debug\DebugTools;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use Src\Request;
use Src\View;
use Src\Auth\Auth;

class AuthController
{
    public function hello(): string
    {
        $user = Auth::user();
        $log = new Logger('in hello');
        $log->pushHandler(new StreamHandler('/opt/lampp/htdocs/pop-it-mvc/logs.log', Level::Info));
        $log->info(Auth::check());
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
        app()->auth::logout();
        app()->route->redirect('/hello');
        return $this->hello();
    }
}