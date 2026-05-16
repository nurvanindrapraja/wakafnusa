# CONTEXT
Anda adalah seorang Full-Stack Software Engineer tingkat lanjut. Tugas Anda adalah merancang dan membuat kode untuk "Sistem Informasi Pengelolaan Wakaf Uang dan Wakaf Melalui Uang Nahdlatul Ulama (NU)" atau disingkat WAKAF NUSA. 

Aplikasi ini harus mengacu pada Surat Keputusan PBNU dan Peraturan Perkumpulan Nomor 14 Tahun 2023, serta memiliki tampilan antarmuka publik bergaya *crowdfunding* mirip platform SATUWAKAF (https://apps.satuwakaf.id/).

# CORE ARCHITECTURE & ACTORS
Aplikasi ini harus mendukung 4 aktor utama dengan *Role-Based Access Control* (RBAC) yang terhubung [1]:
1. **Wakif (Donatur):** Pihak yang menyetorkan dana wakaf [2].
2. **Pelaksana Nazhir:** Pihak ber-SK dan bersertifikat LSP-BWI yang bertugas mengusulkan program wakaf dan melaporkan pelaksanaannya [3].
3. **Tim Penilai Program Wakaf:** Profesional/akademisi yang bertugas menilai kelayakan usulan program (feasibility study) [4, 5].
4. **Nazhir (Pengurus NU/LWPNU):** Admin utama yang menerbitkan persetujuan, mengelola penyaluran dana ke Pelaksana Nazhir, dan mengawasi [6, 7].

# FEATURES & FUNCTIONALITIES

## 1. Portal Publikasi & Fundraising (Frontend Publik)
*   **Katalog Program Wakaf:** Menampilkan kartu program wakaf yang telah disetujui (seperti "Wakaf Produktif Penggemukan Sapi", "Dana Abadi Pendidikan", "Revitalisasi TPA", "Pendidikan Anak Yatim", "Pengembangan Ekonomi Umat","") [8-10].
*   **Detail Kartu Program:** Harus memuat informasi: Judul program, nama lembaga (Pelaksana Nazhir), lokasi, jenis wakaf (Abadi/Temporer), target dana, dan progress pengumpulan dana (progress bar) [9-19].
*   **Checkout Donasi/Wakaf:** 
    *   Wakif dapat memilih metode pembayaran non-tunai (Transfer Bank, Virtual Account, QRIS, E-Wallet, dll) yang bermuara pada rekening Lembaga Keuangan Syariah Penerima Wakaf Uang (LKS-PWU) [20, 21].
    *   Sistem terintegrasi dengan formulir elektronik (e-form) sebagai Akta Ikrar Wakaf (AIW) [21, 22].

## 2. Dasbor Pelaksana Nazhir (Pengusul)
*   **Pengusulan Program:** Form *upload* proposal yang memuat: Judul program, latar belakang, urgensi, tujuan, penerima manfaat (*Mauquf Alaih*), penanggung jawab, Rencana Anggaran Biaya (RAB), dan tahapan waktu pelaksaanaan [3, 23, 24].
*   **Sistem Pelaporan:** Wajib mengirimkan laporan periodik (minimal 3 kali atau setiap bulan untuk wakaf abadi/produktif) [25]. Laporan mencakup:
    *   **Laporan Realisasi:** Progress, foto dokumentasi, dan kendala [26, 27].
    *   **Laporan Keuangan (Buku Kas):** Rincian pemasukan, pengeluaran, dan *upload* bukti transaksi (nota, kuitansi, bukti transfer) [27, 28].

## 3. Dasbor Tim Penilai
*   **Review Program:** Menampilkan daftar usulan program yang masuk dengan nomor register [23]. Tim Penilai dapat memberikan status: **Diterima**, **Koreksi/Perbaikan**, atau **Ditolak** [29].
*   **Kriteria Penilaian:** Memiliki form *checklist* untuk menilai: kesesuaian RAB, rasionalitas tahapan, reputasi pelaksana, kesesuaian nilai NU, serta prinsip 5C dan 3P untuk sektor riil [30, 31].

## 4. Dasbor Nazhir / Admin (LWPNU)
*   **Manajemen Kerjasama & Persetujuan:** Fasilitas e-sign untuk menandatangani Perjanjian Kerjasama dengan Pelaksana Nazhir sebelum program dipublikasikan [32].
*   **Manajemen Sertifikat & Tanda Bukti:** 
    *   Auto-generate "Tanda Bukti Penerimaan Wakaf" [22, 33].
    *   Auto-generate notifikasi/sistem ke bank (LKS-PWU) untuk penerbitan "Sertifikat Wakaf Uang" bagi donasi >= Rp 1.000.000 [33, 34].
*   **Monitoring Pencairan Dana:** Sistem validasi otomatis di mana penyaluran/pencairan dana wakaf periode berikutnya ditahan (ditunda) jika Pelaksana Nazhir belum mengirimkan laporan [28, 35].
*   **Master Data Sistem Informasi Wakaf (Pasal 19):** Tabel database untuk menyimpan aset harta benda wakaf, data Wakif, *Mauquf Alaih*, Pelaksana Nazhir, dan Regulasi [36].

# TECH STACK & DESIGN CONSTRAINTS
*   Gunakan *framework* modern (misalnya: CodeIgniter 5 atau Laravel untuk frontend dan backend, MySQL untuk database).
*   Desain *UI/UX* menggunakan nuansa profesional, Islami, dan bersih (warna dominan hijau khas Nahdlatul Ulama).
*   Siapkan rancangan API *Endpoint* untuk integrasi eksternal: Payment Gateway (LKS-PWU/Bank Syariah) dan WAKAF NUSA [37].