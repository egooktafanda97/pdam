import 'package:flutter/material.dart';
import '../../services/api_service.dart';
import '../../models/tagihan.dart';
import '../../config/theme.dart';
import '../../utils/formatter.dart';
import '../../widgets/status_badge.dart';

class TagihanListScreen extends StatefulWidget {
  const TagihanListScreen({super.key});

  @override
  State<TagihanListScreen> createState() => _TagihanListScreenState();
}

class _TagihanListScreenState extends State<TagihanListScreen> {
  final ApiService _api = ApiService();
  List<Tagihan> _tagihan = [];
  bool _isLoading = true;
  String _filter = 'Semua';

  @override
  void initState() {
    super.initState();
    _loadTagihan();
  }

  Future<void> _loadTagihan() async {
    setState(() => _isLoading = true);
    try {
      final response = await _api.getTagihan(status: _filter);
      final data = response.data['data']['data'] as List;
      setState(() {
        _tagihan = data.map((e) => Tagihan.fromJson(e)).toList();
      });
    } catch (e) {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text('Gagal memuat tagihan')),
        );
      }
    }
    setState(() => _isLoading = false);
  }

  Future<void> _cancelPembayaran(int pembayaranId) async {
    Navigator.pop(context); // Close the detail modal
    setState(() => _isLoading = true);
    try {
      await _api.cancelPembayaran(pembayaranId);
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text('Pembayaran berhasil dibatalkan')),
        );
        _loadTagihan();
      }
    } catch (e) {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text('Gagal membatalkan pembayaran')),
        );
        setState(() => _isLoading = false);
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    return RefreshIndicator(
      onRefresh: _loadTagihan,
      child: Column(
        children: [
          // Filter chips
          Container(
            padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
            child: Row(
              children: ['Semua', 'Belum', 'Lunas'].map((f) {
                final isSelected = _filter == f;
                return Padding(
                  padding: const EdgeInsets.only(right: 8),
                  child: FilterChip(
                    selected: isSelected,
                    label: Text(f),
                    selectedColor: AppTheme.primaryColor.withOpacity(0.15),
                    checkmarkColor: AppTheme.primaryColor,
                    labelStyle: TextStyle(
                      color: isSelected ? AppTheme.primaryColor : Colors.grey[700],
                      fontWeight: isSelected ? FontWeight.w600 : FontWeight.normal,
                    ),
                    onSelected: (_) {
                      setState(() => _filter = f);
                      _loadTagihan();
                    },
                  ),
                );
              }).toList(),
            ),
          ),

          // List
          Expanded(
            child: _isLoading
                ? const Center(child: CircularProgressIndicator())
                : _tagihan.isEmpty
                    ? Center(
                        child: Column(
                          mainAxisSize: MainAxisSize.min,
                          children: [
                            Icon(Icons.receipt_long, size: 60, color: Colors.grey[300]),
                            const SizedBox(height: 12),
                            Text(
                              'Tidak ada tagihan',
                              style: TextStyle(color: Colors.grey[500], fontSize: 16),
                            ),
                          ],
                        ),
                      )
                    : ListView.builder(
                        padding: const EdgeInsets.symmetric(horizontal: 16),
                        itemCount: _tagihan.length,
                        itemBuilder: (context, index) {
                          final t = _tagihan[index];
                          return _buildTagihanCard(t);
                        },
                      ),
          ),
        ],
      ),
    );
  }

  Widget _buildTagihanCard(Tagihan t) {
    return Card(
      margin: const EdgeInsets.only(bottom: 12),
      child: InkWell(
        onTap: () {
          _showTagihanDetail(t);
        },
        borderRadius: BorderRadius.circular(16),
        child: Padding(
          padding: const EdgeInsets.all(16),
          child: Column(
            children: [
              Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  Text(
                    t.periodeFormatted,
                    style: const TextStyle(fontWeight: FontWeight.w600, fontSize: 15),
                  ),
                  StatusBadge(status: t.status),
                ],
              ),
              const Divider(height: 20),
              _infoRow('Pemakaian', '${t.jumlahMeter} m³'),
              _infoRow('Biaya Pemakaian', AppFormatter.rupiah(t.biayaPemakaian)),
              _infoRow('Biaya Admin', AppFormatter.rupiah(t.biayaAdmin)),
              const Divider(height: 16),
              Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  const Text('Total', style: TextStyle(fontWeight: FontWeight.bold)),
                  Text(
                    AppFormatter.rupiah(t.totalTagihan),
                    style: const TextStyle(
                      fontSize: 18,
                      fontWeight: FontWeight.bold,
                      color: AppTheme.primaryColor,
                    ),
                  ),
                ],
              ),
              if (t.status == 'Belum Bayar') ...[
                const SizedBox(height: 12),
                SizedBox(
                  width: double.infinity,
                  child: ElevatedButton(
                    onPressed: () {
                      Navigator.pushNamed(context, '/pembayaran', arguments: t);
                    },
                    child: const Text('Bayar'),
                  ),
                ),
              ],
            ],
          ),
        ),
      ),
    );
  }

  Widget _infoRow(String label, String value) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 2),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Text(label, style: TextStyle(color: Colors.grey[600], fontSize: 13)),
          Text(value, style: const TextStyle(fontSize: 13)),
        ],
      ),
    );
  }

  void _showTagihanDetail(Tagihan t) {
    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      shape: const RoundedRectangleBorder(
        borderRadius: BorderRadius.vertical(top: Radius.circular(20)),
      ),
      builder: (context) {
        return DraggableScrollableSheet(
          initialChildSize: 0.6,
          maxChildSize: 0.85,
          minChildSize: 0.4,
          expand: false,
          builder: (context, scrollController) {
            return SingleChildScrollView(
              controller: scrollController,
              padding: const EdgeInsets.all(24),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Center(
                    child: Container(
                      width: 40,
                      height: 4,
                      decoration: BoxDecoration(
                        color: Colors.grey[300],
                        borderRadius: BorderRadius.circular(2),
                      ),
                    ),
                  ),
                  const SizedBox(height: 20),
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      const Text(
                        'Detail Tagihan',
                        style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold),
                      ),
                      StatusBadge(status: t.status),
                    ],
                  ),
                  const SizedBox(height: 20),
                  _detailRow('Periode', t.periodeFormatted),
                  _detailRow('Pemakaian', '${t.jumlahMeter} m³'),
                  if (t.pemakaianAir != null) ...[
                    _detailRow('Meter Awal', '${t.pemakaianAir!.meterAwal}'),
                    _detailRow('Meter Akhir', '${t.pemakaianAir!.meterAkhir}'),
                  ],
                  const Divider(height: 24),
                  _detailRow('Biaya Pemakaian', AppFormatter.rupiah(t.biayaPemakaian)),
                  _detailRow('Biaya Admin', AppFormatter.rupiah(t.biayaAdmin)),
                  const Divider(height: 24),
                  _detailRow('Total Tagihan', AppFormatter.rupiah(t.totalTagihan),
                      isBold: true),
                  if (t.tanggalJatuhTempo != null)
                    _detailRow('Jatuh Tempo', AppFormatter.tanggal(t.tanggalJatuhTempo)),
                  const SizedBox(height: 24),
                  
                  if (t.status == 'Belum Bayar' || t.status == 'Pending') ...[
                    Builder(
                      builder: (context) {
                        final pendingPayments = t.pembayaran?.where((p) => p.statusPembayaran == 'Pending').toList();
                        final activePayment = (pendingPayments != null && pendingPayments.isNotEmpty) ? pendingPayments.last : null;

                        if (activePayment != null) {
                          final isExpired = activePayment.expiredAt != null && DateTime.now().isAfter(activePayment.expiredAt!);

                          return Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              Container(
                                padding: const EdgeInsets.all(16),
                                decoration: BoxDecoration(
                                  color: isExpired ? Colors.red.shade50 : Colors.blue.shade50,
                                  borderRadius: BorderRadius.circular(12),
                                  border: Border.all(
                                    color: isExpired ? Colors.red.shade200 : Colors.blue.shade200,
                                  ),
                                ),
                                child: Column(
                                  crossAxisAlignment: CrossAxisAlignment.start,
                                  children: [
                                    Text(
                                      isExpired ? 'Menunggu Pembayaran (Kedaluwarsa)' : 'Menunggu Pembayaran',
                                      style: TextStyle(
                                        fontWeight: FontWeight.bold,
                                        color: isExpired ? Colors.red.shade700 : Colors.blue.shade700,
                                      ),
                                    ),
                                    const SizedBox(height: 12),
                                    _detailRow('Metode', activePayment.penyediaLayanan ?? activePayment.metodeBayar),
                                    _detailRow('Reference ID', activePayment.referensiGateway ?? '-'),
                                    _detailRow('Transaction ID', activePayment.idTransaksi ?? '-'),
                                    _detailRow('Kode Bayar', activePayment.kodePembayaran, isBold: true),
                                    if (activePayment.expiredAt != null)
                                      _detailRow('Berlaku Hingga', AppFormatter.tanggalWaktu(activePayment.expiredAt!.toString())),
                                  ],
                                ),
                              ),
                                if (isExpired) ...[
                                  SizedBox(
                                    width: double.infinity,
                                    height: 48,
                                    child: ElevatedButton.icon(
                                      style: ElevatedButton.styleFrom(backgroundColor: AppTheme.primaryColor),
                                      onPressed: () {
                                        Navigator.pop(context);
                                        Navigator.pushNamed(context, '/pembayaran', arguments: t);
                                      },
                                      icon: const Icon(Icons.refresh),
                                      label: const Text('Generate Ulang Kode Bayar'),
                                    ),
                                  ),
                                  const SizedBox(height: 12),
                                ],
                                SizedBox(
                                  width: double.infinity,
                                  height: 48,
                                  child: OutlinedButton.icon(
                                    onPressed: () => _cancelPembayaran(activePayment.id),
                                    icon: const Icon(Icons.cancel_outlined, color: Colors.red),
                                    label: const Text('Batalkan Pembayaran', style: TextStyle(color: Colors.red)),
                                    style: OutlinedButton.styleFrom(side: const BorderSide(color: Colors.red)),
                                  ),
                                ),
                            ],
                          );
                        }

                        // Default if Belum Bayar and no active payment
                        return SizedBox(
                          width: double.infinity,
                          height: 48,
                          child: ElevatedButton.icon(
                            onPressed: () {
                              Navigator.pop(context);
                              Navigator.pushNamed(context, '/pembayaran', arguments: t);
                            },
                            icon: const Icon(Icons.payment),
                            label: const Text('Bayar Sekarang'),
                          ),
                        );
                      },
                    ),
                  ],
                ],
              ),
            );
          },
        );
      },
    );
  }

  Widget _detailRow(String label, String value, {bool isBold = false}) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 6),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Text(label, style: TextStyle(color: Colors.grey[600])),
          Text(
            value,
            style: TextStyle(
              fontWeight: isBold ? FontWeight.bold : FontWeight.w500,
              color: isBold ? AppTheme.primaryColor : null,
              fontSize: isBold ? 16 : 14,
            ),
          ),
        ],
      ),
    );
  }
}
