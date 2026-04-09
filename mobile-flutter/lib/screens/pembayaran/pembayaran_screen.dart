import 'package:flutter/material.dart';
import 'package:dio/dio.dart';
import '../../services/api_service.dart';
import '../../models/tagihan.dart';
import '../../config/theme.dart';
import '../../utils/formatter.dart';

class PembayaranScreen extends StatefulWidget {
  final Tagihan tagihan;
  const PembayaranScreen({super.key, required this.tagihan});

  @override
  State<PembayaranScreen> createState() => _PembayaranScreenState();
}

class _PembayaranScreenState extends State<PembayaranScreen> {
  final ApiService _api = ApiService();
  String? _selectedMethod;
  bool _isProcessing = false;
  Map<String, dynamic>? _paymentResult;

  final List<Map<String, dynamic>> _paymentMethods = [
    {'code': 'BNI', 'name': 'BNI Virtual Account', 'icon': Icons.account_balance},
  ];

  Future<void> _processBayar() async {
    if (_selectedMethod == null) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Pilih metode pembayaran terlebih dahulu')),
      );
      return;
    }

      if (mounted) {
        setState(() => _isProcessing = true);
      }
      try {
        final response = await _api.bayar(widget.tagihan.id, _selectedMethod!);
        final data = response.data;
        if (data['status'] == 'success') {
          if (mounted) {
            setState(() {
              _paymentResult = data['data']['payment_info'];
            });
          }
        }
      } on DioException catch (e) {
        if (mounted) {
          String errorMessage = 'Gagal memproses pembayaran';
          if (e.response != null && e.response?.data != null) {
             if (e.response!.data is Map && e.response!.data['message'] != null) {
                 errorMessage = e.response!.data['message'];
             }
          }
          ScaffoldMessenger.of(context).showSnackBar(
            SnackBar(content: Text(errorMessage)),
          );
        }
      } catch (e) {
        if (mounted) {
          ScaffoldMessenger.of(context).showSnackBar(
            const SnackBar(content: Text('Terjadi kesalahan yang tidak terduga')),
          );
        }
      } finally {
        if (mounted) {
          setState(() => _isProcessing = false);
        }
      }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Pembayaran')),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(16),
        child: _paymentResult != null ? _buildPaymentResult() : _buildPaymentForm(),
      ),
    );
  }

  Widget _buildPaymentForm() {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        // Ringkasan tagihan
        Card(
          child: Padding(
            padding: const EdgeInsets.all(16),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                const Text('Ringkasan Tagihan',
                    style: TextStyle(fontWeight: FontWeight.bold, fontSize: 16)),
                const Divider(height: 20),
                _row('Periode', widget.tagihan.periodeFormatted),
                _row('Pemakaian', '${widget.tagihan.jumlahMeter} m³'),
                _row('Biaya Pemakaian', AppFormatter.rupiah(widget.tagihan.biayaPemakaian)),
                _row('Biaya Admin', AppFormatter.rupiah(widget.tagihan.biayaAdmin)),
                const Divider(height: 16),
                _row('Total', AppFormatter.rupiah(widget.tagihan.totalTagihan), isBold: true),
              ],
            ),
          ),
        ),
        const SizedBox(height: 20),

        const Text('Pilih Metode Pembayaran',
            style: TextStyle(fontWeight: FontWeight.bold, fontSize: 16)),
        const SizedBox(height: 10),

        ...List.generate(_paymentMethods.length, (i) {
          final method = _paymentMethods[i];
          final isSelected = _selectedMethod == method['code'];
          return Card(
            margin: const EdgeInsets.only(bottom: 8),
            shape: RoundedRectangleBorder(
              borderRadius: BorderRadius.circular(12),
              side: BorderSide(
                color: isSelected ? AppTheme.primaryColor : Colors.transparent,
                width: 2,
              ),
            ),
            child: ListTile(
              onTap: () => setState(() => _selectedMethod = method['code']),
              leading: Container(
                padding: const EdgeInsets.all(8),
                decoration: BoxDecoration(
                  color: AppTheme.primaryColor.withOpacity(0.1),
                  borderRadius: BorderRadius.circular(10),
                ),
                child: Icon(method['icon'], color: AppTheme.primaryColor),
              ),
              title: Text(method['name']),
              trailing: isSelected
                  ? const Icon(Icons.check_circle, color: AppTheme.primaryColor)
                  : null,
            ),
          );
        }),

        const SizedBox(height: 24),
        SizedBox(
          width: double.infinity,
          height: 50,
          child: ElevatedButton(
            onPressed: _isProcessing ? null : _processBayar,
            child: _isProcessing
                ? const SizedBox(
                    height: 22,
                    width: 22,
                    child: CircularProgressIndicator(
                      strokeWidth: 2,
                      valueColor: AlwaysStoppedAnimation(Colors.white),
                    ),
                  )
                : const Text('Bayar Sekarang', style: TextStyle(fontSize: 16)),
          ),
        ),
      ],
    );
  }

  Widget _buildPaymentResult() {
    return Column(
      children: [
        const SizedBox(height: 20),
        Container(
          padding: const EdgeInsets.all(16),
          decoration: BoxDecoration(
            color: AppTheme.successColor.withOpacity(0.1),
            shape: BoxShape.circle,
          ),
          child: const Icon(Icons.check_circle, color: AppTheme.successColor, size: 60),
        ),
        const SizedBox(height: 16),
        const Text(
          'Pembayaran Diproses',
          style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold),
        ),
        const SizedBox(height: 8),
        const Text(
          'Silakan selesaikan pembayaran sebelum batas waktu',
          textAlign: TextAlign.center,
          style: TextStyle(color: Colors.grey),
        ),
        const SizedBox(height: 24),
        Card(
          child: Padding(
            padding: const EdgeInsets.all(20),
            child: Column(
              children: [
                if (_paymentResult!['transaction_id'] != null)
                  _row('Transaction ID', _paymentResult!['transaction_id'].toString()),
                _row('Kode Bayar', _paymentResult!['code'] ?? ''),
                _row('Jumlah', AppFormatter.rupiah(
                    double.tryParse(_paymentResult!['amount'].toString()) ?? 0)),
                _row('Batas Waktu', _paymentResult!['limit'] ?? '-'),
              ],
            ),
          ),
        ),
        const SizedBox(height: 24),
        SizedBox(
          width: double.infinity,
          height: 48,
          child: OutlinedButton(
            onPressed: () => Navigator.pop(context),
            child: const Text('Kembali ke Beranda'),
          ),
        ),
      ],
    );
  }

  Widget _row(String label, String value, {bool isBold = false}) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 4),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Text(label, style: TextStyle(color: Colors.grey[600])),
          Text(
            value,
            style: TextStyle(
              fontWeight: isBold ? FontWeight.bold : FontWeight.w500,
              color: isBold ? AppTheme.primaryColor : null,
            ),
          ),
        ],
      ),
    );
  }
}
