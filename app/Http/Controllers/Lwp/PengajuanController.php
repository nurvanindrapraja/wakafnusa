<?php

namespace App\Http\Controllers\Lwp;

use App\Http\Controllers\Controller;
use App\Models\WkPengajuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PengajuanController extends Controller
{
    /**
     * Display a listing of public submissions.
     */
    public function index(Request $request)
    {
        $user = Auth::guard('lwp')->user();
        $level = (int) $user->LEVEL;
        $loc = $user->KODE_LOKASI;

        $query = WkPengajuan::query();

        // Level-based location scoping
        // LEVEL 9 (Admin App) & 91 (Admin PBNU) can see everything
        if (!in_array($level, [9, 91])) {
            if ($level === 31) { // PWNU - Provincial (2 chars)
                $prefix = substr($loc, 0, 2);
                $query->where('provinsi_id', 'LIKE', $prefix . '%');
            } elseif ($level === 3) { // PCNU - Kabupaten (5 chars)
                $prefix = substr($loc, 0, 5);
                $query->where('kabupaten_id', 'LIKE', $prefix . '%');
            } elseif ($level === 2) { // MWCNU - Kecamatan (8 chars)
                $prefix = substr($loc, 0, 8);
                $query->where('kecamatan_id', 'LIKE', $prefix . '%');
            } else {
                // If they are level 1 or unknown, don't show any data to be safe
                $query->whereRaw('1 = 0');
            }
        }

        // Filtering
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nomor_pengajuan', 'LIKE', '%' . $search . '%')
                  ->orWhere('nama_wakif', 'LIKE', '%' . $search . '%')
                  ->orWhere('nama_aset', 'LIKE', '%' . $search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $items = $query->orderBy('id', 'desc')->paginate(10)->withQueryString();

        if ($request->ajax()) {
            return view('lwp.dashboard.partials._pengajuan_list', compact('items'))->render();
        }

        return view('lwp.dashboard.pengajuan', compact('items', 'user'));
    }

    /**
     * Display details of a public submission in modal popup.
     */
    public function show($id)
    {
        $item = WkPengajuan::with('processor')->findOrFail($id);

        if (request()->ajax()) {
            return view('lwp.dashboard.partials._pengajuan_detail_modal', compact('item'))->render();
        }

        return abort(404);
    }

    /**
     * Update status to field verification (Verifikasi Lapangan).
     */
    public function verifyLapangan(Request $request, $id)
    {
        $request->validate([
            'tanggal_verifikasi' => 'required|date'
        ]);

        $item = WkPengajuan::findOrFail($id);
        $item->update([
            'status' => 'VERIFIKASI_LAPANGAN',
            'tanggal_verifikasi' => $request->tanggal_verifikasi,
            'processed_by' => Auth::guard('lwp')->id(),
            'processed_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status pengajuan berhasil diubah menjadi Verifikasi Lapangan.'
        ]);
    }

    /**
     * Mark application processed successfully and export to wk_wakaf.
     */
    public function complete(Request $request, $id)
    {
        $request->validate([
            'nomor_ikrar' => 'required|string|max:255',
            'tanggal_ikrar' => 'required|date',
            'nomor_sertifikat' => 'required|string|max:255',
            'tanggal_sertifikat' => 'required|date',
            'atas_nama_sertifikat' => 'required|string|max:255',
            'jenis_sertifikat' => 'required|string|in:SHM,SHGB,WAKAF'
        ]);

        $item = WkPengajuan::findOrFail($id);
        
        $item->update([
            'sudah_ikrar' => 1,
            'nomor_ikrar' => $request->nomor_ikrar,
            'tanggal_ikrar' => $request->tanggal_ikrar,
            'sudah_sertifikat' => 1,
            'nomor_sertifikat' => $request->nomor_sertifikat,
            'tanggal_sertifikat' => $request->tanggal_sertifikat,
            'atas_nama_sertifikat' => $request->atas_nama_sertifikat,
            'jenis_sertifikat' => $request->jenis_sertifikat,
            'status' => 'SELESAI',
            'processed_by' => Auth::guard('lwp')->id(),
            'processed_at' => now()
        ]);

        // Export data to wk_wakaf table automatically
        $this->exportToSiwaknuTable($item);

        return response()->json([
            'success' => true,
            'message' => 'Pengajuan telah selesai diproses dan data berhasil diekspor ke SIWAKNU!'
        ]);
    }

    /**
     * Cancel/Reject application (Batalkan Pengajuan).
     */
    public function cancel(Request $request, $id)
    {
        $request->validate([
            'alasan_batal' => 'required|string'
        ]);

        $item = WkPengajuan::findOrFail($id);
        $item->update([
            'status' => 'DIBATALKAN',
            'alasan_batal' => $request->alasan_batal,
            'processed_by' => Auth::guard('lwp')->id(),
            'processed_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pengajuan berhasil dibatalkan.'
        ]);
    }

    /**
     * Manual export to SIWAKNU for applications with status Didaftarkan menjadi aset NU.
     */
    public function exportToSiwaknu($id)
    {
        $item = WkPengajuan::findOrFail($id);

        if ($item->tindakan_aset !== 'ASET_NU') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya pengajuan dengan tindakan "Didaftarkan menjadi aset NU" yang dapat diekspor langsung.'
            ], 400);
        }

        $this->exportToSiwaknuTable($item);

        return response()->json([
            'success' => true,
            'message' => 'Data pengajuan aset berhasil diekspor ke SIWAKNU!'
        ]);
    }

    /**
     * Helper method to export WkPengajuan details to wk_wakaf database table.
     */
    private function exportToSiwaknuTable(WkPengajuan $pengajuan)
    {
        $kode_lokasi_input = $pengajuan->kelurahan_id;
        $kode_cabang = substr($kode_lokasi_input, 0, 5);
        $cabang = DB::connection('mysql_no_prefix')->table('wk_cabang')->where('KODE_CABANG', $kode_cabang)->first();
        
        $nia = '';
        $kode_index = '';
        if ($cabang && !empty($cabang->KODE_INDEX)) {
            $kode_index = $cabang->KODE_INDEX;
            $prefix = str_replace('.', '', $kode_index);
            
            $maxNia = DB::connection('mysql_no_prefix')->table('wk_wakaf')
                ->where('NIA', 'like', $prefix . '%')
                ->where('ISDEL', 0)
                ->orderBy('NIA', 'desc')
                ->value('NIA');
            
            if ($maxNia) {
                $nia = (string)((int)$maxNia + 1);
            } else {
                $nia = $prefix . '000001';
            }
        }

        // Get usage code from wk_penggunaan
        $kode_peng = DB::connection('mysql_no_prefix')->table('wk_penggunaan')
            ->where('PENGGUNAAN', 'LIKE', '%' . $pengajuan->penggunaan . '%')
            ->value('KODE_PENG') ?: '07'; // fallback to LAINNYA (07)

        // Get default values for sttanah, stprod, stwakaf, kriteria
        $id_st = DB::connection('mysql_no_prefix')->table('wk_sttanah')->where('JENIS_ST', 'TIDAK SENGKETA')->value('ID_ST') ?: 1;
        $id_sp = DB::connection('mysql_no_prefix')->table('wk_stprod')->value('ID_SP') ?: 1;
        $id_sw = DB::connection('mysql_no_prefix')->table('wk_stwakaf')->value('ID_SW') ?: 1;
        $id_kriteria = DB::connection('mysql_no_prefix')->table('wk_kriteria')->value('ID_KRITERIA') ?: 1;

        $userId = Auth::guard('lwp')->id();

        $data = [
            'KODE_LOKASI' => $kode_lokasi_input,
            'ALAMAT' => $pengajuan->alamat,
            'NO_AIW' => $pengajuan->nomor_ikrar,
            'TGL_AIW' => $pengajuan->tanggal_ikrar,
            'NO_SERT' => $pengajuan->nomor_sertifikat,
            'TGL_SERT' => $pengajuan->tanggal_sertifikat,
            'NAMA' => $pengajuan->nama_aset ?: '-',
            'WAKIF' => $pengajuan->nama_wakif,
            'NADZIR' => $pengajuan->nama_nazhir ?: 'NAHDLATUL ULAMA',
            'LUAS' => $pengajuan->luas,
            'KODE_PENG' => $kode_peng,
            'ID_ST' => $id_st,
            'ID_SP' => $id_sp,
            'ID_SW' => $id_sw,
            'ID_KRITERIA' => $id_kriteria,
            'NJOP' => 0,
            'THN_NJOP' => 0,
            'KETERANGAN' => $pengajuan->keterangan,
            'LAT' => 0.0, // default placeholder
            'LNG' => 0.0, // default placeholder
            'ID_USER' => $userId,
            'TGINS' => now(),
            'ISDEL' => 0,
            'ISOK' => 1,
            'NIA' => $nia,
            'KODE_INDEX' => $kode_index,
        ];

        $id = DB::connection('mysql_no_prefix')->table('wk_wakaf')->insertGetId($data);

        // Record history in wk_hisentri
        DB::connection('mysql_no_prefix')->table('wk_hisentri')->insert([
            'id_wakaf' => $id,
            'id_user' => $userId,
            'tgl_entri' => now(),
            'fase' => 1
        ]);
    }
}
