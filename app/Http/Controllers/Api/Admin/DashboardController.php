<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\AdminDashboardResource;
use App\Models\AuditLog;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(){
        return new AdminDashboardResource([
            'users_count' => User::count(),
            'roles_count' => Role::count(),
            'admins_count' => User::whereHas('role', function($query){
                $query->where('name', 'admin');
            })->count(),
            'latest_logs' => AuditLog::with('user')
            ->latest()
            ->take(5)
            ->get()
        ]);
    }
}
