<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('account_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name', 25)->unique();
            $table->timestamps();
        });

        Schema::create('order_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name', 25)->unique();
            $table->timestamps();
        });

        Schema::create('sender_id_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name', 25)->unique();
            $table->timestamps();
        });

        Schema::create('administrators', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_pk')->unique()->constrained('users')->cascadeOnDelete();
            $table->string('full_name');
            $table->string('rcode', 10)->nullable();
            $table->timestamps();
        });

        Schema::create('agents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_pk')->unique()->constrained('users')->cascadeOnDelete();
            $table->string('agent_name');
            $table->string('region')->nullable();
            $table->string('agent_address')->nullable();
            $table->string('phone_number', 20);
            $table->string('email')->nullable();
            $table->unsignedBigInteger('pricing_scheme_id')->nullable();
            $table->timestamps();
        });

        Schema::create('resellers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_pk')->unique()->constrained('users')->cascadeOnDelete();
            $table->string('business_name');
            $table->string('business_address')->nullable();
            $table->string('phone_number', 20);
            $table->string('email')->nullable();
            $table->unsignedBigInteger('pricing_scheme_id')->nullable();
            $table->string('sender_id', 11)->nullable();
            $table->string('color_type', 20)->nullable();
            $table->string('primary_color')->nullable();
            $table->string('secondary_color')->nullable();
            $table->string('logo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resellers');
        Schema::dropIfExists('agents');
        Schema::dropIfExists('administrators');
        Schema::dropIfExists('sender_id_statuses');
        Schema::dropIfExists('order_statuses');
        Schema::dropIfExists('account_statuses');
    }
};
