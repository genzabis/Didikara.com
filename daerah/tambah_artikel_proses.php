<?php
session_start();

// Keamanan: Pastikan hanya admin pusat yang bisa menambah artikel
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin_pusat') {
    die("Akses ditolak.");
}

// 1. KONEKSI DATABASE
$host = 'localhost'; $user = 'root'; $pass = ''; $db = 'db_didikara';
$mysqli = new mysqli($host, $user, $pass, $db);
if ($mysqli->connect_error) { die("Koneksi gagal"); }

// 2. AMBIL DATA DARI FORM (METHOD POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $category = $_POST['category'] ?? '';
    $excerpt = $_POST['excerpt'] ?? null;
    $content = $_POST['content'] ?? null;
    $cover_image_url = $_POST['cover_image_url'] ?? '';
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    
    // Ambil ID admin yang sedang login sebagai penulis
    $author_id = $_SESSION['user_id'];

    // Buat slug otomatis dari judul
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));

    // Validasi dasar
    if (empty($title) || empty($category) || empty($cover_image_url)) {
        header('Location: index.php?view=artikel&error=Judul, Kategori, dan URL Gambar wajib diisi.');
        exit();
    }
    
    // 3. MULAI TRANSAKSI DATABASE
    $mysqli->begin_transaction();

    try {
        // Query 1: Masukkan data ke tabel 'articles'
        $sql_article = "INSERT INTO articles (slug, title, excerpt, content, category, author_id, is_featured, status, published_at) VALUES (?, ?, ?, ?, ?, ?, ?, 'published', NOW())";
        $stmt_article = $mysqli->prepare($sql_article);
        $stmt_article->bind_param('sssssii', $slug, $title, $excerpt, $content, $category, $author_id, $is_featured);
        $stmt_article->execute();

        // Ambil ID artikel yang baru saja dibuat
        $article_id = $mysqli->insert_id;
        
        // Query 2: Masukkan data ke tabel 'article_images'
        $sql_image = "INSERT INTO article_images (article_id, role, url) VALUES (?, 'cover', ?)";
        $stmt_image = $mysqli->prepare($sql_image);
        $stmt_image->bind_param('is', $article_id, $cover_image_url);
        $stmt_image->execute();

        // Jika semua query berhasil, commit transaksi
        $mysqli->commit();
        
        header('Location: index.php?view=artikel&success=Artikel berhasil ditambahkan');

    } catch (mysqli_sql_exception $exception) {
        // Jika ada error, batalkan semua perubahan (rollback)
        $mysqli->rollback();
        
        header('Location: index.php?view=artikel&error=' . urlencode($exception->getMessage()));
    }

    $stmt_article->close();
    $stmt_image->close();
    $mysqli->close();
    exit();
}
?>