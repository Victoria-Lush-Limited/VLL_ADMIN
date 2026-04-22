<?php

namespace App\Jobs;

use App\Services\Sms\FastHubClient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendSmsChunkJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tries = 4;

    public int $timeout = 120;

    /**
     * @param  array<int, array{text: string, msisdn: string, source: string, reference: string}>  $messages
     */
    public function __construct(
        private array $messages,
        private array $context = []
    ) {
        $this->onQueue('sms-dispatch');
    }

    public function handle(FastHubClient $fastHubClient): void
    {
        $response = $fastHubClient->sendBulk($this->messages);
        if (! ($response['ok'] ?? false)) {
            Log::warning('Admin SMS chunk failed', [
                'context' => $this->context,
                'response' => $response,
            ]);
        }
    }
}
