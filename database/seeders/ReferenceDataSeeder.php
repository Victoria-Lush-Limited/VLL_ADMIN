<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReferenceDataSeeder extends Seeder
{
    /**
     * Seed the application's reference data.
     */
    public function run(): void
    {
        DB::table('account_statuses')->insertOrIgnore([
            ['name' => 'pending', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'active', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'disabled', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('order_statuses')->insertOrIgnore([
            ['name' => 'pending', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'allocated', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'cancelled', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('sender_id_statuses')->insertOrIgnore([
            ['name' => 'pending', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'active', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'rejected', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'inactive', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('pricing_schemes')->insertOrIgnore([
            [
                'name' => 'Default',
                'owner_user_id' => null,
                'is_default' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
