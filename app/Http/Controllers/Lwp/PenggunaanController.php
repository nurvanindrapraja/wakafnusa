<?php

namespace App\Http\Controllers\Lwp;

use App\Http\Controllers\Controller;
use App\Models\Usage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PenggunaanController extends Controller
{
    /**
     * Pastikan hanya Admin APP (9) dan Admin PBNU (91) yang dapat mengakses
     */
    private function checkPermission()
    {
        $user = Auth::guard('lwp')->user();
        if (!$user) {
            abort(403, 'Unauthorized');
        }

        $level = (int)$user->LEVEL;
        if (!in_array($level, [9, 91])) {
            abort(403, 'Akses ditolak. Menu Master Penggunaan hanya dapat diakses oleh Admin PBNU atau Admin APP.');
        }

        return $user;
    }

    /**
     * Daftar preset ikon penggunaan
     */
    public static function getAvailableIcons(): array
    {
        return [
            'mosque.png'        => 'Tempat Ibadah (Masjid / Musholla)',
            'school.png'        => 'Pendidikan (Sekolah / Madrasah)',
            'university.png'    => 'Pendidikan Tinggi / Pesantren',
            'hospital.png'      => 'Kesehatan (Rumah Sakit)',
            'doctor.png'        => 'Kesehatan (Klinik / Dokter)',
            'nursery.png'       => 'Kesehatan (RSIA / Bersalin)',
            'family.png'        => 'Sosial (Panti Asuhan / Yayasan)',
            'park.png'          => 'Fasilitas Umum / Lapangan',
            'cluster2.png'      => 'Makam',
            'hotel.png'         => 'Monumen / Museum / Hotel',
            'shoppingmall.png'  => 'Usaha (Toko / Supermarket)',
            'workoffice.png'    => 'Perkantoran',
            'golf.png'          => 'Lahan (Kebun / Sawah / Kosong)',
            'apartment.png'     => 'Gedung Serbaguna',
            'recycle.png'       => 'Lain-lain / Wakaf',
            'bookstore.png'     => 'TPQ / Perpustakaan',
            'daycare.png'       => 'PAUD / TK',
            'expert.png'        => 'Tidak Disebutkan',
        ];
    }

    /**
     * Tampilkan daftar master penggunaan
     */
    public function index(Request $request)
    {
        $currentUser = $this->checkPermission();

        $search = trim($request->query('search', ''));
        $kategori = trim($request->query('kategori', ''));
        $status = trim($request->query('status', 'active'));

        $connection = 'mysql_no_prefix';

        $query = DB::connection($connection)->table('wk_penggunaan');

        // Filter status
        if ($status === 'active') {
            $query->where('ISDEL', 0);
        } elseif ($status === 'trash') {
            $query->where('ISDEL', 1);
        }

        // Filter kategori utama (contoh: 11, 12, dll)
        if ($kategori !== '') {
            $query->where(function ($q) use ($kategori) {
                $q->where('KODE_PENG', $kategori)
                  ->orWhere('KODE_PENG', 'like', $kategori . '.%');
            });
        }

        // Filter search
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('PENGGUNAAN', 'like', "%{$search}%")
                  ->orWhere('KODE_PENG', 'like', "%{$search}%")
                  ->orWhere('KODE_PENG_OLD', 'like', "%{$search}%");
            });
        }

        // Urutkan berdasarkan KODE_PENG secara numerik & leksikografis
        $driver = DB::connection($connection)->getDriverName();
        if ($driver === 'mysql') {
            $query->orderByRaw("CAST(SUBSTRING_INDEX(KODE_PENG, '.', 1) AS UNSIGNED) ASC, CAST(SUBSTRING_INDEX(KODE_PENG, '.', -1) AS UNSIGNED) ASC, KODE_PENG ASC");
        } else {
            $query->orderBy('KODE_PENG', 'ASC');
        }

        $usages = $query->paginate(15)
            ->withQueryString();

        // Ambil data jumlah wakaf terkait per kode penggunaan
        $wakafCounts = DB::connection($connection)->table('wk_wakaf')
            ->where('ISDEL', 0)
            ->selectRaw('KODE_PENG, COUNT(*) as total')
            ->groupBy('KODE_PENG')
            ->pluck('total', 'KODE_PENG')
            ->toArray();

        // Kategori utama untuk dropdown filter dan form
        $parentCatQuery = DB::connection($connection)->table('wk_penggunaan')
            ->where('ISDEL', 0)
            ->where('KODE_PENG', 'not like', '%.%');
        if ($driver === 'mysql') {
            $parentCatQuery->orderByRaw('CAST(KODE_PENG AS UNSIGNED) ASC');
        } else {
            $parentCatQuery->orderBy('KODE_PENG', 'ASC');
        }
        $parentCategories = $parentCatQuery->get();

        // Statistik ringkasan
        $stats = [
            'total'     => DB::connection($connection)->table('wk_penggunaan')->count(),
            'active'    => DB::connection($connection)->table('wk_penggunaan')->where('ISDEL', 0)->count(),
            'trash'     => DB::connection($connection)->table('wk_penggunaan')->where('ISDEL', 1)->count(),
            'parents'   => DB::connection($connection)->table('wk_penggunaan')->where('ISDEL', 0)->where('KODE_PENG', 'not like', '%.%')->count(),
            'subs'      => DB::connection($connection)->table('wk_penggunaan')->where('ISDEL', 0)->where('KODE_PENG', 'like', '%.%')->count(),
        ];

        $availableIcons = self::getAvailableIcons();

        if ($request->ajax()) {
            return view('lwp.penggunaan.partials._table', compact(
                'usages', 'wakafCounts', 'parentCategories', 'currentUser'
            ))->render();
        }

        return view('lwp.penggunaan.index', compact(
            'usages', 'wakafCounts', 'parentCategories', 'currentUser',
            'search', 'kategori', 'status', 'stats', 'availableIcons'
        ));
    }

    /**
     * Ambil detail data satu penggunaan (untuk modal edit)
     */
    public function show($kode_peng)
    {
        $this->checkPermission();

        $connection = 'mysql_no_prefix';
        $item = DB::connection($connection)->table('wk_penggunaan')
            ->where('KODE_PENG', $kode_peng)
            ->first();

        if (!$item) {
            return response()->json(['status' => 'error', 'message' => 'Data penggunaan tidak ditemukan.'], 404);
        }

        $wakafCount = DB::connection($connection)->table('wk_wakaf')
            ->where('KODE_PENG', $kode_peng)
            ->where('ISDEL', 0)
            ->count();

        return response()->json([
            'status' => 'success',
            'data' => $item,
            'wakaf_count' => $wakafCount,
        ]);
    }

    /**
     * Simpan data penggunaan baru
     */
    public function store(Request $request)
    {
        $this->checkPermission();

        $request->validate([
            'kode_peng'     => 'required|string|max:8',
            'penggunaan'    => 'required|string|max:40',
            'kode_peng_old' => 'nullable|string|max:3',
            'icon'          => 'nullable|string|max:25',
        ]);

        $connection = 'mysql_no_prefix';
        $kodePeng = strtoupper(trim($request->kode_peng));

        // Cek apakah kode sudah digunakan
        $exists = DB::connection($connection)->table('wk_penggunaan')
            ->where('KODE_PENG', $kodePeng)
            ->exists();

        if ($exists) {
            $msg = "Kode penggunaan '{$kodePeng}' sudah terdaftar dalam sistem.";
            if ($request->ajax() || $request->expectsJson()) {
                return response()->json(['status' => 'error', 'message' => $msg], 422);
            }
            return back()->withInput()->with('error', $msg);
        }

        DB::connection($connection)->table('wk_penggunaan')->insert([
            'KODE_PENG'     => $kodePeng,
            'PENGGUNAAN'    => strtoupper(trim($request->penggunaan)),
            'KODE_PENG_OLD' => $request->filled('kode_peng_old') ? trim($request->kode_peng_old) : null,
            'ICON'          => $request->filled('icon') ? trim($request->icon) : null,
            'ISDEL'         => 0,
        ]);

        $successMsg = "Data penggunaan '{$request->penggunaan}' ({$kodePeng}) berhasil ditambahkan.";

        if ($request->ajax() || $request->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => $successMsg,
            ]);
        }

        return redirect()->route('lwp.penggunaan.index')->with('success', $successMsg);
    }

    /**
     * Perbarui data penggunaan
     */
    public function update(Request $request, $kode_peng)
    {
        $this->checkPermission();

        $request->validate([
            'kode_peng'     => 'required|string|max:8',
            'penggunaan'    => 'required|string|max:40',
            'kode_peng_old' => 'nullable|string|max:3',
            'icon'          => 'nullable|string|max:25',
        ]);

        $connection = 'mysql_no_prefix';

        $existing = DB::connection($connection)->table('wk_penggunaan')
            ->where('KODE_PENG', $kode_peng)
            ->first();

        if (!$existing) {
            $msg = 'Data penggunaan tidak ditemukan.';
            if ($request->ajax() || $request->expectsJson()) {
                return response()->json(['status' => 'error', 'message' => $msg], 404);
            }
            return back()->with('error', $msg);
        }

        $newKode = strtoupper(trim($request->kode_peng));

        // Jika kode diubah, pastikan kode baru belum terpakai
        if ($newKode !== $kode_peng) {
            $isDuplicate = DB::connection($connection)->table('wk_penggunaan')
                ->where('KODE_PENG', $newKode)
                ->where('KODE_PENG', '!=', $kode_peng)
                ->exists();

            if ($isDuplicate) {
                $msg = "Kode penggunaan '{$newKode}' sudah digunakan oleh data lain.";
                if ($request->ajax() || $request->expectsJson()) {
                    return response()->json(['status' => 'error', 'message' => $msg], 422);
                }
                return back()->withInput()->with('error', $msg);
            }

            // Sinkronkan kode baru pada data wakaf terkait jika kode diubah
            DB::connection($connection)->table('wk_wakaf')
                ->where('KODE_PENG', $kode_peng)
                ->update(['KODE_PENG' => $newKode]);
        }

        DB::connection($connection)->table('wk_penggunaan')
            ->where('KODE_PENG', $kode_peng)
            ->update([
                'KODE_PENG'     => $newKode,
                'PENGGUNAAN'    => strtoupper(trim($request->penggunaan)),
                'KODE_PENG_OLD' => $request->filled('kode_peng_old') ? trim($request->kode_peng_old) : null,
                'ICON'          => $request->filled('icon') ? trim($request->icon) : null,
            ]);

        $successMsg = "Data penggunaan '{$request->penggunaan}' ({$newKode}) berhasil diperbarui.";

        if ($request->ajax() || $request->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => $successMsg,
            ]);
        }

        return redirect()->route('lwp.penggunaan.index')->with('success', $successMsg);
    }

    /**
     * Hapus data penggunaan (Soft Delete atau Force Delete)
     */
    public function destroy(Request $request, $kode_peng)
    {
        $this->checkPermission();

        $connection = 'mysql_no_prefix';

        $existing = DB::connection($connection)->table('wk_penggunaan')
            ->where('KODE_PENG', $kode_peng)
            ->first();

        if (!$existing) {
            return response()->json(['status' => 'error', 'message' => 'Data penggunaan tidak ditemukan.'], 404);
        }

        $wakafCount = DB::connection($connection)->table('wk_wakaf')
            ->where('KODE_PENG', $kode_peng)
            ->where('ISDEL', 0)
            ->count();

        $force = filter_var($request->query('force', false), FILTER_VALIDATE_BOOLEAN);

        if ($force) {
            if ($wakafCount > 0) {
                return response()->json([
                    'status' => 'error',
                    'message' => "Tidak dapat menghapus permanen. Kode '{$kode_peng}' masih digunakan oleh {$wakafCount} data tanah wakaf aktif."
                ], 422);
            }

            DB::connection($connection)->table('wk_penggunaan')
                ->where('KODE_PENG', $kode_peng)
                ->delete();

            return response()->json([
                'status' => 'success',
                'message' => "Data penggunaan '{$existing->PENGGUNAAN}' ({$kode_peng}) telah dihapus permanen."
            ]);
        }

        // Soft delete: update ISDEL = 1
        DB::connection($connection)->table('wk_penggunaan')
            ->where('KODE_PENG', $kode_peng)
            ->update(['ISDEL' => 1]);

        return response()->json([
            'status' => 'success',
            'message' => "Data penggunaan '{$existing->PENGGUNAAN}' ({$kode_peng}) dipindahkan ke sampah."
        ]);
    }

    /**
     * Pulihkan data penggunaan dari status sampah
     */
    public function restore($kode_peng)
    {
        $this->checkPermission();

        $connection = 'mysql_no_prefix';

        $existing = DB::connection($connection)->table('wk_penggunaan')
            ->where('KODE_PENG', $kode_peng)
            ->first();

        if (!$existing) {
            return response()->json(['status' => 'error', 'message' => 'Data penggunaan tidak ditemukan.'], 404);
        }

        DB::connection($connection)->table('wk_penggunaan')
            ->where('KODE_PENG', $kode_peng)
            ->update(['ISDEL' => 0]);

        return response()->json([
            'status' => 'success',
            'message' => "Data penggunaan '{$existing->PENGGUNAAN}' ({$kode_peng}) berhasil dipulihkan."
        ]);
    }
}
