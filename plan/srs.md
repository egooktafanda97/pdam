# SOFTWARE REQUIREMENTS SPECIFICATION (SRS)

## Sistem Informasi Pembayaran dan Pelayanan PDAM Berbasis Web

**Versi 1.0**
Dokumen Kebutuhan Sistem untuk Skripsi

Program Studi Sistem Informasi
Tahun 2025

---

# BAB 1 — PENDAHULUAN

## 1.1 Tujuan Dokumen

Dokumen Software Requirements Specification (SRS) ini bertujuan untuk mendefinisikan secara lengkap dan formal seluruh kebutuhan fungsional dan non-fungsional dari Sistem Informasi Pembayaran dan Pelayanan PDAM Berbasis Web. Dokumen ini menjadi acuan utama bagi pengembang sistem, penguji, dan pemangku kepentingan dalam proses perancangan, pengembangan, pengujian, serta implementasi sistem.

## 1.2 Ruang Lingkup Sistem

Sistem yang dikembangkan adalah Sistem Informasi Pembayaran dan Pelayanan PDAM Berbasis Web, yang mencakup pengelolaan data pelanggan, pencatatan pemakaian air, pemrosesan tagihan dan pembayaran, penanganan pengaduan pelanggan, serta pembuatan laporan secara terintegrasi. Sistem ini ditujukan untuk digunakan oleh internal PDAM (Admin, Petugas, Pimpinan) dan pelanggan PDAM.

## 1.3 Definisi, Akronim, dan Singkatan

| Istilah / Akronim | Definisi |
| --- | --- |
| SRS | Software Requirements Specification – Dokumen spesifikasi kebutuhan perangkat lunak |
| PDAM | Perusahaan Daerah Air Minum – Badan usaha milik daerah yang mengelola layanan air bersih |
| FR | Functional Requirement – Kebutuhan fungsional sistem |
| NFR | Non-Functional Requirement – Kebutuhan non-fungsional sistem |
| UC | Use Case – Skenario interaksi antara aktor dan sistem |
| Admin | Administrator – Pengguna dengan hak akses penuh dalam sistem |
| Petugas | Pegawai PDAM yang bertugas melayani pelanggan dan menginput data operasional |
| Pimpinan | Manajemen atau pimpinan PDAM yang memiliki akses laporan |
| Pelanggan | Masyarakat yang terdaftar sebagai pengguna layanan air PDAM |
| UI | User Interface – Antarmuka pengguna sistem |
| DBMS | Database Management System – Perangkat lunak pengelola basis data |

## 1.4 Referensi

- IEEE Std 830-1998, IEEE Recommended Practice for Software Requirements Specifications.
- Pressman, R.S. (2014). Software Engineering: A Practitioner's Approach. McGraw-Hill.
- Sommerville, I. (2016). Software Engineering, 10th Edition. Pearson.
- Peraturan Pemerintah Republik Indonesia tentang Perusahaan Daerah Air Minum.

## 1.5 Gambaran Umum Dokumen

Dokumen SRS ini disusun dalam empat bab utama. Bab 1 menjelaskan pendahuluan dan ruang lingkup dokumen. Bab 2 menjelaskan deskripsi umum sistem. Bab 3 memuat spesifikasi kebutuhan fungsional secara lengkap. Bab 4 memuat spesifikasi kebutuhan non-fungsional. Bab 5 menjelaskan daftar use case sistem.

---

# BAB 2 — DESKRIPSI UMUM SISTEM

## 2.1 Perspektif Sistem

Sistem Informasi Pembayaran dan Pelayanan PDAM Berbasis Web merupakan sistem yang berdiri sendiri (standalone web application) namun terintegrasi dengan basis data terpusat. Sistem ini menggantikan proses manual yang sebelumnya dilakukan secara konvensional. Sistem diakses melalui browser internet oleh berbagai aktor dengan hak akses yang berbeda.

## 2.2 Fungsi Utama Sistem

