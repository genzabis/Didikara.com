<?php
require_once __DIR__ . '/../../config/database.php';

// Ambil opsi filter dari DB
$provinces = $pdo->query("SELECT slug, name FROM provinces ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);
$issues    = $pdo->query("SELECT slug, name FROM issue_types ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);

// helper
function e($s)
{
    return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');
}
?>
<!-- Main Content -->
<div class="pt-24 pb-16 min-h-screen">
    <div class="container mx-auto px-4 md:px-6">
        <div class="mb-8">
            <h1 class="text-2xl md:text-3xl font-bold text-indigo-900 mb-4">Cari & Filter Laporan</h1>
            <p class="text-gray-600 max-w-3xl">
                Temukan laporan masalah pendidikan berdasarkan lokasi, jenis masalah, atau kata kunci tertentu.
                Gunakan filter untuk mempersempit hasil pencarian Anda.
            </p>
        </div>

        <!-- Search -->
        <div class="mb-8">
            <form id="search-form" class="flex w-full max-w-3xl" onsubmit="return false;">
                <div class="relative flex-grow">
                    <input
                        id="q"
                        name="q"
                        type="text"
                        placeholder="Cari berdasarkan nama sekolah, lokasi, atau kata kunci..."
                        class="w-full px-4 py-3 pl-12 rounded-l-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                    <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                </div>
                <button
                    id="btn-search"
                    type="submit"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-r-md transition-colors">
                    Cari
                </button>
            </form>
        </div>

        <!-- Filter toggle (mobile) -->
        <button
            id="filter-toggle"
            class="md:hidden flex items-center mb-4 px-4 py-2 bg-indigo-50 text-indigo-700 rounded-md">
            <i class="fas fa-sliders-h mr-2"></i>
            <span id="filter-text">Tampilkan Filter</span>
        </button>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Filter Sidebar -->
            <aside id="filter-sidebar" class="hidden md:block">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-indigo-900 flex items-center">
                            <i class="fas fa-filter mr-2"></i> Filter Laporan
                        </h3>
                        <button id="btn-reset" type="button" class="text-sm text-indigo-600 hover:text-indigo-800">Reset</button>
                    </div>

                    <div class="space-y-6">
                        <!-- Provinsi -->
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 mb-3">Provinsi</h4>
                            <select id="f-province" name="province" class="w-full px-3 py-2 text-sm rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Semua Provinsi</option>
                                <?php foreach ($provinces as $p): ?>
                                    <option value="<?= e($p['slug']) ?>"><?= e($p['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Jenis Masalah -->
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 mb-3">Jenis Masalah</h4>
                            <div id="issue-group" class="space-y-2">
                                <?php foreach ($issues as $it): ?>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="issue[]" value="<?= e($it['slug']) ?>" class="form-checkbox h-4 w-4 text-indigo-600 rounded" />
                                        <span class="ml-2 text-sm text-gray-700"><?= e($it['name']) ?></span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- Tingkat Keparahan -->
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 mb-3">Tingkat Keparahan</h4>
                            <div class="space-y-2">
                                <?php foreach (['high' => 'Tinggi', 'medium' => 'Sedang', 'low' => 'Rendah'] as $val => $label): ?>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="severity[]" value="<?= $val ?>" class="form-checkbox h-4 w-4 text-indigo-600 rounded" checked />
                                        <span class="ml-2 text-sm text-gray-700"><?= $label ?></span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- Status -->
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 mb-3">Status</h4>
                            <div class="space-y-2">
                                <?php
                                // mapping status yang ada di schema
                                $statusList = [
                                    'pending' => 'Menunggu',
                                    'confirmed' => 'Terkonfirmasi',
                                    'investigating' => 'Investigasi',
                                    'resolved' => 'Terselesaikan',
                                    'rejected' => 'Ditolak',
                                    'archived' => 'Arsip'
                                ];
                                foreach ($statusList as $val => $label): ?>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="status[]" value="<?= $val ?>" class="form-checkbox h-4 w-4 text-indigo-600 rounded" <?= in_array($val, ['pending', 'investigating', 'resolved']) ? 'checked' : '' ?> />
                                        <span class="ml-2 text-sm text-gray-700"><?= $label ?></span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- Rentang Waktu -->
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 mb-3">Rentang Waktu</h4>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="text-xs text-gray-500">Dari</label>
                                    <input id="date_from" type="date" class="w-full mt-1 px-3 py-2 text-sm rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                                </div>
                                <div>
                                    <label class="text-xs text-gray-500">Sampai</label>
                                    <input id="date_to" type="date" class="w-full mt-1 px-3 py-2 text-sm rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                                </div>
                            </div>
                        </div>

                        <button id="btn-apply" class="w-full px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md font-medium transition-colors">
                            Terapkan Filter
                        </button>
                    </div>
                </div>
            </aside>

            <!-- Search Results -->
            <section class="md:col-span-2">
                <div class="space-y-6">
                    <div class="flex justify-between items-center">
                        <p id="result-count" class="text-gray-600 text-sm md:text-base">Menampilkan 0 hasil</p>
                        <div class="flex items-center space-x-2">
                            <span class="text-sm text-gray-600">Urutkan:</span>
                            <select id="sort" class="text-sm border border-gray-300 rounded-md px-2 py-1 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="newest">Terbaru</option>
                                <option value="oldest">Terlama</option>
                                <option value="severity_high">Keparahan: Tinggi → Rendah</option>
                                <option value="severity_low">Keparahan: Rendah → Tinggi</option>
                            </select>
                        </div>
                    </div>

                    <!-- Hasil -->
                    <div id="results" class="space-y-4">
                        <!-- JS akan render kartu laporan di sini -->
                    </div>

                    <!-- Pagination -->
                    <div id="pagination" class="flex justify-center mt-8"></div>
                </div>
            </section>
        </div>
    </div>
</div>

<script src="./assets/js/script.js"></script>

<script>
    // ====== Toggle filter (mobile) ======
    const filterToggle = document.getElementById('filter-toggle');
    const filterSidebar = document.getElementById('filter-sidebar');
    const filterText = document.getElementById('filter-text');
    let filtersVisible = false;
    filterToggle?.addEventListener('click', () => {
        filtersVisible = !filtersVisible;
        if (filtersVisible) {
            filterSidebar.classList.remove('hidden');
            filterText.textContent = 'Sembunyikan Filter';
        } else {
            filterSidebar.classList.add('hidden');
            filterText.textContent = 'Tampilkan Filter';
        }
    });

    // ====== Query helpers ======
    const $ = sel => document.querySelector(sel);
    const $$ = sel => Array.from(document.querySelectorAll(sel));

    const state = {
        page: 1,
        per_page: 6
    };

    function collectFilters() {
        const q = $('#q')?.value.trim() || '';
        const province = $('#f-province')?.value || '';
        const issues = $$('input[name="issue[]"]:checked').map(el => el.value);
        const severities = $$('input[name="severity[]"]:checked').map(el => el.value);
        const statuses = $$('input[name="status[]"]:checked').map(el => el.value);
        const date_from = $('#date_from')?.value || '';
        const date_to = $('#date_to')?.value || '';
        const sort = $('#sort')?.value || 'newest';

        return {
            q,
            province,
            issues,
            severities,
            statuses,
            date_from,
            date_to,
            sort
        };
    }

    function severityBadge(sev) {
        const map = {
            high: ['bg-red-100', 'text-red-800', 'Tinggi'],
            medium: ['bg-amber-100', 'text-amber-800', 'Sedang'],
            low: ['bg-teal-100', 'text-teal-800', 'Rendah']
        };
        const [bg, txt, label] = map[sev] || ['bg-gray-100', 'text-gray-800', '-'];
        return `<span class="ml-3 px-2 py-1 text-xs font-medium rounded-full ${bg} ${txt}">${label}</span>`;
    }

    function statusBadge(st) {
        const map = {
            pending: ['text-amber-600', 'bg-amber-100', 'Menunggu Tindakan'],
            confirmed: ['text-indigo-600', 'bg-indigo-100', 'Terkonfirmasi'],
            investigating: ['text-blue-600', 'bg-blue-100', 'Investigasi'],
            resolved: ['text-teal-600', 'bg-teal-100', 'Terselesaikan'],
            rejected: ['text-rose-600', 'bg-rose-100', 'Ditolak'],
            archived: ['text-gray-600', 'bg-gray-100', 'Arsip']
        };
        const [txt, bg, label] = map[st] || ['text-gray-600', 'bg-gray-100', st || '-'];
        return `<span class="inline-block px-3 py-1 text-xs font-medium rounded-full ${txt} ${bg}">${label}</span>`;
    }

    function renderSkeleton(count = 3) {
        const wrap = $('#results');
        wrap.innerHTML = '';
        for (let i = 0; i < count; i++) {
            wrap.innerHTML += `
      <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200 animate-pulse">
        <div class="p-6">
          <div class="h-5 w-1/3 bg-gray-200 rounded"></div>
          <div class="mt-3 h-4 w-2/3 bg-gray-200 rounded"></div>
          <div class="mt-3 h-4 w-1/2 bg-gray-200 rounded"></div>
          <div class="mt-4 h-10 w-24 bg-gray-200 rounded"></div>
        </div>
      </div>`;
        }
    }

    function renderEmpty() {
        $('#results').innerHTML = `
    <div class="bg-white rounded-lg border border-dashed border-gray-300 p-8 text-center">
      <i class="fas fa-folder-open text-3xl text-gray-400"></i>
      <p class="mt-2 text-gray-600">Tidak ada laporan yang cocok dengan filter.</p>
    </div>`;
        $('#result-count').textContent = 'Menampilkan 0 hasil';
        $('#pagination').innerHTML = '';
    }

    function renderResults(payload) {
        const {
            data,
            total,
            page,
            pages,
            per_page
        } = payload;

        if (!data || data.length === 0) {
            renderEmpty();
            return;
        }

        // counter
        const start = (page - 1) * per_page + 1;
        const end = Math.min(page * per_page, total);
        $('#result-count').textContent = `Menampilkan ${start}–${end} dari ${total} hasil`;

        // cards
        const wrap = $('#results');
        wrap.innerHTML = data.map(d => {
            const dateStr = d.date || (d.created_at ? d.created_at.substring(0, 10) : '');
            const sev = severityBadge(d.severity);
            const st = statusBadge(d.status);
            const addr = [d.address, d.province_name].filter(Boolean).join(', ');
            return `
      <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200 hover:shadow-lg transition-shadow hover-lift">
        <div class="p-6">
          <div class="flex flex-col md:flex-row md:items-start md:justify-between">
            <div>
              <div class="flex items-start">
                <h3 class="text-lg font-semibold text-indigo-900">${d.school_name || '-'}</h3>
                ${sev}
              </div>
              <div class="flex items-center text-gray-500 mt-1">
                <i class="fas fa-map-marker-alt flex-shrink-0"></i>
                <span class="ml-1 text-sm">${addr || '-'}</span>
              </div>
              <div class="mt-3 flex items-center space-x-4 text-sm text-gray-500">
                <div class="flex items-center"><i class="fas fa-calendar mr-1"></i><span>${dateStr}</span></div>
                ${d.reporter_name ? `<div class="flex items-center"><i class="fas fa-user mr-1"></i><span>${d.reporter_name}</span></div>` : ''}
                <div class="flex items-center"><i class="fas fa-exclamation-circle mr-1"></i><span>${d.issue_name || '-'}</span></div>
              </div>
            </div>
            <div class="mt-4 md:mt-0 flex-shrink-0">${st}</div>
          </div>
          <div class="mt-4">
            <p class="text-gray-600 line-clamp-2">${d.description || ''}</p>
          </div>
          <div class="mt-4 flex justify-end">
            <a href="./?page=detail-report&id=${d.id}" class="px-4 py-2 border border-indigo-600 text-indigo-600 rounded-md font-medium text-sm hover:bg-indigo-50 transition-colors">
              Lihat Detail
            </a>
          </div>
        </div>
      </div>
    `;
        }).join('');

        // pagination
        const pag = $('#pagination');

        function pageBtn(label, targetPage, disabled = false, active = false) {
            const base = 'px-4 py-2 text-sm font-medium border';
            if (active) return `<button class="${base} text-indigo-600 bg-indigo-50 border-gray-300" disabled>${label}</button>`;
            if (disabled) return `<button class="${base} text-gray-400 bg-white border-gray-300 rounded-md" disabled>${label}</button>`;
            return `<button data-page="${targetPage}" class="${base} text-gray-600 bg-white border-gray-300 hover:bg-gray-50 rounded-md">${label}</button>`;
        }

        const items = [];
        items.push(pageBtn('<<', page - 1, page <= 1));
        // simple pagination window
        const windowSize = 5;
        const startPage = Math.max(1, page - Math.floor(windowSize / 2));
        const endPage = Math.min(pages, startPage + windowSize - 1);
        for (let p = startPage; p <= endPage; p++) {
            items.push(pageBtn(String(p), p, false, p === page));
        }
        items.push(pageBtn('>>', page + 1, page >= pages));

        pag.innerHTML = `<nav class="inline-flex gap-2">${items.join('')}</nav>`;
        pag.querySelectorAll('button[data-page]').forEach(btn => {
            btn.addEventListener('click', () => {
                state.page = parseInt(btn.dataset.page, 10);
                fetchAndRender();
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
        });
    }

    async function fetchReports(params) {
        const url = './users/search/reports_list.php';

        // kirim via POST JSON biar payload array enak
        const res = await fetch(url.toString(), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(params)
        });
        if (!res.ok) throw new Error('HTTP ' + res.status);
        return await res.json();
    }

    async function fetchAndRender() {
        try {
            renderSkeleton();
            const filters = collectFilters();
            const payload = await fetchReports({
                ...filters,
                page: state.page,
                per_page: state.per_page
            });
            renderResults(payload);
        } catch (err) {
            console.error(err);
            $('#results').innerHTML = `
      <div class="bg-white rounded-lg border border-rose-200 p-6">
        <p class="text-rose-600">Gagal memuat data. Coba refresh atau periksa koneksi server.</p>
      </div>`;
            $('#pagination').innerHTML = '';
            $('#result-count').textContent = 'Menampilkan 0 hasil';
        }
    }

    // events
    $('#btn-search')?.addEventListener('click', () => {
        state.page = 1;
        fetchAndRender();
    });
    $('#btn-apply')?.addEventListener('click', () => {
        state.page = 1;
        fetchAndRender();
    });
    $('#sort')?.addEventListener('change', () => {
        state.page = 1;
        fetchAndRender();
    });
    $('#btn-reset')?.addEventListener('click', () => {
        $('#q').value = '';
        $('#f-province').value = '';
        $$('input[name="issue[]"]').forEach(el => el.checked = false);
        $$('input[name="severity[]"]').forEach(el => el.checked = true);
        $$('input[name="status[]"]').forEach(el => el.checked = ['pending', 'investigating', 'resolved'].includes(el.value));
        $('#date_from').value = '';
        $('#date_to').value = '';
        state.page = 1;
        fetchAndRender();
    });

    // initial
    fetchAndRender();
</script>