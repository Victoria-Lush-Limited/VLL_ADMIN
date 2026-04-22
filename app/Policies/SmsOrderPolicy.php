<?php

namespace App\Policies;

use App\Models\SmsOrder;
use App\Models\User;

class SmsOrderPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->account_type, ['administrator', 'reseller', 'agent'], true);
    }

    public function create(User $user, string $targetUserId): bool
    {
        if ($user->account_type === 'administrator') {
            return true;
        }

        return in_array($user->account_type, ['reseller', 'agent'], true)
            && (string) $user->user_id === $targetUserId;
    }

    public function view(User $user, SmsOrder $order): bool
    {
        if ($user->account_type === 'administrator') {
            return true;
        }

        return (string) $order->user_id === (string) $user->user_id;
    }

    public function allocate(User $user, SmsOrder $order): bool
    {
        return $this->view($user, $order);
    }
}
