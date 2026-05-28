<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        /*
        |--------------------------------------------------------------------------
        | CHECK LOGIN
        |--------------------------------------------------------------------------
        */

        if (!Auth::check()) {
            abort(401);
        }

        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | CHECK USER ROLE
        |--------------------------------------------------------------------------
        */

        if (
            !$user->role ||
            !in_array(
                strtolower($user->role->name),
                array_map('strtolower', $roles)
            )
        ) {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}
