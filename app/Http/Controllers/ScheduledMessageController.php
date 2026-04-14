<?php

namespace App\Http\Controllers;

use App\Models\Outgoing;
use App\Models\SmsClient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ScheduledMessageController extends Controller
{
    public function index(Request $request): View
    {
        $keyword = $request->string('q')->toString();
        $now = time();

        $messages = Outgoing::query()
            ->where('date_created', '>', $now)
            ->when($keyword !== '', function ($q) use ($keyword) {
                $q->where(function ($q2) use ($keyword) {
                    $q2->where('message', 'like', '%'.$keyword.'%')
                        ->orWhere('phone_number', 'like', '%'.$keyword.'%')
                        ->orWhere('sender_id', 'like', '%'.$keyword.'%');
                });
            })
            ->orderBy('date_created')
            ->paginate(20)
            ->withQueryString();

        $clients = SmsClient::query()->pluck('client_name', 'user_id');

        return view('scheduled.index', compact('messages', 'keyword', 'clients'));
    }

    public function cancel(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'sms_ids' => ['required', 'array'],
            'sms_ids.*' => ['integer'],
        ]);

        Outgoing::query()
            ->whereIn('sms_id', $data['sms_ids'])
            ->update(['sms_status' => 'Cancelled']);

        return redirect()->route('scheduled.index')->with('status', __('Selected messages cancelled.'));
    }
}
