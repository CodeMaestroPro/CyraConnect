<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('investor_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('investor_type')->nullable();
            $table->string('firm_name')->nullable();
            $table->string('title')->nullable();
            $table->text('investment_thesis')->nullable();
            $table->decimal('min_check_size', 15, 2)->nullable();
            $table->decimal('max_check_size', 15, 2)->nullable();
            $table->json('preferred_stages')->nullable();
            $table->json('preferred_sectors')->nullable();
            $table->boolean('is_actively_investing')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('investor_profiles');
    }
};
