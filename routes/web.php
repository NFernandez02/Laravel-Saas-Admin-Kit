<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home.dashboard');
})->middleware('auth');

Route::middleware(['auth', 'role:admin'])->group(function(){
    Route::get('/admin', function(){
        return view('admin.admin');
    });
});

//Auth Controller
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'loginUser']);
Route::post('/logout', [AuthController::class, 'logout']);