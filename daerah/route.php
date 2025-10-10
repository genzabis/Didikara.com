<?php

// Gunakan 'view' untuk memilih halaman, 'page' kita khususkan untuk nomor halaman paginasi
$view = $_GET['view'] ?? 'dashboard';
$page_number = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Ambil nomor halaman paginasi

require_once 'navbar.php'; // Ini adalah header/template Anda

switch ($view) {
    case 'dashboard':
        require_once 'dashboard.php';
        break;
    case 'laporan':
        require_once 'laporan.php';
        break;
    case 'article':
        require_once 'article.php';
        break;
    case 'pengaturan':
        require_once 'pengaturan.php';
        break;
    default:
        require_once '404.php';
        break;
}