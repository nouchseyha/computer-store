<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\PasswordOtpMail;
use App\Models\PasswordOtp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Password;

class PasswordOtpController extends Controller
{
    // Step 1: Show "enter email" form
    public function showRequestForm()
    {
        return view('auth.forgot-password');
    }

    // Step 2: Send OTP to email
    public function sendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        // Delete any old OTPs for this email
        PasswordOtp::where('email', $request->email)->delete();

        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        PasswordOtp::create([
            'email'      => $request->email,
            'otp'        => $otp,
            'expires_at' => now()->addMinutes(10),
        ]);

        Mail::to($request->email)->send(new PasswordOtpMail($otp));

        // Store email in session for next step
        session(['otp_email' => $request->email]);

        return redirect()->route('password.otp.verify')
            ->with('status', 'OTP sent! Check your email inbox.');
    }

    // Step 3: Show OTP verification form
    public function showVerifyForm()
    {
        if (!session('otp_email')) {
            return redirect()->route('password.request');
        }
        return view('auth.verify-otp');
    }

    // Step 4: Verify OTP
    public function verifyOtp(Request $request)
    {
        $request->validate(['otp' => 'required|string|size:6']);

        $email = session('otp_email');
        if (!$email) {
            return redirect()->route('password.request');
        }

        $record = PasswordOtp::where('email', $email)
            ->where('otp', $request->otp)
            ->first();

        if (!$record) {
            return back()->withErrors(['otp' => 'Invalid OTP code.']);
        }

        if ($record->isExpired()) {
            $record->delete();
            return back()->withErrors(['otp' => 'OTP has expired. Please request a new one.']);
        }

        // OTP valid — allow password reset
        session(['otp_verified' => true]);

        return redirect()->route('password.otp.reset');
    }

    // Step 5: Show new password form
    public function showResetForm()
    {
        if (!session('otp_email') || !session('otp_verified')) {
            return redirect()->route('password.request');
        }
        return view('auth.reset-password-otp');
    }

    // Step 6: Save new password
    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $email = session('otp_email');
        if (!$email || !session('otp_verified')) {
            return redirect()->route('password.request');
        }

        $user = User::where('email', $email)->firstOrFail();
        $user->update(['password' => Hash::make($request->password)]);

        // Clean up
        PasswordOtp::where('email', $email)->delete();
        session()->forget(['otp_email', 'otp_verified']);

        return redirect()->route('login')
            ->with('status', 'Password reset successfully. You can now sign in.');
    }
}
