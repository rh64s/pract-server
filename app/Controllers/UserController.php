<?php

namespace Controllers;

use Debug\DebugTools;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Models\Role;
use Models\User;
use Src\Auth\Auth;
use Src\Request;
use Src\Validator\Validator;
use Src\View;

class UserController
{
    public function store(Request $request): string
    {
        $user = Auth::user();
        $can_create = $user->role->id === 1 ? [2,3] : [3];
        if ($request->method === 'POST') {
            $validator = new Validator($request->all(), [
                'name' => ['required', 'max:255', 'min:3'],
                'email' => ['required', 'email', 'max:255', 'unique:users,email', 'min:5'],
                'surname' => ['required', 'max:255', 'min:3'],
                'phone' => ['required', 'max:13', 'min:3', 'unique:users,phone', 'phone'],
                'patronymic' => ['max:255'],
                'role_id' => ['required', 'exists:roles,id'],
                'login' => ['required', 'unique:users,login', 'max:255', 'min:3', 'regex:/^[a-zA-Z0-9]*$/'],
                'password' => ['required', 'min:5', 'max:255']
            ], [
                'required' => 'Поле :field пусто',
                'unique' => 'Значение для :field не подходит! Оно уже занято',
                'exists' => 'Вы ввели несуществующее значение в поле :field!',
                'max' => 'Длина поля :field слишком длинное! Максимум: :value',
                'min' => 'Длина поля :field слишком короткое! Минимум: :value',
                'regex' => 'Поле логина должно иметь только латинские буквы и цифры',
                'email' => 'Поле почты не является почтой',
                'phone' => 'Вы ввели не номер телефона (только цифры)'
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
    public function index(Request $request): string
    {
        $user = Auth::user();
        $current_role = 3;
        $role = $user->role->id;
        $can_view = $role === 1 ? [2,3] : [3];
        if($request->method === 'POST') {
            if(!in_array($request->all()['choiced_role_id'], $can_view)) {
                return new View('site.users.index', ['can_view' => $can_view, 'current_role' => $current_role, 'message' => 'Вы не можете просматривать эту группу']);
            }
            $current_role = $request->all()['choiced_role_id'];

            if(isset($request->all()['search']) && !empty($request->all()['search'])) {
                $users = User::searchNameRoleAttribute($request->all()['search'], 2);
                return new View('site.users.index', [
                    'can_view' => $can_view,
                    'users' => !empty($users) ? $users : null,
                    'current_role' => $current_role,
                    'search_text' => $request->all()['search'],
                ]);
            }

        }
        return new View('site.users.index', ['can_view' => $can_view, 'users' => User::all()->where('role_id', $current_role)->sortBy('surname')->sortBy('name')->sortBy('patronymic'), 'current_role' => $current_role]);

    }

    public function delete($user_id): string
    {
        return "sas";
    }

    public function show(Request $request): string
    {
        if (((string) gettype((int) $request->get('id'))) == 'NULL') {
            app()->route->redirect('/users');
        }
        $user = Auth::user();
        try {
            $current_user = User::findOrFail((int) $request->get('id'));
        } catch (ModelNotFoundException $e) {
            app()->route->redirect('/users');
        }
        $can_role = $user->role->id === 1 ? [2,3] : [3];

        if ($request->method === 'POST') {
            $validator = new Validator($request->all(), [
                'name' => ['required', 'max:255', 'min:3'],
                'email' => $request->all()['email'] === $user->email ? ['required', 'email', 'max:255', 'unique:users,email', 'min:5'] : ['required', 'email', 'max:255', 'min:5'],
                'surname' => ['required', 'max:255', 'min:3'],
                'phone' => $request->all()['phone'] === $user->phone ? ['required', 'max:13', 'min:3', 'unique:users,phone', 'phone'] : ['required', 'max:255', 'min:3', 'phone'],
                'patronymic' => ['max:255'],
                'role_id' => ['required', 'exists:roles,id'],
                'login' => $request->all()['login'] === $user->phone ? ['required', 'unique:users,login', 'max:255', 'min:3', 'regex:/^[a-zA-Z0-9]*$/'] : ['required', 'max:255', 'min:3', 'regex:/^[a-zA-Z0-9]*$/'],
            ], [
                'required' => 'Поле :field пусто',
                'unique' => 'Значение для :field не подходит! Оно уже занято',
                'exists' => 'Вы ввели несуществующее значение в поле :field!',
                'max' => 'Длина поля :field слишком длинное! Максимум: :value',
                'min' => 'Длина поля :field слишком короткое! Минимум: :value',
                'regex' => 'Поле логина должно иметь только латинские буквы и цифры',
                'email' => 'Поле почты не является почтой',
                'phone' => 'Вы ввели не номер телефона (только цифры)'
            ]);

            if($validator->fails()){
                return new View('site.create-user',
                    ['user' => $current_user, 'message' => json_encode($validator->errors(), JSON_UNESCAPED_UNICODE), 'can_create' => $can_create]);
            }

            if ($current_user->update($request->all())) {
                return new View('site.users.show', [
                    'message' => ('Обновлено успешно!'),
                    'can_role' => $can_role,
                    'user' => $current_user]);
            }
        }
        return new View('site.users.show', ['user' => $current_user, 'can_role' => $can_role]);
    }

}