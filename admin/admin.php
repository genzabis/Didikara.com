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
    <div id="addAdminModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 z-50 hidden items-center justify-center">
  <div class="bg-white rounded-lg shadow-xl w-full max-w-lg mx-4"> 
            <form id="addAdminForm" method="POST" action="tambah_admin_proses.php">
                <div class="flex items-center justify-between p-4 border-b">
                    <h2 class="text-xl font-semibold text-gray-800">Tambah Admin Baru</h2>
                    <button type="button" id="closeAddModal" class="text-gray-500 hover:text-gray-800">&times;</button>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label for="full_name" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                        <input type="text" name="full_name" id="full_name" required class="mt-1 w-full px-3 py-2 border rounded-md">
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" id="email" required class="mt-1 w-full px-3 py-2 border rounded-md">
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <input type="password" name="password" id="password" required class="mt-1 w-full px-3 py-2 border rounded-md">
                    </div>
                    <div>
                        <label for="add_admin_role" class="block text-sm font-medium text-gray-700">Peran (Role)</label>
                        <select name="role" id="add_admin_role" required class="mt-1 w-full px-3 py-2 border rounded-md">
                            <option value="admin">Admin Pusat</option>
                            <option value="admin-wilayah">Admin Wilayah</option>
                            <option value="admin-daerah">Admin Daerah</option>
                        </select>
                    </div>
                    <div id="locationInputs" class="space-y-4 hidden">
                        <div>
                            <label for="province_id" class="block text-sm font-medium text-gray-700">Provinsi</label>
                            <select name="province_id" id="province_id" class="mt-1 w-full px-3 py-2 border rounded-md">
                                <?php
                                // Ambil daftar provinsi dari database
                                $provinces = $mysqli->query("SELECT id, name FROM provinces ORDER BY name ASC");
                                if ($provinces) {
                                    while ($province = $provinces->fetch_assoc()) {
                                        echo "<option value='{$province['id']}'>" . htmlspecialchars($province['name']) . "</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div id="districtInputContainer" class="hidden">
                            <label for="district" class="block text-sm font-medium text-gray-700">Kabupaten/Kota</label>
                            <input type="text" name="district" id="district" placeholder="Contoh: Kabupaten Purbalingga" class="mt-1 w-full px-3 py-2 border rounded-md">
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-end p-4 border-t space-x-2">
                    <button type="button" id="cancelAdd" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Simpan</button>
                </div>
            </form>
        </div>
    </div>
    <header class="bg-white shadow-sm">
        <div class="flex items-center justify-between px-4 md:px-6 py-3 md:py-4">
            <button id="sidebar-toggle" class="text-gray-500 hover:text-gray-700 focus:outline-none">
                <i class="fas fa-bars text-2xl"></i>
            </button>
            <div class="flex items-center space-x-3 md:space-x-4 min-w-0">
                <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-user text-indigo-600"></i>
                </div>
                <div class="text-sm min-w-0">
                    <p class="font-medium text-gray-700 truncate max-w-[180px] md:max-w-none">
                        <?= htmlspecialchars($_SESSION['full_name'] ?? 'Nama Pengguna') ?>
                    </p>
                    <p class="text-gray-500 text-xs truncate max-w-[180px] md:max-w-none">
                        <?= htmlspecialchars($_SESSION['email'] ?? 'email@pengguna.com') ?>
                    </p>
                </div>
            </div>
        </div>
    </header>


    <main class="p-4 md:p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
            <h1 class="text-xl md:text-2xl font-bold text-gray-900">Admin</h1>
            <button id="addAdminBtn" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 w-full sm:w-auto">
                Tambah Admin
            </button>
        </div>


        <div class="bg-white rounded-lg shadow">
            <div class="p-4 md:p-6">
                <form method="GET" class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between mb-4">
                    <input type="hidden" name="view" value="admin">
                    <div class="flex items-center gap-2 text-sm text-gray-700">
                        <i class="fas fa-users text-gray-500"></i>
                        <span class="font-medium">Daftar Admin</span>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-2 w-full md:w-auto">
                        <select name="role" onchange="this.form.submit()" class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 w-full sm:w-48">
                            <option value="all" <?= ($filter_role == 'all' || $filter_role == '') ? 'selected' : '' ?>>Semua Peran</option>
                            <option value="admin" <?= $filter_role == 'admin' ? 'selected' : '' ?>>Admin Pusat</option>
                            <option value="admin-daerah" <?= $filter_role == 'admin-daerah' ? 'selected' : '' ?>>Admin Daerah</option>
                            <option value="admin-wilayah" <?= $filter_role == 'admin-wilayah' ? 'selected' : '' ?>>Admin Wilayah</option>
                        </select>
                        <input type="text" name="search" placeholder="Cari nama atau email..." value="<?= htmlspecialchars($search_query) ?>"
                            class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 w-full sm:w-64" />
                    </div>
                </form>


                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Peran</th>
                                <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell">Status</th>
                                <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell">Bergabung</th>
                                <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php if ($result_users && $result_users->num_rows > 0): ?>
                                <?php while ($user_row = $result_users->fetch_assoc()): ?>
                                    <tr class="align-top">
                                        <td class="px-4 md:px-6 py-3 whitespace-normal break-words">
                                            <div class="font-medium text-gray-900"><?= htmlspecialchars($user_row['full_name']) ?></div>
                                        </td>
                                        <td class="px-4 md:px-6 py-3 whitespace-normal">
                                            <div class="text-gray-900 truncate max-w-[180px] sm:max-w-[260px] md:max-w-none">
                                                <?= htmlspecialchars($user_row['email']) ?>
                                            </div>
                                        </td>
                                        <td class="px-4 md:px-6 py-3"><?= getRoleBadge($user_row['role']) ?></td>
                                        <td class="px-4 md:px-6 py-3 hidden sm:table-cell"><?= getStatusBadge($user_row['is_active']) ?></td>
                                        <td class="px-4 md:px-6 py-3 hidden sm:table-cell text-gray-900">
                                            <?= date('d M Y', strtotime($user_row['created_at'])) ?>
                                        </td>
                                        <td class="px-4 md:px-6 py-3">
                                            <div class="flex items-center gap-3">
                                                <button class="text-indigo-600 hover:text-indigo-900">Edit</button>
                                                <button class="text-red-600 hover:text-red-900">Hapus</button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center px-4 md:px-6 py-4 text-gray-500">Tidak ada admin yang ditemukan.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>


                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mt-4">
                    <div class="text-xs sm:text-sm text-gray-700">
                        Menampilkan <?= $total_results > 0 ? $offset + 1 : 0 ?>-<?= min($offset + $limit, $total_results) ?> dari <?= $total_results ?> pengguna
                    </div>
                    <?php if ($total_pages > 1): ?>
                        <div class="flex flex-wrap items-center gap-2">
                            <?php for ($i = 1; $i <= $total_pages; $i++):
                                $params = ['view' => 'admin', 'page' => $i, 'role' => $filter_role, 'search' => $search_query];
                            ?>
                                <a href="?<?= http_build_query($params) ?>"
                                    class="px-3 py-1 border rounded-md text-sm <?= $page == $i ? 'bg-indigo-50 text-indigo-600 border-indigo-200' : 'hover:bg-gray-50' ?>">
                                    <?= $i ?>
                                </a>
                            <?php endfor; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const addAdminModal = document.getElementById('addAdminModal');
        const addAdminBtn = document.getElementById('addAdminBtn');
        const closeAddModal = document.getElementById('closeAddModal');
        const cancelAdd = document.getElementById('cancelAdd');

        const roleSelect = document.getElementById('add_admin_role');
        const locationInputs = document.getElementById('locationInputs');
        const districtInputContainer = document.getElementById('districtInputContainer');

        // Fungsi untuk menampilkan/menyembunyikan input lokasi
        function toggleLocationInputs() {
            const selectedRole = roleSelect.value;
            if (selectedRole === 'admin-wilayah' || selectedRole === 'admin-daerah') {
                locationInputs.classList.remove('hidden');
                if (selectedRole === 'admin-daerah') {
                    districtInputContainer.classList.remove('hidden');
                } else {
                    districtInputContainer.classList.add('hidden');
                }
            } else {
                locationInputs.classList.add('hidden');
            }
        }

        // Tampilkan modal saat tombol "Tambah Admin" diklik
        addAdminBtn.addEventListener('click', () => {
            addAdminModal.classList.remove('hidden');
            addAdminModal.classList.add('flex');
        });

        // Sembunyikan modal
        function hideModal() {
            addAdminModal.classList.add('hidden');
            addAdminModal.classList.remove('flex');
        }

        closeAddModal.addEventListener('click', hideModal);
        cancelAdd.addEventListener('click', hideModal);

        // Event listener untuk dropdown role
        roleSelect.addEventListener('change', toggleLocationInputs);

        // Panggil sekali saat halaman dimuat
        toggleLocationInputs();
    });
</script>