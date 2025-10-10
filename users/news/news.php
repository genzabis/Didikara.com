<!-- Main Content -->
<div class="pt-24 pb-16 min-h-screen">
    <div class="container mx-auto px-4 md:px-6">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-2xl md:text-3xl font-bold text-indigo-900 mb-2">Berita</h1>
            <p class="text-gray-600 max-w-3xl">
                Temukan update terbaru tentang tindak lanjut laporan, cerita sukses, program, acara, dan publikasi Didikara.
            </p>
        </div>

        <!-- Featured Article -->
        <section id="featured-wrap" class="mb-12">
            <!-- skeleton -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden animate-pulse" id="featured-skeleton" aria-hidden="true">
                <div class="w-full h-64 md:h-80 bg-slate-200"></div>
                <div class="p-6 md:p-8">
                    <div class="h-6 w-28 bg-slate-200 rounded mb-4"></div>
                    <div class="h-7 w-3/4 bg-slate-200 rounded mb-3"></div>
                    <div class="h-4 w-full bg-slate-200 rounded mb-2"></div>
                    <div class="h-4 w-5/6 bg-slate-200 rounded mb-6"></div>
                    <div class="h-9 w-40 bg-slate-200 rounded"></div>
                </div>
            </div>
        </section>

        <!-- Search Bar -->
        <div class="mb-8 w-full">
            <form id="search-form" class="max-w-full" role="search">
                <label class="relative block">
                    <span class="sr-only">Cari artikel</span>
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                        <i class="fas fa-search text-gray-400" aria-hidden="true"></i>
                    </span>
                    <input
                        id="search-input"
                        type="search"
                        placeholder="Cari artikel (judul, ringkasan, isi)..."
                        class="w-full bg-white placeholder:text-gray-400 border border-gray-300 rounded-md py-2 pl-10 pr-10 shadow-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"
                        autocomplete="off" />
                    <button
                        type="button"
                        id="search-clear"
                        class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 hidden"
                        title="Bersihkan"
                        aria-label="Bersihkan pencarian"
                        aria-hidden="true">
                        <i class="fas fa-times" aria-hidden="true"></i>
                    </button>

                </label>
            </form>
        </div>

        <!-- Grid -->
        <section id="grid-wrap">
            <!-- skeleton grid -->
            <div id="grid-skeleton" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" aria-hidden="true"></div>

            <div id="grid-empty" class="hidden bg-white rounded-lg border border-slate-200 p-8 text-center">
                <div class="text-2xl mb-2">😕</div>
                <h3 class="font-semibold text-indigo-900 mb-2">Tidak ada hasil</h3>
                <p class="text-gray-600">Coba kata kunci lain atau periksa ejaan pencarian.</p>
            </div>

            <div id="grid-error" class="hidden bg-rose-50 border border-rose-200 text-rose-800 rounded-lg p-4">
                <strong>Gagal memuat artikel.</strong> Periksa koneksi atau hubungi admin.
            </div>

            <div id="grid" class="hidden grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8"></div>

            <!-- Pagination -->
            <div class="flex justify-center mt-12">
                <nav id="pagination" class="inline-flex rounded-md shadow" aria-label="Pagination"></nav>
            </div>
        </section>
    </div>
</div>

