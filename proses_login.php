<?php
session_start();

// 1. KONEKSI DATABASE
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'db_didikara';
$mysqli = new mysqli($host, $user, $pass, $db);
if ($mysqli->connect_error) {
    die("Koneksi gagal");
}

// 2. AMBIL DATA DARI FORM LOGIN
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
    header('Location: login.php?error=Email dan password wajib diisi');
    exit();
}

// 3. CARI USER DI DATABASE
$sql = "SELECT * FROM users WHERE email = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('s', $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user_data = $result->fetch_assoc();
    
    // 4. VERIFIKASI PASSWORD
    if (password_verify($password, $user_data['password_hash'])) {
        
        // 5. BUAT SESSION DENGAN SEMUA DATA PENTING
        $_SESSION['user_id'] = $user_data['id'];
        $_SESSION['full_name'] = $user_data['full_name'];
        $_SESSION['email'] = $user_data['email'];
        $_SESSION['user_role'] = $user_data['role'];
        $_SESSION['province_id'] = $user_data['province_id'];
        $_SESSION['district'] = $user_data['district'];
        
        // 6. TENTUKAN FOLDER TUJUAN BERDASARKAN ROLE
        switch ($user_data['role']) {
            case 'admin': // <-- INI PERBAIKANNYA
                // Arahkan ke folder /admin/
                header('Location: admin/'); 
                break;
            case 'admin-daerah':
                // Arahkan ke folder /daerah/
                header('Location: daerah/');
                break;
            case 'admin-wilayah':
                // Arahkan ke folder /wilayah/
                header('Location: wilayah/');
                break;
            default:
                // Jika role tidak sesuai (bukan admin), tendang kembali
                header('Location: login.php?error=Anda tidak memiliki hak akses admin');
                break;
        }
        exit(); // Hentikan eksekusi setelah redirect
    }
}

// Jika email tidak ditemukan atau password salah
header('Location: login.php?error=Email atau password salah.');
exit();
?>