# PRD: Sistem Informasi Pengelolaan Wakaf Uang dan Wakaf Melalui Uang (SI-WAKNU)

---

## Informasi Dokumen

| Field                | Detail                                                        |
| -------------------- | ------------------------------------------------------------- |
| **Nama Proyek**      | WAKAF NUSA (Sistem Informasi Wakaf Nahdlatul Ulama)              |
| **Versi**            | 1.0                                                           |
| **Status**           | Draft                                                         |
| **Tanggal Dibuat**   | 11 Mei 2026                                                   |
| **Terakhir Diubah**  | 11 Mei 2026                                                   |
| **Pemilik Dokumen**  | Lembaga Wakaf dan Pertanahan Nahdlatul Ulama                  |

---

## 1. Ringkasan Eksekutif

WAKAF NUSA adalah platform berbasis web untuk pengelolaan **Wakaf Uang** dan **Wakaf Melalui Uang** yang mengacu pada:

- **UU Nomor 41 Tahun 2004** tentang Wakaf
- **PP Nomor 42 Tahun 2006** tentang Pelaksanaan UU Wakaf
- **Peraturan BWI Nomor 1 Tahun 2009** tentang Pedoman Pengelolaan Harta Benda Wakaf Bergerak Berupa Uang
- **Peraturan Perkumpulan (Perkum) Nomor 14 Tahun 2023** tentang Tata Kelola Harta Benda Wakaf di Lingkungan Nahdlatul Ulama

Platform ini dirancang dengan tampilan bergaya *crowdfunding* modern untuk memudahkan masyarakat berwakaf uang secara digital, sekaligus menyediakan sistem manajemen internal bagi Nazhir untuk mengelola program wakaf secara transparan, akuntabel, dan sesuai syariah.

---

## 2. Latar Belakang & Permasalahan

### 2.1 Latar Belakang
Potensi wakaf uang di Indonesia sangat besar namun belum terkelola secara optimal. Pengelolaan masih banyak dilakukan secara manual, tidak terintegrasi, dan kurang transparan. Hal ini menurunkan kepercayaan publik dan menghambat pertumbuhan dana wakaf.

### 2.2 Permasalahan yang Diselesaikan

| No | Masalah                                                                 | Dampak                                          |
| -- | ----------------------------------------------------------------------- | ----------------------------------------------- |
| 1  | Proses pengajuan dan penilaian program wakaf masih manual (paper-based) | Lambat, rawan human error, tidak terdokumentasi |
| 2  | Tidak ada sistem monitoring pencairan dan realisasi dana                | Potensi penyalahgunaan, kurang akuntabel        |
| 3  | Wakif tidak memiliki akses untuk memantau pemanfaatan dana              | Menurunkan kepercayaan dan minat berwakaf       |
| 4  | Pelaporan periodik tidak terstruktur                                    | Sulit diaudit, tidak memenuhi regulasi          |
| 5  | Belum ada platform digital terintegrasi untuk pengelolaan wakaf uang    | Fragmentasi data, duplikasi kerja               |

---

## 3. Tujuan & Sasaran

### 3.1 Tujuan Utama
Membangun platform digital terpadu yang mengelola siklus hidup lengkap wakaf uang: mulai dari pengumpulan dana, penilaian kelayakan program, pencairan, hingga pelaporan realisasi.

### 3.2 Sasaran Terukur (KPI)

| KPI                                       | Target              | Periode   |
| ----------------------------------------- | -------------------- | --------- |
| Jumlah Wakif terdaftar                    | 500 pengguna         | 6 bulan   |
| Total dana wakaf terkumpul                | Rp 500 juta          | 6 bulan   |
| Rata-rata waktu proses penilaian program  | ≤ 7 hari kerja       | Per usul  |
| Tingkat kepatuhan pelaporan Pelaksana     | ≥ 90%                | Per kuartal |
| Skor kepuasan pengguna (CSAT)             | ≥ 4.0 / 5.0          | Per kuartal |

---

## 4. Target Pengguna & Aktor Sistem

