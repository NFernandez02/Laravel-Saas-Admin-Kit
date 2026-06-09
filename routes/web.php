<?php

use App\Http\Controllers\Web\Admin\AuditLogController;
use App\Http\Controllers\Web\Admin\DashboardController;
use App\Http\Controllers\Web\Admin\PermissionController;
use App\Http\Controllers\Web\Admin\RoleController;
use App\Http\Controllers\Web\Admin\UserController;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\DashboardController as UserDashboardController;
use App\Http\Controllers\Web\PasswordController;
use App\Http\Controllers\Web\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [UserDashboardController::class, 'index'])->middleware('auth');

Route::prefix('admin')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {

        Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');

        //AuditLog Controller
        Route::prefix('logs')->group(function () {
            Route::get('/', [AuditLogController::class, 'index'])
                ->middleware('permission:logs.view')
                ->name('admin.logs');
        });

        //User Controller
        Route::prefix('users')->group(function () {
            Route::get('/', [UserController::class, 'index'])
                ->middleware('permission:users.view')
                ->name('admin.users.index');
            Route::get('/create', [UserController::class, 'create'])
                ->middleware('permission:users.create')
                ->name('admin.users.create');
            Route::post('/', [UserController::class, 'store'])
                ->middleware('permission:users.create')
                ->name('admin.users.store');
            Route::get('/{user}/edit', [UserController::class, 'edit'])
                ->middleware('permission:users.edit')
                ->name('admin.users.edit');
            Route::put('/{user}', [UserController::class, 'update'])
                ->middleware('permission:users.edit')
                ->name('admin.users.update');
            Route::delete('/{user}', [UserController::class, 'destroy'])
                ->middleware('permission:users.delete')
                ->name('admin.users.destroy');
        });

        //Role Controller
        Route::prefix('roles')->group(function () {
            Route::get('/', [RoleController::class, 'index'])
                ->middleware('permission:roles.view')
                ->name('admin.roles.index');
            Route::get('/create', [RoleController::class, 'create'])
                ->middleware('permission:roles.create')
                ->name('admin.roles.create');
            Route::post('/', [RoleController::class, 'store'])
                ->middleware('permission:roles.create')
                ->name('admin.roles.store');
            Route::get('/{role}/edit', [RoleController::class, 'edit'])
                ->middleware('permission:roles.edit')
                ->name('admin.roles.edit');
            Route::put('/{role}', [RoleController::class, 'update'])
                ->middleware('permission:roles.edit')
                ->name('admin.roles.update');
            Route::delete('/{role}', [RoleController::class, 'destroy'])
                ->middleware('permission:roles.delete')
                ->name('admin.roles.destroy');
        });

        //Permission Controller
        Route::prefix('permissions')->group(function (){
            Route::get('/', [PermissionController::class, 'index'])
            ->middleware('permission:permissions.view')
            ->name('admin.permissions.index');
            Route::get('/create', [PermissionController::class, 'create'])
            ->middleware('permission:permissions.create')
            ->name('admin.permissions.create');
            Route::get('/{permission}/edit', [PermissionController::class, 'edit'])
            ->middleware('permission:permissions.edit')
            ->name('admin.permissions.edit');
            Route::post('/', [PermissionController::class, 'store'])
            ->middleware('permission:permissions.create')
            ->name('admin.permissions.store');
            Route::put('/{permission}', [PermissionController::class, 'update'])
            ->middleware('permission:permissions.edit')
            ->name('admin.permissions.update');
            Route::delete('/{permission}', [PermissionController::class, 'destroy'])
            ->middleware('permission:permissions.delete')
            ->name('admin.permissions.destroy');
        });
    });
    
Route::middleware('auth')->group(function(){
    //ProfileController
    Route::get('/profile', [ProfileController::class, 'index'])
    ->name('users.profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])
    ->name('users.profile.update');

    //PasswordController
    Route::put('/password', [PasswordController::class, 'update'])
    ->name('users.password.update');
});


//Auth Controller
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'loginUser']);
Route::post('/logout', [AuthController::class, 'logout']);
