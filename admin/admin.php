<?php
// Selalu mulai session di baris paling atas
// session_start();

// Asumsikan user harus login untuk mengakses halaman ini
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}
// Hanya admin pusat yang boleh mengakses halaman manajemen admin
if ($_SESSION['user_role'] !== 'admin') {
    die("Akses ditolak. Hanya Admin Pusat yang dapat mengelola pengguna.");
}

// =======================================================
// BAGIAN LOGIKA PHP UNTUK HALAMAN ADMIN
// =======================================================

// 1. KONEKSI DATABASE
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'db_didikara';
$mysqli = new mysqli($host, $user, $pass, $db);
if ($mysqli->connect_error) {
    die("Koneksi gagal: " . $mysqli->connect_error);
}

// 2. LOGIKA PAGINASI DAN FILTER
$limit = 10;
$page = isset($_GET['page']) && (int)$_GET['page'] > 0 ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$filter_role = $_GET['role'] ?? 'all';
$search_query = isset($_GET['search']) ? trim($_GET['search']) : '';

// 3. MEMBANGUN QUERY
$where_clauses = ["role IN ('admin', 'admin-daerah', 'admin-wilayah')"]; // Selalu filter hanya admin
$params = [];
$types = '';

if (!empty($filter_role) && $filter_role != 'all') {
    $where_clauses[] = "role = ?";
    $params[] = $filter_role;
    $types .= 's';
}
if (!empty($search_query)) {
    $where_clauses[] = "(full_name LIKE ? OR email LIKE ?)";
    $search_term = "%" . $search_query . "%";
    $params[] = $search_term;
    $params[] = $search_term;
    $types .= 'ss';
}

$where_sql = "WHERE " . implode(' AND ', $where_clauses);

// 4. QUERY UNTUK MENGHITUNG TOTAL DATA
$count_sql = "SELECT COUNT(id) as total FROM users $where_sql";
$stmt_count = $mysqli->prepare($count_sql);
if (count($params) > 0) {
    $stmt_count->bind_param($types, ...$params);
}
$stmt_count->execute();
$total_results = $stmt_count->get_result()->fetch_assoc()['total'];
$total_pages = ceil($total_results / $limit);

// 5. QUERY UTAMA UNTUK MENGAMBIL DATA USERS
$sql = "SELECT id, full_name, email, role, is_active, created_at FROM users $where_sql ORDER BY id DESC LIMIT ? OFFSET ?";
$params_main_query = $params;
$params_main_query[] = $limit;
$params_main_query[] = $offset;
$types_main_query = $types . 'ii';

$stmt = $mysqli->prepare($sql);
if (count($params_main_query) > 0) {
    $stmt->bind_param($types_main_query, ...$params_main_query);
}
$stmt->execute();
$result_users = $stmt->get_result();

// Fungsi bantu untuk badge
function getRoleBadge($role)
{
    $map = ['admin' => 'bg-indigo-100 text-indigo-800', 'admin-daerah' => 'bg-green-100 text-green-800', 'admin-wilayah' => 'bg-blue-100 text-blue-800'];
    $style = $map[$role] ?? 'bg-gray-100 text-gray-800';
    return "<span class='px-2 py-1 text-xs font-medium rounded-full {$style}'>" . str_replace('_', ' ', ucfirst($role)) . "</span>";
}
function getStatusBadge($isActive)
{
    return $isActive ? "<span class='px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800'>Aktif</span>" : "<span class='px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800'>Tidak Aktif</span>";
}

?>


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
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Admin</h1>
            <button class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                Tambah Admin
            </button>
        </div>

        <div class="bg-white rounded-lg shadow">
            <div class="p-6">
                <form method="GET" class="flex items-center justify-between mb-4">
                    <input type="hidden" name="view" value="admin">
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-users text-gray-500"></i>
                        <span class="text-gray-700 font-medium">Daftar Admin</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <select name="role" onchange="this.form.submit()" class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="all" <?= ($filter_role == 'all' || $filter_role == '') ? 'selected' : '' ?>>Semua Peran</option>
                            <option value="admin" <?= $filter_role == 'admin' ? 'selected' : '' ?>>Admin Pusat</option>
                            <option value="admin-daerah" <?= $filter_role == 'admin-daerah' ? 'selected' : '' ?>>Admin Daerah</option>
                            <option value="admin-wilayah" <?= $filter_role == 'admin-wilayah' ? 'selected' : '' ?>>Admin Wilayah</option>
                        </select>
                        <input type="text" name="search" placeholder="Cari nama atau email..." value="<?= htmlspecialchars($search_query) ?>" class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                    </div>
                </form>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Peran</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bergabung</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php if ($result_users && $result_users->num_rows > 0): ?>
                                <?php while ($user_row = $result_users->fetch_assoc()): ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900"><?= htmlspecialchars($user_row['full_name']) ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= htmlspecialchars($user_row['email']) ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap"><?= getRoleBadge($user_row['role']) ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap"><?= getStatusBadge($user_row['is_active']) ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= date('d M Y', strtotime($user_row['created_at'])) ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <button class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</button>
                                            <button class="text-red-600 hover:text-red-900">Hapus</button>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center px-6 py-4 text-gray-500">Tidak ada admin yang ditemukan.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="flex items-center justify-between mt-4">
                    <div class="text-sm text-gray-700">
                        Menampilkan <?= $total_results > 0 ? $offset + 1 : 0 ?>-<?= min($offset + $limit, $total_results) ?> dari <?= $total_results ?> pengguna
                    </div>
                    <?php if ($total_pages > 1): ?>
                        <div class="flex items-center space-x-2">
                            <?php for ($i = 1; $i <= $total_pages; $i++):
                                $params = ['view' => 'admin', 'page' => $i, 'role' => $filter_role, 'search' => $search_query];
                            ?>
                                <a href="?<?= http_build_query($params) ?>" class="px-3 py-1 border rounded-md text-sm <?= $page == $i ? 'bg-indigo-50 text-indigo-600' : 'hover:bg-gray-50' ?>"><?= $i ?></a>
                            <?php endfor; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>
</div>