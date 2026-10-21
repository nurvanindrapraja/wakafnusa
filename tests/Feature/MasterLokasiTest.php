<?php

namespace Tests\Feature;

use App\Models\Location;
use App\Models\LwpUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class MasterLokasiTest extends TestCase
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

        // 3. Create wk_wakaf table in-memory
        Schema::create('wk_wakaf', function ($table) {
            $table->increments('ID_WAKAF');
            $table->string('KODE_LOKASI');
            $table->integer('ISDEL')->default(0);
        });

        // Seed initial hierarchy
        DB::table('wk_lokasi')->insert([
            ['kode_lokasi' => '35', 'nama_lokasi' => 'JAWA TIMUR'],
            ['kode_lokasi' => '35.22', 'nama_lokasi' => 'KAB. BOJONEGORO'],
            ['kode_lokasi' => '35.22.01', 'nama_lokasi' => 'BOJONEGORO'],
            ['kode_lokasi' => '35.22.01.2001', 'nama_lokasi' => 'KADIPATEN'],
        ]);
    }

    public function test_guest_cannot_access_master_lokasi()
    {
        $response = $this->get(route('lwp.lokasi.index'));
        $response->assertRedirect(route('lwp.login'));
    }

    public function test_moderator_pwnu_pcnu_cannot_access()
    {
        $moderator = LwpUser::create([
            'NAMA_USER' => 'Test Mod PWNU',
            'USERNAME_USER' => 'testmod_pwnu',
            'EMAIL_USER' => 'testmod@example.com',
            'PASSWORD_USER' => sha1('secret'),
            'LEVEL' => 31,
            'ISDEL' => 0,
        ]);

        $response = $this->actingAs($moderator, 'lwp')->get(route('lwp.lokasi.index'));
        $response->assertStatus(403);
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

        $response = $this->actingAs($admin, 'lwp')->get(route('lwp.lokasi.index'));
        $response->assertStatus(200);
        $response->assertSee('Master Lokasi');
        $response->assertSee('JAWA TIMUR');
        $response->assertSee('KAB. BOJONEGORO');
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

        $response = $this->actingAs($pbnu, 'lwp')->get(route('lwp.lokasi.index'));
        $response->assertStatus(200);
        $response->assertSee('Master Lokasi');
    }

    public function test_crud_lifecycle_for_lokasi()
    {
        $admin = LwpUser::create([
            'NAMA_USER' => 'Admin App',
            'USERNAME_USER' => 'admin_app_crud',
            'EMAIL_USER' => 'admincrud@example.com',
            'PASSWORD_USER' => sha1('secret'),
            'LEVEL' => 9,
            'ISDEL' => 0,
        ]);

        // 1. Suggest code
        $suggestResponse = $this->actingAs($admin, 'lwp')->getJson(route('lwp.lokasi.suggest-code', [
            'level' => 'kelurahan',
            'parent' => '35.22.01'
        ]));
        $suggestResponse->assertStatus(200);
        $suggestResponse->assertJson(['status' => 'success']);

        // 2. Create (Store) new Kelurahan
        $storeResponse = $this->actingAs($admin, 'lwp')->postJson(route('lwp.lokasi.store'), [
            'level' => 'kelurahan',
            'parent_kode' => '35.22.01',
            'kode_suffix' => '2002',
            'nama_lokasi' => 'SUKOREJO',
        ]);
        $storeResponse->assertStatus(200);
        $storeResponse->assertJson(['status' => 'success']);

        $this->assertDatabaseHas('wk_lokasi', [
            'kode_lokasi' => '35.22.01.2002',
            'nama_lokasi' => 'SUKOREJO',
        ]);

        // 3. Duplicate Validation
        $duplicateResponse = $this->actingAs($admin, 'lwp')->postJson(route('lwp.lokasi.store'), [
            'level' => 'kelurahan',
            'parent_kode' => '35.22.01',
            'kode_suffix' => '2002',
            'nama_lokasi' => 'SUKOREJO DUPLIKAT',
        ]);
        $duplicateResponse->assertStatus(422);

        // 4. Show Detail
        $showResponse = $this->actingAs($admin, 'lwp')->getJson(route('lwp.lokasi.show', '35.22.01.2002'));
        $showResponse->assertStatus(200);
        $showResponse->assertJson([
            'status' => 'success',
            'data' => [
                'kode_lokasi' => '35.22.01.2002',
                'nama_lokasi' => 'SUKOREJO',
                'level' => 'kelurahan',
            ]
        ]);

        // 5. Update
        $updateResponse = $this->actingAs($admin, 'lwp')->postJson(route('lwp.lokasi.update', '35.22.01.2002'), [
            'nama_lokasi' => 'SUKOREJO TIMUR',
            'new_kode_lokasi' => '35.22.01.2002',
        ]);
        $updateResponse->assertStatus(200);

        $this->assertDatabaseHas('wk_lokasi', [
            'kode_lokasi' => '35.22.01.2002',
            'nama_lokasi' => 'SUKOREJO TIMUR',
        ]);

        // 6. Delete Safety Protection: Cannot delete parent with children
        $deleteParentResponse = $this->actingAs($admin, 'lwp')->deleteJson(route('lwp.lokasi.destroy', '35.22.01'));
        $deleteParentResponse->assertStatus(422);

        // 7. Delete Safety Protection: Cannot delete if linked to wakaf asset
        DB::table('wk_wakaf')->insert([
            'ID_WAKAF' => 999,
            'KODE_LOKASI' => '35.22.01.2002',
            'ISDEL' => 0,
        ]);
        $deleteWakafResponse = $this->actingAs($admin, 'lwp')->deleteJson(route('lwp.lokasi.destroy', '35.22.01.2002'));
        $deleteWakafResponse->assertStatus(422);

        // Remove the wakaf asset link
        DB::table('wk_wakaf')->where('ID_WAKAF', 999)->delete();

        // 8. Delete Safety Protection: Cannot delete if linked to user
        $userLinked = LwpUser::create([
            'NAMA_USER' => 'User Desa',
            'USERNAME_USER' => 'user_desa',
            'EMAIL_USER' => 'userdesa@example.com',
            'PASSWORD_USER' => sha1('secret'),
            'LEVEL' => 1,
            'KODE_LOKASI' => '35.22.01.2002',
            'ISDEL' => 0,
        ]);
        $deleteUserResponse = $this->actingAs($admin, 'lwp')->deleteJson(route('lwp.lokasi.destroy', '35.22.01.2002'));
        $deleteUserResponse->assertStatus(422);

        // Remove the user link
        $userLinked->delete();

        // 9. Successful Delete when safe
        $deleteSuccessResponse = $this->actingAs($admin, 'lwp')->deleteJson(route('lwp.lokasi.destroy', '35.22.01.2002'));
        $deleteSuccessResponse->assertStatus(200);

        $this->assertDatabaseMissing('wk_lokasi', [
            'kode_lokasi' => '35.22.01.2002',
        ]);
    }
}
