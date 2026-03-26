<?php

namespace Controllers;

use Debug\DebugTools;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Models\Division;
use Models\Role;
use Models\User;
use Src\Auth\Auth;
use Src\Request;
use Src\Validator\Validator;
use Src\View;

class DivisionController
{
    public function store(Request $request): string
    {
        $user = Auth::user();
        // Assuming only admin (role_id 1) can create divisions
        if ($user->role->id !== 1 || $user->role->id !== 2) {
            app()->route->redirect('/divisions');
        }

        if ($request->method === 'POST') {
            $validator = new Validator($request->all(), [
                'name' => ['required', 'max:255', 'min:3', 'unique:divisions,name'],
                'user_id' => ['required', 'exists:users,id'],
            ], [
                'required' => 'Поле :field пусто',
                'unique' => 'Значение для :field не подходит! Оно уже занято',
                'exists' => 'Вы ввели несуществующее значение в поле :field!',
                'max' => 'Длина поля :field слишком длинное! Максимум: :value',
                'min' => 'Длина поля :field слишком короткое! Минимум: :value',
            ]);

            if($validator->fails()){
                return new View('site.divisions.create',
                    ['message' => json_encode($validator->errors(), JSON_UNESCAPED_UNICODE)]);
            }

            if (Division::create($request->all())) {
                return new View('site.divisions.create', [
                    'message' => ('Создание подразделения прошло успешно.'),
                ]);
            }
        }
        return new View('site.divisions.create');
    }

    public function index(Request $request): string
    {
        $user = Auth::user();
        // Assuming only admin (role_id 1) can view all divisions
        if ($user->role->id !== 1) {
            app()->route->redirect('/'); // Redirect to a more appropriate page for non-admins
        }

        if($request->method === 'POST') {
            if(isset($request->all()['search']) && !empty($request->all()['search'])) {
                $divisions = Division::where('name', 'LIKE', '%' . $request->all()['search'] . '%')->get();
                return new View('site.divisions.index', [
                    'divisions' => !empty($divisions) ? $divisions : null,
                    'search_text' => $request->all()['search'],
                ]);
            }
        }
        return new View('site.divisions.index', ['divisions' => Division::all()->sortBy('name')]);
    }

    public function delete(Request $request): string
    {
        $user = Auth::user();
        // Assuming only admin (role_id 1) can delete divisions
        if ($user->role->id !== 1 || $user->role->id !== 2) {
            app()->route->redirect('/divisions');
        }

        $validator = new Validator($request->all(), [
            'id' => ['required', 'exists:divisions,id', 'regex:/^[0-9]*$/'],
        ], [
            'required' => 'Поле :field пусто',
            'exists' => 'Подразделение не найдено',
            'regex' => 'Некорректный формат ID',
        ]);

        if ($validator->fails()) {
            app()->route->redirect('/divisions');
        }

        try {
            Division::destroy((int) $request->get('id'));
        } catch (Exception $e) {
            throw new \Error($e);
        }
        app()->route->redirect('/divisions');
    }

    public function show(Request $request): string
    {
        if (((string) gettype((int) $request->get('id'))) == 'NULL') {
            app()->route->redirect('/divisions');
        }
        $user = Auth::user();
        // Assuming only admin (role_id 1) can manage divisions
        if ($user->role->id !== 1) {
            app()->route->redirect('/divisions');
        }

        try {
            $current_division = Division::findOrFail((int) $request->get('id'));
        } catch (ModelNotFoundException $e) {
            app()->route->redirect('/divisions');
        }

        if ($request->method === 'POST') {
            $validator = new Validator($request->all(), [
                'name' => ['required', 'max:255', 'min:3', 'unique:divisions,name,' . $current_division->id],
                'user_id' => ['exists:users,id', 'regex:/^[0-9]*$/'],
            ], [
                'required' => 'Поле :field пусто',
                'unique' => 'Значение для :field не подходит! Оно уже занято',
                'exists' => 'Вы ввели несуществующее значение в поле :field!',
                'max' => 'Длина поля :field слишком длинное! Максимум: :value',
                'min' => 'Длина поля :field слишком короткое! Минимум: :value',
            ]);

            if($validator->fails()){
                return new View('site.divisions.show',
                    ['division' => $current_division, 'message' => json_encode($validator->errors(), JSON_UNESCAPED_UNICODE)]);
            }

            if ($current_division->update($request->all())) {
                return new View('site.divisions.show', [
                    'message' => ('Обновлено успешно!'),
                    'division' => $current_division]);
            }
        }
        return new View('site.divisions.show', ['division' => $current_division]);
    }
}
