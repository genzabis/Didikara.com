<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../config/database.php';

try {
    // Pastikan PDO lempar exception
    if (isset($pdo) && $pdo instanceof PDO) {
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // Jika tetap ingin reuse nama placeholder yg sama, boleh nyalain ini:
        // $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
    }

    $q       = trim($_GET['q'] ?? '');
    $page    = max(1, (int)($_GET['page'] ?? 1));
    $limit   = max(1, min(24, (int)($_GET['limit'] ?? 9)));
    $offset  = ($page - 1) * $limit;

    $params = [];
    $where  = "a.status='published'";
    $selectRelevance = '';
    $order = 'a.published_at DESC';

    // Tentukan mode pencarian
    $useLikeOnly = false;
    if ($q !== '') {
        // untuk query sangat pendek / lingkungan dengan min token fulltext tinggi
        if (mb_strlen($q) < 3) {
            $useLikeOnly = true;
        }

        $like = '%' . str_replace(['%', '_'], ['\\%', '\\_'], $q) . '%';

        if (!$useLikeOnly) {
            // NOTE: gunakan :q1 dan :q2 (placeholder berbeda)
            $where .= " AND (
        MATCH(a.title, a.excerpt, a.content) AGAINST(:q2 IN NATURAL LANGUAGE MODE)
        OR a.title   LIKE :like1
        OR a.excerpt LIKE :like2
      )";
            $params[':q1']    = $q;   // untuk SELECT (relevance)
            $params[':q2']    = $q;   // untuk WHERE
            $params[':like1'] = $like;
            $params[':like2'] = $like;

            $selectRelevance = ", MATCH(a.title, a.excerpt, a.content) AGAINST(:q1 IN NATURAL LANGUAGE MODE) AS relevance";
            $order = "relevance DESC, a.published_at DESC";
        } else {
            // LIKE-only fallback
            $where .= " AND (
        a.title   LIKE :like1
        OR a.excerpt LIKE :like2
        OR a.content LIKE :like3
      )";
            $params[':like1'] = $like;
            $params[':like2'] = $like;
            $params[':like3'] = $like;
        }
    }

    // Hitung total
    $sqlCount = "SELECT COUNT(*) FROM articles a WHERE $where";
    $st = $pdo->prepare($sqlCount);
    foreach ($params as $k => $v) {
        // Bind hanya placeholder yang benar-benar dipakai di query ini
        if (strpos($sqlCount, $k) !== false) {
            $st->bindValue($k, $v);
        }
    }
    $st->execute();
    $total = (int)$st->fetchColumn();

    // Ambil data
    $sql = "
    SELECT a.id, a.slug, a.title, a.excerpt, a.category, a.published_at,
           u.full_name AS author_name,
           i.url AS cover_url, i.alt AS cover_alt
           $selectRelevance
    FROM articles a
    LEFT JOIN users u ON u.id = a.author_id
    LEFT JOIN article_images i ON i.article_id = a.id AND i.role='cover'
    WHERE $where
    ORDER BY $order
    LIMIT :limit OFFSET :offset
  ";
    $st = $pdo->prepare($sql);
    foreach ($params as $k => $v) {
        if (strpos($sql, $k) !== false) {
            $st->bindValue($k, $v);
        }
    }
    $st->bindValue(':limit',  $limit,  PDO::PARAM_INT);
    $st->bindValue(':offset', $offset, PDO::PARAM_INT);
    $st->execute();

    $rows = $st->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'data'        => $rows,
        'page'        => $page,
        'limit'       => $limit,
        'total'       => $total,
        'total_pages' => (int)ceil(max(0, $total) / $limit),
        'q'           => $q,
    ]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['error' => true, 'message' => $e->getMessage()]);
}
