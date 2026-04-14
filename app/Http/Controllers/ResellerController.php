<?php

namespace App\Http\Controllers;

use App\Models\PricingScheme;
use App\Models\Reseller;
use App\Support\LegacyPassword;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ResellerController extends Controller
{
    public function index(Request $request): View
    {
        $keyword = $request->string('q')->toString();

        $resellers = Reseller::query()
            ->when($keyword !== '', function ($q) use ($keyword) {
                $q->where(function ($q2) use ($keyword) {
                    $q2->where('business_name', 'like', '%'.$keyword.'%')
                        ->orWhere('email', 'like', '%'.$keyword.'%')
                        ->orWhere('user_id', 'like', '%'.$keyword.'%');
                });
            })
            ->orderByDesc('date_created')
            ->paginate(15)
            ->withQueryString();

        $schemes = PricingScheme::query()
            ->where('user_id', 'Administrator')
            ->where('account_type', 'Reseller')
            ->orderBy('scheme_name')
            ->get();

        return view('resellers.index', compact('resellers', 'schemes', 'keyword'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'business_name' => ['required', 'string', 'max:191'],
            'phone_number' => ['required', 'string', 'max:32'],
            'business_address' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:191'],
            'scheme_id' => ['required', 'string', 'max:64'],
            'sender_id' => ['nullable', 'string', 'max:64'],
            'new_password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $userId = $data['email'];
        if (Reseller::query()->where('user_id', $userId)->exists()) {
            return redirect()->route('resellers.index')->withErrors(__('Email address already registered.'));
        }

        Reseller::query()->create([
            'user_id' => $userId,
            'password' => LegacyPassword::hashMd5($data['new_password']),
            'business_name' => $data['business_name'],
            'business_address' => $data['business_address'] ?? '',
            'phone_number' => $data['phone_number'],
            'email' => $data['email'],
            'status' => 'Active',
            'vcode' => '',
            'rcode' => '',
            'scheme_id' => $data['scheme_id'],
            'sender_id' => $data['sender_id'] ?? '',
            'date_created' => time(),
        ]);

        return redirect()->route('resellers.show', ['reseller' => $userId])->with('status', __('Reseller created.'));
    }

    public function show(string $reseller): View
    {
        $resellerModel = Reseller::query()->where('user_id', $reseller)->firstOrFail();
        $scheme = PricingScheme::query()->where('scheme_id', $resellerModel->scheme_id)->first();
        $schemes = PricingScheme::query()
            ->where('user_id', 'Administrator')
            ->where('account_type', 'Reseller')
            ->orderBy('scheme_name')
            ->get();
        try {
            $statuses = DB::table('account_status')->orderBy('status')->pluck('status');
        } catch (\Throwable) {
            $statuses = collect(['Active', 'Pending']);
        }

        $orders = DB::table('sms_orders')
            ->where('user_id', $resellerModel->user_id)
            ->orderByDesc('order_date')
            ->limit(50)
            ->get();

        return view('resellers.show', [
            'reseller' => $resellerModel,
            'scheme' => $scheme,
            'schemes' => $schemes,
            'statuses' => $statuses,
            'orders' => $orders,
            'balance' => $resellerModel->balance(),
        ]);
    }

    public function update(Request $request, string $reseller): RedirectResponse
    {
        $resellerModel = Reseller::query()->where('user_id', $reseller)->firstOrFail();

        $data = $request->validate([
            'business_name' => ['required', 'string', 'max:191'],
            'business_address' => ['nullable', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'max:32'],
            'email' => ['required', 'email', 'max:191'],
            'scheme_id' => ['required', 'string', 'max:64'],
            'sender_id' => ['nullable', 'string', 'max:64'],
            'status' => ['required', 'string', 'max:64'],
        ]);

        $resellerModel->fill([
            'business_name' => $data['business_name'],
            'business_address' => $data['business_address'] ?? '',
            'phone_number' => $data['phone_number'],
            'email' => $data['email'],
            'scheme_id' => $data['scheme_id'],
            'sender_id' => $data['sender_id'] ?? '',
            'status' => $data['status'],
        ]);
        $resellerModel->save();

        return redirect()->route('resellers.show', $resellerModel->user_id)->with('status', __('Reseller updated.'));
    }

    public function updatePassword(Request $request, string $reseller): RedirectResponse
    {
        $request->validate([
            'new_password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $resellerModel = Reseller::query()->where('user_id', $reseller)->firstOrFail();
        $resellerModel->password = LegacyPassword::hashMd5($request->string('new_password')->toString());
        $resellerModel->save();

        return redirect()->route('resellers.show', $resellerModel->user_id)->with('status', __('Password updated.'));
    }

    public function destroy(string $reseller): RedirectResponse
    {
        Reseller::query()->where('user_id', $reseller)->delete();

        return redirect()->route('resellers.index')->with('status', __('Reseller removed.'));
    }
}
