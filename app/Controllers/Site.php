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
        $can_create = $user->role->id === 1 ? [2,3] : [3];
        if ($request->method === 'POST') {
            $validator = new Validator($request->all(), [
                'name' => ['required', 'max:255'],
                'email' => ['required'],
                'surname' => ['required'],
                'phone' => ['required'],
                'patronymic' => [],
                'role_id' => ['required', 'exists:roles,id'],
                'login' => ['required', 'unique:users,login'],
                'password' => ['required']
            ], [
                'required' => 'Поле :field пусто',
                'unique' => 'Значение для :field не подходит! Оно уже занято',
                'exists' => 'Вы ввели несуществующее значение!'
            ]);

            if($validator->fails()){
                return new View('site.create-user',
                    ['message' => json_encode($validator->errors(), JSON_UNESCAPED_UNICODE), 'can_create' => $can_create]);
            }

            if (User::create($request->all())) {
                return new View('site.create-user', [
                    'message' => ('Создание ' . Role::$roles[$request->all()['role_id']] . ' прошло успешно.'),
                    'can_create' => $can_create]);
            }
        }
        return new View('site.create-user', ['can_create' => $can_create]);
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