Sistem memiliki enam fungsi utama yang saling terintegrasi, yaitu:

- Manajemen Login dan Hak Akses Pengguna
- Pengelolaan Data Pelanggan PDAM
- Pencatatan dan Penghitungan Pemakaian Air
- Pemrosesan Tagihan dan Pembayaran
- Penanganan Pengaduan Pelanggan
- Pembuatan dan Ekspor Laporan

## 2.3 Karakteristik Pengguna

Sistem ini dirancang untuk digunakan oleh empat kelompok aktor dengan karakteristik dan kebutuhan yang berbeda:

| Aktor | Peran | Hak Akses Utama | Tingkat Keahlian IT |
| --- | --- | --- | --- |
| Admin | Administrator sistem yang bertanggung jawab atas keseluruhan pengelolaan data dan akun pengguna | Akses penuh ke semua modul | Menengah – Tinggi |
| Petugas | Pegawai lapangan PDAM yang bertugas menginput pemakaian air dan memproses pembayaran | Pelanggan, pemakaian, pembayaran, pengaduan | Dasar – Menengah |
| Pelanggan | Masyarakat pengguna layanan air PDAM yang ingin memantau tagihan dan menyampaikan pengaduan | Lihat tagihan, riwayat, kirim pengaduan | Dasar |
| Pimpinan | Manajemen atau kepala PDAM yang membutuhkan akses laporan untuk pengambilan keputusan | Akses laporan dan statistik | Dasar – Menengah |

## 2.4 Batasan Sistem

- Sistem berbasis web dan hanya dapat diakses melalui browser internet.
- Sistem tidak menyediakan integrasi pembayaran online (payment gateway) pada versi ini.
- Sistem tidak mencakup modul penggajian atau manajemen aset PDAM.
- Pelanggan tidak dapat mengubah data pribadinya sendiri secara langsung tanpa konfirmasi petugas.
- Laporan yang dihasilkan merupakan rekap data dari basis data sistem dan tidak terhubung ke sistem eksternal.

## 2.5 Asumsi dan Ketergantungan

- Sistem dijalankan di lingkungan server dengan koneksi internet yang stabil.
- Setiap petugas memiliki perangkat komputer atau laptop dengan browser modern yang terbarukan.
- Data awal pelanggan diinput secara manual ke dalam sistem sebelum sistem dioperasikan.
- Golongan tarif air dikelola dan diperbarui oleh Admin sesuai ketentuan yang berlaku.

---

# BAB 3 — KEBUTUHAN FUNGSIONAL (FUNCTIONAL REQUIREMENTS)

Bagian ini menjelaskan seluruh kebutuhan fungsional sistem yang harus dipenuhi. Setiap kebutuhan diidentifikasi dengan kode unik, nama, deskripsi, tingkat prioritas, dan aktor yang terlibat.

## 3.1 Keterangan Prioritas

| Prioritas | Keterangan |
| --- | --- |
| Tinggi | Fitur wajib ada dan harus diimplementasikan sebelum sistem dapat dioperasikan (Must Have) |
| Sedang | Fitur penting dan sebaiknya diimplementasikan pada versi pertama (Should Have) |
| Rendah | Fitur tambahan yang dapat diimplementasikan pada versi berikutnya (Nice to Have) |

## 3.2 Tabel Kebutuhan Fungsional

### 3.2.1 Modul Manajemen User dan Autentikasi

| ID | Nama Kebutuhan | Deskripsi | Prioritas | Aktor |
| --- | --- | --- | --- | --- |
| FR-01 | Login Pengguna | Sistem menyediakan halaman autentikasi login bagi Admin, Petugas, Pelanggan, dan Pimpinan dengan validasi username dan password. | Tinggi | Semua Aktor |
| FR-02 | Logout Pengguna | Sistem menyediakan fungsi logout untuk mengakhiri sesi pengguna secara aman. | Tinggi | Semua Aktor |
| FR-03 | Manajemen Hak Akses | Sistem mengatur hak akses setiap pengguna berdasarkan peran (role) masing-masing sehingga fitur yang tersedia sesuai dengan kewenangan. | Tinggi | Admin |
| FR-04 | Kelola Data User | Admin dapat menambah, mengubah, menonaktifkan, dan melihat data akun pengguna sistem. | Tinggi | Admin |

