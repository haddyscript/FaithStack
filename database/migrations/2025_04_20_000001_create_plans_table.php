<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->decimal('price_monthly', 8, 2)->default(0);
            $table->unsignedInteger('trial_days')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->string('cta_label')->default('Get Started');
            $table->string('badge')->nullable();
            $table->string('description');
            $table->json('features');
            $table->json('missing_features')->nullable();
            $table->json('limits')->nullable();
            $table->string('stripe_price_id')->nullable(); // future Stripe integration
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
