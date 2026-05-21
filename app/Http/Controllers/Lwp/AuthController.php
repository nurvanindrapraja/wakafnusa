<?php

namespace App\Http\Controllers\Lwp;

use App\Http\Controllers\Controller;
use App\Models\LwpUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::guard('lwp')->check()) {
            return redirect()->route('lwp.dashboard');
        }
        return view('lwp.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $user = LwpUser::where('USERNAME_USER', $request->username)
            ->where('PASSWORD_USER', sha1($request->password))
            ->where('ISDEL', 0)
            ->first();

        if ($user) {
            if (is_null($user->LEVEL)) {
                return back()->with('info', 'Akun belum aktif. Silakan cek email anda untuk proses verifikasi.');
            }

            Auth::guard('lwp')->login($user);
            $request->session()->regenerate();
            return redirect()->route('lwp.dashboard');
        }

        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ]);
    }

    public function showRegister()
    {
        if (Auth::guard('lwp')->check()) {
            return redirect()->route('lwp.dashboard');
        }
        
        $provinces = \App\Models\Location::whereRaw("LENGTH(kode_lokasi) = 2")->orderBy('nama_lokasi', 'asc')->get();

        return view('lwp.auth.register', compact('provinces'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'prov' => 'required',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $usernameExists = LwpUser::where('USERNAME_USER', $request->username)
            ->where('ISDEL', 0)
            ->exists();

        if ($usernameExists) {
            return back()->withErrors(['username' => 'Username sudah terdaftar.'])->withInput();
        }

        $emailExists = LwpUser::where('EMAIL_USER', $request->email)
            ->where('ISDEL', 0)
            ->exists();

        if ($emailExists) {
            return back()->withErrors(['email' => 'Email sudah terdaftar.'])->withInput();
        }

        $kodeLokasi = $request->kel ?: ($request->kec ?: ($request->kab ?: $request->prov));
        $token = sha1($request->username . date('Ymdhis'));

        $user = LwpUser::create([
            'NAMA_USER' => $request->nama,
            'USERNAME_USER' => $request->username,
            'EMAIL_USER' => $request->email,
            'PASSWORD_USER' => sha1($request->password),
            'KODE_LOKASI' => $kodeLokasi,
            'LEVEL' => null,
            'ISDEL' => 0,
            'TOKEN' => $token,
        ]);

        $verifyUrl = route('lwp.verify-email', ['token' => $token]);
        $emailSent = false;

        try {
            \Mail::send('lwp.auth.emails.verify', ['url' => $verifyUrl, 'nama' => $user->NAMA_USER], function($message) use ($user) {
                $message->to($user->EMAIL_USER)
                        ->subject('Verifikasi Email Akun Pengurus LWP - WAKAF NUSA');
            });
            $emailSent = true;
        } catch (\Exception $e) {
            \Log::error('Gagal mengirim email verifikasi ke ' . $user->EMAIL_USER . ': ' . $e->getMessage());
        }

        $message = "Registrasi berhasil! Silakan cek email Anda untuk proses verifikasi.";

        return redirect()->route('lwp.login')->with('success', $message);
    }

    public function verifyEmail($token)
    {
        $user = LwpUser::where('TOKEN', $token)
            ->where('ISDEL', 0)
            ->whereNull('LEVEL')
            ->first();

        if ($user) {
            $user->LEVEL = 1;
            $user->save();

            return redirect()->route('lwp.login')->with('success', 'Email berhasil diverifikasi! Akun Anda kini aktif. Silakan login.');
        }

        return redirect()->route('lwp.login')->withErrors(['username' => 'Link verifikasi tidak valid atau akun sudah terverifikasi.']);
    }

    public function logout()
    {
        Auth::guard('lwp')->logout();
        return redirect()->route('lwp.login');
    }
}
