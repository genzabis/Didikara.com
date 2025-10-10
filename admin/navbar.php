<?php
// Ambil halaman saat ini dari URL. Jika tidak ada, default-nya adalah 'dashboard'.
$currentPage = $_GET['view'] ?? 'dashboard';

// Definisikan kelas CSS untuk link aktif dan tidak aktif agar lebih rapi
$activeClasses = 'bg-indigo-800 text-white';
$inactiveClasses = 'text-indigo-100 hover:bg-indigo-800 hover:text-white';
?>
<aside id="sidebar" class="sidebar fixed top-0 left-0 h-screen bg-indigo-900 text-white w-64 transition-transform transform translate-x-0 md:translate-x-0 z-50">
    <div class="p-6">
        <div class="flex items-center space-x-2 mb-8">
            <img src="../assets/img/didikara.png" class="w-32 h-auto" alt="logo">
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
            <a href="?view=admin" class="flex items-center space-x-3 px-4 py-3 rounded-md transition-colors <?php echo ($currentPage === 'admin') ? $activeClasses : $inactiveClasses; ?>">
                <i class="fas fa-users"></i>
                <span>Admin</span>
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

<!-- Tombol toggle untuk mobile -->
<button id="sidebarToggle" class="fixed top-4 left-4 z-50 bg-indigo-600 text-white p-2 rounded-md hidden">
    <i class="fas fa-bars"></i>
</button>

<!-- Overlay -->
<div id="overlay" class="fixed inset-0 bg-black bg-opacity-40 hidden z-40"></div>

<script>
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');
    const toggleBtn = document.getElementById('sidebarToggle');

    // Toggle sidebar di mobile
    toggleBtn.addEventListener('click', () => {
        sidebar.classList.toggle('-translate-x-full');
        overlay.classList.toggle('hidden');
    });

    // Klik di luar sidebar = tutup
    overlay.addEventListener('click', () => {
        sidebar.classList.add('-translate-x-full');
        overlay.classList.add('hidden');
    });

    // Deteksi klik luar sidebar (kalau ga pakai overlay)
    document.addEventListener('click', (e) => {
        if (!sidebar.contains(e.target) && !toggleBtn.contains(e.target)) {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        }
    });
</script>