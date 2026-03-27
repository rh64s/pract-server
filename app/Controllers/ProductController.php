<?php

namespace Controllers;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Models\Product;
use Models\UnitType;
use Src\Auth\Auth;
use Src\Request;
use Src\Validator\Validator;
use Src\View;

class ProductController
{
    public function index(Request $request): string
    {
        $query = Product::with('unitType');

        if ($request->method === 'POST' && !empty($request->get('search'))) {
            $search = $request->get('search');
            $query->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('articul', 'LIKE', "%{$search}%");
        }

        $products = $query->orderBy('name')->get();

        return new View('site.products.index', [
            'products' => $products,
            'search_text' => $request->get('search') ?? null,
        ]);
    }

    public function store(Request $request): string
    {
        $unit_types = UnitType::orderBy('name')->get();

        if ($request->method === 'POST') {
            $validator = new Validator($request->all(), [
                'name' => ['required', 'max:255', 'min:3'],
                'articul' => ['required', 'max:255', 'unique:products,articul'],
                'unit_type_id' => ['required', 'exists:unit_types,id'],
            ], [
                'required' => 'Поле :field пусто',
                'unique' => 'Значение для :field не подходит! Оно уже занято',
                'exists' => 'Вы ввели несуществующее значение в поле :field!',
                'max' => 'Длина поля :field слишком длинное!',
                'min' => 'Длина поля :field слишком короткое!',
            ]);

            if ($validator->fails()) {
                return new View('site.products.create', [
                    'message' => json_encode($validator->errors(), JSON_UNESCAPED_UNICODE),
                    'unit_types' => $unit_types
                ]);
            }

            if (Product::create($request->all())) {
                app()->route->redirect('/products');
            }
        }

        return new View('site.products.create', ['unit_types' => $unit_types]);
    }

    public function show(Request $request): string
    {
        try {
            $product = Product::findOrFail((int)$request->get('id'));
        } catch (ModelNotFoundException $e) {
            app()->route->redirect('/products');
        }

        $unit_types = UnitType::orderBy('name')->get();

        if ($request->method === 'POST') {
            $validator = new Validator($request->all(), [
                'name' => ['required', 'max:255', 'min:3'],
                'articul' => ['required', 'max:255', 'unique:products,articul,' . $product->id],
                'unit_type_id' => ['required', 'exists:unit_types,id'],
            ], [
                'required' => 'Поле :field пусто',
                'unique' => 'Значение для :field не подходит! Оно уже занято',
                'exists' => 'Вы ввели несуществующее значение в поле :field!',
                'max' => 'Длина поля :field слишком длинное! Максимум: :value',
                'min' => 'Длина поля :field слишком короткое! Минимум: :value',
            ]);

            if ($validator->fails()) {
                return new View('site.products.show', [
                    'product' => $product,
                    'unit_types' => $unit_types,
                    'message' => json_encode($validator->errors(), JSON_UNESCAPED_UNICODE)
                ]);
            }

            if ($product->update($request->all())) {
                return new View('site.products.show', [
                    'message' => 'Обновлено успешно!',
                    'product' => $product,
                    'unit_types' => $unit_types
                ]);
            }
        }

        return new View('site.products.show', ['product' => $product, 'unit_types' => $unit_types]);
    }

    public function delete(Request $request): void
    {
        $validator = new Validator($request->all(), [
            'id' => ['required', 'exists:products,id'],
        ]);

        if ($validator->fails()) {
            app()->route->redirect('/products');
        }

        try {
            Product::destroy((int)$request->get('id'));
        } catch (Exception $e) {
            // You could log the error here
        }
        app()->route->redirect('/products');
    }
}
