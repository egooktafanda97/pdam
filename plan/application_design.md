# Rancangan Aplikasi (Application Design)
## Sistem Informasi Pembayaran dan Pelayanan PDAM Berbasis Web

Dokumen ini berisi instruksi lengkap rancangan UI dan alur aplikasi dari proses login hingga seluruh modul sistem. Digunakan sebagai panduan implementasi menggunakan Laravel (Blade + Bootstrap 5).

---

## 1. Struktur Navigasi & Layout

### 1.1 Layout Utama (`layouts/app.blade.php`)
```
┌──────────────────────────────────────────────────────┐
│  NAVBAR (Top Bar)                                    │
│  ┌──────┐  PDAM Tirta Bening   [Notif🔔] [User ▼]  │
│  │ Logo │                                Dropdown:   │
│  └──────┘                                - Profil    │
│                                          - Logout    │
├────────────┬─────────────────────────────────────────┤
│  SIDEBAR   │  KONTEN UTAMA                           │
│            │                                         │
│  Menu      │  ┌─────────────────────────────────┐    │
│  sesuai    │  │  Breadcrumb: Home > Module      │    │
│  role      │  ├─────────────────────────────────┤    │
│  pengguna  │  │                                 │    │
│            │  │  [Isi Halaman Dinamis]           │    │
│            │  │                                 │    │
│            │  │                                 │    │
│            │  └─────────────────────────────────┘    │
├────────────┴─────────────────────────────────────────┤
│  FOOTER: © 2025 PDAM Tirta Bening. All rights.      │
└──────────────────────────────────────────────────────┘
```

### 1.2 Sidebar Menu Per Role

#### Admin
```
📊 Dashboard
👥 Manajemen User
🏠 Data Pelanggan
💧 Pemakaian Air
💰 Tagihan & Pembayaran
📢 Pengaduan
📄 Laporan
  ├─ Laporan Pembayaran
  ├─ Laporan Pelanggan
  └─ Laporan Pemakaian Air
```

#### Petugas
```
📊 Dashboard
🏠 Data Pelanggan
💧 Input Pemakaian Air
💰 Pembayaran
📢 Pengaduan
```

#### Pelanggan
```
📊 Dashboard
💧 Pemakaian Air Saya
💰 Tagihan Saya
📜 Riwayat Pembayaran
📢 Pengaduan Saya
```

#### Pimpinan
```
📊 Dashboard
📄 Laporan
  ├─ Laporan Pembayaran
  ├─ Laporan Pelanggan
  └─ Laporan Pemakaian Air
```

---

## 2. Halaman Login (Guest)

**Route:** `GET /login`
**Blade:** `auth/login.blade.php`

```
┌──────────────────────────────────────────────┐
│           (Background: Gradient Biru)        │
│                                              │
│        ┌────────────────────────────┐        │
│        │     ┌──────────┐          │        │
│        │     │   LOGO   │          │        │
│        │     │   PDAM   │          │        │
│        │     └──────────┘          │        │
│        │                           │        │
│        │  Sistem Informasi PDAM    │        │
│        │                           │        │
│        │  ┌──────────────────────┐ │        │
│        │  │ 👤 Username          │ │        │
│        │  └──────────────────────┘ │        │
│        │  ┌──────────────────────┐ │        │
│        │  │ 🔒 Password          │ │        │
│        │  └──────────────────────┘ │        │
│        │                           │        │
│        │  [✅ Ingat Saya]          │        │
│        │                           │        │
│        │  ┌──────────────────────┐ │        │
│        │  │    🔑 MASUK          │ │        │
│        │  └──────────────────────┘ │        │
│        │                           │        │
│        └────────────────────────────┘        │
│                                              │
│  © 2025 PDAM Tirta Bening                    │
└──────────────────────────────────────────────┘
```

**Instruksi:**
- Card putih di tengah layar, background gradient biru
- Validasi: username & password wajib diisi
- Setelah login, redirect sesuai role:
  - `admin` → `/admin/dashboard`
  - `petugas` → `/petugas/dashboard`
  - `pelanggan` → `/pelanggan/dashboard`
  - `pimpinan` → `/pimpinan/dashboard`
- Tampikan pesan error jika kredensial salah

---

## 3. Dashboard

### 3.1 Dashboard Admin
**Route:** `GET /admin/dashboard`

