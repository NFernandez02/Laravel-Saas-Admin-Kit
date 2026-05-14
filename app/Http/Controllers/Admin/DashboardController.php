<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(){
        $totalUsers = User::count();
        $totalRoles = Role::count();
        $totalAdmins = User::whereHas('role', function($query){
            $query->where('name', 'admin');
        })->count();

        $latestLogs = AuditLog::with('user')
        ->latest()
        ->take(5)
        ->get();


        return view('admin.dashboard', compact('totalUsers', 'totalRoles', 'totalAdmins', 'latestLogs'));
    }
}
