<?php
// /users/reports/get_report_detail.php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../config/database.php';

try {
  if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    http_response_code(422);
    echo json_encode(['error'=>true,'message'=>'Parameter id wajib berupa angka']);
    exit;
  }
  $id = (int)$_GET['id'];

  // pastikan PDO lempar exception
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // OPTIONAL: kalau ada tabel referensi (ganti nama join sesuai skema kamu)
  // misal: edu_levels(id,name), issue_types(id,name), provinces(id,name)
  $sql = "
    SELECT
      r.id, r.user_id, r.school_name, r.edu_level_id, r.province_id, r.kab_kota, r.address,
      r.issue_type_id, r.severity, r.description,
      r.reporter_name, r.reporter_nik, r.reporter_email, r.reporter_phone,
      r.agree_terms, r.status, r.latitude, r.longitude, r.created_at, r.updated_at,

      el.name  AS edu_level_name,
      it.name  AS issue_type_name,
      p.name   AS province_name,

      u.full_name AS user_full_name
    FROM reports r
    LEFT JOIN users u         ON u.id = r.user_id
    LEFT JOIN edu_levels el   ON el.id = r.edu_level_id
    LEFT JOIN issue_types it  ON it.id = r.issue_type_id
    LEFT JOIN provinces p     ON p.id = r.province_id
    WHERE r.id = :id
    LIMIT 1
  ";

  $st = $pdo->prepare($sql);
  $st->bindValue(':id', $id, PDO::PARAM_INT);
  $st->execute();
  $report = $st->fetch(PDO::FETCH_ASSOC);

  if (!$report) {
    http_response_code(404);
    echo json_encode(['error'=>true,'message'=>'Report tidak ditemukan']);
    exit;
  }

  // Ambil attachments
  $sqlA = "SELECT id, file_path, mime_type, file_size, created_at
           FROM report_attachments
           WHERE report_id = :rid
           ORDER BY id ASC";
  $sta = $pdo->prepare($sqlA);
  $sta->bindValue(':rid', $report['id'], PDO::PARAM_INT);
  $sta->execute();
  $attachments = $sta->fetchAll(PDO::FETCH_ASSOC);

  // Masking ringan data pelapor (privasi)
  $maskEmail = function($email) {
    if (!$email) return null;
    if (!str_contains($email, '@')) return substr($email,0,2).'***';
    [$u,$d] = explode('@', $email, 2);
    $u2 = mb_substr($u,0,2) . str_repeat('*', max(0, mb_strlen($u)-2));
    return $u2.'@'.$d;
  };
  $maskPhone = function($p) {
    if (!$p) return null;
    $len = mb_strlen($p);
    if ($len <= 4) return '****';
    return mb_substr($p,0,3) . str_repeat('*', max(0,$len-6)) . mb_substr($p,-3);
  };
  $maskNik = function($n) {
    if (!$n) return null;
    $len = mb_strlen($n);
    if ($len <= 6) return str_repeat('*',$len);
    return mb_substr($n,0,4) . str_repeat('*', max(0,$len-8)) . mb_substr($n,-4);
  };

  $report['reporter_email_masked'] = $maskEmail($report['reporter_email']);
  $report['reporter_phone_masked'] = $maskPhone($report['reporter_phone']);
  $report['reporter_nik_masked']   = $maskNik($report['reporter_nik']);

  echo json_encode([
    'data' => [
      'report'      => $report,
      'attachments' => $attachments
    ]
  ]);

} catch (Throwable $e) {
  http_response_code(500);
  echo json_encode(['error'=>true, 'message'=>$e->getMessage()]);
}
