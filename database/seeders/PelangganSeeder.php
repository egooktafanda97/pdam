<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Pelanggan;
use App\Models\GolonganTarif;

class PelangganSeeder extends Seeder
{
    public function run()
    {
        $gol = GolonganTarif::updateOrCreate(
            ['kode_golongan' => 'R1'],
            [
                'nama_golongan' => 'Rumah Tangga 1',
                'tarif_per_m3' => 3500,
                'biaya_admin' => 2500
            ]
        );

        $petugas = User::where('role', 'petugas')->first();

        $customerData = [
            ['username' => 'pelanggan1', 'name' => 'Budi Pelanggan', 'target' => 'PLG001', 'meter' => 'MTR001'],
            ['username' => 'pelanggan2', 'name' => 'Ani Pelanggan', 'target' => 'PLG002', 'meter' => 'MTR002'],
            ['username' => 'pelanggan3', 'name' => 'Citra Pelanggan', 'target' => 'PLG003', 'meter' => 'MTR003'],
        ];

        foreach ($customerData as $data) {
            $user = User::firstOrCreate(
                ['username' => $data['username']],
                [
                    'name' => $data['name'],
                    'password' => \Illuminate\Support\Facades\Hash::make('password'),
                    'role' => 'pelanggan',
                    'is_active' => true
                ]
            );
            $user->assignRole('pelanggan');

            Pelanggan::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'nomor_pelanggan' => $data['target'],
                    'nama' => $data['name'],
                    'alamat' => 'Alamat ' . $data['name'],
                    'nomor_meter' => $data['meter'],
                    'no_telepon' => '081234' . rand(100, 999),
                    'golongan_tarif_id' => $gol->id,
                    'petugas_id' => $petugas ? $petugas->id : null,
                    'is_active' => true
                ]
            );
        }
    }
}
