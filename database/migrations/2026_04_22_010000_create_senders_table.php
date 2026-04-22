<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('senders')) {
            Schema::create('senders', function (Blueprint $table): void {
                $table->id();
                $table->string('sender_id', 11)->index();
                $table->string('user_id', 64)->index();
                $table->enum('id_status', ['pending', 'active', 'rejected', 'inactive'])->default('pending');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('senders');
    }
};
