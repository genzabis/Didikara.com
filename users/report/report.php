<div class="pt-24 pb-16 min-h-screen">
    <div class="container mx-auto px-4 md:px-6">
        <div class="max-w-4xl mx-auto">
            <div class="mb-8 text-center">
                <h1 class="text-2xl md:text-3xl font-bold text-indigo-900 mb-4">
                    Laporkan Masalah Pendidikan
                </h1>
                <p class="text-gray-600">
                    Bantu kami memetakan masalah pendidikan di Indonesia dengan melaporkan masalah yang Anda temui.
                    Laporan Anda akan menjadi bagian penting dalam advokasi perbaikan sistem pendidikan.
                </p>
            </div>
            <?php
            require_once 'info_cards.php';
            require_once 'report_form.php';
            ?>
        </div>
    </div>
</div>

<script>
    let currentStep = 1;
    const totalSteps = 3;

    // Mobile menu toggle (kompatibel: hidden & active)
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');

    if (mobileMenuBtn && mobileMenu) {
        mobileMenuBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            // tampil/sembunyikan
            mobileMenu.classList.toggle('hidden');
            // kalau pakai animasi .mobile-menu.active, ikut toggle juga
            if (mobileMenu.classList.contains('mobile-menu')) {
                mobileMenu.classList.toggle('active');
            }
            // state untuk a11y
            mobileMenuBtn.setAttribute(
                'aria-expanded',
                mobileMenu.classList.contains('hidden') ? 'false' : 'true'
            );
        });

        // klik di luar -> tutup
        document.addEventListener('click', (e) => {
            if (!mobileMenu.contains(e.target) && !mobileMenuBtn.contains(e.target)) {
                if (!mobileMenu.classList.contains('hidden')) {
                    mobileMenu.classList.add('hidden');
                    mobileMenu.classList.remove('active'); // jika ada animasi
                    mobileMenuBtn.setAttribute('aria-expanded', 'false');
                }
            }
        });

        // Esc -> tutup
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !mobileMenu.classList.contains('hidden')) {
                mobileMenu.classList.add('hidden');
                mobileMenu.classList.remove('active');
                mobileMenuBtn.setAttribute('aria-expanded', 'false');
            }
        });

        // klik link di menu -> tutup
        mobileMenu.querySelectorAll('a').forEach(a => {
            a.addEventListener('click', () => {
                mobileMenu.classList.add('hidden');
                mobileMenu.classList.remove('active');
                mobileMenuBtn.setAttribute('aria-expanded', 'false');
            });
        });
    }

    function nextStep() {
        if (currentStep < totalSteps) {
            // Hide current step
            document.getElementById(`step-${currentStep}`).classList.remove('active');
            document.querySelectorAll('.step-indicator')[currentStep - 1].classList.remove('active');

            // Show next step
            currentStep++;
            document.getElementById(`step-${currentStep}`).classList.add('active');
            document.querySelectorAll('.step-indicator')[currentStep - 1].classList.add('active');

            // Update progress bar
            const progress = (currentStep / totalSteps) * 100;
            document.getElementById('progress-bar').style.width = progress + '%';

            // Scroll to top
            window.scrollTo(0, 0);
        }
    }

    function prevStep() {
        if (currentStep > 1) {
            // Hide current step
            document.getElementById(`step-${currentStep}`).classList.remove('active');
            document.querySelectorAll('.step-indicator')[currentStep - 1].classList.remove('active');

            // Show previous step
            currentStep--;
            document.getElementById(`step-${currentStep}`).classList.add('active');
            document.querySelectorAll('.step-indicator')[currentStep - 1].classList.add('active');

            // Update progress bar
            const progress = (currentStep / totalSteps) * 100;
            document.getElementById('progress-bar').style.width = progress + '%';

            // Scroll to top
            window.scrollTo(0, 0);
        }
    }

    function toggleAnonymous() {
        const checkbox = document.querySelector('input[name="anonymous"]');
        const reporterFields = document.getElementById('reporter-fields');
        const nameField = document.querySelector('input[name="reporterName"]');

        if (checkbox.checked) {
            reporterFields.style.display = 'none';
            nameField.required = false;
        } else {
            reporterFields.style.display = 'grid';
            nameField.required = true;
        }
    }

    function closeModal() {
        document.getElementById('success-modal').classList.add('hidden');
        // Reset form
        document.getElementById('report-form').reset();
        currentStep = 1;
        document.getElementById('step-1').classList.add('active');
        document.getElementById('step-2').classList.remove('active');
        document.getElementById('step-3').classList.remove('active');
        document.querySelectorAll('.step-indicator').forEach((indicator, index) => {
            if (index === 0) {
                indicator.classList.add('active');
            } else {
                indicator.classList.remove('active');
            }
        });
        document.getElementById('progress-bar').style.width = '33.33%';
    }

    // Form submission
    document.getElementById('report-form').addEventListener('submit', function(e) {
        e.preventDefault();

        // Show success modal
        document.getElementById('success-modal').classList.remove('hidden');
    });

    // File upload handling
    document.getElementById('file-upload').addEventListener('change', function(e) {
        const files = e.target.files;
        if (files.length > 0) {
            // You can add file preview functionality here
            console.log('Files selected:', files);
        }
    });
</script>