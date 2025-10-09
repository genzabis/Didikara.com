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
         <div class="flex items-center justify-between mb-6">
             <h1 class="text-2xl font-bold text-gray-900">Pengguna</h1>
             <button class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                 Tambah Pengguna
             </button>
         </div>

         <div class="bg-white rounded-lg shadow">
             <div class="p-6">
                 <div class="flex items-center justify-between mb-4">
                     <div class="flex items-center space-x-2">
                         <i class="fas fa-users text-gray-500"></i>
                         <span class="text-gray-700 font-medium">Daftar Pengguna</span>
                     </div>
                     <div class="flex items-center space-x-2">
                         <select class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                             <option value="all">Semua Peran</option>
                             <option value="admin">Admin</option>
                             <option value="contributor">Kontributor</option>
                             <option value="reporter">Pelapor</option>
                         </select>
                         <input
                             type="text"
                             placeholder="Cari pengguna..."
                             class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                     </div>
                 </div>

                 <div class="overflow-x-auto">
                     <table class="min-w-full divide-y divide-gray-200">
                         <thead>
                             <tr>
                                 <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                     Nama
                                 </th>
                                 <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                     Email
                                 </th>
                                 <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                     Peran
                                 </th>
                                 <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                     Status
                                 </th>
                                 <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                     Bergabung
                                 </th>
                                 <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                     Aksi
                                 </th>
                             </tr>
                         </thead>
                         <tbody class="bg-white divide-y divide-gray-200">
                             <tr>
                                 <td class="px-6 py-4 whitespace-nowrap">
                                     <div class="flex items-center">
                                         <div class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center">
                                             <span class="text-sm font-medium text-gray-600">AS</span>
                                         </div>
                                         <div class="ml-4">
                                             <div class="text-sm font-medium text-gray-900">
                                                 Ahmad Sulaiman
                                             </div>
                                         </div>
                                     </div>
                                 </td>
                                 <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                     ahmad.s@example.com
                                 </td>
                                 <td class="px-6 py-4 whitespace-nowrap">
                                     <span class="px-2 py-1 text-xs font-medium rounded-full bg-indigo-100 text-indigo-800">
                                         Kontributor
                                     </span>
                                 </td>
                                 <td class="px-6 py-4 whitespace-nowrap">
                                     <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                                         Aktif
                                     </span>
                                 </td>
                                 <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                     2024-01-15
                                 </td>
                                 <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                     <button class="text-indigo-600 hover:text-indigo-900 mr-3">
                                         Edit
                                     </button>
                                     <button class="text-red-600 hover:text-red-900">
                                         Hapus
                                     </button>
                                 </td>
                             </tr>
                             <tr>
                                 <td class="px-6 py-4 whitespace-nowrap">
                                     <div class="flex items-center">
                                         <div class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center">
                                             <span class="text-sm font-medium text-gray-600">SN</span>
                                         </div>
                                         <div class="ml-4">
                                             <div class="text-sm font-medium text-gray-900">
                                                 Siti Nurhaliza
                                             </div>
                                         </div>
                                     </div>
                                 </td>
                                 <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                     siti.n@example.com
                                 </td>
                                 <td class="px-6 py-4 whitespace-nowrap">
                                     <span class="px-2 py-1 text-xs font-medium rounded-full bg-amber-100 text-amber-800">
                                         Pelapor
                                     </span>
                                 </td>
                                 <td class="px-6 py-4 whitespace-nowrap">
                                     <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                                         Aktif
                                     </span>
                                 </td>
                                 <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                     2024-01-20
                                 </td>
                                 <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                     <button class="text-indigo-600 hover:text-indigo-900 mr-3">
                                         Edit
                                     </button>
                                     <button class="text-red-600 hover:text-red-900">
                                         Hapus
                                     </button>
                                 </td>
                             </tr>
                         </tbody>
                     </table>
                 </div>

                 <div class="flex items-center justify-between mt-4">
                     <div class="text-sm text-gray-700">
                         Menampilkan 1-10 dari 50 pengguna
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