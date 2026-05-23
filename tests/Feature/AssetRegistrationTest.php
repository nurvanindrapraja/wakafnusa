<?php

namespace Tests\Feature;

use App\Models\WkPengajuan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AssetRegistrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create the wk_lokasi table in-memory for testing
        \Illuminate\Support\Facades\Schema::create('wk_lokasi', function ($table) {
            $table->string('kode_lokasi')->primary();
            $table->string('nama_lokasi');
        });

        // Seed sample location hierarchy used in test payload
        \Illuminate\Support\Facades\DB::table('wk_lokasi')->insert([
            ['kode_lokasi' => '35', 'nama_lokasi' => 'Jawa Timur'],
            ['kode_lokasi' => '35.15', 'nama_lokasi' => 'Sidoarjo'],
            ['kode_lokasi' => '35.15.04', 'nama_lokasi' => 'Gedangan'],
            ['kode_lokasi' => '35.15.04.2001', 'nama_lokasi' => 'Keboansikep'],
        ]);

        // Create the wk_penggunaan table in-memory for testing
        \Illuminate\Support\Facades\Schema::create('wk_penggunaan', function ($table) {
            $table->string('KODE_PENG')->primary();
            $table->string('PENGGUNAAN');
        });

        // Seed sample usages
        \Illuminate\Support\Facades\DB::table('wk_penggunaan')->insert([
            ['KODE_PENG' => '01', 'PENGGUNAAN' => 'MASJID'],
            ['KODE_PENG' => '02', 'PENGGUNAAN' => 'MUSHOLA'],
            ['KODE_PENG' => '03', 'PENGGUNAAN' => 'MADRASAH'],
        ]);
    }

    public function test_public_can_access_registration_form()
    {
        $response = $this->get(route('land-waqf.register.form'));
        
        $response->assertStatus(200);
        $response->assertSee('Pendaftaran Aset Anda');
        $response->assertSee('Nazhir Nahdlatul Ulama');
    }

    public function test_submitting_empty_data_fails_validation()
    {
        $response = $this->postJson(route('land-waqf.register.store'), []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            'provinsi_id',
            'provinsi',
            'kabupaten_id',
            'kabupaten',
            'kecamatan_id',
            'kecamatan',
            'kelurahan_id',
            'kelurahan',
            'alamat',
            'luas',
            'nama_wakif',
            'penggunaan',
            'sudah_ikrar',
            'sudah_sertifikat',
            'tindakan_aset',
            'hp_wa'
        ]);
    }

    public function test_submitting_valid_data_creates_record_successfully()
    {
        $payload = [
            // Step 1
            'provinsi_id' => '35',
            'provinsi' => 'Jawa Timur',
            'kabupaten_id' => '35.15',
            'kabupaten' => 'Sidoarjo',
            'kecamatan_id' => '35.15.04',
            'kecamatan' => 'Gedangan',
            'kelurahan_id' => '35.15.04.2001',
            'kelurahan' => 'Keboansikep',
            'alamat' => 'Jl. Masjid No. 12',

            // Step 2
            'nama_aset' => 'Masjid Al-Musthofa',
            'luas' => 250.50,
            'nama_wakif' => 'Haji Musthofa',
            'penggunaan' => 'MASJID',

            // Step 3
            'sudah_ikrar' => 1,
            'nomor_ikrar' => 'W.2/020/06/2019',
            'tanggal_ikrar' => '2019-06-15',
            'nama_nazhir' => 'Nazhir NU Gedangan',

            // Step 4
            'sudah_sertifikat' => 1,
            'nomor_sertifikat' => '10.05.15.04.1.00124',
            'tanggal_sertifikat' => '2020-08-20',
            'atas_nama_sertifikat' => 'Nazhir Nahdlatul Ulama',
            'jenis_sertifikat' => 'WAKAF',

            // Step 5
            'tindakan_aset' => 'ASET_NU',
            'keterangan' => 'Aset berupa masjid jami kecamatan.',
            'hp_wa' => '081234567890'
        ];

        $response = $this->postJson(route('land-waqf.register.store'), $payload);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Data pendaftaran aset berhasil disimpan dan akan segera ditindaklanjuti oleh pengurus LWP NU setempat.'
        ]);
        
        $response->assertJsonStructure([
            'nomor_pengajuan',
            'download_url'
        ]);

        $this->assertDatabaseHas('wk_pengajuan', [
            'nama_aset' => 'Masjid Al-Musthofa',
            'nama_wakif' => 'Haji Musthofa',
            'luas' => 250.50,
            'tindakan_aset' => 'ASET_NU',
            'hp_wa' => '081234567890'
        ]);
    }

    public function test_can_download_registration_receipt_as_pdf()
    {
        $pengajuan = WkPengajuan::create([
            'provinsi_id' => '35',
            'provinsi' => 'Jawa Timur',
            'kabupaten_id' => '35.15',
            'kabupaten' => 'Sidoarjo',
            'kecamatan_id' => '35.15.04',
            'kecamatan' => 'Gedangan',
            'kelurahan_id' => '35.15.04.2001',
            'kelurahan' => 'Keboansikep',
            'alamat' => 'Jl. Masjid No. 12',
            'nama_aset' => 'Masjid Al-Musthofa',
            'luas' => 250.50,
            'nama_wakif' => 'Haji Musthofa',
            'penggunaan' => 'MASJID',
            'sudah_ikrar' => 1,
            'nomor_ikrar' => 'W.2/020/06/2019',
            'tanggal_ikrar' => '2019-06-15',
            'nama_nazhir' => 'Nazhir NU Gedangan',
            'sudah_sertifikat' => 1,
            'nomor_sertifikat' => '10.05.15.04.1.00124',
            'tanggal_sertifikat' => '2020-08-20',
            'atas_nama_sertifikat' => 'Nazhir Nahdlatul Ulama',
            'jenis_sertifikat' => 'WAKAF',
            'tindakan_aset' => 'ASET_NU',
            'keterangan' => 'Aset berupa masjid jami kecamatan.',
            'hp_wa' => '081234567890',
            'nomor_pengajuan' => 'REG/20260523/00001'
        ]);

        $response = $this->get(route('land-waqf.register.download', ['id' => $pengajuan->id]));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_public_can_access_status_check_form()
    {
        $response = $this->get(route('land-waqf.check-status.form'));

        $response->assertStatus(200);
        $response->assertSee('Cek Status Pendaftaran');
        $response->assertSee('Masukkan Nomor Registrasi');
    }

    public function test_public_status_check_shows_not_found_alert_for_invalid_number()
    {
        $response = $this->get(route('land-waqf.check-status.form', ['nomor_pengajuan' => 'REG/INVALID/99999']));

        $response->assertStatus(200);
        $response->assertSee('Nomor Registrasi Tidak Ditemukan');
        $response->assertSee('REG/INVALID/99999');
    }

    public function test_public_status_check_shows_details_and_badge_for_valid_number()
    {
        $pengajuan = WkPengajuan::create([
            'provinsi_id' => '35', 'provinsi' => 'Jawa Timur',
            'kabupaten_id' => '35.15', 'kabupaten' => 'Sidoarjo',
            'kecamatan_id' => '35.15.04', 'kecamatan' => 'Gedangan',
            'kelurahan_id' => '35.15.04.2001', 'kelurahan' => 'Keboansikep',
            'alamat' => 'Jl. Masjid No. 12',
            'nama_aset' => 'Masjid Al-Musthofa',
            'luas' => 250.50,
            'nama_wakif' => 'Haji Musthofa',
            'penggunaan' => 'MASJID',
            'sudah_ikrar' => 1,
            'nomor_ikrar' => 'W.2/020/06/2019',
            'tanggal_ikrar' => '2019-06-15',
            'nama_nazhir' => 'Nazhir NU Gedangan',
            'sudah_sertifikat' => 1,
            'nomor_sertifikat' => '10.05.15.04.1.00124',
            'tanggal_sertifikat' => '2020-08-20',
            'atas_nama_sertifikat' => 'Nazhir Nahdlatul Ulama',
            'jenis_sertifikat' => 'WAKAF',
            'tindakan_aset' => 'ASET_NU',
            'keterangan' => 'Aset berupa masjid jami kecamatan.',
            'hp_wa' => '081234567890',
            'nomor_pengajuan' => 'REG/20260523/00003',
            'status' => 'VERIFIKASI_LAPANGAN'
        ]);

        $response = $this->get(route('land-waqf.check-status.form', ['nomor_pengajuan' => 'REG/20260523/00003']));

        $response->assertStatus(200);
        $response->assertSee('VERIFIKASI_LAPANGAN');
        $response->assertSee('Verifikasi Lapangan');
        $response->assertSee('Masjid Al-Musthofa');
        $response->assertSee('Haji Musthofa');
        // Check that phone number is masked for privacy
        $response->assertSee('0812****7890');
        $response->assertDontSee('081234567890');
    }

    public function test_public_status_check_via_ajax_returns_only_partial_html()
    {
        $pengajuan = WkPengajuan::create([
            'provinsi_id' => '35', 'provinsi' => 'Jawa Timur',
            'kabupaten_id' => '35.15', 'kabupaten' => 'Sidoarjo',
            'kecamatan_id' => '35.15.04', 'kecamatan' => 'Gedangan',
            'kelurahan_id' => '35.15.04.2001', 'kelurahan' => 'Keboansikep',
            'alamat' => 'Jl. Masjid No. 12',
            'nama_aset' => 'Masjid Al-Musthofa',
            'luas' => 250.50,
            'nama_wakif' => 'Haji Musthofa',
            'penggunaan' => 'MASJID',
            'sudah_ikrar' => 1,
            'nomor_ikrar' => 'W.2/020/06/2019',
            'tanggal_ikrar' => '2019-06-15',
            'nama_nazhir' => 'Nazhir NU Gedangan',
            'sudah_sertifikat' => 1,
            'nomor_sertifikat' => '10.05.15.04.1.00124',
            'tanggal_sertifikat' => '2020-08-20',
            'atas_nama_sertifikat' => 'Nazhir Nahdlatul Ulama',
            'jenis_sertifikat' => 'WAKAF',
            'tindakan_aset' => 'ASET_NU',
            'keterangan' => 'Aset berupa masjid jami kecamatan.',
            'hp_wa' => '081234567890',
            'nomor_pengajuan' => 'REG/20260523/00003',
            'status' => 'VERIFIKASI_LAPANGAN'
        ]);

        $response = $this->get(route('land-waqf.check-status.form', ['nomor_pengajuan' => 'REG/20260523/00003']), [
            'HTTP_X-Requested-With' => 'XMLHttpRequest'
        ]);

        $response->assertStatus(200);
        $response->assertSee('VERIFIKASI_LAPANGAN');
        // It should NOT render the main layout elements like headers/footers
        $response->assertDontSee('Pantau proses verifikasi aset');
        $response->assertDontSee('Cek Status Pendaftaran Aset');
    }
}
