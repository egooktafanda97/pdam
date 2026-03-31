import 'golongan_tarif.dart';

class Pelanggan {
  final int id;
  final int userId;
  final String nomorPelanggan;
  final String nama;
  final String alamat;
  final String nomorMeter;
  final String? noTelepon;
  final int golonganTarifId;
  final String? fotoKtp;
  final String? koordinat;
  final bool isActive;
  final GolonganTarif? golonganTarif;

  Pelanggan({
    required this.id,
    required this.userId,
    required this.nomorPelanggan,
    required this.nama,
    required this.alamat,
    required this.nomorMeter,
    this.noTelepon,
    required this.golonganTarifId,
    this.fotoKtp,
    this.koordinat,
    required this.isActive,
    this.golonganTarif,
  });

  factory Pelanggan.fromJson(Map<String, dynamic> json) {
    return Pelanggan(
      id: json['id'],
      userId: json['user_id'],
      nomorPelanggan: json['nomor_pelanggan'] ?? '',
      nama: json['nama'] ?? '',
      alamat: json['alamat'] ?? '',
      nomorMeter: json['nomor_meter'] ?? '',
      noTelepon: json['no_telepon'],
      golonganTarifId: json['golongan_tarif_id'] ?? 0,
      fotoKtp: json['foto_ktp'],
      koordinat: json['koordinat'],
      isActive: json['is_active'] == true || json['is_active'] == 1,
      golonganTarif: json['golongan_tarif'] != null
          ? GolonganTarif.fromJson(json['golongan_tarif'])
          : null,
    );
  }
}
