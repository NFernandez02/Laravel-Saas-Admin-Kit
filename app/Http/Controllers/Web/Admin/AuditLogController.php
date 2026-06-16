<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Contracts\View\View;

class AuditLogController extends Controller
{
    public function index(): View
    {
        $search = request()->string('search')->value();
        $audit_logs = AuditLog::with('user')->
        when(request('search'), function ($query) use ($search) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.logs', compact('audit_logs'));
    }
}
