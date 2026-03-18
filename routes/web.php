<?php

use Src\Route;

Route::add('go', [Controllers\Site::class, 'index']);
Route::add('hello', [Controllers\Site::class, 'hello']);