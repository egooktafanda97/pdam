import 'package:flutter/material.dart';
import '../../services/api_service.dart';
import '../../models/pembayaran.dart';
import '../../config/theme.dart';
import '../../utils/formatter.dart';
import '../../widgets/status_badge.dart';

class RiwayatPembayaranScreen extends StatefulWidget {
  const RiwayatPembayaranScreen({super.key});

  @override
  State<RiwayatPembayaranScreen> createState() => _RiwayatPembayaranScreenState();
}

class _RiwayatPembayaranScreenState extends State<RiwayatPembayaranScreen> {
  final ApiService _api = ApiService();
  List<Pembayaran> _pembayaran = [];
  bool _isLoading = true;

  @override
  void initState() {
    super.initState();
    _loadRiwayat();
  }

  Future<void> _loadRiwayat() async {
    setState(() => _isLoading = true);
    try {
      final response = await _api.getRiwayat();
      final data = response.data['data']['data'] as List;
      setState(() {
        _pembayaran = data.map((e) => Pembayaran.fromJson(e)).toList();
      });
    } catch (e) {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text('Gagal memuat riwayat pembayaran')),
        );
      }
    }
    setState(() => _isLoading = false);
  }

  @override
  Widget build(BuildContext context) {
    return RefreshIndicator(
      onRefresh: _loadRiwayat,
      child: _isLoading
          ? const Center(child: CircularProgressIndicator())
          : _pembayaran.isEmpty
              ? Center(
                  child: Column(
                    mainAxisSize: MainAxisSize.min,
                    children: [
                      Icon(Icons.history, size: 60, color: Colors.grey[300]),
                      const SizedBox(height: 12),
                      Text(
                        'Belum ada riwayat pembayaran',
                        style: TextStyle(color: Colors.grey[500], fontSize: 16),
                      ),
                    ],
                  ),
                )
              : ListView.builder(
                  padding: const EdgeInsets.all(16),
                  itemCount: _pembayaran.length,
                  itemBuilder: (context, index) {
                    final p = _pembayaran[index];
                    return Card(
                      margin: const EdgeInsets.only(bottom: 10),
                      child: ListTile(
                        contentPadding: const EdgeInsets.all(14),
                        leading: Container(
                          padding: const EdgeInsets.all(10),
                          decoration: BoxDecoration(
                            color: AppTheme.primaryColor.withOpacity(0.1),
                            borderRadius: BorderRadius.circular(12),
                          ),
                          child: const Icon(Icons.receipt, color: AppTheme.primaryColor),
                        ),
                        title: Text(
                          AppFormatter.rupiah(p.jumlahBayar),
                          style: const TextStyle(fontWeight: FontWeight.bold),
                        ),
                        subtitle: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            const SizedBox(height: 4),
                            Text(
                              '${p.metodeBayar} • ${AppFormatter.tanggal(p.tanggalBayar)}',
                              style: TextStyle(fontSize: 12, color: Colors.grey[600]),
                            ),
                            const SizedBox(height: 4),
                            Text(
                              'Kode: ${p.kodePembayaran}',
                              style: TextStyle(fontSize: 11, color: Colors.grey[500]),
                            ),
                          ],
                        ),
                        trailing: StatusBadge(status: p.statusPembayaran),
                      ),
                    );
                  },
                ),
    );
  }
}
