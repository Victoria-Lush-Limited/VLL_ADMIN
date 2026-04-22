<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            if (! Schema::hasColumn('users', 'username')) {
                $table->string('username')->nullable()->unique()->after('name');
            }
            if (! Schema::hasColumn('users', 'user_id')) {
                $table->string('user_id', 64)->nullable()->unique()->after('username');
            }
            if (! Schema::hasColumn('users', 'phone_number')) {
                $table->string('phone_number', 20)->nullable()->index()->after('email');
            }
            if (! Schema::hasColumn('users', 'account_type')) {
                $table->string('account_type', 25)->default('broadcaster')->after('phone_number');
            }
            if (! Schema::hasColumn('users', 'status')) {
                $table->string('status', 25)->default('pending')->after('account_type');
            }
            if (! Schema::hasColumn('users', 'api_client_id')) {
                $table->string('api_client_id', 64)->nullable()->unique()->after('password');
            }
            if (! Schema::hasColumn('users', 'api_client_secret')) {
                $table->string('api_client_secret')->nullable()->after('api_client_id');
            }
            if (! Schema::hasColumn('users', 'api_token')) {
                $table->string('api_token')->nullable()->after('api_client_secret');
            }
        });
    }

    public function down(): void
    {
        // Intentionally left blank to avoid destructive rollback on shared environments.
    }
};
