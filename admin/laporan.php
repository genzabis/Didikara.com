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
        <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0 mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Laporan Masalah</h1>
            <div class="flex flex-col sm:flex-row sm:items-center space-y-2 sm:space-y-0 sm:space-x-2">
                <select class="w-full sm:w-auto px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="all">Semua Status</option>
                    <option value="pending">Menunggu Verifikasi</option>
                    <option value="verified">Terverifikasi</option>
                    <option value="in_progress">Dalam Proses</option>
                    <option value="resolved">Terselesaikan</option>
                    <option value="rejected">Ditolak</option>
                </select>
                <button class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                    Export Data
                </button>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow">
            <div class="p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0 mb-4">
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-table text-gray-500"></i>
                        <span class="text-gray-700 font-medium">Daftar Laporan</span>
                    </div>
                    <div class="flex items-center">
                        <input
                            type="text"
                            placeholder="Cari laporan..."
                            class="w-full md:w-auto px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 responsive-table">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    ID Laporan
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Sekolah
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Jenis Masalah
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tanggal
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr>
                                <td data-label="ID Laporan" class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    #LP12345
                                </td>
                                <td data-label="Sekolah" class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    SDN 1 Sukamaju
                                </td>
                                <td data-label="Jenis Masalah" class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    Fasilitas
                                </td>
                                <td data-label="Status" class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">
                                        Menunggu Verifikasi
                                    </span>
                                </td>
                                <td data-label="Tanggal" class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    2024-02-20
                                </td>
                                <td data-label="Aksi" class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <button class="text-indigo-600 hover:text-indigo-900 mr-3">
                                        Detail
                                    </button>
                                    <button class="text-green-600 hover:text-green-900 mr-3">
                                        Verifikasi
                                    </button>
                                    <button class="text-red-600 hover:text-red-900">
                                        Tolak
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td data-label="ID Laporan" class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    #LP12346
                                </td>
                                <td data-label="Sekolah" class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    SMPN 2 Harapan
                                </td>
                                <td data-label="Jenis Masalah" class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    Guru
                                </td>
                                <td data-label="Status" class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                                        Dalam Proses
                                    </span>
                                </td>
                                <td data-label="Tanggal" class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    2024-02-18
                                </td>
                                <td data-label="Aksi" class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <button class="text-indigo-600 hover:text-indigo-900 mr-3">
                                        Detail
                                    </button>
                                    <button class="text-amber-600 hover:text-amber-900">
                                        Update Status
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td data-label="ID Laporan" class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    #LP12347
                                </td>
                                <td data-label="Sekolah" class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    SMAN 3 Sejahtera
                                </td>
                                <td data-label="Jenis Masalah" class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    Teknologi
                                </td>
                                <td data-label="Status" class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                                        Terselesaikan
                                    </span>
                                </td>
                                <td data-label="Tanggal" class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    2024-02-15
                                </td>
                                <td data-label="Aksi" class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <button class="text-indigo-600 hover:text-indigo-900">
                                        Detail
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0 mt-4">
                    <div class="text-sm text-gray-700">
                        Menampilkan 1-10 dari 100 laporan
                    </div>
                    <div class="flex items-center space-x-2">
                        <button class="px-3 py-1 border border-gray-300 rounded-md text-sm">
                            Previous
                        </button>
                        <button class="px-3 py-1 border border-gray-300 rounded-md text-sm bg-indigo-50 text-indigo-600">
                            1
                        </button>
                        <button class="px-3 py-1 border border-gray-300 rounded-md text-sm">
                            2
                        </button>
                        <button class="px-3 py-1 border border-gray-300 rounded-md text-sm">
                            3
                        </button>
                        <button class="px-3 py-1 border border-gray-300 rounded-md text-sm">
                            Next
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>