<?php

namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\Usage;
use App\Models\WkPengajuan;
use Illuminate\Http\Request;

class AssetRegistrationController extends Controller
{
    /**
     * Show the public step-by-step asset registration form.
     */
    public function showForm()
    {
        $provinces = Location::whereRaw('LENGTH(kode_lokasi) = 2')
            ->orderBy('nama_lokasi')
            ->get();

        $usages = Usage::orderBy('KODE_PENG')
            ->get();

        return view('public.land-waqf.register', compact('provinces', 'usages'));
    }

    /**
     * Store the submitted public asset registration.
     */
    public function store(Request $request)
    {
        $rules = [
            // Step 1: Location
            'provinsi_id' => ['required', 'string'],
            'provinsi' => ['required', 'string', 'max:255'],
            'kabupaten_id' => ['required', 'string'],
            'kabupaten' => ['required', 'string', 'max:255'],
            'kecamatan_id' => ['required', 'string'],
            'kecamatan' => ['required', 'string', 'max:255'],
            'kelurahan_id' => ['required', 'string'],
            'kelurahan' => ['required', 'string', 'max:255'],
            'alamat' => ['required', 'string'],

            // Step 2: Asset details
            'nama_aset' => ['nullable', 'string', 'max:255'],
            'luas' => ['required', 'numeric', 'min:0.01'],
            'nama_wakif' => ['required', 'string', 'max:255'],
            'penggunaan' => ['required', 'string', 'max:255'],

            // Step 3: Ikrar
            'sudah_ikrar' => ['required', 'boolean'],
            'nomor_ikrar' => ['required_if:sudah_ikrar,1', 'nullable', 'string', 'max:255'],
            'tanggal_ikrar' => ['required_if:sudah_ikrar,1', 'nullable', 'date'],
            'nama_nazhir' => ['required_if:sudah_ikrar,1', 'nullable', 'string', 'max:255'],

            // Step 4: Certificate
            'sudah_sertifikat' => ['required', 'boolean'],
            'nomor_sertifikat' => ['required_if:sudah_sertifikat,1', 'nullable', 'string', 'max:255'],
            'tanggal_sertifikat' => ['required_if:sudah_sertifikat,1', 'nullable', 'date'],
            'atas_nama_sertifikat' => ['required_if:sudah_sertifikat,1', 'nullable', 'string', 'max:255'],
            'jenis_sertifikat' => ['required_if:sudah_sertifikat,1', 'nullable', 'string', 'in:SHM,SHGB,WAKAF'],

            // Step 5: Action & Contact
            'tindakan_aset' => ['required', 'string', 'in:WAKAF_NU,ASET_NU,GANTI_NAZHIR_NU'],
            'keterangan' => ['nullable', 'string'],
            'hp_wa' => ['required', 'string', 'max:50'],
        ];

        // Custom validation messages in Indonesian
        $messages = [
            'required' => 'Kolom :attribute wajib diisi.',
            'numeric' => 'Kolom :attribute harus berupa angka.',
            'min' => 'Kolom :attribute harus bernilai minimal :min.',
            'in' => 'Pilihan :attribute tidak valid.',
            'date' => 'Format tanggal pada :attribute tidak valid.',
            'required_if' => 'Kolom :attribute wajib diisi karena Anda memilih "Sudah".',
        ];

        $attributes = [
            'provinsi' => 'Provinsi',
            'kabupaten' => 'Kabupaten/Kota',
            'kecamatan' => 'Kecamatan',
            'kelurahan' => 'Kelurahan',
            'alamat' => 'Alamat lengkap',
            'luas' => 'Luas tanah',
            'nama_wakif' => 'Nama Wakif',
            'penggunaan' => 'Jenis penggunaan',
            'sudah_ikrar' => 'Status ikrar wakaf',
            'nomor_ikrar' => 'Nomor ikrar wakaf',
            'tanggal_ikrar' => 'Tanggal ikrar wakaf',
            'nama_nazhir' => 'Nama Nazhir',
            'sudah_sertifikat' => 'Status sertifikat',
            'nomor_sertifikat' => 'Nomor sertifikat/NIB',
            'tanggal_sertifikat' => 'Tanggal sertifikat',
            'atas_nama_sertifikat' => 'Atas nama sertifikat',
            'jenis_sertifikat' => 'Jenis sertifikat',
            'tindakan_aset' => 'Tindakan yang diinginkan',
            'hp_wa' => 'Nomor HP/WhatsApp',
        ];

        $validated = $request->validate($rules, $messages, $attributes);

        // Save application in database
        $pengajuan = WkPengajuan::create($validated);

        // Generate registration number automatically: REG/YYYYMMDD/XXXXX
        $nomor = 'REG/' . date('Ymd') . '/' . str_pad($pengajuan->id, 5, '0', STR_PAD_LEFT);
        $pengajuan->update(['nomor_pengajuan' => $nomor]);

        return response()->json([
            'success' => true,
            'message' => 'Data pendaftaran aset berhasil disimpan dan akan segera ditindaklanjuti oleh pengurus LWP NU setempat.',
            'nomor_pengajuan' => $nomor,
            'download_url' => route('land-waqf.register.download', ['id' => $pengajuan->id]),
            'data' => $pengajuan
        ]);
    }

    /**
     * Download the submitted public asset registration as a PDF receipt.
     */
    public function downloadPdf($id)
    {
        $item = WkPengajuan::findOrFail($id);

        $pdf = \PDF::loadView('lwp.pdf.bukti_pengajuan', compact('item'));
        
        return $pdf->download('bukti_pengajuan_wakaf_' . str_replace('/', '_', $item->nomor_pengajuan) . '.pdf');
    }

    /**
     * Show the status checking form and details of a registration.
     */
    public function showStatusForm(Request $request)
    {
        $nomorPengajuan = $request->query('nomor_pengajuan');
        $item = null;
        $searched = false;

        if ($nomorPengajuan) {
            $searched = true;
            $item = WkPengajuan::where('nomor_pengajuan', trim($nomorPengajuan))->first();
        }

        if ($request->ajax()) {
            return view('public.land-waqf.partials._check_status_result', compact('item', 'searched', 'nomorPengajuan'));
        }

        return view('public.land-waqf.check_status', compact('item', 'searched', 'nomorPengajuan'));
    }
}
