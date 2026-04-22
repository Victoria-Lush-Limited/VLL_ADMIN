<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_login_and_get_profile(): void
    {
        $this->seed();

        $login = $this->postJson('/api/v1/auth/login', [
            'login' => 'admin@vll.local',
            'password' => 'Password@123',
            'device_name' => 'test-device',
        ])->assertOk();

        $token = $login->json('data.token');
        $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/v1/auth/me')
            ->assertOk()
            ->assertJsonPath('data.account_type', 'administrator');
    }
}
