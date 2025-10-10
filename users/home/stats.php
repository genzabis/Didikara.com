<?php
require_once __DIR__ . '/../../config/database.php';

$cacheFile = __DIR__ . '/../../cache/stats.json';
$cacheTime = 300;

if (file_exists($cacheFile) && (time() - filemtime($cacheFile) < $cacheTime)) {
    $stats = json_decode(file_get_contents($cacheFile), true);
} else {
    $stats = [
        'schools' => (int)($pdo->query("SELECT COUNT(DISTINCT school_name) FROM reports")->fetchColumn() ?? 0),
        'provinces' => (int)($pdo->query("SELECT COUNT(DISTINCT province_id) FROM reports WHERE province_id IS NOT NULL")->fetchColumn() ?? 0),
        'resolved_percent' => (float)($pdo->query("
            SELECT IFNULL(ROUND(SUM(CASE WHEN status='resolved' THEN 1 ELSE 0 END) / COUNT(*) * 100, 1), 0)
            FROM reports
        ")->fetchColumn() ?? 0)
    ];

    // 3️⃣ Simpan ke cache
    if (!is_dir(dirname($cacheFile))) mkdir(dirname($cacheFile), 0777, true);
    file_put_contents($cacheFile, json_encode($stats, JSON_PRETTY_PRINT));
}
?>
