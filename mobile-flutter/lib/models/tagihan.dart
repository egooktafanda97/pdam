import 'pemakaian_air.dart';
import 'pembayaran.dart';

class Tagihan {
  final int id;
  final int pelangganId;
  final int? pemakaianAirId;
  final int periodeBulan;
  final int periodeTahun;
  final int jumlahMeter;
  final double biayaPemakaian;
  final double biayaAdmin;
  final double totalTagihan;
  final String? tanggalJatuhTempo;
  final String status;
  final PemakaianAir? pemakaianAir;
  final List<Pembayaran>? pembayaran;

  Tagihan({
    required this.id,
    required this.pelangganId,
    this.pemakaianAirId,
    required this.periodeBulan,
    required this.periodeTahun,
    required this.jumlahMeter,
    required this.biayaPemakaian,
    required this.biayaAdmin,
    required this.totalTagihan,
    this.tanggalJatuhTempo,
    required this.status,
    this.pemakaianAir,
    this.pembayaran,
  });

  factory Tagihan.fromJson(Map<String, dynamic> json) {
    return Tagihan(
      id: json['id'],
      pelangganId: json['pelanggan_id'],
      pemakaianAirId: json['pemakaian_air_id'],
      periodeBulan: json['periode_bulan'] ?? 0,
      periodeTahun: json['periode_tahun'] ?? 0,
      jumlahMeter: json['jumlah_meter'] ?? 0,
      biayaPemakaian: double.tryParse(json['biaya_pemakaian'].toString()) ?? 0,
      biayaAdmin: double.tryParse(json['biaya_admin'].toString()) ?? 0,
      totalTagihan: double.tryParse(json['total_tagihan'].toString()) ?? 0,
      tanggalJatuhTempo: json['tanggal_jatuh_tempo'],
      status: json['status'] ?? 'Belum Bayar',
      pemakaianAir: json['pemakaian_air'] != null
          ? PemakaianAir.fromJson(json['pemakaian_air'])
          : null,
      pembayaran: json['pembayaran'] != null
          ? (json['pembayaran'] as List)
              .map((e) => Pembayaran.fromJson(e))
              .toList()
          : null,
    );
  }

  String get periodeFormatted {
    const months = [
      '', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
      'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];
    if (periodeBulan >= 1 && periodeBulan <= 12) {
      return '${months[periodeBulan]} $periodeTahun';
    }
    return '$periodeBulan/$periodeTahun';
  }
}
