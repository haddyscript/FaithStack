<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('impersonation_tokens', function (Blueprint $table) {
            $table->string('token', 64)->primary();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('impersonator_id')->constrained('users')->cascadeOnDelete();
            $table->string('tenant_name');
            $table->timestamp('expires_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('impersonation_tokens');
    }
};