### 3.2.2 Modul Data Pelanggan

| ID | Nama Kebutuhan | Deskripsi | Prioritas | Aktor |
| --- | --- | --- | --- | --- |
| FR-05 | Tambah Data Pelanggan | Admin atau Petugas dapat menambahkan data pelanggan baru beserta informasi lengkap (nama, alamat, nomor meter, golongan tarif). | Tinggi | Admin, Petugas |
| FR-06 | Ubah Data Pelanggan | Admin atau Petugas dapat mengubah data pelanggan yang sudah terdaftar dalam sistem. | Tinggi | Admin, Petugas |
| FR-07 | Hapus Data Pelanggan | Admin dapat menonaktifkan atau menghapus data pelanggan dari sistem. | Sedang | Admin |
| FR-08 | Lihat Daftar Pelanggan | Admin dan Petugas dapat melihat daftar seluruh pelanggan yang terdaftar. | Tinggi | Admin, Petugas |
| FR-09 | Pencarian Data Pelanggan | Sistem menyediakan fitur pencarian berdasarkan nama, nomor pelanggan, atau alamat. | Tinggi | Admin, Petugas |

### 3.2.3 Modul Pemakaian Air

| ID | Nama Kebutuhan | Deskripsi | Prioritas | Aktor |
| --- | --- | --- | --- | --- |
| FR-10 | Input Data Pemakaian Air | Petugas dapat menginput angka meter awal dan akhir setiap bulan untuk setiap pelanggan. | Tinggi | Petugas |
| FR-11 | Hitung Tagihan Otomatis | Sistem secara otomatis menghitung total pemakaian air dan besaran tagihan berdasarkan golongan tarif yang berlaku. | Tinggi | Sistem |
| FR-12 | Riwayat Pemakaian Air | Sistem menyimpan dan menampilkan riwayat pemakaian air pelanggan per periode bulan. | Sedang | Admin, Petugas, Pelanggan |

### 3.2.4 Modul Pembayaran Tagihan

| ID | Nama Kebutuhan | Deskripsi | Prioritas | Aktor |
| --- | --- | --- | --- | --- |
| FR-13 | Tampilkan Tagihan Pelanggan | Sistem menampilkan informasi tagihan aktif pelanggan beserta rincian pemakaian dan total yang harus dibayar. | Tinggi | Petugas, Pelanggan |
| FR-14 | Catat Transaksi Pembayaran | Petugas dapat mencatat pembayaran tagihan pelanggan dan sistem menerbitkan bukti pembayaran. | Tinggi | Petugas |
| FR-15 | Deteksi Status Tagihan | Sistem secara otomatis menandai status tagihan sebagai 'Lunas' atau 'Belum Bayar' berdasarkan data pembayaran. | Tinggi | Sistem |
| FR-16 | Riwayat Pembayaran | Sistem menyimpan seluruh riwayat pembayaran pelanggan dan dapat ditampilkan kapan saja. | Sedang | Admin, Petugas, Pelanggan |
| FR-17 | Cetak Bukti Pembayaran | Sistem dapat mencetak atau mengunduh bukti pembayaran dalam format yang dapat disimpan pelanggan. | Sedang | Petugas |

### 3.2.5 Modul Pelayanan dan Pengaduan

