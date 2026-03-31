class PemakaianAir {
  final int id;
  final int pelangganId;
  final int periodeBulan;
  final int periodeTahun;
  final int meterAwal;
  final int meterAkhir;
  final int totalPemakaian;

  PemakaianAir({
    required this.id,
    required this.pelangganId,
    required this.periodeBulan,
    required this.periodeTahun,
    required this.meterAwal,
    required this.meterAkhir,
    required this.totalPemakaian,
  });

  factory PemakaianAir.fromJson(Map<String, dynamic> json) {
    return PemakaianAir(
      id: json['id'],
      pelangganId: json['pelanggan_id'],
      periodeBulan: json['periode_bulan'],
      periodeTahun: json['periode_tahun'],
      meterAwal: json['meter_awal'] ?? 0,
      meterAkhir: json['meter_akhir'] ?? 0,
      totalPemakaian: json['total_pemakaian'] ?? 0,
    );
  }
}
