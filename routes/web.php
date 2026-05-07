<?php

use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\UserController;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home.dashboard');
})->middleware('auth');

Route::middleware(['auth', 'role:admin'])->group(function(){
    Route::get('/admin', function(){
        return view('admin.dashboard');
    });
    
    //AuditLog Controller
    Route::get('/admin/logs', [AuditLogController::class, 'index'])->name('admin.logs');
    
    //User Controller
    Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('/admin/users/create', [UserController::class, 'create'])->name('admin.users.create');
    Route::post('/admin/users', [UserController::class, 'store'])->name('admin.users.store');
    Route::get('/admin/users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/admin/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/admin/users/{user}', [UserController::class,'destroy'])->name('admin.users.destroy');

    //Role Controller
    Route::get('/admin/roles', [RoleController::class, 'index'])->name('admin.roles.index');
    Route::get('/admin/roles/create', [RoleController::class, 'create'])->name('admin.roles.create');
    Route::post('/admin/roles', [RoleController::class, 'store'])->name('admin.roles.store');
    Route::get('/admin/roles/{role}/edit', [RoleController::class, 'edit'])->name('admin.roles.edit');
    Route::put('/admin/roles/{role}', [RoleController::class, 'update'])->name('admin.roles.update');
    Route::delete('/admin/roles/{role}', [RoleController::class,'destroy'])->name('admin.roles.destroy');

});

//Auth Controller
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'loginUser']);
Route::post('/logout', [AuthController::class, 'logout']);