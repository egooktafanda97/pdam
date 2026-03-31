<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PemakaianAir extends Model
{
    use HasFactory;

    protected $table = 'pemakaian_air';

    protected $fillable = [
        'pelanggan_id',
        'periode_bulan',
        'periode_tahun',
        'meter_awal',
        'meter_akhir',
        'total_pemakaian',
        'petugas_id',
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }

    public function petugas()
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }

    public function tagihan()
    {
        return $this->hasOne(Tagihan::class);
    }
}
