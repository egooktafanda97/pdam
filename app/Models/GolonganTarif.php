<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GolonganTarif extends Model
{
    use HasFactory;

    protected $table = 'golongan_tarif';

    protected $fillable = [
        'kode_golongan',
        'nama_golongan',
        'tarif_per_m3',
        'biaya_admin',
    ];

    public function pelanggan()
    {
        return $this->hasMany(Pelanggan::class);
    }
}
