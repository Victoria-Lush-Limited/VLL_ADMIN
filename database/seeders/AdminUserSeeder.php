<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@vll.local'],
            [
                'name' => 'VLL Administrator',
                'username' => 'admin',
                'user_id' => 'admin@vll.local',
                'phone_number' => '255700000000',
                'account_type' => 'administrator',
                'status' => 'active',
                'password' => Hash::make('Password@123'),
            ]
        );
    }
}
