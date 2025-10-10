<?php
require_once __DIR__ . '/../../config/database.php';

$provinces = $pdo->query("SELECT slug, name FROM provinces ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);
$issues    = $pdo->query("SELECT slug, name FROM issue_types ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);
$edu_levels = $pdo->query("SELECT id, name FROM edu_levels ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC); // <--- TAMBAHKAN INI

function e($s)
{
    return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');
}
?>

<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class="p-6 border-b border-gray-100">
        <h2 class="text-xl font-bold text-indigo-900">Form Pelaporan (Kamera + Lokasi)</h2>
        <p class="text-gray-600 mt-1">Aktifkan GPS & ambil foto langsung dari kamera.</p>
    </div>

    <div class="p-6">
        <!-- Progress -->
        <div class="relative mb-8">
            <div class="overflow-hidden h-2 text-xs flex rounded bg-indigo-100">
                <div id="progress-bar" class="bg-indigo-600 transition-all duration-500" style="width: 33.33%"></div>
            </div>
            <div class="flex justify-between text-xs text-indigo-800 mt-2">
                <div class="step-indicator active">Sekolah & Lokasi</div>
                <div class="step-indicator">Foto & Masalah</div>
                <div class="step-indicator">Identitas Pelapor</div>
            </div>
        </div>

        <form id="report-form" method="post" action="/didikara.com/users/report/submit_report.php" enctype="multipart/form-data" novalidate>
            <!-- STEP 1 -->
            <div id="step-1" class="form-step active">
                <div class="space-y-6">
                    <div class="flex items-center mb-2">
                        <i class="fas fa-school text-indigo-600 mr-2 text-2xl"></i>
                        <h3 class="text-lg font-semibold text-indigo-900">Sekolah & Lokasi</h3>
                    </div>

                    <!-- Autocomplete Sekolah (Maps) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Sekolah <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input id="schoolName" name="schoolName" type="text" required autocomplete="off"
                                class="w-full px-4 py-2 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                placeholder="Ketik nama sekolah...">
                            <div id="school-suggest" class="absolute z-30 mt-1 w-full bg-white border border-gray-200 rounded-md shadow-lg hidden max-h-60 overflow-auto"></div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Ketik ≥3 huruf untuk cari di peta. Pilih salah satu agar titik lokasi presisi.</p>
                    </div>

                    <!-- Jenjang Pendidikan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jenjang Pendidikan <span class="text-red-500">*</span></label>
                        <select name="edu_level_id" required
                            class="w-full px-4 py-2 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Pilih Jenjang</option>
                            <?php foreach ($edu_levels as $level): ?>
                                <option value="<?= e($level['id']) ?>"><?= e($level['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Provinsi -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Provinsi <span class="text-red-500">*</span></label>
                        <select name="province" required
                            class="w-full px-4 py-2 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Pilih Provinsi</option>
                            <?php foreach ($provinces as $p): ?>
                                <option value="<?= e($p['slug']) ?>"><?= e($p['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Alamat -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap <span class="text-red-500">*</span></label>
                        <textarea name="address" rows="3" required
                            class="w-full px-4 py-2 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                            placeholder="Kecamatan, Kabupaten/Kota, dst..."></textarea>
                        <div class="flex items-center gap-2 mt-2 hidden">
                            <button type="button" id="btn-get-location"
                                class="inline-flex items-center gap-2 px-3 py-2 bg-indigo-50 text-indigo-700 rounded-md border border-indigo-200 hover:bg-indigo-100">
                                <i class="fas fa-location-arrow"></i><span>Gunakan Lokasi Saat Ini</span>
                            </button>
                            <span id="gps-status" class="text-xs text-gray-500">GPS wajib aktif.</span>
                        </div>
                        <input type="hidden" name="latitude" id="latitude" required>
                        <input type="hidden" name="longitude" id="longitude" required>
                        <input type="hidden" name="latitude-sekolah" id="latitude-sekolah" required>
                        <input type="hidden" name="longitude-sekolah" id="longitude-sekolah" required>
                        <input type="hidden" name="kab_kota" id="kab-kota-input">
                        <div id="mini-map" class="w-full h-48 mt-3 rounded-md border"></div>
                        <p id="distance-info" class="text-sm text-indigo-700 font-medium mt-2 hidden"></p>
                    </div>

                    <div class="flex justify-end">
                        <button type="button" onclick="nextStep()" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md font-medium">Lanjutkan</button>
                    </div>
                </div>
            </div>

            <!-- STEP 2 (header only di part A, isi di part B) -->