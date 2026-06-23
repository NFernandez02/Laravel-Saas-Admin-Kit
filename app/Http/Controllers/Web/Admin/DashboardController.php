<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Role;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index(): View
    {
        $data = Cache::remember('admin_dashboard_web_v2', now()->addMinutes(5), function () {
            return [
                'totalUsers' => User::count(),
                'totalRoles' => Role::count(),
                'totalAdmins' => User::whereHas('role', function ($query) {
                    $query->where('name', 'admin');
                })->count(),

            ];
        });
        $latestLogs = AuditLog::with('user')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', $data, compact('latestLogs'));
    }
}