```
┌─────────────────────────────────────────────────────┐
│  Selamat Datang, [Nama Admin]!                      │
├─────────────────────────────────────────────────────┤
│                                                     │
│  ┌──────────┐ ┌──────────┐ ┌──────────┐ ┌────────┐ │
│  │ 👥 120   │ │ 💧 95    │ │ 💰 80    │ │ 📢 5   │ │
│  │ Total    │ │ Pemakaian│ │ Sudah    │ │ Aduan  │ │
│  │ Pelanggan│ │ Bulan Ini│ │ Bayar    │ │ Baru   │ │
│  └──────────┘ └──────────┘ └──────────┘ └────────┘ │
│                                                     │
│  ┌──────────────────────┐ ┌────────────────────┐    │
│  │ 📊 Grafik Pendapatan │ │ 📋 Tagihan Terbaru │    │
│  │ (Bar Chart bulanan)  │ │                    │    │
│  │                      │ │ - Pelanggan A: 50k │    │
│  │                      │ │ - Pelanggan B: 75k │    │
│  │                      │ │ - Pelanggan C: 60k │    │
│  └──────────────────────┘ └────────────────────┘    │
│                                                     │
│  ┌──────────────────────┐ ┌────────────────────┐    │
│  │ 📢 Pengaduan Terbaru │ │ 💰 Pembayaran      │    │
│  │                      │ │    Terakhir         │    │
│  │ - Aduan #12: Air..   │ │ - Trx #45: Rp 80k  │    │
│  │ - Aduan #11: Pipa..  │ │ - Trx #44: Rp 55k  │    │
│  └──────────────────────┘ └────────────────────┘    │
└─────────────────────────────────────────────────────┘
```

**Instruksi:**
- 4 kartu statistik di atas (Total Pelanggan, Pemakaian Bulan Ini, Sudah Bayar, Pengaduan Baru)
- Grafik pendapatan bulanan (gunakan Chart.js / ApexCharts)
- Tabel ringkas: tagihan terbaru, pengaduan terbaru, pembayaran terakhir

### 3.2 Dashboard Petugas
**Route:** `GET /petugas/dashboard`

Sama seperti admin tetapi tanpa menu Manajemen User dan Laporan. Kartu statistik hanya menampilkan:
- Total Pelanggan Aktif
- Pemakaian Belum Diinput Bulan Ini
- Tagihan Belum Lunas
- Pengaduan Baru

### 3.3 Dashboard Pelanggan
**Route:** `GET /pelanggan/dashboard`

```
┌─────────────────────────────────────────────────────┐
│  Selamat Datang, [Nama Pelanggan]!                  │
│  No. Pelanggan: [PLG-001]                           │
├─────────────────────────────────────────────────────┤
│                                                     │
│  ┌─────────────────────┐  ┌────────────────────┐    │
│  │ 💰 TAGIHAN AKTIF    │  │ 💧 PEMAKAIAN       │    │
│  │                     │  │    BULAN INI        │    │
│  │ Periode: Mar 2025   │  │                     │    │
│  │ Pemakaian: 15 m³    │  │ Meter Awal:  120    │    │
│  │ Total: Rp 75.000    │  │ Meter Akhir: 135    │    │
│  │ Status: BELUM BAYAR │  │ Total: 15 m³        │    │
│  │                     │  │                     │    │
│  │ [💳 Bayar Sekarang] │  │                     │    │
│  └─────────────────────┘  └────────────────────┘    │
│                                                     │
│  ┌──────────────────────────────────────────────┐   │
│  │ 📜 Riwayat Pembayaran Terakhir               │   │
│  │ ┌────────┬──────────┬──────────┬───────────┐ │   │
│  │ │ Periode│ Total    │ Tgl Bayar│ Status    │ │   │
│  │ ├────────┼──────────┼──────────┼───────────┤ │   │
│  │ │ Feb 25 │ Rp 65.000│ 15/02/25 │ ✅ Lunas  │ │   │
│  │ │ Jan 25 │ Rp 70.000│ 12/01/25 │ ✅ Lunas  │ │   │
│  │ └────────┴──────────┴──────────┴───────────┘ │   │
│  └──────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────┘
```

### 3.4 Dashboard Pimpinan
**Route:** `GET /pimpinan/dashboard`

Hanya menampilkan kartu statistik ringkasan dan grafik pendapatan. Tidak ada aksi input data.

---

## 4. Modul Manajemen User (Admin Only)

### 4.1 Daftar User
**Route:** `GET /admin/users`

```
┌─────────────────────────────────────────────────────┐
│  Manajemen User                    [+ Tambah User]  │
├─────────────────────────────────────────────────────┤
│  Cari: [________________🔍]   Filter Role: [Semua▼] │
├──────┬──────────┬───────────┬────────┬──────┬───────┤
│  No  │ Username │ Nama      │ Role   │Status│ Aksi  │
├──────┼──────────┼───────────┼────────┼──────┼───────┤
│  1   │ admin1   │ Budi S.   │ Admin  │ ✅   │ ✏️🗑️ │
│  2   │ petugas1 │ Sari M.   │ Petugas│ ✅   │ ✏️🗑️ │
│  3   │ plg001   │ Andi P.   │ Plgn   │ ✅   │ ✏️🗑️ │
├──────┴──────────┴───────────┴────────┴──────┴───────┤
│  Menampilkan 1-10 dari 50       [< 1 2 3 4 5 >]    │
└─────────────────────────────────────────────────────┘
```

