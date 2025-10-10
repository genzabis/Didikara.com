<?php
// =======================================================
// SEMUA LOGIKA PHP YANG SUDAH KITA BUAT SEBELUMNYA
// (TIDAK ADA YANG DIUBAH, HANYA DIPINDAHKAN KE SINI)
// =======================================================

// 1. KONEKSI DATABASE
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'db_didikara';
$mysqli = new mysqli($host, $user, $pass, $db);
if ($mysqli->connect_error) {
    die("Koneksi database gagal: " . $mysqli->connect_error);
}

// 2. LOGIKA PAGINASI DAN FILTER
// Menggunakan variabel $page_number dari route.php
$limit = 10;
$page = $page_number ?? 1; // Mengambil dari router, default ke 1
$offset = ($page - 1) * $limit;

$filter_status = $_GET['status'] ?? '';
$search_query = isset($_GET['search']) ? trim($_GET['search']) : '';
$filter_jenjang = $_GET['jenjang'] ?? 'all';

// 3. MEMBANGUN QUERY SECARA DINAMIS DAN AMAN
$where_clauses = [];
$params = [];
$types = '';

if ($filter_jenjang == 'daerah') {
    $where_clauses[] = "(LOWER(r.school_name) LIKE '%sd%' OR LOWER(r.school_name) LIKE '%smp%')";
} elseif ($filter_jenjang == 'wilayah') {
    $where_clauses[] = "(LOWER(r.school_name) LIKE '%sma%' OR LOWER(r.school_name) LIKE '%smk%')";
}

if (!empty($filter_status) && $filter_status != 'all') {
    $where_clauses[] = "r.status = ?";
    $params[] = $filter_status;
    $types .= 's';
}
if (!empty($search_query)) {
    // Mengubah pencarian nama sekolah menjadi case-insensitive
    $where_clauses[] = "(LOWER(r.school_name) LIKE ? OR r.id = ?)";

    // Siapkan parameter: ubah input ke huruf kecil dan tambahkan wildcard %
    $search_term_like = "%" . strtolower($search_query) . "%";

    $params[] = $search_term_like;
    $params[] = $search_query; // ID tetap dicari secara persis
    $types .= 'ss';
}

$where_sql = count($where_clauses) > 0 ? "WHERE " . implode(' AND ', $where_clauses) : '';

// 4. QUERY UNTUK MENGHITUNG TOTAL DATA
$count_sql = "SELECT COUNT(r.id) as total FROM reports r $where_sql";
$stmt_count = $mysqli->prepare($count_sql);
if (count($params) > 0) {
    $stmt_count->bind_param($types, ...$params);
}
$stmt_count->execute();
$total_results = $stmt_count->get_result()->fetch_assoc()['total'];
$total_pages = ceil($total_results / $limit);

// 5. QUERY UTAMA UNTUK MENGAMBIL DATA LAPORAN
$sql = "
    SELECT r.id, r.school_name, it.name AS issue_name, r.status, r.created_at
    FROM reports r
    JOIN issue_types it ON r.issue_type_id = it.id
    $where_sql
    ORDER BY r.id DESC
    LIMIT ? OFFSET ?
";
$params_main_query = $params;
$params_main_query[] = $limit;
$params_main_query[] = $offset;
$types_main_query = $types . 'ii';

$stmt = $mysqli->prepare($sql);
if (count($params_main_query) > 0) {
    $stmt->bind_param($types_main_query, ...$params_main_query);
}
$stmt->execute();
$result = $stmt->get_result();

