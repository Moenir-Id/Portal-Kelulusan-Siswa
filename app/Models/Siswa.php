<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;

    protected $table = 'siswas';

    protected $fillable = [
        'nisn',
        'nama',
        'kelas',
        'tahun_lulus',
        'nilai_rata',
        'status',
        'catatan',
        'foto_profil',
    ];

    public function getFotoProfilUrlAttribute(): ?string
    {
        if ($this->foto_profil && file_exists(public_path($this->foto_profil))) {
            return asset($this->foto_profil);
        }
        return null;
    }
}