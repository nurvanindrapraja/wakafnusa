<?php

namespace Tests\Feature;

use App\Models\LwpUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Tests\TestCase;

class ImportWakafTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Share the SQLite in-memory connection with mysql_no_prefix
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

        // 2. Create wk_lokasi table in-memory
        Schema::create('wk_lokasi', function ($table) {
            $table->string('kode_lokasi', 13)->primary();
            $table->string('nama_lokasi', 100);
        });

        // 3. Create wk_penggunaan table in-memory
        Schema::create('wk_penggunaan', function ($table) {
            $table->string('KODE_PENG', 8);
            $table->string('PENGGUNAAN', 40);
            $table->string('KODE_PENG_OLD', 3)->nullable();
            $table->string('ICON', 25)->nullable();
            $table->integer('ISDEL')->default(0);
        });

        // 4. Create wk_cabang table in-memory
        Schema::create('wk_cabang', function ($table) {
            $table->increments('ID_CABANG');
            $table->string('KODE_CABANG', 10);
            $table->string('NAMA_CABANG', 50);
            $table->string('KODE_INDEX', 10);
            $table->string('NAMA_INDEX', 50)->nullable();
        });

        // 5. Create wk_wakaf table in-memory
        Schema::create('wk_wakaf', function ($table) {
            $table->increments('ID_WAKAF');
            $table->string('KODE_LOKASI')->nullable();
            $table->string('KODE_PENG')->nullable();
            $table->string('NAMA')->nullable();
            $table->string('ALAMAT')->nullable();
            $table->string('WAKIF')->nullable();
            $table->string('NADZIR')->nullable();
            $table->float('LUAS')->nullable();
            $table->string('NO_AIW')->nullable();
            $table->date('TGL_AIW')->nullable();
            $table->string('NO_SERT')->nullable();
            $table->date('TGL_SERT')->nullable();
            $table->string('KET_PENG')->nullable();
            $table->string('KETERANGAN')->nullable();
            $table->integer('ID_ST')->default(0);
            $table->integer('ID_SP')->default(0);
            $table->integer('ID_SW')->default(1);
            $table->integer('ISDEL')->default(0);
            $table->string('LAT')->nullable();
            $table->string('LNG')->nullable();
            $table->integer('ID_USER')->nullable();
            $table->integer('ISOK')->default(1);
            $table->dateTime('TGINS')->nullable();
            $table->integer('ID_AIW')->default(0);
            $table->integer('ID_PS')->default(0);
            $table->string('NO_BERKAS')->nullable();
            $table->string('TOKEN')->nullable();
            $table->string('NIA')->nullable();
            $table->integer('ID_KRITERIA')->default(1);
            $table->string('KODE_INDEX')->nullable();
            $table->integer('NJOP')->nullable();
            $table->integer('THN_NJOP')->default(0);
        });

        // 6. Create wk_hisentri table in-memory
        Schema::create('wk_hisentri', function ($table) {
            $table->increments('id_hisentri');
            $table->integer('id_wakaf');
            $table->integer('id_user');
            $table->dateTime('tgl_entri');
            $table->integer('fase')->default(1);
        });

        // Seed references
        DB::table('wk_lokasi')->insert([
            ['kode_lokasi' => '35', 'nama_lokasi' => 'JAWA TIMUR'],
            ['kode_lokasi' => '35.22', 'nama_lokasi' => 'KAB. BOJONEGORO'],
            ['kode_lokasi' => '35.22.15', 'nama_lokasi' => 'BOJONEGORO'],
            ['kode_lokasi' => '35.22.15.1011', 'nama_lokasi' => 'KADIPATEN'],
            ['kode_lokasi' => '35.23', 'nama_lokasi' => 'KAB. TUBAN'],
            ['kode_lokasi' => '35.23.01', 'nama_lokasi' => 'TUBAN'],
            ['kode_lokasi' => '35.23.01.1001', 'nama_lokasi' => 'KUTOREJO'],
        ]);

        DB::table('wk_penggunaan')->insert([
            ['KODE_PENG' => '11.01', 'PENGGUNAAN' => 'MASJID', 'ISDEL' => 0],
            ['KODE_PENG' => '11.02', 'PENGGUNAAN' => 'MUSHOLLA', 'ISDEL' => 0],
        ]);

        DB::table('wk_cabang')->insert([
            ['KODE_CABANG' => '35.22', 'NAMA_CABANG' => 'KAB. BOJONEGORO', 'KODE_INDEX' => '16.14'],
        ]);

        // Existing wakaf to set baseline NIA
        DB::table('wk_wakaf')->insert([
            'NIA' => '1614000100',
            'KODE_LOKASI' => '35.22.15.1011',
            'KODE_PENG' => '11.01',
            'ISDEL' => 0,
        ]);
    }

    public function test_guest_cannot_access_import_wakaf()
    {
        $response = $this->get(route('lwp.import-wakaf.index'));
        $response->assertRedirect(route('lwp.login'));
    }

    public function test_level_1_and_level_2_cannot_access_import_wakaf()
    {
        $rantingUser = LwpUser::create([
            'NAMA_USER' => 'Test Ranting',
            'USERNAME_USER' => 'test_ranting',
            'EMAIL_USER' => 'ranting@example.com',
            'PASSWORD_USER' => sha1('secret'),
            'LEVEL' => 1,
            'ISDEL' => 0,
        ]);

        $mwcUser = LwpUser::create([
            'NAMA_USER' => 'Test MWCNU',
            'USERNAME_USER' => 'test_mwc',
            'EMAIL_USER' => 'mwc@example.com',
            'PASSWORD_USER' => sha1('secret'),
            'LEVEL' => 2,
            'ISDEL' => 0,
        ]);

        $this->actingAs($rantingUser, 'lwp')->get(route('lwp.import-wakaf.index'))->assertStatus(403);
        $this->actingAs($mwcUser, 'lwp')->get(route('lwp.import-wakaf.index'))->assertStatus(403);
    }

    public function test_admin_app_level_9_can_access()
    {
        $admin = LwpUser::create([
            'NAMA_USER' => 'Admin App',
            'USERNAME_USER' => 'admin_app',
            'EMAIL_USER' => 'adminapp@example.com',
            'PASSWORD_USER' => sha1('secret'),
            'LEVEL' => 9,
            'ISDEL' => 0,
        ]);

        $response = $this->actingAs($admin, 'lwp')->get(route('lwp.import-wakaf.index'));
        $response->assertStatus(200);
        $response->assertSee('Import Data Wakaf');
        $response->assertSee('Unggah Berkas Spreadsheet');
    }

    public function test_admin_pbnu_level_91_can_access()
    {
        $pbnu = LwpUser::create([
            'NAMA_USER' => 'Admin PBNU',
            'USERNAME_USER' => 'admin_pbnu',
            'EMAIL_USER' => 'adminpbnu@example.com',
            'PASSWORD_USER' => sha1('secret'),
            'LEVEL' => 91,
            'ISDEL' => 0,
        ]);

        $response = $this->actingAs($pbnu, 'lwp')->get(route('lwp.import-wakaf.index'));
        $response->assertStatus(200);
        $response->assertSee('Import Data Wakaf');
    }

    public function test_admin_pwnu_level_31_can_access()
    {
        $pwnu = LwpUser::create([
            'NAMA_USER' => 'Admin PWNU Jatim',
            'USERNAME_USER' => 'admin_pwnu',
            'EMAIL_USER' => 'pwnu@example.com',
            'PASSWORD_USER' => sha1('secret'),
            'LEVEL' => 31,
            'KODE_LOKASI' => '35',
            'ISDEL' => 0,
        ]);

        $response = $this->actingAs($pwnu, 'lwp')->get(route('lwp.import-wakaf.index'));
        $response->assertStatus(200);
        $response->assertSee('Import Data Wakaf');
    }

    public function test_admin_pcnu_level_3_can_access()
    {
        $pcnu = LwpUser::create([
            'NAMA_USER' => 'Admin PCNU Bojonegoro',
            'USERNAME_USER' => 'admin_pcnu',
            'EMAIL_USER' => 'pcnu@example.com',
            'PASSWORD_USER' => sha1('secret'),
            'LEVEL' => 3,
            'KODE_LOKASI' => '35.22',
            'ISDEL' => 0,
        ]);

        $response = $this->actingAs($pcnu, 'lwp')->get(route('lwp.import-wakaf.index'));
        $response->assertStatus(200);
        $response->assertSee('Import Data Wakaf');
    }

    public function test_download_template_returns_excel()
    {
        $admin = LwpUser::create([
            'NAMA_USER' => 'Admin App',
            'USERNAME_USER' => 'admin_app_tpl',
            'EMAIL_USER' => 'admintpl@example.com',
            'PASSWORD_USER' => sha1('secret'),
            'LEVEL' => 9,
            'ISDEL' => 0,
        ]);

        $response = $this->actingAs($admin, 'lwp')->get(route('lwp.import-wakaf.download-template'));
        $response->assertStatus(200);
        $this->assertStringContainsString('spreadsheetml.sheet', $response->headers->get('content-type'));

        // Save content to temp file and inspect headers
        $tmp = tempnam(sys_get_temp_dir(), 'tpl_') . '.xlsx';
        file_put_contents($tmp, $response->streamedContent());
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($tmp);
        $sheet = $spreadsheet->getActiveSheet();
        $headers = $sheet->rangeToArray('A1:P1')[0];
        unlink($tmp);

        $expected = [
            'kel/desa (kelurahan)', 'kec (kecamatan)', 'kota/kab', 'provinsi', 'penggunaan',
            'nama', 'alamat', 'wakif', 'nazhir', 'luas',
            'no aiw', 'tgl aiw', 'no sert/nib', 'tgl sertipikat', 'keterangan', 'njop'
        ];
        $this->assertEquals($expected, $headers);
    }

    public function test_full_import_lifecycle()
    {
        $admin = LwpUser::create([
            'NAMA_USER' => 'Admin App',
            'USERNAME_USER' => 'admin_app_crud',
            'EMAIL_USER' => 'admincrud@example.com',
            'PASSWORD_USER' => sha1('secret'),
            'LEVEL' => 9,
            'ISDEL' => 0,
        ]);

        // Create a temporary Excel file
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $headers = [
            'kel/desa (kelurahan)', 'kec (kecamatan)', 'kota/kab', 'provinsi', 'penggunaan',
            'nama', 'alamat', 'wakif', 'nazhir', 'luas',
            'no aiw', 'tgl aiw', 'no sert/nib', 'tgl sertipikat', 'keterangan', 'njop'
        ];
        $sheet->fromArray([$headers], null, 'A1');
        $sheet->fromArray([[
            'KADIPATEN', 'BOJONEGORO', 'KAB. BOJONEGORO', 'JAWA TIMUR', 'MASJID',
            'Masjid Al-Barokah', 'Jl. Veteran No. 1', 'H. Syafi\'i', 'MWC NU', 1200,
            'AIW-01', '2021-05-10', 'SERT-01', '2021-08-15', 'Untuk tempat ibadah', 250000000
        ]], null, 'A2');

        $tempPath = tempnam(sys_get_temp_dir(), 'test_import_') . '.xlsx';
        $writer = new Xlsx($spreadsheet);
        $writer->save($tempPath);

        $uploadedFile = new UploadedFile($tempPath, 'test_import.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', null, true);

        // 1. Upload File
        $uploadResponse = $this->actingAs($admin, 'lwp')->postJson(route('lwp.import-wakaf.upload'), [
            'file' => $uploadedFile,
        ]);
        $uploadResponse->assertStatus(200);
        $uploadResponse->assertJson(['status' => 'success']);

        $this->assertTrue(session()->has('import_wakaf_session'));
        $sessionData = session('import_wakaf_session');
        $this->assertCount(1, $sessionData['rows']);

        // 2. Generate NIA, Kode Lokasi, and Kode Penggunaan
        $generateResponse = $this->actingAs($admin, 'lwp')->postJson(route('lwp.import-wakaf.generate'), [
            'starting_nia' => '1614000101',
        ]);
        $generateResponse->assertStatus(200);
        $generateResponse->assertJson([
            'status' => 'success',
            'stats' => [
                'total' => 1,
                'ready' => 1,
                'incomplete' => 0,
            ]
        ]);

        $updatedSession = session('import_wakaf_session');
        $row1 = $updatedSession['rows'][0];
        $this->assertEquals('1614000101', $row1['nia']);
        $this->assertEquals('35.22.15.1011', $row1['kode_lokasi']);
        $this->assertEquals('11.01', $row1['kode_peng']);
        $this->assertEquals('ready', $row1['status']);

        // 3. Process Import to wk_wakaf
        $processResponse = $this->actingAs($admin, 'lwp')->postJson(route('lwp.import-wakaf.process'));
        $processResponse->assertStatus(200);
        $processResponse->assertJson([
            'status' => 'success',
            'imported_count' => 1,
        ]);

        // Verify database has new record
        $this->assertDatabaseHas('wk_wakaf', [
            'NIA' => '1614000101',
            'KODE_LOKASI' => '35.22.15.1011',
            'KODE_PENG' => '11.01',
            'NAMA' => 'Masjid Al-Barokah',
            'KODE_INDEX' => '16.14',
        ]);

        // Verify hisentri record
        $this->assertDatabaseHas('wk_hisentri', [
            'fase' => 1,
        ]);

        // Verify session was cleared
        $this->assertFalse(session()->has('import_wakaf_session'));

        // Clean up temp file
        if (file_exists($tempPath)) {
            unlink($tempPath);
        }
    }

    public function test_upload_with_short_header_names(): void
    {
        $admin = LwpUser::create([
            'NAMA_USER' => 'Admin PBNU Short',
            'USERNAME_USER' => 'adminshort',
            'EMAIL_USER' => 'adminpbnu_short@example.com',
            'PASSWORD_USER' => sha1('secret'),
            'LEVEL' => 91,
            'ISDEL' => 0,
        ]);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $headers = [
            'kel/desa', 'kec', 'kota/kab', 'provinsi', 'penggunaan',
            'nama', 'alamat', 'wakif', 'nazhir', 'luas',
            'no aiw', 'tgl aiw', 'no sert/nib', 'tgl sertipikat', 'keterangan', 'njop'
        ];
        $sheet->fromArray([$headers], null, 'A1');
        $sheet->fromArray([[
            'SUKOREJO', 'BOJONEGORO', 'KAB. BOJONEGORO', 'JAWA TIMUR', 'MUSHOLLA',
            'Musholla Al-Ikhlas', 'Jl. Basuki Rahmat', 'H. Nurvan', 'MWC NU', 500,
            'AIW-02', '2022-01-10', 'SERT-02', '2022-04-15', 'Tempat ibadah', 100000000
        ]], null, 'A2');

        $tempPath = tempnam(sys_get_temp_dir(), 'test_short_') . '.xlsx';
        $writer = new Xlsx($spreadsheet);
        $writer->save($tempPath);

        $uploadedFile = new UploadedFile($tempPath, 'test_short.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', null, true);

        $response = $this->actingAs($admin, 'lwp')->postJson(route('lwp.import-wakaf.upload'), [
            'file' => $uploadedFile,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'success',
        ]);

        $sessionData = session('import_wakaf_session');
        $this->assertEquals('SUKOREJO', $sessionData['rows'][0]['desa']);
        $this->assertEquals('BOJONEGORO', $sessionData['rows'][0]['kecamatan']);
        $this->assertEquals('H. Nurvan', $sessionData['rows'][0]['wakif']);

        if (file_exists($tempPath)) {
            unlink($tempPath);
        }
    }

    public function test_pcnu_can_import_within_assigned_kabupaten(): void
    {
        $pcnu = LwpUser::create([
            'NAMA_USER' => 'Admin PCNU Bojonegoro',
            'USERNAME_USER' => 'pcnu_bojonegoro',
            'EMAIL_USER' => 'pcnubjn@example.com',
            'PASSWORD_USER' => sha1('secret'),
            'LEVEL' => 3,
            'KODE_LOKASI' => '35.22',
            'ISDEL' => 0,
        ]);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $headers = [
            'kel/desa', 'kec', 'kota/kab', 'provinsi', 'penggunaan',
            'nama', 'alamat', 'wakif', 'nazhir', 'luas',
            'no aiw', 'tgl aiw', 'no sert/nib', 'tgl sertipikat', 'keterangan', 'njop'
        ];
        $sheet->fromArray([$headers], null, 'A1');
        $sheet->fromArray([[
            'KADIPATEN', 'BOJONEGORO', 'KAB. BOJONEGORO', 'JAWA TIMUR', 'MASJID',
            'Masjid PCNU Bjn', 'Jl. Panglima Polim', 'H. PCNU', 'MWC NU', 800,
            'AIW-PCNU-01', '2023-01-01', 'SERT-PCNU-01', '2023-02-01', 'Wakaf PCNU', 200000000
        ]], null, 'A2');

        $tempPath = tempnam(sys_get_temp_dir(), 'test_pcnu_') . '.xlsx';
        $writer = new Xlsx($spreadsheet);
        $writer->save($tempPath);

        $uploadedFile = new UploadedFile($tempPath, 'test_pcnu.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', null, true);

        // Upload
        $this->actingAs($pcnu, 'lwp')->postJson(route('lwp.import-wakaf.upload'), [
            'file' => $uploadedFile,
        ])->assertStatus(200);

        // Generate
        $genResponse = $this->actingAs($pcnu, 'lwp')->postJson(route('lwp.import-wakaf.generate'), [
            'starting_nia' => '1614000500',
        ]);
        $genResponse->assertStatus(200);
        $genResponse->assertJson([
            'status' => 'success',
            'stats' => [
                'ready' => 1,
                'incomplete' => 0,
            ]
        ]);

        // Process Import
        $procResponse = $this->actingAs($pcnu, 'lwp')->postJson(route('lwp.import-wakaf.process'));
        $procResponse->assertStatus(200);
        $procResponse->assertJson([
            'status' => 'success',
            'imported_count' => 1,
        ]);

        $this->assertDatabaseHas('wk_wakaf', [
            'NIA' => '1614000500',
            'KODE_LOKASI' => '35.22.15.1011',
            'NAMA' => 'Masjid PCNU Bjn',
        ]);

        if (file_exists($tempPath)) {
            unlink($tempPath);
        }
    }

    public function test_pcnu_cannot_import_data_outside_assigned_kabupaten(): void
    {
        $pcnuBjn = LwpUser::create([
            'NAMA_USER' => 'Admin PCNU Bojonegoro 2',
            'USERNAME_USER' => 'pcnu_bojonegoro_2',
            'EMAIL_USER' => 'pcnubjn2@example.com',
            'PASSWORD_USER' => sha1('secret'),
            'LEVEL' => 3,
            'KODE_LOKASI' => '35.22', // Bojonegoro
            'ISDEL' => 0,
        ]);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $headers = [
            'kel/desa', 'kec', 'kota/kab', 'provinsi', 'penggunaan',
            'nama', 'alamat', 'wakif', 'nazhir', 'luas',
            'no aiw', 'tgl aiw', 'no sert/nib', 'tgl sertipikat', 'keterangan', 'njop'
        ];
        $sheet->fromArray([$headers], null, 'A1');
        // KUTOREJO, TUBAN, KAB. TUBAN is outside 35.22
        $sheet->fromArray([[
            'KUTOREJO', 'TUBAN', 'KAB. TUBAN', 'JAWA TIMUR', 'MASJID',
            'Masjid Luar Wilayah', 'Jl. Tuban', 'H. Luar', 'MWC', 500,
            'AIW-OUT-01', '2023-01-01', 'SERT-OUT-01', '2023-02-01', 'Wakaf Luar', 100000000
        ]], null, 'A2');

        $tempPath = tempnam(sys_get_temp_dir(), 'test_out_') . '.xlsx';
        $writer = new Xlsx($spreadsheet);
        $writer->save($tempPath);

        $uploadedFile = new UploadedFile($tempPath, 'test_out.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', null, true);

        // Upload
        $this->actingAs($pcnuBjn, 'lwp')->postJson(route('lwp.import-wakaf.upload'), [
            'file' => $uploadedFile,
        ])->assertStatus(200);

        // Generate
        $genResponse = $this->actingAs($pcnuBjn, 'lwp')->postJson(route('lwp.import-wakaf.generate'), [
            'starting_nia' => '1614000999',
        ]);
        $genResponse->assertStatus(200);
        $genResponse->assertJson([
            'status' => 'success',
            'stats' => [
                'ready' => 0,
                'incomplete' => 1,
            ]
        ]);

        $sessionData = session('import_wakaf_session');
        $this->assertEquals('incomplete', $sessionData['rows'][0]['status']);
        $this->assertStringContainsString('di luar wilayah PCNU Anda', $sessionData['rows'][0]['errors'][0]);

        // Attempting to process should fail because 0 ready rows
        $procResponse = $this->actingAs($pcnuBjn, 'lwp')->postJson(route('lwp.import-wakaf.process'));
        $procResponse->assertStatus(422);

        if (file_exists($tempPath)) {
            unlink($tempPath);
        }
    }
}
