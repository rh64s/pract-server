<?php

namespace Controllers;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Models\UnitType;
use Src\Auth\Auth;
use Src\Request;
use Src\Validator\Validator;
use Src\View;

class UnitTypeController
{
    public function index(Request $request): string
    {
        $user = Auth::user();

        if ($request->method === 'POST' && !empty($request->get('search'))) {
            $unit_types = UnitType::where('name', 'LIKE', '%' . $request->get('search') . '%')->get();
            return new View('site.unit-types.index', [
                'unit_types' => $unit_types,
                'search_text' => $request->get('search'),
            ]);
        }
        return new View('site.unit-types.index', ['unit_types' => UnitType::all()->sortBy('name')]);
    }

    public function store(Request $request): string
    {
        $user = Auth::user();

        if ($request->method === 'POST') {
            $validator = new Validator($request->all(), [
                'name' => ['required', 'max:255', 'min:3', 'unique:unit_types,name'],
            ], [
                'required' => 'Поле :field пусто',
                'unique' => 'Значение для :field не подходит! Оно уже занято',
                'max' => 'Длина поля :field слишком длинное!',
                'min' => 'Длина поля :field слишком короткое!',
            ]);

            if ($validator->fails()) {
                return new View('site.unit-types.create',
                    ['message' => json_encode($validator->errors(), JSON_UNESCAPED_UNICODE)]);
            }

            if (UnitType::create($request->all())) {
                app()->route->redirect('/unit-types');
            }
        }
        return new View('site.unit-types.create');
    }

    public function show(Request $request): string
    {
        $user = Auth::user();

        try {
            $unit_type = UnitType::findOrFail((int)$request->get('id'));
        } catch (ModelNotFoundException $e) {
            app()->route->redirect('/unit-types');
        }

        if ($request->method === 'POST') {
            $validator = new Validator($request->all(), [
                'name' => ['required', 'max:255', 'min:3', 'unique:unit_types,name,' . $unit_type->id],
            ], [
                'required' => 'Поле :field пусто',
                'unique' => 'Значение для :field не подходит! Оно уже занято',
                'max' => 'Длина поля :field слишком длинное! Максимум: :value',
                'min' => 'Длина поля :field слишком короткое! Минимум: :value',
            ]);

            if ($validator->fails()) {
                return new View('site.unit-types.show',
                    ['unit_type' => $unit_type, 'message' => json_encode($validator->errors(), JSON_UNESCAPED_UNICODE)]);
            }

            if ($unit_type->update($request->all())) {
                return new View('site.unit-types.show', [
                    'message' => ('Обновлено успешно!'),
                    'unit_type' => $unit_type,
                ]);
            }
        }
        return new View('site.unit-types.show', ['unit_type' => $unit_type]);
    }

    public function delete(Request $request): void
    {
        $user = Auth::user();

        $validator = new Validator($request->all(), [
            'id' => ['required', 'exists:unit_types,id'],
        ], [
            'required' => 'Поле :field пусто',
            'exists' => 'Тип единицы не найден',
        ]);

        if ($validator->fails()) {
            // You might want to add error feedback here
            app()->route->redirect('/unit-types');
        }

        try {
            UnitType::destroy((int)$request->get('id'));
        } catch (Exception $e) {
            // Log the error
            throw new \Error($e);
        }
        app()->route->redirect('/unit-types');
    }
}