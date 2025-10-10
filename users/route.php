<?php

$page = $_GET['page'] ?? 'home';

require_once 'navbar.php';

switch ($page) {
    case 'home':
        require_once 'home/dashboard.php';
        break;
    case 'map':
        require_once 'map/map.php';
        break;
    case 'report':
        require_once 'report/report.php';
        break;
    case 'search':
        require_once 'search/search.php';
        break;
    case 'news':
        require_once 'news/news.php';
        break;
    case 'about':
        require_once 'about/about.php';
        break;
    case 'news-detail':
        require_once 'news/news_detail.php';
        break;
    case 'detail-report':
        require_once 'report/report_detail.php';
        break;
    default:
        require_once '404.php';
        break;
}

require_once 'footer.php';