### 4.1 Aktor Utama (RBAC — Role-Based Access Control)

#### 1. Wakif (Donatur)
- **Deskripsi**: Individu atau lembaga yang menyetorkan dana wakaf uang
- **Kebutuhan**: Kemudahan berwakaf secara digital, transparansi pemanfaatan, bukti legal (e-AIW, Sertifikat)

#### 2. Pelaksana Nazhir
- **Deskripsi**: Pihak ber-SK dan bersertifikat LSP-BWI yang mengusulkan program wakaf dan melaporkan pelaksanaannya
- **Kebutuhan**: Formulir pengajuan program, upload dokumen, pelaporan periodik

#### 3. Tim Penilai Program Wakaf
- **Deskripsi**: Profesional/akademisi yang menilai kelayakan usulan program menggunakan analisis 5C dan 3P
- **Kebutuhan**: Dashboard review, formulir penilaian terstruktur, akses dokumen proposal

#### 4. Admin Nazhir (LWPNU)
- **Deskripsi**: Administrator Lembaga Wakaf dan Pertanahan NU yang mengelola keseluruhan operasional
- **Kebutuhan**: Monitoring program, manajemen PKS, kontrol pencairan, audit trail

### 4.2 Persona Pengguna

| Persona        | Usia   | Profil                                            | Motivasi                         |
| -------------- | ------ | ------------------------------------------------- | -------------------------------- |
| Hj. Aminah     | 55 thn | Ibu rumah tangga, pengguna smartphone dasar        | Berwakaf mudah tanpa ke bank     |
| Ustadz Hasan   | 40 thn | Pelaksana Nazhir di pesantren, melek teknologi     | Mengajukan program wakaf daring  |
| Dr. Fatimah    | 48 thn | Akademisi ekonomi syariah, tim penilai             | Menilai kelayakan secara objektif|
| Pak Ridwan     | 50 thn | Ketua LWPNU Kabupaten, administrator               | Memantau dan mengendalikan dana  |

---

## 5. Cakupan Produk (Scope)

### 5.1 Dalam Cakupan (In Scope)

- Portal publik bergaya crowdfunding (katalog program, checkout donasi)
- Sistem autentikasi multi-role (RBAC)
- Dashboard Wakif (riwayat donasi, unduh sertifikat)
- Dashboard Pelaksana (pengajuan program wizard, pelaporan)
- Dashboard Tim Penilai (review 5C & 3P)
- Dashboard Admin (monitoring, manajemen PKS, pencairan)
- Integrasi pembayaran (Virtual Account, QRIS) — mock untuk MVP
- Notifikasi email/in-app
- Audit trail dan logging aktivitas
- Fitur social media sharing otomatis
- Multi-bahasa (selain Bahasa Indonesia)
- Tersedia API Callback status pembayaran sukses atau gagal dari payment gateway

### 5.2 Di Luar Cakupan (Out of Scope)

- Aplikasi mobile native (iOS/Android)
- Integrasi langsung dengan sistem core banking LKS-PWU
- Modul akuntansi dan pembukuan lengkap

---

## 6. Kebutuhan Fungsional

### 6.1 Modul Portal Publik

| ID     | Fitur                          | Deskripsi                                                                     | Prioritas |
| ------ | ------------------------------ | ----------------------------------------------------------------------------- | --------- |
| F-PB01 | Landing Page                   | Hero section, statistik dampak, dan daftar program aktif                      | P0        |
| F-PB02 | Katalog Program Wakaf          | Daftar program dengan filter kategori, lokasi, dan progress pendanaan         | P0        |
| F-PB03 | Detail Program                 | Halaman detail dengan deskripsi, RAB, progress bar, dan tombol donasi         | P0        |
| F-PB04 | Checkout Donasi                | Formulir donasi dengan pilihan metode pembayaran (VA/QRIS)                    | P0        |
| F-PB05 | Formulir e-AIW                 | Formulir elektronik Akta Ikrar Wakaf sesuai regulasi BWI                      | P0        |
| F-PB06 | Halaman Tentang & Legalitas    | Informasi lembaga, dasar hukum, dan sertifikasi nazhir                        | P1        |

