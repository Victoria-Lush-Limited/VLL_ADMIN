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
        return view('modules.sales', [
            'sales' => SmsOrder::where('order_status', 'allocated')->orderByDesc('id')->paginate(20),
        ]);
    }

    public function clients(): View
    {
        return view('modules.clients', [
            'clients' => User::where('account_type', 'broadcaster')->orderByDesc('id')->paginate(30),
        ]);
    }

    public function resellers(): View
    {
        return view('modules.resellers', [
            'resellers' => User::where('account_type', 'reseller')->orderByDesc('id')->paginate(30),
        ]);
    }

    public function agents(): View
    {
        return view('modules.agents', [
            'agents' => User::where('account_type', 'agent')->orderByDesc('id')->paginate(30),
        ]);
    }

    public function scheduled(): View
    {
        return view('modules.scheduled', [
            'scheduled' => SmsOrder::where('order_status', 'pending')->orderByDesc('id')->paginate(20),
        ]);
    }

    public function history(): View
    {
        return view('modules.history', [
            'history' => Transaction::orderByDesc('id')->paginate(30),
        ]);
    }

    public function account(): View
    {
        return view('modules.account', ['user' => Auth::user()]);
    }

    public function senderIds(): View
    {
        return view('modules.sender-ids', [
            'senders' => Sender::orderByDesc('id')->paginate(30),
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
        $data = request()->validate([
            'sender_id' => ['required', 'string', 'max:11'],
            'user_id' => ['required', 'string', 'max:64'],
        ]);

        Sender::create([
            'sender_id' => strtoupper($data['sender_id']),
            'user_id' => $data['user_id'],
            'id_status' => 'pending',
        ]);

        return back()->with('success', 'Sender ID request saved.');
    }

    public function updateSenderStatus(int $id): RedirectResponse
    {
        $data = request()->validate([
            'id_status' => ['required', 'in:pending,active,rejected,inactive'],
        ]);

        $sender = Sender::findOrFail($id);
        $sender->update($data);

        return back()->with('success', 'Sender ID status updated.');
    }

    private function storeUserByRole(string $accountType): RedirectResponse
    {
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
