class Pengaduan {
  final int id;
  final int pelangganId;
  final String judulPengaduan;
  final String kategori;
  final String deskripsi;
  final String? tanggalPengaduan;
  final String status;
  final String? tanggapan;
  final String? tanggalTanggapan;

  Pengaduan({
    required this.id,
    required this.pelangganId,
    required this.judulPengaduan,
    required this.kategori,
    required this.deskripsi,
    this.tanggalPengaduan,
    required this.status,
    this.tanggapan,
    this.tanggalTanggapan,
  });

  factory Pengaduan.fromJson(Map<String, dynamic> json) {
    return Pengaduan(
      id: json['id'],
      pelangganId: json['pelanggan_id'],
      judulPengaduan: json['judul_pengaduan'] ?? '',
      kategori: json['kategori'] ?? '',
      deskripsi: json['deskripsi'] ?? '',
      tanggalPengaduan: json['tanggal_pengaduan'],
      status: json['status'] ?? 'Baru',
      tanggapan: json['tanggapan'],
      tanggalTanggapan: json['tanggal_tanggapan'],
    );
  }
}
