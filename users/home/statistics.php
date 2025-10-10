<?php require_once __DIR__ . '/stats.php'; ?>

<section class="py-16 bg-slate-50">
  <div class="container mx-auto px-4 md:px-6">

    <!-- Header -->
    <div class="text-center mb-12">
      <h2 class="text-2xl md:text-3xl font-bold text-indigo-900 mb-4">
        Dampak Didikara.com
      </h2>
      <p class="text-gray-600 max-w-2xl mx-auto">
        Platform kami telah membantu memetakan dan menyelesaikan berbagai masalah pendidikan
        di seluruh Indonesia melalui kolaborasi pemuda dan pemangku kepentingan.
      </p>
    </div>

    <!-- Stats Cards -->
    <div class="flex flex-wrap justify-center gap-6">

      <!-- Sekolah Terpantau -->
      <div class="bg-blue-50 text-blue-700 border border-blue-200 rounded-lg p-6 w-full sm:w-[280px] animate-fadeIn hover-lift">
        <div class="flex items-start">
          <div class="bg-blue-100 text-blue-600 p-2 rounded-full mr-4">
            <i class="fas fa-school text-xl"></i>
          </div>
          <div>
            <p class="text-sm font-medium">Sekolah Terpantau</p>
            <h4 class="text-2xl font-bold mt-1"><?= number_format($stats['schools'], 0, ',', '.') ?>+</h4>
            <p class="text-sm mt-1 opacity-80">Sekolah telah terpetakan</p>
          </div>
        </div>
      </div>

      <!-- Provinsi Terjangkau -->
      <div class="bg-teal-50 text-teal-700 border border-teal-200 rounded-lg p-6 w-full sm:w-[280px] animate-fadeIn hover-lift">
        <div class="flex items-start">
          <div class="bg-teal-100 text-teal-600 p-2 rounded-full mr-4">
            <i class="fas fa-map text-xl"></i>
          </div>
          <div>
            <p class="text-sm font-medium">Provinsi Terjangkau</p>
            <h4 class="text-2xl font-bold mt-1"><?= $stats['provinces'] ?></h4>
            <p class="text-sm mt-1 opacity-80">Dari 38 provinsi di Indonesia</p>
          </div>
        </div>
      </div>

      <!-- Masalah Terselesaikan -->
      <div class="bg-indigo-50 text-indigo-700 border border-indigo-200 rounded-lg p-6 w-full sm:w-[280px] animate-fadeIn hover-lift">
        <div class="flex items-start">
          <div class="bg-indigo-100 text-indigo-600 p-2 rounded-full mr-4">
            <i class="fas fa-chart-bar text-xl"></i>
          </div>
          <div>
            <p class="text-sm font-medium">Masalah Terselesaikan</p>
            <h4 class="text-2xl font-bold mt-1"><?= $stats['resolved_percent'] ?>%</h4>
            <p class="text-sm mt-1 opacity-80">Dari total laporan masuk</p>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

<script>
  // Counter animation
  document.querySelectorAll("[data-count]").forEach(el => {
    const target = +el.dataset.count;
    let current = 0;
    const step = Math.ceil(target / 100);
    const interval = setInterval(() => {
      current += step;
      if (current >= target) {
        current = target;
        clearInterval(interval);
      }
      el.textContent = current.toLocaleString();
    }, 10);
  });
</script>