<script src="./assets/js/script.js"></script>
<script>
    (() => {
        // === Konfigurasi API ===
        const API_LIST = './users/news/get_news.php';
        const API_FEATURED = './users/news/get_news_featured.php';

        // === Elemen2 DOM ===
        const featuredWrap = document.getElementById('featured-wrap');
        const featuredSkeleton = document.getElementById('featured-skeleton');

        const grid = document.getElementById('grid');
        const gridSkeleton = document.getElementById('grid-skeleton');
        const gridEmpty = document.getElementById('grid-empty');
        const gridError = document.getElementById('grid-error');
        const pagination = document.getElementById('pagination');

        // Search elems
        const searchForm = document.getElementById('search-form') || null;
        const searchInput = document.getElementById('search-input') || null;
        const searchClear = document.getElementById('search-clear') || null;

        // === State ===
        const PAGINATION_PARAM = 'p'; // hindari bentrok router ?page=news
        let state = {
            q: '',
            page: 1,
            limit: 9,
            totalPages: 1,
        };

        // === Utils ===
        const fmtDateID = (iso) => {
            if (!iso) return '';
            const d = new Date((iso || '').replace(' ', 'T'));
            return isNaN(d.getTime()) ?
                '' :
                d.toLocaleDateString('id-ID', {
                    day: 'numeric',
                    month: 'long',
                    year: 'numeric'
                });
        };

        const esc = (s) => (s ?? '').toString()
            .replaceAll('&', '&amp;').replaceAll('<', '&lt;')
            .replaceAll('>', '&gt;').replaceAll('"', '&quot;')
            .replaceAll("'", '&#039;');

        const catLabel = (cat) => ({
            'cerita-sukses': 'Cerita Sukses',
            'program': 'Program',
            'acara': 'Acara',
            'publikasi': 'Publikasi',
            'cerita-inspiratif': 'Cerita Inspiratif'
        } [cat] ?? 'Lainnya');

        const safeImg = (url) => (/^(https?:|data:image\/)/i.test(url || '')) ?
            url :
            'https://placehold.co/800x450?text=No+Image';

        const linkDetail = (slugOrId) => `?page=news-detail&slug=${encodeURIComponent(slugOrId)}`;

        // Skeleton cards (6x)
        (function paintSkeletonCards() {
            if (!gridSkeleton) return;
            let html = '';
            for (let i = 0; i < 6; i++) {
                html += `
        <div class="bg-white rounded-lg shadow-md overflow-hidden animate-pulse">
          <div class="h-48 bg-slate-200"></div>
          <div class="p-6">
            <div class="h-6 w-3/4 bg-slate-200 rounded mb-3"></div>
            <div class="h-4 w-full bg-slate-200 rounded mb-2"></div>
            <div class="h-4 w-5/6 bg-slate-200 rounded mb-4"></div>
            <div class="h-4 w-1/2 bg-slate-200 rounded"></div>
          </div>
        </div>`;
            }
            gridSkeleton.innerHTML = html;
        })();

        // === Fetch wrapper ===
        async function getJSON(url) {
            const res = await fetch(url, {
                headers: {
                    'Accept': 'application/json'
                }
            });
            if (!res.ok) throw new Error(`HTTP ${res.status}`);
            const data = await res.json();
            if (data && typeof data === 'object' && data.error) {
                throw new Error(data.message || 'API error');
            }
            return data;
        }

        // === Featured ===
        async function loadFeatured() {
            try {
                const data = await getJSON(`${API_FEATURED}?limit=1`);
                if (!data || !Array.isArray(data.data)) throw new Error('Invalid JSON shape');

                const item = data.data[0];
                if (!item) {
                    featuredWrap?.classList.add('hidden');
                    return;
                }

                featuredWrap.innerHTML = `
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-2">
          <div class="w-full">
            <img
              src="${safeImg(item.cover_url)}"
              alt="${esc(item.cover_alt || item.title)}"
              class="w-full h-64 md:h-80 object-cover object-center"
              onerror="this.onerror=null;this.src='https://placehold.co/800x450?text=No+Image';"
            />
          </div>
          <div class="p-6 md:p-8">
            <span class="inline-block bg-amber-100 text-amber-800 text-xs font-medium px-2.5 py-0.5 rounded-full mb-4">
              ${esc(catLabel(item.category))}
            </span>
            <h2 class="text-xl md:text-2xl font-bold text-indigo-900 mb-3">${esc(item.title)}</h2>
            ${item.excerpt ? `<p class="text-gray-600 mb-4">${esc(item.excerpt)}</p>` : ''}
            <div class="flex items-center text-sm text-gray-500 mb-6">
              <i class="fas fa-calendar mr-1"></i>
              <span class="mr-4">${fmtDateID(item.published_at)}</span>
              <i class="fas fa-user mr-1"></i>
              <span>${esc(item.author_name || 'Tim Didikara')}</span>
            </div>
            <a href="${linkDetail(item.slug)}"
               class="inline-flex items-center px-4 py-2 bg-indigo-900 hover:bg-indigo-800 text-white rounded-md font-medium transition-colors">
              <span>Baca Selengkapnya</span>
              <i class="fas fa-arrow-right ml-2"></i>
            </a>
          </div>
        </div>`;
            } catch (err) {
                console.warn('Featured error:', err);
                featuredWrap?.classList.add('hidden');
            } finally {
                featuredSkeleton?.remove();
            }
        }

        // === Grid + Pagination ===
        function renderGrid(items) {
            if (!Array.isArray(items) || items.length === 0) {
                grid?.classList.add('hidden');
                gridEmpty?.classList.remove('hidden');
                return;
            }
            gridEmpty?.classList.add('hidden');
            grid?.classList.remove('hidden');

            grid.innerHTML = items.map(a => `
      <article class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow hover-lift">
        <div class="h-48 relative">
          <img
            src="${safeImg(a.cover_url)}"
            alt="${esc(a.cover_alt || a.title)}"
            class="w-full h-full object-cover"
            onerror="this.onerror=null;this.src='https://placehold.co/800x450?text=No+Image';"
          />
          <span class="absolute top-3 left-3 bg-white/90 backdrop-blur-sm text-indigo-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
            ${esc(catLabel(a.category))}
          </span>
        </div>
        <div class="p-6">
          <h3 class="text-lg font-bold text-indigo-900 mb-2 line-clamp-2">${esc(a.title)}</h3>
          ${a.excerpt ? `<p class="text-gray-600 text-sm mb-4 line-clamp-3">${esc(a.excerpt)}</p>` : ''}
          <div class="flex items-center text-xs text-gray-500 mb-4">
            <i class="fas fa-calendar mr-1"></i>
            <span class="mr-3">${fmtDateID(a.published_at)}</span>
            <i class="fas fa-user mr-1"></i>
            <span>${esc(a.author_name || 'Tim Didikara')}</span>
          </div>
          <a href="${linkDetail(a.slug)}"
             class="w-full inline-flex items-center justify-center px-4 py-2 border border-indigo-600 text-indigo-600 rounded-md font-medium text-sm hover:bg-indigo-50 transition-colors">
            Baca Selengkapnya
          </a>
        </div>
      </article>
    `).join('');
        }

        function renderPagination(page, totalPages) {
            if (!pagination) return;
            if (totalPages <= 1) {
                pagination.innerHTML = '';
                return;
            }

            const edge = (disabled) =>
                disabled ? 'text-gray-300 bg-white border border-gray-200 pointer-events-none' :
                'text-gray-500 bg-white border border-gray-300 hover:bg-gray-50';
            const active = (p) =>
                (p === page) ? 'text-indigo-600 bg-indigo-50 border-t border-b border-gray-300' :
                'text-gray-500 bg-white border border-gray-300 hover:bg-gray-50';

            const makeBtn = (p, label, classes) =>
                '<a href="#" data-page="' + p + '" class="px-4 py-2 text-sm font-medium ' + classes + '">' + label + '</a>';

            const start = Math.max(1, page - 1);
            const end = Math.min(totalPages, page + 1);

            let html = '';
            html += makeBtn(Math.max(1, page - 1), 'Sebelumnya', 'rounded-l-md ' + edge(page === 1));
            for (let i = start; i <= end; i++) {
                html += makeBtn(i, String(i), active(i));
            }
            html += makeBtn(Math.min(totalPages, page + 1), 'Selanjutnya', 'rounded-r-md ' + edge(page === totalPages));

            pagination.innerHTML = html;

            // listeners
            pagination.querySelectorAll('a[data-page]').forEach(a => {
                a.addEventListener('click', (e) => {
                    e.preventDefault();
                    const target = parseInt(a.getAttribute('data-page'), 10);
                    if (!Number.isNaN(target) && target !== state.page) {
                        state.page = target;
                        syncURL(); // tulis ?p= ke URL (router ?page=news tetap aman)
                        loadList();
                        window.scrollTo({
                            top: 0,
                            behavior: 'smooth'
                        });
                    }
                });
            });
        }

        // === Loader ===
        async function loadList() {
            gridError?.classList.add('hidden');
            grid?.classList.add('hidden');
            gridEmpty?.classList.add('hidden');
            gridSkeleton?.classList.remove('hidden');

            const params = new URLSearchParams();
            if (state.q) params.set('q', state.q);
            params.set('page', state.page); // API tetap pakai "page"
            params.set('limit', state.limit);

            try {
                const data = await getJSON(`${API_LIST}?${params.toString()}`);
                if (!data || !Array.isArray(data.data) || typeof data.total_pages !== 'number') {
                    throw new Error('Invalid JSON shape');
                }
                state.totalPages = data.total_pages || 1;

                renderGrid(data.data);
                renderPagination(state.page, state.totalPages);
            } catch (err) {
                console.error('List error:', err);
                gridError?.classList.remove('hidden');
            } finally {
                gridSkeleton?.classList.add('hidden');
            }
        }

        // === URL sync / restore ===
        function syncURL() {
            const url = new URL(window.location.href);
            if (state.q) url.searchParams.set('q', state.q);
            else url.searchParams.delete('q');
            url.searchParams.set(PAGINATION_PARAM, state.page); // gunakan ?p=
            window.history.replaceState({}, '', url);
        }

        (function initFromURLOnce() {
            const url = new URL(window.location.href);
            const q = url.searchParams.get('q') || '';
            const p = parseInt(url.searchParams.get(PAGINATION_PARAM) || '1', 10);
            state.q = q;
            if (q && searchInput) searchInput.value = q;
            if (!Number.isNaN(p) && p > 1) state.page = p;
        })();

        function applySearch(q) {
            state.q = (q || '').trim();
            state.page = 1;
            syncURL();
            loadList();
        }

        // === Listeners ===
        if (searchForm) {
            searchForm.addEventListener('submit', (e) => {
                e.preventDefault();
                applySearch(searchInput ? searchInput.value : '');
            });
        }
        if (searchClear) {
            searchClear.addEventListener('click', () => {
                if (!searchInput) return;
                if (searchInput.value === '') return;
                searchInput.value = '';
                applySearch('');
            });
        }
        if (searchInput) {
            let typingTimer;
            searchInput.addEventListener('input', () => {
                clearTimeout(typingTimer);
                typingTimer = setTimeout(() => applySearch(searchInput.value), 300);
            });
        }

        // === Init ===
        (async function init() {
            if (typeof fetch !== 'function') {
                console.error('fetch() tidak tersedia di browser ini.');
                return;
            }
            await loadFeatured();
            await loadList();
        })();
    })();
</script>