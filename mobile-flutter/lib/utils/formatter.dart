import 'package:intl/intl.dart';

class AppFormatter {
  static String rupiah(double amount) {
    final formatter = NumberFormat.currency(
      locale: 'id_ID',
      symbol: 'Rp ',
      decimalDigits: 0,
    );
    return formatter.format(amount);
  }

  static String tanggal(String? dateStr) {
    if (dateStr == null || dateStr.isEmpty) return '-';
    try {
      final date = DateTime.parse(dateStr);
      return DateFormat('dd MMM yyyy', 'id').format(date);
    } catch (e) {
      return dateStr;
    }
  }

  static String tanggalWaktu(String? dateStr) {
    if (dateStr == null || dateStr.isEmpty) return '-';
    try {
      final date = DateTime.parse(dateStr);
      return DateFormat('dd MMM yyyy HH:mm', 'id').format(date);
    } catch (e) {
      return dateStr;
    }
  }
}