| ID | Nama Kebutuhan | Deskripsi | Prioritas | Aktor |
| --- | --- | --- | --- | --- |
| FR-18 | Kirim Pengaduan | Pelanggan dapat mengirimkan pengaduan atau keluhan terkait layanan air melalui sistem. | Tinggi | Pelanggan |
| FR-19 | Lihat Daftar Pengaduan | Admin dan Petugas dapat melihat seluruh daftar pengaduan yang masuk beserta statusnya. | Tinggi | Admin, Petugas |
| FR-20 | Respon Pengaduan | Admin atau Petugas dapat memberikan tanggapan atau respons terhadap pengaduan pelanggan. | Tinggi | Admin, Petugas |
| FR-21 | Update Status Pengaduan | Admin atau Petugas dapat memperbarui status penanganan pengaduan (Baru, Diproses, Selesai). | Sedang | Admin, Petugas |
| FR-22 | Riwayat Pengaduan | Sistem menyimpan seluruh riwayat pengaduan beserta respons dan statusnya. | Sedang | Admin, Pelanggan |

### 3.2.6 Modul Laporan

| ID | Nama Kebutuhan | Deskripsi | Prioritas | Aktor |
| --- | --- | --- | --- | --- |
| FR-23 | Laporan Pembayaran | Sistem dapat menghasilkan laporan data pembayaran dalam periode tertentu (harian, bulanan, tahunan). | Tinggi | Admin, Pimpinan |
| FR-24 | Laporan Pelanggan | Sistem dapat menghasilkan laporan data pelanggan aktif maupun nonaktif. | Sedang | Admin, Pimpinan |
| FR-25 | Laporan Pemakaian Air | Sistem dapat menghasilkan laporan rekap pemakaian air seluruh pelanggan per periode. | Sedang | Admin, Pimpinan |
| FR-26 | Ekspor Laporan | Laporan dapat diekspor atau dicetak dalam format PDF maupun Excel untuk keperluan arsip. | Sedang | Admin, Pimpinan |

## 3.3 Rekapitulasi Kebutuhan Fungsional

| Modul | Jumlah FR | FR Prioritas Tinggi | FR Prioritas Sedang |
| --- | --- | --- | --- |
| Manajemen User & Autentikasi | 4 | 3 | 1 |
| Data Pelanggan | 5 | 4 | 1 |
| Pemakaian Air | 3 | 2 | 1 |
| Pembayaran Tagihan | 5 | 3 | 2 |
| Pelayanan & Pengaduan | 5 | 3 | 2 |
| Laporan | 4 | 1 | 3 |
| **TOTAL** | **26** | **16** | **10** |

---

# BAB 4 — KEBUTUHAN NON-FUNGSIONAL (NON-FUNCTIONAL REQUIREMENTS)

Kebutuhan non-fungsional mendefinisikan kualitas dan standar kinerja yang harus dipenuhi oleh sistem. Kebutuhan ini tidak berkaitan langsung dengan fungsi spesifik, tetapi sangat mempengaruhi keberhasilan implementasi dan penerimaan sistem oleh pengguna.

## 4.1 Tabel Kebutuhan Non-Fungsional

