<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class BackofficeFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_order_and_allocate_credits(): void
    {
        $this->seed();
        $admin = User::where('account_type', 'administrator')->firstOrFail();
        Sanctum::actingAs($admin, ['profile.read', 'backoffice.manage']);

        $schemeResponse = $this->postJson('/api/v1/backoffice/pricing-schemes', [
                'name' => 'Standard',
                'tiers' => [
                    ['min_sms' => 1, 'max_sms' => 1000, 'price' => 25],
                ],
            ]);
        $schemeResponse->assertCreated();
        $schemeId = $schemeResponse->json('data.id');

        $orderResponse = $this->postJson('/api/v1/backoffice/sms-orders', [
                'user_id' => '255700000001',
                'quantity' => 100,
                'pricing_scheme_id' => $schemeId,
            ]);
        $orderResponse->assertCreated();
        $orderId = $orderResponse->json('data.id');

        $this->postJson("/api/v1/backoffice/sms-orders/{$orderId}/allocate")
            ->assertOk()
            ->assertJsonPath('data.order_status', 'allocated');
    }

    public function test_backoffice_requires_token_ability(): void
    {
        $this->seed();
        $admin = User::where('account_type', 'administrator')->firstOrFail();
        Sanctum::actingAs($admin, ['profile.read']);

        $this->getJson('/api/v1/backoffice/pricing-schemes')
            ->assertForbidden();
    }
}
