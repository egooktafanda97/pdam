# Task: Migrasi Template Dashboard Mazer → Morvin

## Fase 1: Persiapan
- [ ] Copy assets Morvin ke path yang benar (`public/admin-template/assets/` → `public/assets/` atau gunakan path `admin-template/assets/`)
- [ ] Backup layout files lama (opsional, karena ada git)
- [ ] Pastikan semua library Morvin tersedia (MetisMenu, SimpleBar, Node-Waves, dll.)

## Fase 2: Migrasi Layout Utama (4 file)
- [ ] **`layouts/app.blade.php`** — Tulis ulang: CSS refs, JS refs, body wrapper structure
- [ ] **`layouts/header.blade.php`** — Tulis ulang: topbar Morvin dengan logo, toggle, user dropdown
- [ ] **`layouts/sidebar.blade.php`** — Tulis ulang: MetisMenu sidebar, user profile section, semua icon, active state logic, pertahankan @hasrole
- [ ] **`layouts/footer.blade.php`** — Update: wrapper + credit text
- [ ] Tes layout: pastikan halaman bisa dimuat tanpa error

## Fase 3: Migrasi Dashboard Views (5 file)
- [ ] **`dashboard.blade.php`** — Breadcrumb format
- [ ] **`dashboard/admin.blade.php`** — Breadcrumb, `iconly-*`→`mdi-*`, `stats-icon`, `font-extrabold`, `py-4-5`, `text-bold-500`, hapus `iconly/bold.css`
- [ ] **`dashboard/petugas.blade.php`** — Breadcrumb, `iconly-*`→`mdi-*`, `stats-icon`, `font-extrabold`, `py-4-5`, hapus `iconly/bold.css`
- [ ] **`dashboard/pelanggan.blade.php`** — Breadcrumb, `bi-*`→`mdi-*` (8 icon)
- [ ] **`dashboard/pimpinan.blade.php`** — Breadcrumb, `iconly-*`→`mdi-*`, `stats-icon`, `bg-light-success`, hapus `iconly/bold.css`
- [ ] Tes: login per role, cek tampilan dashboard

## Fase 4: Migrasi Content Views — Pelanggan & Golongan Tarif (6 file)
- [ ] **`pelanggan/index.blade.php`** — Breadcrumb, icon `bi-*`→`mdi-*`, `text-bold-500`→`fw-bold`, simple-datatables
- [ ] **`pelanggan/create.blade.php`** — Breadcrumb, icon
- [ ] **`pelanggan/edit.blade.php`** — Breadcrumb, icon
- [ ] **`golongan_tarif/index.blade.php`** — Breadcrumb, `bg-light-primary`, simple-datatables
- [ ] **`golongan_tarif/create.blade.php`** — Breadcrumb
- [ ] **`golongan_tarif/edit.blade.php`** — Breadcrumb
- [ ] Tes: CRUD pelanggan & golongan tarif berfungsi

## Fase 5: Migrasi Content Views — Pemakaian, Tagihan, Pembayaran (8 file)
- [ ] **`pemakaian/index.blade.php`** — Breadcrumb, icon, simple-datatables
- [ ] **`pemakaian/create.blade.php`** — Breadcrumb, icon
- [ ] **`tagihan/index.blade.php`** — Breadcrumb, icon, simple-datatables
- [ ] **`tagihan/pelanggan.blade.php`** — Breadcrumb, icon (5 icon)
- [ ] **`pembayaran/create.blade.php`** — Breadcrumb, icon
- [ ] **`pembayaran/online.blade.php`** — Breadcrumb, icon (4 icon)
- [ ] **`pembayaran/riwayat.blade.php`** — Breadcrumb, icon
- [ ] **`pembayaran/cetak.blade.php`** — Cek apakah perlu perubahan (standalone print layout)
- [ ] Tes: alur pemakaian → tagihan → pembayaran

## Fase 6: Migrasi Content Views — Pengaduan, Laporan, Users (10 file)
- [ ] **`pengaduan/index.blade.php`** — Breadcrumb, icon
- [ ] **`pengaduan/create.blade.php`** — Breadcrumb, icon (3 icon)
- [ ] **`pengaduan/show.blade.php`** — Breadcrumb, icon (5 icon), `bg-light-primary`
- [ ] **`pengaduan/riwayat.blade.php`** — Breadcrumb, icon (8 icon), `bg-light-info`
- [ ] **`laporan/pembayaran.blade.php`** — Breadcrumb, simple-datatables
- [ ] **`laporan/pemakaian.blade.php`** — Breadcrumb, simple-datatables
- [ ] **`laporan/pelanggan.blade.php`** — Breadcrumb, simple-datatables
- [ ] **`users/index.blade.php`** — Breadcrumb, icon, simple-datatables
- [ ] **`users/create.blade.php`** — Breadcrumb
- [ ] **`users/edit.blade.php`** — Breadcrumb
- [ ] Tes: pengaduan, laporan, manajemen user

## Fase 7: Migrasi Views Lainnya (3 file)
- [ ] **`profile/edit.blade.php`** — Konversi dari `<x-app-layout>` (Breeze) ke `layouts.app` Morvin (atau biarkan)
- [ ] **`example.blade.php`** — Breadcrumb format
- [ ] **Halaman Auth** (`login`, `register`, `forgot-password`, dll.) — Konversi ke tema Morvin (opsional, tergantung keputusan)
- [ ] Tes: profile edit, login/logout flow

## Fase 8: Testing & Verifikasi Menyeluruh
- [ ] `php artisan test` — pastikan semua test pass
- [ ] Login sebagai **admin** → cek semua halaman
- [ ] Login sebagai **petugas** → cek semua halaman
- [ ] Login sebagai **pelanggan** → cek semua halaman
- [ ] Login sebagai **pimpinan** → cek semua halaman
- [ ] Cek **responsive** (mobile sidebar toggle, layout mobile)
- [ ] Cek **semua modal, form submit, SweetAlert** masih berfungsi
- [ ] Cek **DataTables** (sorting, pagination, search) masih berfungsi

## Fase 9: Cleanup — Hapus Template Mazer Lama
- [ ] Hapus `public/assets/css/app.css` (CSS Mazer)
- [ ] Hapus `public/assets/css/style.css` (CSS Mazer)
- [ ] Hapus `public/assets/js/app.js` (JS Mazer)
- [ ] Hapus `public/assets/js/default.js` (JS Mazer)
- [ ] Hapus `public/assets/vendor/perfect-scrollbar/` (Mazer dependency)
- [ ] Hapus `public/assets/vendor/iconly/` (Mazer icon library)
- [ ] Hapus/archive `public/assets/fonts/` yang sudah tidak dipakai (jika ada font Mazer-specific)
- [ ] Hapus `layouts/app_breeze.blade.php` (jika sudah tidak dipakai)
- [ ] Hapus `layouts/navigation.blade.php` (jika sudah tidak dipakai, Breeze nav)
- [ ] Pindahkan assets Morvin dari `public/admin-template/assets/` ke `public/assets/` (opsional, untuk path yang lebih clean)
- [ ] Update semua path asset di blade files jika dipindahkan
- [ ] Hapus folder `public/admin-template/` (semua HTML demo files)
- [ ] Final test: pastikan semuanya masih berfungsi setelah cleanup
- [ ] Commit final ke git
