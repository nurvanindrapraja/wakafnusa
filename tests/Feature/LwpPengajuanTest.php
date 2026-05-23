<?php

namespace Tests\Feature;

use App\Models\LwpUser;
use App\Models\WkPengajuan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class LwpPengajuanTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Override mysql_no_prefix connection to share the same SQLite connection instance during tests
        $defaultConnection = DB::connection();
        app('db')->extend('mysql_no_prefix', function () use ($defaultConnection) {
            return $defaultConnection;
        });

        // 1. Create wk_user table in-memory
        Schema::create('wk_user', function ($table) {
            $table->increments('ID_USER');
            $table->string('NAMA_USER');
            $table->string('USERNAME_USER');
            $table->string('PASSWORD_USER');
            $table->string('EMAIL_USER');
            $table->integer('LEVEL');
            $table->string('KODE_LOKASI')->nullable();
            $table->boolean('ISDEL')->default(false);
        });

        // 2. Create wk_wakaf table in-memory
        Schema::create('wk_wakaf', function ($table) {
            $table->increments('ID_WAKAF');
            $table->string('KODE_LOKASI');
            $table->text('ALAMAT');
            $table->string('NO_AIW')->nullable();
            $table->date('TGL_AIW')->nullable();
            $table->string('NO_SERT')->nullable();
            $table->date('TGL_SERT')->nullable();
            $table->string('NAMA');
            $table->string('WAKIF');
            $table->string('NADZIR');
            $table->decimal('LUAS', 15, 2);
            $table->string('KODE_PENG');
            $table->integer('ID_ST');
            $table->integer('ID_SP');
            $table->integer('ID_SW');
            $table->integer('ID_KRITERIA');
            $table->decimal('NJOP', 15, 2)->default(0);
            $table->integer('THN_NJOP')->default(0);
            $table->text('KETERANGAN')->nullable();
            $table->decimal('LAT', 10, 7)->default(0);
            $table->decimal('LNG', 10, 7)->default(0);
            $table->unsignedInteger('ID_USER');
            $table->timestamp('TGINS');
            $table->boolean('ISDEL')->default(false);
            $table->boolean('ISOK')->default(true);
            $table->string('NIA');
            $table->string('KODE_INDEX')->nullable();
        });

        // 3. Create helper tables
        Schema::create('wk_cabang', function ($table) {
            $table->string('KODE_CABANG')->primary();
            $table->string('KODE_INDEX');
        });

        Schema::create('wk_penggunaan', function ($table) {
            $table->string('KODE_PENG')->primary();
            $table->string('PENGGUNAAN');
        });

        Schema::create('wk_sttanah', function ($table) {
            $table->increments('ID_ST');
            $table->string('JENIS_ST');
        });

        Schema::create('wk_stprod', function ($table) {
            $table->increments('ID_SP');
            $table->string('JENIS_SP');
        });

        Schema::create('wk_stwakaf', function ($table) {
            $table->increments('ID_SW');
            $table->string('JENIS_SW');
        });

        Schema::create('wk_kriteria', function ($table) {
            $table->increments('ID_KRITERIA');
            $table->string('KRITERIA');
        });

        Schema::create('wk_hisentri', function ($table) {
            $table->increments('id_history');
            $table->unsignedInteger('id_wakaf');
            $table->unsignedInteger('id_user');
            $table->timestamp('tgl_entri');
            $table->integer('fase');
        });

        // 4. Seed helper data
        DB::table('wk_cabang')->insert([
            ['KODE_CABANG' => '35.15', 'KODE_INDEX' => 'LWP.3515']
        ]);

        DB::table('wk_penggunaan')->insert([
            ['KODE_PENG' => '01', 'PENGGUNAAN' => 'MASJID']
        ]);

        DB::table('wk_sttanah')->insert([
            ['ID_ST' => 1, 'JENIS_ST' => 'TIDAK SENGKETA']
        ]);

        DB::table('wk_stprod')->insert([
            ['ID_SP' => 1, 'JENIS_SP' => 'PRODUKTIF']
        ]);

        DB::table('wk_stwakaf')->insert([
            ['ID_SW' => 1, 'JENIS_SW' => 'WAKAF']
        ]);

        DB::table('wk_kriteria')->insert([
            ['ID_KRITERIA' => 1, 'KRITERIA' => 'KRITERIA A']
        ]);
    }

    public function test_guest_cannot_access_pengajuan_index()
    {
        $response = $this->get(route('lwp.pengajuan.index'));
        $response->assertRedirect(route('lwp.login'));
    }

    public function test_admin_can_view_all_pengajuan()
    {
        $admin = LwpUser::create([
            'NAMA_USER' => 'Admin SIWAKNU',
            'USERNAME_USER' => 'admin',
            'PASSWORD_USER' => sha1('password'),
            'EMAIL_USER' => 'admin@siwaknu.id',
            'LEVEL' => 9,
            'KODE_LOKASI' => '35'
        ]);

        WkPengajuan::create([
            'provinsi_id' => '35', 'provinsi' => 'Jawa Timur',
            'kabupaten_id' => '35.15', 'kabupaten' => 'Sidoarjo',
            'kecamatan_id' => '35.15.04', 'kecamatan' => 'Gedangan',
            'kelurahan_id' => '35.15.04.2001', 'kelurahan' => 'Keboansikep',
            'alamat' => 'Jl. Masjid No. 12', 'nama_aset' => 'Masjid Al-Falah',
            'luas' => 250.00, 'nama_wakif' => 'H. Anwar', 'penggunaan' => 'MASJID',
            'tindakan_aset' => 'ASET_NU', 'hp_wa' => '081234567890',
            'nomor_pengajuan' => 'REG/20260523/00001'
        ]);

        $response = $this->actingAs($admin, 'lwp')->get(route('lwp.pengajuan.index'));

        $response->assertStatus(200);
        $response->assertSee('REG/20260523/00001');
        $response->assertSee('Masjid Al-Falah');
    }

    public function test_can_verify_lapangan_successfully()
    {
        $admin = LwpUser::create([
            'NAMA_USER' => 'Admin SIWAKNU',
            'USERNAME_USER' => 'admin',
            'PASSWORD_USER' => sha1('password'),
            'EMAIL_USER' => 'admin@siwaknu.id',
            'LEVEL' => 9,
            'KODE_LOKASI' => '35'
        ]);

        $pengajuan = WkPengajuan::create([
            'provinsi_id' => '35', 'provinsi' => 'Jawa Timur',
            'kabupaten_id' => '35.15', 'kabupaten' => 'Sidoarjo',
            'kecamatan_id' => '35.15.04', 'kecamatan' => 'Gedangan',
            'kelurahan_id' => '35.15.04.2001', 'kelurahan' => 'Keboansikep',
            'alamat' => 'Jl. Masjid No. 12', 'nama_aset' => 'Masjid Al-Falah',
            'luas' => 250.00, 'nama_wakif' => 'H. Anwar', 'penggunaan' => 'MASJID',
            'tindakan_aset' => 'ASET_NU', 'hp_wa' => '081234567890',
            'nomor_pengajuan' => 'REG/20260523/00001'
        ]);

        $response = $this->actingAs($admin, 'lwp')->postJson(route('lwp.pengajuan.verify-lapangan', ['id' => $pengajuan->id]), [
            'tanggal_verifikasi' => '2026-05-23'
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('wk_pengajuan', [
            'id' => $pengajuan->id,
            'status' => 'VERIFIKASI_LAPANGAN',
            'tanggal_verifikasi' => '2026-05-23 00:00:00',
            'processed_by' => $admin->ID_USER
        ]);
    }

    public function test_can_complete_process_and_export_to_siwaknu()
    {
        $admin = LwpUser::create([
            'NAMA_USER' => 'Admin SIWAKNU',
            'USERNAME_USER' => 'admin',
            'PASSWORD_USER' => sha1('password'),
            'EMAIL_USER' => 'admin@siwaknu.id',
            'LEVEL' => 9,
            'KODE_LOKASI' => '35'
        ]);

        $pengajuan = WkPengajuan::create([
            'provinsi_id' => '35', 'provinsi' => 'Jawa Timur',
            'kabupaten_id' => '35.15', 'kabupaten' => 'Sidoarjo',
            'kecamatan_id' => '35.15.04', 'kecamatan' => 'Gedangan',
            'kelurahan_id' => '35.15.04.2001', 'kelurahan' => 'Keboansikep',
            'alamat' => 'Jl. Masjid No. 12', 'nama_aset' => 'Masjid Al-Falah',
            'luas' => 250.00, 'nama_wakif' => 'H. Anwar', 'penggunaan' => 'MASJID',
            'tindakan_aset' => 'ASET_NU', 'hp_wa' => '081234567890',
            'nomor_pengajuan' => 'REG/20260523/00001'
        ]);

        $response = $this->actingAs($admin, 'lwp')->postJson(route('lwp.pengajuan.complete', ['id' => $pengajuan->id]), [
            'nomor_ikrar' => 'AIW-12345',
            'tanggal_ikrar' => '2026-05-20',
            'nomor_sertifikat' => 'SERT-67890',
            'tanggal_sertifikat' => '2026-05-22',
            'atas_nama_sertifikat' => 'NAHDLATUL ULAMA',
            'jenis_sertifikat' => 'WAKAF'
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('wk_pengajuan', [
            'id' => $pengajuan->id,
            'status' => 'SELESAI',
            'nomor_ikrar' => 'AIW-12345',
            'nomor_sertifikat' => 'SERT-67890'
        ]);

        $this->assertDatabaseHas('wk_wakaf', [
            'WAKIF' => 'H. Anwar',
            'NO_AIW' => 'AIW-12345',
            'NO_SERT' => 'SERT-67890'
        ]);
    }
}
