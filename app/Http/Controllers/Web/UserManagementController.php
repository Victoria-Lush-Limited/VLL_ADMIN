<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class UserManagementController extends Controller
{
    public function index(Request $request): View
    {
        $search = (string) $request->query('q', '');
        $query = User::query()->orderByDesc('id');
        if ((string) Auth::user()->account_type !== 'administrator') {
            $query->where('user_id', (string) Auth::user()->user_id);
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%")
                    ->orWhere('user_id', 'like', "%{$search}%");
            });
        }

        return view('users.index', ['users' => $query->paginate(30), 'search' => $search]);
    }

    public function updateStatus(Request $request, int $id): RedirectResponse
    {
        abort_unless((string) Auth::user()->account_type === 'administrator', 403, 'Forbidden.');

        $data = $request->validate([
            'status' => ['required', 'in:active,disabled,pending'],
        ]);

        $user = User::findOrFail($id);
        $user->update(['status' => $data['status']]);

        return back()->with('success', 'User status updated.');
    }
}
