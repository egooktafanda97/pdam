<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Create Permissions
        $permissions = [
            'manage users',
            'manage golongan tarif',
            'manage pelanggan',
            'view pelanggan',
            'manage pemakaian',
            'view pemakaian',
            'manage tagihan',
            'view tagihan',
            'pay tagihan',
            'manage pengaduan',
            'view pengaduan',
            'view laporan',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // 2. Create Roles & Assign Permissions
        $roleAdmin = Role::firstOrCreate(['name' => 'admin']);
        $roleAdmin->givePermissionTo([
            'manage users',
            'manage golongan tarif',
            'manage pelanggan',
            'view pelanggan',
            'manage pemakaian',
            'view pemakaian',
            'manage tagihan',
            'view tagihan',
            'manage pengaduan',
            'view pengaduan',
            'view laporan',
        ]);

        $rolePetugas = Role::firstOrCreate(['name' => 'petugas']);
        $rolePetugas->givePermissionTo([
            'manage pelanggan',
            'view pelanggan',
            'manage pemakaian',
            'view pemakaian',
            'manage tagihan',
            'view tagihan',
            'manage pengaduan',
            'view pengaduan',
        ]);

        $rolePelanggan = Role::firstOrCreate(['name' => 'pelanggan']);
        $rolePelanggan->givePermissionTo([
            'view tagihan',
            'pay tagihan',
            'manage pengaduan',
            'view pengaduan',
        ]);

        $rolePimpinan = Role::firstOrCreate(['name' => 'pimpinan']);
        $rolePimpinan->givePermissionTo([
            'view laporan',
        ]);

        // 3. Create Default Users and Assign roles
        
        // Admin
        $admin = User::firstOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'is_active' => true,
            ]
        );
        $admin->assignRole('admin');

        // Petugas
        $petugas = User::firstOrCreate(
            ['username' => 'petugas'],
            [
                'name' => 'Petugas Lapangan',
                'password' => Hash::make('password'),
                'role' => 'petugas',
                'is_active' => true,
            ]
        );
        $petugas->assignRole('petugas');

        // Pimpinan
        $pimpinan = User::firstOrCreate(
            ['username' => 'pimpinan'],
            [
                'name' => 'Pimpinan PDAM',
                'password' => Hash::make('password'),
                'role' => 'pimpinan',
                'is_active' => true,
            ]
        );
        $pimpinan->assignRole('pimpinan');

        // Pelanggan
        $pelangganUser = User::firstOrCreate(
            ['username' => 'pelanggan1'],
            [
                'name' => 'Budi Pelanggan',
                'password' => Hash::make('password'),
                'role' => 'pelanggan',
                'is_active' => true,
            ]
        );
        $pelangganUser->assignRole('pelanggan');
    }
}
