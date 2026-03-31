<?php

namespace Controllers\Api;

use Models\Product;
use Models\ProductInDivision;
use Src\Auth\Auth;
use Src\Request;
use BasicValidators\Validator\Validator;
use Src\View;

class DivisionProductController
{
    public function index(Request $request): void
    {
        $user = Auth::user();
        $division = $user->division;

        if (!$division) {
            (new View())->toJSON(['error' => 'Unauthorized'], 403);
            return;
        }

        $productsInDivision = ProductInDivision::where('division_id', $division->id)
            ->with('product.unitType')
            ->get();

        (new View())->toJSON($productsInDivision->toArray());
    }

    public function add(Request $request): void
    {
        $user = Auth::user();
        $division = $user->division;

        if (!$division) {
            (new View())->toJSON(['error' => 'Unauthorized'], 403);
            return;
        }

        $validator = new Validator($request->all(), [
            'product_id' => ['required', 'exists:products,id'],
            'count' => ['regex:/^[0-9]*$/'],
        ]);

        if ($validator->fails()) {
            (new View())->toJSON(['errors' => $validator->errors()], 422);
            return;
        }

        $productInDivision = ProductInDivision::create([
            'product_id' => $request->get('product_id'),
            'division_id' => $division->id,
            'count' => (int) $request->get('count') ?? 0,
            'min_value' => 1
        ]);

        if ($productInDivision) {
            (new View())->toJSON($productInDivision->toArray(), 201);
        } else {
            (new View())->toJSON(['error' => 'Failed to add product to division'], 500);
        }
    }

    public function remove(Request $request): void
    {
        $user = Auth::user();
        $division = $user->division;

        if (!$division) {
            (new View())->toJSON(['error' => 'Unauthorized'], 403);
            return;
        }

        $validator = new Validator($request->all(), [
            'product_id' => ['required', 'exists:products,id'],
            'division_id' => ['required', 'exists:divisions,id']
        ]);

        if ($validator->fails()) {
            (new View())->toJSON(['errors' => $validator->errors()], 422);
            return;
        }

        $deleted = ProductInDivision::where('division_id', $division->id)
            ->where('product_id', $request->get('product_id'))
            ->delete();

        if ($deleted) {
            (new View())->toJSON(['message' => 'Product removed from division successfully.'], 200);
        } else {
            (new View())->toJSON(['error' => 'Failed to remove product from division'], 500);
        }
    }
}
