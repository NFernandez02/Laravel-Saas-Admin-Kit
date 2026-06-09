<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\AuditLogResource;
use App\Models\AuditLog;

class AuditLogController extends Controller
{
    public function index(){
        $logs = AuditLog::with('user')->
        when(request('search'), function($query, $search){
            $query->whereHas('user', function ($q) use ($search){
                $q->where('name', 'like', '%'. $search. '%');
            });
        })
        ->paginate(10)
        ->withQueryString();

        return AuditLogResource::collection($logs);
    }
}
