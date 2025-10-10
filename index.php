<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Didikara.com - Data Aksi Digital Untuk Pendidikan Nusantara</title>
    <link rel="icon" type="image/png" href="./assets/img/favicon.png" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">

    <!-- Leaflet -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'indigo': {
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            200: '#c7d2fe',
                            300: '#a5b4fc',
                            400: '#818cf8',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                            800: '#3730a3',
                            900: '#312e81',
                        }
                    }
                }
            }
        }
    </script>

    <link
        rel="stylesheet"
        href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
        crossorigin="" />
    <script
        src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin=""></script>

    <style>
        /* ===== Animations ===== */
        .animate-fadeIn {
            animation: fadeIn .5s ease-in-out;
        }

        .animate-slideIn {
            animation: slideIn .6s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* ===== Effects ===== */
        .hover-lift {
            transition: transform .3s ease;
        }

        .hover-lift:hover {
            transform: translateY(-5px);
        }

        /* variant kecil sesuai request terbaru */
        .hover-lift-sm {
            transition: transform .2s ease;
        }

        .hover-lift-sm:hover {
            transform: translateY(-2px);
        }

        .gradient-bg {
            background: linear-gradient(135deg, #312e81 0%, #4338ca 100%);
        }

        .card-shadow {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, .10), 0 2px 4px -1px rgba(0, 0, 0, .06);
        }

        .card-shadow:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, .10), 0 4px 6px -2px rgba(0, 0, 0, .05);
        }

        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .line-clamp-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* ===== Navbar & Mobile Menu ===== */
        .navbar-scrolled {
            background-color: rgba(255, 255, 255, .95);
            backdrop-filter: blur(10px);
            box-shadow: 0 1px 3px rgba(0, 0, 0, .1);
        }

        #navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 10000 !important;
        }

        #mobile-menu {
            z-index: 10001 !important;
        }

        .mobile-menu {
            transform: translateY(-100%);
            transition: transform .3s ease-in-out;
        }

        .mobile-menu.active {
            transform: translateY(0);
        }

        /* ===== Step Form ===== */
        .step-indicator {
            transition: all .3s ease;
        }

        .step-indicator.active {
            background-color: #4f46e5;
            color: #fff;
        }

        .form-step {
            display: none;
        }

        .form-step.active {
            display: block;
            animation: fadeIn .3s ease-in-out;
        }

        /* ===== Leaflet Overrides ===== */
        .leaflet-container {
            font-family: ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
            z-index: 0 !important;
        }

        .leaflet-pane,
        .leaflet-top,
        .leaflet-bottom {
            z-index: 0 !important;
        }

        .leaflet-pane.leaflet-popup-pane {
            z-index: 500 !important;
        }

        .leaflet-top {
            margin-top: 72px;
        }

        @media (max-width:767px) {
            .leaflet-top {
                margin-top: 64px;
            }
        }

        /* ===== Badges ===== */
        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 9999px;
            font-size: .75rem;
            font-weight: 600;
        }

        .badge-status-pending {
            color: #92400e;
            background: #fef3c7;
        }

        .badge-status-inprog {
            color: #1e40af;
            background: #dbeafe;
        }

        .badge-status-resolved {
            color: #065f46;
            background: #ccfbf1;
        }
    </style>

</head>

<body class="bg-slate-50">
    <!-- Main Content -->

    <?php
    require_once './users/route.php';

    ?>

<!-- //     <script src="assets/js/script.js"></script> -->
</body>

</html>