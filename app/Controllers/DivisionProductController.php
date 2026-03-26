<?php

namespace Controllers;

use Exception;
use Models\Product;
use Models\ProductInDivision;
use Src\Auth\Auth;
use Src\Request;
use Src\Validator\Validator;
use Src\View;

class DivisionProductController
{
    public function index(): string
    {
        $user = Auth::user();
        $division = $user->division()->with('products.unitType')->first();

        if (!$division) {
            // This storekeeper has no division, so there's nothing to show.
            return new View('site.division-products.index', ['division' => null, 'products' => [], 'available_products' => []]);
        }

        $divisionProductIds = $division->products->pluck('id')->all();
        $available_products = Product::whereNotIn('id', $divisionProductIds)->orderBy('name')->get();

        return new View('site.division-products.index', [
            'division' => $division,
            'products' => $division->products,
            'available_products' => $available_products
        ]);
    }

    public function add(Request $request): void
    {
        $user = Auth::user();
        $division = $user->division;

        $validator = new Validator($request->all(), [
            'product_id' => ['required', 'exists:products,id'],
        ]);

        if (!$validator->fails() && $division) {
            try {
//                $division->products()->attach($request->get('product_id'));
                ProductInDivision::create([
                    'product_id' => $request->get('product_id'),
                    'division_id' => $division->id,
                    'count' => 0
                ]);
            } catch (Exception $e) {
                throw new \Error($e);
            }
        }
        app()->route->redirect('/division-products');
    }

    public function remove(Request $request): void
    {
        $user = Auth::user();
        $division = $user->division;

        $validator = new Validator($request->all(), [
            'product_id' => ['required', 'exists:products,id'],
        ]);

        if (!$validator->fails() && $division) {
            try {
                $division->products()->detach($request->get('product_id'));
            } catch (Exception $e) {
                throw new
            }
        }
        app()->route->redirect('/division-products');
    }

    public function updateCount(Request $request): void
    {
        $user = Auth::user();
        $division = $user->division;

        $validator = new Validator($request->all(), [
            'product_id' => ['required', 'exists:products,id'],
            'count' => ['required', 'integer', 'min:0'],
        ]);

        if (!$validator->fails() && $division) {
            try {
                ProductInDivision::where('division_id', $division->id)
                    ->where('product_id', $request->get('product_id'))
                    ->update(['count' => $request->get('count')]);
            } catch (Exception $e) {
                // You could log the error here
            }
        }
        app()->route->redirect('/division-products');
    }
}