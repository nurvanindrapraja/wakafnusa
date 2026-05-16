<x-public-layout>
    @section('title', 'Tentang WAKAF NUSA')

    <!-- Header -->
    <section class="hero-pattern pt-32 pb-20 relative overflow-hidden" style="background-color: #004000;">
        <div class="absolute inset-0 bg-gradient-to-b from-black/20 to-transparent"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <h1 class="text-3xl md:text-5xl font-extrabold text-white mb-4">Tentang WAKAF NUSA</h1>
            <p class="text-white max-w-2xl mx-auto text-lg opacity-80">Mengenal lebih dekat platform digital resmi
                pengelolaan wakaf uang / melalui uang Nahdlatul Ulama.</p>
        </div>
    </section>

    <section class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-24 items-center">
                <div class="order-2 lg:order-1">
                    <div
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-nu-green-50 text-nu-green-600 text-xs font-bold uppercase tracking-widest mb-6">
                        Misi & Tujuan
                    </div>
                    <h2 class="text-3xl md:text-4xl font-black text-surface-900 mb-6 leading-tight">
                        Memudahkan Penyaluran <br>
                        <span class="text-nu-green-600">Wakaf Uang Digital</span>
                    </h2>
                    <div class="space-y-6 text-lg text-surface-600 leading-relaxed">
                        <p>
                            <strong>WAKAF NUSA</strong> hadir sebagai jawaban atas
                            tantangan pengelolaan wakaf di era digital. Dibangun di bawah naungan <strong>Lembaga Wakaf
                                & Pertanahan PBNU</strong>, platform ini didesain untuk mentransformasi cara umat
                            berkontribusi dalam pembangunan kemaslahatan melalui wakaf uang.
                        </p>
                        <p>
                            Tujuan utama kami adalah menciptakan ekosistem wakaf yang <strong>transparan, akuntabel, dan
                                mudah diakses</strong> oleh siapa saja, di mana saja. Dengan teknologi, kami memastikan
                            setiap rupiah wakaf Anda tercatat dengan benar dan disalurkan ke program-program yang
                            memberikan dampak nyata bagi umat.
                        </p>
                        <p>
                            Melalui wakaf uang atau wakaf melalui uang, Anda tidak hanya menyumbang, tetapi menanam
                            investasi akhirat yang manfaatnya akan terus mengalir abadi, mendukung pendidikan,
                            kesehatan, hingga pemberdayaan ekonomi umat.
                        </p>
                    </div>

                    <div class="mt-10 grid grid-cols-2 gap-8 pt-10 border-t border-surface-100">
                        <div>
                            <div class="text-2xl font-bold text-nu-green-600">Transparan</div>
                            <div class="text-sm text-surface-500">Pelaporan real-time untuk setiap dana yang terkumpul.
                            </div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-nu-green-600">Sesuai Syariah</div>
                            <div class="text-sm text-surface-500">Pengelolaan diawasi oleh Dewan Penilai & BWI.</div>
                        </div>
                    </div>
                </div>

                <div class="order-1 lg:order-2">
                    <div class="relative">
                        <div class="absolute -inset-6 bg-nu-gold-500/15 rounded-[60px] blur-3xl -z-10"></div>
                        <div class="rounded-[60px] overflow-hidden shadow-2xl border-[12px] border-white">
                            <img src="{{ asset('images/about/digital-waqf.png') }}" alt="Ilustrasi Wakaf Digital"
                                class="w-full h-auto scale-105">
                        </div>

                        <!-- Floating Stats -->
                        <div
                            class="absolute -bottom-6 -left-6 bg-white rounded-2xl p-6 shadow-xl border border-surface-100 hidden md:block">
                            <div class="flex items-center gap-4">
                                <div
                                    class="w-12 h-12 rounded-full bg-nu-green-100 flex items-center justify-center text-nu-green-600">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-sm text-surface-400 font-medium">Dampak Sosial</div>
                                    <div class="text-xl font-bold text-surface-900">Maksimal</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-24 bg-surface-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-bold text-surface-900 mb-16">Nilai Utama Kami</h2>
            <div class="grid md:grid-cols-3 gap-12">
                <div class="bg-white p-10 rounded-3xl shadow-card">
                    <div
                        class="w-16 h-16 rounded-2xl bg-nu-green-600 flex items-center justify-center text-white mx-auto mb-8 shadow-lg shadow-nu-green-200">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-surface-900 mb-4">Amanah</h3>
                    <p class="text-surface-500">Menjaga kepercayaan umat dengan pengelolaan yang sesuai dengan regulasi
                        negara dan syariat Islam.</p>
                </div>

                <div class="bg-white p-10 rounded-3xl shadow-card border-2 border-nu-gold-500/20">
                    <div
                        class="w-16 h-16 rounded-2xl bg-nu-gold-500 flex items-center justify-center text-white mx-auto mb-8 shadow-lg shadow-nu-gold-200">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-surface-900 mb-4">Produktif</h3>
                    <p class="text-surface-500">Memastikan dana wakaf dikelola untuk program-program produktif yang
                        memberikan manfaat berkelanjutan.</p>
                </div>

                <div class="bg-white p-10 rounded-3xl shadow-card">
                    <div
                        class="w-16 h-16 rounded-2xl bg-nu-green-600 flex items-center justify-center text-white mx-auto mb-8 shadow-lg shadow-nu-green-200">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-surface-900 mb-4">Umat-Sentris</h3>
                    <p class="text-surface-500">Berfokus pada pemberdayaan jamaah Nahdlatul Ulama dan masyarakat luas
                        secara inklusif.</p>
                </div>
            </div>
        </div>
    </section>

</x-public-layout>