<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        /** @var User $user */
        $user = $request->user();

        // If user's doesn't have a role or their role is not included on the roles passed
        if (! $user->role || ! in_array($user->role->name, $roles, true)) {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}
