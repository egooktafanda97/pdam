import 'tagihan.dart';

class Pembayaran {
  final int id;
  final int tagihanId;
  final String? tanggalBayar;
  final double jumlahBayar;
  final String metodeBayar;
  final String? penyediaLayanan;
  final String kodePembayaran;
  final String statusPembayaran;
  final String? referensiGateway;
  final String? idTransaksi;
  final DateTime? expiredAt;
  final Tagihan? tagihan;

  Pembayaran({
    required this.id,
    required this.tagihanId,
    this.tanggalBayar,
    required this.jumlahBayar,
    required this.metodeBayar,
    this.penyediaLayanan,
    required this.kodePembayaran,
    required this.statusPembayaran,
    this.referensiGateway,
    this.idTransaksi,
    this.expiredAt,
    this.tagihan,
  });

  factory Pembayaran.fromJson(Map<String, dynamic> json) {
    return Pembayaran(
      id: json['id'],
      tagihanId: json['tagihan_id'],
      tanggalBayar: json['tanggal_bayar'],
      jumlahBayar: double.tryParse(json['jumlah_bayar'].toString()) ?? 0,
      metodeBayar: json['metode_bayar'] ?? '',
      penyediaLayanan: json['penyedia_layanan'],
      kodePembayaran: json['kode_pembayaran'] ?? '',
      statusPembayaran: json['status_pembayaran'] ?? 'Pending',
      referensiGateway: json['referensi_gateway'],
      idTransaksi: json['id_transaksi']?.toString(),
      expiredAt: json['expired_at'] != null 
          ? DateTime.tryParse(json['expired_at']) 
          : null,
      tagihan: json['tagihan'] != null
          ? Tagihan.fromJson(json['tagihan'])
          : null,
    );
  }
}
