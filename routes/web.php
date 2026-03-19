<?php

use Src\Route;

Route::add('go', [Controllers\Site::class, 'index']);
Route::add('goto', [Controllers\Site::class, 'show']);
Route::add('hello', [Controllers\Site::class, 'hello']);
Route::add('signup', [Controller\Site::class, 'signup']);