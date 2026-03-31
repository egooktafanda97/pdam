import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../../providers/auth_provider.dart';
import '../../services/api_service.dart';
import '../../config/theme.dart';

class ProfileScreen extends StatefulWidget {
  const ProfileScreen({super.key});

  @override
  State<ProfileScreen> createState() => _ProfileScreenState();
}

class _ProfileScreenState extends State<ProfileScreen> {
  @override
  Widget build(BuildContext context) {
    final auth = Provider.of<AuthProvider>(context);
    final pelanggan = auth.pelanggan;

    return SingleChildScrollView(
      padding: const EdgeInsets.all(16),
      child: Column(
        children: [
          // Avatar & Name
          Container(
            width: double.infinity,
            padding: const EdgeInsets.all(24),
            decoration: BoxDecoration(
              gradient: AppTheme.primaryGradient,
              borderRadius: BorderRadius.circular(20),
            ),
            child: Column(
              children: [
                Container(
                  padding: const EdgeInsets.all(16),
                  decoration: BoxDecoration(
                    color: Colors.white.withOpacity(0.2),
                    shape: BoxShape.circle,
                  ),
                  child: const Icon(Icons.person, color: Colors.white, size: 48),
                ),
                const SizedBox(height: 12),
                Text(
                  pelanggan?.nama ?? '-',
                  style: const TextStyle(
                    color: Colors.white,
                    fontSize: 20,
                    fontWeight: FontWeight.bold,
                  ),
                ),
                const SizedBox(height: 4),
                Text(
                  'No. ${pelanggan?.nomorPelanggan ?? '-'}',
                  style: TextStyle(color: Colors.white.withOpacity(0.85)),
                ),
              ],
            ),
          ),
          const SizedBox(height: 20),

          // Info cards
          Card(
            child: Padding(
              padding: const EdgeInsets.all(16),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const Text('Informasi Pelanggan',
                      style: TextStyle(fontWeight: FontWeight.bold, fontSize: 16)),
                  const Divider(height: 20),
                  _infoTile(Icons.badge, 'Nomor Pelanggan', pelanggan?.nomorPelanggan ?? '-'),
                  _infoTile(Icons.location_on, 'Alamat', pelanggan?.alamat ?? '-'),
                  _infoTile(Icons.speed, 'Nomor Meter', pelanggan?.nomorMeter ?? '-'),
                  _infoTile(Icons.phone, 'No. Telepon', pelanggan?.noTelepon ?? '-'),
                  _infoTile(Icons.category, 'Golongan Tarif',
                      pelanggan?.golonganTarif?.namaGolongan ?? '-'),
                ],
              ),
            ),
          ),
          const SizedBox(height: 12),

          // Actions
          Card(
            child: Column(
              children: [
                ListTile(
                  leading: const Icon(Icons.lock_outline, color: AppTheme.primaryColor),
                  title: const Text('Ubah Password'),
                  trailing: const Icon(Icons.chevron_right),
                  onTap: () => _showChangePassword(context),
                ),
                const Divider(height: 1),
                ListTile(
                  leading: const Icon(Icons.logout, color: AppTheme.dangerColor),
                  title: const Text('Keluar', style: TextStyle(color: AppTheme.dangerColor)),
                  onTap: () => _confirmLogout(context, auth),
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }

  Widget _infoTile(IconData icon, String label, String value) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 6),
      child: Row(
        children: [
          Icon(icon, size: 20, color: Colors.grey[600]),
          const SizedBox(width: 12),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(label, style: TextStyle(fontSize: 12, color: Colors.grey[500])),
                Text(value, style: const TextStyle(fontWeight: FontWeight.w500)),
              ],
            ),
          ),
        ],
      ),
    );
  }

  void _showChangePassword(BuildContext context) {
    final currentCtrl = TextEditingController();
    final newCtrl = TextEditingController();
    final confirmCtrl = TextEditingController();
    final formKey = GlobalKey<FormState>();

    showDialog(
      context: context,
      builder: (ctx) {
        bool isSubmitting = false;
        return StatefulBuilder(builder: (ctx, setDialogState) {
          return AlertDialog(
            title: const Text('Ubah Password'),
            content: Form(
              key: formKey,
              child: Column(
                mainAxisSize: MainAxisSize.min,
                children: [
                  TextFormField(
                    controller: currentCtrl,
                    obscureText: true,
                    decoration: const InputDecoration(labelText: 'Password Saat Ini'),
                    validator: (v) =>
                        v == null || v.isEmpty ? 'Wajib diisi' : null,
                  ),
                  const SizedBox(height: 10),
                  TextFormField(
                    controller: newCtrl,
                    obscureText: true,
                    decoration: const InputDecoration(labelText: 'Password Baru'),
                    validator: (v) =>
                        v != null && v.length < 8 ? 'Minimal 8 karakter' : null,
                  ),
                  const SizedBox(height: 10),
                  TextFormField(
                    controller: confirmCtrl,
                    obscureText: true,
                    decoration:
                        const InputDecoration(labelText: 'Konfirmasi Password Baru'),
                    validator: (v) =>
                        v != newCtrl.text ? 'Password tidak cocok' : null,
                  ),
                ],
              ),
            ),
            actions: [
              TextButton(
                onPressed: () => Navigator.pop(ctx),
                child: const Text('Batal'),
              ),
              ElevatedButton(
                onPressed: isSubmitting
                    ? null
                    : () async {
                        if (!formKey.currentState!.validate()) return;
                        setDialogState(() => isSubmitting = true);
                        try {
                          await ApiService().updatePassword(
                            currentPassword: currentCtrl.text,
                            password: newCtrl.text,
                            passwordConfirmation: confirmCtrl.text,
                          );
                          if (context.mounted) {
                            Navigator.pop(ctx);
                            ScaffoldMessenger.of(context).showSnackBar(
                              const SnackBar(
                                content: Text('Password berhasil diperbarui'),
                                backgroundColor: AppTheme.successColor,
                              ),
                            );
                          }
                        } catch (e) {
                          if (context.mounted) {
                            ScaffoldMessenger.of(context).showSnackBar(
                              const SnackBar(
                                content: Text('Gagal memperbarui password'),
                              ),
                            );
                          }
                        }
                        setDialogState(() => isSubmitting = false);
                      },
                child: const Text('Simpan'),
              ),
            ],
          );
        });
      },
    );
  }

  void _confirmLogout(BuildContext context, AuthProvider auth) {
    showDialog(
      context: context,
      builder: (ctx) {
        return AlertDialog(
          title: const Text('Konfirmasi'),
          content: const Text('Apakah Anda yakin ingin keluar?'),
          actions: [
            TextButton(
              onPressed: () => Navigator.pop(ctx),
              child: const Text('Batal'),
            ),
            ElevatedButton(
              style: ElevatedButton.styleFrom(backgroundColor: AppTheme.dangerColor),
              onPressed: () async {
                Navigator.pop(ctx);
                await auth.logout();
                if (context.mounted) {
                  Navigator.pushReplacementNamed(context, '/login');
                }
              },
              child: const Text('Keluar', style: TextStyle(color: Colors.white)),
            ),
          ],
        );
      },
    );
  }
}
