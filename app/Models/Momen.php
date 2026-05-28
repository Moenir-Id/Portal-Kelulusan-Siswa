<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Momen extends Model
{
    protected $table = 'momen';

    protected $fillable = ['siswa_account_id', 'foto', 'caption'];

    public function siswaAccount()
    {
        return $this->belongsTo(SiswaAccount::class, 'siswa_account_id');
    }

    // Shortcut ke data siswa
    public function getSiswaAttribute()
    {
        return $this->siswaAccount?->siswa;
    }

    public function getFotoUrlAttribute(): string
    {
        return asset('storage/' . $this->foto);
    }
}
