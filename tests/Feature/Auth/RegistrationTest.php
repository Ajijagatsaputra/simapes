<?php

namespace Tests\Feature\Auth;

use App\Models\OtpCode;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register_and_verify_otp(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertRedirect(route('otp.verify'));

        $user = User::where('email', 'test@example.com')->first();
        $this->assertNotNull($user);
        $this->assertNull($user->email_verified_at);

        $otp = OtpCode::where('user_id', $user->id)->first();
        $this->assertNotNull($otp);

        // Submit valid OTP
        $verifyResponse = $this->withSession(['otp_user_id' => $user->id])
            ->post(route('otp.verify.submit'), [
                'code' => $otp->code,
            ]);

        $this->assertAuthenticatedAs($user);
        $this->assertNotNull($user->fresh()->email_verified_at);
        $verifyResponse->assertRedirect(route('pelanggan.dashboard', absolute: false));
    }
}
