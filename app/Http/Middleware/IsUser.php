<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth('api')->user();

        // Tous les rôles ont accès aux routes de type "user"
        if ($user && in_array($user->role_id, [1, 2, 3])) {
            return $next($request);
        }

        return response()->json([
            'message' => 'Access denied: USER role required.',
        ], 403);
    }
}
