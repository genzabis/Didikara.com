<?php

$page = $_GET['page'] ?? 'dashboard';

require_once 'navbar.php';
switch ($page) {
    case 'dashboard':
        require_once 'dashboard.php';
        break;
    case 'laporan':
        require_once 'laporan.php';
        break;
    case 'admin':
        require_once 'admin.php';
        break;
    case 'pengaturan':
        require_once 'pengaturan.php';
        break;
    default:
        require_once '404.php';
        break;
}