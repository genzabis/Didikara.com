<div class="pt-24 pb-16 min-h-screen">
    <div class="container mx-auto px-4 md:px-6">
        <!-- Breadcrumbs -->
        <nav class="text-sm text-gray-500 mb-4">
            <a href="?page=news" class="hover:underline">Berita</a>
            <span class="mx-2">/</span>
            <span id="bc-category">…</span>
        </nav>

        <!-- Header -->
        <header class="mb-6">
            <h1 id="title" class="text-2xl md:text-3xl font-bold text-indigo-900 mb-2">Memuat…</h1>
            <div class="flex flex-wrap items-center gap-x-4 text-sm text-gray-500">
                <div>
                    <i class="fas fa-calendar mr-1"></i><span id="date">—</span>
                </div>
                <div>
                    <i class="fas fa-user mr-1"></i><span id="author">—</span>
                </div>
                <span id="badge-category" class="inline-block bg-indigo-100 text-indigo-800 text-xs font-medium px-2.5 py-0.5 rounded-full"></span>
            </div>
        </header>

        <!-- Cover -->
        <figure class="mb-6">
            <div class="w-full h-64 md:h-96 bg-slate-200 rounded-lg overflow-hidden" id="cover-skeleton" aria-hidden="true"></div>
            <img id="cover"
                class="hidden w-full h-64 md:h-96 object-cover rounded-lg"
                alt=""
                onerror="this.onerror=null;this.classList.add('hidden');document.getElementById('cover-fallback').classList.remove('hidden');" />
            <div id="cover-fallback" class="hidden w-full h-64 md:h-96 bg-slate-100 grid place-items-center rounded-lg text-gray-400">
                No Image
            </div>
            <figcaption id="cover-alt" class="sr-only"></figcaption>
        </figure>

        <!-- Excerpt -->
        <p id="excerpt" class="text-lg text-gray-700 mb-6 hidden"></p>

        <!-- Content -->
        <article id="content" class="prose max-w-none hidden"></article>

        <!-- Gallery -->
        <section id="gallery-wrap" class="hidden mt-10">
            <h2 class="text-lg font-semibold text-indigo-900 mb-3">Galeri</h2>
            <div id="gallery" class="grid grid-cols-2 md:grid-cols-3 gap-3"></div>
        </section>

        <!-- Error / Not found -->
        <div id="error" class="hidden bg-rose-50 border border-rose-200 text-rose-800 rounded-lg p-4 mt-6">
            <strong>Gagal memuat artikel.</strong> Silakan kembali ke halaman <a class="underline" href="?page=news">Berita</a>.
        </div>

        <!-- Related -->
        <section id="related-wrap" class="hidden mt-12">
            <h3 class="text-xl font-bold text-indigo-900 mb-4">Artikel Terkait</h3>
            <div id="related-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"></div>
        </section>
    </div>
</div>

