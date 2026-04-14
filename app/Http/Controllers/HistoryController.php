<?php

namespace App\Http\Controllers;

use App\Models\Outgoing;
use App\Models\SmsClient;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HistoryController extends Controller
{
    public function index(Request $request): View
    {
        $keyword = $request->string('q')->toString();
        $clientId = $request->string('client_id')->toString();
        $smsStatus = $request->string('sms_status')->toString();
        $fromInput = $request->input('from', date('Y-m-d', strtotime('-30 days')));
        $toInput = $request->input('to', date('Y-m-d'));
        $from = strtotime($fromInput.' 00:00:00') ?: strtotime('-30 days');
        $to = strtotime($toInput.' 23:59:59') ?: time();
        $now = time();

        $clients = SmsClient::query()->orderBy('username')->get(['user_id', 'username', 'client_name']);

        $statuses = Outgoing::query()
            ->select('sms_status')
            ->distinct()
            ->orderBy('sms_status')
            ->pluck('sms_status');

        $messages = Outgoing::query()
            ->where('date_created', '>', $from)
            ->where('date_created', '<=', $to)
            ->where('date_created', '<', $now)
            ->when($clientId !== '', fn ($q) => $q->where('user_id', 'like', '%'.$clientId.'%'))
            ->when($smsStatus !== '', fn ($q) => $q->where('sms_status', 'like', '%'.$smsStatus.'%'))
            ->when($keyword !== '', function ($q) use ($keyword) {
                $q->where(function ($q2) use ($keyword) {
                    $q2->where('message', 'like', '%'.$keyword.'%')
                        ->orWhere('phone_number', 'like', '%'.$keyword.'%')
                        ->orWhere('sender_id', 'like', '%'.$keyword.'%');
                });
            })
            ->orderByDesc('date_created')
            ->paginate(25)
            ->withQueryString();

        return view('history.index', compact(
            'messages',
            'keyword',
            'clients',
            'statuses',
            'clientId',
            'smsStatus',
            'from',
            'to',
            'fromInput',
            'toInput'
        ));
    }
}
