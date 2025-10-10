<div class="pt-24 pb-16 min-h-screen">
    <div class="container mx-auto px-4 md:px-6">

        <!-- Breadcrumb -->
        <nav class="text-sm text-gray-500 mb-4">
            <a href="?page=report" class="hover:underline">Laporan</a>
            <span class="mx-2">/</span>
            <span id="bc-school">…</span>
        </nav>

        <!-- Header -->
        <header class="mb-6">
            <h1 id="title" class="text-2xl md:text-3xl font-bold text-indigo-900 mb-2">Memuat…</h1>
            <div class="flex flex-wrap items-center gap-2 text-sm">
                <span id="badge-issue" class="inline-block bg-slate-100 text-slate-800 px-2.5 py-0.5 rounded-full">—</span>
                <span id="badge-severity" class="inline-block px-2.5 py-0.5 rounded-full">—</span>
                <span id="badge-status" class="inline-block px-2.5 py-0.5 rounded-full">—</span>
            </div>
            <div class="mt-3 text-gray-600 text-sm flex flex-wrap gap-x-4 gap-y-2">
                <div><i class="fas fa-school mr-1"></i><span id="meta-level">—</span></div>
                <div><i class="fas fa-map-marker-alt mr-1"></i><span id="meta-location">—</span></div>
                <div><i class="fas fa-clock mr-1"></i><span id="meta-created">—</span></div>
            </div>
        </header>

        <!-- Skeleton -->
        <div id="skeleton" class="animate-pulse space-y-4">
            <div class="h-4 w-48 bg-slate-200 rounded"></div>
            <div class="h-4 w-full bg-slate-200 rounded"></div>
            <div class="h-4 w-5/6 bg-slate-200 rounded"></div>
            <div class="h-64 bg-slate-200 rounded"></div>
        </div>

        <!-- Deskripsi -->
        <article id="description" class="prose max-w-none hidden"></article>

        <!-- Lokasi / Peta -->
        <section id="map-wrap" class="hidden mt-8">
            <h2 class="text-lg font-semibold text-indigo-900 mb-3">Lokasi</h2>
            <div class="rounded-lg overflow-hidden border border-slate-200">
                <iframe id="map" class="w-full h-72" referrerpolicy="no-referrer-when-downgrade" loading="lazy"></iframe>
            </div>
            <p id="map-note" class="text-xs text-gray-500 mt-2"></p>
        </section>

        <!-- Lampiran (preview foto saja) -->
        <section id="att-wrap" class="hidden mt-10">
            <h2 class="text-lg font-semibold text-indigo-900 mb-3">Lampiran</h2>
            <div id="att-grid" class="space-y-4"></div>
        </section>

        <!-- Pelapor -->
        <section id="reporter-wrap" class="hidden mt-10">
            <h2 class="text-lg font-semibold text-indigo-900 mb-3">Data Pelapor</h2>
            <div class="bg-white border border-slate-200 rounded-lg p-4 text-sm">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div><span class="text-gray-500">Nama</span>
                        <div id="rep-name" class="font-medium">—</div>
                    </div>
                    <div><span class="text-gray-500">Email</span>
                        <div id="rep-email" class="font-medium">—</div>
                    </div>
                    <div><span class="text-gray-500">Telepon</span>
                        <div id="rep-phone" class="font-medium">—</div>
                    </div>
                    <div><span class="text-gray-500">NIK</span>
                        <div id="rep-nik" class="font-medium">—</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Error -->
        <div id="error" class="hidden bg-rose-50 border border-rose-200 text-rose-800 rounded-lg p-4 mt-6">
            <strong>Gagal memuat laporan.</strong> Silakan kembali ke <a class="underline" href="?page=report">daftar laporan</a>.
        </div>

    </div>
</div>

