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
        Schema::create('pricing_schemes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('owner_user_id', 64)->nullable()->index();
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });

        Schema::create('pricing', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pricing_scheme_id')->constrained('pricing_schemes')->cascadeOnDelete();
            $table->unsignedInteger('min_sms');
            $table->unsignedInteger('max_sms')->nullable();
            $table->decimal('price', 12, 2);
            $table->timestamps();
        });

        Schema::create('sms_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_pk')->nullable()->constrained('users')->nullOnDelete();
            $table->string('user_id', 64)->index();
            $table->string('account_type', 25)->nullable();
            $table->unsignedInteger('quantity');
            $table->decimal('price', 12, 2);
            $table->decimal('amount', 12, 2);
            $table->string('order_status', 25)->default('pending')->index();
            $table->string('reference', 64)->nullable()->unique();
            $table->string('receipt', 64)->nullable();
            $table->string('payment_method', 50)->nullable();
            $table->string('reseller_id', 64)->nullable()->index();
            $table->string('agent_id', 64)->nullable()->index();
            $table->timestamps();
        });

        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_pk')->nullable()->constrained('users')->nullOnDelete();
            $table->string('user_id', 64)->index();
            $table->string('username')->nullable();
            $table->unsignedBigInteger('allocated')->default(0);
            $table->unsignedBigInteger('consumed')->default(0);
            $table->string('reference', 64)->nullable()->index();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('payment_method', 50);
            $table->string('reseller_id', 64)->index();
            $table->text('instructions')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('sms_orders');
        Schema::dropIfExists('pricing');
        Schema::dropIfExists('pricing_schemes');
    }
};
