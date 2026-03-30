<?php
use Src\Route;
Route::add('GET', '/api/', [Controllers\Api::class, 'index'])->middleware('token');
Route::add('POST', '/api/echo', [Controllers\Api::class, 'echo'])->middleware('token');
Route::add('POST', '/api/login', [Controllers\Api::class, 'login']);