<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengaduan extends Model
{
    use HasFactory;

    protected $table = 'pengaduan';

    protected $fillable = [
        'pelanggan_id',
        'judul_pengaduan',
        'kategori',
        'deskripsi',
        'tanggal_pengaduan',
        'status',
        'tanggapan',
        'petugas_id',
        'tanggal_tanggapan',
    ];

    protected $casts = [
        'tanggal_pengaduan' => 'datetime',
        'tanggal_tanggapan' => 'datetime',
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }

    public function petugas()
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }
}
