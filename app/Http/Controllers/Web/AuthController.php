<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use RuntimeException;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $user = User::query()
            ->where('email', $data['login'])
            ->orWhere('username', $data['login'])
            ->orWhere('user_id', $data['login'])
            ->first();

        if (! $user || ! $this->validateAndUpgradePassword($user, $data['password'])) {
            return back()->withErrors(['login' => 'Invalid credentials'])->withInput();
        }

        if (($user->status ?? 'pending') !== 'active') {
            return back()->withErrors(['login' => 'Account is not active'])->withInput();
        }

        if (! in_array($user->account_type, ['administrator', 'reseller', 'agent'], true)) {
            return back()->withErrors(['login' => 'This account cannot access vll-admin'])->withInput();
        }

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('web.dashboard');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    private function validateAndUpgradePassword(User $user, string $plainPassword): bool
    {
        $storedPassword = (string) $user->password;

        try {
            return Hash::check($plainPassword, $storedPassword);
        } catch (RuntimeException) {
            $legacyMatch = false;

            if (strlen($storedPassword) === 32 && ctype_xdigit($storedPassword)) {
                $legacyMatch = hash_equals(strtolower($storedPassword), md5($plainPassword));
            } else {
                $legacyMatch = hash_equals($storedPassword, $plainPassword);
            }

            if (! $legacyMatch) {
                return false;
            }

            $user->password = Hash::make($plainPassword);
            $user->save();

            return true;
        }
    }
}
