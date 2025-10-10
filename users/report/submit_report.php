<?php
require_once __DIR__ . '/../../config/database.php';
header('Content-Type: application/json');

try {
    // ==== Ambil data POST ====
    $reporterNIK   = trim($_POST['reporterNIK'] ?? '');
    if (!preg_match('/^\d{16}$/', $reporterNIK)) {
        throw new Exception('NIK harus terdiri dari 16 digit angka.');
    }
    $schoolName    = trim($_POST['schoolName'] ?? '');
    $eduLevelId    = !empty($_POST['edu_level_id']) ? (int)$_POST['edu_level_id'] : null;
    $provinceSlug  = trim($_POST['province'] ?? '');
    $kabKota       = trim($_POST['kab_kota'] ?? '');
    $address       = trim($_POST['address'] ?? '');
    $latitude      = $_POST['latitude-sekolah'] !== '' ? $_POST['latitude-sekolah'] : null;
    $longitude     = $_POST['longitude-sekolah'] !== '' ? $_POST['longitude-sekolah'] : null;
    $issueSlug     = trim($_POST['issueType'] ?? '');
    $severity      = $_POST['severity'] ?? '';
    $description   = trim($_POST['description'] ?? '');
    $reporterName  = trim($_POST['reporterName'] ?? '');
    $reporterEmail = trim($_POST['reporterEmail'] ?? '');
    $reporterPhone = trim($_POST['reporterPhone'] ?? '');
    $agreeTerms    = isset($_POST['agreeTerms']) ? 1 : 0;

    // ==== Validasi wajib ====
    if (!$schoolName || !$eduLevelId || !$provinceSlug || !$address || !$issueSlug || !$severity || !$description || !$reporterEmail) {
        throw new Exception('Field wajib belum lengkap.');
    }
    if (!in_array($severity, ['low', 'medium', 'high'], true)) {
        throw new Exception('Severity tidak valid.');
    }

    // ==== Ambil ID dari slug ====
    $stmt = $pdo->prepare("SELECT id FROM provinces WHERE slug = ? LIMIT 1");
    $stmt->execute([$provinceSlug]);
    $provinceId = $stmt->fetchColumn();
    if (!$provinceId) throw new Exception('Provinsi tidak ditemukan.');

    $stmt = $pdo->prepare("SELECT id FROM issue_types WHERE slug = ? LIMIT 1");
    $stmt->execute([$issueSlug]);
    $issueTypeId = $stmt->fetchColumn();
    if (!$issueTypeId) throw new Exception('Jenis masalah tidak ditemukan.');

    // ==== Upload files ====
    $uploadedFiles = [];
    $uploadDir = __DIR__ . '/../../uploads/reports/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

    if (!empty($_FILES['attachments']['name'][0])) {
        $allowedExt = ['png', 'jpg', 'jpeg', 'pdf'];
        $maxBytes   = 10 * 1024 * 1024; // 10 MB

        foreach ($_FILES['attachments']['name'] as $i => $name) {
            $tmp  = $_FILES['attachments']['tmp_name'][$i];
            $size = (int)($_FILES['attachments']['size'][$i] ?? 0);
            $err  = (int)($_FILES['attachments']['error'][$i] ?? 0);
            $type = $_FILES['attachments']['type'][$i] ?? 'application/octet-stream';
            $ext  = strtolower(pathinfo($name, PATHINFO_EXTENSION));

            if ($err !== UPLOAD_ERR_OK) continue;
            if (!in_array($ext, $allowedExt, true)) continue;
            if ($size <= 0 || $size > $maxBytes) continue;

            $safeBase = preg_replace('/[^a-zA-Z0-9_\-\.]/', '_', pathinfo($name, PATHINFO_FILENAME));
            $newName  = $safeBase . '_' . uniqid() . '.' . $ext;
            $destPath = $uploadDir . $newName;

            if (move_uploaded_file($tmp, $destPath)) {
                $uploadedFiles[] = [
                    'path' => 'uploads/reports/' . $newName,
                    'mime' => $type,
                    'size' => $size
                ];
            }
        }
    }

    // ==== Insert ke reports ====
    $stmt = $pdo->prepare("
        INSERT INTO reports
(school_name, edu_level_id, province_id, kab_kota, address, latitude, longitude, issue_type_id, severity, description,
 reporter_name, reporter_nik, reporter_email, reporter_phone, agree_terms, status, created_at)
VALUES
(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', NOW())
    ");
    $stmt->execute([
        $schoolName,
        $eduLevelId,
        $provinceId,
        $kabKota,
        $address,
        $latitude,
        $longitude,
        $issueTypeId,
        $severity,
        $description,
        $reporterName,
        $reporterNIK,
        $reporterEmail,
        $reporterPhone,
        $agreeTerms
    ]);
    $reportId = $pdo->lastInsertId();

    // ==== Insert ke report_attachments ====
    if ($uploadedFiles) {
        $stmtA = $pdo->prepare("
            INSERT INTO report_attachments (report_id, file_path, mime_type, file_size)
            VALUES (?, ?, ?, ?)
        ");
        foreach ($uploadedFiles as $f) {
            $stmtA->execute([$reportId, $f['path'], $f['mime'], $f['size']]);
        }
    }

    echo json_encode(['success' => true, 'report_id' => $reportId]);
} catch (Throwable $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
