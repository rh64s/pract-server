<?php

namespace Controllers\Api;

use Models\Order;
use Models\Product;
use Src\Auth\Auth;
use Src\Request;
use BasicValidators\Validator\Validator;
use Src\View;

class OrderController
{
    public function index(): void
    {
        $user = Auth::user();
        $orders = [];

        if ($user->isAdmin()) {
            $orders = Order::with(['product', 'division'])->orderBy('created_at', 'desc')->get();
        } elseif ($user->isStorekeeper() && $user->division) {
            $orders = Order::where('division_id', $user->division->id)->with(['product', 'division'])->orderBy('created_at', 'desc')->get();
        }

        (new View())->toJSON($orders->toArray());
    }

    public function store(Request $request): void
    {
        $user = Auth::user();
        if (!$user->isStorekeeper() || !$user->division) {
            (new View())->toJSON(['error' => 'Unauthorized'], 403);
            return;
        }

        $validator = new Validator($request->all(), [
            'product_id' => ['required', 'exists:products,id'],
            'count' => ['required', 'integer', 'min:1'],
        ]);

        if ($validator->fails()) {
            (new View())->toJSON(['errors' => $validator->errors()], 422);
            return;
        }

        $data = $request->all();
        $data['division_id'] = $user->division->id;

        $order = Order::create($data);

        if ($order) {
            (new View())->toJSON($order->toArray(), 201);
        } else {
            (new View())->toJSON(['error' => 'Failed to create order'], 500);
        }
    }

    public function delete(Request $request): void
    {
        $user = Auth::user();
        $validator = new Validator($request->all(), [
            'id' => ['required', 'exists:orders,id'],
        ]);

        if ($validator->fails()) {
            (new View())->toJSON(['errors' => $validator->errors()], 422);
            return;
        }

        $order = Order::findOrFail((int)$request->get('id'));

        $canDelete = $user->isAdmin() ||
            ($user->isStorekeeper() && $user->division && $user->division->id === $order->division_id);

        if ($canDelete) {
            $order->delete();
            (new View())->toJSON(['message' => 'Order deleted successfully.'], 200);
        } else {
            (new View())->toJSON(['error' => 'Unauthorized'], 403);
        }
    }

    public function complete(Request $request): void
    {
        $user = Auth::user();
        if ($user->isStorekeeper()) {
            (new View())->toJSON(['error' => 'Unauthorized'], 403);
            return;
        }

        $validator = new Validator($request->all(), [
            'id' => ['required', 'exists:orders,id'],
        ]);

        if ($validator->fails()) {
            (new View())->toJSON(['errors' => $validator->errors()], 422);
            return;
        }

        $order = Order::find((int)$request->get('id'));

        if (!$order->exists()) {
            (new View())->toJSON(['error' => 'Order is not found',
                'your request' => $request], 404);
            return;
        }

        if ($order->is_completed) {
            (new View())->toJSON(['error' => 'Order is already completed'], 400);
            return;
        }

        $order->markAsCompleted();
        (new View())->toJSON($order->toArray(), 200);
    }
}
