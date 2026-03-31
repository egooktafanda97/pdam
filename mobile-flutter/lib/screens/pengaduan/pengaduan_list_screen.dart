import 'package:flutter/material.dart';
import '../../services/api_service.dart';
import '../../models/pengaduan.dart';
import '../../config/theme.dart';
import '../../utils/formatter.dart';
import '../../widgets/status_badge.dart';

class PengaduanListScreen extends StatefulWidget {
  const PengaduanListScreen({super.key});

  @override
  State<PengaduanListScreen> createState() => _PengaduanListScreenState();
}

class _PengaduanListScreenState extends State<PengaduanListScreen> {
  final ApiService _api = ApiService();
  List<Pengaduan> _pengaduan = [];
  bool _isLoading = true;

  @override
  void initState() {
    super.initState();
    _loadPengaduan();
  }

  Future<void> _loadPengaduan() async {
    setState(() => _isLoading = true);
    try {
      final response = await _api.getPengaduan();
      final data = response.data['data']['data'] as List;
      setState(() {
        _pengaduan = data.map((e) => Pengaduan.fromJson(e)).toList();
      });
    } catch (e) {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text('Gagal memuat pengaduan')),
        );
      }
    }
    setState(() => _isLoading = false);
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Pengaduan Saya')),
      floatingActionButton: FloatingActionButton.extended(
        onPressed: () async {
          final result = await Navigator.pushNamed(context, '/pengaduan/create');
          if (result == true) _loadPengaduan();
        },
        backgroundColor: AppTheme.primaryColor,
        icon: const Icon(Icons.add, color: Colors.white),
        label: const Text('Buat Aduan', style: TextStyle(color: Colors.white)),
      ),
      body: RefreshIndicator(
        onRefresh: _loadPengaduan,
        child: _isLoading
            ? const Center(child: CircularProgressIndicator())
            : _pengaduan.isEmpty
                ? Center(
                    child: Column(
                      mainAxisSize: MainAxisSize.min,
                      children: [
                        Icon(Icons.inbox, size: 60, color: Colors.grey[300]),
                        const SizedBox(height: 12),
                        Text(
                          'Belum ada pengaduan',
                          style: TextStyle(color: Colors.grey[500], fontSize: 16),
                        ),
                      ],
                    ),
                  )
                : ListView.builder(
                    padding: const EdgeInsets.all(16),
                    itemCount: _pengaduan.length,
                    itemBuilder: (context, index) {
                      final p = _pengaduan[index];
                      return Card(
                        margin: const EdgeInsets.only(bottom: 10),
                        child: InkWell(
                          onTap: () => _showDetail(p),
                          borderRadius: BorderRadius.circular(16),
                          child: Padding(
                            padding: const EdgeInsets.all(16),
                            child: Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                Row(
                                  children: [
                                    Expanded(
                                      child: Text(
                                        p.judulPengaduan,
                                        style: const TextStyle(
                                          fontWeight: FontWeight.w600,
                                          fontSize: 15,
                                        ),
                                      ),
                                    ),
                                    StatusBadge(status: p.status),
                                  ],
                                ),
                                const SizedBox(height: 8),
                                Row(
                                  children: [
                                    Container(
                                      padding: const EdgeInsets.symmetric(
                                          horizontal: 8, vertical: 2),
                                      decoration: BoxDecoration(
                                        color: Colors.grey[100],
                                        borderRadius: BorderRadius.circular(6),
                                      ),
                                      child: Text(
                                        p.kategori,
                                        style: TextStyle(
                                          fontSize: 11,
                                          color: Colors.grey[700],
                                        ),
                                      ),
                                    ),
                                    const SizedBox(width: 8),
                                    Text(
                                      AppFormatter.tanggal(p.tanggalPengaduan),
                                      style: TextStyle(
                                        fontSize: 12,
                                        color: Colors.grey[500],
                                      ),
                                    ),
                                  ],
                                ),
                              ],
                            ),
                          ),
                        ),
                      );
                    },
                  ),
      ),
    );
  }

  void _showDetail(Pengaduan p) {
    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      shape: const RoundedRectangleBorder(
        borderRadius: BorderRadius.vertical(top: Radius.circular(20)),
      ),
      builder: (context) {
        return DraggableScrollableSheet(
          initialChildSize: 0.65,
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
                      width: 40, height: 4,
                      decoration: BoxDecoration(
                        color: Colors.grey[300],
                        borderRadius: BorderRadius.circular(2),
                      ),
                    ),
                  ),
                  const SizedBox(height: 20),
                  Row(
                    children: [
                      Expanded(
                        child: Text(
                          p.judulPengaduan,
                          style: const TextStyle(
                            fontSize: 18, fontWeight: FontWeight.bold),
                        ),
                      ),
                      StatusBadge(status: p.status),
                    ],
                  ),
                  const SizedBox(height: 6),
                  Text(
                    '${p.kategori} • ${AppFormatter.tanggal(p.tanggalPengaduan)}',
                    style: TextStyle(color: Colors.grey[600], fontSize: 13),
                  ),
                  const Divider(height: 24),
                  const Text('Deskripsi',
                      style: TextStyle(fontWeight: FontWeight.w600)),
                  const SizedBox(height: 6),
                  Text(p.deskripsi, style: const TextStyle(height: 1.5)),
                  if (p.tanggapan != null && p.tanggapan!.isNotEmpty) ...[
                    const SizedBox(height: 20),
                    Container(
                      width: double.infinity,
                      padding: const EdgeInsets.all(14),
                      decoration: BoxDecoration(
                        color: AppTheme.successColor.withOpacity(0.05),
                        borderRadius: BorderRadius.circular(12),
                        border: Border.all(
                          color: AppTheme.successColor.withOpacity(0.3)),
                      ),
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Row(
                            children: [
                              const Icon(Icons.reply, size: 16,
                                  color: AppTheme.successColor),
                              const SizedBox(width: 6),
                              const Text('Tanggapan Petugas',
                                  style: TextStyle(
                                    fontWeight: FontWeight.w600,
                                    color: AppTheme.successColor,
                                  )),
                            ],
                          ),
                          const SizedBox(height: 8),
                          Text(p.tanggapan!, style: const TextStyle(height: 1.5)),
                          if (p.tanggalTanggapan != null)
                            Padding(
                              padding: const EdgeInsets.only(top: 6),
                              child: Text(
                                AppFormatter.tanggal(p.tanggalTanggapan),
                                style: TextStyle(
                                  fontSize: 11, color: Colors.grey[500]),
                              ),
                            ),
                        ],
                      ),
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
}
