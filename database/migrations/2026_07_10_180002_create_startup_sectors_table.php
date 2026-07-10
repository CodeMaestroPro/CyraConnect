<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('startup_sectors', function (Blueprint $table) {
            $table->foreignId('startup_id')->constrained()->cascadeOnDelete();
            $table->foreignId('sector_id')->constrained()->cascadeOnDelete();

            $table->primary(['startup_id', 'sector_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('startup_sectors');
    }
};
