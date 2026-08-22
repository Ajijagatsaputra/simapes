<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Mail\SendOtpMail;
use App\Models\OtpCode;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        $user = Auth::user();

        // ── Cek apakah email sudah terverifikasi (hanya untuk pelanggan) ──
        if ($user->role === 'pelanggan' && $user->email_verified_at === null) {
            // Logout user — jangan biarkan masuk tanpa verifikasi
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            // Siapkan atau buat ulang OTP jika belum ada / sudah expired
            $existingOtp = OtpCode::where('user_id', $user->id)
                ->where('purpose', 'email_verification')
                ->whereNull('verified_at')
                ->where('expires_at', '>', now())
                ->latest()
                ->first();

            if (!$existingOtp) {
                // Hapus OTP lama yang sudah expired
                OtpCode::where('user_id', $user->id)
                    ->where('purpose', 'email_verification')
                    ->whereNull('verified_at')
                    ->delete();

                // Buat OTP baru
                $otpCode = sprintf('%06d', mt_rand(0, 999999));

                OtpCode::create([
                    'user_id' => $user->id,
                    'code' => $otpCode,
                    'purpose' => 'email_verification',
                    'expires_at' => now()->addMinutes(10),
                    'attempts' => 0,
                ]);

                // Kirim email OTP baru
                try {
                    Mail::to($user->email)->send(new SendOtpMail($user, $otpCode));
                } catch (\Throwable $e) {
                    logger()->error('Gagal mengirim email OTP saat login: ' . $e->getMessage());
                }

                $statusMsg = 'Akun Anda belum diverifikasi. Kode OTP baru telah dikirimkan ke email Anda.';
            } else {
                $statusMsg = 'Akun Anda belum diverifikasi. Silakan masukkan kode OTP yang sudah dikirim ke email Anda.';
            }

            // Simpan user ID ke session untuk proses verifikasi
            session(['otp_user_id' => $user->id]);

            return redirect()->route('otp.verify')->with('status', $statusMsg);
        }

        // ── Redirect berdasarkan role ──
        if ($user->role === 'admin') {
            return redirect()->intended(route('admin.dashboard', absolute: false));
        }

        return redirect()->intended(route('pelanggan.katalog', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
