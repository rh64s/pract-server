<?php
return [
    //Класс аутентификации
    'auth' => \Src\Auth\Auth::class,
    //Клас пользователя
    'identity'=>\Models\User::class,
    'routeMiddleware' => [
        'auth' => \Middlewares\AuthMiddleware::class,
        'authToken' => \Middlewares\TokenMiddleware::class,
        'admin-only' => \Middlewares\AdminOnlyMiddleware::class,
        'superadmin-only' => \Middlewares\SuperAdminOnlyMiddleware::class,
        'int' => \Middlewares\OnlyNumInParameter::class,
        'storekeeper-only' => \Middlewares\StorekeeperOnly::class,
    ],
    'routeAppMiddleware' => [
        'json' => \Middlewares\JSONMiddleware::class,
        'csrf' => \Middlewares\CSRFMiddleware::class,
        'specialChars' => \Middlewares\SpecialCharsMiddleware::class,
        'trim' => \Middlewares\TrimMiddleware::class,
    ],
    'validators' => \BasicValidators\getValidators(),
    'providers' => [
        'kernel' => \Providers\KernelProvider::class,
        'route' => \Providers\RouteProvider::class,
        'db' => \Providers\DBProvider::class,
        'auth' => \Providers\AuthProvider::class,
    ],
];