### 6.2 Modul Autentikasi & Profil

| ID     | Fitur                          | Deskripsi                                                                     | Prioritas |
| ------ | ------------------------------ | ----------------------------------------------------------------------------- | --------- |
| F-AU01 | Registrasi Wakif               | Pendaftaran akun wakif dengan verifikasi email                                | P0        |
| F-AU02 | Login Multi-Role               | Login terpadu dengan redirect otomatis sesuai role pengguna                   | P0        |
| F-AU03 | Manajemen Profil               | Edit data diri, ganti kata sandi, upload foto                                 | P1        |
| F-AU04 | Lupa Kata Sandi                | Reset password melalui email                                                  | P1        |

### 6.3 Modul Dashboard Wakif

| ID     | Fitur                          | Deskripsi                                                                     | Prioritas |
| ------ | ------------------------------ | ----------------------------------------------------------------------------- | --------- |
| F-WK01 | Riwayat Donasi                 | Daftar seluruh transaksi wakaf beserta status pembayaran                      | P0        |
| F-WK02 | Unduh Tanda Bukti Wakaf        | Download PDF tanda bukti penerimaan wakaf uang                                | P0        |
| F-WK03 | Unduh Sertifikat Wakaf Uang    | Download sertifikat SWU untuk donasi yang telah diverifikasi                  | P1        |
| F-WK04 | Pantau Program                 | Melihat progress dan laporan realisasi program yang didanai                   | P1        |

### 6.4 Modul Dashboard Pelaksana Nazhir

| ID     | Fitur                          | Deskripsi                                                                     | Prioritas |
| ------ | ------------------------------ | ----------------------------------------------------------------------------- | --------- |
| F-PN01 | Pengajuan Program (Wizard)     | Formulir bertahap: Info Umum → Target & Lokasi → RAB → Dokumen Pendukung     | P0        |
| F-PN02 | Daftar Program Saya            | Monitoring status seluruh program yang diajukan                               | P0        |
| F-PN03 | Upload Laporan Periodik        | Formulir upload laporan realisasi dengan bukti dokumentasi                    | P0        |
| F-PN04 | Revisi Proposal                | Edit ulang proposal yang dikembalikan oleh Tim Penilai                        | P0        |
| F-PN05 | Validasi RAB                   | Validasi otomatis alokasi biaya operasional (maks 10% sesuai Perkum NU)      | P0        |

### 6.5 Modul Dashboard Tim Penilai

| ID     | Fitur                          | Deskripsi                                                                     | Prioritas |
| ------ | ------------------------------ | ----------------------------------------------------------------------------- | --------- |
| F-TP01 | Daftar Usulan Masuk            | Tabel monitoring usulan yang perlu direview                                   | P0        |
| F-TP02 | Formulir Penilaian 5C          | Checklist: Character, Capacity, Capital, Collateral, Condition                | P0        |
| F-TP03 | Formulir Penilaian 3P          | Checklist: People (Sosial), Planet (Lingkungan), Profit (Keberlanjutan)       | P0        |
| F-TP04 | Keputusan Review               | Pilihan: Direkomendasikan, Perlu Revisi, atau Ditolak (dengan catatan)       | P0        |
| F-TP05 | Akses Dokumen Proposal & RAB   | Viewer dokumen terintegrasi dalam halaman review                              | P1        |

### 6.6 Modul Dashboard Admin Nazhir