### 4.2 Form Tambah / Edit User
**Route:** `GET /admin/users/create` | `GET /admin/users/{id}/edit`

```
┌─────────────────────────────────────────┐
│  Tambah User Baru                       │
├─────────────────────────────────────────┤
│  Username    : [________________]       │
│  Nama Lengkap: [________________]       │
│  Password    : [________________]       │
│  Role        : [Pilih Role      ▼]     │
│               (admin/petugas/           │
│                pelanggan/pimpinan)       │
│  Status      : [✅ Aktif]              │
│                                         │
│  [💾 Simpan]  [← Batal]                │
└─────────────────────────────────────────┘
```

**Instruksi:**
- Validasi: username unik, password min 6 karakter
- Saat edit, password boleh kosong (tidak diubah)

---

## 5. Modul Data Pelanggan (Admin & Petugas)

### 5.1 Daftar Pelanggan
**Route:** `GET /admin/pelanggan`

```
┌─────────────────────────────────────────────────────────────┐
│  Data Pelanggan                      [+ Tambah Pelanggan]   │
├─────────────────────────────────────────────────────────────┤
│  Cari: [________________🔍]   Golongan: [Semua ▼]           │
├────┬──────────┬──────────┬─────────────┬────────┬──────┬────┤
│ No │ No. Plgn │ Nama     │ Alamat      │ Gol.   │Status│Aksi│
├────┼──────────┼──────────┼─────────────┼────────┼──────┼────┤
│ 1  │ PLG-001  │ Andi P   │ Jl. Melati  │ R1     │ ✅   │✏️🗑️│
│ 2  │ PLG-002  │ Budi S   │ Jl. Mawar   │ R2     │ ✅   │✏️🗑️│
│ 3  │ PLG-003  │ Citra R  │ Jl. Kenanga │ Niaga  │ ❌   │✏️🗑️│
├────┴──────────┴──────────┴─────────────┴────────┴──────┴────┤
│  Menampilkan 1-10 dari 120      [< 1 2 3 ... 12 >]         │
└─────────────────────────────────────────────────────────────┘
```

### 5.2 Form Tambah / Edit Pelanggan
**Route:** `GET /admin/pelanggan/create` | `GET /admin/pelanggan/{id}/edit`

```
┌─────────────────────────────────────────┐
│  Tambah Pelanggan Baru                  │
├─────────────────────────────────────────┤
│  No. Pelanggan  : [PLG-___] (auto)      │
│  Nama Lengkap   : [________________]    │
│  Alamat         : [________________]    │
│                   [________________]    │
│  No. Meter      : [________________]    │
│  No. Telepon    : [________________]    │
│  Golongan Tarif : [Pilih Golongan  ▼]   │
│  Foto KTP       : [📷 Upload File  ]    │
│  Koordinat      : [________________]    │
│  Status         : [✅ Aktif]            │
│                                         │
│  Buat Akun Login Pelanggan?             │
│  [✅ Ya] → Username & Password auto     │
│                                         │
│  [💾 Simpan]  [← Batal]                │
└─────────────────────────────────────────┘
```

**Instruksi:**
- Nomor pelanggan otomatis di-generate (format: PLG-XXX)
- Dropdown golongan tarif diambil dari tabel `golongan_tarif`
- Jika "Buat Akun Login" dicentang, sistem otomatis membuat user dengan role `pelanggan`
- Upload foto KTP disimpan di `storage/app/public/ktp/`

---

## 6. Modul Pemakaian Air

### 6.1 Daftar Pemakaian Air (Admin & Petugas)
**Route:** `GET /petugas/pemakaian`

```
┌───────────────────────────────────────────────────────────────┐
│  Data Pemakaian Air                   [+ Input Pemakaian]     │
├───────────────────────────────────────────────────────────────┤
│  Periode: [Maret ▼] [2025 ▼]   Cari Pelanggan: [________🔍] │
├────┬──────────┬──────────┬───────┬───────┬────────┬──────────┤
│ No │ No. Plgn │ Nama     │ M.Awal│M.Akhir│ Pakai  │ Petugas  │
├────┼──────────┼──────────┼───────┼───────┼────────┼──────────┤
│ 1  │ PLG-001  │ Andi P   │ 120   │ 135   │ 15 m³  │ Sari M.  │
│ 2  │ PLG-002  │ Budi S   │ 200   │ 225   │ 25 m³  │ Sari M.  │
│ 3  │ PLG-003  │ Citra R  │ -     │ -     │ Belum  │ -        │
├────┴──────────┴──────────┴───────┴───────┴────────┴──────────┤
│  Menampilkan 1-10 dari 120      [< 1 2 3 ... 12 >]           │
└───────────────────────────────────────────────────────────────┘
```

