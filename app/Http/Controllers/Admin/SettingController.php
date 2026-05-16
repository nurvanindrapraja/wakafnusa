<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings = SystemSetting::all()->pluck('value', 'key');
        return view('dashboard.admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'chairman_name' => 'required|string|max:255',
            'chairman_title' => 'required|string|max:255',
            'chairman_signature' => 'nullable|image|mimes:png,jpg,jpeg|max:2048', // Increased to 2MB to avoid 'uploaded' error for slightly larger files
        ], [
            'chairman_signature.uploaded' => 'Gagal mengunggah gambar. Pastikan ukuran file tidak melebihi batas server (php.ini) dan format file benar.',
        ]);

        try {
            SystemSetting::updateOrCreate(['key' => 'chairman_name'], ['value' => $request->chairman_name]);
            SystemSetting::updateOrCreate(['key' => 'chairman_title'], ['value' => $request->chairman_title]);

            if ($request->hasFile('chairman_signature')) {
                $file = $request->file('chairman_signature');
                
                if (!$file->isValid()) {
                    return redirect()->back()->with('error', 'File tidak valid atau rusak.');
                }

                // Delete old signature
                $oldSignature = SystemSetting::where('key', 'chairman_signature')->first();
                if ($oldSignature && $oldSignature->value) {
                    Storage::disk('public')->delete($oldSignature->value);
                }

                $path = $file->store('settings', 'public');
                SystemSetting::updateOrCreate(['key' => 'chairman_signature'], ['value' => $path]);
            }

            return redirect()->back()->with('success', 'Pengaturan berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }
}
