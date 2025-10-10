<?php
// /users/news/get_article_detail.php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../config/database.php';

try {
  if (!isset($_GET['slug']) || $_GET['slug'] === '') {
    http_response_code(422);
    echo json_encode(['error'=>true,'message'=>'Parameter slug wajib diisi']);
    exit;
  }
  $slug = $_GET['slug'];

  // safety
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // ambil artikel utama
  $sql = "
    SELECT a.id, a.slug, a.title, a.excerpt, a.content, a.category, a.is_featured,
           a.status, a.published_at, a.created_at, a.updated_at,
           u.full_name AS author_name,
           i.url AS cover_url, i.alt AS cover_alt, i.width AS cover_width, i.height AS cover_height
    FROM articles a
    LEFT JOIN users u ON u.id = a.author_id
    LEFT JOIN article_images i ON i.article_id = a.id AND i.role='cover'
    WHERE a.slug = :slug AND a.status='published'
    LIMIT 1
  ";
  $st = $pdo->prepare($sql);
  $st->bindValue(':slug', $slug);
  $st->execute();
  $article = $st->fetch(PDO::FETCH_ASSOC);

  if (!$article) {
    http_response_code(404);
    echo json_encode(['error'=>true,'message'=>'Artikel tidak ditemukan']);
    exit;
  }

  // ambil gallery
  $sqlGal = "
    SELECT id, url, alt, width, height, position
    FROM article_images
    WHERE article_id = :aid AND role='gallery'
    ORDER BY position ASC, id ASC
  ";
  $stg = $pdo->prepare($sqlGal);
  $stg->bindValue(':aid', $article['id'], PDO::PARAM_INT);
  $stg->execute();
  $gallery = $stg->fetchAll(PDO::FETCH_ASSOC);

  echo json_encode([
    'data' => [
      'article' => $article,
      'gallery' => $gallery,
    ]
  ]);
} catch (Throwable $e) {
  http_response_code(500);
  echo json_encode(['error'=>true,'message'=>$e->getMessage()]);
}
