<?php

namespace Controllers;

use Exception;
use Models\Order;
use Models\Product;
use Monolog\Level;
use Monolog\Logger;
use Src\Auth\Auth;
use Src\Request;
use BasicValidators\Validator\Validator;
use Src\View;

class OrderController
{
    public function index(): string
    {
        $user = Auth::user();
        $orders = [];

        // Admins see all orders, storekeepers see orders for their division
        if ($user->isAdmin()) {
            $orders = Order::with(['product', 'division'])->orderBy('created_at', 'desc')->get();
        } elseif ($user->isStorekeeper() && $user->division) {
            $orders = Order::where('division_id', $user->division->id)->with(['product', 'division'])->orderBy('created_at', 'desc')->get();
        }

        return new View('site.orders.index', ['orders' => $orders]);
    }


    public function store(Request $request): string
    {
        $log = new Logger('name');
        $log->pushHandler(new \Monolog\Handler\StreamHandler('/opt/lampp/htdocs/pop-it-mvc/logs.log', Level::Info));
        if (!Auth::check()) {
            app()->route->redirect('/login');
            return (new View)->render('site.login');
        }
        $user = Auth::user();
        $log->info('authenticated');
        if (!$user->isStorekeeper() || !$user->division) {
            $log->info('wrong!');
            app()->route->redirect('/');
            return (new View)->render('site.hello', ['message' => 'У вас нет отдела']);

        }

        if ($request->method === 'POST') {
            $log->info('its post');
            $log->info(print_r($request->all(), 1));

            $validator = new Validator($request->all(), [
                'product_id' => ['required', 'exists:products,id'],
                'count' => ['required', 'integer', 'min:1'],
            ], [
                'required' => 'Поле :field пусто',
                'exists' => 'Выбранный :field не существует',
                'integer' => 'Поле :field должно быть целым числом',
                'min' => 'Количество должно быть не меньше 1',
            ]);

            if ($validator->fails()) {
                $log->info('fail');
                return new View('site.orders.create', [
                    'products' => Product::orderBy('name')->get(),
                    'message' => json_encode($validator->errors(), JSON_UNESCAPED_UNICODE)
                ]);
            }
            $log->info('ok!');

            $data = $request->all();
            $data['division_id'] = $user->division->id;

            if (Order::create($data)) {
                $log->info('success!');

                app()->route->redirect('/orders');
                return $this->index();
            }
        }

        return new View('site.orders.create', [
            'products' => Product::orderBy('name')->get()
        ]);
    }

    public function delete(Request $request): void
    {
        $user = Auth::user();
        $validator = new Validator($request->all(), [
            'id' => ['required', 'exists:orders,id'],
        ]);

        if ($validator->fails()) {
            app()->route->redirect('/orders');
        }

        try {
            $order = Order::findOrFail((int)$request->get('id'));

            $canDelete = $user->isAdmin() ||
                ($user->isStorekeeper() && $user->division && $user->division->id === $order->division_id);

            if ($canDelete) {
                $order->delete();
            }
        } catch (Exception $e) {
            throw new \Error($e);
        }

        app()->route->redirect('/orders');
    }

    public function complete(Request $request): void
    {
        $user = Auth::user();
        if (!$user->isStorekeeper()) {
            app()->route->redirect('/orders');
        }

        $validator = new Validator($request->all(), [
            'id' => ['required', 'exists:orders,id'],
        ]);

        if ($validator->fails()) {
            app()->route->redirect('/orders');
        }

        try {
            $order = Order::findOrFail((int)$request->get('id'));

            if ($user->division->id === $order->division_id) {
                $order->markAsCompleted();
            }
        } catch (Exception $e) {
            throw new \Error($e);
        }

        app()->route->redirect('/orders');
    }
}