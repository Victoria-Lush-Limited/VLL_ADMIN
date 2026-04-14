<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\PricingScheme;
use App\Support\LegacyPassword;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AgentController extends Controller
{
    public function index(Request $request): View
    {
        $keyword = $request->string('q')->toString();

        $agents = Agent::query()
            ->when($keyword !== '', function ($q) use ($keyword) {
                $q->where(function ($q2) use ($keyword) {
                    $q2->where('agent_name', 'like', '%'.$keyword.'%')
                        ->orWhere('email', 'like', '%'.$keyword.'%')
                        ->orWhere('user_id', 'like', '%'.$keyword.'%');
                });
            })
            ->orderByDesc('date_created')
            ->paginate(15)
            ->withQueryString();

        $schemes = PricingScheme::query()
            ->where('user_id', 'Administrator')
            ->where('account_type', 'Agent')
            ->orderBy('scheme_name')
            ->get();

        return view('agents.index', compact('agents', 'schemes', 'keyword'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'agent_name' => ['required', 'string', 'max:191'],
            'region' => ['nullable', 'string', 'max:191'],
            'agent_address' => ['nullable', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'max:32'],
            'email' => ['required', 'email', 'max:191'],
            'scheme_id' => ['required', 'string', 'max:64'],
            'new_password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $userId = $data['email'];
        if (Agent::query()->where('user_id', $userId)->exists()) {
            return redirect()->route('agents.index')->withErrors(__('Email address already registered.'));
        }

        Agent::query()->create([
            'user_id' => $userId,
            'password' => LegacyPassword::hashMd5($data['new_password']),
            'agent_name' => $data['agent_name'],
            'region' => $data['region'] ?? '',
            'agent_address' => $data['agent_address'] ?? '',
            'phone_number' => $data['phone_number'],
            'email' => $data['email'],
            'status' => 'Active',
            'vcode' => '',
            'rcode' => '',
            'scheme_id' => $data['scheme_id'],
            'date_created' => time(),
        ]);

        return redirect()->route('agents.show', ['agent' => $userId])->with('status', __('Agent created.'));
    }

    public function show(string $agent): View
    {
        $agentModel = Agent::query()->where('user_id', $agent)->firstOrFail();
        $scheme = PricingScheme::query()->where('scheme_id', $agentModel->scheme_id)->first();
        $schemes = PricingScheme::query()
            ->where('user_id', 'Administrator')
            ->where('account_type', 'Agent')
            ->orderBy('scheme_name')
            ->get();
        try {
            $statuses = DB::table('account_status')->orderBy('status')->pluck('status');
        } catch (\Throwable) {
            $statuses = collect(['Active', 'Pending']);
        }

        $orders = DB::table('sms_orders')
            ->where('user_id', $agentModel->user_id)
            ->orderByDesc('order_date')
            ->limit(50)
            ->get();

        return view('agents.show', [
            'agent' => $agentModel,
            'scheme' => $scheme,
            'schemes' => $schemes,
            'statuses' => $statuses,
            'orders' => $orders,
            'balance' => $agentModel->balance(),
        ]);
    }

    public function update(Request $request, string $agent): RedirectResponse
    {
        $agentModel = Agent::query()->where('user_id', $agent)->firstOrFail();

        $data = $request->validate([
            'agent_name' => ['required', 'string', 'max:191'],
            'region' => ['nullable', 'string', 'max:191'],
            'agent_address' => ['nullable', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'max:32'],
            'email' => ['required', 'email', 'max:191'],
            'scheme_id' => ['required', 'string', 'max:64'],
            'status' => ['required', 'string', 'max:64'],
        ]);

        $agentModel->fill([
            'agent_name' => $data['agent_name'],
            'region' => $data['region'] ?? '',
            'agent_address' => $data['agent_address'] ?? '',
            'phone_number' => $data['phone_number'],
            'email' => $data['email'],
            'scheme_id' => $data['scheme_id'],
            'status' => $data['status'],
        ]);
        $agentModel->save();

        return redirect()->route('agents.show', $agentModel->user_id)->with('status', __('Agent updated.'));
    }

    public function updatePassword(Request $request, string $agent): RedirectResponse
    {
        $request->validate([
            'new_password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $agentModel = Agent::query()->where('user_id', $agent)->firstOrFail();
        $agentModel->password = LegacyPassword::hashMd5($request->string('new_password')->toString());
        $agentModel->save();

        return redirect()->route('agents.show', $agentModel->user_id)->with('status', __('Password updated.'));
    }

    public function destroy(string $agent): RedirectResponse
    {
        Agent::query()->where('user_id', $agent)->delete();

        return redirect()->route('agents.index')->with('status', __('Agent removed.'));
    }
}
