<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pricing', function (Blueprint $table) {
            $table->bigIncrements('pricing_id');
            $table->unsignedBigInteger('scheme_id');
            $table->unsignedInteger('min_sms');
            $table->unsignedInteger('max_sms');
            $table->decimal('price', 12, 4);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pricing');
    }
};