| ID | Kategori | Parameter | Deskripsi Kebutuhan | Prioritas |
| --- | --- | --- | --- | --- |
| NFR-01 | Ketersediaan (Availability) | Uptime Sistem | Sistem harus dapat diakses minimal 99% waktu dalam sebulan. Sistem tidak boleh mengalami downtime lebih dari 7,2 jam per bulan. | Tinggi |
| NFR-02 | Kinerja (Performance) | Waktu Respons | Sistem harus mampu menampilkan halaman dan memproses data dalam waktu tidak lebih dari 3 detik pada kondisi jaringan normal. | Tinggi |
| NFR-03 | Kinerja (Performance) | Kapasitas Pengguna | Sistem harus mampu melayani minimal 50 pengguna secara bersamaan tanpa penurunan performa yang signifikan. | Sedang |
| NFR-04 | Keamanan (Security) | Autentikasi | Sistem menggunakan mekanisme autentikasi berbasis username dan password terenkripsi (bcrypt/hashing). Sesi pengguna dikelola dengan token yang aman. | Tinggi |
| NFR-05 | Keamanan (Security) | Otorisasi Berbasis Peran | Setiap pengguna hanya dapat mengakses fitur sesuai hak akses berdasarkan perannya. Akses tidak sah harus dicegah oleh sistem. | Tinggi |
| NFR-06 | Keamanan (Security) | Proteksi Data | Data sensitif pelanggan dan transaksi disimpan secara aman dalam basis data yang terproteksi dan tidak dapat diakses langsung dari luar sistem. | Tinggi |
| NFR-07 | Kegunaan (Usability) | Kemudahan Penggunaan | Antarmuka sistem dirancang intuitif dan mudah dipelajari oleh pengguna non-teknis (pegawai PDAM) dalam waktu kurang dari 1 hari pelatihan. | Sedang |
| NFR-08 | Kegunaan (Usability) | Responsif | Tampilan sistem harus responsif dan dapat diakses dengan baik melalui berbagai perangkat seperti komputer desktop, laptop, dan tablet. | Sedang |
| NFR-09 | Keandalan (Reliability) | Validasi Data | Sistem harus memvalidasi seluruh input data dari pengguna sebelum diproses agar tidak terjadi kesalahan perhitungan atau penyimpanan data. | Tinggi |
| NFR-10 | Keandalan (Reliability) | Konsistensi Data | Data pelanggan, pemakaian air, dan pembayaran harus konsisten dan terintegrasi dalam satu basis data yang terpusat. | Tinggi |
| NFR-11 | Portabilitas (Portability) | Kompatibilitas Browser | Sistem harus dapat diakses dan berfungsi dengan baik pada browser modern utama: Google Chrome, Mozilla Firefox, dan Microsoft Edge. | Sedang |
| NFR-12 | Keterpeliharaan (Maintainability) | Kemudahan Pemeliharaan | Sistem dibangun dengan kode yang terstruktur dan terdokumentasi sehingga memudahkan pengembang dalam melakukan pemeliharaan atau pengembangan di masa mendatang. | Rendah |

## 4.2 Batasan Teknis Sistem

| Komponen | Teknologi / Spesifikasi yang Disarankan |
| --- | --- |
| Bahasa Pemrograman Backend | PHP (Laravel Framework) atau Python (Django/Flask) |
| Bahasa Pemrograman Frontend | HTML5, CSS3, JavaScript (Bootstrap / Tailwind CSS) |
| Database Management System | MySQL / MariaDB / PostgreSQL |
| Web Server | Apache / Nginx |
| Browser yang Didukung | Google Chrome (v90+), Mozilla Firefox (v88+), Microsoft Edge (v90+) |
| Sistem Operasi Server | Linux (Ubuntu / CentOS) atau Windows Server |
| Format Ekspor Laporan | PDF (menggunakan library dompdf / TCPDF) dan Excel (.xlsx) |

---

# BAB 5 — DESKRIPSI USE CASE

Bagian ini mendaftarkan seluruh use case sistem berdasarkan kebutuhan fungsional yang telah diidentifikasi. Setiap use case menggambarkan interaksi antara aktor dengan sistem untuk mencapai suatu tujuan tertentu.

## 5.1 Daftar Aktor

| Aktor | Deskripsi |
| --- | --- |
| Admin | Pengguna dengan hak akses penuh. Bertanggung jawab atas keseluruhan pengelolaan sistem, data pelanggan, dan laporan. |
| Petugas | Pegawai operasional PDAM yang melakukan input pemakaian air, mencatat pembayaran, dan menangani pengaduan. |
| Pelanggan | Pengguna akhir layanan air PDAM yang dapat memantau tagihan, riwayat, dan mengirim pengaduan. |
| Pimpinan | Manajemen PDAM yang memiliki akses khusus untuk membaca laporan dan statistik operasional. |
| Sistem | Proses otomatis yang dijalankan sistem tanpa interaksi langsung dari pengguna, seperti penghitungan tagihan dan perubahan status. |

## 5.2 Tabel Use Case

