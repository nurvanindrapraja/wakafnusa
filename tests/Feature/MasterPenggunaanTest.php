<?php

namespace Tests\Feature;

use App\Models\LwpUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class MasterPenggunaanTest extends TestCase
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

        // 2. Create wk_penggunaan table in-memory
        Schema::create('wk_penggunaan', function ($table) {
            $table->string('KODE_PENG', 8);
            $table->string('PENGGUNAAN', 40);
            $table->string('KODE_PENG_OLD', 3)->nullable();
            $table->string('ICON', 25)->nullable();
            $table->integer('ISDEL')->default(0);
        });

        // 3. Create wk_wakaf table in-memory
        Schema::create('wk_wakaf', function ($table) {
            $table->increments('ID_WAKAF');
            $table->string('KODE_PENG');
            $table->integer('ISDEL')->default(0);
        });

        // Seed initial data
        DB::table('wk_penggunaan')->insert([
            ['KODE_PENG' => '11', 'PENGGUNAAN' => 'TEMPAT IBADAH', 'KODE_PENG_OLD' => null, 'ICON' => 'mosque.png', 'ISDEL' => 0],
            ['KODE_PENG' => '11.01', 'PENGGUNAAN' => 'MASJID', 'KODE_PENG_OLD' => '1', 'ICON' => 'mosque.png', 'ISDEL' => 0],
        ]);
    }

    public function test_guest_cannot_access_master_penggunaan()
    {
        $response = $this->get(route('lwp.penggunaan.index'));
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

        $response = $this->actingAs($moderator, 'lwp')->get(route('lwp.penggunaan.index'));
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

        $response = $this->actingAs($admin, 'lwp')->get(route('lwp.penggunaan.index'));
        $response->assertStatus(200);
        $response->assertSee('Master Penggunaan');
        $response->assertSee('TEMPAT IBADAH');
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

        $response = $this->actingAs($pbnu, 'lwp')->get(route('lwp.penggunaan.index'));
        $response->assertStatus(200);
        $response->assertSee('Master Penggunaan');
    }

    public function test_crud_lifecycle_for_penggunaan()
    {
        $admin = LwpUser::create([
            'NAMA_USER' => 'Admin App',
            'USERNAME_USER' => 'admin_app_crud',
            'EMAIL_USER' => 'admincrud@example.com',
            'PASSWORD_USER' => sha1('secret'),
            'LEVEL' => 9,
            'ISDEL' => 0,
        ]);

        // 1. Create (Store)
        $storeResponse = $this->actingAs($admin, 'lwp')->postJson(route('lwp.penggunaan.store'), [
            'kode_peng' => '11.02',
            'penggunaan' => 'MUSHOLLA',
            'kode_peng_old' => '11',
            'icon' => 'mosque.png',
        ]);

        $storeResponse->assertStatus(200);
        $storeResponse->assertJson(['status' => 'success']);

        $this->assertDatabaseHas('wk_penggunaan', [
            'KODE_PENG' => '11.02',
            'PENGGUNAAN' => 'MUSHOLLA',
            'ISDEL' => 0,
        ]);

        // 2. Duplicate Validation
        $duplicateResponse = $this->actingAs($admin, 'lwp')->postJson(route('lwp.penggunaan.store'), [
            'kode_peng' => '11.02',
            'penggunaan' => 'DUPLIKAT',
        ]);
        $duplicateResponse->assertStatus(422);

        // 3. Show
        $showResponse = $this->actingAs($admin, 'lwp')->getJson(route('lwp.penggunaan.show', '11.02'));
        $showResponse->assertStatus(200);
        $showResponse->assertJson([
            'status' => 'success',
            'data' => [
                'KODE_PENG' => '11.02',
                'PENGGUNAAN' => 'MUSHOLLA',
            ]
        ]);

        // 4. Update
        $updateResponse = $this->actingAs($admin, 'lwp')->postJson(route('lwp.penggunaan.update', '11.02'), [
            'kode_peng' => '11.02',
            'penggunaan' => 'MUSHOLLA / LANGGAR',
            'kode_peng_old' => '11',
            'icon' => 'school.png',
        ]);
        $updateResponse->assertStatus(200);

        $this->assertDatabaseHas('wk_penggunaan', [
            'KODE_PENG' => '11.02',
            'PENGGUNAAN' => 'MUSHOLLA / LANGGAR',
            'ICON' => 'school.png',
        ]);

        // 5. Soft Delete
        $deleteResponse = $this->actingAs($admin, 'lwp')->deleteJson(route('lwp.penggunaan.destroy', '11.02'));
        $deleteResponse->assertStatus(200);

        $this->assertDatabaseHas('wk_penggunaan', [
            'KODE_PENG' => '11.02',
            'ISDEL' => 1,
        ]);

        // 6. Restore
        $restoreResponse = $this->actingAs($admin, 'lwp')->postJson(route('lwp.penggunaan.restore', '11.02'));
        $restoreResponse->assertStatus(200);

        $this->assertDatabaseHas('wk_penggunaan', [
            'KODE_PENG' => '11.02',
            'ISDEL' => 0,
        ]);

        // 7. Force Delete
        $forceDeleteResponse = $this->actingAs($admin, 'lwp')->deleteJson(route('lwp.penggunaan.destroy', ['kode_peng' => '11.02', 'force' => 1]));
        $forceDeleteResponse->assertStatus(200);

        $this->assertDatabaseMissing('wk_penggunaan', [
            'KODE_PENG' => '11.02',
        ]);
    }
}
