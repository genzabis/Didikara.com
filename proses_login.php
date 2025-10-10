<?php
session_start();

// KERASIN COOKIE SESSION (lakukan ini sebelum session_start kalau mau atur global)
$secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => '',
    'secure' => $secure,
    'httponly' => true,
    'samesite' => 'Lax'
]);

// 0. TOLAK NON-POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php?error=' . rawurlencode('Metode tidak diizinkan'));
    exit();
}

// 1. KONEKSI DATABASE
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'db_didikara';
$mysqli = new mysqli($host, $user, $pass, $db);
if ($mysqli->connect_error) {
    // Jangan echo apa pun sebelum header
    header('Location: login.php?error=' . rawurlencode('Koneksi gagal'));
    exit();
}
$mysqli->set_charset('utf8mb4');

// 2. AMBIL DATA DARI FORM LOGIN
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$password = $_POST['password'] ?? '';

if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $email === '' || $password === '') {
    header('Location: login.php?error=' . rawurlencode('Email dan password wajib diisi'));
    exit();
}

// 3. CARI USER DI DATABASE
$sql = "SELECT id, full_name, email, role, province_id, district, password_hash
        FROM users WHERE email = ? LIMIT 1";
$stmt = $mysqli->prepare($sql);
if (!$stmt) {
    header('Location: login.php?error=' . rawurlencode('Terjadi kesalahan. Coba lagi.'));
    exit();
}
$stmt->bind_param('s', $email);
if (!$stmt->execute()) {
    header('Location: login.php?error=' . rawurlencode('Terjadi kesalahan. Coba lagi.'));
    exit();
}
$result = $stmt->get_result();

if ($result && $result->num_rows === 1) {
    $user_data = $result->fetch_assoc();

    // 4. VERIFIKASI PASSWORD
    if (password_verify($password, $user_data['password_hash'])) {
        // (Opsional) cek status aktif/verified di sini

        // 5. BUAT SESSION DENGAN SEMUA DATA PENTING
        session_regenerate_id(true);
        $_SESSION['user_id']     = $user_data['id'];
        $_SESSION['full_name']   = $user_data['full_name'];
        $_SESSION['email']       = $user_data['email'];
        $_SESSION['user_role']   = $user_data['role'];
        $_SESSION['province_id'] = $user_data['province_id'];
        $_SESSION['district']    = $user_data['district'];

        // 6. TENTUKAN FOLDER TUJUAN BERDASARKAN ROLE
        switch ($user_data['role']) {
            case 'admin':
                header('Location: ./admin/');
                break;
            case 'admin-daerah':
                header('Location: ./daerah/');
                break;
            case 'admin-wilayah':
                header('Location: ./wilayah/');
                break;
            default:
                // Role valid tapi bukan admin* → boleh jadikan error generik juga biar tidak bocor info
                header('Location: login.php?error=' . rawurlencode('Anda tidak memiliki hak akses admin'));
                break;
        }
        exit();
    }
}

// Jika email tidak ditemukan atau password salah (pesan generik anti-enumerasi)
header('Location: login.php?error=' . rawurlencode('Email atau password salah.'));
exit();
