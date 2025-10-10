<!-- About / Tentang Didikara -->
<section class="pt-24 pb-16 min-h-screen">
    <style>
        /* utilitas ringan */
        .gradient-bg {
            background: radial-gradient(1200px 600px at 50% -200px, #4f46e5 0, #4338ca 20%, #312e81 55%, #1f2937 100%)
        }

        .hover-lift {
            transition: transform .25s ease, box-shadow .25s ease
        }

        .hover-lift:hover {
            transform: translateY(-2px)
        }
    </style>

    <div class="container mx-auto px-4 md:px-6">

        <!-- Hero -->
        <header class="relative rounded-xl overflow-hidden mb-16">
            <div class="gradient-bg absolute inset-0"></div>
            <div class="absolute inset-0 opacity-20">
                <div class="absolute inset-0 bg-cover bg-center" style="background-image:url('https://images.pexels.com/photos/301926/pexels-photo-301926.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2')"></div>
            </div>
            <div class="relative p-8 md:p-12 text-center">
                <h1 class="text-3xl md:text-4xl font-bold text-white mb-4">Tentang Didikara.com</h1>
                <p class="text-gray-200 max-w-2xl mx-auto text-lg">
                    Platform kolaborasi pemuda desa, sekolah, pemerintah, dan organisasi untuk memetakan
                    serta menyelesaikan masalah pendidikan lokal di Indonesia.
                </p>
            </div>
        </header>

        <!-- Our Story -->
        <section class="mb-16">
            <div class="text-center mb-8">
                <h2 class="text-2xl md:text-3xl font-bold text-indigo-900 mb-4">Kisah Kami</h2>
                <div class="w-24 h-1 bg-amber-400 mx-auto mb-6"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                <div class="animate-slideIn">
                    <p class="text-gray-700 mb-4">
                        <span class="text-indigo-900 font-semibold">Didikara.com</span> lahir dari keprihatinan pemuda desa di Jawa Barat
                        terhadap kondisi pendidikan sekitar. Banyak masalah teridentifikasi, namun belum ada
                        platform yang memfasilitasi pelaporan dan tindak lanjut terstruktur.
                    </p>
                    <p class="text-gray-700 mb-4">
                        Tahun 2022, lima pemuda lintas bidang (pendidikan, teknologi, kebijakan publik) berkolaborasi
                        membangun platform untuk menghimpun laporan, memvisualisasikan data, dan memfasilitasi kolaborasi
                        antar pemangku kepentingan pendidikan.
                    </p>
                    <p class="text-gray-700">
                        Dengan dukungan awal organisasi non-profit dan universitas lokal, Didikara.com
                        diluncurkan Maret 2023 dan telah membantu memetakan ratusan masalah pendidikan di berbagai wilayah.
                    </p>
                </div>
                <figure class="animate-fadeIn w-full md:max-w-2xl md:justify-self-end">
                    <div class="relative w-full aspect-[16/9] md:aspect-[21/9] overflow-hidden rounded-lg shadow-lg">
                        <img
                            src="https://images.pexels.com/photos/8423721/pexels-photo-8423721.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2"
                            alt="Tim Didikara berdiskusi"
                            class="absolute inset-0 w-full h-full object-cover object-center" />
                    </div>
                </figure>
            </div>
        </section>

        <!-- Vision & Mission -->
        <section class="mb-16">
            <div class="text-center mb-8">
                <h2 class="text-2xl md:text-3xl font-bold text-indigo-900 mb-4">Visi & Misi</h2>
                <div class="w-24 h-1 bg-amber-400 mx-auto mb-6"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <article class="bg-white p-8 rounded-lg shadow-md border-t-4 border-indigo-600 animate-fadeIn">
                    <div class="flex items-center mb-4">
                        <div class="bg-indigo-100 p-3 rounded-full mr-4" aria-hidden="true">
                            <i class="fas fa-book-open text-indigo-600 text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-indigo-900">Visi</h3>
                    </div>
                    <p class="text-gray-700">
                        Menjadi platform kolaboratif terdepan yang mendorong pemerataan akses dan kualitas pendidikan di Indonesia
                        melalui keterlibatan aktif masyarakat lokal, khususnya pemuda desa.
                    </p>
                </article>

                <article class="bg-white p-8 rounded-lg shadow-md border-t-4 border-amber-400 animate-fadeIn">
                    <div class="flex items-center mb-4">
                        <div class="bg-amber-100 p-3 rounded-full mr-4" aria-hidden="true">
                            <i class="fas fa-bullseye text-amber-600 text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-indigo-900">Misi</h3>
                    </div>
                    <ul class="text-gray-700 space-y-2 list-none">
                        <li class="flex">
                            <span class="text-amber-500 mr-2">•</span>
                            Membangun sistem pelaporan yang mudah diakses dan dipahami masyarakat lokal.
                        </li>
                        <li class="flex">
                            <span class="text-amber-500 mr-2">•</span>
                            Menyediakan visualisasi data yang efektif untuk mengidentifikasi pola masalah pendidikan.
                        </li>
                        <li class="flex">
                            <span class="text-amber-500 mr-2">•</span>
                            Memfasilitasi kolaborasi antara pemuda desa, sekolah, pemerintah, dan organisasi.
                        </li>
                        <li class="flex">
                            <span class="text-amber-500 mr-2">•</span>
                            Mendokumentasikan serta membagikan praktik terbaik penyelesaian masalah pendidikan lokal.
                        </li>
                    </ul>
                </article>
            </div>
        </section>

        <!-- Our Values -->
        <section class="mb-16">
            <div class="text-center mb-8">
                <h2 class="text-2xl md:text-3xl font-bold text-indigo-900 mb-4">Nilai-Nilai Kami</h2>
                <div class="w-24 h-1 bg-amber-400 mx-auto mb-6"></div>
                <p class="text-gray-600 max-w-2xl mx-auto">
                    Prinsip yang memandu setiap keputusan dan tindakan kami dalam mewujudkan pendidikan yang lebih baik.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <article class="bg-white p-6 rounded-lg shadow-md animate-fadeIn hover-lift">
                    <div class="bg-indigo-100 w-12 h-12 rounded-full flex items-center justify-center mb-4" aria-hidden="true">
                        <i class="fas fa-users text-indigo-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-indigo-900 mb-2">Partisipatif</h3>
                    <p class="text-gray-600">
                        Solusi terbaik lahir dari keterlibatan aktif masyarakat lokal yang paling memahami konteksnya.
                    </p>
                </article>

                <article class="bg-white p-6 rounded-lg shadow-md animate-fadeIn hover-lift">
                    <div class="bg-amber-100 w-12 h-12 rounded-full flex items-center justify-center mb-4" aria-hidden="true">
                        <i class="fas fa-comments text-amber-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-indigo-900 mb-2">Transparan</h3>
                    <p class="text-gray-600">
                        Menjunjung keterbukaan data, proses pengambilan keputusan, dan penggunaan sumber daya.
                    </p>
                </article>

                <article class="bg-white p-6 rounded-lg shadow-md animate-fadeIn hover-lift">
                    <div class="bg-teal-100 w-12 h-12 rounded-full flex items-center justify-center mb-4" aria-hidden="true">
                        <i class="fas fa-medal text-teal-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-indigo-900 mb-2">Berorientasi Dampak</h3>
                    <p class="text-gray-600">
                        Setiap inisiatif diarahkan untuk menciptakan perubahan nyata pada kualitas pendidikan.
                    </p>
                </article>
            </div>
        </section>

        <!-- Our Team -->
        <section class="mb-16">
            <div class="text-center mb-8">
                <h2 class="text-2xl md:text-3xl font-bold text-indigo-900 mb-4">Tim Kami</h2>
                <div class="w-24 h-1 bg-amber-400 mx-auto mb-6"></div>
                <p class="text-gray-600 max-w-2xl mx-auto">
                    Tim multidisiplin yang terdorong untuk meningkatkan kualitas pendidikan di Indonesia.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                <article class="bg-white rounded-lg shadow-md overflow-hidden text-center animate-fadeIn hover-lift">
                    <div class="h-56 overflow-hidden">
                        <img src="https://images.pexels.com/photos/2379004/pexels-photo-2379004.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2"
                            alt="Andi Wijaya" class="w-full h-full object-cover">
                    </div>
                    <div class="p-4">
                        <h3 class="text-lg font-semibold text-indigo-900">Andi Wijaya</h3>
                        <p class="text-gray-600">Pendiri & CEO</p>
                    </div>
                </article>

                <article class="bg-white rounded-lg shadow-md overflow-hidden text-center animate-fadeIn hover-lift">
                    <div class="h-56 overflow-hidden">
                        <img src="https://images.pexels.com/photos/415829/pexels-photo-415829.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2"
                            alt="Siti Rahayu" class="w-full h-full object-cover">
                    </div>
                    <div class="p-4">
                        <h3 class="text-lg font-semibold text-indigo-900">Siti Rahayu</h3>
                        <p class="text-gray-600">Kepala Program</p>
                    </div>
                </article>

                <article class="bg-white rounded-lg shadow-md overflow-hidden text-center animate-fadeIn hover-lift">
                    <div class="h-56 overflow-hidden">
                        <img src="https://images.pexels.com/photos/614810/pexels-photo-614810.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2"
                            alt="Budi Santoso" class="w-full h-full object-cover">
                    </div>
                    <div class="p-4">
                        <h3 class="text-lg font-semibold text-indigo-900">Budi Santoso</h3>
                        <p class="text-gray-600">Pengembang Platform</p>
                    </div>
                </article>

                <article class="bg-white rounded-lg shadow-md overflow-hidden text-center animate-fadeIn hover-lift">
                    <div class="h-56 overflow-hidden">
                        <img src="https://images.pexels.com/photos/774909/pexels-photo-774909.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2"
                            alt="Dewi Anggraini" class="w-full h-full object-cover">
                    </div>
                    <div class="p-4">
                        <h3 class="text-lg font-semibold text-indigo-900">Dewi Anggraini</h3>
                        <p class="text-gray-600">Manajer Komunitas</p>
                    </div>
                </article>
            </div>
        </section>

        <!-- Contact (info only) -->
        <section>
            <div class="text-center mb-8">
                <h2 class="text-2xl md:text-3xl font-bold text-indigo-900 mb-4">Hubungi Kami</h2>
                <div class="w-24 h-1 bg-amber-400 mx-auto mb-6"></div>
                <p class="text-gray-600 max-w-2xl mx-auto">
                    Punya pertanyaan atau ingin berkolaborasi? Hubungi kami lewat informasi berikut.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <aside class="bg-white p-6 rounded-lg shadow-md animate-fadeIn">
                    <h3 class="text-lg font-semibold text-indigo-900 mb-4">Informasi Kontak</h3>
                    <div class="space-y-4">
                        <div>
                            <h4 class="text-indigo-900 font-medium mb-1">Alamat</h4>
                            <p class="text-gray-600">Jl. Pendidikan No. 123, Jakarta Pusat, Indonesia</p>
                        </div>
                        <div>
                            <h4 class="text-indigo-900 font-medium mb-1">Email</h4>
                            <a href="mailto:info@didikara.com" class="text-indigo-600 hover:text-indigo-800">info@didikara.com</a>
                        </div>
                        <div>
                            <h4 class="text-indigo-900 font-medium mb-1">Telepon</h4>
                            <a href="tel:+62212345678" class="text-indigo-600 hover:text-indigo-800">+62 21 2345 678</a>
                        </div>
                        <div class="mt-6">
                            <h4 class="text-indigo-900 font-medium mb-3">Ikuti Kami</h4>
                            <nav class="flex space-x-4" aria-label="Social links">
                                <a href="#" aria-label="Facebook" class="text-indigo-600 hover:text-indigo-800"><i class="fab fa-facebook text-2xl"></i></a>
                                <a href="#" aria-label="Twitter/X" class="text-indigo-600 hover:text-indigo-800"><i class="fab fa-twitter text-2xl"></i></a>
                                <a href="#" aria-label="Instagram" class="text-indigo-600 hover:text-indigo-800"><i class="fab fa-instagram text-2xl"></i></a>
                                <a href="#" aria-label="GitHub" class="text-indigo-600 hover:text-indigo-800"><i class="fab fa-github text-2xl"></i></a>
                            </nav>
                        </div>
                    </div>
                </aside>

                <aside class="bg-white p-6 rounded-lg shadow-md animate-fadeIn">
                    <h3 class="text-lg font-semibold text-indigo-900 mb-4">Lokasi</h3>
                    <div class="h-56 bg-gray-200 rounded-md flex items-center justify-center">
                        <p class="text-gray-500 text-sm">(Peta lokasi akan ditampilkan di sini)</p>
                    </div>
                </aside>
            </div>
        </section>
    </div>
</section>

<script src="./assets/js/script.js"></script>
