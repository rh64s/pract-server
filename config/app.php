<?php
return [
    //Класс аутентификации
    'auth' => \Src\Auth\Auth::class,
    //Клас пользователя
    'identity'=>\Models\User::class,
    'routeMiddleware' => [
        'auth' => \Middlewares\AuthMiddleware::class,
    ]
];
