<script>
    document.addEventListener('DOMContentLoaded', () => {
        const app = {
            state: {
                currentStep: 0,
                schoolLocation: null,
                photoQuality: {
                    score: 0,
                    res: 0,
                    bright: 0,
                    blur: 0
                },
                isSubmitting: false,
            },

            utils: {
                $: s => document.querySelector(s),
                $$: s => Array.from(document.querySelectorAll(s)),
                e: s => {
                    if (s === null || s === undefined) return '';
                    const el = document.createElement('div');
                    el.innerText = String(s);
                    return el.innerHTML;
                },
                calculateDistance(lat1, lon1, lat2, lon2) {
                    if (lat1 == null || lon1 == null || lat2 == null || lon2 == null) return null;
                    const R = 6371e3;
                    const φ1 = lat1 * Math.PI / 180;
                    const φ2 = lat2 * Math.PI / 180;
                    const Δφ = (lat2 - lat1) * Math.PI / 180;
                    const Δλ = (lon2 - lon1) * Math.PI / 180;
                    const a = Math.sin(Δφ / 2) * Math.sin(Δφ / 2) + Math.cos(φ1) * Math.cos(φ2) * Math.sin(Δλ / 2) * Math.sin(Δλ / 2);
                    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
                    return R * c;
                }
            },

            checkAndDisplayDistance() {
                const userLat = parseFloat(this.gpsMap.latInput?.value);
                const userLng = parseFloat(this.gpsMap.lngInput?.value);
                const schoolLoc = this.state.schoolLocation;
                const distanceInfo = this.utils.$('#distance-info');

                if (userLat && userLng && schoolLoc && distanceInfo) {
                    const distance = this.utils.calculateDistance(userLat, userLng, schoolLoc.lat, schoolLoc.lng);
                    if (distance !== null) {
                        distanceInfo.classList.remove('hidden');
                        distanceInfo.textContent = `Jarak dari lokasi Anda ke sekolah: ${distance.toFixed(0)} meter.`;
                        if (distance > 600) {
                            distanceInfo.classList.remove('text-indigo-700');
                            distanceInfo.classList.add('text-red-600');
                            distanceInfo.textContent += ' (JARAK MELEBIHI 600M)';
                        } else {
                            distanceInfo.classList.remove('text-red-600');
                            distanceInfo.classList.add('text-indigo-700');
                        }
                    }
                } else if (distanceInfo) {
                    distanceInfo.classList.add('hidden');
                }
            },

            init() {
                this.formStepper.init();
                this.gpsMap.init();
                this.cameraHandler.init();
                this.schoolFinder.init();
                this.formSubmitter.init();
                this.modals.init();

                const nikInput = this.utils.$('input[name="reporterNIK"]');
                nikInput?.addEventListener('input', function() {
                    this.value = this.value.replace(/\D/g, '').slice(0, 16);
                });
            }
        };
        app.formStepper = {
            steps: app.utils.$$('.form-step'),
            indicators: app.utils.$$('.step-indicator'),
            progressBar: app.utils.$('#progress-bar'),

            init() {
                window.nextStep = this.next.bind(this);
                window.prevStep = this.prev.bind(this);
                this.showStep(0);
            },

            showStep(index) {
                this.steps.forEach((s, idx) => s.style.display = idx === index ? 'block' : 'none');
                this.indicators.forEach((el, idx) => el.classList.toggle('active', idx <= index));
                if (this.progressBar) {
                    const progress = this.steps.length > 1 ? (index / (this.steps.length - 1)) * 100 : 0;
                    this.progressBar.style.width = `${progress}%`;
                }
                app.state.currentStep = index;
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            },

            next() {
                if (!this._validateCurrentStep()) return;
                if (app.state.currentStep < this.steps.length - 1) {
                    this.showStep(app.state.currentStep + 1);
                }
            },

            prev() {
                if (app.state.currentStep > 0) {
                    this.showStep(app.state.currentStep - 1);
                }
            },

            _validateCurrentStep() {
                const index = app.state.currentStep;
                const step = this.steps[index];
                this._clearErrors(step);
                let isValid = true;

                step.querySelectorAll('[required]').forEach(inp => {
                    if (inp.type === 'radio' || inp.type === 'checkbox') {
                        const groupName = inp.name;
                        if (groupName && !step.querySelector(`input[name="${groupName}"]:checked`)) {
                            isValid = false;
                            const groupElements = step.querySelectorAll(`input[name="${groupName}"]`);
                            this._fieldError(groupElements[groupElements.length - 1].closest('div'), 'Pilih salah satu opsi.');
                        }
                        return;
                    }
                    if (!inp.value || !String(inp.value).trim()) {
                        isValid = false;
                        this._fieldError(inp);
                    }
                    if (inp.name === 'description' && (inp.value || '').trim().length < 50) {
                        isValid = false;
                        this._fieldError(inp, 'Deskripsi laporan minimal 50 karakter.');
                    }
                });

                if (index === 0) {
                    if (!app.utils.$('#latitude')?.value || !app.utils.$('#longitude')?.value) {
                        alert('Mohon aktifkan dan tandai lokasi di peta terlebih dahulu.');
                        return false;
                    }
                    const userLat = parseFloat(app.gpsMap.latInput?.value);
                    const userLng = parseFloat(app.gpsMap.lngInput?.value);
                    const schoolLoc = app.state.schoolLocation;

                    if (userLat && userLng && schoolLoc) {
                        const distance = app.utils.calculateDistance(userLat, userLng, schoolLoc.lat, schoolLoc.lng);
                        if (distance > 600) {
                            alert(`Jarak Anda dari sekolah sekitar ${distance.toFixed(0)} meter. Lokasi Anda terlalu jauh dari sekolah yang dilaporkan (maksimal 500 meter).`);
                            return false;
                        }
                    }
                }
                if (index === 1 && (app.cameraHandler.photoFiles.length === 0 || (app.state.photoQuality.score || 0) < 0.60)) {
                    alert('Kualitas foto belum memenuhi syarat (skor ≥ 0.60) atau belum ada foto yang diunggah. Silakan ambil foto yang lebih jelas.');
                    return false;
                }

                return isValid;
            },

            _fieldError(el, msg = 'Wajib diisi.') {
                el.classList.add('ring-2', 'ring-red-500', 'border-red-500');
                const p = document.createElement('p');
                p.className = 'text-xs text-red-500 mt-1 error-message';
                p.textContent = msg;
                el.parentElement.appendChild(p);
            },

            _clearErrors(scope) {
                scope.querySelectorAll('.ring-red-500').forEach(x => x.classList.remove('ring-2', 'ring-red-500', 'border-red-500'));
                scope.querySelectorAll('.error-message').forEach(x => x.remove());
            }
        };
        app.gpsMap = {
            map: null,
            marker: null,
            latInput: app.utils.$('#latitude'),
            lngInput: app.utils.$('#longitude'),
            gpsStatus: app.utils.$('#gps-status'),
            btnGPS: app.utils.$('#btn-get-location'),
            DEFAULT_POS: [-2.5, 118],

            init() {
                this._ensureMap();
                this._initGPSRequest();
                this.btnGPS?.addEventListener('click', () => this._handleGPSButtonClick());
            },

            _ensureMap() {
                if (!window.L || this.map) return;
                this.map = L.map('mini-map').setView(this.DEFAULT_POS, 5);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(this.map);
                setTimeout(() => this.map.invalidateSize(), 120);
            },

            setPosition(lat, lng, zoom = 16, updateInputs = true) {
                this._ensureMap();
                const pos = [lat, lng];
                if (!this.marker) {
                    this.marker = L.marker(pos, {
                        draggable: true
                    }).addTo(this.map);
                    this.marker.on('dragend', e => {
                        const ll = e.target.getLatLng();
                        if (this.latInput) this.latInput.value = ll.lat.toFixed(7);
                        if (this.lngInput) this.lngInput.value = ll.lng.toFixed(7);
                        app.checkAndDisplayDistance();
                    });
                } else {
                    this.marker.setLatLng(pos);
                }
                this.map.setView(pos, zoom, {
                    animate: true
                });

                if (updateInputs) {
                    if (this.latInput) this.latInput.value = lat.toFixed(7);
                    if (this.lngInput) this.lngInput.value = lng.toFixed(7);
                }

                app.checkAndDisplayDistance();
            },

            _initGPSRequest() {
                if (!navigator.geolocation) {
                    alert('Browser tidak mendukung geolokasi.');
                    return window.location.href = '/';
                }
                if (this.gpsStatus) this.gpsStatus.textContent = 'Meminta akses GPS...';
                navigator.geolocation.getCurrentPosition(
                    pos => {
                        const {
                            latitude,
                            longitude
                        } = pos.coords;
                        this.setPosition(latitude, longitude);
                        if (this.gpsStatus) this.gpsStatus.textContent = `Lokasi terdeteksi. Anda bisa menggeser pin jika perlu.`;
                    },
                    err => {
                        console.error(err);
                        alert('GPS wajib diaktifkan untuk melapor. Mohon izinkan akses lokasi dan segarkan halaman.');
                        window.location.href = '/';
                    }, {
                        enableHighAccuracy: true,
                        timeout: 12000,
                        maximumAge: 0
                    }
                );
            },

            _handleGPSButtonClick() {
                this.btnGPS.disabled = true;
                this.btnGPS.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span> Mendapatkan...</span>';
                navigator.geolocation.getCurrentPosition(
                    pos => {
                        this.setPosition(pos.coords.latitude, pos.coords.longitude);
                        this.btnGPS.innerHTML = '<i class="fas fa-check text-green-600"></i><span> Lokasi Diperbarui</span>';
                        this.btnGPS.disabled = false;
                    },
                    err => {
                        console.error(err);
                        alert('Gagal mendapatkan lokasi. Pastikan GPS aktif.');
                        this.btnGPS.innerHTML = '<i class="fas fa-location-arrow"></i><span> Gunakan Lokasi Saat Ini</span>';
                        this.btnGPS.disabled = false;
                    }, {
                        enableHighAccuracy: true,
                        timeout: 10000
                    }
                );
            }
        };
        app.cameraHandler = {
            stream: null,
            isReady: false,
            photoFiles: [],
            cameraView: app.utils.$('#camera-view'),
            videoEl: app.utils.$('#cam-video'),
            previewsContainer: app.utils.$('#photo-previews-container'),
            canvasEl: app.utils.$('#cam-canvas'),
            inputEl: app.utils.$('#hidden-photo'),
            addPhotoBtn: app.utils.$('#btn-add-photo'),
            shutterBtn: app.utils.$('#btn-shutter'),
            nextBtnStep2: app.utils.$('#btn-next-2'),
            init() {
                if (!this.videoEl) return;
                this.videoEl.addEventListener('loadedmetadata', () => {
                    this.isReady = true;
                });
                this.addPhotoBtn?.addEventListener('click', () => this.open());
                this.shutterBtn?.addEventListener('click', () => this.capture());
            },
            async open() {
                if (this.photoFiles.length >= 5) {
                    return alert('Anda sudah mencapai batas maksimal 5 foto.');
                }
                this.isReady = false;
                if (!navigator.mediaDevices?.getUserMedia) {
                    return alert('Browser Anda tidak mendukung akses kamera.');
                }
                this.cameraView?.classList.remove('hidden');
                this.addPhotoBtn?.classList.add('hidden');
                this._resetQualityUI();
                try {
                    this.stream = await navigator.mediaDevices.getUserMedia({
                        video: {
                            facingMode: 'environment',
                            width: {
                                ideal: 1920
                            },
                            height: {
                                ideal: 1080
                            }
                        }
                    });
                    this.videoEl.srcObject = this.stream;
                    this.videoEl.play();
                } catch (err) {
                    console.error("Camera Error:", err);
                    alert('Gagal mengakses kamera.');
                    this.closeStream();
                }
            },
            closeStream() {
                this.stream?.getTracks().forEach(track => track.stop());
                this.stream = null;
                this.cameraView?.classList.add('hidden');
                this.addPhotoBtn?.classList.remove('hidden');
            },
            capture() {
                if (!this.isReady || !this.videoEl.videoWidth) {
                    return alert('Kamera belum siap.');
                }
                const w = this.videoEl.videoWidth,
                    h = this.videoEl.videoHeight;
                this.canvasEl.width = w;
                this.canvasEl.height = h;
                this.canvasEl.getContext('2d').drawImage(this.videoEl, 0, 0, w, h);
                this.canvasEl.toBlob(blob => {
                    if (!blob) return alert('Gagal mengambil gambar.');
                    const file = new File([blob], `bukti_${Date.now()}.jpg`, {
                        type: 'image/jpeg'
                    });
                    this.photoFiles.push(file);
                    this._updateFileInput();
                    this._renderAllPreviews();
                    this.closeStream();
                    this.analyzeQuality();
                }, 'image/jpeg', 0.92);
            },
            deletePhoto(index) {
                if (index > -1 && index < this.photoFiles.length) {
                    this.photoFiles.splice(index, 1);
                    this._updateFileInput();
                    this._renderAllPreviews();
                    if (this.photoFiles.length === 0) {
                        this._resetQualityUI();
                    }
                }
            },
            _renderAllPreviews() {
                if (!this.previewsContainer) return;
                this.previewsContainer.innerHTML = '';
                this.photoFiles.forEach((file, index) => {
                    const reader = new FileReader();
                    reader.onload = e => {
                        const pWrap = document.createElement('div');
                        pWrap.className = 'relative group aspect-square';
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'w-full h-full object-cover rounded-md';
                        const delBtn = document.createElement('button');
                        delBtn.type = 'button';
                        delBtn.innerHTML = '&times;';
                        delBtn.className = 'absolute top-1 right-1 w-6 h-6 bg-red-600/80 hover:bg-red-700 text-white rounded-full flex items-center justify-center text-lg leading-none opacity-0 group-hover:opacity-100 transition-opacity';
                        delBtn.onclick = () => this.deletePhoto(index);
                        pWrap.appendChild(img);
                        pWrap.appendChild(delBtn);
                        this.previewsContainer.appendChild(pWrap);
                    };
                    reader.readAsDataURL(file);
                });
            },
            _updateFileInput() {
                const dT = new DataTransfer();
                this.photoFiles.forEach(f => dT.items.add(f));
                if (this.inputEl) {
                    this.inputEl.files = dT.files;
                }
            },
            _resetQualityUI() {
                const qS = app.utils.$('#q-score');
                if (qS) qS.textContent = '0.00';
                const qB = app.utils.$('#q-bar');
                if (qB) qB.style.width = '0%';
                const qR = app.utils.$('#q-res');
                if (qR) qR.textContent = '• Resolusi: -';
                const qBr = app.utils.$('#q-bright');
                if (qBr) qBr.textContent = '• Kecerahan: -';
                const qBl = app.utils.$('#q-blur');
                if (qBl) qBl.textContent = '• Ketajaman: -';
                if (app.state?.photoQuality) app.state.photoQuality.score = 0;
                if (this.nextBtnStep2) this.nextBtnStep2.disabled = true;
            },
            analyzeQuality() {
                const ctx = this.canvasEl?.getContext('2d');
                if (!ctx) return;
                const W = this.canvasEl.width,
                    H = this.canvasEl.height;
                if (!W || !H) return;
                const iD = ctx.getImageData(0, 0, W, H).data;
                let s = 0;
                for (let i = 0; i < iD.length; i += 4) s += (0.299 * iD[i] + 0.587 * iD[i + 1] + 0.114 * iD[i + 2]);
                const mL = (s / (iD.length / 4)) / 255,
                    bS = Math.max(0, 1 - Math.abs(mL - 0.55) / 0.40),
                    k = [0, -1, 0, -1, 4, -1, 0, -1, 0];
                let a = 0,
                    c = 0,
                    st = 4;
                for (let y = 1; y < H - 1; y += st) {
                    for (let x = 1; x < W - 1; x += st) {
                        let l = 0,
                            ki = 0;
                        for (let ky = -1; ky <= 1; ky++) {
                            for (let kx = -1; kx <= 1; kx++) {
                                const p = ((y + ky) * W + (x + kx)) * 4;
                                const g = 0.299 * iD[p] + 0.587 * iD[p + 1] + 0.114 * iD[p + 2];
                                l += g * k[ki++];
                            }
                        }
                        a += l * l;
                        c++;
                    }
                }
                const v = a / Math.max(1, c),
                    mP = (W * H) / 1e6,
                    rS = Math.min(1, mP / 1.0),
                    bN = Math.min(1, Math.log10(1 + v) / 3);
                const t = 0.4 * rS + 0.25 * bS + 0.35 * bN;
                if (app.state?.photoQuality) app.state.photoQuality = {
                    score: t,
                    res: rS,
                    bright: bS,
                    blur: bN
                };
                const qS = app.utils.$('#q-score');
                if (qS) qS.textContent = t.toFixed(2);
                const qB = app.utils.$('#q-bar');
                if (qB) qB.style.width = `${Math.round(t*100)}%`;
                const qR = app.utils.$('#q-res');
                if (qR) qR.textContent = `• Resolusi: ${mP.toFixed(2)} MP (${(rS*100).toFixed(0)}%)`;
                const qBr = app.utils.$('#q-bright');
                if (qBr) qBr.textContent = `• Kecerahan: ${(mL*100).toFixed(0)}% (${(bS*100).toFixed(0)}%)`;
                const qBl = app.utils.$('#q-blur');
                if (qBl) qBl.textContent = `• Ketajaman: ${(bN*100).toFixed(0)}%`;
                if (this.nextBtnStep2) this.nextBtnStep2.disabled = t < 0.60;
            }
        };
        app.schoolFinder = {
            inputEl: app.utils.$('#schoolName'),
            suggestBox: app.utils.$('#school-suggest'),
            schoolLatInput: app.utils.$('#latitude-sekolah'),
            schoolLngInput: app.utils.$('#longitude-sekolah'),
            provinceSelect: app.utils.$('select[name="province"]'),
            kabKotaInput: app.utils.$('#kab-kota-input'),
            addressTextarea: app.utils.$('textarea[name="address"]'),
            debounceId: null,

            init() {
                if (!this.inputEl) return;
                this.inputEl.addEventListener('input', () => this._onInput());
                document.addEventListener('click', e => {
                    if (this.suggestBox && !this.suggestBox.contains(e.target) && e.target !== this.inputEl) {
                        this._hide();
                    }
                });
            },

            _onInput() {
                app.state.schoolLocation = null;
                if (this.schoolLatInput) this.schoolLatInput.value = '';
                if (this.schoolLngInput) this.schoolLngInput.value = '';
                if (this.kabKotaInput) this.kabKotaInput.value = '';
                if (this.addressTextarea) this.addressTextarea.value = '';
                app.checkAndDisplayDistance();

                const query = this.inputEl.value.trim();
                if (query.length < 3) return this._hide();
                clearTimeout(this.debounceId);
                this.debounceId = setTimeout(() => this._fetch(query), 300);
            },

            async _fetch(query) {
                try {
                    const lat = app.gpsMap.latInput?.value || '';
                    const lng = app.gpsMap.lngInput?.value || '';
                    const url = `/didikara.com/users/report/schools_proxy.php?q=${encodeURIComponent(query)}&lat=${lat}&lng=${lng}`;
                    const res = await fetch(url);
                    if (!res.ok) throw new Error(`HTTP ${res.status}`);
                    const items = await res.json();
                    if (!items?.length) return this._hide();
                    this._render(items);
                } catch (e) {
                    console.error(e);
                    this._hide();
                }
            },

            _render(items) {
                this.suggestBox.innerHTML = items.slice(0, 8).map(it => `
            <button type="button" class="w-full text-left px-3 py-2 hover:bg-indigo-50" 
                    data-name="${app.utils.e(it.name || '')}" 
                    data-lat="${it.lat || ''}" 
                    data-lng="${it.lng || ''}"
                    data-provinsi="${app.utils.e(it.provinsi || '')}"
                    data-kab-kota="${app.utils.e(it.kab_kota || '')}"
                    data-address="${app.utils.e(it.address || '')}">
                <div class="text-sm text-indigo-900 font-medium">${app.utils.e(it.name || '-')}</div>
                <div class="text-xs text-gray-600">${app.utils.e(it.address || '')}</div>
            </button>`).join('');
                this.suggestBox.classList.remove('hidden');
                this.suggestBox.querySelectorAll('button').forEach(btn => {
                    btn.addEventListener('click', () => {
                        this.inputEl.value = btn.dataset.name;
                        const lat = parseFloat(btn.dataset.lat);
                        const lng = parseFloat(btn.dataset.lng);
                        const provinceName = btn.dataset.provinsi;
                        const kabKotaName = btn.dataset.kabKota;
                        const fullAddress = btn.dataset.address;

                        if (this.provinceSelect && provinceName) {
                            const options = Array.from(this.provinceSelect.options);
                            const matchingOption = options.find(opt => opt.text.toLowerCase() === provinceName.toLowerCase());
                            if (matchingOption) {
                                this.provinceSelect.value = matchingOption.value;
                            }
                        }

                        if (this.kabKotaInput) {
                            this.kabKotaInput.value = kabKotaName;
                        }

                        if (this.addressTextarea) {
                            this.addressTextarea.value = fullAddress;
                        }

                        if (!isNaN(lat) && !isNaN(lng)) {
                            app.state.schoolLocation = {
                                lat: lat,
                                lng: lng
                            };
                            if (this.schoolLatInput) this.schoolLatInput.value = lat.toFixed(7);
                            if (this.schoolLngInput) this.schoolLngInput.value = lng.toFixed(7);
                            app.gpsMap.setPosition(lat, lng, 17, false);
                        } else {
                            app.state.schoolLocation = null;
                            if (this.schoolLatInput) this.schoolLatInput.value = '';
                            if (this.schoolLngInput) this.schoolLngInput.value = '';
                            app.checkAndDisplayDistance();
                        }
                        this._hide();
                    });
                });
            },

            _hide() {
                if (!this.suggestBox) return;
                this.suggestBox.classList.add('hidden');
                this.suggestBox.innerHTML = '';
            }
        };

        app.formSubmitter = {
            form: app.utils.$('#report-form'),

            init() {
                this.form?.addEventListener('submit', e => {
                    e.preventDefault();
                    if (!app.formStepper._validateCurrentStep()) return;
                    this.submit();
                });
            },

            async submit() {
                if (app.state.isSubmitting) return;
                app.state.isSubmitting = true;
                const submitBtn = this.form.querySelector('button[type="submit"]');
                const originalHtml = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengirim...';
                try {
                    const formData = new FormData(this.form);
                    const res = await fetch(this.form.action, {
                        method: 'POST',
                        body: formData
                    });
                    const data = await res.json();
                    if (data.success) {
                        app.modals.openSuccess(data.report_id || data.id);
                        this.form.reset();
                        app.formStepper.showStep(0);
                        app.cameraHandler.photoFiles = [];
                        app.cameraHandler._renderAllPreviews();
                        app.cameraHandler._updateFileInput();
                        app.cameraHandler._resetQualityUI();
                    } else {
                        app.modals.openError(data.message || 'Gagal menyimpan laporan.');
                    }
                } catch (err) {
                    console.error(err);
                    app.modals.openError(err.message || 'Gagal menghubungi server.');
                } finally {
                    app.state.isSubmitting = false;
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalHtml;
                }
            }
        };

        app.modals = {
            successModal: app.utils.$('#success-modal'),
            errorModal: app.utils.$('#error-modal'),

            init() {
                document.addEventListener('click', e => {
                    if (e.target.closest('[data-close-modal]')) this.closeAll();
                    const modal = e.target.closest('.fixed.inset-0');
                    if (modal && e.target === modal) this.closeAll();
                });
                document.addEventListener('keydown', e => {
                    if (e.key === 'Escape') this.closeAll();
                });
            },

            openSuccess(reportId) {
                const idSpan = this.successModal?.querySelector('#success-report-id');
                if (idSpan) idSpan.textContent = `#${reportId}`;
                this.successModal?.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            },

            openError(message) {
                const msgP = this.errorModal?.querySelector('#error-message');
                if (msgP) msgP.textContent = message;
                this.errorModal?.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            },

            closeAll() {
                this.successModal?.classList.add('hidden');
                this.errorModal?.classList.add('hidden');
                document.body.style.overflow = '';
            }
        };

        app.init();
    });
</script>