<?php

namespace App\Http\Controllers;

use App\Models\Sender;
use App\Models\SmsClient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class SenderController extends Controller
{
    public function index(Request $request): View
    {
        $keyword = $request->string('q')->toString();

        $senders = Sender::query()
            ->when($keyword !== '', function ($q) use ($keyword) {
                $q->where(function ($q2) use ($keyword) {
                    $q2->where('sender_id', 'like', '%'.$keyword.'%')
                        ->orWhere('message', 'like', '%'.$keyword.'%');
                });
            })
            ->orderByDesc('date_requested')
            ->paginate(15)
            ->withQueryString();

        $senders->load(['client']);

        $clients = SmsClient::query()->orderBy('client_name')->limit(500)->get();

        $idTypes = Schema::hasTable('id_types')
            ? DB::table('id_types')->orderBy('id_type')->pluck('id_type')
            : collect(['Private', 'Public']);

        $idStatuses = Schema::hasTable('id_status')
            ? DB::table('id_status')->orderBy('id_status')->pluck('id_status')
            : collect(['Pending', 'Approved', 'Rejected']);

        return view('senders.index', compact('senders', 'clients', 'keyword', 'idTypes', 'idStatuses'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'user_id' => ['required', 'string', 'max:32'],
            'sender_id' => ['required', 'string', 'max:32'],
            'message' => ['required', 'string', 'max:500'],
        ]);

        $dup = Sender::query()
            ->where('sender_id', $data['sender_id'])
            ->where(function ($q) use ($data) {
                $q->where('user_id', $data['user_id'])
                    ->orWhere('id_type', 'Public');
            })
            ->exists();

        if ($dup) {
            return redirect()->route('senders.index')->withErrors(__('Duplicate sender for this client or public ID.'));
        }

        $row = [
            'sender_id' => $data['sender_id'],
            'message' => $data['message'],
            'id_type' => 'Private',
            'user_id' => $data['user_id'],
            'date_requested' => time(),
        ];

        if (Schema::hasColumn('senders', 'id_status')) {
            $row['id_status'] = 'Pending';
        }
        if (Schema::hasColumn('senders', 'status')) {
            $row['status'] = 'Pending';
        }

        Sender::query()->create($row);

        return redirect()->route('senders.index')->with('status', __('Sender request saved.'));
    }

    public function update(Request $request, Sender $sender): RedirectResponse
    {
        $data = $request->validate([
            'id_type' => ['required', 'string', 'max:64'],
            'id_status' => ['required', 'string', 'max:64'],
        ]);

        $sender->id_type = $data['id_type'];
        if (Schema::hasColumn('senders', 'id_status')) {
            $sender->id_status = $data['id_status'];
        }
        if (Schema::hasColumn('senders', 'status')) {
            $sender->status = $data['id_status'];
        }
        $sender->save();

        return redirect()->route('senders.index')->with('status', __('Sender updated.'));
    }

    public function destroy(Sender $sender): RedirectResponse
    {
        $sender->delete();

        return redirect()->route('senders.index')->with('status', __('Sender removed.'));
    }
}