### 6.2 Form Input Pemakaian Air
**Route:** `GET /petugas/pemakaian/create`

```
┌───────────────────────────────────────────┐
│  Input Pemakaian Air                      │
├───────────────────────────────────────────┤
│  Pelanggan   : [🔍 Cari Pelanggan...  ▼] │
│                                           │
│  ── Info Pelanggan (auto-fill) ──         │
│  Nama        : Andi Pratama               │
│  No. Meter   : MTR-00123                  │
│  Golongan    : R1 (Rumah Tangga 1)        │
│                                           │
│  Periode     : [Maret ▼] [2025 ▼]        │
│  Meter Awal  : [120     ] (auto/manual)   │
│  Meter Akhir : [________]                 │
│                                           │
│  ── Kalkulasi Otomatis ──                 │
│  Total Pemakaian : 15 m³                  │
│  Tarif/m³        : Rp 3.500              │
│  Biaya Pemakaian : Rp 52.500             │
│  Biaya Admin     : Rp 5.000             │
│  TOTAL TAGIHAN   : Rp 57.500            │
│                                           │
│  [💾 Simpan & Buat Tagihan]  [← Batal]   │
└───────────────────────────────────────────┘
```

**Instruksi:**
- Saat pelanggan dipilih, meter awal otomatis terisi dari `meter_akhir` bulan sebelumnya
- Kalkulasi real-time saat `meter_akhir` diisi (JavaScript)
- Saat disimpan, otomatis membuat record di tabel `pemakaian_air` DAN `tagihan`
- Validasi: `meter_akhir` >= `meter_awal`

---

## 7. Modul Tagihan & Pembayaran

### 7.1 Daftar Tagihan (Admin & Petugas)
**Route:** `GET /petugas/tagihan`

```
┌────────────────────────────────────────────────────────────────┐
│  Daftar Tagihan                                                │
├────────────────────────────────────────────────────────────────┤
│  Periode: [Semua ▼]  Status: [Semua ▼]  Cari: [__________🔍] │
├────┬──────────┬────────┬────────┬────────────┬────────┬───────┤
│ No │ No. Plgn │ Nama   │ Periode│ Total (Rp) │ Status │ Aksi  │
├────┼──────────┼────────┼────────┼────────────┼────────┼───────┤
│ 1  │ PLG-001  │ Andi P │ Mar-25 │ 57.500     │🔴Belum │[Bayar]│
│ 2  │ PLG-002  │ Budi S │ Mar-25 │ 92.500     │🔴Belum │[Bayar]│
│ 3  │ PLG-003  │ Citra  │ Feb-25 │ 45.000     │🟢Lunas │[Cetak]│
├────┴──────────┴────────┴────────┴────────────┴────────┴───────┤
│  Menampilkan 1-10 dari 200      [< 1 2 3 ... 20 >]            │
└────────────────────────────────────────────────────────────────┘
```

### 7.2 Form Pembayaran (Petugas - Loket)
**Route:** `GET /petugas/pembayaran/{tagihan_id}`

```
┌───────────────────────────────────────────┐
│  Proses Pembayaran Tagihan                │
├───────────────────────────────────────────┤
│  ── Detail Tagihan ──                     │
│  No. Pelanggan : PLG-001                  │
│  Nama          : Andi Pratama             │
│  Periode       : Maret 2025              │
│  Pemakaian     : 15 m³                   │
│  Biaya Pakai   : Rp 52.500              │
│  Biaya Admin   : Rp  5.000              │
│  ─────────────────────────                │
│  TOTAL TAGIHAN : Rp 57.500              │
│                                           │
│  ── Pembayaran ──                         │
│  Jumlah Bayar  : [________]              │
│  Metode        : [Tunai           ▼]     │
│                                           │
│  [✅ Konfirmasi Pembayaran]  [← Batal]   │
└───────────────────────────────────────────┘
```

**Instruksi:**
- Setelah konfirmasi:
  1. Buat record di tabel `pembayaran` (status_pembayaran = 'Sukses')
  2. Update `tagihan.status` menjadi 'Lunas'
  3. Generate nomor bukti pembayaran otomatis
  4. Redirect ke halaman cetak bukti pembayaran

### 7.3 Cetak Bukti Pembayaran
**Route:** `GET /petugas/pembayaran/{id}/cetak`

