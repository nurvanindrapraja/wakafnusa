<?php

namespace App\Http\Controllers\Lwp;

use App\Http\Controllers\Controller;
use App\Models\LwpUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    private function checkPermission()
    {
        $user = Auth::guard('lwp')->user();
        if (!$user) {
            abort(403, 'Unauthorized');
        }
        $level = (int)$user->LEVEL;
        if (!in_array($level, [9, 91, 31, 3])) {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk mengelola data pengguna.');
        }
        return $user;
    }

    public function index(Request $request)
    {
        $currentUser = $this->checkPermission();
        $currentLevel = (int)$currentUser->LEVEL;
        $search = $request->query('search');
        $kodeLokasi = trim($request->query('kode_lokasi'));

        $query = DB::connection('mysql_no_prefix')
            ->table('vw_user_detail')
            ->where('ISDEL', 0);

        // Apply scoping rules and location-based filters securely
        if ($currentLevel === 9) {
            // Admin (App) - see all, can apply any location filter
            if ($kodeLokasi) {
                $query->where('KODE_LOKASI', 'like', $kodeLokasi . '%');
            }
        } elseif ($currentLevel === 91) {
            // Admin (PBNU) - see all except Admin (App), can apply any location filter
            $query->where('LEVEL', '!=', 9);
            if ($kodeLokasi) {
                $query->where('KODE_LOKASI', 'like', $kodeLokasi . '%');
            }
        } elseif ($currentLevel === 31) {
            // Moderator (PWNU) - see within province, can filter within province (exclude Admin App and Admin PBNU)
            $query->whereNotIn('LEVEL', [9, 91]);
            $provPrefix = substr($currentUser->KODE_LOKASI, 0, 2);
            if ($kodeLokasi && strpos($kodeLokasi, $provPrefix) === 0) {
                $query->where('KODE_LOKASI', 'like', $kodeLokasi . '%');
            } else {
                $query->where('KODE_LOKASI', 'like', $provPrefix . '%');
            }
        } elseif ($currentLevel === 3) {
            // Moderator (PCNU) - see within regency, can filter within regency (exclude Admin App and Admin PBNU)
            $query->whereNotIn('LEVEL', [9, 91]);
            $kabPrefix = substr($currentUser->KODE_LOKASI, 0, 5);
            if ($kodeLokasi && strpos($kodeLokasi, $kabPrefix) === 0) {
                $query->where('KODE_LOKASI', 'like', $kodeLokasi . '%');
            } else {
                $query->where('KODE_LOKASI', 'like', $kabPrefix . '%');
            }
        }

        // Apply Search
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('NAMA_USER', 'like', '%' . $search . '%')
                  ->orWhere('USERNAME_USER', 'like', '%' . $search . '%')
                  ->orWhere('EMAIL_USER', 'like', '%' . $search . '%')
                  ->orWhere('NAMA_CABANG', 'like', '%' . $search . '%')
                  ->orWhere('NAMA_LEVEL', 'like', '%' . $search . '%');
            });
        }

        $users = $query->orderBy('ID_USER', 'desc')->paginate(10)->withQueryString();

        // Get Levels list according to role permission (hide PEMROSES and PENERIMA)
        $levelsQuery = DB::connection('mysql_no_prefix')
            ->table('wk_level')
            ->where('NAMA_LEVEL', 'NOT LIKE', 'PEMROSES%')
            ->where('NAMA_LEVEL', 'NOT LIKE', 'PENERIMA%')
            ->orderBy('URUTAN', 'asc');

        if ($currentLevel === 91) {
            $levelsQuery->where('LEVEL', '!=', 9);
        } elseif ($currentLevel === 31) {
            $levelsQuery->whereNotIn('LEVEL', [9, 91]);
        } elseif ($currentLevel === 3) {
            $levelsQuery->whereNotIn('LEVEL', [9, 91, 31]);
        }
        $levels = $levelsQuery->get();

        // Get Branches
        $cabangs = DB::connection('mysql_no_prefix')
            ->table('wk_cabang')
            ->orderBy('NAMA_CABANG', 'asc')
            ->get();

        // Load all provinces for initial select cascade
        $provinces = DB::connection('mysql_no_prefix')
            ->table('wk_lokasi')
            ->whereRaw('LENGTH(kode_lokasi) = 2')
            ->orderBy('nama_lokasi', 'asc')
            ->get();

        // Load hierarchical filters lists depending on user level
        $filterProvinces = [];
        $filterKabupatens = [];
        $filterKecamatans = [];

        if ($currentLevel === 9 || $currentLevel === 91) {
            $filterProvinces = $provinces;
        } elseif ($currentLevel === 31) {
            $provPrefix = substr($currentUser->KODE_LOKASI, 0, 2);
            $filterKabupatens = DB::connection('mysql_no_prefix')
                ->table('wk_lokasi')
                ->whereRaw('LENGTH(kode_lokasi) = 5')
                ->where('kode_lokasi', 'like', $provPrefix . '%')
                ->orderBy('nama_lokasi', 'asc')
                ->get();
        } elseif ($currentLevel === 3) {
            $kabPrefix = substr($currentUser->KODE_LOKASI, 0, 5);
            $filterKecamatans = DB::connection('mysql_no_prefix')
                ->table('wk_lokasi')
                ->whereRaw('LENGTH(kode_lokasi) = 8')
                ->where('kode_lokasi', 'like', $kabPrefix . '%')
                ->orderBy('nama_lokasi', 'asc')
                ->get();
        }

        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'html' => view('lwp.user.partials._user_table', compact('users'))->render()
            ]);
        }

        return view('lwp.user.index', compact('users', 'levels', 'cabangs', 'provinces', 'search', 'currentUser', 'filterProvinces', 'filterKabupatens', 'filterKecamatans', 'kodeLokasi'));
    }

    public function show($id)
    {
        $currentUser = $this->checkPermission();
        $currentLevel = (int)$currentUser->LEVEL;

        $user = DB::connection('mysql_no_prefix')
            ->table('vw_user_detail')
            ->where('ID_USER', $id)
            ->where('ISDEL', 0)
            ->first();

        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'Data pengguna tidak ditemukan atau sudah dihapus.'], 404);
        }

        // Verify scoping permissions on this specific target user
        $targetLevel = (int)$user->LEVEL;
        if ($currentLevel === 9) {
            // unrestricted
        } elseif ($currentLevel === 91) {
            if ($targetLevel === 9) {
                return response()->json(['status' => 'error', 'message' => 'Anda tidak memiliki akses untuk melihat data Admin (App).'], 403);
            }
        } elseif ($currentLevel === 31) {
            if (in_array($targetLevel, [9, 91])) {
                return response()->json(['status' => 'error', 'message' => 'Akses ditolak.'], 403);
            }
            $provPrefix = substr($currentUser->KODE_LOKASI, 0, 2);
            if (substr($user->KODE_LOKASI, 0, 2) !== $provPrefix) {
                return response()->json(['status' => 'error', 'message' => 'Akses dibatasi. Pengguna berada di luar wilayah Anda.'], 403);
            }
        } elseif ($currentLevel === 3) {
            if (in_array($targetLevel, [9, 91, 31])) {
                return response()->json(['status' => 'error', 'message' => 'Akses ditolak.'], 403);
            }
            $kabPrefix = substr($currentUser->KODE_LOKASI, 0, 5);
            if (substr($user->KODE_LOKASI, 0, 5) !== $kabPrefix) {
                return response()->json(['status' => 'error', 'message' => 'Akses dibatasi. Pengguna berada di luar cabang Anda.'], 403);
            }
        }

        // Return both target user detail and location details if editing
        $provinceCode = substr($user->KODE_LOKASI, 0, 2);
        $kabupatenCode = strlen($user->KODE_LOKASI) >= 5 ? substr($user->KODE_LOKASI, 0, 5) : '';
        $kecamatanCode = strlen($user->KODE_LOKASI) >= 8 ? substr($user->KODE_LOKASI, 0, 8) : '';
        $kelurahanCode = strlen($user->KODE_LOKASI) >= 13 ? $user->KODE_LOKASI : '';

        return response()->json([
            'status' => 'success',
            'data' => $user,
            'location_codes' => [
                'province' => $provinceCode,
                'kabupaten' => $kabupatenCode,
                'kecamatan' => $kecamatanCode,
                'kelurahan' => $kelurahanCode,
            ]
        ]);
    }

    public function store(Request $request)
    {
        $currentUser = $this->checkPermission();
        $currentLevel = (int)$currentUser->LEVEL;

        $request->validate([
            'nama_user' => 'required|string|max:45',
            'username_user' => 'required|string|max:45',
            'email_user' => 'required|email|max:100',
            'password_user' => 'required|string|min:6',
            'level' => 'required|integer',
        ]);

        // Validate username uniqueness (excluding soft-deleted)
        $usernameExists = DB::connection('mysql_no_prefix')
            ->table('wk_user')
            ->where('USERNAME_USER', $request->username_user)
            ->where('ISDEL', 0)
            ->exists();

        if ($usernameExists) {
            return response()->json(['status' => 'error', 'message' => 'Username sudah digunakan.'], 422);
        }

        // Validate email uniqueness (excluding soft-deleted)
        $emailExists = DB::connection('mysql_no_prefix')
            ->table('wk_user')
            ->where('EMAIL_USER', $request->email_user)
            ->where('ISDEL', 0)
            ->exists();

        if ($emailExists) {
            return response()->json(['status' => 'error', 'message' => 'Email sudah digunakan.'], 422);
        }

        // Check if level chosen is within current user's permitted scoping levels
        $targetLevel = (int)$request->level;
        if ($currentLevel === 9) {
            // OK
        } elseif ($currentLevel === 91) {
            if ($targetLevel === 9) {
                return response()->json(['status' => 'error', 'message' => 'Anda tidak diizinkan membuat pengguna Admin (App).'], 403);
            }
        } elseif ($currentLevel === 31) {
            if (in_array($targetLevel, [9, 91])) {
                return response()->json(['status' => 'error', 'message' => 'Anda tidak diizinkan membuat pengguna setingkat PBNU atau App.'], 403);
            }
        } elseif ($currentLevel === 3) {
            if (in_array($targetLevel, [9, 91, 31])) {
                return response()->json(['status' => 'error', 'message' => 'Anda tidak diizinkan membuat pengguna setingkat PWNU ke atas.'], 403);
            }
        }

        // Determine deepest selected location code
        $kodeLokasi = $request->kelurahan ?: $request->kecamatan ?: $request->kabupaten ?: $request->provinsi ?: '';

        // If PWNU/PCNU, verify that the saved location is within their scoping prefix
        if ($currentLevel === 31) {
            $provPrefix = substr($currentUser->KODE_LOKASI, 0, 2);
            if (empty($kodeLokasi) || substr($kodeLokasi, 0, 2) !== $provPrefix) {
                return response()->json(['status' => 'error', 'message' => 'Wilayah pengguna baru harus berada di dalam cakupan provinsi Anda.'], 403);
            }
        } elseif ($currentLevel === 3) {
            $kabPrefix = substr($currentUser->KODE_LOKASI, 0, 5);
            if (empty($kodeLokasi) || substr($kodeLokasi, 0, 5) !== $kabPrefix) {
                return response()->json(['status' => 'error', 'message' => 'Wilayah pengguna baru harus berada di dalam cakupan cabang Anda.'], 403);
            }
        }

        $userId = DB::connection('mysql_no_prefix')->table('wk_user')->insertGetId([
            'NAMA_USER' => $request->nama_user,
            'USERNAME_USER' => $request->username_user,
            'PASSWORD_USER' => sha1($request->password_user),
            'EMAIL_USER' => $request->email_user,
            'TELP_USER' => $request->telp_user,
            'LEVEL' => $request->level,
            'ID_CABANG' => $request->id_cabang,
            'KODE_LOKASI' => $kodeLokasi ?: null,
            'NO_KARTANU' => $request->no_kartanu,
            'ALAMAT_USER' => $request->alamat_user,
            'ISDEL' => 0
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Data pengguna berhasil ditambahkan.'
        ]);
    }

    public function update(Request $request, $id)
    {
        $currentUser = $this->checkPermission();
        $currentLevel = (int)$currentUser->LEVEL;

        $request->validate([
            'nama_user' => 'required|string|max:45',
            'username_user' => 'required|string|max:45',
            'email_user' => 'required|email|max:100',
            'level' => 'required|integer',
        ]);

        $existingUser = DB::connection('mysql_no_prefix')
            ->table('wk_user')
            ->where('ID_USER', $id)
            ->where('ISDEL', 0)
            ->first();

        if (!$existingUser) {
            return response()->json(['status' => 'error', 'message' => 'Data pengguna tidak ditemukan.'], 404);
        }

        // Verify edit scoping permissions on target user
        $targetLevel = (int)$existingUser->LEVEL;
        if ($currentLevel === 9) {
            // OK
        } elseif ($currentLevel === 91) {
            if ($targetLevel === 9 || (int)$request->level === 9) {
                return response()->json(['status' => 'error', 'message' => 'Anda tidak diizinkan mengubah level/data pengguna menjadi Admin (App).'], 403);
            }
        } elseif ($currentLevel === 31) {
            if (in_array($targetLevel, [9, 91]) || in_array((int)$request->level, [9, 91])) {
                return response()->json(['status' => 'error', 'message' => 'Akses ditolak.'], 403);
            }
            $provPrefix = substr($currentUser->KODE_LOKASI, 0, 2);
            if (substr($existingUser->KODE_LOKASI, 0, 2) !== $provPrefix) {
                return response()->json(['status' => 'error', 'message' => 'Akses dibatasi. Pengguna berada di luar wilayah Anda.'], 403);
            }
        } elseif ($currentLevel === 3) {
            if (in_array($targetLevel, [9, 91, 31]) || in_array((int)$request->level, [9, 91, 31])) {
                return response()->json(['status' => 'error', 'message' => 'Akses ditolak.'], 403);
            }
            $kabPrefix = substr($currentUser->KODE_LOKASI, 0, 5);
            if (substr($existingUser->KODE_LOKASI, 0, 5) !== $kabPrefix) {
                return response()->json(['status' => 'error', 'message' => 'Akses dibatasi. Pengguna berada di luar cabang Anda.'], 403);
            }
        }

        // Validate username uniqueness (excluding this user)
        $usernameExists = DB::connection('mysql_no_prefix')
            ->table('wk_user')
            ->where('USERNAME_USER', $request->username_user)
            ->where('ID_USER', '!=', $id)
            ->where('ISDEL', 0)
            ->exists();

        if ($usernameExists) {
            return response()->json(['status' => 'error', 'message' => 'Username sudah digunakan oleh akun lain.'], 422);
        }

        // Validate email uniqueness (excluding this user)
        $emailExists = DB::connection('mysql_no_prefix')
            ->table('wk_user')
            ->where('EMAIL_USER', $request->email_user)
            ->where('ID_USER', '!=', $id)
            ->where('ISDEL', 0)
            ->exists();

        if ($emailExists) {
            return response()->json(['status' => 'error', 'message' => 'Email sudah digunakan oleh akun lain.'], 422);
        }

        // Determine deepest selected location code
        $kodeLokasi = $request->kelurahan ?: $request->kecamatan ?: $request->kabupaten ?: $request->provinsi ?: '';

        // If PWNU/PCNU, verify that the updated location remains within their scoping prefix
        if ($currentLevel === 31) {
            $provPrefix = substr($currentUser->KODE_LOKASI, 0, 2);
            if (empty($kodeLokasi) || substr($kodeLokasi, 0, 2) !== $provPrefix) {
                return response()->json(['status' => 'error', 'message' => 'Wilayah pengguna harus berada di dalam cakupan provinsi Anda.'], 403);
            }
        } elseif ($currentLevel === 3) {
            $kabPrefix = substr($currentUser->KODE_LOKASI, 0, 5);
            if (empty($kodeLokasi) || substr($kodeLokasi, 0, 5) !== $kabPrefix) {
                return response()->json(['status' => 'error', 'message' => 'Wilayah pengguna harus berada di dalam cakupan cabang Anda.'], 403);
            }
        }

        DB::connection('mysql_no_prefix')
            ->table('wk_user')
            ->where('ID_USER', $id)
            ->update([
                'NAMA_USER' => $request->nama_user,
                'USERNAME_USER' => $request->username_user,
                'EMAIL_USER' => $request->email_user,
                'TELP_USER' => $request->telp_user,
                'LEVEL' => $request->level,
                'ID_CABANG' => $request->id_cabang,
                'KODE_LOKASI' => $kodeLokasi ?: null,
                'NO_KARTANU' => $request->no_kartanu,
                'ALAMAT_USER' => $request->alamat_user,
            ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Data pengguna berhasil diperbarui.'
        ]);
    }

    public function destroy($id)
    {
        $currentUser = $this->checkPermission();
        $currentLevel = (int)$currentUser->LEVEL;

        $user = DB::connection('mysql_no_prefix')
            ->table('wk_user')
            ->where('ID_USER', $id)
            ->where('ISDEL', 0)
            ->first();

        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'Data pengguna tidak ditemukan.'], 404);
        }

        // Verify delete scoping permissions
        $targetLevel = (int)$user->LEVEL;
        if ($currentLevel === 9) {
            // OK
        } elseif ($currentLevel === 91) {
            if ($targetLevel === 9) {
                return response()->json(['status' => 'error', 'message' => 'Anda tidak diizinkan menghapus pengguna Admin (App).'], 403);
            }
        } elseif ($currentLevel === 31) {
            if (in_array($targetLevel, [9, 91])) {
                return response()->json(['status' => 'error', 'message' => 'Akses ditolak.'], 403);
            }
            $provPrefix = substr($currentUser->KODE_LOKASI, 0, 2);
            if (substr($user->KODE_LOKASI, 0, 2) !== $provPrefix) {
                return response()->json(['status' => 'error', 'message' => 'Akses dibatasi. Pengguna berada di luar wilayah Anda.'], 403);
            }
        } elseif ($currentLevel === 3) {
            if (in_array($targetLevel, [9, 91, 31])) {
                return response()->json(['status' => 'error', 'message' => 'Akses ditolak.'], 403);
            }
            $kabPrefix = substr($currentUser->KODE_LOKASI, 0, 5);
            if (substr($user->KODE_LOKASI, 0, 5) !== $kabPrefix) {
                return response()->json(['status' => 'error', 'message' => 'Akses dibatasi. Pengguna berada di luar cabang Anda.'], 403);
            }
        }

        // Soft delete: ISDEL = 1
        DB::connection('mysql_no_prefix')
            ->table('wk_user')
            ->where('ID_USER', $id)
            ->update(['ISDEL' => 1]);

        return response()->json([
            'status' => 'success',
            'message' => 'Data pengguna berhasil dihapus.'
        ]);
    }

    public function resetPassword(Request $request, $id)
    {
        $currentUser = $this->checkPermission();
        $currentLevel = (int)$currentUser->LEVEL;

        $request->validate([
            'password' => 'required|string|min:6',
        ]);

        $user = DB::connection('mysql_no_prefix')
            ->table('wk_user')
            ->where('ID_USER', $id)
            ->where('ISDEL', 0)
            ->first();

        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'Data pengguna tidak ditemukan.'], 404);
        }

        // Verify reset password scoping permissions
        $targetLevel = (int)$user->LEVEL;
        if ($currentLevel === 9) {
            // OK
        } elseif ($currentLevel === 91) {
            if ($targetLevel === 9) {
                return response()->json(['status' => 'error', 'message' => 'Anda tidak diizinkan mengubah password pengguna Admin (App).'], 403);
            }
        } elseif ($currentLevel === 31) {
            if (in_array($targetLevel, [9, 91])) {
                return response()->json(['status' => 'error', 'message' => 'Akses ditolak.'], 403);
            }
            $provPrefix = substr($currentUser->KODE_LOKASI, 0, 2);
            if (substr($user->KODE_LOKASI, 0, 2) !== $provPrefix) {
                return response()->json(['status' => 'error', 'message' => 'Akses dibatasi. Pengguna berada di luar wilayah Anda.'], 403);
            }
        } elseif ($currentLevel === 3) {
            if (in_array($targetLevel, [9, 91, 31])) {
                return response()->json(['status' => 'error', 'message' => 'Akses ditolak.'], 403);
            }
            $kabPrefix = substr($currentUser->KODE_LOKASI, 0, 5);
            if (substr($user->KODE_LOKASI, 0, 5) !== $kabPrefix) {
                return response()->json(['status' => 'error', 'message' => 'Akses dibatasi. Pengguna berada di luar cabang Anda.'], 403);
            }
        }

        DB::connection('mysql_no_prefix')
            ->table('wk_user')
            ->where('ID_USER', $id)
            ->update([
                'PASSWORD_USER' => sha1($request->password)
            ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Password pengguna berhasil di-reset.'
        ]);
    }

    public function getCabang(Request $request)
    {
        $parent = trim($request->query('parent'));
        if (!$parent) {
            return response()->json([]);
        }

        $query = DB::connection('mysql_no_prefix')
            ->table('wk_cabang');

        if (strlen($parent) === 2) {
            $query->where('KODE_CABANG', 'like', $parent . '%');
        } else {
            $kabPrefix = substr($parent, 0, 5);
            $query->where('KODE_CABANG', '=', $kabPrefix);
        }

        $cabangs = $query->orderBy('NAMA_CABANG', 'asc')->get();

        return response()->json($cabangs);
    }
}
