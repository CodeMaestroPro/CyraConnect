<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->uuid('uuid')->nullable()->unique()->after('id');
            $table->string('first_name')->nullable()->after('uuid');
            $table->string('last_name')->nullable()->after('first_name');
            $table->string('avatar')->nullable()->after('password');
            $table->string('phone', 20)->nullable()->after('avatar');
            $table->string('timezone', 50)->default('UTC')->after('phone');
            $table->string('locale', 10)->default('en')->after('timezone');
            $table->timestamp('last_login_at')->nullable()->after('remember_token');
            $table->string('last_login_ip', 45)->nullable()->after('last_login_at');
            $table->boolean('is_active')->default(true)->after('last_login_ip');
            $table->timestamp('profile_completed_at')->nullable()->after('is_active');
            $table->softDeletes();
        });

        foreach (DB::table('users')->orderBy('id')->get() as $user) {
            $parts = explode(' ', $user->name, 2);
            DB::table('users')->where('id', $user->id)->update([
                'uuid' => (string) Str::uuid(),
                'first_name' => $parts[0] ?? 'User',
                'last_name' => $parts[1] ?? '',
            ]);
        }

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('name');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('name')->nullable()->after('uuid');
        });

        foreach (DB::table('users')->orderBy('id')->get() as $user) {
            DB::table('users')->where('id', $user->id)->update([
                'name' => trim("{$user->first_name} {$user->last_name}"),
            ]);
        }

        Schema::table('users', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropColumn([
                'uuid', 'first_name', 'last_name', 'avatar', 'phone',
                'timezone', 'locale', 'last_login_at', 'last_login_ip',
                'is_active', 'profile_completed_at', 'name',
            ]);
        });
    }
};
