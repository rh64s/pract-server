<?php

use Src\Route;

Route::add('go', [Controllers\Site::class, 'index']);
Route::add('goto', [Controllers\Site::class, 'show']);
Route::add('hello', [Controllers\Site::class, 'hello']);
Route::add('signup', [Controllers\Site::class, 'signup']);
Route::add('login', [Controllers\Site::class, 'login']);
Route::add('logout', [Controllers\Site::class, 'logout']);