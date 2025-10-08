<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - didikara.com</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
    <style>
        /* == MODIFIED CSS START == */

        /* Default (Desktop) Styles */
        .sidebar {
            transform: translateX(0);
            transition: transform 0.3s ease-in-out;
        }

        .sidebar.hidden {
            transform: translateX(-100%);
        }

        .main-content {
            margin-left: 16rem;
            /* w-64 */
            transition: margin-left 0.3s ease-in-out;
        }

        .main-content.full-width {
            margin-left: 0;
        }

        /* Mobile Styles */
        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
            }

            .sidebar {
                position: fixed;
                z-index: 50;
                height: 100vh;
                /* Sidebar hidden by default on mobile */
                transform: translateX(-100%);
            }

            /* New class to show sidebar on mobile */
            .sidebar.is-open {
                transform: translateX(0);
            }
        }

        /* == MODIFIED CSS END == */
    </style>
</head>

<body class="bg-gray-100">
    <?php
    require_once 'navbar.php';
    require_once 'route.php';
    ?>
    <script>
        // == MODIFIED SCRIPT START ==
        document.addEventListener('DOMContentLoaded', () => {
            const sidebarToggle = document.getElementById('sidebar-toggle');
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            const backdrop = document.getElementById('sidebar-backdrop');

            const closeMobileSidebar = () => {
                sidebar.classList.remove('is-open');
                backdrop.classList.add('hidden');
            };

            sidebarToggle.addEventListener('click', () => {
                if (window.innerWidth < 768) {
                    // Mobile behavior: toggle overlay
                    sidebar.classList.toggle('is-open');
                    backdrop.classList.toggle('hidden');
                } else {
                    // Desktop behavior: toggle push content
                    sidebar.classList.toggle('hidden');
                    mainContent.classList.toggle('full-width');
                }
            });

            // Close sidebar when clicking on the backdrop
            backdrop.addEventListener('click', closeMobileSidebar);

            // Handle window resize to clean up states
            window.addEventListener('resize', () => {
                if (window.innerWidth >= 768) {
                    // If we resize to desktop, ensure mobile overlay is closed
                    closeMobileSidebar();
                }
            });

            // Initialize chart
            const ctx = document.getElementById('reportsChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
                    datasets: [{
                        label: 'Laporan',
                        data: [65, 85, 120, 90, 110, 95],
                        backgroundColor: '#4F46E5',
                        borderColor: '#4F46E5',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
        // == MODIFIED SCRIPT END ==
    </script>
</body>

</html>