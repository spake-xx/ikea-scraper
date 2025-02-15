<?php

use App\Http\Controllers\HookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/hook', [HookController::class, 'hook']);