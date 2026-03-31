<?php
use Src\Route;
Route::add('GET', '/api/', [Controllers\Api::class, 'index'])->middleware('token');
Route::add('POST', '/api/echo', [Controllers\Api::class, 'echo'])->middleware('token');
Route::add('POST', '/api/login', [Controllers\Api::class, 'login']);
Route::add('GET', '/api/me', [Controllers\Api::class, 'profile'])->middleware('token');

Route::add('GET', '/api/users', [Controllers\Api\UserController::class, 'index'])->middleware('token');
Route::add('POST', '/api/users', [Controllers\Api\UserController::class, 'store'])->middleware('token');

Route::add('GET', '/api/products', [Controllers\Api\ProductController::class, 'index'])->middleware('token');
Route::add('POST', '/api/products', [Controllers\Api\ProductController::class, 'store'])->middleware('token');

Route::add('GET', '/api/unit-types', [Controllers\Api\UnitTypeController::class, 'index'])->middleware('token');
Route::add('POST', '/api/unit-types', [Controllers\Api\UnitTypeController::class, 'store'])->middleware('token');

Route::add('GET', '/api/orders', [Controllers\Api\OrderController::class, 'index'])->middleware('token');
Route::add('POST', '/api/orders', [Controllers\Api\OrderController::class, 'store'])->middleware('token');
Route::add('DELETE', '/api/orders', [Controllers\Api\OrderController::class, 'delete'])->middleware('token');
Route::add('PATCH', '/api/orders/complete', [Controllers\Api\OrderController::class, 'complete'])->middleware('token');

Route::add('GET', '/api/division-products', [Controllers\Api\DivisionProductController::class, 'index'])->middleware('token');
Route::add('POST', '/api/division-products', [Controllers\Api\DivisionProductController::class, 'add'])->middleware('token');
Route::add('DELETE', '/api/division-products', [Controllers\Api\DivisionProductController::class, 'remove'])->middleware('token');

Route::add('GET', '/api/reports', [Controllers\Api\DivisionController::class, 'reports'])->middleware('token');
Route::add('GET', '/api/reports/show', [Controllers\Api\DivisionController::class, 'showReport'])->middleware('token');
