<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleGuard
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $role = strtolower((string) optional($request->user())->account_type);
        if ($role === '' || ! in_array($role, $roles, true)) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden: insufficient role.',
                'data' => null,
            ], 403);
        }

        return $next($request);
    }
}
