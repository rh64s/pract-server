<?php

use Controllers\AuthController;
use Src\Route;

Route::add('GET', '/', [AuthController::class, 'hello']);
Route::add('GET', '/hello', [AuthController::class, 'hello']);
Route::add(['GET', 'POST'], '/login', [AuthController::class, 'login'])->middleware('csrf');
Route::add('GET', '/logout', [AuthController::class, 'logout']);

Route::add(['GET', 'POST'], '/users', [Controllers\UserController::class, 'index'])->middleware('admin-only',  'trim', 'csrf');
Route::add(['GET', 'POST'], '/users/show', [Controllers\UserController::class, 'show'])->middleware('csrf','admin-only',  'trim', 'int:id');
Route::add(['GET', 'POST'], '/users/create', [Controllers\UserController::class, 'store'])->middleware('csrf','admin-only',  'trim');
Route::add(['GET', 'POST'], '/users/delete/{user}', [Controllers\UserController::class, 'delete'])->middleware('csrf','admin-only',  'trim');
