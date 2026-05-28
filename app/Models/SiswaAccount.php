<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class SiswaAccount extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'siswa_id',
        'nisn',
        'password',
        'plain_password',
        'last_login_at',
        'last_login_ip',
        'login_count',
    ];

    protected $hidden = ['password', 'remember_token'];

    // JANGAN pakai 'hashed' cast di sini karena password sudah di-Hash::make()
    // secara manual di controller dan import. Kalau pakai cast 'hashed',
    // password akan di-hash dua kali dan attempt() selalu gagal.
    protected function casts(): array
    {
        return [
            'last_login_at' => 'datetime',
        ];
    }

    // ── Paksa Laravel pakai 'nisn' sebagai field login ──────────
    public function getAuthIdentifierName(): string
    {
        return 'nisn';
    }

    // ── Relasi ──────────────────────────────────────────────────
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function momens()
    {
        return $this->hasMany(Momen::class, 'siswa_account_id');
    }
}