<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // -------------------------------------------------------
        // users
        // -------------------------------------------------------
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        // -------------------------------------------------------
        // password_reset_tokens
        // -------------------------------------------------------
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // -------------------------------------------------------
        // sessions
        // -------------------------------------------------------
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

        // -------------------------------------------------------
        // cache
        // -------------------------------------------------------
        Schema::create('cache', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->mediumText('value');
            $table->integer('expiration')->index();
        });

        Schema::create('cache_locks', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->string('owner');
            $table->integer('expiration')->index();
        });

        // -------------------------------------------------------
        // jobs
        // -------------------------------------------------------
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->string('queue')->index();
            $table->longText('payload');
            $table->unsignedTinyInteger('attempts');
            $table->unsignedInteger('reserved_at')->nullable();
            $table->unsignedInteger('available_at');
            $table->unsignedInteger('created_at');
        });

        Schema::create('job_batches', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name');
            $table->integer('total_jobs');
            $table->integer('pending_jobs');
            $table->integer('failed_jobs');
            $table->longText('failed_job_ids');
            $table->mediumText('options')->nullable();
            $table->integer('cancelled_at')->nullable();
            $table->integer('created_at');
            $table->integer('finished_at')->nullable();
        });

        Schema::create('failed_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->text('connection');
            $table->text('queue');
            $table->longText('payload');
            $table->longText('exception');
            $table->timestamp('failed_at')->useCurrent();
        });

        // -------------------------------------------------------
        // settings
        // -------------------------------------------------------
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // -------------------------------------------------------
        // siswas
        // -------------------------------------------------------
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

        // -------------------------------------------------------
        // siswa_accounts
        // -------------------------------------------------------
        Schema::create('siswa_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswas')->onDelete('cascade');
            $table->string('nisn', 20)->unique();
            $table->string('password');
            $table->string('plain_password')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        // -------------------------------------------------------
        // momen
        // -------------------------------------------------------
        Schema::create('momen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_account_id')->constrained('siswa_accounts')->onDelete('cascade');
            $table->string('foto');
            $table->string('caption', 300)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('momen');
        Schema::dropIfExists('siswa_accounts');
        Schema::dropIfExists('siswas');
        Schema::dropIfExists('settings');
        Schema::dropIfExists('failed_jobs');
        Schema::dropIfExists('job_batches');
        Schema::dropIfExists('jobs');
        Schema::dropIfExists('cache_locks');
        Schema::dropIfExists('cache');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};
