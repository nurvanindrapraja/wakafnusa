<p align="center"><img src="public/images/logo_wakaf_nusa_hitam.png" width="300" alt="Wakaf Nusa Logo"></p>

# README: Ekosistem Digital Wakaf Nahdlatul Ulama (SIWAKNU & Wakaf Nusa)

## 1. Pengenalan Ekosistem
Repository ini memuat panduan ekosistem digitalisasi wakaf di lingkungan Nahdlatul Ulama (NU), yang dikelola oleh Lembaga Wakaf dan Pertanahan PBNU (LWPNU). Sistem ini kini telah terintegrasi untuk mengelola wakaf uang dan pemetaan aset tanah melalui satu pintu. 

Aplikasi **SIWAKNU** kini sudah terintegrasi secara langsung dengan **Wakaf Nusa**, yang dapat diakses oleh pengguna (pengurus) melalui tautan: **https://wakafnusa.siwaknu.id/lwp/login**.

---

## 2. Wakaf Nusa (Platform Wakaf Uang & Transparansi Publik)
Wakaf Nusa adalah aplikasi resmi Nahdlatul Ulama untuk memudahkan masyarakat dalam berwakaf uang atau wakaf melalui uang secara transparan, akuntabel, dan sesuai syariah. Platform ini diawasi langsung oleh Badan Wakaf Indonesia (BWI) dan PBNU.

**Fitur Akses Publik (Masyarakat Umum):**
Tanpa perlu *login* khusus, masyarakat umum dan calon wakif dapat mengakses portal publik untuk melihat:
*   **Live Statistics (Statistik Wakaf):** Menampilkan data *real-time* mengenai total dana terkumpul, jumlah wakif yang terdaftar, dan program wakaf yang sedang aktif.
*   **Katalog Aset Wakaf:** Masyarakat dapat melihat daftar lengkap transparansi aset wakaf tanah yang telah terverifikasi dan dikelola secara profesional oleh LWPNU.
*   **Katalog Program Wakaf:** Menjelajahi berbagai program wakaf (Sosial, Ibadah, Pendidikan, Ekonomi, dan Kesehatan) untuk menyalurkan dananya secara non-tunai (QRIS/Virtual Account).

---

## 3. SIWAKNU (Sistem Manajemen & Pemetaan Aset)
SIWAKNU adalah *back-end* manajemen yang dirancang khusus untuk pendataan, pemetaan, dan penyelamatan aset tanah wakaf NU secara digital. Semua data yang dimasukkan ke SIWAKNU akan dipublikasikan ke dalam katalog aset Wakaf Nusa.

### Hak Akses Pengguna (Roles)
Terdapat 4 tingkatan pengguna di dalam sistem manajemen ini:
*   **Beginner**: Pengguna awal yang telah melakukan pendaftaran dan aktivasi akun. Hak akses *role* ini dibatasi **hanya dapat melihat rekapitulasi data wakaf**.
*   **User**: Pengelola di tingkat MWC (Kecamatan) yang berhak melakukan penambahan/entri data aset wakaf.
*   **Moderator**: Pengelola di tingkat PCNU (Cabang) yang bertugas memverifikasi data wakaf dari tingkat di bawahnya dan menambahkan *user*.
*   **Admin**: Hak akses penuh untuk pengelola di tingkat PWNU (Wilayah) dan PBNU (Pusat).

### Sistem Penomoran Aset (NIA)
Setiap aset yang berhasil dientri akan otomatis mendapatkan **Nomor Identitas Aset (NIA)** berupa 10 digit angka unik. NIA berfungsi sebagai kunci pencarian untuk menghindari dobel pendataan, yang terdiri dari: kode PWNU (2 digit), kode PCNU (2 digit), dan nomor urut aset (6 digit).
