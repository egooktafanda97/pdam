# Migrasi Tema Dashboard: Mazer → Morvin

## Ringkasan

Mengganti tema template dashboard dari **Mazer** ke **Morvin** (`public/admin-template`). Kedua template berbasis Bootstrap 5. Perubahan utama: layout wrapper, sidebar, header/topbar, footer, CSS/JS assets, dan icon library.

---

## Analisis Perbandingan Template

| Aspek | Mazer (Lama) | Morvin (Baru) |
|---|---|---|
| **Wrapper** | `#app > #sidebar + #main.layout-navbar` | `#layout-wrapper > header#page-topbar + .vertical-menu + .main-content` |
| **Sidebar** | `ul.menu > li.sidebar-item > a.sidebar-link` | `ul.metismenu#side-menu > li > a.waves-effect` |
| **Submenu** | `li.has-sub > ul.submenu > li.submenu-item` | `li > a.has-arrow + ul.sub-menu > li` |
| **Sidebar Title** | `li.sidebar-title` | `li.menu-title` |
| **Active State** | class `active` | class `mm-active` + `mm-show` |
| **Header** | `<header> > nav.navbar.navbar-top` | `header#page-topbar > .navbar-header` |
| **Content** | `#main-content > .page-heading > section.section` | `.main-content > .page-content > .container-fluid` |
| **Icons** | Bootstrap Icons (`bi-*`), Iconly (`iconly-*`) | MDI (`mdi-*`), Dripicons (`dripicons-*`) |
| **CSS** | `assets/css/app.css` + `style.css` | `bootstrap.min.css` + `icons.min.css` + `app.min.css` |
| **JS** | jQuery, Perfect Scrollbar | jQuery, Bootstrap Bundle, MetisMenu, SimpleBar, Node-Waves |
| **Sidebar Profile** | Tidak ada | Ada (`.user-sidebar`) |

---

## Daftar Lengkap Semua File yang Terdampak

### A. Layout Files (4 file) — Perubahan BESAR

| # | File | Perubahan |
|---|---|---|
| 1 | `layouts/app.blade.php` | **Tulis ulang**: CSS refs, JS refs, body structure wrapper |
| 2 | `layouts/header.blade.php` | **Tulis ulang**: navbar → topbar Morvin, icons `bi-*` → `mdi-*` |
| 3 | `layouts/sidebar.blade.php` | **Tulis ulang**: sidebar structure, menu classes, semua icon, active state logic |
| 4 | `layouts/footer.blade.php` | **Update**: wrapper, credit text |

### B. Dashboard Views (5 file) — Perubahan SEDANG

| # | File | Perubahan yang Spesifik |
|---|---|---|
| 5 | `dashboard.blade.php` | Breadcrumb format |
| 6 | `dashboard/admin.blade.php` | Breadcrumb, `iconly-*` → `mdi-*` (4), `stats-icon` (4x), `font-extrabold` (4x), `py-4-5` (4x), `text-bold-500` (2x), hapus `iconly/bold.css` |
| 7 | `dashboard/petugas.blade.php` | Breadcrumb, `iconly-*` → `mdi-*` (4), `stats-icon` (4x), `font-extrabold` (4x), `py-4-5` (4x) |
| 8 | `dashboard/pelanggan.blade.php` | Breadcrumb, `bi-*` → `mdi-*` (8 icon) |
| 9 | `dashboard/pimpinan.blade.php` | Breadcrumb, `iconly-*` → `mdi-*` (3), `stats-icon` (3x), `bg-light-success` |

### C. Pelanggan Views (3 file)

| # | File | Perubahan |
|---|---|---|
| 10 | `pelanggan/index.blade.php` | Breadcrumb, `bi-plus/pencil/trash` → `mdi-*`, `text-bold-500` → `fw-bold`, simple-datatables |
| 11 | `pelanggan/create.blade.php` | Breadcrumb, `bi-info-circle/save` → `mdi-*` |
| 12 | `pelanggan/edit.blade.php` | Breadcrumb, `bi-save` → `mdi-content-save` |

### D. Golongan Tarif Views (3 file)

| # | File | Perubahan |
|---|---|---|
| 13 | `golongan_tarif/index.blade.php` | Breadcrumb, `bg-light-primary`, simple-datatables |
| 14 | `golongan_tarif/create.blade.php` | Breadcrumb |
| 15 | `golongan_tarif/edit.blade.php` | Breadcrumb |

### E. Pemakaian Views (2 file)

