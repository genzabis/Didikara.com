<?php
// get_report_details.php

// Panggil file konfigurasi untuk mendapatkan BASE_URL
require_once 'config.php';

// 1. KONEKSI DATABASE
$host = 'localhost';
$user = 'argtgbgt_db_didikara'; // User default XAMPP/MariaDB
$pass = 'pWK^hRLZJ-V64CQs';     // Password default XAMPP/MariaDB kosong
$db   = 'argtgbgt_db_ddkr';
$mysqli = new mysqli($host, $user, $pass, $db);
if ($mysqli->connect_error) { die("Koneksi gagal"); }

// 2. AMBIL ID LAPORAN DENGAN AMAN
$report_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($report_id === 0) { die("ID Laporan tidak valid."); }

// 3. QUERY UNTUK MENGAMBIL SEMUA DETAIL
// Query ini sudah mengambil semua data yang kita butuhkan
$sql = "
    SELECT 
        r.id, r.school_name, r.address, r.description, r.severity, r.status, r.created_at,
        r.reporter_name, r.reporter_nik, r.reporter_email, r.reporter_phone,
        r.latitude, r.longitude,
        p.name as province_name,
        it.name as issue_name,
        ra.file_path
    FROM reports r
    LEFT JOIN provinces p ON r.province_id = p.id
    LEFT JOIN issue_types it ON r.issue_type_id = it.id
    LEFT JOIN report_attachments ra ON r.id = ra.report_id
    WHERE r.id = ?
";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('i', $report_id);
$stmt->execute();
$report = $stmt->get_result()->fetch_assoc();
$stmt->close();
$mysqli->close();

if (!$report) { die("Data laporan tidak ditemukan."); }

// FUNGSI BANTU BARU UNTUK SEVERITY
function getSeverityBadge($severity) {
    $styles = ['low' => 'bg-green-100 text-green-800', 'medium' => 'bg-amber-100 text-amber-800', 'high' => 'bg-red-100 text-red-800'];
    return "<span class='inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {$styles[$severity]}'>" . ucfirst($severity) . "</span>";
}

// 4. TAMPILKAN DATA DALAM FORMAT HTML LENGKAP
?>
<div class="space-y-6">
    <div>
        <h3 class="font-semibold text-gray-700">Informasi Laporan</h3>
        <div class="grid grid-cols-2 gap-x-4 gap-y-2 mt-2 text-sm">
            <span class="text-gray-500">Nama Sekolah:</span>
            <span class="text-gray-800 font-medium col-span-1"><?= htmlspecialchars($report['school_name']) ?></span>
            
            <span class="text-gray-500">Tanggal Dilaporkan:</span>
            <span class="text-gray-800"><?= date('d M Y, H:i', strtotime($report['created_at'])) ?> WIB</span>

            <span class="text-gray-500">Provinsi:</span>
            <span class="text-gray-800"><?= htmlspecialchars($report['province_name'] ?? 'N/A') ?></span>

            <span class="text-gray-500">Jenis Masalah:</span>
            <span class="text-gray-800"><?= htmlspecialchars($report['issue_name']) ?></span>
            
            <span class="text-gray-500">Alamat:</span>
            <span class="text-gray-800 col-span-1"><?= htmlspecialchars($report['address'] ?? 'N/A') ?></span>

            <span class="text-gray-500">Tingkat Keparahan:</span>
            <span class="text-gray-800"><?= getSeverityBadge($report['severity']) ?></span>
        </div>
    </div>

    <div>
        <h3 class="font-semibold text-gray-700">Deskripsi Masalah</h3>
        <p class="text-sm text-gray-800 bg-gray-50 p-3 rounded-md mt-2 whitespace-pre-wrap"><?= htmlspecialchars($report['description']) ?></p>
    </div>

    <div>
        <h3 class="font-semibold text-gray-700">Data Pelapor</h3>
        <div class="grid grid-cols-2 gap-x-4 gap-y-2 mt-2 text-sm">
            <span class="text-gray-500">Nama:</span>
            <span class="text-gray-800 font-medium"><?= htmlspecialchars($report['reporter_name'] ?? 'N/A') ?></span>
            
            <span class="text-gray-500">NIK:</span>
            <span class="text-gray-800"><?= htmlspecialchars($report['reporter_nik'] ?? 'N/A') ?></span>

            <span class="text-gray-500">Email:</span>
            <span class="text-gray-800"><?= htmlspecialchars($report['reporter_email'] ?? 'N/A') ?></span>

            <span class="text-gray-500">Telepon:</span>
            <span class="text-gray-800"><?= htmlspecialchars($report['reporter_phone'] ?? 'N/A') ?></span>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <h3 class="font-semibold text-gray-700">Lokasi (Google Maps)</h3>
            <div class="mt-2">
                <?php if (!empty($report['latitude']) && !empty($report['longitude'])): ?>
                    <a href="https://www.google.com/maps?q=<?= $report['latitude'] ?>,<?= $report['longitude'] ?>" target="_blank"
                       class="inline-block text-sm text-indigo-600 bg-indigo-50 hover:bg-indigo-100 rounded-md px-3 py-2">
                        <i class="fas fa-map-marker-alt mr-2"></i>Buka di Google Maps
                    </a>
                <?php else: ?>
                    <p class="text-sm text-gray-500">Data lokasi tidak tersedia.</p>
                <?php endif; ?>
            </div>
        </div>
        <div>
            <h3 class="font-semibold text-gray-700">Lampiran Foto</h3>
            <div class="mt-2">
                <?php if (!empty($report['file_path'])): ?>
                    <a href="<?= BASE_URL . htmlspecialchars($report['file_path']) ?>" target="_blank">
                        <img src="<?= BASE_URL . htmlspecialchars($report['file_path']) ?>" alt="Lampiran Laporan" class="w-full h-auto max-h-48 object-cover rounded-md border hover:opacity-80 transition-opacity">
                    </a>
                <?php else: ?>
                    <p class="text-sm text-gray-500">Tidak ada lampiran foto.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>