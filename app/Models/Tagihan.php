<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tagihan extends Model
{
    use HasFactory;

    protected $table = 'tagihan';

    protected $fillable = [
        'pelanggan_id',
        'pemakaian_air_id',
        'periode_bulan',
        'periode_tahun',
        'jumlah_meter',
        'biaya_pemakaian',
        'biaya_admin',
        'total_tagihan',
        'tanggal_jatuh_tempo',
        'status',
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }

    public function pemakaianAir()
    {
        return $this->belongsTo(PemakaianAir::class);
    }

    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class);
    }
}
