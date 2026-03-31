import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'config/theme.dart';
import 'providers/auth_provider.dart';
import 'screens/splash_screen.dart';
import 'screens/login_screen.dart';
import 'screens/home_screen.dart';
import 'screens/pembayaran/pembayaran_screen.dart';
import 'screens/pengaduan/pengaduan_list_screen.dart';
import 'screens/pengaduan/pengaduan_create_screen.dart';
import 'models/tagihan.dart';

void main() {
  runApp(const PdamApp());
}

class PdamApp extends StatelessWidget {
  const PdamApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MultiProvider(
      providers: [
        ChangeNotifierProvider(create: (_) => AuthProvider()),
      ],
      child: MaterialApp(
        title: 'PDAM Mobile',
        debugShowCheckedModeBanner: false,
        theme: AppTheme.lightTheme,
        initialRoute: '/',
        routes: {
          '/': (context) => const SplashScreen(),
          '/login': (context) => const LoginScreen(),
          '/home': (context) => const HomeScreen(),
          '/pengaduan': (context) => const PengaduanListScreen(),
          '/pengaduan/create': (context) => const PengaduanCreateScreen(),
        },
        onGenerateRoute: (settings) {
          // Route pembayaran dengan argument Tagihan
          if (settings.name == '/pembayaran') {
            final tagihan = settings.arguments as Tagihan;
            return MaterialPageRoute(
              builder: (context) => PembayaranScreen(tagihan: tagihan),
            );
          }
          return null;
        },
      ),
    );
  }
}
