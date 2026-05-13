<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(){
        $audit_logs = AuditLog::with('user')->
        when(request('search'), function ($query, $search) {
            $query->whereHas('user', function ($q) use ($search){
                $q->where('name', 'like', '%'. $search . '%');
            });
        })
        ->latest()
        ->paginate(10)
        ->withQueryString();
        return view('admin.logs', compact('audit_logs'));
    }
}