| Kode UC | Nama Use Case | Aktor | Deskripsi | FR Terkait |
| --- | --- | --- | --- | --- |
| UC-01 | Login ke Sistem | Semua Aktor | Pengguna memasukkan kredensial untuk mengakses sistem sesuai peran masing-masing. | FR-01, FR-03 |
| UC-02 | Logout dari Sistem | Semua Aktor | Pengguna mengakhiri sesi aktif dan keluar dari sistem. | FR-02 |
| UC-03 | Kelola Akun Pengguna | Admin | Admin menambah, mengubah, atau menonaktifkan akun pengguna sistem. | FR-04 |
| UC-04 | Tambah Pelanggan Baru | Admin, Petugas | Menginput data pelanggan baru ke dalam sistem. | FR-05 |
| UC-05 | Ubah Data Pelanggan | Admin, Petugas | Memperbarui informasi pelanggan yang telah terdaftar. | FR-06 |
| UC-06 | Hapus / Nonaktifkan Pelanggan | Admin | Menghapus atau menonaktifkan data pelanggan dari sistem. | FR-07 |
| UC-07 | Cari Data Pelanggan | Admin, Petugas | Mencari data pelanggan menggunakan kata kunci tertentu. | FR-08, FR-09 |
| UC-08 | Input Pemakaian Air | Petugas | Memasukkan angka stand meter awal dan akhir pelanggan setiap bulan. | FR-10 |
| UC-09 | Hitung Tagihan Otomatis | Sistem | Sistem menghitung tagihan berdasarkan pemakaian dan tarif yang berlaku. | FR-11 |
| UC-10 | Lihat Riwayat Pemakaian | Admin, Petugas, Pelanggan | Melihat rekap historis pemakaian air per bulan. | FR-12 |
| UC-11 | Lihat Tagihan | Petugas, Pelanggan | Melihat tagihan aktif beserta rincian pemakaian dan nominal yang harus dibayar. | FR-13, FR-15 |
| UC-12 | Catat Pembayaran | Petugas | Merekam transaksi pembayaran tagihan pelanggan. | FR-14 |
| UC-13 | Cetak Bukti Pembayaran | Petugas | Mencetak atau mengunduh bukti pembayaran untuk pelanggan. | FR-17 |
| UC-14 | Lihat Riwayat Pembayaran | Admin, Petugas, Pelanggan | Melihat riwayat seluruh transaksi pembayaran yang pernah dilakukan. | FR-16 |
| UC-15 | Kirim Pengaduan | Pelanggan | Pelanggan mengajukan pengaduan atau keluhan terkait layanan air. | FR-18 |
| UC-16 | Kelola Pengaduan | Admin, Petugas | Melihat, merespons, dan memperbarui status penanganan pengaduan pelanggan. | FR-19, FR-20, FR-21 |
| UC-17 | Lihat Riwayat Pengaduan | Admin, Pelanggan | Melihat riwayat pengaduan beserta respons dan statusnya. | FR-22 |
| UC-18 | Cetak Laporan Pembayaran | Admin, Pimpinan | Menghasilkan laporan data pembayaran berdasarkan periode tertentu. | FR-23, FR-26 |
| UC-19 | Cetak Laporan Pelanggan | Admin, Pimpinan | Menghasilkan laporan data pelanggan aktif dan nonaktif. | FR-24, FR-26 |
| UC-20 | Cetak Laporan Pemakaian Air | Admin, Pimpinan | Menghasilkan laporan rekap pemakaian air seluruh pelanggan per periode. | FR-25, FR-26 |

## 5.3 Spesifikasi Use Case Detail

Berikut adalah contoh spesifikasi detail untuk use case inti sistem:

### UC-12: Catat Transaksi Pembayaran

