<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Sender;
use App\Models\SmsOrder;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class LegacyModuleController extends Controller
{
    public function sales(): View
    {
        $actor = Auth::user();
        $query = SmsOrder::query()->where('order_status', 'allocated');
        if ((string) $actor->account_type !== 'administrator') {
            $actorUserId = (string) $actor->user_id;
            $query->where(function ($inner) use ($actor, $actorUserId): void {
                $inner->where('user_id', $actorUserId);
                if ((string) $actor->account_type === 'reseller') {
                    $inner->orWhere('reseller_id', $actorUserId);
                }
                if ((string) $actor->account_type === 'agent') {
                    $inner->orWhere('agent_id', $actorUserId);
                }
            });
        }

        return view('modules.sales', [
            'sales' => $query->orderByDesc('id')->paginate(20),
        ]);
    }

    public function clients(): View
    {
        $actor = Auth::user();
        $query = User::query()->where('account_type', 'broadcaster');
        if ((string) $actor->account_type !== 'administrator') {
            $scopedUserIds = SmsOrder::query()
                ->select('user_id')
                ->where(function ($inner) use ($actor): void {
                    if ((string) $actor->account_type === 'reseller') {
                        $inner->where('reseller_id', (string) $actor->user_id);
                    } elseif ((string) $actor->account_type === 'agent') {
                        $inner->where('agent_id', (string) $actor->user_id);
                    } else {
                        $inner->where('user_id', (string) $actor->user_id);
                    }
                })
                ->distinct()
                ->pluck('user_id');
            $query->whereIn('user_id', $scopedUserIds);
        }

        return view('modules.clients', [
            'clients' => $query->orderByDesc('id')->paginate(30),
        ]);
    }

    public function resellers(): View
    {
        $actor = Auth::user();
        $query = User::query()->where('account_type', 'reseller');
        if ((string) $actor->account_type !== 'administrator') {
            $query->where('user_id', (string) $actor->user_id);
        }

        return view('modules.resellers', [
            'resellers' => $query->orderByDesc('id')->paginate(30),
        ]);
    }

    public function agents(): View
    {
        $actor = Auth::user();
        $query = User::query()->where('account_type', 'agent');
        if ((string) $actor->account_type !== 'administrator') {
            $query->where('user_id', (string) $actor->user_id);
        }

        return view('modules.agents', [
            'agents' => $query->orderByDesc('id')->paginate(30),
        ]);
    }

    public function scheduled(): View
    {
        $actor = Auth::user();
        $query = SmsOrder::query()->where('order_status', 'pending');
        if ((string) $actor->account_type !== 'administrator') {
            $actorUserId = (string) $actor->user_id;
            $query->where(function ($inner) use ($actor, $actorUserId): void {
                $inner->where('user_id', $actorUserId);
                if ((string) $actor->account_type === 'reseller') {
                    $inner->orWhere('reseller_id', $actorUserId);
                }
                if ((string) $actor->account_type === 'agent') {
                    $inner->orWhere('agent_id', $actorUserId);
                }
            });
        }

        return view('modules.scheduled', [
            'scheduled' => $query->orderByDesc('id')->paginate(20),
        ]);
    }

    public function history(): View
    {
        $actor = Auth::user();
        $query = Transaction::query();
        if ((string) $actor->account_type !== 'administrator') {
            $query->where('user_id', (string) $actor->user_id);
        }

        return view('modules.history', [
            'history' => $query->orderByDesc('id')->paginate(30),
        ]);
    }

    public function account(): View
    {
        return view('modules.account', ['user' => Auth::user()]);
    }

    public function senderIds(): View
    {
        $actor = Auth::user();
        $query = Sender::query();
        if ((string) $actor->account_type !== 'administrator') {
            $query->where('user_id', (string) $actor->user_id);
        }

        return view('modules.sender-ids', [
            'senders' => $query->orderByDesc('id')->paginate(30),
        ]);
    }

    public function storeClient(): RedirectResponse
    {
        return $this->storeUserByRole('broadcaster');
    }

    public function storeReseller(): RedirectResponse
    {
        return $this->storeUserByRole('reseller');
    }

    public function storeAgent(): RedirectResponse
    {
        return $this->storeUserByRole('agent');
    }

    public function storeSenderId(): RedirectResponse
    {
        $actor = Auth::user();
        $data = request()->validate([
            'sender_id' => ['required', 'string', 'max:11'],
            'user_id' => ['required', 'string', 'max:64'],
        ]);

        if ((string) $actor->account_type !== 'administrator') {
            $data['user_id'] = (string) $actor->user_id;
        }

        Sender::create([
            'sender_id' => strtoupper($data['sender_id']),
            'user_id' => $data['user_id'],
            'id_status' => 'pending',
        ]);

        return back()->with('success', 'Sender ID request saved.');
    }

    public function updateSenderStatus(int $id): RedirectResponse
    {
        abort_unless((string) Auth::user()->account_type === 'administrator', 403, 'Forbidden.');

        $data = request()->validate([
            'id_status' => ['required', 'in:pending,active,rejected,inactive'],
        ]);

        $sender = Sender::findOrFail($id);
        $sender->update($data);

        return back()->with('success', 'Sender ID status updated.');
    }

    private function storeUserByRole(string $accountType): RedirectResponse
    {
        abort_unless((string) Auth::user()->account_type === 'administrator', 403, 'Forbidden.');

        $data = request()->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'status' => ['nullable', 'in:active,pending,suspended,disabled'],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        User::create([
            'name' => $data['name'],
            'username' => Str::slug($data['name']).random_int(100, 999),
            'user_id' => strtoupper($accountType[0]).'-'.strtoupper(Str::random(8)),
            'email' => $data['email'] ?? null,
            'phone_number' => $data['phone_number'],
            'account_type' => $accountType,
            'status' => $data['status'] ?? 'active',
            'password' => Hash::make($data['password'] ?? Str::password(12)),
        ]);

        return back()->with('success', ucfirst($accountType).' account created.');
    }
}
