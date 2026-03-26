<?php

use Controllers\AuthController;
use Src\Route;

Route::add('GET', '/', [AuthController::class, 'hello']);
Route::add('GET', '/hello', [AuthController::class, 'hello']);
Route::add(['GET', 'POST'], '/login', [AuthController::class, 'login'])->middleware('csrf');
Route::add('GET', '/logout', [AuthController::class, 'logout'])->middleware('auth');

Route::add(['GET', 'POST'], '/users', [Controllers\UserController::class, 'index'])->middleware('auth', 'admin-only',  'trim', 'csrf');
Route::add(['GET', 'POST'], '/users/show', [Controllers\UserController::class, 'show'])->middleware('auth', 'csrf','admin-only',  'trim', 'int:id');
Route::add(['GET', 'POST'], '/users/create', [Controllers\UserController::class, 'store'])->middleware('auth', 'csrf','admin-only',  'trim');
Route::add('POST', '/users/delete', [Controllers\UserController::class, 'delete'])->middleware('auth', 'csrf','admin-only', 'trim', 'int:id');

Route::add(['GET', 'POST'], '/divisions', [Controllers\DivisionController::class, 'index'])->middleware('auth', 'admin-only', 'trim', 'csrf');
Route::add(['GET', 'POST'], '/divisions/show', [Controllers\DivisionController::class, 'show'])->middleware('auth', 'csrf', 'admin-only', 'trim', 'int:id');
Route::add(['GET', 'POST'], '/divisions/create', [Controllers\DivisionController::class, 'store'])->middleware('auth', 'csrf', 'admin-only', 'trim');
Route::add('POST', '/divisions/delete', [Controllers\DivisionController::class, 'delete'])->middleware('auth', 'csrf', 'admin-only', 'trim', 'int:id');

Route::add(['GET', 'POST'], '/unit-types', [Controllers\UnitTypeController::class, 'index'])->middleware('auth', 'admin-only', 'trim', 'csrf');
Route::add(['GET', 'POST'], '/unit-types/show', [Controllers\UnitTypeController::class, 'show'])->middleware('auth', 'csrf', 'admin-only', 'trim', 'int:id');
Route::add(['GET', 'POST'], '/unit-types/create', [Controllers\UnitTypeController::class, 'store'])->middleware('auth', 'csrf', 'admin-only', 'trim');
Route::add('POST', '/unit-types/delete', [Controllers\UnitTypeController::class, 'delete'])->middleware('auth', 'csrf', 'admin-only', 'trim', 'int:id');

Route::add(['GET', 'POST'], '/products', [Controllers\ProductController::class, 'index'])->middleware('auth', 'admin-only', 'trim', 'csrf');
Route::add(['GET', 'POST'], '/products/show', [Controllers\ProductController::class, 'show'])->middleware('auth', 'csrf', 'admin-only', 'trim', 'int:id');
Route::add(['GET', 'POST'], '/products/create', [Controllers\ProductController::class, 'store'])->middleware('auth', 'csrf', 'admin-only', 'trim');
Route::add('POST', '/products/delete', [Controllers\ProductController::class, 'delete'])->middleware('auth', 'csrf', 'admin-only', 'trim', 'int:id');

Route::add('GET', '/orders', [Controllers\OrderController::class, 'index'])->middleware('auth');
Route::add(['GET', 'POST'], '/orders/create', [Controllers\OrderController::class, 'store'])->middleware('auth','csrf', 'storekeeper-only', 'trim');
Route::add('POST', '/orders/delete', [Controllers\OrderController::class, 'delete'])->middleware('csrf', 'auth', 'trim', 'int:id');

Route::add('GET', '/division-products', [Controllers\DivisionProductController::class, 'index'])->middleware('auth','csrf', 'storekeeper-only');
Route::add('POST', '/division-products/add', [Controllers\DivisionProductController::class, 'add'])->middleware('auth','csrf', 'storekeeper-only', 'trim');
Route::add('POST', '/division-products/remove', [Controllers\DivisionProductController::class, 'remove'])->middleware('auth','csrf', 'storekeeper-only', 'trim');
Route::add('POST', '/division-products/update-count', [Controllers\DivisionProductController::class, 'updateCount'])->middleware('auth','csrf', 'storekeeper-only', 'trim');
