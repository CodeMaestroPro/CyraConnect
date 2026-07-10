<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('startups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('funding_stage')->nullable();
            $table->decimal('total_funding', 15, 2)->nullable();
            $table->date('last_funding_date')->nullable();
            $table->decimal('last_funding_amount', 15, 2)->nullable();
            $table->string('pitch_deck')->nullable();
            $table->string('business_model')->nullable();
            $table->string('revenue_model', 100)->nullable();
            $table->unsignedInteger('monthly_users')->nullable();
            $table->unsignedInteger('monthly_revenue')->nullable();
            $table->boolean('is_hiring')->default(false);
            $table->boolean('is_raising')->default(false);
            $table->decimal('target_raise', 15, 2)->nullable();
            $table->unsignedBigInteger('views_count')->default(0);
            $table->timestamp('verification_requested_at')->nullable();
            $table->timestamps();

            $table->index('funding_stage');
            $table->index('is_raising');
            $table->index('is_hiring');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('startups');
    }
};
