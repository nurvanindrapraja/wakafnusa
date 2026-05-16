<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Program;
use App\Models\Donation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Create Roles ─────────────────────────────────────────
        $roles = ['wakif', 'pelaksana', 'penilai', 'admin'];
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        // ── Create Admin ─────────────────────────────────────────
        $admin = User::create([
            'name' => 'Admin LWPNU',
            'email' => 'admin@siwaknu.id',
            'password' => Hash::make('password'),
            'role' => 'ADMIN',
            'phone' => '081234567890',
            'email_verified_at' => now(),
        ]);
        $admin->assignRole('admin');

        // ── Create Pelaksana ─────────────────────────────────────
        $pelaksana = User::create([
            'name' => 'Ustadz Hasan',
            'email' => 'pelaksana@siwaknu.id',
            'password' => Hash::make('password'),
            'role' => 'PELAKSANA',
            'phone' => '081234567891',
            'sk_number' => 'SK/LWPNU/2024/001',
            'lsp_certificate' => 'LSP-BWI/2024/0123',
            'email_verified_at' => now(),
        ]);
        $pelaksana->assignRole('pelaksana');

        // ── Create Tim Penilai ───────────────────────────────────
        $penilai = User::create([
            'name' => 'Dr. Fatimah',
            'email' => 'penilai@siwaknu.id',
            'password' => Hash::make('password'),
            'role' => 'PENILAI',
            'phone' => '081234567892',
            'email_verified_at' => now(),
        ]);
        $penilai->assignRole('penilai');

        // ── Create Wakif ─────────────────────────────────────────
        $wakif = User::create([
            'name' => 'Hj. Aminah',
            'email' => 'wakif@siwaknu.id',
            'password' => Hash::make('password'),
            'role' => 'WAKIF',
            'phone' => '081234567893',
            'email_verified_at' => now(),
        ]);
        $wakif->assignRole('wakif');

        // ── Create Sample Programs ───────────────────────────────
        $programs = [
            [
                'pelaksana_id' => $pelaksana->id,
                'title' => 'Pembangunan Masjid At-Taqwa Desa Sukamaju',
                'category' => 'IBADAH',
                'description' => 'Program wakaf untuk pembangunan masjid At-Taqwa di Desa Sukamaju, Kecamatan Cianjur Selatan. Masjid ini akan melayani jamaah dari 3 desa dengan kapasitas 500 jamaah. Pembangunan meliputi ruang utama, tempat wudhu, dan fasilitas pendukung.',
                'target_amount' => 500000000,
                'collected_amount' => 325000000,
                'location_province' => 'Jawa Barat',
                'location_city' => 'Cianjur',
                'location_detail' => 'Desa Sukamaju, Kec. Cianjur Selatan',
                'beneficiary_count' => 1500,
                'start_date' => '2026-01-15',
                'end_date' => '2026-12-31',
                'rab_total' => 500000000,
                'rab_operational_pct' => 8.5,
                'status' => 'PUBLISHED',
                'cover_image' => 'programs/ibadah.png',
                'published_at' => now()->subDays(30),
            ],
            [
                'pelaksana_id' => $pelaksana->id,
                'title' => 'Beasiswa Santri Berprestasi Pondok Pesantren Nurul Huda',
                'category' => 'PENDIDIKAN',
                'description' => 'Program wakaf melalui uang untuk beasiswa santri berprestasi dari keluarga kurang mampu di Pondok Pesantren Nurul Huda. Dana wakaf akan dikelola secara produktif dan hasilnya digunakan untuk biaya pendidikan, kitab, dan kebutuhan santri.',
                'target_amount' => 200000000,
                'collected_amount' => 87500000,
                'location_province' => 'Jawa Timur',
                'location_city' => 'Jombang',
                'location_detail' => 'Pondok Pesantren Nurul Huda, Kec. Peterongan',
                'beneficiary_count' => 50,
                'start_date' => '2026-03-01',
                'end_date' => '2027-02-28',
                'rab_total' => 200000000,
                'rab_operational_pct' => 7.0,
                'status' => 'PUBLISHED',
                'cover_image' => 'programs/pendidikan.png',
                'published_at' => now()->subDays(15),
            ],
            [
                'pelaksana_id' => $pelaksana->id,
                'title' => 'Klinik Kesehatan Gratis Masyarakat Pedesaan',
                'category' => 'KESEHATAN',
                'description' => 'Pendirian klinik kesehatan gratis untuk masyarakat pedesaan di wilayah terpencil Kabupaten Lombok Timur. Klinik akan menyediakan layanan kesehatan dasar, posyandu, dan edukasi kesehatan ibu dan anak.',
                'target_amount' => 350000000,
                'collected_amount' => 150000000,
                'location_province' => 'Nusa Tenggara Barat',
                'location_city' => 'Lombok Timur',
                'location_detail' => 'Desa Pringgabaya, Kec. Pringgabaya',
                'beneficiary_count' => 3000,
                'start_date' => '2026-06-01',
                'end_date' => '2027-05-31',
                'rab_total' => 350000000,
                'rab_operational_pct' => 9.0,
                'status' => 'PUBLISHED',
                'cover_image' => 'programs/kesehatan.png',
                'published_at' => now()->subDays(10),
            ],
            [
                'pelaksana_id' => $pelaksana->id,
                'title' => 'Pemberdayaan Ekonomi UMKM Berbasis Pesantren',
                'category' => 'EKONOMI',
                'description' => 'Program wakaf produktif untuk pemberdayaan ekonomi umat melalui UMKM berbasis pesantren. Dana akan digunakan untuk modal usaha, pelatihan kewirausahaan, dan pendampingan bisnis bagi santri dan alumni pesantren.',
                'target_amount' => 150000000,
                'collected_amount' => 42000000,
                'location_province' => 'Jawa Tengah',
                'location_city' => 'Kudus',
                'location_detail' => 'Pesantren Darul Falah, Kec. Undaan',
                'beneficiary_count' => 100,
                'start_date' => '2026-04-01',
                'end_date' => '2027-03-31',
                'rab_total' => 150000000,
                'rab_operational_pct' => 10.0,
                'status' => 'PUBLISHED',
                'cover_image' => 'programs/ekonomi.png',
                'published_at' => now()->subDays(5),
            ],
            [
                'pelaksana_id' => $pelaksana->id,
                'title' => 'Sumur Wakaf dan Sanitasi Desa Terpencil',
                'category' => 'SOSIAL',
                'description' => 'Pembangunan sumur bor dan fasilitas sanitasi untuk 5 desa terpencil di Kabupaten Ende, NTT. Program ini bertujuan menyediakan akses air bersih dan sanitasi layak bagi masyarakat yang selama ini kesulitan mendapatkan air bersih.',
                'target_amount' => 250000000,
                'collected_amount' => 175000000,
                'location_province' => 'Nusa Tenggara Timur',
                'location_city' => 'Ende',
                'location_detail' => 'Kec. Wolojita, 5 Desa Terpencil',
                'beneficiary_count' => 2000,
                'start_date' => '2026-02-01',
                'end_date' => '2026-11-30',
                'rab_total' => 250000000,
                'rab_operational_pct' => 8.0,
                'status' => 'PUBLISHED',
                'cover_image' => 'programs/sosial.png',
                'published_at' => now()->subDays(20),
            ],
            [
                'pelaksana_id' => $pelaksana->id,
                'title' => 'Renovasi Madrasah Ibtidaiyah Desa Mekarjaya',
                'category' => 'PENDIDIKAN',
                'description' => 'Renovasi gedung Madrasah Ibtidaiyah yang sudah tidak layak pakai di Desa Mekarjaya. Renovasi meliputi perbaikan atap, dinding, lantai, serta pengadaan mebeler dan sarana belajar mengajar.',
                'target_amount' => 180000000,
                'collected_amount' => 180000000,
                'location_province' => 'Banten',
                'location_city' => 'Pandeglang',
                'location_detail' => 'Desa Mekarjaya, Kec. Sumur',
                'beneficiary_count' => 200,
                'start_date' => '2026-01-01',
                'end_date' => '2026-06-30',
                'rab_total' => 180000000,
                'rab_operational_pct' => 6.5,
                'status' => 'FUNDED',
                'cover_image' => 'programs/pendidikan.png',
                'published_at' => now()->subDays(60),
            ],
        ];

        foreach ($programs as $programData) {
            Program::create($programData);
        }

        // ── Create Sample Donations ──────────────────────────────
        $sampleDonations = [
            ['wakif_id' => $wakif->id, 'program_id' => 1, 'amount' => 500000, 'payment_status' => 'PAID', 'payment_method' => 'VIRTUAL_ACCOUNT', 'paid_at' => now()->subDays(20)],
            ['wakif_id' => $wakif->id, 'program_id' => 2, 'amount' => 1000000, 'payment_status' => 'PAID', 'payment_method' => 'QRIS', 'paid_at' => now()->subDays(10)],
            ['wakif_id' => $wakif->id, 'program_id' => 3, 'amount' => 250000, 'payment_status' => 'PAID', 'payment_method' => 'VIRTUAL_ACCOUNT', 'paid_at' => now()->subDays(5)],
            ['wakif_id' => $wakif->id, 'program_id' => 4, 'amount' => 100000, 'payment_status' => 'PENDING', 'payment_method' => 'QRIS'],
        ];

        foreach ($sampleDonations as $donationData) {
            Donation::create($donationData);
        }
    }
}
