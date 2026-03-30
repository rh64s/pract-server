<?php

namespace Controllers;

use Error;
use Exception;
use Models\Product;
use Models\ProductInDivision;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Src\Auth\Auth;
use Src\Request;
use BasicValidators\Validator\Validator;
use Src\View;

class DivisionProductController
{
    public function index(Request $request): string
    {
        $log = new Logger('DivisionProductController'); $log->pushHandler(new StreamHandler('/opt/lampp/htdocs/pop-it-mvc/logss.log', Logger::INFO));
        $user = Auth::user();
        $division = $user->division;
        $message = '';
        if (!$division) {
            return new View('site.division-products.index', ['division' => null, 'products' => [], 'available_products' => []]);
        }

        if ($request->method === 'POST') {
            $log->info(print_r($request->all(),1 ));

            $validator = new Validator($request->all(), [
                'product_id' => ['required', 'exists:products,id'],
                'min_value' => ['required', 'regex:/^[0-9]*$/', 'min:1'],
                'count' => ['required', 'regex:/^[0-9]*$/', 'min:0'],
            ]);

            if (!$validator->fails()) {
                try {
                    ProductInDivision::where('division_id', $division->id)
                        ->where('product_id', $request->get('product_id'))
                        ->update(['count' => $request->get('count'), 'min_value' => $request->get('min_value')]);
                } catch (Exception $e) {
                    throw new Error($e);
                }
            } else {
                $message = json_encode($validator->errors(), JSON_UNESCAPED_UNICODE);
            }
        }

        $productsInDivision = ProductInDivision::where('division_id', $division->id)
            ->with('product.unitType')
            ->get();
        $divisionProductIds = $productsInDivision->pluck('product_id')->all();
        $available_products = Product::whereNotIn('id', $divisionProductIds)->orderBy('name')->get();

        return new View('site.division-products.index', [
            'division' => $division,
            'products' => $productsInDivision,
            'available_products' => $available_products,
            'message' => $message
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
                ProductInDivision::where('division_id', $division->id)
                    ->where('product_id', $request->get('product_id'))
                    ->delete();
            } catch (Exception $e) {
                throw new Error($e);
            }
        }
        app()->route->redirect('/division-products');
    }
}