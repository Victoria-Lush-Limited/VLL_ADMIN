<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pricing_schemes', function (Blueprint $table) {
            $table->bigIncrements('scheme_id');
            $table->string('scheme_name', 191);
            $table->string('account_type', 64);
            $table->string('user_id', 191);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pricing_schemes');
    }
};
