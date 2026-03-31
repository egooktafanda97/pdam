import 'package:flutter/material.dart';
import '../../services/api_service.dart';
import '../../config/theme.dart';

class PengaduanCreateScreen extends StatefulWidget {
  const PengaduanCreateScreen({super.key});

  @override
  State<PengaduanCreateScreen> createState() => _PengaduanCreateScreenState();
}

class _PengaduanCreateScreenState extends State<PengaduanCreateScreen> {
  final _formKey = GlobalKey<FormState>();
  final ApiService _api = ApiService();

  String? _kategori;
  final _judulController = TextEditingController();
  final _deskripsiController = TextEditingController();
  bool _isSending = false;

  final List<String> _kategoriList = [
    'Air Keruh',
    'Air Mati',
    'Pipa Bocor',
    'Meteran Rusak',
    'Tagihan Tidak Sesuai',
    'Lainnya',
  ];

  @override
  void dispose() {
    _judulController.dispose();
    _deskripsiController.dispose();
    super.dispose();
  }

  Future<void> _submit() async {
    if (!_formKey.currentState!.validate()) return;

    setState(() => _isSending = true);
    try {
      await _api.storePengaduan(
        kategori: _kategori!,
        judulPengaduan: _judulController.text,
        deskripsi: _deskripsiController.text,
      );
      if (!mounted) return;
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          content: Text('Pengaduan berhasil dikirim!'),
          backgroundColor: AppTheme.successColor,
        ),
      );
      Navigator.pop(context, true);
    } catch (e) {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text('Gagal mengirim pengaduan')),
        );
      }
    }
    setState(() => _isSending = false);
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Buat Pengaduan')),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(20),
        child: Form(
          key: _formKey,
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              const Text('Kategori',
                  style: TextStyle(fontWeight: FontWeight.w600)),
              const SizedBox(height: 8),
              DropdownButtonFormField<String>(
                value: _kategori,
                hint: const Text('Pilih kategori'),
                decoration: InputDecoration(
                  border: OutlineInputBorder(
                    borderRadius: BorderRadius.circular(12),
                  ),
                ),
                items: _kategoriList.map((k) {
                  return DropdownMenuItem(value: k, child: Text(k));
                }).toList(),
                onChanged: (v) => setState(() => _kategori = v),
                validator: (v) => v == null ? 'Pilih kategori' : null,
              ),
              const SizedBox(height: 16),
              const Text('Judul Pengaduan',
                  style: TextStyle(fontWeight: FontWeight.w600)),
              const SizedBox(height: 8),
              TextFormField(
                controller: _judulController,
                decoration: InputDecoration(
                  hintText: 'Tuliskan judul singkat',
                  border: OutlineInputBorder(
                    borderRadius: BorderRadius.circular(12),
                  ),
                ),
                validator: (v) =>
                    v == null || v.isEmpty ? 'Judul wajib diisi' : null,
              ),
              const SizedBox(height: 16),
              const Text('Deskripsi',
                  style: TextStyle(fontWeight: FontWeight.w600)),
              const SizedBox(height: 8),
              TextFormField(
                controller: _deskripsiController,
                maxLines: 5,
                decoration: InputDecoration(
                  hintText: 'Jelaskan secara detail permasalahan Anda...',
                  border: OutlineInputBorder(
                    borderRadius: BorderRadius.circular(12),
                  ),
                ),
                validator: (v) =>
                    v == null || v.isEmpty ? 'Deskripsi wajib diisi' : null,
              ),
              const SizedBox(height: 28),
              SizedBox(
                width: double.infinity,
                height: 50,
                child: ElevatedButton.icon(
                  onPressed: _isSending ? null : _submit,
                  icon: _isSending
                      ? const SizedBox(
                          width: 20,
                          height: 20,
                          child: CircularProgressIndicator(
                            strokeWidth: 2,
                            valueColor: AlwaysStoppedAnimation(Colors.white),
                          ),
                        )
                      : const Icon(Icons.send, color: Colors.white),
                  label: Text(
                    _isSending ? 'Mengirim...' : 'Kirim Pengaduan',
                    style: const TextStyle(fontSize: 16, color: Colors.white),
                  ),
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}
