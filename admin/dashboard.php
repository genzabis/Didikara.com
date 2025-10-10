<?php
// LANGKAH 1: KONFIGURASI DAN KONEKSI DATABASE
// ===========================================
// Sesuaikan detail ini dengan konfigurasi database Anda.
$host = 'localhost';
$user = 'root'; // User default XAMPP/MariaDB
$pass = '';     // Password default XAMPP/MariaDB kosong
$db   = 'db_didikara';

// Membuat koneksi menggunakan mysqli
$mysqli = new mysqli($host, $user, $pass, $db);

// Cek koneksi, jika gagal akan menampilkan error
if ($mysqli->connect_error) {
    die("Koneksi database gagal: " . $mysqli->connect_error);
}


// LANGKAH 2: PENGAMBILAN SEMUA DATA YANG DIBUTUHKAN
// ================================================

// --- Data untuk Kartu Statistik ---
$total_laporan = $mysqli->query("SELECT COUNT(id) AS total FROM reports")->fetch_assoc()['total'];
$sekolah_terpantau = $mysqli->query("SELECT COUNT(DISTINCT school_name) AS total FROM reports")->fetch_assoc()['total'];
$kontributor_aktif = $mysqli->query("SELECT COUNT(id) AS total FROM contributors WHERE status = 'approved'")->fetch_assoc()['total'];
$provinsi_terjangkau = $mysqli->query("SELECT COUNT(DISTINCT province_id) AS total FROM reports WHERE province_id IS NOT NULL")->fetch_assoc()['total'];

// --- Data untuk Diagram Tren Laporan (6 Bulan Terakhir) ---
$chart_labels = [];
$chart_data_map = [];
// Inisialisasi 6 bulan terakhir dengan data 0
for ($i = 5; $i >= 0; $i--) {
    $month = date('Y-m', strtotime("-$i months"));
    $chart_labels[] = date('M Y', strtotime($month));
    $chart_data_map[$month] = 0;
}
$query_tren = "
    SELECT DATE_FORMAT(created_at, '%Y-%m') AS bulan, COUNT(id) AS jumlah
    FROM reports
    WHERE created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
    GROUP BY bulan
    ORDER BY bulan ASC
";
$result_tren = $mysqli->query($query_tren);
if ($result_tren) {
    while ($row = $result_tren->fetch_assoc()) {
        $chart_data_map[$row['bulan']] = (int) $row['jumlah'];
    }
}
$chart_data = array_values($chart_data_map); // Konversi ke array numerik untuk Chart.js

// --- Data untuk Widget Status Laporan ---
$query_status = "SELECT status, COUNT(id) AS jumlah FROM reports GROUP BY status";
$result_status = $mysqli->query($query_status);
$status_counts = ['menunggu' => 0, 'proses' => 0, 'selesai' => 0];
$total_status_reports = 0;
if ($result_status) {
    while ($row = $result_status->fetch_assoc()) {
        $total_status_reports += $row['jumlah'];
        switch ($row['status']) {
            case 'pending':
                $status_counts['menunggu'] += $row['jumlah'];
                break;
            case 'confirmed':
            case 'investigating':
                $status_counts['proses'] += $row['jumlah'];
                break;
            case 'resolved':
                $status_counts['selesai'] += $row['jumlah'];
                break;
        }
    }
}
// Hitung persentase, hindari pembagian dengan nol
$menunggu_percent = ($total_status_reports > 0) ? round(($status_counts['menunggu'] / $total_status_reports) * 100) : 0;
$proses_percent = ($total_status_reports > 0) ? round(($status_counts['proses'] / $total_status_reports) * 100) : 0;
$selesai_percent = ($total_status_reports > 0) ? round(($status_counts['selesai'] / $total_status_reports) * 100) : 0;

// --- Data untuk Tabel Laporan Terbaru ---
$query_laporan_terbaru = "
    SELECT r.school_name, it.name AS issue_name, r.status, r.created_at 
    FROM reports r JOIN issue_types it ON r.issue_type_id = it.id
    ORDER BY r.created_at DESC LIMIT 5
