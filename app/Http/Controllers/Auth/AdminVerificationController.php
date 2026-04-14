<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class AdminVerificationController extends Controller
{
    public function show(): View
    {
        return view('auth.verification');
    }

    public function verify(Request $request): RedirectResponse
    {
        $request->validate([
            'vcode' => ['required', 'string', 'max:32'],
        ]);

        $admin = Auth::guard('admin')->user();

        if (! Schema::hasColumn('administrators', 'vcode')) {
            $admin->status = 'Active';
            $admin->save();

            return redirect()->route('dashboard')->with('status', __('Account activated.'));
        }

        if ((string) ($admin->vcode ?? '') !== $request->string('vcode')->toString()) {
            return redirect()->back()->withErrors(['vcode' => __('Invalid verification code.')]);
        }

        $admin->status = 'Active';
        if (Schema::hasColumn('administrators', 'vcode')) {
            $admin->vcode = '';
        }
        $admin->save();

        return redirect()->route('dashboard')->with('status', __('Account verified.'));
    }
}
