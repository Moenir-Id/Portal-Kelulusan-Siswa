<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Untuk siswa
        Schema::table('siswa_accounts', function (Blueprint $table) {
            $table->timestamp('last_login_at')->nullable()->after('remember_token');
            $table->string('last_login_ip', 45)->nullable()->after('last_login_at');
            $table->unsignedSmallInteger('login_count')->default(0)->after('last_login_ip');
        });

        // Untuk admin
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('last_login_at')->nullable()->after('remember_token');
            $table->string('last_login_ip', 45)->nullable()->after('last_login_at');
            $table->unsignedSmallInteger('login_count')->default(0)->after('last_login_ip');
        });
    }

    public function down(): void
    {
        Schema::table('siswa_accounts', function (Blueprint $table) {
            $table->dropColumn(['last_login_at', 'last_login_ip', 'login_count']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['last_login_at', 'last_login_ip', 'login_count']);
        });
    }
};