```
┌──────────────────────────────────────────┐
│        BUKTI PEMBAYARAN PDAM             │
│        Tirta Bening                      │
│  ──────────────────────────────────      │
│  No. Bukti    : PAY-2025-0001            │
│  Tanggal      : 11 Maret 2025           │
│  ──────────────────────────────────      │
│  No. Pelanggan: PLG-001                  │
│  Nama         : Andi Pratama             │
│  Alamat       : Jl. Melati No. 10        │
│  Golongan     : R1                       │
│  ──────────────────────────────────      │
│  Periode      : Maret 2025              │
│  Pemakaian    : 15 m³                   │
│  Biaya Pakai  : Rp  52.500              │
│  Biaya Admin  : Rp   5.000              │
│  ──────────────────────────────────      │
│  TOTAL BAYAR  : Rp  57.500              │
│  Metode       : Tunai                    │
│  Status       : ✅ LUNAS                 │
│  ──────────────────────────────────      │
│  Petugas      : Sari Mutiara             │
│                                          │
│  [🖨️ Cetak]  [📥 Download PDF]          │
└──────────────────────────────────────────┘
```

### 7.4 Tagihan Saya (Pelanggan)
**Route:** `GET /pelanggan/tagihan`

```
┌────────────────────────────────────────────────────────────────┐
│  Tagihan Saya                                                  │
├────────────────────────────────────────────────────────────────┤
│  Filter: [Semua ▼]                                             │
├────┬────────┬────────────┬────────────┬────────────┬──────────┤
│ No │ Periode│ Pemakaian  │ Total (Rp) │ Status     │ Aksi     │
├────┼────────┼────────────┼────────────┼────────────┼──────────┤
│ 1  │ Mar-25 │ 15 m³      │ 57.500     │🔴Belum     │[💳 Bayar]│
│ 2  │ Feb-25 │ 12 m³      │ 47.000     │🟢Lunas     │[📄 Bukti]│
│ 3  │ Jan-25 │ 18 m³      │ 68.000     │🟢Lunas     │[📄 Bukti]│
├────┴────────┴────────────┴────────────┴────────────┴──────────┤
│  Menampilkan 1-10 dari 24       [< 1 2 3 >]                   │
└────────────────────────────────────────────────────────────────┘
```

**Instruksi:**
- Hanya menampilkan tagihan milik pelanggan yang login
- Tombol **[💳 Bayar]** muncul hanya pada tagihan berstatus "Belum Bayar"
- Tombol **[📄 Bukti]** muncul pada tagihan berstatus "Lunas"
- Klik "Bayar" menuju halaman pembayaran online (7.5)

### 7.5 Pembayaran Online — Payment Gateway (Pelanggan)
**Route:** `GET /pelanggan/bayar/{tagihan_id}`

```
┌─────────────────────────────────────────────────┐
│  Pembayaran Online                              │
├─────────────────────────────────────────────────┤
│  ── Detail Tagihan ──                           │
│  No. Pelanggan : PLG-001                        │
│  Nama          : Andi Pratama                   │
│  Periode       : Maret 2025                     │
│  Total Tagihan : Rp 57.500                      │
│  ─────────────────────────────                  │
│                                                 │
│  ── Pilih Metode Pembayaran ──                  │
│                                                 │
│  ┌──────────────────┐  ┌──────────────────┐     │
│  │  🏦 Virtual      │  │  📱 QRIS         │     │
│  │     Account (VA) │  │                  │     │
│  │  ○ BCA VA        │  │  Scan untuk      │     │
│  │  ○ Mandiri VA    │  │  bayar via       │     │
│  │  ○ BNI VA        │  │  e-wallet /      │     │
│  │  ○ BRI VA        │  │  mobile banking  │     │
│  │                  │  │                  │     │
│  └──────────────────┘  └──────────────────┘     │
│                                                 │
│  [✅ Bayar Sekarang]  [← Kembali]              │
└─────────────────────────────────────────────────┘
```

**Setelah klik "Bayar Sekarang" → Tampilkan kode pembayaran:**

