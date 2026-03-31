class User {
  final int id;
  final String name;
  final String username;
  final String? role;
  final bool isActive;

  User({
    required this.id,
    required this.name,
    required this.username,
    this.role,
    required this.isActive,
  });

  factory User.fromJson(Map<String, dynamic> json) {
    return User(
      id: json['id'],
      name: json['name'] ?? '',
      username: json['username'] ?? '',
      role: json['role'],
      isActive: json['is_active'] == true || json['is_active'] == 1,
    );
  }
}
