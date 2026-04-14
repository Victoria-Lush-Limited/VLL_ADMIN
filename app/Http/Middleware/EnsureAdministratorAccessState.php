<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdministratorAccessState
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::guard('admin')->user();
        if (! $user) {
            return $next($request);
        }

        $onVerification = $request->routeIs('admin.verification.*');

        if (($user->status ?? '') === 'Pending' && ! $onVerification) {
            return redirect()->route('admin.verification.show');
        }

        if (($user->status ?? '') === 'Active' && $onVerification) {
            return redirect()->route('dashboard');
        }

        return $next($request);
    }
}