| ID     | Fitur                          | Deskripsi                                                                     | Prioritas |
| ------ | ------------------------------ | ----------------------------------------------------------------------------- | --------- |
| F-AD01 | Overview Operasional           | Dashboard statistik: total wakif, dana terkumpul, program aktif               | P0        |
| F-AD02 | Manajemen PKS                  | Penerbitan dan penandatanganan Perjanjian Kerjasama (e-sign mock)             | P0        |
| F-AD03 | Kontrol Pencairan Dana         | Validasi pelaporan sebelum pencairan (auto-lock jika laporan belum masuk)     | P0        |
| F-AD04 | Publikasi Program              | Persetujuan dan publikasi program ke portal publik                            | P0        |
| F-AD05 | Manajemen Pengguna             | CRUD akun Pelaksana dan Tim Penilai                                           | P1        |
| F-AD06 | Log Aktivitas                  | Audit trail seluruh aksi krusial dalam sistem                                 | P1        |
| F-AD07 | Laporan & Ekspor Data          | Generate laporan periodik dalam format PDF/Excel                              | P1        |

---

## 7. Kebutuhan Non-Fungsional

| Aspek            | Kebutuhan                                                                              |
| ---------------- | -------------------------------------------------------------------------------------- |
| **Performa**     | Halaman publik dimuat dalam ≤ 2 detik (LCP). API response ≤ 500ms                     |
| **Keamanan**     | Enkripsi data sensitif (AES-256), HTTPS wajib, proteksi CSRF/XSS, hashing password    |
| **Skalabilitas** | Arsitektur mendukung hingga 10.000 pengguna konkuren tanpa degradasi performa          |
| **Ketersediaan** | Uptime target ≥ 99.5% per bulan                                                        |
| **Responsif**    | UI berfungsi optimal di desktop (1024px+), tablet (768px+), dan mobile (360px+)        |
| **Aksesibilitas**| Memenuhi standar WCAG 2.1 Level AA                                                      |
| **Compliance**   | Sesuai UU Wakaf No.41/2004, Perkum NU No.14/2023, dan pedoman BWI                     |
| **Backup**       | Backup database otomatis harian dengan retensi 30 hari                                  |

---

## 8. Alur Pengguna Utama (User Flow)

### 8.1 Alur Wakif — Berwakaf Uang

```
Buka Portal → Pilih Program → Isi Nominal & Data Diri
→ Isi e-AIW (Akta Ikrar Wakaf) → Pilih Metode Pembayaran
→ Terima VA/QRIS → Lakukan Pembayaran → Konfirmasi Otomatis
→ Terima Tanda Bukti (PDF) → Pantau Realisasi Program
```

### 8.2 Alur Pelaksana Nazhir — Pengajuan Program

```
Login → Dashboard Pelaksana → Usulkan Program Baru
→ Isi Wizard (4 Tahap) → Submit Proposal
→ [Menunggu Review Tim Penilai]
→ Jika Revisi: Edit & Submit Ulang
→ Jika Disetujui: [Menunggu PKS Admin]
→ PKS Ditandatangani → Program Dipublikasikan
→ Dana Terkumpul → Upload Laporan Periodik
→ Laporan Divalidasi → Pencairan Dana Tahap Berikutnya
```

### 8.3 Alur Tim Penilai — Review Program

```
Login → Dashboard Penilai → Pilih Usulan
→ Review Proposal & RAB → Isi Checklist 5C
→ Isi Checklist 3P → Berikan Keputusan
→ (Direkomendasikan / Revisi / Tolak)
→ Submit Review dengan Catatan
```

### 8.4 Alur Admin — Persetujuan & Monitoring

```
Login → Dashboard Admin → Lihat Program Direkomendasikan
→ Review Hasil Penilaian → Terbitkan PKS
→ Tanda Tangan Digital (E-Sign) → Publikasikan ke Portal
→ Monitor Pengumpulan Dana → Validasi Laporan Periodik
→ Cairkan Dana (Jika Laporan Lengkap)
```

---

## 9. Arsitektur Teknis

### 9.1 Technology Stack