```
┌─────────────────────────────────────────────────┐
│  Instruksi Pembayaran                           │
├─────────────────────────────────────────────────┤
│                                                 │
│  ── Jika VA ──                                  │
│  ┌─────────────────────────────────────┐        │
│  │  🏦 BCA Virtual Account             │        │
│  │                                     │        │
│  │  Nomor VA: 1234 5678 9012 3456      │        │
│  │                     [📋 Salin]      │        │
│  │                                     │        │
│  │  Total Bayar : Rp 57.500            │        │
│  │  Batas Waktu : 11 Mar 2025, 23:59   │        │
│  │  Status      : ⏳ Menunggu Bayar    │        │
│  └─────────────────────────────────────┘        │
│                                                 │
│  ── Jika QRIS ──                                │
│  ┌─────────────────────────────────────┐        │
│  │  📱 QRIS                            │        │
│  │  ┌───────────────┐                  │        │
│  │  │               │                  │        │
│  │  │   [QR CODE]   │                  │        │
│  │  │               │                  │        │
│  │  └───────────────┘                  │        │
│  │  Total Bayar : Rp 57.500            │        │
│  │  Batas Waktu : 11 Mar 2025, 23:59   │        │
│  │  Status      : ⏳ Menunggu Bayar    │        │
│  └─────────────────────────────────────┘        │
│                                                 │
│  Petunjuk Pembayaran:                           │
│  1. Buka aplikasi m-banking / e-wallet          │
│  2. Pilih Transfer VA / Scan QRIS               │
│  3. Masukkan nomor VA atau scan QR di atas      │
│  4. Konfirmasi dan selesaikan pembayaran        │
│  5. Status otomatis berubah setelah terbayar    │
│                                                 │
│  [🔄 Cek Status Bayar]  [← Kembali ke Tagihan] │
└─────────────────────────────────────────────────┘
```

**Instruksi Backend:**
- Saat pelanggan klik "Bayar Sekarang":
  1. Buat record di `pembayaran` (status = 'Pending', metode_bayar = 'VA'/'QRIS')
  2. Update `tagihan.status` ke 'Pending'
  3. Kirim request ke Payment Gateway API → terima `kode_pembayaran` (nomor VA / QRIS URL)
  4. Simpan `kode_pembayaran` dan `referensi_gateway` ke tabel `pembayaran`
  5. Tampilkan halaman instruksi pembayaran
- **Webhook Callback** (`POST /api/payment/callback`):
  1. Terima notifikasi dari payment gateway
  2. Verifikasi signature/token
  3. Update `pembayaran.status_pembayaran` → 'Sukses'
  4. Update `tagihan.status` → 'Lunas'
- **Cek Status** (polling atau auto-refresh):
  1. Pelanggan klik "Cek Status Bayar" → query status terbaru
  2. Jika sudah 'Sukses', redirect ke halaman bukti pembayaran

### 7.6 Riwayat Pembayaran Saya (Pelanggan)
**Route:** `GET /pelanggan/riwayat`

```
┌──────────────────────────────────────────────────────────────┐
│  Riwayat Pembayaran Saya                                     │
├──────────────────────────────────────────────────────────────┤
│  Tahun: [2025 ▼]                                             │
├────┬────────┬────────────┬──────────┬────────┬───────┬───────┤
│ No │ Periode│ Total (Rp) │ Tgl Bayar│ Metode │ Status│ Aksi  │
├────┼────────┼────────────┼──────────┼────────┼───────┼───────┤
│ 1  │ Mar-25 │ 57.500     │ 11/03/25 │ 🏦 VA  │✅Sukses│[📄]  │
│ 2  │ Feb-25 │ 47.000     │ 10/02/25 │ 💵Tunai│✅Sukses│[📄]  │
│ 3  │ Jan-25 │ 68.000     │ 08/01/25 │ 📱QRIS │✅Sukses│[📄]  │
│ 4  │ Des-24 │ 55.000     │    -     │   -    │⏳Pndng│  -   │
├────┴────────┴────────────┴──────────┴────────┴───────┴───────┤
│  Menampilkan 1-10 dari 12       [< 1 2 >]                    │
└──────────────────────────────────────────────────────────────┘
```

**Instruksi:**
- Tampilkan seluruh riwayat pembayaran milik pelanggan yang login
- Filter per tahun
- Kolom metode menampilkan ikon sesuai: Tunai 💵, VA 🏦, QRIS 📱
- Tombol [📄] membuka bukti pembayaran (popup/PDF)
- Status: Sukses ✅, Pending ⏳, Gagal ❌, Kedaluwarsa ⏰

---

## 8. Modul Pengaduan

### 8.1 Daftar Pengaduan (Admin & Petugas)
**Route:** `GET /admin/pengaduan`

```
┌──────────────────────────────────────────────────────────────────┐
│  Daftar Pengaduan                                                │
├──────────────────────────────────────────────────────────────────┤
│  Status: [Semua ▼]  Kategori: [Semua ▼]  Cari: [__________🔍]  │
├────┬──────────┬──────────────────┬──────────┬────────┬──────────┤
│ No │ Pelanggan│ Judul            │ Kategori │ Status │ Aksi     │
├────┼──────────┼──────────────────┼──────────┼────────┼──────────┤
│ 1  │ Andi P   │ Air keruh        │ Teknis   │🔴 Baru │[Respon]  │
│ 2  │ Budi S   │ Meteran rusak    │ Teknis   │🟡Proses│[Detail]  │
│ 3  │ Citra R  │ Tagihan salah    │ Tagihan  │🟢Selesai│[Detail] │
├────┴──────────┴──────────────────┴──────────┴────────┴──────────┤
│  Menampilkan 1-10 dari 30       [< 1 2 3 >]                     │
└──────────────────────────────────────────────────────────────────┘
```