<script>
    (() => {
        const API_DETAIL = './users/news/get_news_detail.php';
        const API_LIST = './users/news/get_news.php'; // utk related

        // DOM
        const el = (id) => document.getElementById(id);
        const title = el('title');
        const dateEl = el('date');
        const author = el('author');
        const bcCat = el('bc-category');
        const badgeCat = el('badge-category');
        const cover = el('cover');
        const coverSk = el('cover-skeleton');
        const coverAlt = el('cover-alt');
        const excerpt = el('excerpt');
        const content = el('content');
        const galleryWrap = el('gallery-wrap');
        const gallery = el('gallery');
        const errorBox = el('error');
        const relatedWrap = el('related-wrap');
        const relatedGrid = el('related-grid');

        const fmtDateID = (iso) => {
            if (!iso) return '';
            const d = new Date((iso || '').replace(' ', 'T'));
            return isNaN(d.getTime()) ? '' : d.toLocaleDateString('id-ID', {
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


        const qs = new URLSearchParams(window.location.search);
        const slug = qs.get('slug') || '';

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

        function renderDetail({
            article,
            gallery: gal
        }) {
            // header
            title.textContent = article.title || 'Tanpa Judul';
            dateEl.textContent = fmtDateID(article.published_at) || '';
            author.textContent = article.author_name || 'Tim Didikara';
            bcCat.textContent = catLabel(article.category);
            badgeCat.textContent = catLabel(article.category);

            // cover
            if (article.cover_url) {
                cover.src = safeImg(article.cover_url);
                cover.alt = article.cover_alt || article.title || '';
                coverAlt.textContent = cover.alt;
                cover.classList.remove('hidden');
            } else {
                el('cover-fallback').classList.remove('hidden');
            }
            coverSk?.remove();

            // excerpt
            if (article.excerpt) {
                excerpt.textContent = article.excerpt;
                excerpt.classList.remove('hidden');
            }

            // content: kita render apa adanya (diasumsikan sudah aman/bersih).
            // Kalau kamu simpan HTML penuh di DB, langsung innerHTML. Kalaupun null, biarin hidden.
            if (article.content) {
                content.innerHTML = article.content; // NOTE: pastikan content sudah dikurasi/di-sanitize dari sisi admin
                content.classList.remove('hidden');
            }

            // gallery
            if (Array.isArray(gal) && gal.length) {
                galleryWrap.classList.remove('hidden');
                gallery.innerHTML = gal.map(g => `
        <figure class="rounded-lg overflow-hidden border border-slate-200 bg-white">
          <img
            src="${safeImg(g.url)}"
            alt="${esc(g.alt || '')}"
            class="w-full h-40 md:h-44 object-cover"
            onerror="this.onerror=null;this.src='https://placehold.co/600x400?text=No+Image';"
          />
        </figure>
      `).join('');
            }
        }

        async function loadRelated(category, excludeId) {
            try {
                const params = new URLSearchParams();
                if (category) params.set('category', category);
                if (excludeId) params.set('exclude_id', excludeId);
                params.set('page', '1');
                params.set('limit', '3');

                const data = await getJSON(`${API_LIST}?${params.toString()}`);
                const items = Array.isArray(data.data) ? data.data : [];
                if (!items.length) return;

                relatedWrap.classList.remove('hidden');
                relatedGrid.innerHTML = items.map(a => `
        <article class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
          <div class="h-40 relative">
            <img
              src="${safeImg(a.cover_url)}"
              alt="${esc(a.cover_alt || a.title)}"
              class="w-full h-full object-cover"
              onerror="this.onerror=null;this.src='https://placehold.co/800x450?text=No+Image';"
            />
            <span class="absolute top-3 left-3 bg-white/90 text-indigo-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
              ${esc(catLabel(a.category))}
            </span>
          </div>
          <div class="p-4">
            <h4 class="font-semibold text-indigo-900 mb-2 line-clamp-2">${esc(a.title)}</h4>
            <div class="text-xs text-gray-500">
              <i class="fas fa-calendar mr-1"></i>${fmtDateID(a.published_at)}
            </div>
            <a href="?page=news-detail&slug=${encodeURIComponent(a.slug)}"
               class="mt-3 inline-flex items-center text-indigo-700 hover:text-indigo-800 text-sm font-medium">
               Baca &nbsp;<i class="fas fa-arrow-right ml-1"></i>
            </a>
          </div>
        </article>
      `).join('');
            } catch (e) {
                // related gagal? gak apa2, cukup sembunyikan
                console.warn('Related error:', e);
            }
        }

        (async function init() {
            if (!slug) {
                errorBox.classList.remove('hidden');
                return;
            }
            try {
                const data = await getJSON(`${API_DETAIL}?slug=${encodeURIComponent(slug)}`);
                if (!data || !data.data || !data.data.article) throw new Error('Invalid JSON');
                const {
                    article,
                    gallery
                } = data.data;

                renderDetail({
                    article,
                    gallery
                });
                await loadRelated(article.category, article.id);
            } catch (err) {
                console.error(err);
                errorBox.classList.remove('hidden');
                coverSk?.remove();
            }
        })();
    })();
</script>