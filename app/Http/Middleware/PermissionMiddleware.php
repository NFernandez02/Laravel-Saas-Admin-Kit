<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$permissions): Response
    {
        $user = $request->user();

        if(!$user || !$user->role){
            abort(403);
        }

        $hasPermission = $user->role
        ->permissions
        ->pluck('name')
        ->intersect($permissions)
        ->isNotEmpty();

        if(!$hasPermission){
            abort(403);
        }
        return $next($request);
    }
}
