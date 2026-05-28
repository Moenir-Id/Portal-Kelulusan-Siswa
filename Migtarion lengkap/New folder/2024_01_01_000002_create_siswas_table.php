<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('siswas', function (Blueprint $table) {
            $table->id();
            $table->string('nisn', 20)->unique();
            $table->string('nama', 100);
            $table->string('kelas', 20);
            $table->year('tahun_lulus');
            $table->decimal('nilai_rata', 5, 2)->nullable();
            $table->enum('status', ['LULUS', 'TIDAK LULUS', 'LULUS BERSYARAT']);
            $table->text('catatan')->nullable();
            $table->string('foto_profil')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('siswas');
    }
};
