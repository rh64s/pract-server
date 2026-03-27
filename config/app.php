<?php
return [
    //Класс аутентификации
    'auth' => \Src\Auth\Auth::class,
    //Клас пользователя
    'identity'=>\Models\User::class,
    'routeMiddleware' => [
        'auth' => \Middlewares\AuthMiddleware::class,
        'admin-only' => \Middlewares\AdminOnlyMiddleware::class,
        'superadmin-only' => \Middlewares\SuperAdminOnlyMiddleware::class,
        'trim' => \Middlewares\TrimMiddleware::class,
        'specialChars' => \Middlewares\SpecialCharsMiddleware::class,
        'csrf' => \Middlewares\CSRFMiddleware::class,
        'int' => \Middlewares\OnlyNumInParameter::class,
        'storekeeper-only' => \Middlewares\StorekeeperOnly::class
    ],
    'validators' => \BasicValidators\getValidators()
];
