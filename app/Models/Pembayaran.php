<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayaran';

    protected $fillable = [
        'tagihan_id',
        'tanggal_bayar',
        'jumlah_bayar',
        'metode_bayar',
        'penyedia_layanan',
        'kode_pembayaran',
        'status_pembayaran',
        'referensi_gateway',
        'bukti_pembayaran',
        'petugas_id',
        'expired_at',
    ];

    protected $casts = [
        'tanggal_bayar' => 'datetime',
        'expired_at' => 'datetime',
    ];

    public function tagihan()
    {
        return $this->belongsTo(Tagihan::class);
    }

    public function petugas()
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }
}
