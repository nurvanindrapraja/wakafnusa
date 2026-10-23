<?php

namespace App\Http\Controllers\Lwp;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class LokasiController extends Controller
{
    /**
     * Check if current user is Admin APP (9) or Admin PBNU (91)
     */
    private function checkPermission()
    {
        $user = Auth::guard('lwp')->user();
        if (!$user || !in_array((int)$user->LEVEL, [9, 91])) {
            abort(403, 'Akses Terbatas: Hanya Admin PBNU atau Admin APP yang dapat mengelola Master Lokasi.');
        }
    }

    /**
     * Display a listing of locations
     */
    public function index(Request $request)
    {
        $this->checkPermission();

        $search = trim($request->query('search', ''));
        $level = trim($request->query('level', ''));
        $filterProv = trim($request->query('provinsi', ''));
        $filterKab = trim($request->query('kabupaten', ''));
        $filterKec = trim($request->query('kecamatan', ''));

        // Query locations
        $query = Location::query();

        // Level filtering
        if ($level) {
            $length = match($level) {
                'provinsi' => 2,
                'kabupaten' => 5,
                'kecamatan' => 8,
                'kelurahan' => 13,
                default => 0,
            };
            if ($length > 0) {
                $query->whereRaw('LENGTH(kode_lokasi) = ?', [$length]);
            }
        }

        // Region hierarchy filtering
        if ($filterKec) {
            $query->where('kode_lokasi', 'like', $filterKec . '.%');
        } elseif ($filterKab) {
            $query->where('kode_lokasi', 'like', $filterKab . '.%');
        } elseif ($filterProv) {
            $query->where('kode_lokasi', 'like', $filterProv . '.%');
        }

        // Search filtering (name or code)
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('nama_lokasi', 'like', '%' . $search . '%')
                  ->orWhere('kode_lokasi', 'like', $search . '%');
            });
        }

        // Sort by kode_lokasi
        $locations = $query->orderBy('kode_lokasi', 'asc')->paginate(25)->withQueryString();

        // Collect parent codes for all items on current page to load breadcrumbs in 1 batch query
        $parentCodes = [];
        $pageCodes = [];
        foreach ($locations as $loc) {
            $pageCodes[] = $loc->kode_lokasi;
            $len = strlen($loc->kode_lokasi);
            if ($len === 13) {
                $parentCodes[] = substr($loc->kode_lokasi, 0, 8); // Kec
                $parentCodes[] = substr($loc->kode_lokasi, 0, 5); // Kab
                $parentCodes[] = substr($loc->kode_lokasi, 0, 2); // Prov
            } elseif ($len === 8) {
                $parentCodes[] = substr($loc->kode_lokasi, 0, 5); // Kab
                $parentCodes[] = substr($loc->kode_lokasi, 0, 2); // Prov
            } elseif ($len === 5) {
                $parentCodes[] = substr($loc->kode_lokasi, 0, 2); // Prov
            }
        }

        $parentMap = [];
        if (!empty($parentCodes)) {
            $uniqueParents = array_unique($parentCodes);
            $parentMap = Location::whereIn('kode_lokasi', $uniqueParents)
                ->pluck('nama_lokasi', 'kode_lokasi')
                ->toArray();
        }

        // Check asset count for each location on this page
        $assetCounts = [];
        if (!empty($pageCodes)) {
            $assetCounts = DB::connection('mysql_no_prefix')->table('wk_wakaf')
                ->whereIn('KODE_LOKASI', $pageCodes)
                ->where('ISDEL', 0)
                ->groupBy('KODE_LOKASI')
                ->selectRaw('KODE_LOKASI, count(*) as total')
                ->pluck('total', 'KODE_LOKASI')
                ->toArray();
        }

        // Attach resolved metadata to each location object
        foreach ($locations as $loc) {
            $len = strlen($loc->kode_lokasi);
            $loc->asset_count = $assetCounts[$loc->kode_lokasi] ?? 0;
            
            $hierarchy = [];
            if ($len === 13) {
                $pProv = substr($loc->kode_lokasi, 0, 2);
                $pKab = substr($loc->kode_lokasi, 0, 5);
                $pKec = substr($loc->kode_lokasi, 0, 8);
                if (isset($parentMap[$pProv])) $hierarchy[] = $parentMap[$pProv];
                if (isset($parentMap[$pKab])) $hierarchy[] = $parentMap[$pKab];
                if (isset($parentMap[$pKec])) $hierarchy[] = $parentMap[$pKec];
            } elseif ($len === 8) {
                $pProv = substr($loc->kode_lokasi, 0, 2);
                $pKab = substr($loc->kode_lokasi, 0, 5);
                if (isset($parentMap[$pProv])) $hierarchy[] = $parentMap[$pProv];
                if (isset($parentMap[$pKab])) $hierarchy[] = $parentMap[$pKab];
            } elseif ($len === 5) {
                $pProv = substr($loc->kode_lokasi, 0, 2);
                if (isset($parentMap[$pProv])) $hierarchy[] = $parentMap[$pProv];
            }
            $loc->parent_hierarchy = !empty($hierarchy) ? implode(' › ', $hierarchy) : '-';
        }

        // Summary stats (cached for 10 minutes)
        $stats = Cache::remember('master_lokasi_summary_stats', 600, function () {
            $counts = DB::connection('mysql_no_prefix')
                ->select('SELECT LENGTH(kode_lokasi) as len, COUNT(*) as cnt FROM wk_lokasi GROUP BY LENGTH(kode_lokasi)');
            
            $statData = [
                'total' => 0,
                'provinsi' => 0,
                'kabupaten' => 0,
                'kecamatan' => 0,
                'kelurahan' => 0,
            ];

            foreach ($counts as $row) {
                $statData['total'] += (int)$row->cnt;
                if ($row->len == 2) $statData['provinsi'] = (int)$row->cnt;
                elseif ($row->len == 5) $statData['kabupaten'] = (int)$row->cnt;
                elseif ($row->len == 8) $statData['kecamatan'] = (int)$row->cnt;
                elseif ($row->len == 13) $statData['kelurahan'] = (int)$row->cnt;
            }

            return $statData;
        });

        // Load dropdown options for cascading filters
        $provinces = Location::provinsi()->orderBy('nama_lokasi', 'asc')->get();
        $kabupatens = $filterProv ? Location::kabupaten($filterProv)->orderBy('nama_lokasi', 'asc')->get() : collect();
        $kecamatans = $filterKab ? Location::kecamatan($filterKab)->orderBy('nama_lokasi', 'asc')->get() : collect();

        if ($request->ajax() || $request->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'html' => view('lwp.lokasi.partials._table', compact('locations'))->render(),
                'pagination' => (string) $locations->links('lwp.lokasi.partials._pagination'),
                'total' => $locations->total(),
            ]);
        }

        return view('lwp.lokasi.index', compact(
            'locations',
            'stats',
            'provinces',
            'kabupatens',
            'kecamatans',
            'filterProv',
            'filterKab',
            'filterKec',
            'level',
            'search'
        ));
    }

    /**
     * Show detail of a location
     */
    public function show($kode_lokasi)
    {
        $this->checkPermission();

        $location = Location::where('kode_lokasi', $kode_lokasi)->first();
        if (!$location) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data lokasi tidak ditemukan.'
            ], 404);
        }

        $childCount = Location::where('kode_lokasi', 'like', $kode_lokasi . '.%')->count();
        $wakafCount = DB::connection('mysql_no_prefix')->table('wk_wakaf')
            ->where('KODE_LOKASI', 'like', $kode_lokasi . '%')
            ->where('ISDEL', 0)
            ->count();
        $userCount = DB::connection('mysql_no_prefix')->table('wk_user')
            ->where('KODE_LOKASI', 'like', $kode_lokasi . '%')
            ->where('ISDEL', 0)
            ->count();

        // Get Parent names
        $parents = [];
        $len = strlen($kode_lokasi);
        if ($len > 2) {
            $provCode = substr($kode_lokasi, 0, 2);
            $prov = Location::where('kode_lokasi', $provCode)->first();
            if ($prov) $parents['provinsi'] = $prov->nama_lokasi;
        }
        if ($len > 5) {
            $kabCode = substr($kode_lokasi, 0, 5);
            $kab = Location::where('kode_lokasi', $kabCode)->first();
            if ($kab) $parents['kabupaten'] = $kab->nama_lokasi;
        }
        if ($len > 8) {
            $kecCode = substr($kode_lokasi, 0, 8);
            $kec = Location::where('kode_lokasi', $kecCode)->first();
            if ($kec) $parents['kecamatan'] = $kec->nama_lokasi;
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'kode_lokasi' => $location->kode_lokasi,
                'nama_lokasi' => $location->nama_lokasi,
                'level' => $location->level,
                'level_label' => $location->level_label,
                'parent_code' => $location->parent_code,
                'parents' => $parents,
                'child_count' => $childCount,
                'wakaf_count' => $wakafCount,
                'user_count' => $userCount,
            ]
        ]);
    }

    /**
     * Suggest next available code for a parent region
     */
    public function suggestCode(Request $request)
    {
        $this->checkPermission();

        $level = $request->query('level');
        $parent = trim($request->query('parent', ''));

        if ($level === 'provinsi') {
            // Suggest next 2-digit code
            $maxProv = Location::whereRaw('LENGTH(kode_lokasi) = 2')
                ->whereRaw("kode_lokasi REGEXP '^[0-9]+$'")
                ->max('kode_lokasi');
            $next = $maxProv ? (int)$maxProv + 1 : 11;
            return response()->json([
                'status' => 'success',
                'parent' => '',
                'suffix' => (string)$next,
                'full_code' => (string)$next,
            ]);
        }

        if (!$parent) {
            return response()->json([
                'status' => 'error',
                'message' => 'Parent lokasi wajib dipilih.'
            ], 422);
        }

        if ($level === 'kabupaten') {
            // parent is prov (e.g. 35) -> format 35.XX
            $existing = Location::where('kode_lokasi', 'like', $parent . '.%')
                ->whereRaw('LENGTH(kode_lokasi) = 5')
                ->pluck('kode_lokasi');

            $maxSuffix = 0;
            foreach ($existing as $c) {
                $parts = explode('.', $c);
                if (isset($parts[1]) && is_numeric($parts[1])) {
                    $val = (int)$parts[1];
                    if ($val > $maxSuffix) $maxSuffix = $val;
                }
            }
            $nextSuffix = str_pad($maxSuffix + 1, 2, '0', STR_PAD_LEFT);
            return response()->json([
                'status' => 'success',
                'parent' => $parent,
                'suffix' => $nextSuffix,
                'full_code' => $parent . '.' . $nextSuffix,
            ]);
        }

        if ($level === 'kecamatan') {
            // parent is kab (e.g. 35.22) -> format 35.22.XX
            $existing = Location::where('kode_lokasi', 'like', $parent . '.%')
                ->whereRaw('LENGTH(kode_lokasi) = 8')
                ->pluck('kode_lokasi');

            $maxSuffix = 0;
            foreach ($existing as $c) {
                $parts = explode('.', $c);
                if (isset($parts[2]) && is_numeric($parts[2])) {
                    $val = (int)$parts[2];
                    if ($val > $maxSuffix) $maxSuffix = $val;
                }
            }
            $nextSuffix = str_pad($maxSuffix + 1, 2, '0', STR_PAD_LEFT);
            return response()->json([
                'status' => 'success',
                'parent' => $parent,
                'suffix' => $nextSuffix,
                'full_code' => $parent . '.' . $nextSuffix,
            ]);
        }

        if ($level === 'kelurahan') {
            // parent is kec (e.g. 35.22.01) -> format 35.22.01.XXXX (usually 2001, 2002, or 1001)
            $existing = Location::where('kode_lokasi', 'like', $parent . '.%')
                ->whereRaw('LENGTH(kode_lokasi) = 13')
                ->pluck('kode_lokasi');

            $maxSuffix = 2000;
            foreach ($existing as $c) {
                $parts = explode('.', $c);
                if (isset($parts[3]) && is_numeric($parts[3])) {
                    $val = (int)$parts[3];
                    if ($val > $maxSuffix) $maxSuffix = $val;
                }
            }
            $nextSuffix = (string)($maxSuffix + 1);
            return response()->json([
                'status' => 'success',
                'parent' => $parent,
                'suffix' => $nextSuffix,
                'full_code' => $parent . '.' . $nextSuffix,
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Level tidak valid.'
        ], 422);
    }

    /**
     * Store a newly created location
     */
    public function store(Request $request)
    {
        $this->checkPermission();

        $request->validate([
            'level' => 'required|in:provinsi,kabupaten,kecamatan,kelurahan',
            'nama_lokasi' => 'required|string|max:100',
            'kode_suffix' => 'required|string',
            'parent_kode' => 'nullable|string',
        ]);

        $level = $request->level;
        $namaLokasi = strtoupper(trim($request->nama_lokasi));
        $suffix = trim($request->kode_suffix);
        $parent = trim($request->parent_kode ?? '');

        // Compose full kode_lokasi
        if ($level === 'provinsi') {
            $kodeLokasi = $suffix;
            if (strlen($kodeLokasi) !== 2) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Kode provinsi harus terdiri dari 2 karakter (contoh: 35).'
                ], 422);
            }
        } else {
            if (!$parent) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Wilayah induk (parent) wajib dipilih.'
                ], 422);
            }
            $kodeLokasi = $parent . '.' . $suffix;
        }

        // Validate lengths
        $expectedLength = match($level) {
            'provinsi' => 2,
            'kabupaten' => 5,
            'kecamatan' => 8,
            'kelurahan' => 13,
        };

        if (strlen($kodeLokasi) !== $expectedLength) {
            return response()->json([
                'status' => 'error',
                'message' => "Format kode lokasi tidak sesuai. Panjang kode untuk tingkat $level harus $expectedLength karakter."
            ], 422);
        }

        // Check duplicate
        if (Location::where('kode_lokasi', $kodeLokasi)->exists()) {
            return response()->json([
                'status' => 'error',
                'message' => "Kode lokasi '$kodeLokasi' sudah terdaftar dalam sistem."
            ], 422);
        }

        // Insert
        Location::create([
            'kode_lokasi' => $kodeLokasi,
            'nama_lokasi' => $namaLokasi,
        ]);

        Cache::forget('master_lokasi_summary_stats');

        if ($request->ajax() || $request->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => "Data lokasi '$namaLokasi' ($kodeLokasi) berhasil ditambahkan.",
                'data' => [
                    'kode_lokasi' => $kodeLokasi,
                    'nama_lokasi' => $namaLokasi,
                ]
            ]);
        }

        return redirect()->route('lwp.lokasi.index')->with('success', "Lokasi $namaLokasi berhasil ditambahkan.");
    }

    /**
     * Update the specified location
     */
    public function update(Request $request, $kode_lokasi)
    {
        $this->checkPermission();

        $location = Location::where('kode_lokasi', $kode_lokasi)->first();
        if (!$location) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data lokasi tidak ditemukan.'
            ], 404);
        }

        $request->validate([
            'nama_lokasi' => 'required|string|max:100',
            'new_kode_lokasi' => 'nullable|string',
        ]);

        $namaLokasi = strtoupper(trim($request->nama_lokasi));
        $newKode = trim($request->new_kode_lokasi ?? $kode_lokasi);

        // If kode_lokasi changed
        if ($newKode !== $kode_lokasi) {
            if (strlen($newKode) !== strlen($kode_lokasi)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Panjang kode lokasi baru harus sama dengan tingkat wilayah aslinya.'
                ], 422);
            }

            if (Location::where('kode_lokasi', $newKode)->exists()) {
                return response()->json([
                    'status' => 'error',
                    'message' => "Kode lokasi '$newKode' sudah digunakan oleh wilayah lain."
                ], 422);
            }

            // Begin transaction for cascaded update
            DB::connection('mysql_no_prefix')->transaction(function () use ($location, $kode_lokasi, $newKode, $namaLokasi) {
                // Update primary record
                $location->kode_lokasi = $newKode;
                $location->nama_lokasi = $namaLokasi;
                $location->save();

                // Cascade update sub-locations
                $subLocations = Location::where('kode_lokasi', 'like', $kode_lokasi . '.%')->get();
                foreach ($subLocations as $sub) {
                    $subNewCode = $newKode . substr($sub->kode_lokasi, strlen($kode_lokasi));
                    $sub->kode_lokasi = $subNewCode;
                    $sub->save();
                }

                // Cascade update related wakaf assets
                DB::connection('mysql_no_prefix')->table('wk_wakaf')
                    ->where('KODE_LOKASI', $kode_lokasi)
                    ->update(['KODE_LOKASI' => $newKode]);

                // Cascade update related users
                DB::connection('mysql_no_prefix')->table('wk_user')
                    ->where('KODE_LOKASI', $kode_lokasi)
                    ->update(['KODE_LOKASI' => $newKode]);
            });
        } else {
            $location->nama_lokasi = $namaLokasi;
            $location->save();
        }

        Cache::forget('master_lokasi_summary_stats');

        if ($request->ajax() || $request->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => "Data lokasi '$namaLokasi' berhasil diperbarui.",
                'data' => [
                    'kode_lokasi' => $newKode,
                    'nama_lokasi' => $namaLokasi,
                ]
            ]);
        }

        return redirect()->route('lwp.lokasi.index')->with('success', "Data lokasi $namaLokasi berhasil diperbarui.");
    }

    /**
     * Remove the specified location with integrity checks
     */
    public function destroy(Request $request, $kode_lokasi)
    {
        $this->checkPermission();

        $location = Location::where('kode_lokasi', $kode_lokasi)->first();
        if (!$location) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data lokasi tidak ditemukan.'
            ], 404);
        }

        // Safety 1: Check child locations
        $childCount = Location::where('kode_lokasi', 'like', $kode_lokasi . '.%')->count();
        if ($childCount > 0) {
            return response()->json([
                'status' => 'error',
                'message' => "Lokasi ini tidak dapat dihapus karena masih memiliki $childCount sub-wilayah di bawahnya. Hapus seluruh sub-wilayah terlebih dahulu."
            ], 422);
        }

        // Safety 2: Check wakaf assets
        $wakafCount = DB::connection('mysql_no_prefix')->table('wk_wakaf')
            ->where('KODE_LOKASI', $kode_lokasi)
            ->where('ISDEL', 0)
            ->count();
        if ($wakafCount > 0) {
            return response()->json([
                'status' => 'error',
                'message' => "Lokasi ini tidak dapat dihapus karena masih terhubung dengan $wakafCount aset tanah wakaf aktif."
            ], 422);
        }

        // Safety 3: Check users
        $userCount = DB::connection('mysql_no_prefix')->table('wk_user')
            ->where('KODE_LOKASI', $kode_lokasi)
            ->where('ISDEL', 0)
            ->count();
        if ($userCount > 0) {
            return response()->json([
                'status' => 'error',
                'message' => "Lokasi ini tidak dapat dihapus karena masih ditugaskan kepada $userCount akun pengguna."
            ], 422);
        }

        $nama = $location->nama_lokasi;
        $location->delete();

        Cache::forget('master_lokasi_summary_stats');

        if ($request->ajax() || $request->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => "Lokasi '$nama' ($kode_lokasi) berhasil dihapus."
            ]);
        }

        return redirect()->route('lwp.lokasi.index')->with('success', "Lokasi $nama berhasil dihapus.");
    }
}