| # | File | Perubahan |
|---|---|---|
| 16 | `pemakaian/index.blade.php` | Breadcrumb, `bi-search/plus` → `mdi-*`, simple-datatables |
| 17 | `pemakaian/create.blade.php` | Breadcrumb, `bi-save` → `mdi-content-save` |

### F. Tagihan Views (2 file)

| # | File | Perubahan |
|---|---|---|
| 18 | `tagihan/index.blade.php` | Breadcrumb, `bi-filter/cash/printer` → `mdi-*`, simple-datatables |
| 19 | `tagihan/pelanggan.blade.php` | Breadcrumb, 5 icon `bi-*` → `mdi-*` |

### G. Pembayaran Views (4 file)

| # | File | Perubahan |
|---|---|---|
| 20 | `pembayaran/create.blade.php` | Breadcrumb, `bi-receipt/wallet2/check-circle` → `mdi-*` |
| 21 | `pembayaran/online.blade.php` | Breadcrumb, 4 icon `bi-*` → `mdi-*` |
| 22 | `pembayaran/riwayat.blade.php` | Breadcrumb, 3 icon `bi-*` → `mdi-*` |
| 23 | `pembayaran/cetak.blade.php` | **Standalone print** — kemungkinan tidak perlu diubah |

### H. Pengaduan Views (4 file)

| # | File | Perubahan |
|---|---|---|
| 24 | `pengaduan/index.blade.php` | Breadcrumb, `bi-eye` → `mdi-eye` |
| 25 | `pengaduan/create.blade.php` | Breadcrumb, 3 icon `bi-*` → `mdi-*` |
| 26 | `pengaduan/show.blade.php` | Breadcrumb, 5 icon, `bg-light-primary` |
| 27 | `pengaduan/riwayat.blade.php` | Breadcrumb, 8 icon, `bg-light-info` |

### I. Laporan Views (3 file)

| # | File | Perubahan |
|---|---|---|
| 28 | `laporan/pembayaran.blade.php` | Breadcrumb, simple-datatables |
| 29 | `laporan/pemakaian.blade.php` | Breadcrumb, simple-datatables |
| 30 | `laporan/pelanggan.blade.php` | Breadcrumb, simple-datatables |

### J. Users Views (3 file)

| # | File | Perubahan |
|---|---|---|
| 31 | `users/index.blade.php` | Breadcrumb, `bi-plus/pencil/trash` → `mdi-*`, simple-datatables |
| 32 | `users/create.blade.php` | Breadcrumb |
| 33 | `users/edit.blade.php` | Breadcrumb |

### K. Profile & Other (4 file) — PERLU KEPUTUSAN

| # | File | Perubahan |
|---|---|---|
| 34 | `profile/edit.blade.php` | Pakai `<x-app-layout>` (Breeze) — konversi atau biarkan? |
| 35 | `example.blade.php` | Breadcrumb format |
| 36 | `layouts/app_breeze.blade.php` | Layout Breeze — hapus di cleanup? |
| 37 | `layouts/navigation.blade.php` | Navigasi Breeze — hapus di cleanup? |

---

## CSS Class Mapping (Mazer → Morvin/Bootstrap 5)

| Class Mazer | Pengganti |
|---|---|
| `stats-icon purple/blue/green/red/orange` | `mini-stat-icon` + `avatar-title rounded-circle bg-soft-*` |
| `text-bold-500` | `fw-bold` |
| `font-extrabold` | `fw-bolder` atau `font-size-20 fw-bold` |
| `font-semibold` | `fw-semibold` |
| `text-subtitle` | `font-size-14` atau hapus |
| `py-4-5` | `py-4` |
| `bg-light-primary/success/info` | `bg-soft-primary/success/info` |

---

## Icon Mapping Lengkap

### `bi-*` → `mdi-*`

