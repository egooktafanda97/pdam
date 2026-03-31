<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    use HasFactory;

    protected $table = 'pelanggan';

    protected $fillable = [
        'user_id',
        'nomor_pelanggan',
        'nama',
        'alamat',
        'nomor_meter',
        'no_telepon',
        'golongan_tarif_id',
        'petugas_id',
        'foto_ktp',
        'koordinat',
        'is_active',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function petugas()
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }

    public function golonganTarif()
    {
        return $this->belongsTo(GolonganTarif::class);
    }

    public function pemakaianAir()
    {
        return $this->hasMany(PemakaianAir::class);
    }

    public function tagihan()
    {
        return $this->hasMany(Tagihan::class);
    }

    public function pengaduan()
    {
        return $this->hasMany(Pengaduan::class);
    }
}
