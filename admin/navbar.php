<?php
// Ambil halaman saat ini dari URL. Jika tidak ada, default-nya adalah 'dashboard'.
$currentPage = $_GET['page'] ?? 'dashboard';

// Definisikan kelas CSS untuk link aktif dan tidak aktif agar lebih rapi
$activeClasses = 'bg-indigo-800 text-white';
$inactiveClasses = 'text-indigo-100 hover:bg-indigo-800 hover:text-white';
?>

<aside id="sidebar" class="sidebar fixed top-0 left-0 h-screen bg-indigo-900 text-white w-64">
    <div class="p-6">
        <div class="flex items-center space-x-2 mb-8">
            <div class="flex items-center">
                <img src="../assets/img/didikara.png" class="w-32 h-auto" alt="logo">
            </div>
        </div>

        <nav class="space-y-1">
            <a href="?page=dashboard" class="flex items-center space-x-3 px-4 py-3 rounded-md transition-colors <?php echo ($currentPage === 'dashboard') ? $activeClasses : $inactiveClasses; ?>">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
            <a href="?page=laporan" class="flex items-center space-x-3 px-4 py-3 rounded-md transition-colors <?php echo ($currentPage === 'laporan') ? $activeClasses : $inactiveClasses; ?>">
                <i class="fas fa-clipboard-list"></i>
                <span>Laporan</span>
            </a>
            <a href="?page=admin" class="flex items-center space-x-3 px-4 py-3 rounded-md transition-colors <?php echo ($currentPage === 'pengguna') ? $activeClasses : $inactiveClasses; ?>">
                <i class="fas fa-users"></i>
                <span>admin</span>
            </a>
            <a href="?page=pengaturan" class="flex items-center space-x-3 px-4 py-3 rounded-md transition-colors <?php echo ($currentPage === 'pengaturan') ? $activeClasses : $inactiveClasses; ?>">
                <i class="fas fa-cog"></i>
                <span>Pengaturan</span>
            </a>
        </nav>
    </div>

    <div class="absolute bottom-0 left-0 right-0 p-6">
        <a href="../" class="flex items-center space-x-3 text-indigo-100 hover:text-white w-full px-4 py-3 rounded-md hover:bg-indigo-800 transition-colors">
            <i class="fas fa-sign-out-alt"></i>
            <span>Kembali ke Website</span>
        </a>
    </div>
</aside>