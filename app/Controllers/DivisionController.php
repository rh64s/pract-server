<?php

namespace Controllers;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Models\Division;
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
        $storekeepers = User::where('role_id', 3)
            ->doesntHave('division')
            ->get();

        if ($request->method === 'POST') {
            $validator = new Validator($request->all(), [
                'name' => ['required', 'max:255', 'min:3', 'unique:divisions,name'],
                'user_id' => ['required', 'exists:users,id', 'unique:divisions,user_id'],
            ], [
                'required' => 'Поле :field пусто',
                'unique' => 'Значение для :field не подходит! Оно уже занято',
                'exists' => 'Вы ввели несуществующее значение в поле :field!',
                'max' => 'Длина поля :field слишком длинное! Максимум: :value',
                'min' => 'Длина поля :field слишком короткое! Минимум: :value',
            ]);

            if($validator->fails()){
                return new View('site.divisions.create',
                    ['message' => json_encode($validator->errors(), JSON_UNESCAPED_UNICODE), 'storekeepers' => $storekeepers]);
            }

            if (Division::create($request->all())) {
                return new View('site.divisions.create', [
                    'message' => ('Создание подразделения прошло успешно.'),
                    'storekeepers' => $storekeepers
                ]);
            }
        }
        return new View('site.divisions.create', ['storekeepers' => $storekeepers]);
    }

    public function index(Request $request): string
    {
        $user = Auth::user();
        // Assuming only admin (role_id 1) can view all divisions
        if (!$user->isAdmin()) {
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

    public function delete(Request $request): void
    {
        // Assuming only admin (role_id 1) can delete divisions

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
            app()->route->redirect('/divisions');
        } catch (Exception $e) {
            throw new \Error($e);
        }
    }

    public function show(Request $request): string
    {
        if (((string) gettype((int) $request->get('id'))) == 'NULL') {
            app()->route->redirect('/divisions');
        }
        $user = Auth::user();
        // Assuming only admin (role_id 1) can manage divisions
        if (!$user->isAdmin()) {
            app()->route->redirect('/divisions');
        }

        try {
            $current_division = Division::findOrFail((int) $request->get('id'));
        } catch (ModelNotFoundException $e) {
            app()->route->redirect('/divisions');
        }

        $storekeepers = User::where('role_id', 3)
            ->where(function ($query) use ($current_division) {
                $query->doesntHave('division')
                    ->orWhereHas('division', function ($query) use ($current_division) {
                        $query->where('id', $current_division->id);
                    });
            })
            ->get();

        if ($request->method === 'POST') {
            $validator = new Validator($request->all(), [
                'name' => ['required', 'max:255', 'min:3', ( $request->get('name') !== $current_division->name ? ('unique:divisions,name') : '')],
                'user_id' => ['exists:users,id', ( $request->get('user_id') !== $current_division->user_id ? ('unique:divisions,name') : '')],
            ], [
                'required' => 'Поле :field пусто',
                'unique' => 'Значение для :field не подходит! Оно уже занято',
                'exists' => 'Вы ввели несуществующее значение в поле :field!',
                'max' => 'Длина поля :field слишком длинное! Максимум: :value',
                'min' => 'Длина поля :field слишком короткое! Минимум: :value',
            ]);

            if($validator->fails()){
                return new View('site.divisions.show',
                    ['division' => $current_division, 'message' => json_encode($validator->errors(), JSON_UNESCAPED_UNICODE), 'storekeepers' => $storekeepers]);
            }

            if ($current_division->update($request->all())) {
                return new View('site.divisions.show', [
                    'message' => ('Обновлено успешно!'),
                    'division' => $current_division,
                    'storekeepers' => $storekeepers
                ]);
            }
        }
        return new View('site.divisions.show', ['division' => $current_division, 'storekeepers' => $storekeepers]);
    }
}
