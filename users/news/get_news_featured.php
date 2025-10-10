<?php
// /api/get_news_featured.php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../config/database.php';

try {
  $limit = max(1, min(5, (int)($_GET['limit'] ?? 1))); // default ambil 1 featured

  $sql = "
    SELECT a.id, a.slug, a.title, a.excerpt, a.category, a.published_at,
           u.full_name AS author_name,
           i.url AS cover_url, i.alt AS cover_alt
    FROM articles a
    LEFT JOIN users u ON u.id = a.author_id
    LEFT JOIN article_images i ON i.article_id = a.id AND i.role='cover'
    WHERE a.status='published' AND a.is_featured=1
    ORDER BY a.published_at DESC
    LIMIT :limit
  ";
  $st = $pdo->prepare($sql);
  $st->bindValue(':limit', $limit, PDO::PARAM_INT);
  $st->execute();

  $rows = $st->fetchAll(PDO::FETCH_ASSOC);

  echo json_encode([
    'data' => $rows,
    'limit' => $limit,
    'total' => count($rows),
  ]);
} catch (Throwable $e) {
  http_response_code(500);
  echo json_encode(['error' => true, 'message' => $e->getMessage()]);
}
