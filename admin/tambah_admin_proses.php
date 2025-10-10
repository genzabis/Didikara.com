<?php
session_start();

// Keamanan: Pastikan hanya admin pusat yang bisa menambah user
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    // Jika tidak punya hak akses, hentikan eksekusi
    die("Akses ditolak. Anda tidak memiliki izin untuk melakukan tindakan ini.");
}

// 1. KONEKSI DATABASE
$host = 'localhost'; 
$user = 'root'; 
$pass = ''; 
$db = 'db_didikara';
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
$full_name = $_POST['full_name'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$role = $_POST['role'] ?? '';
$province_id = !empty($_POST['province_id']) ? (int)$_POST['province_id'] : null;
$district = !empty($_POST['district']) ? trim($_POST['district']) : null;

// Validasi dasar
if (empty($full_name) || empty($email) || empty($password) || empty($role)) {
    header('Location: index.php?view=admin&error=Semua data wajib diisi');
    exit();
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