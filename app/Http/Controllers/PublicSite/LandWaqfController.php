<?php

namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
use App\Models\LandWaqf;
use App\Models\Location;
use App\Models\Usage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LandWaqfController extends Controller
{
    public function index(Request $request)
    {
        $query = LandWaqf::where('ISOK', 1)
            ->where('ISDEL', 0);

        // Text Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('ALAMAT', 'like', "%{$search}%")
                  ->orWhere('KEL', 'like', "%{$search}%")
                  ->orWhere('KEC', 'like', "%{$search}%")
                  ->orWhere('KAB', 'like', "%{$search}%")
                  ->orWhere('PROV', 'like', "%{$search}%")
                  ->orWhere('WAKIF', 'like', "%{$search}%")
                  ->orWhere('NO_AIW', 'like', "%{$search}%")
                  ->orWhere('NO_SERT', 'like', "%{$search}%");
            });
        }

        // Usage Filter
        if ($request->filled('penggunaan')) {
            $query->where('KODE_PENG', 'like', $request->penggunaan . '%');
        }

        // Location Filters
        if ($request->filled('kelurahan')) {
            $query->where('KODE_LOKASI', $request->kelurahan);
        } elseif ($request->filled('kecamatan')) {
            $query->where('KODE_LOKASI', 'like', $request->kecamatan . '%');
        } elseif ($request->filled('kabupaten')) {
            $query->where('KODE_LOKASI', 'like', $request->kabupaten . '%');
        } elseif ($request->filled('provinsi')) {
            $query->where('KODE_LOKASI', 'like', $request->provinsi . '%');
        }

        $items = $query->orderBy('ID_WAKAF', 'desc')->paginate(15)->withQueryString();

        // Data for Filters
        $provinces = Location::whereRaw('LENGTH(kode_lokasi) = 2')->orderBy('nama_lokasi')->get();
        $usages = Usage::whereRaw('LENGTH(KODE_PENG) = 2')->orderBy('PENGGUNAAN')->get();
        
        $cities = collect();
        if ($request->filled('provinsi')) {
            $cities = Location::whereRaw('LENGTH(kode_lokasi) = 5')
                ->where('kode_lokasi', 'like', $request->provinsi . '%')
                ->orderBy('nama_lokasi')->get();
        }

        $districts = collect();
        if ($request->filled('kabupaten')) {
            $districts = Location::whereRaw('LENGTH(kode_lokasi) = 8')
                ->where('kode_lokasi', 'like', $request->kabupaten . '%')
                ->orderBy('nama_lokasi')->get();
        }

        $villages = collect();
        if ($request->filled('kecamatan')) {
            $villages = Location::whereRaw('LENGTH(kode_lokasi) = 13')
                ->where('kode_lokasi', 'like', $request->kecamatan . '%')
                ->orderBy('nama_lokasi')->get();
        }

        if ($request->ajax()) {
            return view('public.land-waqf.partials._card_list', compact('items'))->render();
        }

        return view('public.land-waqf.index', compact('items', 'provinces', 'cities', 'districts', 'villages', 'usages'));
    }

    public function statistics()
    {
        $baseQuery = LandWaqf::where('ISOK', 1)->where('ISDEL', 0);

        $totalCount = $baseQuery->count();
        $totalArea = $baseQuery->sum('LUAS');

        // Usage Composition from vw_jml_peng (only 2 digits)
        $usageComposition = DB::connection('mysql_no_prefix')
            ->table('vw_jml_peng')
            ->whereRaw('LENGTH(KODE_PENG) = 2')
            ->orderBy('JUMLAH', 'desc')
            ->get();

        // Province breakdown from views
        $provinceBreakdown = DB::connection('mysql_no_prefix')
            ->table('vw_jml_per_prov as j')
            ->join('vw_luas_per_prov as l', 'j.KODE_LOKASI', '=', 'l.KODE_LOKASI')
            ->select('j.NAMA_LOKASI as label', 'j.KODE_LOKASI as kode', 'j.JUMLAH as count', 'l.JUMLAH as area')
            ->orderBy('j.JUMLAH', 'desc')
            ->get();

        $topProvinces = $provinceBreakdown->take(10);

        return view('public.land-waqf.statistics', compact('totalCount', 'totalArea', 'usageComposition', 'provinceBreakdown', 'topProvinces'));
    }

    public function getBreakdown(Request $request)
    {
        $parentCode = $request->parent_code;
        $level = (int)$request->level; // 2, 5, 8
        
        $target = match($level) {
            2 => ['jml' => 'vw_jml_per_kab', 'luas' => 'vw_luas_per_kab'],
            5 => ['jml' => 'vw_jml_per_kec', 'luas' => 'vw_luas_per_kec'],
            8 => ['jml' => 'vw_jml_per_kel', 'luas' => 'vw_luas_per_kel'],
            default => null
        };

        if (!$target) return response()->json([]);

        $data = DB::connection('mysql_no_prefix')
            ->table($target['jml'] . ' as j')
            ->join($target['luas'] . ' as l', 'j.KODE_LOKASI', '=', 'l.KODE_LOKASI')
            ->select('j.NAMA_LOKASI as label', 'j.KODE_LOKASI as kode', 'j.JUMLAH as count', 'l.JUMLAH as area')
            ->where('j.KODE_LOKASI', 'like', $parentCode . '%')
            ->orderBy('j.JUMLAH', 'desc')
            ->get();

        return response()->json($data);
    }

    public function show($id)
    {
        $item = LandWaqf::where('ISOK', 1)
            ->where('ISDEL', 0)
            ->findOrFail($id);

        return view('public.land-waqf.show', compact('item'));
    }

    public function verifyWaqf($nia)
    {
        $item = DB::connection('mysql_no_prefix')
            ->table('vw_wakaf_detail as vw')
            ->leftJoin('wk_wakaf as wk', 'vw.ID_WAKAF', '=', 'wk.ID_WAKAF')
            ->leftJoin('wk_kriteria as k', 'wk.ID_KRITERIA', '=', 'k.ID_KRITERIA')
            ->where('wk.NIA', $nia)
            ->where('wk.ISOK', 1)
            ->where('wk.ISDEL', 0)
            ->select('vw.*', 'wk.NIA', 'k.KRITERIA')
            ->first();

        if (!$item) {
            return abort(404, 'Data wakaf tidak ditemukan atau belum terverifikasi');
        }

        return view('public.land-waqf.verify', compact('item'));
    }

    public function getSubLocations(Request $request)
    {
        $parent = $request->parent;
        $length = $request->length;

        $locations = Location::where('kode_lokasi', 'like', $parent . '%')
            ->whereRaw("LENGTH(kode_lokasi) = $length")
            ->orderBy('nama_lokasi')
            ->get();

        return response()->json($locations);
    }
}
