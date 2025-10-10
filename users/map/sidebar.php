<?php
require_once __DIR__ . '/../../config/database.php';

// Ambil daftar provinsi & issue types dari DB
$provinces = $pdo->query("SELECT slug, name FROM provinces ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);
$issues    = $pdo->query("SELECT slug, name FROM issue_types ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);

// Helper esc
function e($s)
{
    return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');
}
?>

<div class="h-full">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-indigo-900 mb-4">Pencarian & Filter</h2>

        <form id="search-form" class="mb-6">
            <div class="relative">
                <input id="q" type="text" placeholder="Cari sekolah atau lokasi..."
                    class="w-full px-4 py-2 pr-10 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-indigo-700" aria-label="Cari">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </form>

        <div class="mb-6">
            <h3 class="flex items-center text-md font-semibold text-gray-700 mb-3">
                <i class="fas fa-filter mr-2"></i> Filter Laporan
            </h3>

            <div class="space-y-4">
                <!-- Provinsi (dinamis) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Provinsi</label>
                    <select id="f-province"
                        class="w-full px-3 py-2 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Semua Provinsi</option>
                        <?php foreach ($provinces as $p): ?>
                            <option value="<?= e($p['slug']) ?>"><?= e($p['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Jenis Masalah (dinamis) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Masalah</label>
                    <select id="f-issue"
                        class="w-full px-3 py-2 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Semua Masalah</option>
                        <?php foreach ($issues as $it): ?>
                            <option value="<?= e($it['slug']) ?>"><?= e($it['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Tingkat Keparahan -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tingkat Keparahan</label>
                    <div class="flex flex-wrap gap-4">
                        <label class="inline-flex items-center">
                            <input id="sev-high" type="checkbox" class="h-4 w-4 text-red-500" checked />
                            <span class="ml-2 text-sm text-gray-700">Tinggi</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input id="sev-medium" type="checkbox" class="h-4 w-4 text-amber-500" checked />
                            <span class="ml-2 text-sm text-gray-700">Sedang</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input id="sev-low" type="checkbox" class="h-4 w-4 text-teal-500" checked />
                            <span class="ml-2 text-sm text-gray-700">Rendah</span>
                        </label>
                    </div>
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <div class="flex flex-wrap gap-4">
                        <label class="inline-flex items-center">
                            <input id="st-pending" type="checkbox" class="h-4 w-4 text-amber-500" checked />
                            <span class="ml-2 text-sm text-gray-700">Menunggu</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input id="st-inprog" type="checkbox" class="h-4 w-4 text-blue-500" checked />
                            <span class="ml-2 text-sm text-gray-700">Proses</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input id="st-resolved" type="checkbox" class="h-4 w-4 text-teal-500" checked />
                            <span class="ml-2 text-sm text-gray-700">Selesai</span>
                        </label>
                    </div>
                </div>

                <button id="apply"
                    class="w-full px-4 py-2 bg-amber-400 hover:bg-amber-500 text-indigo-900 rounded-md font-medium text-sm transition-colors">
                    Terapkan Filter
                </button>
            </div>
        </div>

        <div>
            <h3 class="text-md font-semibold text-gray-700 mb-3">Legenda</h3>
            <div class="space-y-2 text-sm">
                <div class="flex items-center">
                    <span class="inline-block w-4 h-4 rounded-full bg-red-500 mr-2"></span>
                    <span class="text-gray-700">Masalah Tingkat Tinggi</span>
                </div>
                <div class="flex items-center">
                    <span class="inline-block w-4 h-4 rounded-full bg-amber-500 mr-2"></span>
                    <span class="text-gray-700">Masalah Tingkat Sedang</span>
                </div>
                <div class="flex items-center">
                    <span class="inline-block w-4 h-4 rounded-full bg-teal-500 mr-2"></span>
                    <span class="text-gray-700">Masalah Tingkat Rendah</span>
                </div>
            </div>
        </div>

    </div>
</div>