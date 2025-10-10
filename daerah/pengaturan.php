 <div id="sidebar-backdrop" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden md:hidden"></div>
 <div id="main-content" class="main-content">
     <header class="bg-white shadow-sm">
        <div class="flex items-center justify-between px-6 py-4">
            <button id="sidebar-toggle" class="text-gray-500 hover:text-gray-700 focus:outline-none">
                <i class="fas fa-bars text-2xl"></i>
            </button>
            <div class="flex items-center space-x-4">
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
     <!-- Settings Content -->
     <main class="p-6">
         <div class="mb-6">
             <h1 class="text-2xl font-bold text-gray-900">Pengaturan</h1>
             <p class="text-gray-600">Kelola pengaturan platform dan preferensi admin</p>
         </div>

         <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
             <!-- General Settings -->
             <div class="col-span-2">
                 <div class="bg-white rounded-lg shadow">
                     <div class="p-6">
                         <div class="flex items-center space-x-2 mb-6">
                             <i class="fas fa-cog text-gray-500"></i>
                             <h2 class="text-lg font-medium text-gray-900">Pengaturan Umum</h2>
                         </div>

                         <div class="space-y-6">
                             <div>
                                 <label class="block text-sm font-medium text-gray-700 mb-1">
                                     Nama Platform
                                 </label>
                                 <input
                                     type="text"
                                     value="didikara.com"
                                     class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                             </div>

                             <div>
                                 <label class="block text-sm font-medium text-gray-700 mb-1">
                                     Email Kontak
                                 </label>
                                 <input
                                     type="email"
                                     value="admin@didikara.com"
                                     class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                             </div>

                             <div>
                                 <label class="block text-sm font-medium text-gray-700 mb-1">
                                     Zona Waktu
                                 </label>
                                 <select class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                     <option value="Asia/Jakarta">Asia/Jakarta (WIB)</option>
                                     <option value="Asia/Makassar">Asia/Makassar (WITA)</option>
                                     <option value="Asia/Jayapura">Asia/Jayapura (WIT)</option>
                                 </select>
                             </div>
                         </div>
                     </div>
                 </div>

                 <!-- Notification Settings -->
                 <div class="bg-white rounded-lg shadow mt-6">
                     <div class="p-6">
                         <div class="flex items-center space-x-2 mb-6">
                             <i class="fas fa-bell text-gray-500"></i>
                             <h2 class="text-lg font-medium text-gray-900">Pengaturan Notifikasi</h2>
                         </div>

                         <div class="space-y-4">
                             <div class="flex items-center justify-between">
                                 <div>
                                     <h3 class="text-sm font-medium text-gray-900">Laporan Baru</h3>
                                     <p class="text-sm text-gray-500">
                                         Notifikasi saat ada laporan baru masuk
                                     </p>
                                 </div>
                                 <label class="flex items-center">
                                     <input type="checkbox" class="form-checkbox h-4 w-4 text-indigo-600" checked />
                                 </label>
                             </div>

                             <div class="flex items-center justify-between">
                                 <div>
                                     <h3 class="text-sm font-medium text-gray-900">Pendaftaran Kontributor</h3>
                                     <p class="text-sm text-gray-500">
                                         Notifikasi saat ada pendaftaran kontributor baru
                                     </p>
                                 </div>
                                 <label class="flex items-center">
                                     <input type="checkbox" class="form-checkbox h-4 w-4 text-indigo-600" checked />
                                 </label>
                             </div>

                             <div class="flex items-center justify-between">
                                 <div>
                                     <h3 class="text-sm font-medium text-gray-900">Update Status Laporan</h3>
                                     <p class="text-sm text-gray-500">
                                         Notifikasi saat ada perubahan status laporan
                                     </p>
                                 </div>
                                 <label class="flex items-center">
                                     <input type="checkbox" class="form-checkbox h-4 w-4 text-indigo-600" />
                                 </label>
                             </div>
                         </div>
                     </div>
                 </div>
             </div>

             <!-- Sidebar Settings -->
             <div class="space-y-6">
                 <!-- Security Settings -->
                 <div class="bg-white rounded-lg shadow">
                     <div class="p-6">
                         <div class="flex items-center space-x-2 mb-6">
                             <i class="fas fa-shield-alt text-gray-500"></i>
                             <h2 class="text-lg font-medium text-gray-900">Keamanan</h2>
                         </div>

                         <div class="space-y-4">
                             <button class="w-full px-4 py-2 text-sm text-indigo-600 border border-indigo-600 rounded-md hover:bg-indigo-50">
                                 Ubah Password
                             </button>
                             <button class="w-full px-4 py-2 text-sm text-indigo-600 border border-indigo-600 rounded-md hover:bg-indigo-50">
                                 Aktifkan 2FA
                             </button>
                         </div>
                     </div>
                 </div>

                 <!-- Email Templates -->
                 <div class="bg-white rounded-lg shadow">
                     <div class="p-6">
                         <div class="flex items-center space-x-2 mb-6">
                             <i class="fas fa-envelope text-gray-500"></i>
                             <h2 class="text-lg font-medium text-gray-900">Template Email</h2>
                         </div>

                         <div class="space-y-4">
                             <button class="w-full px-4 py-2 text-sm text-indigo-600 border border-indigo-600 rounded-md hover:bg-indigo-50">
                                 Email Verifikasi
                             </button>
                             <button class="w-full px-4 py-2 text-sm text-indigo-600 border border-indigo-600 rounded-md hover:bg-indigo-50">
                                 Notifikasi Laporan
                             </button>
                             <button class="w-full px-4 py-2 text-sm text-indigo-600 border border-indigo-600 rounded-md hover:bg-indigo-50">
                                 Update Status
                             </button>
                         </div>
                     </div>
                 </div>
             </div>
         </div>

         <div class="mt-6 flex justify-end space-x-3">
             <button class="px-4 py-2 text-sm text-gray-700 border border-gray-300 rounded-md hover:bg-gray-50">
                 Batal
             </button>
             <button class="px-4 py-2 text-sm text-white bg-indigo-600 rounded-md hover:bg-indigo-700">
                 Simpan Perubahan
             </button>
         </div>
     </main>
 </div>
 <script>
     // Sidebar toggle
     const sidebarToggle = document.getElementById('sidebar-toggle');
     const sidebar = document.getElementById('sidebar');
     const mainContent = document.getElementById('main-content');
     let sidebarOpen = true;

     sidebarToggle.addEventListener('click', () => {
         sidebarOpen = !sidebarOpen;

         if (window.innerWidth >= 768) {
             if (sidebarOpen) {
                 sidebar.classList.remove('hidden');
                 mainContent.classList.remove('full-width');
             } else {
                 sidebar.classList.add('hidden');
                 mainContent.classList.add('full-width');
             }
         } else {
             sidebar.classList.toggle('hidden');
         }
     });

     // Handle window resize
     window.addEventListener('resize', () => {
         if (window.innerWidth < 768) {
             mainContent.classList.add('full-width');
         } else if (sidebarOpen) {
             mainContent.classList.remove('full-width');
         }
     });
 </script>