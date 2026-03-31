# Task Checklist: UI Implementation using Mazer

- [x] 1. Controller & Route Setup
  - [x] Generate Controllers (Dashboard, User, Pelanggan, Pemakaian, Tagihan, Pembayaran, Pengaduan, Laporan, Tarif)
  - [x] Define routes in `routes/web.php` for all roles

- [x] 2. Layout & Navigation
  - [x] Update `resources/views/layouts/sidebar.blade.php` to handle dynamic role menus

- [x] 3. Authentication
  - [x] Customize `resources/views/auth/login.blade.php`

- [x] 4. Dashboards
  - [x] Create Admin Dashboard (`dashboard/admin.blade.php`)
  - [x] Create Petugas Dashboard (`dashboard/petugas.blade.php`)
  - [x] Create Pelanggan Dashboard (`dashboard/pelanggan.blade.php`)
  - [x] Create Pimpinan Dashboard (`dashboard/pimpinan.blade.php`)

- [x] 5. Manajemen User (Admin)
  - [x] Index, Create, Edit views

- [x] 6. Data Pelanggan (Admin & Petugas)
  - [x] Index, Create, Edit views

- [x] 7. Pemakaian Air (Admin & Petugas)
  - [x] Index, Create views

- [x] 8. Tagihan & Pembayaran
  - [x] Admin/Petugas: Daftar tagihan & Form bayar loket (cetak struk)
  - [x] Pelanggan: Tagihan saya, Pembayaran online (VA/QRIS), Riwayat bayar

- [x] 9. Pengaduan
  - [x] Admin/Petugas: Daftar aduan & Respon
  - [x] Pelanggan: Kirim aduan & Riwayat

- [x] 10. Laporan (Admin & Pimpinan)
  - [x] Laporan Pembayaran, Pelanggan, Pemakaian Air

- [x] 11. Golongan Tarif (Admin)
  - [x] Index, Create, Edit views

- [x] 12. Setup Role & Permissions (Spatie)
  - [x] Lengkapi sidebar untuk masing-masing akses user
  - [x] Buat & jalankan `RolePermissionSeeder`
