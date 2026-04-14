<?php

namespace App\Http\Controllers;

use App\Models\Outgoing;
use App\Models\PricingScheme;
use App\Models\Reseller;
use App\Models\SmsClient;
use App\Support\LegacyPassword;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ClientController extends Controller
{
    public function index(Request $request): View
    {
        $keyword = $request->string('q')->toString();

        $clients = SmsClient::query()
            ->when($keyword !== '', function ($q) use ($keyword) {
                $q->where(function ($q2) use ($keyword) {
                    $q2->where('username', 'like', '%'.$keyword.'%')
                        ->orWhere('client_name', 'like', '%'.$keyword.'%')
                        ->orWhere('email', 'like', '%'.$keyword.'%')
                        ->orWhere('contact_phone', 'like', '%'.$keyword.'%')
                        ->orWhere('user_id', 'like', '%'.$keyword.'%');
                });
            })
            ->orderByDesc('user_date_created')
            ->paginate(15)
            ->withQueryString();

        $schemes = PricingScheme::query()
            ->where('user_id', 'Administrator')
            ->where('account_type', 'Broadcaster')
            ->orderBy('scheme_name')
            ->get();

        return view('clients.index', compact('clients', 'schemes', 'keyword'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'client_name' => ['required', 'string', 'max:191'],
            'phone_number' => ['required', 'string', 'max:32'],
            'email' => ['required', 'email', 'max:191'],
            'scheme_id' => ['required', 'string', 'max:64'],
            'username' => ['required', 'string', 'max:191'],
            'new_password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        if (SmsClient::query()->where('username', $data['username'])->exists()) {
            return redirect()->route('clients.index')->withErrors(__('Username already registered.'));
        }

        $userId = $data['phone_number'];
        if (SmsClient::query()->where('user_id', $userId)->exists()) {
            return redirect()->route('clients.index')->withErrors(__('Phone number already registered.'));
        }

        $vcode = (string) random_int(100000, 999999);
        $now = time();

        $client = new SmsClient;
        $client->user_id = $userId;
        $client->password = LegacyPassword::hashMd5($data['new_password']);
        $client->client_name = $data['client_name'];
        $client->status = 'Pending';
        $client->vcode = $vcode;
        $client->rcode = '';
        $client->scheme_id = $data['scheme_id'];
        $client->username = $data['username'];
        $client->email = $data['email'];
        $client->contact_phone = $data['phone_number'];
        $client->reseller_id = 'Administrator';
        $client->user_date_created = $now;
        $client->save();

        $app = DB::table('app')->first();
        $senderId = $app->sender_id ?? 'VERIFY';
        $admin = Auth::guard('admin')->user();
        $message = 'Your verification code is: '.$vcode;
        $credits = (int) max(1, ceil(strlen($message) / 160));

        Outgoing::query()->create([
            'phone_number' => $userId,
            'sender_id' => $senderId,
            'message' => $message,
            'credits' => $credits,
            'schedule' => 'None',
            'start_date' => $now,
            'end_date' => $now,
            'date_created' => $now,
            'attempts' => 0,
            'sms_status' => 'Pending',
            'user_id' => $admin->user_id,
            'smsc_id' => '',
        ]);

        return redirect()->route('clients.show', ['client' => $userId])->with('status', __('Client created.'));
    }

    public function show(string $client): View
    {
        $clientModel = SmsClient::query()->where('user_id', $client)->firstOrFail();
        $scheme = PricingScheme::query()->where('scheme_id', $clientModel->scheme_id)->first();
        $reseller = Reseller::query()->where('user_id', $clientModel->reseller_id)->first();
        $schemes = PricingScheme::query()
            ->where('user_id', 'Administrator')
            ->where('account_type', 'Broadcaster')
            ->orderBy('scheme_name')
            ->get();

        try {
            $statuses = DB::table('account_status')->orderBy('status')->pluck('status');
        } catch (\Throwable) {
            $statuses = collect(['Active', 'Pending']);
        }

        $orders = DB::table('sms_orders')
            ->where('user_id', $clientModel->user_id)
            ->orderByDesc('order_date')
            ->limit(50)
            ->get();

        return view('clients.show', [
            'client' => $clientModel,
            'scheme' => $scheme,
            'reseller' => $reseller,
            'schemes' => $schemes,
            'statuses' => $statuses,
            'orders' => $orders,
            'balance' => $clientModel->balance(),
        ]);
    }

    public function update(Request $request, string $client): RedirectResponse
    {
        $clientModel = SmsClient::query()
            ->where('user_id', $client)
            ->where('reseller_id', 'Administrator')
            ->firstOrFail();

        $data = $request->validate([
            'client_name' => ['required', 'string', 'max:191'],
            'contact_phone' => ['required', 'string', 'max:32'],
            'email' => ['required', 'email', 'max:191'],
            'username' => ['required', 'string', 'max:191'],
            'scheme_id' => ['required', 'string', 'max:64'],
            'status' => ['required', 'string', 'max:64'],
        ]);

        $other = SmsClient::query()->where('username', $data['username'])->where('user_id', '!=', $clientModel->user_id)->exists();
        if ($other) {
            return redirect()->route('clients.show', $clientModel->user_id)->withErrors(__('Username already in use.'));
        }

        $clientModel->fill([
            'client_name' => $data['client_name'],
            'contact_phone' => $data['contact_phone'],
            'email' => $data['email'],
            'username' => $data['username'],
            'scheme_id' => $data['scheme_id'],
            'status' => $data['status'],
        ]);
        $clientModel->save();

        return redirect()->route('clients.show', $clientModel->user_id)->with('status', __('Client updated.'));
    }

    public function updatePassword(Request $request, string $client): RedirectResponse
    {
        $request->validate([
            'new_password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $clientModel = SmsClient::query()->where('user_id', $client)->firstOrFail();
        $clientModel->password = LegacyPassword::hashMd5($request->string('new_password')->toString());
        $clientModel->save();

        return redirect()->route('clients.show', $clientModel->user_id)->with('status', __('Password updated.'));
    }

    public function transfer(Request $request, string $client): RedirectResponse
    {
        $data = $request->validate([
            'transfer_reseller_id' => ['required', 'string', 'max:191'],
        ]);

        $clientModel = SmsClient::query()->where('user_id', $client)->firstOrFail();
        $clientModel->reseller_id = $data['transfer_reseller_id'];
        $clientModel->save();

        return redirect()->route('clients.show', $clientModel->user_id)->with('status', __('Client transferred.'));
    }

    public function destroy(string $client): RedirectResponse
    {
        SmsClient::query()->where('user_id', $client)->delete();

        return redirect()->route('clients.index')->with('status', __('Client removed.'));
    }
}
