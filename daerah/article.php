<?php
// Selalu mulai session di baris paling atas
// session_start();

// Keamanan: Pastikan user sudah login dan merupakan admin pusat
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin-daerah') {
    die("Akses ditolak. Hanya Admin Pusat yang dapat mengelola artikel.");
}

// =======================================================
// BAGIAN LOGIKA PHP UNTUK HALAMAN ARTIKEL
// =======================================================

// 1. KONEKSI DATABASE
$host = 'localhost'; $user = 'root'; $pass = ''; $db = 'db_didikara';
$mysqli = new mysqli($host, $user, $pass, $db);
if ($mysqli->connect_error) { die("Koneksi gagal: " . $mysqli->connect_error); }

// 2. LOGIKA PAGINASI DAN FILTER
$limit = 10;
$page = isset($_GET['page']) && (int)$_GET['page'] > 0 ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$filter_category = $_GET['category'] ?? 'all';
$search_query = isset($_GET['search']) ? trim($_GET['search']) : '';

// 3. MEMBANGUN QUERY
$where_clauses = [];
$params = [];
$types = '';

if (!empty($filter_category) && $filter_category != 'all') {
    $where_clauses[] = "a.category = ?";
    $params[] = $filter_category;
    $types .= 's';
}
if (!empty($search_query)) {
    $where_clauses[] = "a.title LIKE ?";
    $search_term = "%" . $search_query . "%";
    $params[] = $search_term;
    $types .= 's';
}

$where_sql = count($where_clauses) > 0 ? "WHERE " . implode(' AND ', $where_clauses) : '';

// 4. QUERY UNTUK MENGHITUNG TOTAL DATA
$count_sql = "SELECT COUNT(a.id) as total FROM articles a $where_sql";
$stmt_count = $mysqli->prepare($count_sql);
if (count($params) > 0) { $stmt_count->bind_param($types, ...$params); }
$stmt_count->execute();
$total_results = $stmt_count->get_result()->fetch_assoc()['total'];
$total_pages = ceil($total_results / $limit);

// 5. QUERY UTAMA UNTUK MENGAMBIL DATA ARTIKEL
$sql = "
    SELECT 
        a.id, a.title, a.category, a.status, a.published_at,
        u.full_name as author_name,
        ai.url as cover_image_url
    FROM articles a
    LEFT JOIN users u ON a.author_id = u.id
    LEFT JOIN article_images ai ON a.id = ai.article_id AND ai.role = 'cover'
    $where_sql 
    ORDER BY a.published_at DESC 
    LIMIT ? OFFSET ?
";
$params_main_query = $params;
$params_main_query[] = $limit;
$params_main_query[] = $offset;
$types_main_query = $types . 'ii';

$stmt = $mysqli->prepare($sql);
if(count($params_main_query) > 0) { $stmt->bind_param($types_main_query, ...$params_main_query); }
$stmt->execute();
$result_articles = $stmt->get_result();
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
                        <p class="font-medium text-gray-700"><?= htmlspecialchars($_SESSION['full_name'] ?? 'Nama Pengguna') ?></p>
                        <p class="text-gray-500 text-xs"><?= htmlspecialchars($_SESSION['email'] ?? 'email@pengguna.com') ?></p>
                    </div>
                </div>
            </div>
        </div>
    </header>
<main class="p-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Manajemen Artikel</h1>
        <button class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
            <i class="fas fa-plus mr-2"></i>Tambah Artikel
        </button>
    </div>

    <div class="bg-white rounded-lg shadow">
        <div class="p-6">
            <form method="GET" class="flex flex-col md:flex-row items-center justify-between mb-4 space-y-4 md:space-y-0">
                <input type="hidden" name="view" value="artikel">
                <div class="flex items-center space-x-2 self-start">
                    <i class="fas fa-newspaper text-gray-500"></i>
                    <span class="text-gray-700 font-medium">Daftar Artikel</span>
                </div>
                <div class="w-full md:w-auto flex flex-col sm:flex-row items-center space-y-2 sm:space-y-0 sm:space-x-2">
                    <select name="category" onchange="this.form.submit()" class="w-full sm:w-auto px-4 py-2 border border-gray-300 rounded-md">
                        <option value="all">Semua Kategori</option>
                        <option value="cerita-sukses" <?= $filter_category == 'cerita-sukses' ? 'selected' : '' ?>>Cerita Sukses</option>
                        <option value="program" <?= $filter_category == 'program' ? 'selected' : '' ?>>Program</option>
                        <option value="acara" <?= $filter_category == 'acara' ? 'selected' : '' ?>>Acara</option>
                        <option value="publikasi" <?= $filter_category == 'publikasi' ? 'selected' : '' ?>>Publikasi</option>
                        <option value="cerita-inspiratif" <?= $filter_category == 'cerita-inspiratif' ? 'selected' : '' ?>>Cerita Inspiratif</option>
                    </select>
                    <input type="text" name="search" placeholder="Cari judul artikel..." value="<?= htmlspecialchars($search_query) ?>" class="w-full sm:w-auto px-4 py-2 border border-gray-300 rounded-md" />
                </div>
            </form>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul Artikel</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Penulis</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tgl Terbit</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php if ($result_articles && $result_articles->num_rows > 0): ?>
                            <?php while ($article = $result_articles->fetch_assoc()): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <img class="h-10 w-10 rounded-md object-cover" src="<?= htmlspecialchars($article['cover_image_url'] ?? 'https://via.placeholder.com/150') ?>" alt="">
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900"><?= htmlspecialchars($article['title']) ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= ucwords(str_replace('-', ' ', $article['category'])) ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($article['author_name'] ?? 'N/A') ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?= $article['status'] == 'published' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' ?>">
                                            <?= ucfirst($article['status']) ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= date('d M Y', strtotime($article['published_at'])) ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="#" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center px-6 py-4 text-gray-500">Tidak ada artikel yang ditemukan.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
             </div>
    </div>
</main>
</div>