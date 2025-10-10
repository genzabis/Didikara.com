<?php
require_once __DIR__ . '/../../config/database.php';

// Ambil data laporan + jenis masalah + provinsi
$query = "
  SELECT 
    r.id,
    r.school_name AS schoolName,
    r.address,
    p.name AS province,
    i.name AS issueType,
    r.description,
    r.severity,
    r.status,
    DATE_FORMAT(r.created_at, '%Y-%m-%d') AS date,
    r.latitude AS lat,
    r.longitude AS lng
  FROM reports r
  LEFT JOIN provinces p ON r.province_id = p.id
  LEFT JOIN issue_types i ON r.issue_type_id = i.id
  WHERE r.latitude IS NOT NULL AND r.longitude IS NOT NULL
  ORDER BY r.created_at DESC
";

$stmt = $pdo->query($query);
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
