class ApiConfig {
  // Otomatis: localhost untuk web, 10.0.2.2 untuk Android emulator
  static String get baseUrl => 'http://10.0.2.2:8000/api';

  // Auth
  static const String login = '/auth/login';
  static const String logout = '/auth/logout';
  static const String me = '/auth/me';

  // Dashboard
  static const String dashboard = '/pelanggan/dashboard';

  // Tagihan
  static const String tagihan = '/pelanggan/tagihan';

  // Pembayaran
  static String bayar(int tagihanId) => '/pelanggan/bayar/$tagihanId';
  static const String riwayat = '/pelanggan/riwayat';
  static String pembayaranDetail(int id) => '/pelanggan/pembayaran/$id';
  static String cancelPembayaran(int id) => '/pelanggan/pembayaran/$id/cancel';

  // Pengaduan
  static const String pengaduan = '/pelanggan/pengaduan';
  static String pengaduanDetail(int id) => '/pelanggan/pengaduan/$id';

  // Profil
  static const String profil = '/pelanggan/profil';
  static const String updatePassword = '/pelanggan/profil/password';
}