";
$result_laporan_terbaru = $mysqli->query($query_laporan_terbaru);

// --- Helper Function untuk Badge Status ---
function getStatusBadge($status) {
    switch ($status) {
        case 'pending':
            return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">Menunggu</span>';
        case 'confirmed':
        case 'investigating':
            return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Diproses</span>';
        case 'resolved':
            return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Selesai</span>';
        default:
            return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Lainnya</span>';
    }
}
?>

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

    <main class="p-6">
        <div class="space-y-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Dashboard</h1>
                <div class="mt-2 md:mt-0 flex items-center space-x-2">
                    <span class="text-sm text-gray-500">Data per:</span>
                    <span class="text-sm font-medium"><?= date('d M Y, H:i') ?> WIB</span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-gray-500 text-sm">Total Laporan</p>
                            <h3 class="text-2xl font-bold text-gray-800 mt-1"><?= number_format($total_laporan) ?></h3>
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
                            <h3 class="text-2xl font-bold text-gray-800 mt-1"><?= number_format($sekolah_terpantau) ?></h3>
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
                            <h3 class="text-2xl font-bold text-gray-800 mt-1"><?= number_format($kontributor_aktif) ?></h3>
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
                            <h3 class="text-2xl font-bold text-gray-800 mt-1"><?= number_format($provinsi_terjangkau) ?></h3>
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
                            <span class="font-semibold"><?= $menunggu_percent ?>%</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                                <span class="text-gray-600">Dalam Proses</span>
                            </div>
                            <span class="font-semibold"><?= $proses_percent ?>%</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                <span class="text-gray-600">Terselesaikan</span>
                            </div>
                            <span class="font-semibold"><?= $selesai_percent ?>%</span>
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
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sekolah</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Masalah</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php if ($result_laporan_terbaru->num_rows > 0): ?>
                                <?php while($row = $result_laporan_terbaru->fetch_assoc()): ?>
                                 <tr class="hover:bg-gray-50">
                                     <td class="px-6 py-4 whitespace-nowrap">
                                         <span class="text-sm font-medium text-gray-900"><?= htmlspecialchars($row['school_name']) ?></span>
                                     </td>
                                     <td class="px-6 py-4 whitespace-nowrap">
                                         <span class="text-sm text-gray-500"><?= htmlspecialchars($row['issue_name']) ?></span>
                                     </td>
                                     <td class="px-6 py-4 whitespace-nowrap">
                                         <?= getStatusBadge($row['status']) ?>
                                     </td>
                                     <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                         <?= date('Y-m-d', strtotime($row['created_at'])) ?>
                                     </td>
                                 </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center px-6 py-4 text-gray-500">Tidak ada laporan terbaru.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
    // Menunggu DOM siap sebelum menjalankan script chart
    document.addEventListener('DOMContentLoaded', () => {
        const ctx = document.getElementById('reportsChart');
        if (ctx) {
            // Mengambil data dari PHP dan mengubahnya menjadi format JavaScript
            const chartLabels = <?= json_encode($chart_labels) ?>;
            const chartData = <?= json_encode($chart_data) ?>;

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: chartLabels,
                    datasets: [{
                        label: 'Jumlah Laporan',
                        data: chartData,
                        backgroundColor: 'rgba(79, 70, 229, 0.7)', // Warna Indigo
                        borderColor: 'rgba(79, 70, 229, 1)',
                        borderWidth: 1,
                        borderRadius: 5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                // Memastikan angka di sumbu Y adalah bilangan bulat
                                stepSize: 1,
                                precision: 0
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false // Menyembunyikan legenda 'Jumlah Laporan'
                        }
                    }
                }
            });
        }
    });
</script>

<?php
// LANGKAH 3: TUTUP KONEKSI DATABASE
// ===================================
$mysqli->close();
?>