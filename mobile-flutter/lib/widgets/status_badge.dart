import 'package:flutter/material.dart';
import '../config/theme.dart';

class StatusBadge extends StatelessWidget {
  final String status;

  const StatusBadge({super.key, required this.status});

  @override
  Widget build(BuildContext context) {
    Color bgColor;
    Color textColor;
    IconData icon;

    switch (status) {
      case 'Lunas':
      case 'Sukses':
      case 'Selesai':
        bgColor = AppTheme.successColor.withOpacity(0.15);
        textColor = AppTheme.successColor;
        icon = Icons.check_circle;
        break;
      case 'Pending':
      case 'Diproses':
        bgColor = AppTheme.warningColor.withOpacity(0.15);
        textColor = AppTheme.warningColor;
        icon = Icons.access_time;
        break;
      case 'Gagal':
        bgColor = AppTheme.dangerColor.withOpacity(0.15);
        textColor = AppTheme.dangerColor;
        icon = Icons.cancel;
        break;
      default: // Belum Bayar, Baru
        bgColor = AppTheme.infoColor.withOpacity(0.15);
        textColor = AppTheme.infoColor;
        icon = Icons.info;
    }

    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
      decoration: BoxDecoration(
        color: bgColor,
        borderRadius: BorderRadius.circular(20),
      ),
      child: Row(
        mainAxisSize: MainAxisSize.min,
        children: [
          Icon(icon, size: 14, color: textColor),
          const SizedBox(width: 4),
          Text(
            status,
            style: TextStyle(
              color: textColor,
              fontSize: 12,
              fontWeight: FontWeight.w600,
            ),
          ),
        ],
      ),
    );
  }
}