| Komponen            | Teknologi                                                         |
| ------------------- | ----------------------------------------------------------------- |
| **Backend**         | Laravel 11+ (PHP 8.2+) / CodeIgniter 4 (alternatif)              |
| **Frontend**        | Blade Template Engine + Vanilla JS / Alpine.js                    |
| **Bahasa**          | PHP 8.2+, JavaScript                                              |
| **Database**        | MySQL 8.0+ / PostgreSQL (via Eloquent ORM / Query Builder)        |
| **Styling**         | Tailwind CSS + Custom NU Design System                            |
| **UI Library**      | Bootstrap 5 / Livewire + Alpine.js + Framer Motion (animasi)     |
| **Autentikasi**     | Laravel Breeze / Fortify (RBAC via Spatie Permission)             |
| **API**             | Laravel RESTful API (Sanctum / Passport untuk token-based auth)   |
| **Storage**         | Laravel Storage (local disk / AWS S3 / MinIO)                     |
| **Pembayaran**      | Mock Payment Gateway (MVP)                                        |
| **Task Queue**      | Laravel Queue (Redis / Database driver)                           |
| **Deployment**      | VPS (Nginx + PHP-FPM) / Shared Hosting (CI4)                     |

### 9.2 Skema Database (Entitas Utama)

```
┌──────────────┐     ┌──────────────┐     ┌──────────────┐
│     User     │────▶│   Program    │◀────│   Review     │
│  (4 Roles)   │     │  (Proposal)  │     │  (5C & 3P)   │
└──────┬───────┘     └──────┬───────┘     └──────────────┘
       │                    │
       │                    ├──────────────┐
       ▼                    ▼              ▼
┌──────────────┐     ┌──────────────┐  ┌──────────────┐
│  Donation    │     │   Report     │  │ Certificate  │
│  (Transaksi) │     │  (Laporan)   │  │  (SWU/Bukti) │
└──────────────┘     └──────────────┘  └──────────────┘
```

**Entitas utama:**
- **User** — Menyimpan data pengguna dengan 4 role (WAKIF, PELAKSANA, PENILAI, ADMIN)
- **Program** — Program wakaf dengan status lifecycle (PROPOSED → REVIEWING → APPROVED → FUNDED → IMPLEMENTING → COMPLETED)
- **Donation** — Transaksi wakaf uang dari Wakif ke Program
- **Review** — Hasil penilaian 5C & 3P oleh Tim Penilai
- **Report** — Laporan realisasi periodik oleh Pelaksana Nazhir
- **Certificate** — Tanda bukti dan sertifikat wakaf uang untuk Wakif

### 9.3 Struktur Direktori Proyek (Laravel)

```
siwaknu/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/              # Login, Register, Reset Password
│   │   │   ├── Public/            # Landing Page, Katalog, Detail Program
│   │   │   ├── Wakif/             # Dashboard Wakif
│   │   │   ├── Pelaksana/         # Dashboard Pelaksana Nazhir
│   │   │   ├── Penilai/           # Dashboard Tim Penilai
│   │   │   ├── Admin/             # Dashboard Admin Nazhir
│   │   │   └── Api/               # API Endpoints (Payment Callback, dll)
│   │   ├── Middleware/            # RBAC, Verified, dll
│   │   └── Requests/             # Form Request Validation
│   ├── Models/                    # Eloquent Models (User, Program, Donation, dll)
│   ├── Services/                  # Business Logic Layer
│   ├── Notifications/             # Email & In-App Notifications
│   ├── Jobs/                      # Queue Jobs (PDF generation, dll)
│   └── Policies/                  # Authorization Policies
├── database/
│   ├── migrations/                # Skema database
│   ├── seeders/                   # Data awal (roles, permissions, dummy)
│   └── factories/                 # Model Factories untuk testing
├── resources/
│   ├── views/
│   │   ├── auth/                  # Halaman Login, Register
│   │   ├── public/                # Landing Page, Katalog
│   │   ├── dashboard/
│   │   │   ├── wakif/
│   │   │   ├── pelaksana/
│   │   │   ├── penilai/
│   │   │   └── admin/
│   │   ├── components/            # Blade Components (reusable UI)
│   │   └── layouts/               # Layout templates (app, guest, dashboard)
│   ├── css/                       # Tailwind CSS source
│   └── js/                        # Alpine.js / Vanilla JS
├── routes/
│   ├── web.php                    # Route halaman web
│   └── api.php                    # Route API (payment callback, dll)
├── public/                        # Assets publik (images, compiled CSS/JS)
├── storage/                       # File uploads, logs, cache
├── config/                        # Konfigurasi aplikasi
├── tests/                         # Unit & Feature Tests (PHPUnit/Pest)
├── tailwind.config.js
├── vite.config.js                 # Laravel Vite untuk asset bundling
└── composer.json
```

