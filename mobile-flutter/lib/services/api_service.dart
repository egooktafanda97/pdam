import 'package:dio/dio.dart';
import 'package:flutter_secure_storage/flutter_secure_storage.dart';
import '../config/api_config.dart';

class ApiService {
  late Dio _dio;
  final FlutterSecureStorage _storage = const FlutterSecureStorage();

  ApiService() {
    _dio = Dio(BaseOptions(
      baseUrl: ApiConfig.baseUrl,
      connectTimeout: const Duration(seconds: 15),
      receiveTimeout: const Duration(seconds: 15),
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
    ));

    _dio.interceptors.add(InterceptorsWrapper(
      onRequest: (options, handler) async {
        final token = await _storage.read(key: 'auth_token');
        if (token != null) {
          options.headers['Authorization'] = 'Bearer $token';
        }
        return handler.next(options);
      },
      onError: (error, handler) {
        if (error.response?.statusCode == 401) {
          _storage.delete(key: 'auth_token');
        }
        return handler.next(error);
      },
    ));
  }

  // ====== AUTH ====== //

  Future<Response> login(String username, String password) async {
    return _dio.post(ApiConfig.login, data: {
      'username': username,
      'password': password,
    });
  }

  Future<Response> logout() async {
    return _dio.post(ApiConfig.logout);
  }

  Future<Response> getMe() async {
    return _dio.get(ApiConfig.me);
  }

  // ====== DASHBOARD ====== //

  Future<Response> getDashboard() async {
    return _dio.get(ApiConfig.dashboard);
  }

  // ====== TAGIHAN ====== //

  Future<Response> getTagihan({String status = 'Semua', int page = 1}) async {
    return _dio.get(ApiConfig.tagihan, queryParameters: {
      'status': status,
      'page': page,
    });
  }

  Future<Response> getTagihanDetail(int id) async {
    return _dio.get('${ApiConfig.tagihan}/$id');
  }

  // ====== PEMBAYARAN ====== //

  Future<Response> bayar(int tagihanId, String paymentMethod) async {
    return _dio.post(ApiConfig.bayar(tagihanId), data: {
      'payment_method': paymentMethod,
    });
  }

  Future<Response> getRiwayat({int page = 1}) async {
    return _dio.get(ApiConfig.riwayat, queryParameters: {'page': page});
  }

  Future<Response> getPembayaranDetail(int id) async {
    return _dio.get(ApiConfig.pembayaranDetail(id));
  }

  Future<Response> cancelPembayaran(int id) async {
    return _dio.post(ApiConfig.cancelPembayaran(id));
  }

  // ====== PENGADUAN ====== //

  Future<Response> getPengaduan({int page = 1}) async {
    return _dio.get(ApiConfig.pengaduan, queryParameters: {'page': page});
  }

  Future<Response> storePengaduan({
    required String kategori,
    required String judulPengaduan,
    required String deskripsi,
  }) async {
    return _dio.post(ApiConfig.pengaduan, data: {
      'kategori': kategori,
      'judul_pengaduan': judulPengaduan,
      'deskripsi': deskripsi,
    });
  }

  Future<Response> getPengaduanDetail(int id) async {
    return _dio.get(ApiConfig.pengaduanDetail(id));
  }

  // ====== PROFIL ====== //

  Future<Response> getProfil() async {
    return _dio.get(ApiConfig.profil);
  }

  Future<Response> updatePassword({
    required String currentPassword,
    required String password,
    required String passwordConfirmation,
  }) async {
    return _dio.put(ApiConfig.updatePassword, data: {
      'current_password': currentPassword,
      'password': password,
      'password_confirmation': passwordConfirmation,
    });
  }
}
