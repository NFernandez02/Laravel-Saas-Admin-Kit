<?php

namespace App\Listeners;

use App\Events\AdminDashboardDataChanged;
use Illuminate\Support\Facades\Cache;

class ClearAdminDashboardCache
{
    public function handle(AdminDashboardDataChanged $event): void
    {
        Cache::forget('admin_dashboard_api');
        Cache::forget('admin_dashboard_web');
    }
}
