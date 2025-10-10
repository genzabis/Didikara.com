<?php
// file: public/api/reports_list.php

ini_set('display_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');

// Fungsi untuk mengirim response error JSON dan menghentikan script
function send_json_error($message, $code = 500)
{
    http_response_code($code);
    echo json_encode(['error' => $message]);
    exit();
}

try {
    require_once __DIR__ . '/../../config/database.php';

    // Pastikan koneksi $pdo ada
    if (!isset($pdo)) {
        send_json_error('Koneksi database tidak terdefinisi.');
    }

    $input = json_decode(file_get_contents('php://input'), true) ?: [];

    // Ambil parameter
    $q          = trim($input['q'] ?? '');
    $province   = trim($input['province'] ?? '');
    $issues     = $input['issues'] ?? [];
    $severities = $input['severities'] ?? [];
    $statuses   = $input['statuses'] ?? [];
    $date_from  = trim($input['date_from'] ?? '');
    $date_to    = trim($input['date_to'] ?? '');
    $sort       = trim($input['sort'] ?? 'newest');
    $page       = max(1, (int)($input['page'] ?? 1));
    $per_page   = max(1, min(24, (int)($input['per_page'] ?? 10)));

    // Whitelist sorting
    $sortExpr = 'r.created_at DESC';
    switch ($sort) {
        case 'oldest':
            $sortExpr = 'r.created_at ASC';
            break;
        case 'severity_high':
            $sortExpr = "FIELD(r.severity,'high','medium','low'), r.created_at DESC";
            break;
        case 'severity_low':
            $sortExpr = "FIELD(r.severity,'low','medium','high'), r.created_at DESC";
            break;
    }

    // Build WHERE
    $where = [];
    $params = [];

    // Keyword: cari di school_name, address, dan issue_types.name
    if ($q !== '') {
        $where[] = "(r.school_name LIKE :q1 OR r.address LIKE :q2 OR COALESCE(it.name, '') LIKE :q3)";
        $params[':q1'] = "%{$q}%";
        $params[':q2'] = "%{$q}%";
        $params[':q3'] = "%{$q}%";
    }

    // Filter lainnya (sudah cukup baik)
    if ($province !== '') {
        $where[] = "p.slug = :prov";
        $params[':prov'] = $province;
    }

    if (is_array($issues) && count($issues) > 0) {
        $in = [];
        foreach ($issues as $i => $slug) {
            $key = ":it{$i}";
            $in[] = $key;
            $params[$key] = $slug;
        }
        $where[] = "it.slug IN (" . implode(',', $in) . ")";
    }

    if (is_array($severities) && count($severities) > 0) {
        $validSev = array_values(array_intersect($severities, ['low', 'medium', 'high']));
        if (count($validSev) > 0) {
            $in = [];
            foreach ($validSev as $i => $sev) {
                $key = ":sev{$i}";
                $in[] = $key;
                $params[$key] = $sev;
            }
            $where[] = "r.severity IN (" . implode(',', $in) . ")";
        }
    }

    if (is_array($statuses) && count($statuses) > 0) {
        $validSt = array_values(array_intersect($statuses, ['pending', 'confirmed', 'investigating', 'resolved', 'rejected', 'archived']));
        if (count($validSt) > 0) {
            $in = [];
            foreach ($validSt as $i => $st) {
                $key = ":st{$i}";
                $in[] = $key;
                $params[$key] = $st;
            }
            $where[] = "r.status IN (" . implode(',', $in) . ")";
        }
    }

    if ($date_from !== '' && preg_match('/^\d{4}-\d{2}-\d{2}$/', $date_from)) {
        $where[] = "DATE(r.created_at) >= :dfrom";
        $params[':dfrom'] = $date_from;
    }
    if ($date_to !== '' && preg_match('/^\d{4}-\d{2}-\d{2}$/', $date_to)) {
        $where[] = "DATE(r.created_at) <= :dto";
        $params[':dto'] = $date_to;
    }

    $whereSql = count($where) ? ('WHERE ' . implode(' AND ', $where)) : '';

    // Base query untuk join
    $baseQuery = "
        FROM reports r
        LEFT JOIN provinces p ON p.id = r.province_id
        LEFT JOIN issue_types it ON it.id = r.issue_type_id
        $whereSql
    ";

    // Count total
    $stmt = $pdo->prepare("SELECT COUNT(DISTINCT r.id) AS c " . $baseQuery);
    $stmt->execute($params);
    $total = (int)$stmt->fetchColumn();

    // Pagination
    $pages = ($total > 0) ? (int)ceil($total / $per_page) : 1;
    $page  = min($page, $pages);
    $offset = ($page - 1) * $per_page;

    // Data query
    $sql = "
        SELECT
            r.id, r.school_name, r.address, r.severity, r.status,
            DATE(r.created_at) AS date, r.created_at, r.description,
            r.reporter_name, r.latitude, r.longitude,
            p.name AS province_name, p.slug AS province_slug,
            it.name AS issue_name, it.slug AS issue_slug
        " . $baseQuery . "
        ORDER BY $sortExpr
        LIMIT :limit OFFSET :offset
    ";

    $stmt = $pdo->prepare($sql);
    foreach ($params as $k => $v) {
        $stmt->bindValue($k, $v);
    }
    $stmt->bindValue(':limit', $per_page, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Output sukses
    echo json_encode([
        'data' => $data,
        'total' => $total,
        'page' => $page,
        'pages' => $pages,
        'per_page' => $per_page
    ]);
} catch (\Throwable $e) {
    // Jika ada error apapun di blok try, tangkap di sini
    // Kirim response JSON error yang terstruktur
    // (Optional: log error ke file untuk debugging di sisi server)
    // error_log($e->getMessage()); 
    send_json_error('Terjadi kesalahan pada server. ' . $e->getMessage());
}

