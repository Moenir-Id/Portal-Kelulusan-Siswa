<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('siswa_accounts', function (Blueprint $table) {
            // Simpan plain text password untuk keperluan cetak kartu login
            // Kolom ini nullable — akun lama yang belum diperbarui tidak punya plain_password
            $table->string('plain_password')->nullable()->after('password');
        });
    }

    public function down(): void
    {
        Schema::table('siswa_accounts', function (Blueprint $table) {
            $table->dropColumn('plain_password');
        });
    }
};
