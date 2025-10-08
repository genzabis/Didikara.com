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