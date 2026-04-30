<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home.dashboard');
})->middleware('auth');

Route::middleware(['auth', 'role:admin'])->group(function(){
    Route::get('/admin', function(){
        return view('admin.dashboard');
    });

    //User Controller
    Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('/admin/users/create', [UserController::class, 'create']);
    Route::post('/admin/users', [UserController::class, 'store']);
    Route::get('/admin/users/{user}/edit', [UserController::class, 'edit']);
    Route::put('/admin/users/{user}', [UserController::class, 'update']);
    Route::delete('/admin/users/{user}', [UserController::class,'destroy']);
});

//Auth Controller
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'loginUser']);
Route::post('/logout', [AuthController::class, 'logout']);