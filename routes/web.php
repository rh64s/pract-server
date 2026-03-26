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
Route::add('POST', '/users/delete', [Controllers\UserController::class, 'delete'])->middleware('csrf','admin-only', 'trim', 'int:id');

Route::add(['GET', 'POST'], '/divisions', [Controllers\DivisionController::class, 'index'])->middleware('admin-only', 'trim', 'csrf');
Route::add(['GET', 'POST'], '/divisions/show', [Controllers\DivisionController::class, 'show'])->middleware('csrf', 'admin-only', 'trim', 'int:id');
Route::add(['GET', 'POST'], '/divisions/create', [Controllers\DivisionController::class, 'store'])->middleware('csrf', 'admin-only', 'trim');
Route::add('POST', '/divisions/delete', [Controllers\DivisionController::class, 'delete'])->middleware('csrf', 'admin-only', 'trim', 'int:id');

Route::add(['GET', 'POST'], '/unit-types', [Controllers\UnitTypeController::class, 'index'])->middleware('admin-only', 'trim', 'csrf');
Route::add(['GET', 'POST'], '/unit-types/show', [Controllers\UnitTypeController::class, 'show'])->middleware('csrf', 'admin-only', 'trim', 'int:id');
Route::add(['GET', 'POST'], '/unit-types/create', [Controllers\UnitTypeController::class, 'store'])->middleware('csrf', 'admin-only', 'trim');
Route::add('POST', '/unit-types/delete', [Controllers\UnitTypeController::class, 'delete'])->middleware('csrf', 'admin-only', 'trim', 'int:id');

Route::add(['GET', 'POST'], '/products', [Controllers\ProductController::class, 'index'])->middleware('admin-only', 'trim', 'csrf');
Route::add(['GET', 'POST'], '/products/show', [Controllers\ProductController::class, 'show'])->middleware('csrf', 'admin-only', 'trim', 'int:id');
Route::add(['GET', 'POST'], '/products/create', [Controllers\ProductController::class, 'store'])->middleware('csrf', 'admin-only', 'trim');
Route::add('POST', '/products/delete', [Controllers\ProductController::class, 'delete'])->middleware('csrf', 'admin-only', 'trim', 'int:id');

Route::add('GET', '/orders', [Controllers\OrderController::class, 'index'])->middleware('auth');
Route::add(['GET', 'POST'], '/orders/create', [Controllers\OrderController::class, 'store'])->middleware('csrf', 'storekeeper-only', 'trim');
Route::add('POST', '/orders/delete', [Controllers\OrderController::class, 'delete'])->middleware('csrf', 'auth', 'trim', 'int:id');
