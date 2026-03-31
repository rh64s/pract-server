<?php

namespace Controllers\Api;

use Models\Product;
use Src\Request;
use BasicValidators\Validator\Validator;
use Src\View;

class ProductController
{
    public function index(Request $request): void
    {
        $query = Product::with('unitType');

        if (isset($request->all()['search'])) {
            $search = $request->all()['search'];
            $query->where('name', 'LIKE', "%{$search}%")
                ->orWhere('articul', 'LIKE', "%{$search}%");
        }

        $products = $query->orderBy('name')->get();

        (new View())->toJSON($products->toArray());
    }

    public function store(Request $request): void
    {
        $validator = new Validator($request->all(), [
            'name' => ['required', 'max:255', 'min:3'],
            'articul' => ['required', 'max:255', 'unique:products,articul'],
            'unit_type_id' => ['required', 'exists:unit_types,id'],
        ]);

        if ($validator->fails()) {
            (new View())->toJSON(['errors' => $validator->errors()], 422);
            return;
        }

        $product = Product::create($request->all());

        if ($product) {
            (new View())->toJSON($product->toArray(), 201);
        } else {
            (new View())->toJSON(['error' => 'Failed to create product'], 500);
        }
    }
}
