<?php
session_start();

// Keamanan: Pastikan hanya admin pusat yang bisa menambah user
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    // Jika tidak punya hak akses, hentikan eksekusi
    die("Akses ditolak. Anda tidak memiliki izin untuk melakukan tindakan ini.");
}

// 1. KONEKSI DATABASE
$host = 'localhost';
$user = 'argtgbgt_db_didikara'; // User default XAMPP/MariaDB
$pass = 'pWK^hRLZJ-V64CQs';     // Password default XAMPP/MariaDB kosong
$db   = 'argtgbgt_db_ddkr';
$mysqli = new mysqli($host, $user, $pass, $db);
if ($mysqli->connect_error) { 
    die("Koneksi gagal: " . $mysqli->connect_error); 
}

// 2. PASTIKAN METODE ADALAH POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php?view=admin');
    exit();
}

// 3. AMBIL DAN VALIDASI DATA DARI FORM
$full_name = isset($_POST['full_name']) ? trim($_POST['full_name']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$password = $_POST['password'] ?? '';
$role = isset($_POST['role']) ? trim($_POST['role']) : '';
$province_id = isset($_POST['province_id']) && $_POST['province_id'] !== '' ? (int)$_POST['province_id'] : null;
$district = isset($_POST['district']) && $_POST['district'] !== '' ? trim($_POST['district']) : null;

// Validasi dasar
if ($full_name === '' || $email === '' || $password === '' || $role === '') {
    header('Location: index.php?view=admin&error=' . urlencode('Semua data wajib diisi'));
    exit();
}

// Validasi format email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: index.php?view=admin&error=' . urlencode('Format email tidak valid'));
    exit();
}

// Validasi panjang password minimal 8 karakter
if (strlen($password) < 8) {
    header('Location: index.php?view=admin&error=' . urlencode('Password minimal 8 karakter'));
    exit();
}

// Whitelist peran yang diizinkan
$allowed_roles = ['admin', 'admin_wilayah'];
if (!in_array($role, $allowed_roles, true)) {
    header('Location: index.php?view=admin&error=' . urlencode('Peran tidak diizinkan'));
    exit();
}

// Sanitasi district bila ada (huruf, angka, spasi, dash, koma, titik)
if ($district !== null) {
    $district = substr($district, 0, 100);
    if (!preg_match('/^[\p{L}0-9\s\-\.,]+$/u', $district)) {
        header('Location: index.php?view=admin&error=' . urlencode('Nama kecamatan/kabupaten tidak valid'));
        exit();
    }
}

// 4. HASH PASSWORD UNTUK KEAMANAN
$password_hash = password_hash($password, PASSWORD_BCRYPT);

// 5. SIAPKAN DAN JALANKAN QUERY INSERT
$sql = "INSERT INTO users (full_name, email, password_hash, role, province_id, district) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $mysqli->prepare($sql);

// Sesuaikan nilai null untuk data lokasi berdasarkan peran
if ($role === 'admin') {
    $province_id = null;
    $district = null;
} elseif ($role === 'admin_wilayah') {
    $district = null; // Admin wilayah hanya terikat pada provinsi
    // Validasi: province_id wajib untuk admin_wilayah
    if ($province_id === null || $province_id <= 0) {
        header('Location: index.php?view=admin&error=' . urlencode('Provinsi wajib diisi untuk Admin Wilayah'));
        exit();
    }
}

// Pastikan prepare berhasil
if ($stmt === false) {
    header('Location: index.php?view=admin&error=' . urlencode('Gagal menyiapkan penyimpanan data'));
    exit();
}

$stmt->bind_param('ssssis', $full_name, $email, $password_hash, $role, $province_id, $district);

if ($stmt->execute()) {
    // Jika berhasil, kembali ke halaman admin dengan pesan sukses
    header('Location: index.php?view=admin&success=Admin baru berhasil ditambahkan');
} else {
    // Jika gagal (misal: email sudah ada), kembali dengan pesan error
    // Kode 1062 adalah untuk error duplicate entry
    if ($stmt->errno == 1062) {
        $error_message = 'Email sudah terdaftar. Silakan gunakan email lain.';
    } else {
        $error_message = 'Terjadi kesalahan saat menyimpan data.';
    }
    header('Location: index.php?view=admin&error=' . urlencode($error_message));
}

$stmt->close();
$mysqli->close();
exit();
?>