### 8.2 Detail & Respon Pengaduan (Admin/Petugas)
**Route:** `GET /admin/pengaduan/{id}`

```
┌───────────────────────────────────────────┐
│  Detail Pengaduan #1                      │
├───────────────────────────────────────────┤
│  Pelanggan  : Andi Pratama (PLG-001)      │
│  Judul      : Air keruh sejak 3 hari      │
│  Kategori   : Teknis                      │
│  Tanggal    : 10 Maret 2025              │
│  Status     : 🔴 Baru                     │
│  ─────────────────────────────────        │
│  Isi Pengaduan:                           │
│  "Air dari keran saya berwarna keruh      │
│   kecoklatan sejak 3 hari yang lalu.      │
│   Mohon segera ditangani."                │
│  ─────────────────────────────────        │
│                                           │
│  ── Tanggapan Petugas ──                  │
│  Status Baru : [Diproses       ▼]        │
│  Tanggapan   : [____________________]    │
│               [____________________]     │
│                                           │
│  [💬 Kirim Tanggapan]  [← Kembali]       │
└───────────────────────────────────────────┘
```

### 8.3 Kirim Pengaduan (Pelanggan)
**Route:** `GET /pelanggan/pengaduan/create`

```
┌───────────────────────────────────────────┐
│  Kirim Pengaduan Baru                     │
├───────────────────────────────────────────┤
│  Judul        : [____________________]    │
│  Kategori     : [Pilih Kategori    ▼]     │
│                 (Teknis/Tagihan/Layanan)   │
│  Deskripsi    : [____________________]    │
│                [____________________]     │
│                [____________________]     │
│                                           │
│  [📨 Kirim Pengaduan]  [← Batal]         │
└───────────────────────────────────────────┘
```

### 8.4 Riwayat Pengaduan Saya (Pelanggan)
**Route:** `GET /pelanggan/pengaduan`

Daftar pengaduan milik pelanggan. Bisa klik untuk melihat status dan tanggapan petugas.

---

## 9. Modul Laporan (Admin & Pimpinan)

### 9.1 Laporan Pembayaran
**Route:** `GET /admin/laporan/pembayaran`

```
┌──────────────────────────────────────────────────────────┐
│  Laporan Pembayaran                                      │
├──────────────────────────────────────────────────────────┤
│  Dari: [01/03/2025]  Sampai: [31/03/2025]  [🔍 Filter]  │
│                                                          │
│  [📥 Export PDF]  [📥 Export Excel]                       │
├────┬──────────┬──────────┬──────────┬──────────┬────────┤
│ No │ No. Bukti│ Pelanggan│ Tgl Bayar│ Jumlah   │ Metode │
├────┼──────────┼──────────┼──────────┼──────────┼────────┤
│ 1  │ PAY-0001 │ Andi P   │ 05/03/25 │ 57.500   │ Tunai  │
│ 2  │ PAY-0002 │ Budi S   │ 07/03/25 │ 92.500   │ Tunai  │
├────┴──────────┴──────────┴──────────┴──────────┴────────┤
│                           TOTAL    : Rp 150.000          │
│  Menampilkan 1-10 dari 80       [< 1 2 3 ... 8 >]       │
└──────────────────────────────────────────────────────────┘
```

### 9.2 Laporan Pelanggan
**Route:** `GET /admin/laporan/pelanggan`

Daftar seluruh pelanggan aktif/nonaktif dengan filter golongan dan status. Bisa ekspor ke PDF/Excel.

### 9.3 Laporan Pemakaian Air
**Route:** `GET /admin/laporan/pemakaian`

Rekap pemakaian air per pelanggan per periode. Bisa ekspor ke PDF/Excel.

---

## 10. Modul Golongan Tarif (Admin Only)

### 10.1 Daftar Golongan Tarif
**Route:** `GET /admin/golongan-tarif`

```
┌──────────────────────────────────────────────────────────────┐
│  Golongan Tarif                         [+ Tambah Golongan]  │
├────┬─────────┬──────────────────┬────────────┬───────┬───────┤
│ No │ Kode    │ Nama Golongan    │ Tarif/m³   │ Admin │ Aksi  │
├────┼─────────┼──────────────────┼────────────┼───────┼───────┤
│ 1  │ R1      │ Rumah Tangga 1   │ Rp 3.500   │ 5.000 │ ✏️🗑️ │
│ 2  │ R2      │ Rumah Tangga 2   │ Rp 4.200   │ 5.000 │ ✏️🗑️ │
│ 3  │ NIAGA   │ Niaga/Komersial  │ Rp 8.000   │ 7.500 │ ✏️🗑️ │
│ 4  │ SOSIAL  │ Sosial           │ Rp 2.000   │ 3.000 │ ✏️🗑️ │
├────┴─────────┴──────────────────┴────────────┴───────┴───────┤
└──────────────────────────────────────────────────────────────┘
```

