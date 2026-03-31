class GolonganTarif {
  final int id;
  final String kodeGolongan;
  final String namaGolongan;
  final double tarifPerM3;
  final double biayaAdmin;

  GolonganTarif({
    required this.id,
    required this.kodeGolongan,
    required this.namaGolongan,
    required this.tarifPerM3,
    required this.biayaAdmin,
  });

  factory GolonganTarif.fromJson(Map<String, dynamic> json) {
    return GolonganTarif(
      id: json['id'],
      kodeGolongan: json['kode_golongan'] ?? '',
      namaGolongan: json['nama_golongan'] ?? '',
      tarifPerM3: double.tryParse(json['tarif_per_m3'].toString()) ?? 0,
      biayaAdmin: double.tryParse(json['biaya_admin'].toString()) ?? 0,
    );
  }
}
