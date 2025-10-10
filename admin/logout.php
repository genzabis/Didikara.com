<?php
// Selalu mulai session di baris paling atas
session_start();

// 1. Hapus semua variabel session
$_SESSION = array();

// 2. Hancurkan session
session_destroy();

// 3. Alihkan (redirect) pengguna ke halaman login
// Kita gunakan ../login.php karena diasumsikan login.php berada di luar folder admin
header('Location: ../login.php');
exit();
?>