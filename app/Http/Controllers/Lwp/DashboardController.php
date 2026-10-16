<?php

namespace App\Http\Controllers\Lwp;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::guard('lwp')->user();
        $level = (int) $user->LEVEL;

        // 1. Total Nasional
        $totalNasional = \DB::connection('mysql_no_prefix')->table('vw_jml_per_prov')->sum('JUMLAH');
        $luasNasional = \DB::connection('mysql_no_prefix')->table('vw_luas_per_prov')->sum('JUMLAH');

        // 2. Total Spesifik Level
        $totalSpesifik = null;
        $luasSpesifik = null;
        $namaSpesifik = null;
        
        $kodeLokasi = $user->KODE_LOKASI;

        if (in_array($level, [2, 3, 31])) {
            $tableJml = '';
            $tableLuas = '';
            
            if ($level === 2) {
                $tableJml = 'vw_jml_per_kec';
                $tableLuas = 'vw_luas_per_kec';
            } elseif ($level === 3) {
                $tableJml = 'vw_jml_per_kab';
                $tableLuas = 'vw_luas_per_kab';
            } elseif ($level === 31) {
                $tableJml = 'vw_jml_per_prov';
                $tableLuas = 'vw_luas_per_prov';
            }

            if ($tableJml && $kodeLokasi) {
                $wilayahInfo = \DB::connection('mysql_no_prefix')
                    ->table($tableJml)
                    ->where('KODE_LOKASI', $kodeLokasi)
                    ->first();
                if ($wilayahInfo) {
                    $totalSpesifik = (int) $wilayahInfo->JUMLAH;
                    $namaSpesifik = 'Wilayah: ' . $wilayahInfo->NAMA_LOKASI;
                    
                    $luasInfo = \DB::connection('mysql_no_prefix')
                        ->table($tableLuas)
                        ->where('KODE_LOKASI', $kodeLokasi)
                        ->first();
                    $luasSpesifik = $luasInfo ? (float) $luasInfo->JUMLAH : 0;
                }
            }
        } elseif (in_array($level, [1, 9, 91])) {
            $topProvinsi = \DB::connection('mysql_no_prefix')
                ->table('vw_jml_per_prov as j')
                ->leftJoin('vw_luas_per_prov as l', 'j.KODE_LOKASI', '=', 'l.KODE_LOKASI')
                ->select('j.KODE_LOKASI', 'j.NAMA_LOKASI', 'j.JUMLAH as jml', 'l.JUMLAH as luas')
                ->orderBy('j.JUMLAH', 'desc')
                ->first();
            
            if ($topProvinsi) {
                $totalSpesifik = (int) $topProvinsi->jml;
                $luasSpesifik = (float) $topProvinsi->luas;
                $namaSpesifik = 'Provinsi Terbanyak: ' . $topProvinsi->NAMA_LOKASI;
            }
        }

        // 3. Grafik Komposisi Penggunaan (Pie)
        $pieData = \DB::connection('mysql_no_prefix')->table('vw_jml_peng')
            ->orderBy('JUMLAH', 'desc')
            ->get();

        // 4. Grafik 10 Provinsi Terbanyak (Bar)
        $barData = \DB::connection('mysql_no_prefix')->table('vw_jml_per_prov')
            ->orderBy('JUMLAH', 'desc')
            ->limit(10)
            ->get();

        // 5. Tabel Penggunaan per Provinsi (vw_jml_peng_per_prov)
        // Group by KODE_LOKASI -> NAMA_LOKASI -> PENGGUNAAN
        $rawPenggunaanProv = \DB::connection('mysql_no_prefix')->table('vw_jml_peng_per_prov')
            ->orderBy('NAMA_LOKASI')
            ->orderBy('JUMLAH', 'desc')
            ->get();
            
        $tablePenggunaanProv = [];
        foreach ($rawPenggunaanProv as $row) {
            if (!isset($tablePenggunaanProv[$row->KODE_LOKASI])) {
                $tablePenggunaanProv[$row->KODE_LOKASI] = [
                    'nama_lokasi' => $row->NAMA_LOKASI,
                    'penggunaan' => []
                ];
            }
            $tablePenggunaanProv[$row->KODE_LOKASI]['penggunaan'][] = [
                'nama' => $row->PENGGUNAAN,
                'jumlah' => $row->JUMLAH
            ];
        }

        return view('lwp.dashboard.index', compact(
            'totalNasional', 'luasNasional', 
            'totalSpesifik', 'luasSpesifik', 'namaSpesifik', 'level',
            'pieData', 'barData', 'tablePenggunaanProv'
        ));
    }

    public function dashboardKabupatenTable(Request $request)
    {
        $search = $request->query('search');

        $query = \DB::connection('mysql_no_prefix')
            ->table('vw_jml_per_kab as j')
            ->leftJoin('vw_luas_per_kab as l', 'j.KODE_LOKASI', '=', 'l.KODE_LOKASI')
            ->select('j.KODE_LOKASI', 'j.NAMA_LOKASI', 'j.JUMLAH as total_aset', 'l.JUMLAH as total_luas');

        if ($search) {
            $query->where('j.NAMA_LOKASI', 'like', '%' . $search . '%');
        }

        $topKabupaten = $query->orderBy('j.JUMLAH', 'desc')->paginate(10)->onEachSide(1);
            
        return view('lwp.dashboard.partials._table_kabupaten', compact('topKabupaten'))->render();
    }

    public function manajemen(Request $request)
    {
        $user = Auth::guard('lwp')->user();
        $query = \DB::connection('mysql_no_prefix')
            ->table('vw_wakaf_detail')
            ->where('ISDEL', 0)
            ->whereIn('ISOK', [0, 1]);

        // Scoping based on Level
        // 9: Admin (App), 91: Admin (PBNU) -> All data
        // 31: Moderator (PWNU) -> 2 chars prefix (Prov)
        // 3: Moderator (PCNU) -> 5 chars prefix (Kab/Kota)
        // 2: User (MWCNU) -> 8 chars prefix (Kec)
        
        $level = (int) $user->LEVEL;
        $loc = $user->KODE_LOKASI;

        if ($level === 31) { // PWNU
            $prefix = substr($loc, 0, 2);
            $query->where('KODE_LOKASI', 'LIKE', $prefix . '%');
        } elseif ($level === 3) { // PCNU
            $prefix = substr($loc, 0, 5);
            $query->where('KODE_LOKASI', 'LIKE', $prefix . '%');
        } elseif ($level === 2) { // MWCNU
            $prefix = substr($loc, 0, 8);
            $query->where('KODE_LOKASI', 'LIKE', $prefix . '%');
        }

        // Search Filters
        if ($request->wakif) {
            $query->where('WAKIF', 'LIKE', '%' . $request->wakif . '%');
        }
        if ($request->penggunaan) {
            $query->where('PENGGUNAAN', 'LIKE', '%' . $request->penggunaan . '%');
        }
        if ($request->lokasi) {
            $lokasi = $request->lokasi;
            $query->where(function($q) use ($lokasi) {
                $q->where('PROV', 'LIKE', '%' . $lokasi . '%')
                  ->orWhere('KAB', 'LIKE', '%' . $lokasi . '%')
                  ->orWhere('KEC', 'LIKE', '%' . $lokasi . '%')
                  ->orWhere('KEL', 'LIKE', '%' . $lokasi . '%');
            });
        }

        // Cascading Location Filters
        if ($request->filled('kelurahan')) {
            $query->where('KODE_LOKASI', 'LIKE', $request->kelurahan . '%');
        } elseif ($request->filled('kecamatan')) {
            $query->where('KODE_LOKASI', 'LIKE', $request->kecamatan . '%');
        } elseif ($request->filled('kabupaten')) {
            $query->where('KODE_LOKASI', 'LIKE', $request->kabupaten . '%');
        } elseif ($request->filled('provinsi')) {
            $query->where('KODE_LOKASI', 'LIKE', $request->provinsi . '%');
        }

        $items = $query->orderBy('ID_WAKAF', 'desc')->paginate(10)->appends($request->query());

        if ($request->ajax()) {
            return view('lwp.dashboard.partials._waqf_list', compact('items'))->render();
        }

        $provinces = collect();
        $filterKabupatens = collect();
        $filterKecamatans = collect();
        $filterKelurahans = collect();

        if (in_array($level, [9, 91])) { // Admin (App) / Admin (PBNU)
            $provinces = \App\Models\Location::whereRaw("LENGTH(kode_lokasi) = 2")
                ->orderBy('nama_lokasi', 'asc')
                ->get();

            if ($request->filled('provinsi')) {
                $filterKabupatens = \App\Models\Location::where('kode_lokasi', 'like', $request->provinsi . '%')
                    ->whereRaw("LENGTH(kode_lokasi) = 5")
                    ->orderBy('nama_lokasi', 'asc')
                    ->get();
            }
            if ($request->filled('kabupaten')) {
                $filterKecamatans = \App\Models\Location::where('kode_lokasi', 'like', $request->kabupaten . '%')
                    ->whereRaw("LENGTH(kode_lokasi) = 8")
                    ->orderBy('nama_lokasi', 'asc')
                    ->get();
            }
            if ($request->filled('kecamatan')) {
                $filterKelurahans = \App\Models\Location::where('kode_lokasi', 'like', $request->kecamatan . '%')
                    ->whereRaw("LENGTH(kode_lokasi) = 13")
                    ->orderBy('nama_lokasi', 'asc')
                    ->get();
            }
        } elseif ($level === 31) { // PWNU (Provinsi fixed)
            $provPrefix = substr($loc, 0, 2);
            $filterKabupatens = \App\Models\Location::where('kode_lokasi', 'like', $provPrefix . '%')
                ->whereRaw("LENGTH(kode_lokasi) = 5")
                ->orderBy('nama_lokasi', 'asc')
                ->get();

            if ($request->filled('kabupaten')) {
                $filterKecamatans = \App\Models\Location::where('kode_lokasi', 'like', $request->kabupaten . '%')
                    ->whereRaw("LENGTH(kode_lokasi) = 8")
                    ->orderBy('nama_lokasi', 'asc')
                    ->get();
            }
            if ($request->filled('kecamatan')) {
                $filterKelurahans = \App\Models\Location::where('kode_lokasi', 'like', $request->kecamatan . '%')
                    ->whereRaw("LENGTH(kode_lokasi) = 13")
                    ->orderBy('nama_lokasi', 'asc')
                    ->get();
            }
        } elseif ($level === 3) { // PCNU (Kota/Kab fixed)
            $kabPrefix = substr($loc, 0, 5);
            $filterKecamatans = \App\Models\Location::where('kode_lokasi', 'like', $kabPrefix . '%')
                ->whereRaw("LENGTH(kode_lokasi) = 8")
                ->orderBy('nama_lokasi', 'asc')
                ->get();

            if ($request->filled('kecamatan')) {
                $filterKelurahans = \App\Models\Location::where('kode_lokasi', 'like', $request->kecamatan . '%')
                    ->whereRaw("LENGTH(kode_lokasi) = 13")
                    ->orderBy('nama_lokasi', 'asc')
                    ->get();
            }
        } elseif ($level === 2) { // MWCNU (Kecamatan fixed)
            $kecPrefix = substr($loc, 0, 8);
            $filterKelurahans = \App\Models\Location::where('kode_lokasi', 'like', $kecPrefix . '%')
                ->whereRaw("LENGTH(kode_lokasi) = 13")
                ->orderBy('nama_lokasi', 'asc')
                ->get();
        } else {
            // Level 1 / Lainnya
            if (!empty($loc)) {
                $filterKelurahans = \App\Models\Location::where('kode_lokasi', 'like', substr($loc, 0, 8) . '%')
                    ->whereRaw("LENGTH(kode_lokasi) = 13")
                    ->orderBy('nama_lokasi', 'asc')
                    ->get();
            }
        }

        return view('lwp.dashboard.manajemen', compact(
            'items',
            'user',
            'level',
            'provinces',
            'filterKabupatens',
            'filterKecamatans',
            'filterKelurahans'
        ));
    }

    public function show($id)
    {
        $item = \DB::connection('mysql_no_prefix')
            ->table('vw_wakaf_detail as vw')
            ->leftJoin('wk_wakaf as wk', 'vw.ID_WAKAF', '=', 'wk.ID_WAKAF')
            ->leftJoin('wk_user as u', 'wk.ID_USER', '=', 'u.ID_USER')
            ->where('vw.ID_WAKAF', $id)
            ->select('vw.*', 'wk.NIA', 'wk.TGINS', 'u.NAMA_USER as INPUTTER')
            ->first();

        $images = \DB::connection('mysql_no_prefix')
            ->table('wk_gambar as g')
            ->join('wk_jenis_gambar as j', 'g.ID_JENIS_GAMBAR', '=', 'j.ID_JENIS_GAMBAR')
            ->where('g.ID_WAKAF', $id)
            ->where('g.ISDEL', 0)
            ->get();

        $history = \DB::connection('mysql_no_prefix')
            ->table('wk_hisentri as h')
            ->join('wk_user as u', 'h.id_user', '=', 'u.ID_USER')
            ->where('h.id_wakaf', $id)
            ->select('h.*', 'u.NAMA_USER')
            ->orderBy('h.tgl_entri', 'desc')
            ->get();

        foreach ($images as $img) {
            $localPath = public_path('upload/location/' . $img->PATH_GAMBAR);
            if (file_exists($localPath)) {
                $img->URL = url('upload/location/' . $img->PATH_GAMBAR);
            } else {
                $img->URL = 'https://siwaknu.id/upload/location/' . $img->PATH_GAMBAR;
            }
        }

        if (request()->ajax()) {
            $readOnly = request()->has('readonly');
            return view('lwp.dashboard.partials._waqf_detail_modal', compact('item', 'images', 'history', 'readOnly'))->render();
        }
        return abort(404);
    }

    public function generatePdf($id)
    {
        $item = \DB::connection('mysql_no_prefix')
            ->table('vw_wakaf_detail as vw')
            ->leftJoin('wk_wakaf as wk', 'vw.ID_WAKAF', '=', 'wk.ID_WAKAF')
            ->leftJoin('wk_user as u', 'wk.ID_USER', '=', 'u.ID_USER')
            ->leftJoin('wk_kriteria as k', 'wk.ID_KRITERIA', '=', 'k.ID_KRITERIA')
            ->where('vw.ID_WAKAF', $id)
            ->select('vw.*', 'wk.NIA', 'wk.TGINS', 'u.NAMA_USER as INPUTTER', 'k.KRITERIA')
            ->first();

        if (!$item) {
            return abort(404, 'Data wakaf tidak ditemukan');
        }

        $pdf = \PDF::loadView('lwp.pdf.bukti_pendaftaran', compact('item'));
        
        return $pdf->stream('bukti_pendaftaran_wakaf_' . $item->NIA . '.pdf');
    }

    public function create()
    {
        $provinces = \DB::connection('mysql_no_prefix')->table('wk_lokasi')->whereRaw('LENGTH(kode_lokasi) = 2')->orderBy('nama_lokasi', 'asc')->get();
        $usages = \DB::connection('mysql_no_prefix')->table('wk_penggunaan')->orderBy('KODE_PENG', 'asc')->get();
        $st_tanah = \DB::connection('mysql_no_prefix')->table('wk_sttanah')->get();
        $st_prod = \DB::connection('mysql_no_prefix')->table('wk_stprod')->get();
        $st_wakaf = \DB::connection('mysql_no_prefix')->table('wk_stwakaf')->get();
        $kriterias = \DB::connection('mysql_no_prefix')->table('wk_kriteria')->get();

        if (request()->ajax()) {
            return view('lwp.dashboard.partials._waqf_create_modal', compact('provinces', 'usages', 'st_tanah', 'st_prod', 'st_wakaf', 'kriterias'))->render();
        }
        return abort(404);
    }

    public function validateLegalitas(Request $request)
    {
        $kode_lokasi = $request->kelurahan ?: $request->kecamatan ?: $request->kabupaten ?: $request->provinsi;
        $no_aiw = $request->no_aiw;
        $no_sert = $request->no_sert;

        $query = \DB::connection('mysql_no_prefix')->table('wk_wakaf')
            ->where('KODE_LOKASI', 'like', $kode_lokasi . '%')
            ->where('ISDEL', 0);
            
        $query->where(function($q) use ($no_aiw, $no_sert) {
            if ($no_aiw) {
                $q->orWhere('NO_AIW', $no_aiw);
            }
            if ($no_sert) {
                $q->orWhere('NO_SERT', $no_sert);
            }
        });

        if ($no_aiw || $no_sert) {
            $exists = $query->first();
            if ($exists) {
                // Get region names
                $kabCode = substr($exists->KODE_LOKASI, 0, 5);
                $kecCode = substr($exists->KODE_LOKASI, 0, 8);
                $kelCode = $exists->KODE_LOKASI;

                $lokasiNames = \DB::connection('mysql_no_prefix')->table('wk_lokasi')
                    ->whereIn('kode_lokasi', [$kabCode, $kecCode, $kelCode])
                    ->pluck('nama_lokasi', 'kode_lokasi')
                    ->toArray();

                $kabName = $lokasiNames[$kabCode] ?? 'Kab/Kota Tidak Diketahui';
                $kecName = $lokasiNames[$kecCode] ?? 'Kecamatan Tidak Diketahui';
                $kelName = $lokasiNames[$kelCode] ?? 'Kelurahan/Desa Tidak Diketahui';
                
                // Construct the error message
                $identifier = [];
                if ($no_aiw && $exists->NO_AIW === $no_aiw) {
                    $identifier[] = "Nomor AIW";
                }
                if ($no_sert && $exists->NO_SERT === $no_sert) {
                    $identifier[] = "Nomor Sertifikat";
                }
                $idString = implode(' atau ', $identifier) ?: "Data";

                $message = "Data dengan {$idString} tersebut sudah ada di {$kabName}, Kec. {$kecName}, {$kelName}";

                return response()->json(['exists' => true, 'message' => $message]);
            }
        }

        return response()->json(['exists' => false]);
    }

    public function store(Request $request)
    {
        $user = Auth::guard('lwp')->user();
        
        $kode_lokasi_input = $request->kelurahan ?: $request->kecamatan ?: $request->kabupaten ?: $request->provinsi;
        
        // Generate NIA and get KODE_INDEX
        $kode_cabang = substr($kode_lokasi_input, 0, 5);
        $cabang = \DB::connection('mysql_no_prefix')->table('wk_cabang')->where('KODE_CABANG', $kode_cabang)->first();
        
        $nia = '';
        $kode_index = '';
        if ($cabang && !empty($cabang->KODE_INDEX)) {
            $kode_index = $cabang->KODE_INDEX;
            $prefix = str_replace('.', '', $kode_index);
            
            $maxNia = \DB::connection('mysql_no_prefix')->table('wk_wakaf')
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
        
        $data = [
            'KODE_LOKASI' => $kode_lokasi_input,
            'ALAMAT' => $request->alamat,
            'NO_AIW' => $request->no_aiw,
            'TGL_AIW' => $request->tgl_aiw,
            'NO_SERT' => $request->no_sert,
            'TGL_SERT' => $request->tgl_sert,
            'NAMA' => $request->nama,
            'WAKIF' => $request->wakif,
            'NADZIR' => $request->nazhir,
            'LUAS' => str_replace(',', '.', $request->luas),
            'KODE_PENG' => $request->penggunaan,
            'ID_ST' => $request->status_sengketa,
            'ID_SP' => $request->status_produktif,
            'ID_SW' => $request->status_tanah,
            'ID_KRITERIA' => $request->kriteria,
            'NJOP' => $request->njop ?: 0,
            'THN_NJOP' => $request->thn_njop ?: 0,
            'KETERANGAN' => $request->keterangan,
            'LAT' => $request->lat,
            'LNG' => $request->lng,
            'ID_USER' => $user->ID_USER,
            'TGINS' => now(),
            'ISDEL' => 0,
            'ISOK' => 1,
            'NIA' => $nia,
            'KODE_INDEX' => $kode_index,
        ];

        $id = \DB::connection('mysql_no_prefix')->table('wk_wakaf')->insertGetId($data);

        // Record history
        \DB::connection('mysql_no_prefix')->table('wk_hisentri')->insert([
            'id_wakaf' => $id,
            'id_user' => $user->ID_USER,
            'tgl_entri' => now(),
            'fase' => 1
        ]);

        // Upload files
        $uploadPath = public_path('upload/location');
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $files = [
            ['input' => 'file_sertifikat', 'type' => 1, 'desc' => 'ket_sertifikat'],
            ['input' => 'file_aiw', 'type' => 2, 'desc' => 'ket_aiw'],
            ['input' => 'file_fisik', 'type' => 3, 'desc' => 'ket_fisik']
        ];

        foreach ($files as $file) {
            if ($request->hasFile($file['input'])) {
                $uploadedFiles = $request->file($file['input']);
                if (!is_array($uploadedFiles)) {
                    $uploadedFiles = [$uploadedFiles];
                }
                
                foreach ($uploadedFiles as $uploadedFile) {
                    $filename = 'wk_' . md5(uniqid()) . '-' . $file['type'] . '-' . $id . '-' . mt_rand(1000, 9999) . '.' . $uploadedFile->getClientOriginalExtension();
                    $uploadedFile->move($uploadPath, $filename);

                    \DB::connection('mysql_no_prefix')->table('wk_gambar')->insert([
                        'ID_WAKAF' => $id,
                        'ID_USER' => $user->ID_USER,
                        'ID_JENIS_GAMBAR' => $file['type'],
                        'PATH_GAMBAR' => $filename,
                        'KET_GAMBAR' => $request->input($file['desc']),
                        'ISDEL' => 0,
                        'TGL_UPLOAD' => now()
                    ]);
                }
            }
        }

        return response()->json(['success' => true]);
    }

    public function edit($id)
    {
        $item = \DB::connection('mysql_no_prefix')
            ->table('wk_wakaf')
            ->where('ID_WAKAF', $id)
            ->first();

        $provinces = \DB::connection('mysql_no_prefix')->table('wk_lokasi')->whereRaw('LENGTH(kode_lokasi) = 2')->orderBy('nama_lokasi', 'asc')->get();
        $usages = \DB::connection('mysql_no_prefix')->table('wk_penggunaan')->orderBy('KODE_PENG', 'asc')->get();
        $st_tanah = \DB::connection('mysql_no_prefix')->table('wk_sttanah')->get();
        $st_prod = \DB::connection('mysql_no_prefix')->table('wk_stprod')->get();
        $st_wakaf = \DB::connection('mysql_no_prefix')->table('wk_stwakaf')->get();
        $kriterias = \DB::connection('mysql_no_prefix')->table('wk_kriteria')->get();

        $images = \DB::connection('mysql_no_prefix')
            ->table('wk_gambar as g')
            ->join('wk_jenis_gambar as j', 'g.ID_JENIS_GAMBAR', '=', 'j.ID_JENIS_GAMBAR')
            ->where('g.ID_WAKAF', $id)
            ->where('g.ISDEL', 0)
            ->select('g.*', 'j.JENIS_GAMBAR')
            ->get();
            
        foreach ($images as $img) {
            $localPath = public_path('upload/location/' . $img->PATH_GAMBAR);
            if (file_exists($localPath)) {
                $img->URL = url('upload/location/' . $img->PATH_GAMBAR);
            } else {
                $img->URL = 'https://siwaknu.id/upload/location/' . $img->PATH_GAMBAR;
            }
        }

        $kode_lokasi = $item->KODE_LOKASI;
        $prov = substr($kode_lokasi, 0, 2);
        $kab = substr($kode_lokasi, 0, 5);
        $kec = substr($kode_lokasi, 0, 8);
        $kel = substr($kode_lokasi, 0, 13);
        
        $kabupatens = $prov ? \DB::connection('mysql_no_prefix')->table('wk_lokasi')->where('kode_lokasi', 'like', $prov . '.%')->whereRaw('LENGTH(kode_lokasi) = 5')->orderBy('nama_lokasi', 'asc')->get() : [];
        $kecamatans = $kab ? \DB::connection('mysql_no_prefix')->table('wk_lokasi')->where('kode_lokasi', 'like', $kab . '.%')->whereRaw('LENGTH(kode_lokasi) = 8')->orderBy('nama_lokasi', 'asc')->get() : [];
        $kelurahans = $kec ? \DB::connection('mysql_no_prefix')->table('wk_lokasi')->where('kode_lokasi', 'like', $kec . '.%')->whereRaw('LENGTH(kode_lokasi) = 13')->orderBy('nama_lokasi', 'asc')->get() : [];

        if (request()->ajax()) {
            return view('lwp.dashboard.partials._waqf_edit_modal', compact('item', 'provinces', 'usages', 'st_tanah', 'st_prod', 'st_wakaf', 'kriterias', 'kabupatens', 'kecamatans', 'kelurahans', 'prov', 'kab', 'kec', 'kel', 'images'))->render();
        }
        return abort(404);
    }

    public function update(Request $request, $id)
    {
        $user = Auth::guard('lwp')->user();
        $kode_lokasi_input = $request->kelurahan ?: $request->kecamatan ?: $request->kabupaten ?: $request->provinsi;
        
        \DB::connection('mysql_no_prefix')
            ->table('wk_wakaf')
            ->where('ID_WAKAF', $id)
            ->update([
                'KODE_LOKASI' => $kode_lokasi_input,
                'ALAMAT' => $request->alamat,
                'NO_AIW' => $request->no_aiw,
                'TGL_AIW' => $request->tgl_aiw,
                'NO_SERT' => $request->no_sert,
                'TGL_SERT' => $request->tgl_sert,
                'NAMA' => $request->nama,
                'WAKIF' => $request->wakif,
                'NADZIR' => $request->nazhir,
                'LUAS' => str_replace(',', '.', $request->luas),
                'KODE_PENG' => $request->penggunaan,
                'ID_ST' => $request->status_sengketa,
                'ID_SP' => $request->status_produktif,
                'ID_SW' => $request->status_tanah,
                'ID_KRITERIA' => $request->kriteria,
                'NJOP' => $request->njop ?: 0,
                'THN_NJOP' => $request->thn_njop ?: 0,
                'KETERANGAN' => $request->keterangan,
                'LAT' => $request->lat,
                'LNG' => $request->lng,
            ]);

        // Record history
        \DB::connection('mysql_no_prefix')->table('wk_hisentri')->insert([
            'id_wakaf' => $id,
            'id_user' => $user->ID_USER,
            'tgl_entri' => now(),
            'fase' => 2
        ]);

        // Upload files
        $uploadPath = public_path('upload/location');
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $files = [
            ['input' => 'file_sertifikat', 'type' => 1, 'desc' => 'ket_sertifikat'],
            ['input' => 'file_aiw', 'type' => 2, 'desc' => 'ket_aiw'],
            ['input' => 'file_fisik', 'type' => 3, 'desc' => 'ket_fisik']
        ];

        foreach ($files as $file) {
            if ($request->hasFile($file['input'])) {
                $uploadedFiles = $request->file($file['input']);
                if (!is_array($uploadedFiles)) {
                    $uploadedFiles = [$uploadedFiles];
                }
                
                foreach ($uploadedFiles as $uploadedFile) {
                    $filename = 'wk_' . md5(uniqid()) . '-' . $file['type'] . '-' . $id . '-' . mt_rand(1000, 9999) . '.' . $uploadedFile->getClientOriginalExtension();
                    $uploadedFile->move($uploadPath, $filename);

                    \DB::connection('mysql_no_prefix')->table('wk_gambar')->insert([
                        'ID_WAKAF' => $id,
                        'ID_USER' => $user->ID_USER,
                        'ID_JENIS_GAMBAR' => $file['type'],
                        'PATH_GAMBAR' => $filename,
                        'KET_GAMBAR' => $request->input($file['desc']),
                        'ISDEL' => 0,
                        'TGL_UPLOAD' => now()
                    ]);
                }
            }
        }

        return response()->json(['success' => true]);
    }

    public function verify($id)
    {
        $user = Auth::guard('lwp')->user();
        
        \DB::connection('mysql_no_prefix')
            ->table('wk_wakaf')
            ->where('ID_WAKAF', $id)
            ->update(['ISOK' => 1]);

        // Record history
        \DB::connection('mysql_no_prefix')->table('wk_hisentri')->insert([
            'id_wakaf' => $id,
            'id_user' => $user->ID_USER,
            'tgl_entri' => now(),
            'fase' => 2
        ]);

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $user = Auth::guard('lwp')->user();
        \DB::connection('mysql_no_prefix')
            ->table('wk_wakaf')
            ->where('ID_WAKAF', $id)
            ->update(['ISDEL' => 1]);

        // Record history
        \DB::connection('mysql_no_prefix')->table('wk_hisentri')->insert([
            'id_wakaf' => $id,
            'id_user' => $user->ID_USER,
            'tgl_entri' => now(),
            'fase' => 3
        ]);

        return response()->json(['success' => true]);
    }

    public function deleteImage($id)
    {
        \DB::connection('mysql_no_prefix')
            ->table('wk_gambar')
            ->where('ID_GAMBAR', $id)
            ->update(['ISDEL' => 1]);
            
        return response()->json(['success' => true]);
    }

    public function getLokasi(Request $request)
    {
        $parent = trim($request->parent);
        $level = $request->level;
        
        $length = match($level) {
            'kab' => 5,
            'kec' => 8,
            'kel' => 13,
            default => 0
        };

        if ($level == 'cabang') {
            $data = \DB::connection('mysql_no_prefix')->table('wk_cabang')
                ->where('KODE_LOKASI', 'like', substr($parent, 0, 5) . '%')
                ->get();
            return response()->json($data);
        }

        $query = \App\Models\Location::where('kode_lokasi', 'like', $parent . '%')
            ->whereRaw("LENGTH(kode_lokasi) = $length")
            ->orderBy('nama_lokasi', 'asc');

        return response()->json($query->get());
    }

    public function profil()
    {
        $user = Auth::guard('lwp')->user();
        $detail = \DB::connection('mysql_no_prefix')
            ->table('vw_user_detail')
            ->where('ID_USER', $user->ID_USER)
            ->first();

        return view('lwp.dashboard.profil', compact('user', 'detail'));
    }

    public function updateProfil(Request $request)
    {
        $user = Auth::guard('lwp')->user();
        
        $request->validate([
            'nama_user' => 'required|string|max:45',
            'telp_user' => 'nullable|string|max:45',
            'no_kartanu' => 'nullable|string|max:45',
            'old_password' => 'nullable|string',
            'new_password' => 'nullable|string|min:6|confirmed',
        ]);

        $updateData = [
            'NAMA_USER' => $request->nama_user,
            'TELP_USER' => $request->telp_user,
            'NO_KARTANU' => $request->no_kartanu,
        ];

        if ($request->filled('new_password')) {
            if (!$request->filled('old_password')) {
                if ($request->ajax()) {
                    return response()->json(['status' => 'error', 'message' => 'Password lama wajib diisi untuk mengubah password.'], 422);
                }
                return back()->withErrors(['old_password' => 'Password lama wajib diisi untuk mengubah password.'])->withInput();
            }

            $dbUser = \DB::connection('mysql_no_prefix')
                ->table('wk_user')
                ->where('ID_USER', $user->ID_USER)
                ->first();

            if (sha1($request->old_password) !== $dbUser->PASSWORD_USER) {
                if ($request->ajax()) {
                    return response()->json(['status' => 'error', 'message' => 'Password lama tidak sesuai.'], 422);
                }
                return back()->withErrors(['old_password' => 'Password lama tidak sesuai.'])->withInput();
            }

            $updateData['PASSWORD_USER'] = sha1($request->new_password);
        }

        \DB::connection('mysql_no_prefix')
            ->table('wk_user')
            ->where('ID_USER', $user->ID_USER)
            ->update($updateData);

        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Profil Anda berhasil diperbarui.',
                'user' => [
                    'nama_user' => $request->nama_user,
                    'telp_user' => $request->telp_user ?: '-',
                    'no_kartanu' => $request->no_kartanu ?: '-',
                ]
            ]);
        }

        return redirect()->route('lwp.profil')->with('success', 'Profil Anda berhasil diperbarui.');
    }

    public function getImages($id)
    {
        $images = \DB::connection('mysql_no_prefix')
            ->table('wk_gambar as g')
            ->join('wk_jenis_gambar as j', 'g.ID_JENIS_GAMBAR', '=', 'j.ID_JENIS_GAMBAR')
            ->where('g.ID_WAKAF', $id)
            ->where('g.ISDEL', 0)
            ->select('g.*', 'j.JENIS_GAMBAR')
            ->get();
            
        foreach ($images as $img) {
            $localPath = public_path('upload/location/' . $img->PATH_GAMBAR);
            if (file_exists($localPath)) {
                $img->URL = url('upload/location/' . $img->PATH_GAMBAR);
            } else {
                $img->URL = 'https://siwaknu.id/upload/location/' . $img->PATH_GAMBAR;
            }
        }
            
        return response()->json($images);
    }

    public function laporan(Request $request)
    {
        $currentUser = Auth::guard('lwp')->user();
        $currentLevel = (int) $currentUser->LEVEL;
        $userLoc = $currentUser->KODE_LOKASI;

        $kodeLokasi = trim($request->query('kode_lokasi'));
        $search = $request->query('search');

        // Enforce role-based prefix scoping
        $scopingPrefix = '';
        if ($currentLevel === 31) {
            $scopingPrefix = substr($userLoc, 0, 2);
        } elseif ($currentLevel === 3) {
            $scopingPrefix = substr($userLoc, 0, 5);
        } elseif ($currentLevel === 2) {
            $scopingPrefix = substr($userLoc, 0, 8);
        }

        // Set default initial selection based on role
        if (empty($kodeLokasi)) {
            if ($currentLevel === 3) {
                $kodeLokasi = $scopingPrefix;
            } elseif ($currentLevel === 2) {
                $kodeLokasi = $scopingPrefix;
            }
        }

        // Validate that selected code is within scope
        if (!empty($scopingPrefix) && !empty($kodeLokasi)) {
            if (strpos($kodeLokasi, $scopingPrefix) !== 0) {
                $kodeLokasi = $scopingPrefix;
            }
        }

        // Determine level based on the selected location code length
        if (empty($kodeLokasi)) {
            $level = 'prov';
            $parent = '';
        } elseif (strlen($kodeLokasi) == 2) {
            $level = 'kab';
            $parent = $kodeLokasi;
        } elseif (strlen($kodeLokasi) == 5) {
            $level = 'kec';
            $parent = $kodeLokasi;
        } elseif (strlen($kodeLokasi) == 8) {
            $level = 'kel';
            $parent = $kodeLokasi;
        } else {
            $level = 'kel';
            $parent = $kodeLokasi;
        }

        $cntTable = match($level) {
            'kab' => 'vw_jml_per_kab',
            'kec' => 'vw_jml_per_kec',
            'kel' => 'vw_jml_per_kel',
            default => 'vw_jml_per_prov'
        };
        
        $areaTable = match($level) {
            'kab' => 'vw_luas_per_kab',
            'kec' => 'vw_luas_per_kec',
            'kel' => 'vw_luas_per_kel',
            default => 'vw_luas_per_prov'
        };

        // Determine view location length
        $viewLocLen = match($level) {
            'kab' => 5,
            'kec' => 8,
            'kel' => 13,
            default => 2
        };

        // Scoping function helper
        $applyScoping = function($q, $column = 'KODE_LOKASI') use ($scopingPrefix, $viewLocLen) {
            if (empty($scopingPrefix)) return;
            $scopeLen = strlen($scopingPrefix);
            if ($viewLocLen <= $scopeLen) {
                $q->where($column, '=', substr($scopingPrefix, 0, $viewLocLen));
            } else {
                $q->where($column, 'like', $scopingPrefix . '%');
            }
        };

        // Query builders for total summary
        $totalAsetQuery = \DB::connection('mysql_no_prefix')->table($cntTable);
        $totalLuasQuery = \DB::connection('mysql_no_prefix')->table($areaTable);
        $totalWilayahQuery = \DB::connection('mysql_no_prefix')->table($cntTable);

        // Apply prefix scoping to aggregates
        $applyScoping($totalAsetQuery);
        $applyScoping($totalLuasQuery);
        $applyScoping($totalWilayahQuery);

        // Apply parent filter to aggregates if selected
        if (!empty($parent)) {
            if (strlen($parent) == 13) {
                $totalAsetQuery->where('KODE_LOKASI', $parent);
                $totalLuasQuery->where('KODE_LOKASI', $parent);
                $totalWilayahQuery->where('KODE_LOKASI', $parent);
            } else {
                $totalAsetQuery->where('KODE_LOKASI', 'like', $parent . '%');
                $totalLuasQuery->where('KODE_LOKASI', 'like', $parent . '%');
                $totalWilayahQuery->where('KODE_LOKASI', 'like', $parent . '%');
            }
        }

        $totalAset = $totalAsetQuery->sum('JUMLAH') ?: 0;
        $totalLuas = $totalLuasQuery->sum('JUMLAH') ?: 0;
        $totalWilayah = $totalWilayahQuery->count();

        // Main Query
        $query = \DB::connection('mysql_no_prefix')
            ->table($cntTable . ' as c')
            ->leftJoin($areaTable . ' as a', 'c.KODE_LOKASI', '=', 'a.KODE_LOKASI')
            ->select(
                'c.KODE_LOKASI',
                'c.NAMA_LOKASI',
                \DB::raw('COALESCE(c.JUMLAH, 0) as JML_ASET'),
                \DB::raw('COALESCE(a.JUMLAH, 0) as JML_LUAS')
            );

        // Apply prefix scoping to main query
        $applyScoping($query, 'c.KODE_LOKASI');

        if (!empty($parent)) {
            if (strlen($parent) == 13) {
                $query->where('c.KODE_LOKASI', $parent);
            } else {
                $query->where('c.KODE_LOKASI', 'like', $parent . '%');
            }
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('c.NAMA_LOKASI', 'like', '%' . $search . '%')
                  ->orWhere('c.KODE_LOKASI', 'like', '%' . $search . '%');
            });
        }

        $data = $query->orderBy('c.JUMLAH', 'desc')
            ->orderBy('c.KODE_LOKASI', 'asc')
            ->paginate(15)
            ->withQueryString();

        // Load provinces based on scope
        $provincesQuery = \App\Models\Location::whereRaw("LENGTH(kode_lokasi) = 2");
        if (!empty($scopingPrefix)) {
            $provincesQuery->where('kode_lokasi', '=', substr($scopingPrefix, 0, 2));
        }
        $provinces = $provincesQuery->orderBy('nama_lokasi', 'asc')->get();

        // Prefill sub location dropdowns for restricted roles
        $filterKabupatens = [];
        $filterKecamatans = [];
        $filterKelurahans = [];

        if ($currentLevel === 31) {
            $provPrefix = substr($userLoc, 0, 2);
            $filterKabupatens = \App\Models\Location::where('kode_lokasi', 'like', $provPrefix . '%')
                ->whereRaw("LENGTH(kode_lokasi) = 5")
                ->orderBy('nama_lokasi', 'asc')
                ->get();
        } elseif ($currentLevel === 3) {
            $provPrefix = substr($userLoc, 0, 2);
            $kabPrefix = substr($userLoc, 0, 5);
            $filterKabupatens = \App\Models\Location::where('kode_lokasi', $kabPrefix)->get();
            $filterKecamatans = \App\Models\Location::where('kode_lokasi', 'like', $kabPrefix . '%')
                ->whereRaw("LENGTH(kode_lokasi) = 8")
                ->orderBy('nama_lokasi', 'asc')
                ->get();
        } elseif ($currentLevel === 2) {
            $provPrefix = substr($userLoc, 0, 2);
            $kabPrefix = substr($userLoc, 0, 5);
            $kecPrefix = substr($userLoc, 0, 8);
            $filterKabupatens = \App\Models\Location::where('kode_lokasi', $kabPrefix)->get();
            $filterKecamatans = \App\Models\Location::where('kode_lokasi', $kecPrefix)->get();
            $filterKelurahans = \App\Models\Location::where('kode_lokasi', 'like', $kecPrefix . '%')
                ->whereRaw("LENGTH(kode_lokasi) = 13")
                ->orderBy('nama_lokasi', 'asc')
                ->get();
        }

        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'html' => view('lwp.dashboard.partials._laporan_table', compact('data', 'level', 'kodeLokasi', 'search'))->render(),
                'aggregates' => [
                    'totalAset' => number_format($totalAset, 0, ',', '.'),
                    'totalLuas' => number_format($totalLuas, 0, ',', '.'),
                    'totalWilayah' => number_format($totalWilayah, 0, ',', '.')
                ]
            ]);
        }

        return view('lwp.dashboard.laporan', compact(
            'data', 'level', 'kodeLokasi', 'search', 'totalAset', 'totalLuas', 'totalWilayah', 'provinces',
            'filterKabupatens', 'filterKecamatans', 'filterKelurahans', 'currentLevel'
        ));
    }

    public function exportLaporan(Request $request)
    {
        $currentUser = Auth::guard('lwp')->user();
        $currentLevel = (int) $currentUser->LEVEL;
        $userLoc = $currentUser->KODE_LOKASI;

        $kodeLokasi = trim($request->query('kode_lokasi'));
        $search = $request->query('search');
        $format = $request->query('format', 'excel');

        // Enforce role-based prefix scoping
        $scopingPrefix = '';
        if ($currentLevel === 31) {
            $scopingPrefix = substr($userLoc, 0, 2);
        } elseif ($currentLevel === 3) {
            $scopingPrefix = substr($userLoc, 0, 5);
        } elseif ($currentLevel === 2) {
            $scopingPrefix = substr($userLoc, 0, 8);
        }

        // Set default initial selection based on role
        if (empty($kodeLokasi)) {
            if ($currentLevel === 3) {
                $kodeLokasi = $scopingPrefix;
            } elseif ($currentLevel === 2) {
                $kodeLokasi = $scopingPrefix;
            }
        }

        // Validate that selected code is within scope
        if (!empty($scopingPrefix) && !empty($kodeLokasi)) {
            if (strpos($kodeLokasi, $scopingPrefix) !== 0) {
                $kodeLokasi = $scopingPrefix;
            }
        }

        if (empty($kodeLokasi)) {
            $level = 'prov';
            $parent = '';
        } elseif (strlen($kodeLokasi) == 2) {
            $level = 'kab';
            $parent = $kodeLokasi;
        } elseif (strlen($kodeLokasi) == 5) {
            $level = 'kec';
            $parent = $kodeLokasi;
        } elseif (strlen($kodeLokasi) == 8) {
            $level = 'kel';
            $parent = $kodeLokasi;
        } else {
            $level = 'kel';
            $parent = $kodeLokasi;
        }

        $cntTable = match($level) {
            'kab' => 'vw_jml_per_kab',
            'kec' => 'vw_jml_per_kec',
            'kel' => 'vw_jml_per_kel',
            default => 'vw_jml_per_prov'
        };
        
        $areaTable = match($level) {
            'kab' => 'vw_luas_per_kab',
            'kec' => 'vw_luas_per_kec',
            'kel' => 'vw_luas_per_kel',
            default => 'vw_luas_per_prov'
        };

        // Determine view location length
        $viewLocLen = match($level) {
            'kab' => 5,
            'kec' => 8,
            'kel' => 13,
            default => 2
        };

        // Scoping function helper
        $applyScoping = function($q, $column = 'KODE_LOKASI') use ($scopingPrefix, $viewLocLen) {
            if (empty($scopingPrefix)) return;
            $scopeLen = strlen($scopingPrefix);
            if ($viewLocLen <= $scopeLen) {
                $q->where($column, '=', substr($scopingPrefix, 0, $viewLocLen));
            } else {
                $q->where($column, 'like', $scopingPrefix . '%');
            }
        };

        $query = \DB::connection('mysql_no_prefix')
            ->table($cntTable . ' as c')
            ->leftJoin($areaTable . ' as a', 'c.KODE_LOKASI', '=', 'a.KODE_LOKASI')
            ->select(
                'c.KODE_LOKASI',
                'c.NAMA_LOKASI',
                \DB::raw('COALESCE(c.JUMLAH, 0) as JML_ASET'),
                \DB::raw('COALESCE(a.JUMLAH, 0) as JML_LUAS')
            );

        // Apply prefix scoping to query
        $applyScoping($query, 'c.KODE_LOKASI');

        if (!empty($parent)) {
            if (strlen($parent) == 13) {
                $query->where('c.KODE_LOKASI', $parent);
            } else {
                $query->where('c.KODE_LOKASI', 'like', $parent . '%');
            }
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('c.NAMA_LOKASI', 'like', '%' . $search . '%')
                  ->orWhere('c.KODE_LOKASI', 'like', '%' . $search . '%');
            });
        }

        $data = $query->orderBy('c.JUMLAH', 'desc')
            ->orderBy('c.KODE_LOKASI', 'asc')
            ->get();

        $levelLabel = match($level) {
            'kab' => 'Kabupaten_Kota',
            'kec' => 'Kecamatan',
            'kel' => 'Kelurahan',
            default => 'Provinsi'
        };

        // Resolve location hierarchy for report headers
        $selectedLokasi = [
            'provinsi' => null,
            'kabupaten' => null,
            'kecamatan' => null,
            'kelurahan' => null,
        ];
        $locationHierarchy = [];

        if (!empty($kodeLokasi)) {
            $codes = [];
            if (strlen($kodeLokasi) >= 2) {
                $codes['provinsi'] = substr($kodeLokasi, 0, 2);
            }
            if (strlen($kodeLokasi) >= 5) {
                $codes['kabupaten'] = substr($kodeLokasi, 0, 5);
            }
            if (strlen($kodeLokasi) >= 8) {
                $codes['kecamatan'] = substr($kodeLokasi, 0, 8);
            }
            if (strlen($kodeLokasi) >= 13) {
                $codes['kelurahan'] = substr($kodeLokasi, 0, 13);
            }

            $locRows = \App\Models\Location::whereIn('kode_lokasi', array_values($codes))
                ->pluck('nama_lokasi', 'kode_lokasi');

            foreach ($codes as $type => $code) {
                if (isset($locRows[$code])) {
                    $selectedLokasi[$type] = [
                        'kode' => $code,
                        'nama' => $locRows[$code],
                    ];
                    $locationHierarchy[] = $locRows[$code];
                }
            }
        }

        if (in_array($format, ['excel', 'xlsx'])) {
            $filename = "Laporan_Aset_Wakaf_Per_" . $levelLabel . "_" . date('Ymd_His') . ".xlsx";

            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle('Laporan Aset Wakaf');

            // Page Setup
            $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_PORTRAIT);
            $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);

            // Title Kop
            $sheet->setCellValue('A1', 'LEMBAGA WAKAF DAN PERTANAHAN NAHDLATUL ULAMA');
            $sheet->mergeCells('A1:E1');
            $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('006400'));
            $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

            $titleLaporan = "LAPORAN REKAPITULASI ASET WAKAF PER " . strtoupper(str_replace('_', '/', $levelLabel));
            $sheet->setCellValue('A2', $titleLaporan);
            $sheet->mergeCells('A2:E2');
            $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(11)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('B8860B'));
            $sheet->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

            // Metadata Wilayah & Tanggal
            $currentRow = 4;
            if (!empty($selectedLokasi['provinsi'])) {
                $sheet->setCellValue('A' . $currentRow, 'PROVINSI');
                $sheet->setCellValue('B' . $currentRow, ': ' . strtoupper($selectedLokasi['provinsi']['nama']));
                $sheet->getStyle('A' . $currentRow)->getFont()->setBold(true);
                $currentRow++;
            }
            if (!empty($selectedLokasi['kabupaten'])) {
                $sheet->setCellValue('A' . $currentRow, 'KABUPATEN / KOTA');
                $sheet->setCellValue('B' . $currentRow, ': ' . strtoupper($selectedLokasi['kabupaten']['nama']));
                $sheet->getStyle('A' . $currentRow)->getFont()->setBold(true);
                $currentRow++;
            }
            if (!empty($selectedLokasi['kecamatan'])) {
                $sheet->setCellValue('A' . $currentRow, 'KECAMATAN');
                $sheet->setCellValue('B' . $currentRow, ': ' . strtoupper($selectedLokasi['kecamatan']['nama']));
                $sheet->getStyle('A' . $currentRow)->getFont()->setBold(true);
                $currentRow++;
            }
            if (!empty($selectedLokasi['kelurahan'])) {
                $sheet->setCellValue('A' . $currentRow, 'KELURAHAN / DESA');
                $sheet->setCellValue('B' . $currentRow, ': ' . strtoupper($selectedLokasi['kelurahan']['nama']));
                $sheet->getStyle('A' . $currentRow)->getFont()->setBold(true);
                $currentRow++;
            }
            $sheet->setCellValue('A' . $currentRow, 'TANGGAL UNDUH');
            $sheet->setCellValue('B' . $currentRow, ': ' . date('d F Y H:i'));
            $sheet->getStyle('A' . $currentRow)->getFont()->setBold(true);

            $currentRow += 2; // spasi sebelum tabel

            // Header Tabel
            $headerRow = $currentRow;
            $sheet->setCellValue('A' . $headerRow, 'NO');
            $sheet->setCellValue('B' . $headerRow, 'KODE LOKASI');
            $sheet->setCellValue('C' . $headerRow, 'NAMA WILAYAH (' . strtoupper(str_replace('_', '/', $levelLabel)) . ')');
            $sheet->setCellValue('D' . $headerRow, 'JUMLAH ASET');
            $sheet->setCellValue('E' . $headerRow, 'TOTAL LUAS (M²)');

            $sheet->getStyle("A{$headerRow}:E{$headerRow}")->applyFromArray([
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                    'size' => 10,
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '006400'],
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
            ]);
            $sheet->getRowDimension($headerRow)->setRowHeight(26);

            $currentRow++;
            $dataStartRow = $currentRow;
            $totalAset = 0;
            $totalLuas = 0;

            foreach ($data as $index => $row) {
                $sheet->setCellValue('A' . $currentRow, $index + 1);
                $sheet->setCellValueExplicit('B' . $currentRow, (string) $row->KODE_LOKASI, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $sheet->setCellValue('C' . $currentRow, strtoupper($row->NAMA_LOKASI));
                $sheet->setCellValue('D' . $currentRow, (int) $row->JML_ASET);
                $sheet->setCellValue('E' . $currentRow, (float) $row->JML_LUAS);

                $totalAset += (int) $row->JML_ASET;
                $totalLuas += (float) $row->JML_LUAS;
                $currentRow++;
            }
            $dataEndRow = $currentRow - 1;

            // Total Row
            $totalRow = $currentRow;
            $sheet->setCellValue('A' . $totalRow, 'TOTAL KESELURUHAN:');
            $sheet->mergeCells("A{$totalRow}:C{$totalRow}");
            $sheet->setCellValue('D' . $totalRow, $totalAset);
            $sheet->setCellValue('E' . $totalRow, $totalLuas);

            // Styling Table Rows
            if ($dataEndRow >= $dataStartRow) {
                $sheet->getStyle("A{$dataStartRow}:A{$dataEndRow}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("B{$dataStartRow}:B{$dataEndRow}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("C{$dataStartRow}:C{$dataEndRow}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                $sheet->getStyle("D{$dataStartRow}:D{$dataEndRow}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle("E{$dataStartRow}:E{$dataEndRow}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle("D{$dataStartRow}:D{$dataEndRow}")->getNumberFormat()->setFormatCode('#,##0');
                $sheet->getStyle("E{$dataStartRow}:E{$dataEndRow}")->getNumberFormat()->setFormatCode('#,##0.00');
            }

            // Styling Total Row
            $sheet->getStyle("A{$totalRow}:E{$totalRow}")->applyFromArray([
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => '006400'],
                    'size' => 10.5,
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'F1F5F9'],
                ],
            ]);
            $sheet->getStyle("A{$totalRow}:C{$totalRow}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle("D{$totalRow}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle("E{$totalRow}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle("D{$totalRow}")->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getStyle("E{$totalRow}")->getNumberFormat()->setFormatCode('#,##0.00');
            $sheet->getRowDimension($totalRow)->setRowHeight(22);

            // Borders on entire table
            $sheet->getStyle("A{$headerRow}:E{$totalRow}")->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['rgb' => 'CBD5E1'],
                    ],
                ],
            ]);

            // Auto-size columns
            foreach (['A', 'B', 'C', 'D', 'E'] as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

            return response()->streamDownload(function() use ($writer) {
                $writer->save('php://output');
            }, $filename, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Cache-Control' => 'max-age=0',
            ]);
        }

        if ($format === 'pdf') {
            $title = "LAPORAN REKAPITULASI ASET WAKAF PER " . strtoupper(str_replace('_', '/', $levelLabel));
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('lwp.dashboard.pdf_laporan', compact('data', 'title', 'levelLabel', 'selectedLokasi', 'locationHierarchy'));
            
            return $pdf->download("Laporan_Aset_Wakaf_Per_" . $levelLabel . "_" . date('Ymd_His') . ".pdf");
        }

        return back();
    }

    public function exportManajemenWakaf(Request $request)
    {
        @ini_set('memory_limit', '1024M');
        @set_time_limit(300);

        $currentUser = Auth::guard('lwp')->user();
        $currentLevel = (int) $currentUser->LEVEL;
        $userLoc = $currentUser->KODE_LOKASI;

        $kabupaten = $request->input('kabupaten');
        $kecamatan = $request->input('kecamatan');
        $kelurahan = $request->input('kelurahan');
        $provinsi = $request->input('provinsi');

        // Validation rule:
        // When selected provinsi and kab/kota (or kecamatan, kelurahan), allow.
        // But if ONLY provinsi is selected (or nothing is selected) and not level 2 or 3, reject.
        $hasKabOrLower = !empty($kabupaten) || !empty($kecamatan) || !empty($kelurahan);
        $isScopedToKabOrLower = in_array($currentLevel, [3, 2]);

        if (!$hasKabOrLower && !$isScopedToKabOrLower) {
            abort(400, 'Pilihan minimal Kota / Kabupaten diperlukan untuk mengunduh laporan.');
        }

        $query = \DB::connection('mysql_no_prefix')
            ->table('vw_wakaf_detail')
            ->where('ISDEL', 0)
            ->whereIn('ISOK', [0, 1]);

        // Scoping based on user role level
        if ($currentLevel === 31) {
            $query->where('KODE_LOKASI', 'LIKE', substr($userLoc, 0, 2) . '%');
        } elseif ($currentLevel === 3) {
            $query->where('KODE_LOKASI', 'LIKE', substr($userLoc, 0, 5) . '%');
        } elseif ($currentLevel === 2) {
            $query->where('KODE_LOKASI', 'LIKE', substr($userLoc, 0, 8) . '%');
        }

        // Search Filters
        if ($request->filled('wakif')) {
            $query->where('WAKIF', 'LIKE', '%' . $request->wakif . '%');
        }
        if ($request->filled('penggunaan')) {
            $query->where('PENGGUNAAN', 'LIKE', '%' . $request->penggunaan . '%');
        }
        if ($request->filled('lokasi')) {
            $lokasi = $request->lokasi;
            $query->where(function($q) use ($lokasi) {
                $q->where('PROV', 'LIKE', '%' . $lokasi . '%')
                  ->orWhere('KAB', 'LIKE', '%' . $lokasi . '%')
                  ->orWhere('KEC', 'LIKE', '%' . $lokasi . '%')
                  ->orWhere('KEL', 'LIKE', '%' . $lokasi . '%');
            });
        }

        // Cascading Location Filters
        if ($request->filled('kelurahan')) {
            $query->where('KODE_LOKASI', 'LIKE', $request->kelurahan . '%');
        } elseif ($request->filled('kecamatan')) {
            $query->where('KODE_LOKASI', 'LIKE', $request->kecamatan . '%');
        } elseif ($request->filled('kabupaten')) {
            $query->where('KODE_LOKASI', 'LIKE', $request->kabupaten . '%');
        } elseif ($request->filled('provinsi')) {
            $query->where('KODE_LOKASI', 'LIKE', $request->provinsi . '%');
        }

        $items = $query->orderBy('ID_WAKAF', 'desc')->get();

        // Resolve location names hierarchy for metadata
        $selectedLokasi = [
            'provinsi' => null,
            'kabupaten' => null,
            'kecamatan' => null,
            'kelurahan' => null,
        ];

        $targetCode = $request->kelurahan ?: ($request->kecamatan ?: ($request->kabupaten ?: ($request->provinsi ?: '')));
        if (empty($targetCode) && in_array($currentLevel, [3, 2])) {
            $targetCode = substr($userLoc, 0, ($currentLevel === 3 ? 5 : 8));
        }

        if (!empty($targetCode)) {
            $codes = [];
            if (strlen($targetCode) >= 2) $codes['provinsi'] = substr($targetCode, 0, 2);
            if (strlen($targetCode) >= 5) $codes['kabupaten'] = substr($targetCode, 0, 5);
            if (strlen($targetCode) >= 8) $codes['kecamatan'] = substr($targetCode, 0, 8);
            if (strlen($targetCode) >= 13) $codes['kelurahan'] = substr($targetCode, 0, 13);

            $locRows = \App\Models\Location::whereIn('kode_lokasi', array_values($codes))
                ->pluck('nama_lokasi', 'kode_lokasi');

            foreach ($codes as $type => $c) {
                if (isset($locRows[$c])) {
                    $selectedLokasi[$type] = [
                        'kode' => $c,
                        'nama' => $locRows[$c]
                    ];
                }
            }
        }

        // Additional filter info text if any
        $filterParts = [];
        if ($request->filled('wakif')) $filterParts[] = 'Wakif: "' . $request->wakif . '"';
        if ($request->filled('penggunaan')) $filterParts[] = 'Penggunaan: "' . $request->penggunaan . '"';
        if ($request->filled('lokasi')) $filterParts[] = 'Kata Kunci: "' . $request->lokasi . '"';
        $filterInfo = !empty($filterParts) ? implode(', ', $filterParts) : null;

        $format = strtolower($request->query('format', 'excel'));

        if (in_array($format, ['excel', 'xlsx'])) {
            return $this->exportManajemenExcel($items, $selectedLokasi, $filterInfo);
        } else {
            return $this->exportManajemenPdf($items, $selectedLokasi, $filterInfo);
        }
    }

    protected function exportManajemenExcel($items, $selectedLokasi, $filterInfo = null)
    {
        $locLabel = !empty($selectedLokasi['kabupaten']) ? \Illuminate\Support\Str::slug($selectedLokasi['kabupaten']['nama'], '_') : 'Wilayah';
        $filename = "Daftar_Aset_Wakaf_" . $locLabel . "_" . date('Ymd_His') . ".xlsx";

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Aset Wakaf');

        // Page setup
        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
        $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);

        // Title Kop
        $sheet->setCellValue('A1', 'LEMBAGA WAKAF DAN PERTANAHAN NAHDLATUL ULAMA');
        $sheet->mergeCells('A1:J1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('006400'));
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue('A2', 'DAFTAR ASET WAKAF TANAH NAHDLATUL ULAMA');
        $sheet->mergeCells('A2:J2');
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(11)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('B8860B'));
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Metadata Wilayah & Info
        $currentRow = 4;
        if (!empty($selectedLokasi['provinsi'])) {
            $sheet->setCellValue('A' . $currentRow, 'PROVINSI');
            $sheet->setCellValue('B' . $currentRow, ': ' . strtoupper($selectedLokasi['provinsi']['nama']));
            $sheet->getStyle('A' . $currentRow)->getFont()->setBold(true);
            $currentRow++;
        }
        if (!empty($selectedLokasi['kabupaten'])) {
            $sheet->setCellValue('A' . $currentRow, 'KABUPATEN / KOTA');
            $sheet->setCellValue('B' . $currentRow, ': ' . strtoupper($selectedLokasi['kabupaten']['nama']));
            $sheet->getStyle('A' . $currentRow)->getFont()->setBold(true);
            $currentRow++;
        }
        if (!empty($selectedLokasi['kecamatan'])) {
            $sheet->setCellValue('A' . $currentRow, 'KECAMATAN');
            $sheet->setCellValue('B' . $currentRow, ': ' . strtoupper($selectedLokasi['kecamatan']['nama']));
            $sheet->getStyle('A' . $currentRow)->getFont()->setBold(true);
            $currentRow++;
        }
        if (!empty($selectedLokasi['kelurahan'])) {
            $sheet->setCellValue('A' . $currentRow, 'KELURAHAN / DESA');
            $sheet->setCellValue('B' . $currentRow, ': ' . strtoupper($selectedLokasi['kelurahan']['nama']));
            $sheet->getStyle('A' . $currentRow)->getFont()->setBold(true);
            $currentRow++;
        }
        $sheet->setCellValue('A' . $currentRow, 'TANGGAL UNDUH');
        $sheet->setCellValue('B' . $currentRow, ': ' . date('d F Y H:i') . ' WIB');
        $sheet->getStyle('A' . $currentRow)->getFont()->setBold(true);
        $currentRow++;

        $sheet->setCellValue('A' . $currentRow, 'TOTAL DATA');
        $sheet->setCellValue('B' . $currentRow, ': ' . number_format(count($items), 0, ',', '.') . ' Bidang Tanah');
        $sheet->getStyle('A' . $currentRow)->getFont()->setBold(true);

        if (!empty($filterInfo)) {
            $currentRow++;
            $sheet->setCellValue('A' . $currentRow, 'FILTER TAMBAHAN');
            $sheet->setCellValue('B' . $currentRow, ': ' . $filterInfo);
            $sheet->getStyle('A' . $currentRow)->getFont()->setBold(true);
        }

        $currentRow += 2; // spasi sebelum tabel

        // Header Tabel: 10 kolom persis sesuai permintaan
        // Alamat, kelurahan, kecamatan, wakif, nazhir, penggunaan, luas, no aiw, no sert
        $headerRow = $currentRow;
        $sheet->setCellValue('A' . $headerRow, 'NO');
        $sheet->setCellValue('B' . $headerRow, 'ALAMAT');
        $sheet->setCellValue('C' . $headerRow, 'KELURAHAN');
        $sheet->setCellValue('D' . $headerRow, 'KECAMATAN');
        $sheet->setCellValue('E' . $headerRow, 'WAKIF');
        $sheet->setCellValue('F' . $headerRow, 'NAZHIR');
        $sheet->setCellValue('G' . $headerRow, 'PENGGUNAAN');
        $sheet->setCellValue('H' . $headerRow, 'LUAS (M²)');
        $sheet->setCellValue('I' . $headerRow, 'NO. AIW');
        $sheet->setCellValue('J' . $headerRow, 'NO. SERTIFIKAT');

        $sheet->getStyle("A{$headerRow}:J{$headerRow}")->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 10,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '006400'],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ]);
        $sheet->getRowDimension($headerRow)->setRowHeight(26);

        // Data Rows
        $currentRow++;
        $dataStartRow = $currentRow;
        $totalLuas = 0;

        foreach ($items as $index => $row) {
            $luasVal = (float) ($row->LUAS ?? 0);
            $totalLuas += $luasVal;

            $sheet->setCellValue('A' . $currentRow, $index + 1);
            $sheet->setCellValue('B' . $currentRow, $row->ALAMAT ?: '-');
            $sheet->setCellValue('C' . $currentRow, $row->KEL ?: '-');
            $sheet->setCellValue('D' . $currentRow, $row->KEC ?: '-');
            $sheet->setCellValue('E' . $currentRow, $row->WAKIF ?: '-');
            $sheet->setCellValue('F' . $currentRow, $row->NADZIR ?: '-');
            $sheet->setCellValue('G' . $currentRow, $row->PENGGUNAAN ?: '-');
            $sheet->setCellValue('H' . $currentRow, $luasVal);
            $sheet->setCellValueExplicit('I' . $currentRow, $row->NO_AIW ?: '-', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('J' . $currentRow, $row->NO_SERT ?: '-', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);

            // Row styling
            $sheet->getStyle('A' . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('B' . $currentRow)->getAlignment()->setWrapText(true);
            $sheet->getStyle('C' . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            $sheet->getStyle('D' . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            $sheet->getStyle('E' . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            $sheet->getStyle('F' . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            $sheet->getStyle('G' . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            $sheet->getStyle('H' . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle('I' . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('J' . $currentRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

            // Subtle zebra striping
            if ($index % 2 === 1) {
                $sheet->getStyle("A{$currentRow}:J{$currentRow}")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('F8FAFC');
            }

            $currentRow++;
        }

        $dataEndRow = $currentRow - 1;
        $totalRow = $currentRow;

        if ($dataEndRow >= $dataStartRow) {
            $sheet->getStyle("H{$dataStartRow}:H{$dataEndRow}")->getNumberFormat()->setFormatCode('#,##0.00');
            
            // Total Row
            $sheet->setCellValue('A' . $totalRow, 'TOTAL LUAS:');
            $sheet->mergeCells("A{$totalRow}:G{$totalRow}");
            $sheet->setCellValue('H' . $totalRow, "=SUM(H{$dataStartRow}:H{$dataEndRow})");
            $sheet->setCellValue('I' . $totalRow, '-');
            $sheet->setCellValue('J' . $totalRow, '-');
        } else {
            // Empty data
            $sheet->setCellValue('A' . $totalRow, 'Tidak ada data aset wakaf yang sesuai dengan kriteria filter.');
            $sheet->mergeCells("A{$totalRow}:J{$totalRow}");
            $sheet->getStyle('A' . $totalRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('A' . $totalRow)->getFont()->setItalic(true);
        }

        // Styling Total Row
        $sheet->getStyle("A{$totalRow}:J{$totalRow}")->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => '006400'],
                'size' => 10,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'F1F5F9'],
            ],
        ]);
        $sheet->getStyle("A{$totalRow}:G{$totalRow}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle("H{$totalRow}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle("H{$totalRow}")->getNumberFormat()->setFormatCode('#,##0.00');
        $sheet->getStyle("I{$totalRow}:J{$totalRow}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getRowDimension($totalRow)->setRowHeight(22);

        // Borders on entire table
        $sheet->getStyle("A{$headerRow}:J{$totalRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => 'CBD5E1'],
                ],
            ],
        ]);

        // Auto-size columns
        foreach (['A', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J'] as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        $sheet->getColumnDimension('B')->setWidth(30); // Alamat width

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

        return response()->streamDownload(function() use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    protected function exportManajemenPdf($items, $selectedLokasi, $filterInfo = null)
    {
        $locLabel = !empty($selectedLokasi['kabupaten']) ? \Illuminate\Support\Str::slug($selectedLokasi['kabupaten']['nama'], '_') : 'Wilayah';
        $filename = "Daftar_Aset_Wakaf_" . $locLabel . "_" . date('Ymd_His') . ".pdf";

        $title = "DAFTAR ASET WAKAF TANAH NAHDLATUL ULAMA";
        if (!empty($selectedLokasi['kabupaten'])) {
            $title .= " " . strtoupper($selectedLokasi['kabupaten']['nama']);
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('lwp.dashboard.pdf_manajemen', compact(
            'items',
            'selectedLokasi',
            'filterInfo',
            'title'
        ))->setPaper('a4', 'landscape');

        return $pdf->download($filename);
    }

    public function petaSebaran()
    {
        if ((int) Auth::guard('lwp')->user()->LEVEL !== 9) {
            abort(403, 'Akses ditolak.');
        }

        return view('lwp.dashboard.peta_sebaran');
    }

    public function petaSebaranData(Request $request)
    {
        if ((int) Auth::guard('lwp')->user()->LEVEL !== 9) {
            abort(403, 'Akses ditolak.');
        }

        $level = $request->query('level', 'provinsi');
        $parentCode = $request->query('parent_code');

        $provinceCoordinates = [
            '11' => ['lat' => 4.6951, 'lng' => 96.7494], // Aceh
            '12' => ['lat' => 2.1154, 'lng' => 99.5451], // Sumatera Utara
            '13' => ['lat' => -0.7399, 'lng' => 100.8086], // Sumatera Barat
            '14' => ['lat' => 0.2933, 'lng' => 101.7068], // Riau
            '15' => ['lat' => -1.6186, 'lng' => 102.7753], // Jambi
            '16' => ['lat' => -3.3194, 'lng' => 104.9145], // Sumatera Selatan
            '17' => ['lat' => -3.7928, 'lng' => 102.2608], // Bengkulu
            '18' => ['lat' => -4.5586, 'lng' => 105.4000], // Lampung
            '19' => ['lat' => -2.7410, 'lng' => 106.4406], // Bangka Belitung
            '21' => ['lat' => 3.9456, 'lng' => 108.1428], // Kepulauan Riau
            '31' => ['lat' => -6.2088, 'lng' => 106.8456], // DKI Jakarta
            '32' => ['lat' => -7.0909, 'lng' => 107.6689], // Jawa Barat
            '33' => ['lat' => -7.1510, 'lng' => 110.1403], // Jawa Tengah
            '34' => ['lat' => -7.8754, 'lng' => 110.4262], // DIY
            '35' => ['lat' => -7.5361, 'lng' => 112.7050], // Jawa Timur
            '36' => ['lat' => -6.4058, 'lng' => 106.0605], // Banten
            '51' => ['lat' => -8.4095, 'lng' => 115.1889], // Bali
            '52' => ['lat' => -8.6529, 'lng' => 117.3616], // NTB
            '53' => ['lat' => -8.6574, 'lng' => 121.0794], // NTT
            '61' => ['lat' => -0.2788, 'lng' => 111.4753], // Kalimantan Barat
            '62' => ['lat' => -1.6815, 'lng' => 113.3824], // Kalimantan Tengah
            '63' => ['lat' => -3.0926, 'lng' => 115.2838], // Kalimantan Selatan
            '64' => ['lat' => -0.4500, 'lng' => 116.5000], // Kalimantan Timur
            '65' => ['lat' => 3.0731, 'lng' => 116.0414], // Kalimantan Utara
            '71' => ['lat' => 1.2731, 'lng' => 124.8481], // Sulawesi Utara
            '72' => ['lat' => -1.4300, 'lng' => 121.4456], // Sulawesi Tengah
            '73' => ['lat' => -3.6687, 'lng' => 119.9741], // Sulawesi Selatan
            '74' => ['lat' => -4.1449, 'lng' => 122.1746], // Sulawesi Tenggara
            '75' => ['lat' => 0.6999, 'lng' => 122.4467], // Gorontalo
            '76' => ['lat' => -2.8441, 'lng' => 119.2321], // Sulawesi Barat
            '81' => ['lat' => -3.2385, 'lng' => 130.1453], // Maluku
            '82' => ['lat' => 1.4839, 'lng' => 127.8087], // Maluku Utara
            '91' => ['lat' => -4.2699, 'lng' => 138.0803], // Papua
            '92' => ['lat' => -1.3361, 'lng' => 133.1747], // Papua Barat
        ];

        // Determine group length based on current level
        $groupLen = 2;
        if ($level === 'kabupaten') {
            $groupLen = 5;
        } elseif ($level === 'kecamatan') {
            $groupLen = 8;
        } elseif ($level === 'kelurahan') {
            $groupLen = 13;
        }

        $query = \DB::connection('mysql_no_prefix')->table('wk_wakaf')
            ->selectRaw("LEFT(KODE_LOKASI, {$groupLen}) as region_code, COUNT(ID_WAKAF) as total_wakaf, SUM(LUAS) as total_luas, AVG(CASE WHEN LAT != 0 AND LAT IS NOT NULL AND LAT BETWEEN -12 AND 10 THEN LAT END) as avg_lat, AVG(CASE WHEN LNG != 0 AND LNG IS NOT NULL AND LNG BETWEEN 94 AND 142 THEN LNG END) as avg_lng")
            ->where('ISDEL', 0);

        if ($parentCode) {
            $query->where('KODE_LOKASI', 'like', $parentCode . '%');
        }

        $activeAssets = $query->groupByRaw("LEFT(KODE_LOKASI, {$groupLen})")->get();

        // Get names from wk_lokasi
        $codes = $activeAssets->pluck('region_code')->toArray();
        $names = \DB::connection('mysql_no_prefix')->table('wk_lokasi')
            ->whereIn('kode_lokasi', $codes)
            ->pluck('nama_lokasi', 'kode_lokasi')
            ->toArray();

        $data = [];
        foreach ($activeAssets as $row) {
            $code = $row->region_code;
            $name = isset($names[$code]) ? $names[$code] : 'Wilayah Baru';
            
            // Coordinate determination
            $lat = $row->avg_lat;
            $lng = $row->avg_lng;

            $provCode = substr($code, 0, 2);

            // Fallbacks
            if ($level === 'provinsi') {
                if (isset($provinceCoordinates[$code])) {
                    $lat = $provinceCoordinates[$code]['lat'];
                    $lng = $provinceCoordinates[$code]['lng'];
                }
            }

            if (is_null($lat) || is_null($lng)) {
                // Determine base center from parent province
                $baseLat = isset($provinceCoordinates[$provCode]) ? $provinceCoordinates[$provCode]['lat'] : -2.5;
                $baseLng = isset($provinceCoordinates[$provCode]) ? $provinceCoordinates[$provCode]['lng'] : 118.0;

                // Consistent pseudo-random offset
                $seed = crc32($code);
                srand($seed);
                $latShift = (rand(-100, 100) / 1000.0);
                $lngShift = (rand(-100, 100) / 1000.0);

                // Add slightly larger shifts for deeper levels so they scatter around the center
                if ($level === 'kecamatan') {
                    $latShift *= 2.0;
                    $lngShift *= 2.0;
                } elseif ($level === 'kelurahan') {
                    $latShift *= 3.0;
                    $lngShift *= 3.0;
                }

                $lat = $baseLat + $latShift;
                $lng = $baseLng + $lngShift;
            }

            $data[] = [
                'code' => $code,
                'name' => $name,
                'lat' => (float)$lat,
                'lng' => (float)$lng,
                'total_wakaf' => (int)$row->total_wakaf,
                'total_luas' => (float)$row->total_luas,
            ];
        }

        return response()->json($data);
    }

    public function trash(Request $request)
    {
        $user = Auth::guard('lwp')->user();
        $level = (int) $user->LEVEL;
        $loc = $user->KODE_LOKASI;

        // Tab aktif
        $tab = $request->get('tab', 'wakaf');

        // === DELETED WAKAF ===
        $wakafQuery = \DB::connection('mysql_no_prefix')
            ->table('vw_wakaf_detail')
            ->where('ISDEL', 1);

        // Scoping
        if ($level === 31) {
            $wakafQuery->where('KODE_LOKASI', 'LIKE', substr($loc, 0, 2) . '%');
        } elseif ($level === 3) {
            $wakafQuery->where('KODE_LOKASI', 'LIKE', substr($loc, 0, 5) . '%');
        } elseif ($level === 2) {
            $wakafQuery->where('KODE_LOKASI', 'LIKE', substr($loc, 0, 8) . '%');
        }

        if ($request->search_wakaf) {
            $s = $request->search_wakaf;
            $wakafQuery->where(function($q) use ($s) {
                $q->where('PENGGUNAAN', 'LIKE', '%'.$s.'%')
                  ->orWhere('WAKIF', 'LIKE', '%'.$s.'%')
                  ->orWhere('NO_AIW', 'LIKE', '%'.$s.'%')
                  ->orWhere('NO_SERT', 'LIKE', '%'.$s.'%');
            });
        }

        $deletedWakaf = $wakafQuery->orderBy('ID_WAKAF', 'desc')->paginate(10, ['*'], 'wakaf_page');

        // === DELETED USERS ===
        $userQuery = \DB::connection('mysql_no_prefix')
            ->table('wk_user')
            ->where('ISDEL', 1);

        if ($request->search_user) {
            $s = $request->search_user;
            $userQuery->where(function($q) use ($s) {
                $q->where('NAMA_USER', 'LIKE', '%'.$s.'%')
                  ->orWhere('EMAIL_USER', 'LIKE', '%'.$s.'%')
                  ->orWhere('TELP_USER', 'LIKE', '%'.$s.'%');
            });
        }

        $deletedUsers = $userQuery->orderBy('ID_USER', 'desc')->paginate(10, ['*'], 'user_page');

        if ($request->ajax()) {
            if ($tab === 'users') {
                return view('lwp.dashboard.partials._trash_users', compact('deletedUsers'))->render();
            }
            return view('lwp.dashboard.partials._trash_wakaf', compact('deletedWakaf'))->render();
        }

        return view('lwp.dashboard.trash', compact('deletedWakaf', 'deletedUsers', 'tab'));
    }

    public function restoreWakaf($id)
    {
        $user = Auth::guard('lwp')->user();

        \DB::connection('mysql_no_prefix')
            ->table('wk_wakaf')
            ->where('ID_WAKAF', $id)
            ->update(['ISDEL' => 0]);

        \DB::connection('mysql_no_prefix')->table('wk_hisentri')->insert([
            'id_wakaf' => $id,
            'id_user' => $user->ID_USER,
            'tgl_entri' => now(),
            'fase' => 2
        ]);

        return response()->json(['success' => true]);
    }

    public function permanentDeleteWakaf($id)
    {
        \DB::connection('mysql_no_prefix')->table('wk_gambar')->where('ID_WAKAF', $id)->delete();
        \DB::connection('mysql_no_prefix')->table('wk_hisentri')->where('id_wakaf', $id)->delete();
        \DB::connection('mysql_no_prefix')->table('wk_wakaf')->where('ID_WAKAF', $id)->delete();

        return response()->json(['success' => true]);
    }

    public function restoreUser($id)
    {
        \DB::connection('mysql_no_prefix')
            ->table('wk_user')
            ->where('ID_USER', $id)
            ->update(['ISDEL' => 0]);

        return response()->json(['success' => true]);
    }

    public function permanentDeleteUser($id)
    {
        \DB::connection('mysql_no_prefix')->table('wk_user')->where('ID_USER', $id)->delete();

        return response()->json(['success' => true]);
    }
}
