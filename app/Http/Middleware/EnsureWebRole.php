<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureWebRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = Auth::user();
        if (! $user) {
            return redirect()->route('login');
        }

        $role = strtolower((string) $user->account_type);
        if (! in_array($role, $roles, true)) {
            abort(403, 'Forbidden');
        }

        return $next($request);
    }
}
