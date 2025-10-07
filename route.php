<?php

$page = $_GET['page'] ?? 'home';

require_once 'navbar.php';

switch ($page) {
    case 'home':
        require_once 'dashboard.php';
        break;
    case 'about':
        require_once 'about.php';
        break;
    case 'services':
        require_once 'services.php';
        break;
    case 'contact':
        require_once 'contact.php';
        break;
    default:
        require_once '404.php';
        break;
}

require_once 'footer.php';