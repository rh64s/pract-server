<?php

use Src\Route;

Route::add('GET', '/', [Controllers\Site::class, 'index'])->middleware('auth');
Route::add('GET', '/goto', [Controllers\Site::class, 'show']);
Route::add('GET', '/hello', [Controllers\Site::class, 'hello'])->middleware('auth');
Route::add(['GET', 'POST'], '/signup', [Controllers\Site::class, 'signup'])->middleware('admin-only');
Route::add(['GET', 'POST'], '/login', [Controllers\Site::class, 'login']);
Route::add('GET', '/logout', [Controllers\Site::class, 'logout']);