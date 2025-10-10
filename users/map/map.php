  <!-- Main Content -->
  <div class="pt-24 pb-16 min-h-screen">
      <div class="container mx-auto px-4 md:px-6">
          <div class="mb-6 md:mb-8">
              <h1 class="text-2xl md:text-3xl font-bold text-indigo-900 mb-3">
                  Peta Interaktif Masalah Pendidikan
              </h1>
              <p class="text-gray-600 max-w-3xl">
                  Lihat sebaran masalah pendidikan di seluruh Indonesia. Klik marker untuk melihat detail.
                  Gunakan filter untuk menemukan laporan berdasarkan jenis masalah, lokasi, tingkat keparahan, dan status.
              </p>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-3 gap-6 h-full">
              <!-- Map -->
              <div class="md:col-span-2 bg-white rounded-lg shadow-md overflow-hidden h-[360px] md:h-[700px] relative z-0">
                  <div id="map" class="absolute inset-0"></div>
                  <!-- Counter overlay -->
                  <div id="counter" class="absolute left-3 top-3 bg-white/90 rounded-md shadow px-3 py-1.5 text-sm font-medium text-indigo-900">
                      0 laporan ditampilkan
                  </div>
              </div>

              <!-- Sidebar -->
              <?php require_once 'sidebar.php'; ?>
          </div>

      </div>
  </div>
  <script>
      // ===== Utilities =====
      const $ = (sel) => document.querySelector(sel);
      const $$ = (sel) => Array.from(document.querySelectorAll(sel));
      const BP_MD = 768;
      const isMobile = () => window.innerWidth < BP_MD;

      // Navbar scroll
      const navbar = $('#navbar');
      const onScroll = () => {
          if (!navbar) return;
          if (window.scrollY > 8) navbar.classList.add('navbar-scrolled');
          else navbar.classList.remove('navbar-scrolled');
      };
      window.addEventListener('scroll', onScroll);
      onScroll();

      // Mobile menu
      const mobileMenuBtn = document.getElementById('mobile-menu-btn');
      const mobileMenu = document.getElementById('mobile-menu');
      if (mobileMenuBtn && mobileMenu) {
          mobileMenuBtn.addEventListener('click', () => {
              mobileMenu.classList.toggle('hidden');
              mobileMenuBtn.setAttribute('aria-expanded', mobileMenu.classList.contains('hidden') ? 'false' : 'true');
          });
          mobileMenu.querySelectorAll('a').forEach(a => a.addEventListener('click', () => mobileMenu.classList.add('hidden')));
          document.addEventListener('keydown', (e) => {
              if (e.key === 'Escape') mobileMenu.classList.add('hidden');
          });
          document.addEventListener('click', (e) => {
              if (!mobileMenu.contains(e.target) && !mobileMenuBtn.contains(e.target)) mobileMenu.classList.add('hidden');
          });
      }

      // ===== Leaflet init =====
      const map = L.map('map', {
          scrollWheelZoom: true
      }).setView([-2.5, 118], 5);
      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
          maxZoom: 19,
          attribution: '© OpenStreetMap'
      }).addTo(map);
      setTimeout(() => map.invalidateSize(), 0);

      const mapContainer = $('#map')?.parentElement;

      function applyMapHeightForMobile() {
          if (!mapContainer) return;
          if (isMobile()) mapContainer.style.height = '360px';
          else mapContainer.style.height = ''; // back to Tailwind md:h-[700px]
      }

      function applyMapInteractivity() {
          const enable = !isMobile();
          map.dragging[enable ? 'enable' : 'disable']();
          map.touchZoom[enable ? 'enable' : 'disable']();
          map.scrollWheelZoom[enable ? 'enable' : 'disable']();
          map.boxZoom[enable ? 'enable' : 'disable']();
          map.keyboard[enable ? 'enable' : 'disable']();
      }

      function applyResponsiveMap() {
          applyMapHeightForMobile();
          applyMapInteractivity();
          map.invalidateSize();
      }
      applyResponsiveMap();
      window.addEventListener('resize', applyResponsiveMap);

      // ===== Markers & helpers =====
      const markerLayer = L.layerGroup().addTo(map);
      const severityColor = {
          high: '#ef4444',
          medium: '#f59e0b',
          low: '#14b8a6'
      };
      const statusBadgeClass = {
          pending: 'badge badge-status-pending',
          investigating: 'badge badge-status-inprog',
          confirmed: 'badge badge-status-inprog',
          in_progress: 'badge badge-status-inprog',
          resolved: 'badge badge-status-resolved'
      };
      const statusText = {
          pending: 'Menunggu Tindakan',
          investigating: 'Dalam Proses',
          confirmed: 'Dalam Proses',
          in_progress: 'Dalam Proses',
          resolved: 'Terselesaikan'
      };

      const slugify = (s = '') =>
          s.toString()
          .normalize('NFKD')
          .replace(/[\u0300-\u036f]/g, '')
          .toLowerCase()
          .replace(/&/g, ' dan ')
          .replace(/[^a-z0-9]+/g, '-')
          .replace(/(^-|-$)/g, '');

      function circleDivIcon(color) {
          const html = `<span style="display:inline-block;width:14px;height:14px;border-radius:9999px;background:${color};box-shadow:0 0 0 2px #fff,0 2px 6px rgba(0,0,0,.2)"></span>`;
          return L.divIcon({
              html,
              className: '',
              iconSize: [14, 14],
              iconAnchor: [7, 7],
              popupAnchor: [0, -8]
          });
      }

      function buildPopup(d) {
          return `
      <div class="text-sm">
        <h4 class="font-semibold text-indigo-900">${d.schoolName || '-'}</h4>
        <p class="text-gray-500 text-xs mb-2">${d.address || ''}</p>
        <div class="flex items-center mb-2">
          <span class="inline-block w-3 h-3 rounded-full mr-2" style="background:${severityColor[d.severity] || '#999'};"></span>
          <span class="text-gray-700">${d.issueType || 'Tidak diketahui'}</span>
        </div>
        <p class="text-gray-700 mb-2">${d.description || ''}</p>
        <div class="flex justify-between items-center text-xs mb-2">
          <span class="text-gray-500">Dilaporkan: ${d.date || '-'}</span>
          <span class="${statusBadgeClass[d.status] || ''}">${statusText[d.status] || (d.status || '').toString()}</span>
        </div>
        <a href="./?page=detail-report&id=${d.id}" class="inline-flex items-center justify-center px-3 py-1.5 bg-indigo-900 hover:bg-indigo-800 text-white rounded-md font-medium text-xs transition-colors">
          Lihat Detail
        </a>
      </div>
    `;
      }

      let reportData = []; // normalized data from server

      function renderMarkers(data) {
          markerLayer.clearLayers();
          const validPoints = [];

          for (const d of data) {
              const lat = parseFloat(d.lat);
              const lng = parseFloat(d.lng);
              if (Number.isNaN(lat) || Number.isNaN(lng)) continue;

              validPoints.push([lat, lng]);
              const m = L.marker([lat, lng], {
                      icon: circleDivIcon(severityColor[d.severity] || '#999')
                  })
                  .bindPopup(buildPopup(d));
              markerLayer.addLayer(m);
          }

          if (validPoints.length) {
              const bounds = L.latLngBounds(validPoints);
              map.fitBounds(bounds.pad(0.2));
          }

          const counterEl = $('#counter');
          if (counterEl) counterEl.textContent = `${validPoints.length} laporan ditampilkan`;
      }

      // ===== Filters (sidebar) =====
      const els = {
          q: $('#q'),
          province: $('#f-province'), // expect slug value (e.g., "jawa-barat")
          issue: $('#f-issue'), // expect slug (e.g., "fasilitas")
          sevHigh: $('#sev-high'),
          sevMedium: $('#sev-medium'),
          sevLow: $('#sev-low'),
          stPending: $('#st-pending'),
          stInprog: $('#st-inprog'),
          stResolved: $('#st-resolved'),
          apply: $('#apply'),
          searchForm: $('#search-form'),
      };

      function applyFilters() {
          const q = (els.q?.value || '').trim().toLowerCase();
          const province = els.province?.value || ''; // slug
          const issue = els.issue?.value || ''; // slug

          const sevSet = new Set([
              els.sevHigh?.checked ? 'high' : null,
              els.sevMedium?.checked ? 'medium' : null,
              els.sevLow?.checked ? 'low' : null
          ].filter(Boolean));

          const stSet = new Set([
              els.stPending?.checked ? 'pending' : null,
              (els.stInprog?.checked ? ['investigating', 'confirmed', 'in_progress'] : []).flat(),
              els.stResolved?.checked ? 'resolved' : null
          ].flat().filter(Boolean));

          const filtered = reportData.filter(d => {
              if (province && d.provinceSlug !== province) return false;
              if (issue && d.issueSlug !== issue) return false;
              if (!sevSet.has(d.severity)) return false;
              if (!stSet.has(d.status)) return false;
              if (q) {
                  const hay = `${d.schoolName || ''} ${d.address || ''} ${d.issueType || ''} ${d.province || ''}`.toLowerCase();
                  if (!hay.includes(q)) return false;
              }
              return true;
          });

          renderMarkers(filtered);
      }

      els.searchForm?.addEventListener('submit', (e) => {
          e.preventDefault();
          applyFilters();
      });
      els.apply?.addEventListener('click', applyFilters);
      [els.province, els.issue, els.sevHigh, els.sevMedium, els.sevLow, els.stPending, els.stInprog, els.stResolved]
      .filter(Boolean).forEach(el => el.addEventListener('change', applyFilters));

      // ===== Load reports from server (normalize)
      async function resolveEndpoint(path) {
          try {
              const head = await fetch(path, {
                  method: 'HEAD'
              });
              return head.ok ? path : ('../' + path);
          } catch {
              return '../' + path;
          }
      }

      async function loadReports() {
          try {
              const endpoint = await resolveEndpoint('./users/map/get_reports.php');
              const res = await fetch(endpoint, {
                  headers: {
                      'Accept': 'application/json'
                  }
              });
              if (!res.ok) throw new Error(`HTTP ${res.status}`);
              const raw = await res.json();

              reportData = (Array.isArray(raw) ? raw : []).map(r => {
                  const severity = (r.severity || '').toLowerCase(); // low|medium|high
                  const status = (r.status || '').toLowerCase(); // pending|investigating|confirmed|in_progress|resolved
                  const provinceName = r.province || '';
                  const issueName = r.issueType || '';
                  return {
                      ...r,
                      lat: r.lat != null ? parseFloat(r.lat) : NaN,
                      lng: r.lng != null ? parseFloat(r.lng) : NaN,
                      severity,
                      status,
                      province: provinceName,
                      provinceSlug: slugify(provinceName),
                      issueType: issueName,
                      issueSlug: slugify(issueName)
                  };
              });

              // default filter states (kalau checkbox severity/status ada)
              if (els.sevHigh) els.sevHigh.checked = true;
              if (els.sevMedium) els.sevMedium.checked = true;
              if (els.sevLow) els.sevLow.checked = true;
              if (els.stPending) els.stPending.checked = true;
              if (els.stInprog) els.stInprog.checked = true;
              if (els.stResolved) els.stResolved.checked = true;

              // first render
              renderMarkers(reportData);
          } catch (err) {
              console.error("Gagal load data laporan:", err);
              const counterEl = document.getElementById('counter');
              if (counterEl) counterEl.textContent = '0 laporan ditampilkan (error)';
          }

      }

      document.addEventListener('keydown', e => {
          if (e.key === 'Escape') map.closePopup();
      });
      document.addEventListener('DOMContentLoaded', loadReports);
  </script>