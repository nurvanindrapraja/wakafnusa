<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SystemSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            'nazhir_name' => 'LWPNU',
            'chairman_name' => 'Drs. H. Ahmad Muzakki, M.Ag',
            'chairman_title' => 'Ketua LWP PBNU',
            'chairman_signature' => null, // Path to signature image
        ];

        foreach ($settings as $key => $value) {
            \App\Models\SystemSetting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
