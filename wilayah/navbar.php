<?php
// Ambil halaman saat ini dari URL. Jika tidak ada, default-nya adalah 'dashboard'.
$currentPage = $_GET['view'] ?? 'dashboard';

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
            <a href="?view=dashboard" class="flex items-center space-x-3 px-4 py-3 rounded-md transition-colors <?php echo ($currentPage === 'dashboard') ? $activeClasses : $inactiveClasses; ?>">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
            <a href="?view=laporan" class="flex items-center space-x-3 px-4 py-3 rounded-md transition-colors <?php echo ($currentPage === 'laporan') ? $activeClasses : $inactiveClasses; ?>">
                <i class="fas fa-clipboard-list"></i>
                <span>Laporan</span>
            </a>
            <a href="?view=admin" class="flex items-center space-x-3 px-4 py-3 rounded-md transition-colors <?php echo ($currentPage === 'pengguna') ? $activeClasses : $inactiveClasses; ?>">
                <i class="fas fa-users"></i>
                <span>admin</span>
            </a>
            <a href="?view=pengaturan" class="flex items-center space-x-3 px-4 py-3 rounded-md transition-colors <?php echo ($currentPage === 'pengaturan') ? $activeClasses : $inactiveClasses; ?>">
                <i class="fas fa-cog"></i>
                <span>Pengaturan</span>
            </a>
            <a href="logout.php" class="flex items-center space-x-3 text-indigo-100 hover:text-white w-full px-4 py-3 rounded-md hover:bg-indigo-800 transition-colors">
            <i class="fas fa-sign-out-alt"></i>
            <span>Logout</span>
        </a>
        </nav>
    </div>
</aside>