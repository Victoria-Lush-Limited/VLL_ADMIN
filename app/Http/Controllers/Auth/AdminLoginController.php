<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Administrator;
use App\Support\LegacyPassword;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AdminLoginController extends Controller
{
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'user_id' => ['required', 'string', 'max:191'],
            'password' => ['required', 'string'],
        ]);

        $admin = Administrator::query()->where('user_id', $credentials['user_id'])->first();

        if (! $admin || ! LegacyPassword::verify($credentials['password'], $admin->getAuthPassword())) {
            throw ValidationException::withMessages([
                'user_id' => __('Invalid username or password.'),
            ]);
        }

        Auth::guard('admin')->login($admin);
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
