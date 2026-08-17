<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\SendOtpMail;
use App\Models\OtpCode;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class OtpVerificationController extends Controller
{
    /**
     * Tampilkan halaman verifikasi OTP.
     */
    public function show(Request $request): View|RedirectResponse
    {
        $userId = session('otp_user_id') ?? Auth::id();

        if (!$userId) {
            return redirect()->route('login');
        }

        $user = User::find($userId);

        if (!$user) {
            session()->forget('otp_user_id');
            return redirect()->route('login');
        }

        if ($user->email_verified_at !== null) {
            session()->forget('otp_user_id');
            return redirect()->route('pelanggan.dashboard');
        }

        return view('auth.verify-otp', [
            'user' => $user,
        ]);
    }

    /**
     * Proses verifikasi kode OTP yang diinput pengguna.
     */
    public function verify(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'string', 'size:6'],
        ], [
            'code.required' => 'Kode OTP wajib diisi.',
            'code.size' => 'Kode OTP harus terdiri dari 6 digit.',
        ]);

        $userId = session('otp_user_id') ?? Auth::id();

        if (!$userId) {
            return redirect()->route('login')->withErrors(['email' => 'Sesi verifikasi telah berakhir. Silakan login kembali.']);
        }

        $user = User::find($userId);

        if (!$user) {
            return redirect()->route('login');
        }

        if ($user->email_verified_at !== null) {
            Auth::login($user);
            session()->forget('otp_user_id');
            return redirect()->route('pelanggan.dashboard');
        }

        $otp = OtpCode::where('user_id', $user->id)
            ->where('purpose', 'email_verification')
            ->whereNull('verified_at')
            ->latest()
            ->first();

        if (!$otp) {
            return back()->withErrors(['code' => 'Kode OTP tidak ditemukan. Silakan minta kode baru.']);
        }

        if ($otp->attempts >= 5) {
            return back()->withErrors(['code' => 'Batas percobaan memasukkan OTP telah habis. Silakan klik "Kirim Ulang OTP".']);
        }

        if ($otp->isExpired()) {
            return back()->withErrors(['code' => 'Kode OTP telah kadaluarsa (berlaku 10 menit). Silakan klik "Kirim Ulang OTP".']);
        }

        if ($otp->code !== $request->code) {
            $otp->increment('attempts');
            $remaining = 5 - $otp->attempts;
            return back()->withErrors(['code' => "Kode OTP salah. Sisa percobaan: {$remaining}x."]);
        }

        // OTP Valid! Mark OTP as verified & update user email_verified_at
        $otp->update(['verified_at' => now()]);
        $user->forceFill(['email_verified_at' => now()])->save();

        Auth::login($user);
        session()->forget('otp_user_id');

        return redirect()->route('pelanggan.dashboard')->with('success', 'Email berhasil diverifikasi! Selamat datang di SIMAPES.');
    }

    /**
     * Kirim ulang kode OTP.
     */
    public function resend(Request $request): RedirectResponse
    {
        $userId = session('otp_user_id') ?? Auth::id();

        if (!$userId) {
            return redirect()->route('login');
        }

        $user = User::find($userId);

        if (!$user) {
            return redirect()->route('login');
        }

        if ($user->email_verified_at !== null) {
            return redirect()->route('pelanggan.dashboard');
        }

        // Invalidate previous unverified OTPs
        OtpCode::where('user_id', $user->id)
            ->where('purpose', 'email_verification')
            ->whereNull('verified_at')
            ->delete();

        // Generate new 6-digit OTP
        $newCode = sprintf('%06d', mt_rand(0, 999999));

        OtpCode::create([
            'user_id' => $user->id,
            'code' => $newCode,
            'purpose' => 'email_verification',
            'expires_at' => now()->addMinutes(10),
            'attempts' => 0,
        ]);

        try {
            Mail::to($user->email)->send(new SendOtpMail($user, $newCode));
        } catch (\Throwable $e) {
            return back()->withErrors(['code' => 'Gagal mengirim ulang email OTP: ' . $e->getMessage()]);
        }

        return back()->with('status', 'Kode OTP baru berhasil dikirimkan ke email Anda.');
    }
}
