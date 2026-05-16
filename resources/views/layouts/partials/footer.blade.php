<footer class="bg-surface-900 text-white pt-16 pb-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
            <div class="md:col-span-1">
                <div class="flex items-center gap-3 mb-4">
                    <img src="{{ asset('images/logo_wakaf_nusa.png') }}" alt="Wakaf Nusa Logo" class="h-10 w-auto">
                </div>
                <p class="text-surface-400 text-sm leading-relaxed">Platform digital wakaf uang / melalui uang Nahdlatul
                    Ulama. Transparan, akuntabel, dan sesuai syariah.</p>
            </div>
            <div>
                <h4 class="font-semibold text-nu-gold-500 mb-4">Navigasi</h4>
                <ul class="space-y-2 text-sm text-surface-400">
                    <li><a href="/" class="hover:text-white transition-colors">Beranda</a></li>
                    <li><a href="{{ route('catalog') }}" class="hover:text-white transition-colors">Program Wakaf</a>
                    </li>
                    <li><a href="{{ route('about') }}" class="hover:text-white transition-colors">Tentang Kami</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-semibold text-nu-gold-500 mb-4">Dasar Hukum</h4>
                <ul class="space-y-2 text-sm text-surface-400">
                    <li>UU No. 41 Tahun 2004</li>
                    <li>PP No. 42 Tahun 2006</li>
                    <li>Peraturan BWI No. 1/2009</li>
                    <li>Perkum NU No. 14/2023</li>
                </ul>
            </div>
            <div>
                <h4 class="font-semibold text-nu-gold-500 mb-4">Kontak</h4>
                <ul class="space-y-2 text-sm text-surface-400">
                    <li>admin@siwaknu.id</li>
                    <li>(021) 123-4567</li>
                    <li>PBNU, JL Kramat Raya No. 164, Jakarta Pusat</li>
                </ul>
            </div>
        </div>
        <div class="border-t border-surface-800 pt-8 mb-8">
            <div class="flex flex-wrap justify-center items-center gap-8">
                <div class="flex items-center gap-2 text-surface-500">
                    <div class="w-8 h-8 bg-surface-700 rounded-lg flex items-center justify-center"><span
                            class="text-xs font-bold text-nu-gold-500">NU</span></div>
                    <span class="text-xs">PBNU</span>
                </div>
                <div class="flex items-center gap-2 text-surface-500">
                    <div class="w-8 h-8 bg-surface-700 rounded-lg flex items-center justify-center"><span
                            class="text-xs font-bold text-nu-gold-500">BWI</span></div>
                    <span class="text-xs">Badan Wakaf Indonesia</span>
                </div>
                <div class="flex items-center gap-2 text-surface-500">
                    <div class="w-8 h-8 bg-surface-700 rounded-lg flex items-center justify-center"><span
                            class="text-xs font-bold text-nu-gold-500">LW</span></div>
                    <span class="text-xs">LWPNU</span>
                </div>
            </div>
        </div>
        <div class="text-center text-surface-500 text-sm">
            <p>&copy; {{ date('Y') }} WAKAF NUSA — Lembaga Wakaf dan Pertanahan Nahdlatul Ulama.</p>
        </div>
    </div>
</footer>