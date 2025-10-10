    <nav id="navbar" class="fixed w-full z-[10000] bg-white/95 backdrop-blur-md shadow-sm transition-all duration-300 py-4">
        <div class="container mx-auto px-4 md:px-6 relative">
            <div class="flex justify-between items-center">
                <a href="./?page=home" class="flex items-center space-x-2">
                    <div class="flex items-center text-indigo-900">
                        <img src="./assets/img/didikara 3.png" alt="" style="width: 120px;">
                    </div>
                </a>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-6">
                    <a href="?page=home"
                        class="text-sm font-medium text-gray-700 hover:text-indigo-700 transition-colors active">Beranda</a>
                    <a href="?page=map"
                        class="text-sm font-medium text-gray-700 hover:text-indigo-700 transition-colors">Peta
                        Interaktif</a>
                    <a href="?page=report"
                        class="text-sm font-medium text-gray-700 hover:text-indigo-700 transition-colors">Laporkan</a>
                    <a href="?page=search"
                        class="text-sm font-medium text-gray-700 hover:text-indigo-700 transition-colors">Cari
                        Laporan</a>
                    <a href="?page=news"
                        class="text-sm font-medium text-gray-700 hover:text-indigo-700 transition-colors">Berita</a>
                    <a href="?page=about"
                        class="text-sm font-medium text-gray-700 hover:text-indigo-700 transition-colors">Tentang
                        Kami</a>
                </div>

                <!-- Mobile menu button -->
                <button id="mobile-menu-btn" class="md:hidden text-gray-700 hover:text-indigo-700">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
            </div>

            <!-- Mobile Menu -->
            <div id="mobile-menu"
                class="md:hidden hidden absolute left-0 right-0 top-full mx-4 py-4 bg-white rounded-md shadow-lg z-[10001]">
                <div class="flex flex-col space-y-4 px-4">
                    <a href="?page=home"
                        class="text-sm font-medium py-2 text-gray-700 hover:text-indigo-700 transition-colors">Beranda</a>
                    <a href="?page=map"
                        class="text-sm font-medium py-2 text-gray-700 hover:text-indigo-700 transition-colors">Peta
                        Interaktif</a>
                    <a href="?page=report"
                        class="text-sm font-medium py-2 text-gray-700 hover:text-indigo-700 transition-colors">Laporkan</a>
                    <a href="?page=search"
                        class="text-sm font-medium py-2 text-gray-700 hover:text-indigo-700 transition-colors">Cari
                        Laporan</a>
                    <a href="?page=news"
                        class="text-sm font-medium py-2 text-gray-700 hover:text-indigo-700 transition-colors">Berita</a>
                    <a href="?page=about"
                        class="text-sm font-medium py-2 text-gray-700 hover:text-indigo-700 transition-colors">Tentang
                        Kami</a>
                </div>
            </div>
        </div>
    </nav>