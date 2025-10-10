      <!-- STEP 2 -->
      <div id="step-2" class="form-step" style="display:none">
          <div class="space-y-6">
              <div class="flex items-center mb-2">
                  <i class="fas fa-camera text-amber-500 mr-2 text-2xl"></i>
                  <h3 class="text-lg font-semibold text-indigo-900">Foto & Detail Masalah</h3>
              </div>

              <!-- Kamera Langsung -->
              <div id="camera-view" class="relative hidden mb-4">
                  <video id="cam-video" class="w-full rounded-md border bg-black" autoplay playsinline muted></video>
                  <canvas id="cam-canvas" class="hidden"></canvas>

                  <div class="absolute inset-x-0 bottom-4 flex justify-center">
                      <button type="button" id="btn-shutter" class="w-16 h-16 bg-white/90 hover:bg-white rounded-full flex items-center justify-center shadow-lg">
                          <span class="w-12 h-12 bg-indigo-600 rounded-full"></span>
                      </button>
                  </div>
              </div>

              <div id="photo-previews-container" class="grid grid-cols-3 sm:grid-cols-4 gap-4 mb-4">
              </div>

              <div id="quality-analysis" class="space-y-2 border-t pt-4 mt-4 mb-4">
                  <h4 class="font-semibold text-gray-800">Analisis Kualitas Foto Terakhir</h4>
                  <div class="flex items-center gap-3">
                      <div id="q-score" class="text-2xl font-bold text-indigo-600">0.00</div>
                      <div class="w-full bg-gray-200 rounded-full h-2.5">
                          <div id="q-bar" class="bg-indigo-600 h-2.5 rounded-full" style="width: 0%"></div>
                      </div>
                  </div>
                  <div class="text-sm text-gray-600 space-y-1">
                      <p id="q-res">• Resolusi: -</p>
                      <p id="q-bright">• Kecerahan: -</p>
                      <p id="q-blur">• Ketajaman: -</p>
                  </div>
              </div>

              <div class="text-center">
                  <button type="button" id="btn-add-photo" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium">
                      Tambah Foto
                  </button>
              </div>

              <input id="hidden-photo" name="attachments[]" type="file" class="hidden" required multiple />


              <!-- Jenis Masalah -->
              <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Masalah <span class="text-red-500">*</span></label>
                  <select name="issueType" required
                      class="w-full px-4 py-2 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                      <option value="">Pilih Jenis Masalah</option>
                      <?php foreach ($issues as $it): ?>
                          <option value="<?= e($it['slug']) ?>"><?= e($it['name']) ?></option>
                      <?php endforeach; ?>
                  </select>
              </div>

              <!-- Severity -->
              <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Tingkat Keparahan <span class="text-red-500">*</span></label>
                  <div class="flex gap-4">
                      <label class="inline-flex items-center"><input type="radio" name="severity" value="high" class="form-radio h-4 w-4 text-red-600"><span class="ml-2 text-sm">Tinggi</span></label>
                      <label class="inline-flex items-center"><input type="radio" name="severity" value="medium" class="form-radio h-4 w-4 text-amber-600"><span class="ml-2 text-sm">Sedang</span></label>
                      <label class="inline-flex items-center"><input type="radio" name="severity" value="low" class="form-radio h-4 w-4 text-teal-600"><span class="ml-2 text-sm">Rendah</span></label>
                  </div>
              </div>

              <!-- Deskripsi -->
              <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Masalah <span class="text-red-500">*</span></label>
                  <textarea name="description" rows="5" required
                      class="w-full px-4 py-2 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                      placeholder="Jelaskan detail masalah (≥50 karakter)."></textarea>
              </div>

              <div class="flex justify-between">
                  <button type="button" onclick="prevStep()" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-md font-medium hover:bg-gray-50">Kembali</button>
                  <button type="button" onclick="nextStep()" id="btn-next-2" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md font-medium" disabled>Lanjutkan</button>
              </div>
          </div>
      </div>

      <!-- STEP 3 -->
      <div id="step-3" class="form-step" style="display:none">
          <div class="space-y-6">
              <div class="flex items-center mb-2">
                  <i class="fas fa-user text-indigo-600 mr-2 text-2xl"></i>
                  <h3 class="text-lg font-semibold text-indigo-900">Identitas Pelapor</h3>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div>
                      <label class="block text-sm font-medium text-gray-700 mb-1">NIK <span class="text-red-500">*</span></label>
                      <input type="text" name="reporterNIK" maxlength="16" pattern="\d{16}" required
                          class="w-full px-4 py-2 rounded-md border border-gray-300 focus:ring-2 focus:ring-indigo-500"
                          placeholder="16 digit KTP">
                  </div>
                  <div>
                      <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                      <input type="text" name="reporterName" required
                          class="w-full px-4 py-2 rounded-md border border-gray-300 focus:ring-2 focus:ring-indigo-500"
                          placeholder="Nama sesuai identitas">
                  </div>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div>
                      <label class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                      <input type="email" name="reporterEmail" required
                          class="w-full px-4 py-2 rounded-md border border-gray-300 focus:ring-2 focus:ring-indigo-500"
                          placeholder="nama@email.com">
                      <p class="text-xs text-gray-500 mt-1">Notifikasi status laporan dikirim ke email ini.</p>
                  </div>
                  <div>
                      <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon (Opsional)</label>
                      <input type="tel" name="reporterPhone"
                          class="w-full px-4 py-2 rounded-md border border-gray-300 focus:ring-2 focus:ring-indigo-500"
                          placeholder="0812xxxxxxx">
                  </div>
              </div>

              <label class="flex items-center">
                  <input type="checkbox" name="agreeTerms" required class="form-checkbox h-5 w-5 text-indigo-600">
                  <span class="ml-2 text-gray-700 text-sm">Saya menyatakan data yang diberikan benar dan dapat dipertanggungjawabkan.</span>
              </label>

              <div class="flex justify-between">
                  <button type="button" onclick="prevStep()" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-md font-medium hover:bg-gray-50">Kembali</button>
                  <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token'] ?? '') ?>">
                  <button type="submit" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md font-medium">Kirim Laporan</button>
              </div>
          </div>
      </div>
      </form>

      <!-- SUCCESS MODAL (z tinggi biar gak ketutup navbar) -->
      <div id="success-modal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-[100] hidden">
          <div class="bg-white rounded-lg shadow-xl p-8 max-w-md mx-4 w-full">
              <div class="text-center">
                  <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 mb-4">
                      <i class="fas fa-check text-green-600 text-xl"></i>
                  </div>
                  <h3 class="text-lg font-medium text-gray-900 mb-2">Laporan Terkirim!</h3>
                  <p class="text-sm text-gray-500 mb-4">Terima kasih. Laporan Anda akan ditindaklanjuti.</p>
                  <p class="text-sm font-medium text-gray-700 mb-4">ID Laporan: <span id="success-report-id" class="text-indigo-600">#—</span></p>
                  <div class="space-y-2">
                      <a href="?page=map" class="w-full inline-flex items-center justify-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md font-medium">Lihat di Peta</a>
                      <button data-close-modal class="w-full px-4 py-2 border border-gray-300 text-gray-700 rounded-md font-medium hover:bg-gray-50">Tutup</button>
                  </div>
              </div>
          </div>
      </div>

      <!-- ERROR MODAL -->
      <div id="error-modal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-[100] hidden">
          <div class="bg-white rounded-lg shadow-xl p-8 max-w-md mx-4 w-full">
              <div class="text-center">
                  <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                      <i class="fas fa-times text-red-600 text-xl"></i>
                  </div>
                  <h3 class="text-lg font-medium text-gray-900 mb-2">Laporan Gagal</h3>
                  <p id="error-message" class="text-sm text-gray-500 mb-4">Terjadi kesalahan saat mengirim data.</p>
                  <div class="space-y-2">
                      <button data-retry class="w-full inline-flex items-center justify-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md font-medium">Coba Lagi</button>
                      <button data-close-error class="w-full px-4 py-2 border border-gray-300 text-gray-700 rounded-md font-medium hover:bg-gray-50">Tutup</button>
                  </div>
              </div>
          </div>
      </div>
      </div>
      </div>