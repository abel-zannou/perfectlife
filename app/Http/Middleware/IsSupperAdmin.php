<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsSupperAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth('api')->user();
        // if( $user && $user->role === 'supper_admin'){
        // Seul le role admin_master (3) est autorisé ici
        if ($user && $user->role_id === 3) {
            return $next($request);
        }

        return response()->json([
            'message' => 'Access denied: ADMIN MASTER role required.',
        ], 403);
    }
}