// 6. FUNGSI BANTU UNTUK TAMPILAN STATUS
function getStatusBadge($status)
{
    $styles = ['pending' => 'bg-yellow-100 text-yellow-800', 'confirmed' => 'bg-blue-100 text-blue-800', 'investigating' => 'bg-cyan-100 text-cyan-800', 'resolved' => 'bg-green-100 text-green-800', 'rejected' => 'bg-red-100 text-red-800', 'archived' => 'bg-gray-100 text-gray-800'];
    $text = ['pending' => 'Menunggu', 'confirmed' => 'Dikonfirmasi', 'investigating' => 'Investigasi', 'resolved' => 'Selesai', 'rejected' => 'Ditolak', 'archived' => 'Diarsipkan'];
    $style = $styles[$status] ?? 'bg-gray-100 text-gray-800';
    $status_text = $text[$status] ?? ucfirst($status);
    return "<span class='px-2 py-1 text-xs font-medium rounded-full {$style}'>{$status_text}</span>";
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
        <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0 mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Laporan Masalah</h1>
            <form method="GET" class="flex flex-col sm:flex-row sm:items-center space-y-2 sm:space-y-0 sm:space-x-2">
                <input type="hidden" name="view" value="laporan">
                <select name="status" onchange="this.form.submit()" class="w-full sm:w-auto px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="all" <?= ($filter_status == 'all' || $filter_status == '') ? 'selected' : '' ?>>Semua Status</option>
                    <option value="pending" <?= ($filter_status == 'pending') ? 'selected' : '' ?>>Menunggu</option>
                    <option value="confirmed" <?= ($filter_status == 'confirmed') ? 'selected' : '' ?>>Dikonfirmasi</option>
                    <option value="resolved" <?= ($filter_status == 'resolved') ? 'selected' : '' ?>>Selesai</option>
                    <option value="rejected" <?= ($filter_status == 'rejected') ? 'selected' : '' ?>>Ditolak</option>
                </select>
                <input type="hidden" name="jenjang" value="<?= htmlspecialchars($filter_jenjang) ?>">
                <select name="jenjang" onchange="this.form.submit()" class="w-full sm:w-auto px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="all" <?= ($_GET['jenjang'] ?? 'all') == 'all' ? 'selected' : '' ?>>Semua Jenjang</option>
                    <option value="daerah" <?= ($_GET['jenjang'] ?? '') == 'daerah' ? 'selected' : '' ?>>Data Daerah (SD/SMP)</option>
                    <option value="wilayah" <?= ($_GET['jenjang'] ?? '') == 'wilayah' ? 'selected' : '' ?>>Data Wilayah (SMA/SMK)</option>
                </select>
                </select>
                <button type="button" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Export Data</button>
            </form>
        </div>

        <div class="bg-white rounded-lg shadow">
            <div class="p-6">
                <form method="GET" class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0 mb-4">
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-table text-gray-500"></i>
                        <span class="text-gray-700 font-medium">Daftar Laporan</span>
                    </div>
                    <div class="flex items-center">
                        <input type="hidden" name="view" value="laporan">
                        <input type="hidden" name="status" value="<?= htmlspecialchars($filter_status) ?>">
                        <input type="text" name="search" placeholder="Cari ID atau nama sekolah..." value="<?= htmlspecialchars($search_query) ?>" class="w-full md:w-auto px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                    </div>
                </form>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sekolah</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Masalah</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php if ($result && $result->num_rows > 0): ?>
                                <?php
                                // Inisialisasi nomor urut berdasarkan halaman saat ini
                                $nomor_urut = ($page - 1) * $limit + 1;
                                ?>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td class="px-6 py-4 font-medium text-gray-900"><?= $nomor_urut ?></td>

                                        <td class="px-6 py-4"><?= htmlspecialchars($row['school_name']) ?></td>
                                        <td class="px-6 py-4"><?= htmlspecialchars($row['issue_name']) ?></td>
                                        <td class="px-6 py-4"><?= getStatusBadge($row['status']) ?></td>
                                        <td class="px-6 py-4"><?= date('d M Y', strtotime($row['created_at'])) ?></td>
                                        <td class="px-6 py-4">
                                            <button data-id="<?= $row['id'] ?>" class="detail-button text-indigo-600 hover:text-indigo-900 text-sm font-medium">Detail</button>
                                        </td>
                                    </tr>
                                    <?php $nomor_urut++; // Naikkan nomor urut untuk baris berikutnya 
                                    ?>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center px-6 py-4 text-gray-500">Tidak ada laporan yang ditemukan.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0 mt-4">
                    <div class="text-sm text-gray-700">
                        Menampilkan <?= $total_results > 0 ? $offset + 1 : 0 ?>-<?= min($offset + $limit, $total_results) ?> dari <?= $total_results ?> laporan
                    </div>

                    <?php if ($total_pages > 1): ?>
                        <nav>
                            <ul class="flex items-center space-x-2">
                                <?php for ($i = 1; $i <= $total_pages; $i++):
                                  $params = ['view' => 'laporan', 'page' => $i, 'jenjang' => $filter_jenjang, 'status' => $filter_status, 'search' => $search_query];
                                ?>
                                    <li>
                                        <a href="?<?= http_build_query($params) ?>"
                                            class="px-3 py-1 border border-gray-300 rounded-md text-sm <?= ($page == $i) ? 'bg-indigo-50 text-indigo-600 font-bold' : 'hover:bg-gray-50' ?>">
                                            <?= $i ?>
                                        </a>
                                    </li>
                                <?php endfor; ?>
                            </ul>
                        </nav>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>
    <div id="detailModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 z-50 hidden items-center justify-center">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between p-4 border-b">
                <h2 class="text-xl font-semibold text-gray-800">Detail Laporan <span id="modalReportId" class="font-bold"></span></h2>
                <button id="closeModal" class="text-gray-500 hover:text-gray-800">&times;</button>
            </div>
            <div class="p-6 space-y-4">
                <div id="modalContent">
                    <p class="text-center text-gray-500">Memuat data...</p>
                </div>
            </div>
        </div>
    </div>
    </main>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const detailModal = document.getElementById('detailModal');
        const closeModal = document.getElementById('closeModal');
        const modalContent = document.getElementById('modalContent');
        const modalReportId = document.getElementById('modalReportId');

        // Fungsi untuk menutup modal
        function hideModal() {
            detailModal.classList.add('hidden');
            detailModal.classList.remove('flex');
        }

        // Event listener untuk tombol tutup
        closeModal.addEventListener('click', hideModal);
        // Juga tutup jika klik di luar area modal
        detailModal.addEventListener('click', function(e) {
            if (e.target === detailModal) {
                hideModal();
            }
        });

        // Tambahkan event listener ke semua tombol detail
        document.querySelectorAll('.detail-button').forEach(button => {
            button.addEventListener('click', function() {
                const reportId = this.getAttribute('data-id');

                // Tampilkan modal dan indikator loading
                modalReportId.textContent = `#LP${reportId}`;
                modalContent.innerHTML = '<p class="text-center text-gray-500">Memuat data...</p>';
                detailModal.classList.remove('hidden');
                detailModal.classList.add('flex');

                // Ambil data detail dari server menggunakan Fetch API
                fetch(`get_report_details.php?id=${reportId}`)
                    .then(response => response.text())
                    .then(html => {
                        modalContent.innerHTML = html;
                    })
                    .catch(error => {
                        modalContent.innerHTML = '<p class="text-center text-red-500">Gagal memuat data.</p>';
                        console.error('Error:', error);
                    });
            });
        });
    });
</script>