---

## 10. Desain & Branding

### 10.1 Identitas Visual

| Elemen             | Spesifikasi                                         |
| ------------------ | --------------------------------------------------- |
| **Warna Primer**   | Hijau NU (#006400 dan variasinya)                   |
| **Warna Sekunder** | Emas (#D4AF37) untuk aksen premium                  |
| **Tipografi**      | Inter (Google Fonts) — modern dan mudah dibaca       |
| **Gaya Desain**    | Glassmorphism, gradient halus, micro-animations      |
| **Inspirasi UI**   | Platform crowdfunding modern (SATUWAKAF, Kitabisa)   |

### 10.2 Prinsip Desain
1. **Kepercayaan (Trust)** — Tampilkan logo BWI, PBNU, dan sertifikasi nazhir secara menonjol
2. **Kesederhanaan** — Alur donasi maksimal 3-4 langkah dari landing page hingga pembayaran
3. **Transparansi** — Progress bar real-time dan laporan realisasi yang dapat diakses publik
4. **Responsivitas** — Mobile-first design mengingat mayoritas pengguna mengakses via smartphone

---

## 11. Rencana Pengembangan (Roadmap)

### Fase 1: Foundation & Portal Publik (Minggu 1–4)
- Setup proyek Laravel (Composer, Vite, MySQL/PostgreSQL)
- Konfigurasi Spatie Permission (roles & permissions RBAC)
- Implementasi design system (warna, tipografi, Blade components)
- Landing page dengan hero section dan statistik dampak
- Katalog program wakaf dengan filter dan progress bar
- Halaman detail program

### Fase 2: Autentikasi & Alur Donasi (Minggu 5–8)
- Sistem autentikasi multi-role (Laravel Breeze + Spatie Permission)
- Alur checkout donasi lengkap (e-AIW, pembayaran mock)
- Dashboard Wakif (riwayat, unduh sertifikat)
- Profil pengguna

### Fase 3: Dashboard Pelaksana & Penilai (Minggu 9–12)
- Formulir pengajuan program (wizard 4 tahap)
- Validasi RAB otomatis (maks 10% operasional)
- Dashboard Tim Penilai dengan checklist 5C & 3P
- Sistem keputusan (Rekomendasikan/Revisi/Tolak)

### Fase 4: Dashboard Admin & Finalisasi (Minggu 13–16)
- Dashboard monitoring Admin Nazhir
- Manajemen PKS dengan e-sign (mock)
- Kontrol pencairan dana (auto-lock)
- Audit trail dan log aktivitas
- UAT (User Acceptance Testing)
- Deployment ke production

---

## 12. Risiko & Mitigasi

| No | Risiko                                                    | Dampak | Probabilitas | Mitigasi                                                    |
| -- | --------------------------------------------------------- | ------ | ------------ | ----------------------------------------------------------- |
| 1  | Integrasi payment gateway terhambat izin LKS-PWU          | Tinggi | Sedang       | Gunakan mock payment untuk MVP, paralel urus perizinan      |
| 2  | Rendahnya adopsi pengguna (terutama Wakif usia lanjut)    | Tinggi | Tinggi       | UI sangat sederhana, panduan video, pendampingan offline     |
| 3  | Perubahan regulasi BWI/PBNU di tengah pengembangan        | Sedang | Rendah       | Arsitektur modular agar mudah disesuaikan                   |
| 4  | Keamanan data finansial dan pribadi Wakif                 | Tinggi | Rendah       | Enkripsi end-to-end, audit keamanan berkala, ISO 27001      |
| 5  | Keterlambatan pelaporan oleh Pelaksana Nazhir             | Sedang | Tinggi       | Auto-lock pencairan, notifikasi reminder otomatis           |

---

## 13. Pertanyaan Terbuka

| No | Pertanyaan                                                                                   | Status      |
| -- | -------------------------------------------------------------------------------------------- | ----------- |
| 1  | Payment Gateway mana yang akan digunakan untuk produksi? (BSI, Bank Muamalat, atau lainnya?) | Belum Jawab |
| 2  | Apakah akan menggunakan provider e-signature resmi (Peruri/Vida) atau cukup internal?        | Belum Jawab |
| 3  | Berapa nominal minimum wakaf uang yang ditetapkan?                                           | Belum Jawab |
| 4  | Apakah diperlukan integrasi dengan sistem SIWAK Kemenag?                                     | Belum Jawab |
| 5  | Siapa yang bertanggung jawab verifikasi identitas Pelaksana Nazhir (onboarding)?             | Belum Jawab |

---

## 14. Rencana Verifikasi

### 14.1 Pengujian Otomatis
- **Unit Test**: Validasi logika RAB, kalkulasi pendanaan, dan kontrol akses RBAC (PHPUnit / Pest)
- **Feature Test**: Alur end-to-end dari pengajuan program hingga pencairan (Laravel HTTP Tests)
- **E2E Test**: Simulasi alur Wakif berwakaf menggunakan Laravel Dusk (browser testing)

### 14.2 Verifikasi Manual
- **Visual QA**: Review tampilan portal publik pada berbagai perangkat
- **Flow Test**: Walkthrough lengkap setiap role dari login hingga aksi utama
- **Security Audit**: Penetration testing sebelum deployment produksi
- **UAT**: Pengujian oleh pengguna representatif dari setiap role

---

## 15. Lampiran

### 15.1 Glosarium

| Istilah                | Definisi                                                                                          |
| ---------------------- | ------------------------------------------------------------------------------------------------- |
| **Wakaf Uang**         | Wakaf berupa uang yang dikelola secara produktif, hasilnya untuk mauquf 'alaih                   |
| **Wakaf Melalui Uang** | Wakaf dengan memberikan uang untuk membeli/mengadakan harta benda sesuai kehendak wakif          |
| **Wakif**              | Pihak yang mewakafkan harta bendanya                                                              |
| **Nazhir**             | Pihak yang menerima dan mengelola harta benda wakaf                                               |
| **Mauquf 'Alaih**      | Pihak yang ditunjuk untuk memperoleh manfaat atas harta benda wakaf                               |
| **AIW**                | Akta Ikrar Wakaf — dokumen legal pernyataan kehendak wakif                                        |
| **SWU**                | Sertifikat Wakaf Uang — bukti kepemilikan wakaf uang                                             |
| **PKS**                | Perjanjian Kerjasama antara Nazhir dan Pelaksana Nazhir                                           |
| **LKS-PWU**            | Lembaga Keuangan Syariah Penerima Wakaf Uang                                                     |
| **LWPNU**              | Lembaga Wakaf dan Pertanahan Nahdlatul Ulama                                                      |
| **5C**                 | Character, Capacity, Capital, Collateral, Condition — kriteria penilaian kelayakan                |
| **3P**                 | People, Planet, Profit — kriteria dampak sosial, lingkungan, dan keberlanjutan                    |
| **BWI**                | Badan Wakaf Indonesia — lembaga independen pembina perwakafan nasional                            |

### 15.2 Referensi Regulasi
1. UU Nomor 41 Tahun 2004 tentang Wakaf
2. PP Nomor 42 Tahun 2006 tentang Pelaksanaan UU Wakaf
3. Peraturan BWI Nomor 1 Tahun 2009 tentang Pedoman Pengelolaan Harta Benda Wakaf Bergerak Berupa Uang
4. Peraturan BWI Nomor 1 Tahun 2020 tentang Pedoman Pengelolaan dan Pengembangan Harta Benda Wakaf
5. Peraturan Perkumpulan NU Nomor 14 Tahun 2023 tentang Tata Kelola Harta Benda Wakaf di Lingkungan NU
