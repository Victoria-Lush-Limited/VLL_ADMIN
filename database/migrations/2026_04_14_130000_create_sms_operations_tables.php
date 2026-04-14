<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->string('user_id', 191)->primary();
            $table->string('password', 255);
            $table->string('client_name', 191)->nullable();
            $table->string('status', 32)->default('Pending');
            $table->string('vcode', 64)->nullable();
            $table->string('rcode', 64)->nullable();
            $table->unsignedBigInteger('scheme_id')->nullable();
            $table->string('username', 191)->nullable();
            $table->string('email', 191)->nullable();
            $table->string('contact_phone', 64)->nullable();
            $table->string('reseller_id', 191)->default('Administrator');
            $table->unsignedInteger('user_date_created')->nullable();
        });

        Schema::create('resellers', function (Blueprint $table) {
            $table->string('user_id', 191)->primary();
            $table->string('password', 255);
            $table->string('business_name', 191)->nullable();
            $table->string('business_address', 255)->nullable();
            $table->string('phone_number', 64)->nullable();
            $table->string('email', 191)->nullable();
            $table->string('status', 32)->default('Active');
            $table->string('vcode', 64)->nullable();
            $table->string('rcode', 64)->nullable();
            $table->unsignedBigInteger('scheme_id')->nullable();
            $table->string('sender_id', 64)->nullable();
            $table->unsignedInteger('date_created')->nullable();
        });

        Schema::create('agents', function (Blueprint $table) {
            $table->string('user_id', 191)->primary();
            $table->string('password', 255);
            $table->string('agent_name', 191)->nullable();
            $table->string('region', 128)->nullable();
            $table->string('agent_address', 255)->nullable();
            $table->string('phone_number', 64)->nullable();
            $table->string('email', 191)->nullable();
            $table->string('status', 32)->default('Active');
            $table->string('vcode', 64)->nullable();
            $table->string('rcode', 64)->nullable();
            $table->unsignedBigInteger('scheme_id')->nullable();
            $table->unsignedInteger('date_created')->nullable();
        });

        Schema::create('sms_orders', function (Blueprint $table) {
            $table->bigIncrements('order_id');
            $table->string('user_id', 191);
            $table->string('account_type', 64);
            $table->unsignedInteger('quantity');
            $table->decimal('price', 12, 4);
            $table->decimal('amount', 14, 4);
            $table->unsignedInteger('order_date')->nullable();
            $table->string('order_status', 32)->default('Pending');
            $table->string('reference', 191)->nullable();
            $table->string('receipt', 191)->nullable();
            $table->string('payment_method', 191)->nullable();
        });

        Schema::create('outgoing', function (Blueprint $table) {
            $table->bigIncrements('sms_id');
            $table->string('phone_number', 32);
            $table->string('sender_id', 64)->nullable();
            $table->text('message')->nullable();
            $table->unsignedInteger('credits')->default(0);
            $table->string('schedule', 32)->default('None');
            $table->unsignedInteger('start_date')->nullable();
            $table->unsignedInteger('end_date')->nullable();
            $table->unsignedInteger('date_created')->nullable();
            $table->unsignedInteger('attempts')->default(0);
            $table->string('sms_status', 32)->default('Pending');
            $table->string('user_id', 191)->nullable();
            $table->string('smsc_id', 64)->nullable();
        });

        Schema::create('transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('user_id', 191);
            $table->unsignedInteger('allocated')->default(0);
            $table->unsignedInteger('consumed')->default(0);
            $table->unsignedInteger('tdate')->nullable();
        });

        Schema::create('senders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('sender_id', 64);
            $table->string('message', 500)->nullable();
            $table->string('id_type', 64)->default('Private');
            $table->string('user_id', 191);
            $table->unsignedInteger('date_requested')->nullable();
            $table->string('id_status', 64)->nullable();
            $table->string('status', 64)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('senders');
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('outgoing');
        Schema::dropIfExists('sms_orders');
        Schema::dropIfExists('agents');
        Schema::dropIfExists('resellers');
        Schema::dropIfExists('users');
    }
};