| Atribut | Keterangan |
| --- | --- |
| Kode Use Case | UC-12 |
| Nama Use Case | Catat Transaksi Pembayaran |
| Aktor Utama | Petugas |
| Kondisi Awal (Pre-condition) | Petugas telah login ke sistem. Data tagihan pelanggan telah tersedia. |
| Kondisi Akhir (Post-condition) | Tagihan berubah status menjadi 'Lunas'. Sistem menyimpan riwayat pembayaran. Bukti pembayaran dapat dicetak. |
| Alur Normal (Main Flow) | 1. Petugas memilih menu Pembayaran. 2. Petugas mencari data pelanggan berdasarkan nomor pelanggan atau nama. 3. Sistem menampilkan tagihan aktif pelanggan beserta rinciannya. 4. Petugas mengkonfirmasi pembayaran dan memasukkan jumlah uang yang diterima. 5. Sistem memvalidasi dan menyimpan transaksi pembayaran. 6. Sistem memperbarui status tagihan menjadi 'Lunas'. 7. Sistem menampilkan bukti pembayaran yang dapat dicetak. |
| Alur Alternatif | 3a. Apabila pelanggan tidak memiliki tagihan aktif, sistem menampilkan pesan 'Tidak ada tagihan yang perlu dibayar'. 4a. Apabila jumlah pembayaran tidak sesuai, sistem menampilkan peringatan validasi. |
| Kebutuhan Terkait | FR-13, FR-14, FR-15, FR-16, FR-17 |

### UC-15: Kirim Pengaduan

| Atribut | Keterangan |
| --- | --- |
| Kode Use Case | UC-15 |
| Nama Use Case | Kirim Pengaduan |
| Aktor Utama | Pelanggan |
| Kondisi Awal (Pre-condition) | Pelanggan telah login ke sistem. |
| Kondisi Akhir (Post-condition) | Pengaduan tersimpan dalam sistem dengan status 'Baru'. Admin/Petugas dapat melihat pengaduan. |
| Alur Normal (Main Flow) | 1. Pelanggan memilih menu Pengaduan. 2. Pelanggan mengklik tombol 'Kirim Pengaduan Baru'. 3. Sistem menampilkan formulir pengaduan. 4. Pelanggan mengisi judul, kategori, dan isi pengaduan. 5. Pelanggan mengklik tombol 'Kirim'. 6. Sistem memvalidasi data dan menyimpan pengaduan. 7. Sistem menampilkan konfirmasi bahwa pengaduan berhasil dikirim. |
| Alur Alternatif | 4a. Apabila form tidak lengkap diisi, sistem menampilkan pesan validasi dan meminta pelanggan melengkapi data. |
| Kebutuhan Terkait | FR-18, FR-22 |

---

# BAB 6 — RINGKASAN DAN PENUTUP

## 6.1 Ringkasan Kebutuhan Sistem

Berdasarkan analisis yang telah dilakukan, Sistem Informasi Pembayaran dan Pelayanan PDAM Berbasis Web membutuhkan total 26 kebutuhan fungsional dan 12 kebutuhan non-fungsional yang mencakup 6 modul utama dan melibatkan 5 aktor sistem. Seluruh kebutuhan tersebut dirangkum sebagai berikut:

| Kategori | Jumlah |
| --- | --- |
| Kebutuhan Fungsional (FR) | 26 item |
| Kebutuhan Non-Fungsional (NFR) | 12 item |
| Use Case (UC) | 20 use case |
| Aktor Sistem | 5 aktor |
| Modul Utama | 6 modul |

## 6.2 Kesimpulan

Dokumen SRS ini merupakan landasan teknis yang kuat untuk memulai proses perancangan dan pengembangan Sistem Informasi Pembayaran dan Pelayanan PDAM Berbasis Web. Sistem yang akan dibangun dirancang untuk meningkatkan efisiensi pelayanan PDAM melalui digitalisasi proses bisnis yang sebelumnya dilakukan secara manual, sehingga mampu meningkatkan akurasi data, kecepatan layanan, dan kepuasan pelanggan.

Dokumen ini bersifat hidup (living document) dan dapat diperbarui sesuai kebutuhan seiring berjalannya proses pengembangan sistem.