### 10.2 Form Tambah/Edit Golongan Tarif

```
┌─────────────────────────────────────────┐
│  Tambah Golongan Tarif                  │
├─────────────────────────────────────────┤
│  Kode Golongan  : [________]            │
│  Nama Golongan  : [________________]    │
│  Tarif per m³   : Rp [________]         │
│  Biaya Admin    : Rp [________]         │
│                                         │
│  [💾 Simpan]  [← Batal]                │
└─────────────────────────────────────────┘
```

---

## 11. Ringkasan Route Aplikasi

| No | Route | Method | Controller | Middleware | Keterangan |
|---|---|---|---|---|---|
| 1 | `/login` | GET/POST | AuthController | guest | Halaman login |
| 2 | `/logout` | POST | AuthController | auth | Proses logout |
| 3 | `/admin/dashboard` | GET | DashboardController | auth, role:admin | Dashboard Admin |
| 4 | `/petugas/dashboard` | GET | DashboardController | auth, role:petugas | Dashboard Petugas |
| 5 | `/pelanggan/dashboard` | GET | DashboardController | auth, role:pelanggan | Dashboard Pelanggan |
| 6 | `/pimpinan/dashboard` | GET | DashboardController | auth, role:pimpinan | Dashboard Pimpinan |
| 7 | `/admin/users` | CRUD | UserController | auth, role:admin | Kelola User |
| 8 | `/admin/golongan-tarif` | CRUD | GolonganTarifController | auth, role:admin | Kelola Tarif |
| 9 | `/admin/pelanggan` | CRUD | PelangganController | auth, role:admin,petugas | Kelola Pelanggan |
| 10 | `/petugas/pemakaian` | CRUD | PemakaianAirController | auth, role:admin,petugas | Input Pemakaian |
| 11 | `/petugas/tagihan` | GET | TagihanController | auth, role:admin,petugas | Daftar Tagihan |
| 12 | `/petugas/pembayaran/{id}` | GET/POST | PembayaranController | auth, role:petugas | Proses Bayar |
| 13 | `/petugas/pembayaran/{id}/cetak` | GET | PembayaranController | auth | Cetak Bukti |
| 14 | `/admin/pengaduan` | GET | PengaduanController | auth, role:admin,petugas | Kelola Pengaduan |
| 15 | `/admin/pengaduan/{id}` | GET/POST | PengaduanController | auth, role:admin,petugas | Respon Pengaduan |
| 16 | `/pelanggan/tagihan` | GET | TagihanController | auth, role:pelanggan | Tagihan Saya |
| 17 | `/pelanggan/bayar/{id}` | GET/POST | PembayaranController | auth, role:pelanggan | Bayar Online (VA/QRIS) |
| 18 | `/pelanggan/riwayat` | GET | PembayaranController | auth, role:pelanggan | Riwayat Bayar |
| 19 | `/pelanggan/pengaduan` | CRUD | PengaduanController | auth, role:pelanggan | Pengaduan Saya |
| 20 | `/api/payment/callback` | POST | PaymentCallbackController | - | Webhook Gateway |
| 21 | `/admin/laporan/pembayaran` | GET | LaporanController | auth, role:admin,pimpinan | Lap. Pembayaran |
| 22 | `/admin/laporan/pelanggan` | GET | LaporanController | auth, role:admin,pimpinan | Lap. Pelanggan |
| 23 | `/admin/laporan/pemakaian` | GET | LaporanController | auth, role:admin,pimpinan | Lap. Pemakaian |

---

## 12. Teknologi & Library UI

| Komponen | Teknologi |
|---|---|
| CSS Framework | Bootstrap 5 |
| Icon | Bootstrap Icons / Font Awesome |
| Grafik Dashboard | Chart.js / ApexCharts |
| DataTable | Yajra DataTables (server-side) |
| Select Dropdown | Select2 (pencarian pelanggan) |
| Date Picker | Flatpickr |
| PDF Export | DomPDF / Barryvdh Laravel-DomPDF |
| Excel Export | Maatwebsite/Laravel-Excel |
| Notifikasi | SweetAlert2 |
| Validasi Form | Laravel Request Validation + JS |
| Payment Gateway | Midtrans / Xendit (Snap API) |
| QR Code | chillerlan/php-qrcode (render QRIS) |
