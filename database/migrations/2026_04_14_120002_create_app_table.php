<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('app', function (Blueprint $table) {
            $table->id();
            $table->string('app_name', 191);
            $table->string('sender_id', 64)->default('VERIFY');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('app');
    }
};
