<?php

namespace Database\Seeders;

use App\Models\Pricing;
use App\Models\PricingScheme;
use Illuminate\Database\Seeder;

class PricingSchemeSeeder extends Seeder
{
    public function run(): void
    {
        if (PricingScheme::query()->exists()) {
            return;
        }

        $adminScheme = PricingScheme::query()->create([
            'scheme_name' => 'Default',
            'account_type' => 'Administrator',
            'user_id' => 'Administrator',
        ]);

        Pricing::query()->create([
            'scheme_id' => $adminScheme->scheme_id,
            'min_sms' => 1,
            'max_sms' => 10_000,
            'price' => 1.0000,
        ]);

        Pricing::query()->create([
            'scheme_id' => $adminScheme->scheme_id,
            'min_sms' => 10_001,
            'max_sms' => 0,
            'price' => 0.8000,
        ]);

        $broadcaster = PricingScheme::query()->create([
            'scheme_name' => 'Standard',
            'account_type' => 'Broadcaster',
            'user_id' => 'Administrator',
        ]);

        Pricing::query()->create([
            'scheme_id' => $broadcaster->scheme_id,
            'min_sms' => 1,
            'max_sms' => 0,
            'price' => 1.0000,
        ]);

        $reseller = PricingScheme::query()->create([
            'scheme_name' => 'Standard',
            'account_type' => 'Reseller',
            'user_id' => 'Administrator',
        ]);

        Pricing::query()->create([
            'scheme_id' => $reseller->scheme_id,
            'min_sms' => 1,
            'max_sms' => 0,
            'price' => 0.9000,
        ]);

        $agent = PricingScheme::query()->create([
            'scheme_name' => 'Standard',
            'account_type' => 'Agent',
            'user_id' => 'Administrator',
        ]);

        Pricing::query()->create([
            'scheme_id' => $agent->scheme_id,
            'min_sms' => 1,
            'max_sms' => 0,
            'price' => 0.9500,
        ]);
    }
}
