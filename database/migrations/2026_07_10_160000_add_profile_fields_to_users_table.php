<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->text('bio')->nullable()->after('avatar');
            $table->string('website')->nullable()->after('bio');
            $table->string('linkedin_url')->nullable()->after('website');
            $table->string('twitter_url')->nullable()->after('linkedin_url');
            $table->string('profile_visibility', 20)->default('public')->after('twitter_url');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['bio', 'website', 'linkedin_url', 'twitter_url', 'profile_visibility']);
        });
    }
};
