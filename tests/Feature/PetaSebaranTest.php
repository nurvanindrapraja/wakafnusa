<?php

namespace Tests\Feature;

use App\Models\LwpUser;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class PetaSebaranTest extends TestCase
{
    public function test_guest_cannot_access_peta_sebaran()
    {
        $response = $this->get(route('lwp.peta-sebaran'));
        $response->assertRedirect(route('lwp.login'));
    }

    public function test_level_1_user_cannot_access_peta_sebaran()
    {
        $user = LwpUser::where('LEVEL', 1)->where('ISDEL', 0)->first();
        if (!$user) {
            $user = LwpUser::create([
                'NAMA_USER' => 'Test Level One',
                'USERNAME_USER' => 'testlevel1',
                'EMAIL_USER' => 'testlevel1@example.com',
                'PASSWORD_USER' => sha1('password'),
                'LEVEL' => 1,
                'ISDEL' => 0,
            ]);
        }

        $response = $this->actingAs($user, 'lwp')->get(route('lwp.peta-sebaran'));
        $response->assertStatus(403);

        $responseJson = $this->actingAs($user, 'lwp')->get(route('lwp.peta-sebaran.data'));
        $responseJson->assertStatus(403);
    }

    public function test_admin_app_can_access_peta_sebaran()
    {
        $admin = LwpUser::where('LEVEL', 9)->where('ISDEL', 0)->first();
        $this->assertNotNull($admin, 'Admin App user not found');

        // Test HTML view
        $response = $this->actingAs($admin, 'lwp')->get(route('lwp.peta-sebaran'));
        $response->assertStatus(200);
        $response->assertSee('Peta Sebaran Wakaf');
        $response->assertSee('sebaran-map');

        // Test API data - Provinsi level
        $responseJson = $this->actingAs($admin, 'lwp')->get(route('lwp.peta-sebaran.data', ['level' => 'provinsi']));
        $responseJson->assertStatus(200);
        $responseJson->assertJsonStructure([
            '*' => [
                'code',
                'name',
                'lat',
                'lng',
                'total_wakaf',
                'total_luas'
            ]
        ]);

        // Test API data - Kabupaten level (Jawa Timur = 35)
        $responseJson2 = $this->actingAs($admin, 'lwp')->get(route('lwp.peta-sebaran.data', [
            'level' => 'kabupaten',
            'parent_code' => '35'
        ]));
        $responseJson2->assertStatus(200);
        $responseJson2->assertJsonStructure([
            '*' => [
                'code',
                'name',
                'lat',
                'lng',
                'total_wakaf',
                'total_luas'
            ]
        ]);
    }
}
