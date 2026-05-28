<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE siswas MODIFY COLUMN status ENUM('LULUS', 'TIDAK LULUS', 'LULUS BERSYARAT') NOT NULL");
    }

    public function down(): void
    {
        // Hapus dulu data LULUS BERSYARAT sebelum rollback, atau akan error
        DB::statement("UPDATE siswas SET status = 'TIDAK LULUS' WHERE status = 'LULUS BERSYARAT'");
        DB::statement("ALTER TABLE siswas MODIFY COLUMN status ENUM('LULUS', 'TIDAK LULUS') NOT NULL");
    }
};