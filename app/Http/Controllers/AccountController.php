<?php

namespace App\Http\Controllers;

use App\Support\LegacyPassword;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AccountController extends Controller
{
    public function show(): View
    {
        return view('account.show', [
            'admin' => Auth::guard('admin')->user(),
        ]);
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => ['required', 'string'],
            'new_password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $admin = Auth::guard('admin')->user();

        if (! LegacyPassword::verify($request->string('current_password')->toString(), $admin->getAuthPassword())) {
            return redirect()->route('account.show')->withErrors(__('Incorrect current password.'));
        }

        $admin->password = LegacyPassword::hash($request->string('new_password')->toString());
        $admin->save();

        return redirect()->route('account.show')->with('status', __('Password changed. Use your new password next time.'));
    }
}
