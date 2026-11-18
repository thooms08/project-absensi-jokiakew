<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Tampilkan halaman login.
     */
    public function showLogin(Request $request)
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user()->role);
        }

        /* Generate captcha baru setiap kali halaman login dibuka */
        [$question, $answer] = $this->generateCaptcha();
        $request->session()->put('captcha_answer', $answer);
        $request->session()->put('captcha_question', $question);

        return view('auth.login');
    }

    /**
     * Proses login.
     */
    public function login(Request $request)
    {
        /* ---- Validasi captcha sederhana ---- */
        $expectedCaptcha = $request->session()->get('captcha_answer');
        if ((int) $request->input('captcha') !== (int) $expectedCaptcha) {
            return response()->json([
                'status'  => 'captcha_error',
                'message' => 'Jawaban captcha salah.',
            ], 422);
        }

        /* ---- Cari user berdasarkan username atau email ---- */
        $identity = $request->input('identity');
        $user     = User::where('username', $identity)
                        ->orWhere('email', $identity)
                        ->first();

        if (! $user) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Username atau email tidak ditemukan.',
            ], 422);
        }

        /* ---- Cek password ---- */
        $credentials = filter_var($identity, FILTER_VALIDATE_EMAIL)
            ? ['email' => $identity, 'password' => $request->input('password')]
            : ['username' => $identity, 'password' => $request->input('password')];

        /* Durasi remember-me: 3 jam (180 menit) */
        $remember = (bool) $request->input('remember');

        if (! Auth::attempt($credentials, $remember)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Password yang Anda masukkan salah.',
            ], 422);
        }

        /* ---- Jika remember me aktif, perpanjang sesi ke 180 menit ---- */
        if ($remember) {
            $request->session()->put('login_expires_at', now()->addMinutes(180));
            config(['session.lifetime' => 180]);
        }

        $request->session()->regenerate();

        return response()->json([
            'status'   => 'success',
            'message'  => 'Login berhasil.',
            'redirect' => $this->redirectUrlByRole(Auth::user()->role),
        ]);
    }

    /**
     * Logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
     * Generate captcha baru dan simpan ke sesi.
     */
    public function refreshCaptcha(Request $request)
    {
        [$question, $answer] = $this->generateCaptcha();
        $request->session()->put('captcha_answer', $answer);
        $request->session()->put('captcha_question', $question);

        return response()->json(['question' => $question]);
    }

    /* ------------------------------------------------------------------ */
    /*  Helper                                                              */
    /* ------------------------------------------------------------------ */

    /**
     * Buat soal captcha: penjumlahan dua angka 1-5.
     * Range tetap sederhana (1-5) untuk UX yang baik — guru tidak perlu kalkulator.
     * Keamanan utama dijamin oleh:
     *   1. Rate limiting 5 attempts per menit (throttle:5,1)
     *   2. Session-based captcha yang di-refresh setiap page load
     * TODO: Migrasi ke reCAPTCHA v3 atau hCaptcha untuk solusi jangka panjang.
     */
    private function generateCaptcha(): array
    {
        $a        = rand(1, 5);
        $b        = rand(1, 5);
        $question = $a . ' + ' . $b . ' = ?';
        return [$question, $a + $b];
    }

    /**
     * Simpan captcha ke sesi saat halaman login dimuat pertama kali.
     * Dipanggil dari showLogin via middleware atau langsung.
     */
    public function storeCaptcha(Request $request): string
    {
        [$question, $answer] = $this->generateCaptcha();
        $request->session()->put('captcha_answer', $answer);
        return $question;
    }

    private function redirectByRole(string $role)
    {
        return redirect($this->redirectUrlByRole($role));
    }

    private function redirectUrlByRole(string $role): string
    {
        return match (true) {
            $role === 'admin'                          => route('admin.dashboard'),
            in_array($role, ['guru_smk', 'guru_smp']) => route('guru.dashboard'),
            in_array($role, ['murid_smk', 'murid_smp']) => route('murid.dashboard'),
            default => '/',
        };
    }
}
