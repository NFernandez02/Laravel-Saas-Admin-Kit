<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(){
        $audit_logs = AuditLog::with('user')->latest()->get();
        return view('admin.logs', compact('audit_logs'));
    }
}
