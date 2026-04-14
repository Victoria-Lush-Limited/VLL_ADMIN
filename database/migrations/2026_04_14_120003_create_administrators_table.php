<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('administrators', function (Blueprint $table) {
            $table->string('user_id', 191)->primary();
            $table->string('password', 255);
            $table->string('full_name', 191)->nullable();
            $table->string('status', 32)->default('Active');
            $table->unsignedBigInteger('scheme_id')->nullable();
            $table->string('vcode', 64)->nullable();
            $table->string('rcode', 64)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('administrators');
    }
};
