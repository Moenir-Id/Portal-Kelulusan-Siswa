<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('momen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_account_id')->constrained('siswa_accounts')->onDelete('cascade');
            $table->string('foto'); // path di storage/public/momen/
            $table->string('caption', 300)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('momen');
    }
};