| Lama | Baru | Dipakai di |
|---|---|---|
| `bi-grid-fill` | `dripicons-home` | sidebar |
| `bi-people-fill` | `dripicons-user-group` | sidebar |
| `bi-tags-fill` | `mdi mdi-tag-multiple` | sidebar |
| `bi-house-door-fill` | `mdi mdi-account-group` | sidebar |
| `bi-droplet-fill/half` | `mdi mdi-water` | sidebar, dashboard |
| `bi-cash-stack/cash` | `mdi mdi-cash-multiple` | sidebar, tagihan |
| `bi-megaphone-fill` | `mdi mdi-bullhorn` | sidebar |
| `bi-file-earmark-text-fill/text` | `mdi mdi-file-document` | sidebar, tagihan, pembayaran |
| `bi-receipt` | `mdi mdi-receipt` | sidebar, pembayaran |
| `bi-clock-history` | `mdi mdi-history` | sidebar, dashboard, riwayat |
| `bi-plus` | `mdi mdi-plus` | pelanggan, users, pemakaian |
| `bi-pencil/pencil-square` | `mdi mdi-pencil` / `mdi-pencil-box` | pelanggan, users, pengaduan |
| `bi-trash` | `mdi mdi-delete` | pelanggan, users |
| `bi-eye` | `mdi mdi-eye` | dashboard, pengaduan |
| `bi-save` | `mdi mdi-content-save` | pelanggan, pemakaian |
| `bi-search` | `mdi mdi-magnify` | pemakaian |
| `bi-filter` | `mdi mdi-filter` | tagihan |
| `bi-printer` | `mdi mdi-printer` | tagihan |
| `bi-check-circle/fill` | `mdi mdi-check-circle` | status badges, pengaduan |
| `bi-hourglass/split` | `mdi mdi-timer-sand` | status badges, pengaduan |
| `bi-x-circle` | `mdi mdi-close-circle` | status badges |
| `bi-credit-card` | `mdi mdi-credit-card` | tagihan, dashboard |
| `bi-wallet2` | `mdi mdi-wallet` | dashboard, pembayaran |
| `bi-send/fill` | `mdi mdi-send` | pengaduan |
| `bi-info-circle` | `mdi mdi-information` | pelanggan, pembayaran, pengaduan |
| `bi-geo-alt` | `mdi mdi-map-marker` | pengaduan/show |
| `bi-chat-dots/left-text` | `mdi mdi-chat` | pengaduan |
| `bi-person-fill` | `mdi mdi-account` | pengaduan, header |
| `bi-calendar-date` | `mdi mdi-calendar` | pengaduan |
| `bi-image` | `mdi mdi-image` | pengaduan |
| `bi-ui-checks` | `mdi mdi-check-all` | pengaduan |
| `bi-x bi-middle` | `mdi mdi-close` | sidebar |
| `bi-justify` | `mdi mdi-menu` | header |
| `bi-heart-fill` | `mdi mdi-heart` | footer |

### `iconly-*` → `mdi-*`

| Lama | Baru |
|---|---|
| `iconly-boldProfile` | `mdi mdi-account-group` |
| `iconly-boldDocument` | `mdi mdi-file-document` |
| `iconly-boldActivity` | `mdi mdi-chart-line` |
| `iconly-boldMessage` | `mdi mdi-message-alert` |
| `iconly-boldWallet` | `mdi mdi-wallet` |
| `iconly-boldTickSquare` | `mdi mdi-checkbox-marked` |

---

## Perubahan Detail per Komponen

### 1. `layouts/app.blade.php`
- CSS: `assets/css/app.css` + `style.css` → `admin-template/assets/css/bootstrap.min.css` + `icons.min.css` + `app.min.css`
- Body: `#app > #sidebar + #main` → `#layout-wrapper > @include(header) + @include(sidebar) + .main-content > .page-content`
- Breadcrumb: `.page-heading > .page-title > .row` → `.page-title-box > .container-fluid > .row`
- Content: `section.section` → `.container-fluid > .page-content-wrapper`
- JS: hapus Perfect Scrollbar, tambah Bootstrap Bundle + MetisMenu + SimpleBar + Node-Waves

### 2. `layouts/header.blade.php`
- Tulis ulang ke `header#page-topbar > .navbar-header`
- Tambah `.navbar-brand-box` + logo
- Toggle: `#vertical-menu-btn` + `mdi-menu`
- User dropdown: ganti icon, pertahankan Auth logic

### 3. `layouts/sidebar.blade.php`
- Wrapper: `.vertical-menu > div[data-simplebar]`
- User profile section (`.user-sidebar`)
- Menu: `ul.metismenu.list-unstyled#side-menu`
- Active: `mm-active` / `mm-show`
- Pertahankan `@hasrole` dan route checks

### 4. `layouts/footer.blade.php`
- Wrapper: `.container-fluid > .row`
- Update credit text

### 5. Semua 29 Content Views
- Breadcrumb: format 2-column → format Morvin 1-column
- Icons: ganti sesuai mapping
- CSS Classes: ganti Mazer-specific → Bootstrap 5

---

## Verification Plan

1. `php artisan serve` → buka browser
2. Login per role (admin, petugas, pelanggan, pimpinan)
3. Cek: sidebar, topbar, breadcrumb, icons, modals, tabel, form
4. Cek responsive (mobile sidebar toggle)
5. `php artisan test` (pastikan backend test pass)
