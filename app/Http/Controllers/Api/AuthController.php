<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Concerns\ApiResponder;
use App\Http\Controllers\Controller;
use App\Http\Requests\AuthLoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use ApiResponder;

    public function login(AuthLoginRequest $request): JsonResponse
    {
        $data = $request->validated();
        $login = $data['login'];

        $user = User::query()
            ->where('email', $login)
            ->orWhere('username', $login)
            ->orWhere('user_id', $login)
            ->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            return $this->fail('Invalid credentials.', 401);
        }

        if (($user->status ?? 'pending') !== 'active') {
            return $this->fail('Account is not active.', 403);
        }

        $token = $user->createToken(
            $data['device_name'] ?? 'api-token',
            $this->abilitiesFor((string) $user->account_type)
        )->plainTextToken;

        return $this->ok('Login successful.', [
            'token' => $token,
            'user' => $user,
        ]);
    }

    public function me(Request $request): JsonResponse
    {
        return $this->ok('Authenticated user retrieved.', $request->user());
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()?->currentAccessToken()?->delete();

        return $this->ok('Logout successful.');
    }

    private function abilitiesFor(string $accountType): array
    {
        return match (strtolower($accountType)) {
            'administrator' => ['profile.read', 'backoffice.manage', 'messaging.manage', 'gateway.dispatch'],
            'reseller', 'agent' => ['profile.read', 'backoffice.manage', 'messaging.manage'],
            'broadcaster' => ['profile.read', 'messaging.manage'],
            default => ['profile.read'],
        };
    }
}
