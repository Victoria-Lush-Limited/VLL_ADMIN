<?php

namespace App\Services\Sms;

use App\Jobs\SendSmsChunkJob;
use App\Models\SmsOrder;
use App\Models\User;
use App\Support\PhoneNumber;
use Illuminate\Support\Str;

class AllocationNotificationService
{
    public function sendAllocatedCreditsNotice(SmsOrder $order): void
    {
        $user = User::query()->where('user_id', $order->user_id)->first();
        if (! $user) {
            return;
        }

        $msisdn = PhoneNumber::normalizeTz($user->phone_number);
        if (! $msisdn) {
            return;
        }

        $message = sprintf(
            'Dear %s, your SMS order %s has been allocated with %d SMS credits.',
            $user->name ?: ($user->username ?: $user->user_id),
            $order->reference,
            (int) $order->quantity
        );

        SendSmsChunkJob::dispatch([
            [
                'text' => $message,
                'msisdn' => $msisdn,
                'source' => (string) config('services.fasthub.otp_sender_id', 'VLLSMS'),
                'reference' => 'ALLOC-'.Str::upper(Str::random(26)),
            ],
        ], [
            'type' => 'allocation-notice',
            'order_id' => $order->id,
            'user_id' => $order->user_id,
        ]);
    }
}
