import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../providers/auth_provider.dart';
import '../services/api_service.dart';
import '../models/tagihan.dart';
import '../models/pemakaian_air.dart';
import '../models/pembayaran.dart';
import '../config/theme.dart';
import '../utils/formatter.dart';
import '../widgets/status_badge.dart';

class DashboardScreen extends StatefulWidget {
  const DashboardScreen({super.key});

  @override
  State<DashboardScreen> createState() => _DashboardScreenState();
}

class _DashboardScreenState extends State<DashboardScreen> {
  final ApiService _api = ApiService();
  bool _isLoading = true;
  Tagihan? _tagihanAktif;
  PemakaianAir? _pemakaianBulanIni;
  List<Pembayaran> _riwayatPembayaran = [];

  @override
  void initState() {
    super.initState();
    _loadDashboard();
  }

  Future<void> _loadDashboard() async {
    setState(() => _isLoading = true);
    try {
      final response = await _api.getDashboard();
      final data = response.data['data'];

      setState(() {
        if (data['tagihan_aktif'] != null) {
          _tagihanAktif = Tagihan.fromJson(data['tagihan_aktif']);
        }
        if (data['pemakaian_bulan_ini'] != null) {
          _pemakaianBulanIni = PemakaianAir.fromJson(data['pemakaian_bulan_ini']);
        }
        if (data['riwayat_pembayaran'] != null) {
          _riwayatPembayaran = (data['riwayat_pembayaran'] as List)
              .map((e) => Pembayaran.fromJson(e))
              .toList();
        }
      });
    } catch (e) {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text('Gagal memuat data dashboard')),
        );
      }
    }
    setState(() => _isLoading = false);
  }

  Future<void> _cancelPembayaran(int pembayaranId) async {
    setState(() => _isLoading = true);
    try {
      await _api.cancelPembayaran(pembayaranId);
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text('Pembayaran berhasil dibatalkan')),
        );
        _loadDashboard();
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
    final pelanggan = Provider.of<AuthProvider>(context).pelanggan;

    return RefreshIndicator(
      onRefresh: _loadDashboard,
      child: SingleChildScrollView(
        physics: const AlwaysScrollableScrollPhysics(),
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // Header Card
            Container(
              width: double.infinity,
              padding: const EdgeInsets.all(20),
              decoration: BoxDecoration(
                gradient: AppTheme.primaryGradient,
                borderRadius: BorderRadius.circular(20),
                boxShadow: [
                  BoxShadow(
                    color: AppTheme.primaryColor.withOpacity(0.3),
                    blurRadius: 15,
                    offset: const Offset(0, 8),
                  ),
                ],
              ),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Row(
                    children: [
                      Container(
                        padding: const EdgeInsets.all(10),
                        decoration: BoxDecoration(
                          color: Colors.white.withOpacity(0.2),
                          borderRadius: BorderRadius.circular(12),
                        ),
                        child: const Icon(Icons.person, color: Colors.white, size: 28),
                      ),
                      const SizedBox(width: 14),
                      Expanded(
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Text(
                              pelanggan?.nama ?? 'Pelanggan',
                              style: const TextStyle(
                                color: Colors.white,
                                fontSize: 18,
                                fontWeight: FontWeight.bold,
                              ),
                            ),
                            const SizedBox(height: 2),
                            Text(
                              'No. ${pelanggan?.nomorPelanggan ?? '-'}',
                              style: TextStyle(
                                color: Colors.white.withOpacity(0.85),
                                fontSize: 13,
                              ),
                            ),
                          ],
                        ),
                      ),
                    ],
                  ),
                  const SizedBox(height: 16),
                  Container(
                    padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
                    decoration: BoxDecoration(
                      color: Colors.white.withOpacity(0.2),
                      borderRadius: BorderRadius.circular(20),
                    ),
                    child: Text(
                      pelanggan?.golonganTarif?.namaGolongan ?? 'Golongan -',
                      style: const TextStyle(color: Colors.white, fontSize: 12),
                    ),
                  ),
                ],
              ),
            ),
            const SizedBox(height: 20),

            // Pemakaian Bulan Ini
            _sectionTitle('Pemakaian Bulan Ini'),
            const SizedBox(height: 8),
            _isLoading
                ? _loadingCard()
                : _pemakaianBulanIni != null
                    ? _buildPemakaianCard()
                    : _emptyCard('Belum ada data pemakaian bulan ini'),
            const SizedBox(height: 20),

            // Tagihan Aktif
            _sectionTitle('Tagihan Aktif'),
            const SizedBox(height: 8),
            _isLoading
                ? _loadingCard()
                : _tagihanAktif != null
                    ? _buildTagihanCard()
                    : _emptyCard('Tidak ada tagihan yang belum dibayar 🎉'),
            const SizedBox(height: 20),

            // Riwayat Pembayaran Terakhir
            _sectionTitle('Pembayaran Terakhir'),
            const SizedBox(height: 8),
            _isLoading
                ? _loadingCard()
                : _riwayatPembayaran.isNotEmpty
                    ? Column(
                        children: _riwayatPembayaran
                            .map((p) => _buildPembayaranItem(p))
                            .toList(),
                      )
                    : _emptyCard('Belum ada riwayat pembayaran'),
          ],
        ),
      ),
    );
  }

  Widget _sectionTitle(String title) {
    return Text(
      title,
      style: const TextStyle(fontSize: 16, fontWeight: FontWeight.bold),
    );
  }

  Widget _buildPemakaianCard() {
    return Card(
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Row(
          children: [
            Container(
              padding: const EdgeInsets.all(12),
              decoration: BoxDecoration(
                color: AppTheme.accentColor.withOpacity(0.1),
                borderRadius: BorderRadius.circular(12),
              ),
              child: const Icon(Icons.speed, color: AppTheme.accentColor, size: 28),
            ),
            const SizedBox(width: 16),
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    '${_pemakaianBulanIni!.totalPemakaian} m³',
                    style: const TextStyle(fontSize: 22, fontWeight: FontWeight.bold),
                  ),
                  Text(
                    'Meter: ${_pemakaianBulanIni!.meterAwal} → ${_pemakaianBulanIni!.meterAkhir}',
                    style: TextStyle(color: Colors.grey[600], fontSize: 13),
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildTagihanCard() {
    final t = _tagihanAktif!;
    
    return Card(
      child: InkWell(
        onTap: () => _showTagihanDetail(t),
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
                    style: const TextStyle(fontWeight: FontWeight.w600),
                  ),
                  StatusBadge(status: t.status),
                ],
              ),
              const Divider(height: 24),
              Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  const Text('Total Tagihan'),
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
              const SizedBox(height: 12),
              if (t.status == 'Belum Bayar')
                SizedBox(
                  width: double.infinity,
                  child: ElevatedButton.icon(
                    onPressed: () {
                      Navigator.pushNamed(context, '/pembayaran', arguments: t);
                    },
                    icon: const Icon(Icons.payment, size: 18),
                    label: const Text('Bayar Sekarang'),
                  ),
                )
              else if (t.status == 'Pending')
                const SizedBox(
                   width: double.infinity,
                   child: Padding(
                     padding: EdgeInsets.symmetric(vertical: 8),
                     child: Row(
                       mainAxisAlignment: MainAxisAlignment.center,
                       children: [
                         Icon(Icons.info_outline, size: 16, color: AppTheme.primaryColor),
                         SizedBox(width: 8),
                         Text('Klik untuk lihat detail pembayaran', 
                           style: TextStyle(color: AppTheme.primaryColor, fontSize: 13, fontWeight: FontWeight.w500)),
                       ],
                     ),
                   ),
                ),
            ],
          ),
        ),
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
                                const SizedBox(height: 16),
                                if (isExpired) ...[
                                  SizedBox(
                                    width: double.infinity,
                                    height: 48,
                                    child: ElevatedButton.icon(
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
                                    onPressed: () {
                                       Navigator.pop(context);
                                       _cancelPembayaran(activePayment.id);
                                    },
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

  Widget _buildPembayaranItem(Pembayaran p) {
    return Card(
      margin: const EdgeInsets.only(bottom: 8),
      child: ListTile(
        leading: Container(
          padding: const EdgeInsets.all(8),
          decoration: BoxDecoration(
            color: AppTheme.successColor.withOpacity(0.1),
            borderRadius: BorderRadius.circular(10),
          ),
          child: const Icon(Icons.receipt_long, color: AppTheme.successColor),
        ),
        title: Text(
          AppFormatter.rupiah(p.jumlahBayar),
          style: const TextStyle(fontWeight: FontWeight.bold),
        ),
        subtitle: Text(AppFormatter.tanggal(p.tanggalBayar)),
        trailing: StatusBadge(status: p.statusPembayaran),
      ),
    );
  }

  Widget _loadingCard() {
    return Card(
      child: Container(
        padding: const EdgeInsets.all(30),
        child: const Center(child: CircularProgressIndicator()),
      ),
    );
  }

  Widget _emptyCard(String msg) {
    return Card(
      child: Container(
        width: double.infinity,
        padding: const EdgeInsets.all(24),
        child: Column(
          children: [
            Icon(Icons.inbox_outlined, size: 40, color: Colors.grey[400]),
            const SizedBox(height: 8),
            Text(msg, style: TextStyle(color: Colors.grey[500])),
          ],
        ),
      ),
    );
  }
}
