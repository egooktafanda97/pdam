import 'package:flutter/material.dart';
import 'package:flutter_secure_storage/flutter_secure_storage.dart';
import '../models/user.dart';
import '../models/pelanggan.dart';
import '../services/api_service.dart';

class AuthProvider extends ChangeNotifier {
  final ApiService _api = ApiService();
  final FlutterSecureStorage _storage = const FlutterSecureStorage();

  User? _user;
  Pelanggan? _pelanggan;
  bool _isLoading = false;
  bool _isLoggedIn = false;
  String? _errorMessage;

  User? get user => _user;
  Pelanggan? get pelanggan => _pelanggan;
  bool get isLoading => _isLoading;
  bool get isLoggedIn => _isLoggedIn;
  String? get errorMessage => _errorMessage;

  Future<bool> login(String username, String password) async {
    _isLoading = true;
    _errorMessage = null;
    notifyListeners();

    try {
      final response = await _api.login(username, password);
      final data = response.data;

      if (data['status'] == 'success') {
        await _storage.write(key: 'auth_token', value: data['token']);
        _user = User.fromJson(data['user']);
        if (data['pelanggan'] != null) {
          _pelanggan = Pelanggan.fromJson(data['pelanggan']);
        }
        _isLoggedIn = true;
        _isLoading = false;
        notifyListeners();
        return true;
      }
    } catch (e) {
      _errorMessage = _getErrorMessage(e);
    }

    _isLoading = false;
    notifyListeners();
    return false;
  }

  Future<bool> checkAuth() async {
    final token = await _storage.read(key: 'auth_token');
    if (token == null) return false;

    try {
      final response = await _api.getMe();
      final data = response.data;
      if (data['status'] == 'success') {
        _user = User.fromJson(data['user']);
        if (data['pelanggan'] != null) {
          _pelanggan = Pelanggan.fromJson(data['pelanggan']);
        }
        _isLoggedIn = true;
        notifyListeners();
        return true;
      }
    } catch (e) {
      await _storage.delete(key: 'auth_token');
    }

    return false;
  }

  Future<void> logout() async {
    try {
      await _api.logout();
    } catch (_) {}
    await _storage.delete(key: 'auth_token');
    _user = null;
    _pelanggan = null;
    _isLoggedIn = false;
    notifyListeners();
  }

  String _getErrorMessage(dynamic e) {
    if (e is Exception) {
      try {
        final dioErr = e as dynamic;
        if (dioErr.response?.data != null) {
          return dioErr.response.data['message'] ?? 'Terjadi kesalahan';
        }
      } catch (_) {}
    }
    return 'Tidak dapat terhubung ke server. Periksa koneksi Anda.';
  }
}