<script>
    (() => {
        const API = './users/report/get_report_detail.php';

        // ===== helpers =====
        const $ = (id) => document.getElementById(id);
        const qs = new URLSearchParams(window.location.search);
        const id = qs.get('id');

        const fmtDateID = (iso) => {
            if (!iso) return '';
            const d = new Date(String(iso).replace(' ', 'T'));
            return isNaN(d.getTime()) ? '' : d.toLocaleString('id-ID', {
                day: 'numeric',
                month: 'long',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        };

        const severityClass = (sev) => ({
            low: 'bg-emerald-100 text-emerald-800',
            medium: 'bg-amber-100 text-amber-800',
            high: 'bg-rose-100 text-rose-800'
        } [sev] || 'bg-slate-100 text-slate-800');

        const statusClass = (st) => ({
            pending: 'bg-slate-100 text-slate-800',
            confirmed: 'bg-blue-100 text-blue-800',
            investigating: 'bg-amber-100 text-amber-800',
            resolved: 'bg-emerald-100 text-emerald-800',
            rejected: 'bg-rose-100 text-rose-800',
            archived: 'bg-slate-200 text-slate-700'
        } [st] || 'bg-slate-100 text-slate-800');

        // Terima http(s), data:image, dan SEMUA path relatif (uploads/..., ./, ../, /)
        const resolveImgUrl = (p) => {
            if (!p) return null;
            const s = String(p).trim();
            if (/^(https?:|data:image\/)/i.test(s)) return s; // absolut atau data URI
            try {
                return new URL(s, window.location.href).href;
            } catch {
                return null;
            }
        };

        // Gambar bila mime image/* atau dari ekstensi umum
        const isImageAttachment = (a) => {
            if (!a) return false;
            if (a.mime_type && /^image\//i.test(a.mime_type)) return true;
            const p = (a.file_path || '').toLowerCase();
            return /\.(jpe?g|png|gif|webp|bmp|svg)$/.test(p);
        };

        async function getJSON(url) {
            const res = await fetch(url, {
                headers: {
                    'Accept': 'application/json'
                }
            });
            if (!res.ok) throw new Error(`HTTP ${res.status}`);
            const data = await res.json();
            if (data && data.error) throw new Error(data.message || 'API error');
            return data;
        }

        const buildOSM = (lat, lng, label) => {
            const bbox = [lng - 0.01, lat - 0.01, lng + 0.01, lat + 0.01].join('%2C');
            const marker = `${lat}%2C${lng}`;
            const q = encodeURIComponent(label || 'Lokasi');
            return `https://www.openstreetmap.org/export/embed.html?bbox=${bbox}&layer=mapnik&marker=${marker}&q=${q}`;
        };

        // ===== attachments (preview foto saja) =====
        function renderAttachments(list) {
            const wrap = $('att-wrap');
            const grid = $('att-grid');
            if (!wrap || !grid) return false;

            const imgs = (Array.isArray(list) ? list : [])
                .filter(isImageAttachment)
                .map(a => resolveImgUrl(a.file_path))
                .filter(Boolean);

            if (imgs.length === 0) {
                wrap.classList.add('hidden');
                grid.innerHTML = '';
                return false;
            }

            grid.innerHTML = imgs.map(src => `
              <div class="rounded-xl overflow-hidden border border-slate-200 bg-white">
                <img
                  src="${src}"
                  alt="Lampiran"
                  class="w-full h-auto max-h-[70vh] object-contain"
                  loading="lazy"
                  onerror="this.onerror=null;this.src='https://placehold.co/1200x800?text=No+Image';"
                />
              </div>
            `).join('');

            wrap.classList.remove('hidden');
            return true;
        }

        // ===== init =====
        (async function init() {
            if (!id) {
                $('error')?.classList.remove('hidden');
                return;
            }

            try {
                const payload = await getJSON(`${API}?id=${encodeURIComponent(id)}`);
                const {
                    report,
                    attachments
                } = payload.data || {};
                if (!report) throw new Error('Invalid JSON');

                // Header
                $('bc-school').textContent = report.school_name || 'Sekolah';
                $('title').textContent = report.school_name || 'Detail Laporan';

                $('badge-issue').textContent = report.issue_type_name || `Issue #${report.issue_type_id||'-'}`;

                const sev = (report.severity || '').toLowerCase();
                $('badge-severity').textContent = `Severity: ${sev || '-'}`;
                $('badge-severity').className = `inline-block px-2.5 py-0.5 rounded-full ${severityClass(sev)}`;

                const st = (report.status || '').toLowerCase();
                $('badge-status').textContent = `Status: ${st || '-'}`;
                $('badge-status').className = `inline-block px-2.5 py-0.5 rounded-full ${statusClass(st)}`;

                // Meta
                $('meta-level').textContent = report.edu_level_name || `Jenjang #${report.edu_level_id||'-'}`;
                const parts = [report.address, report.kab_kota, report.province_name].filter(Boolean);
                $('meta-location').textContent = parts.join(', ') || '-';
                $('meta-created').textContent = fmtDateID(report.created_at);

                // Deskripsi (plain text)
                const desc = $('description');
                desc.innerText = report.description || '-';
                desc.classList.remove('hidden');

                // Map
                if (report.latitude && report.longitude) {
                    const lat = parseFloat(report.latitude);
                    const lng = parseFloat(report.longitude);
                    if (!Number.isNaN(lat) && !Number.isNaN(lng)) {
                        $('map').src = buildOSM(lat, lng, report.school_name || 'Lokasi');
                        $('map-note').textContent = `Koordinat: ${lat.toFixed(6)}, ${lng.toFixed(6)}`;
                        $('map-wrap').classList.remove('hidden');
                    }
                }

                // Lampiran foto
                renderAttachments(attachments);

                // Data Pelapor (tampilkan bila ada)
                if (report.reporter_name || report.reporter_email_masked || report.reporter_phone_masked || report.reporter_nik_masked) {
                    $('rep-name').textContent = report.reporter_name || '-';
                    $('rep-email').textContent = report.reporter_email_masked || '-';
                    $('rep-phone').textContent = report.reporter_phone_masked || '-';
                    $('rep-nik').textContent = report.reporter_nik_masked || '-';
                    $('reporter-wrap').classList.remove('hidden');
                }

                $('skeleton')?.classList.add('hidden');
            } catch (e) {
                $('skeleton')?.classList.add('hidden');
                $('error')?.classList.remove('hidden');
            }
        })();
    })();
</script>