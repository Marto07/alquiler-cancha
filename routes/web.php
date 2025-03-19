<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/personas', [PersonaController::class, 'index']);

Route::get('/login', function () {
    return view('auth/login');
});

Route::post('/login', [AuthController::class, 'login']);
