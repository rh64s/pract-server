<?php
return [
    //Класс аутентификации
    'auth' => \Src\Auth\Auth::class,
    //Клас пользователя
    'identity'=>\Models\User::class,
    'routeMiddleware' => [
        'auth' => \Middlewares\AuthMiddleware::class,
//        'access:admin-only' => \Middlewares\AdminOnlyMiddleware::class,
//        'access:superadmin-only' => \Middlewares\SuperAdminOnlyMiddleware::class,
        'admin-only' => \Middlewares\AdminOnlyMiddleware::class,
        'superadmin-only' => \Middlewares\SuperAdminOnlyMiddleware::class,
        'trim' => \Middlewares\TrimMiddleware::class,
    ],
    'validators' => [
        'required' => \Validators\RequireValidator::class,
        'unique' => \Validators\UniqueValidator::class
    ]
];
