<?php

namespace Database\Seeders;

use App\Models\Administrator;
use App\Models\PricingScheme;
use App\Support\LegacyPassword;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdministratorSeeder extends Seeder
{
    public function run(): void
    {
        if (DB::table('app')->count() === 0) {
            DB::table('app')->insert([
                'app_name' => config('app.name', 'VLL Admin'),
                'sender_id' => 'VERIFY',
            ]);
        }

        $userId = (string) env('ADMIN_SEED_USER_ID', 'admin');
        if (Administrator::query()->where('user_id', $userId)->exists()) {
            return;
        }

        $adminScheme = PricingScheme::query()
            ->where('user_id', 'Administrator')
            ->where('account_type', 'Administrator')
            ->orderBy('scheme_id')
            ->first();

        Administrator::query()->create([
            'user_id' => $userId,
            'password' => LegacyPassword::hash((string) env('ADMIN_SEED_PASSWORD', 'password')),
            'full_name' => 'System Administrator',
            'status' => 'Active',
            'scheme_id' => $adminScheme?->scheme_id,
            'vcode' => null,
            'rcode' => null,
        ]);
    }
}
