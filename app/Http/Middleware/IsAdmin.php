<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth('api')->user();
        // if($user && ($user->role === 'admin' || $user->role === 'supper_admin')){
        // Uniquement les admins et admin_master (pas les users)
        if ($user && in_array($user->role_id, [2, 3])) {
            return $next($request);
        }

        return response()->json([
            'message' => 'Access denied: ADMIN role required.',
        ], 403);
    }
}
