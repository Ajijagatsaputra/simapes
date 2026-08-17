<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\SendOtpMail;
use App\Models\OtpCode;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'no_whatsapp' => ['nullable', 'string', 'max:20'],
            'alamat' => ['nullable', 'string'],
            'nama_sekolah' => ['nullable', 'string', 'max:255'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'pelanggan',
            'no_whatsapp' => $request->no_whatsapp,
            'alamat' => $request->alamat,
            'nama_sekolah' => $request->nama_sekolah,
            'email_verified_at' => null,
        ]);

        event(new Registered($user));

        // Generate OTP Code
        $otpCode = sprintf('%06d', mt_rand(0, 999999));

        OtpCode::create([
            'user_id' => $user->id,
            'code' => $otpCode,
            'purpose' => 'email_verification',
            'expires_at' => now()->addMinutes(10),
            'attempts' => 0,
        ]);

        // Send OTP Email
        try {
            Mail::to($user->email)->send(new SendOtpMail($user, $otpCode));
        } catch (\Throwable $e) {
            // Log error or ignore in dev if mail fail, but user can click resend
            logger()->error('Gagal mengirim email OTP: ' . $e->getMessage());
        }

        session(['otp_user_id' => $user->id]);

        return redirect()->route('otp.verify')->with('status', 'Pendaftaran berhasil! Kode OTP telah dikirimkan ke email Anda.');
    }
}
