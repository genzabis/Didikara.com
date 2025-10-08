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
    <?php include 'navbar.php'; ?>

    <div id="sidebar-backdrop" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden md:hidden"></div>

    <div id="main-content" class="main-content">
        <header class="bg-white shadow-sm">
            <div class="flex items-center justify-between px-6 py-4">
                <button id="sidebar-toggle" class="text-gray-500 hover:text-gray-700 focus:outline-none">
                    <i class="fas fa-bars text-2xl"></i>
                </button>

                <div class="flex items-center space-x-4">
                    <button class="text-gray-500 hover:text-gray-700 relative">
                        <i class="fas fa-bell text-xl"></i>
                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-4 h-4 flex items-center justify-center">
                            3
                        </span>
                    </button>

                    <div class="flex items-center space-x-2">
                        <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-indigo-600"></i>
                        </div>
                        <div class="text-sm">
                            <p class="font-medium text-gray-700">Admin User</p>
                            <p class="text-gray-500 text-xs">admin@didikara.com</p>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <main class="p-6">
            <div class="space-y-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
                    <h1 class="text-2xl font-bold text-gray-800">Dashboard</h1>
                    <div class="mt-2 md:mt-0 flex items-center space-x-2">
                        <span class="text-sm text-gray-500">Terakhir diperbarui:</span>
                        <span class="text-sm font-medium">10 Mar 2024, 15:30 WIB</span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-gray-500 text-sm">Total Laporan</p>
                                <h3 class="text-2xl font-bold text-gray-800 mt-1">2,547</h3>
                                <div class="flex items-center mt-2">
                                    <i class="fas fa-arrow-up text-green-500 mr-1"></i>
                                    <span class="text-green-500 text-sm">+12.5%</span>
                                </div>
                            </div>
                            <div class="bg-indigo-50 p-3 rounded-lg">
                                <i class="fas fa-file-alt text-indigo-600 text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-gray-500 text-sm">Sekolah Terpantau</p>
                                <h3 class="text-2xl font-bold text-gray-800 mt-1">1,250</h3>
                                <div class="flex items-center mt-2">
                                    <i class="fas fa-arrow-up text-green-500 mr-1"></i>
                                    <span class="text-green-500 text-sm">+8.2%</span>
                                </div>
                            </div>
                            <div class="bg-indigo-50 p-3 rounded-lg">
                                <i class="fas fa-school text-amber-600 text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-gray-500 text-sm">Kontributor Aktif</p>
                                <h3 class="text-2xl font-bold text-gray-800 mt-1">560</h3>
                                <div class="flex items-center mt-2">
                                    <i class="fas fa-arrow-up text-green-500 mr-1"></i>
                                    <span class="text-green-500 text-sm">+15.3%</span>
                                </div>
                            </div>
                            <div class="bg-indigo-50 p-3 rounded-lg">
                                <i class="fas fa-users text-teal-600 text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-gray-500 text-sm">Provinsi Terjangkau</p>
                                <h3 class="text-2xl font-bold text-gray-800 mt-1">28</h3>
                                <div class="flex items-center mt-2">
                                    <i class="fas fa-arrow-up text-green-500 mr-1"></i>
                                    <span class="text-green-500 text-sm">+2</span>
                                </div>
                            </div>
                            <div class="bg-indigo-50 p-3 rounded-lg">
                                <i class="fas fa-map text-purple-600 text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-lg font-semibold text-gray-800">Tren Laporan</h2>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-chart-bar text-gray-400"></i>
                                <span class="text-sm text-gray-500">6 bulan terakhir</span>
                            </div>
                        </div>
                        <div class="h-80">
                            <canvas id="reportsChart"></canvas>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-lg font-semibold text-gray-800">Status Laporan</h2>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-exclamation-circle text-gray-400"></i>
                                <span class="text-sm text-gray-500">Status terkini</span>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2">
                                    <div class="w-3 h-3 bg-amber-500 rounded-full"></div>
                                    <span class="text-gray-600">Menunggu Tindakan</span>
                                </div>
                                <span class="font-semibold">42%</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2">
                                    <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                                    <span class="text-gray-600">Dalam Proses</span>
                                </div>
                                <span class="font-semibold">35%</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2">
                                    <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                    <span class="text-gray-600">Terselesaikan</span>
                                </div>
                                <span class="font-semibold">23%</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm">
                    <div class="p-6 border-b">
                        <h2 class="text-lg font-semibold text-gray-800">Laporan Terbaru</h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Sekolah
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Masalah
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tanggal
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm font-medium text-gray-900">SDN 1 Sukamaju</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm text-gray-500">Atap Bocor</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                            Menunggu
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        2024-02-20
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm font-medium text-gray-900">SMPN 2 Harapan</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm text-gray-500">Kekurangan Guru</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            Diproses
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        2024-02-18
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm font-medium text-gray-900">SMAN 3 Sejahtera</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm text-gray-500">Internet Lambat</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Selesai
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        2024-02-15
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

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