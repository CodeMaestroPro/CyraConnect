<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('startup_milestones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('startup_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('achieved_at');
            $table->timestamps();

            $table->index(['startup_id', 'achieved_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('startup_milestones');
    }
};
