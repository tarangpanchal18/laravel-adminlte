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
        Schema::create('custom_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('module')->nullable();
            $table->enum('severity', ['error', 'warning', 'critical'])->default('error')->nullable();
            $table->string('file')->nullable();
            $table->string('line')->nullable();
            $table->string('url')->nullable();
            $table->string('method')->nullable();
            $table->text('message')->nullable();
            $table->string('ip_address')->nullable();
            $table->longText('description')->nullable();
            $table->json('metadata')->nullable();
            $table->tinyInteger('is_read')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_logs');
    }
};
