<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.login');
    }

    /**
     * Admin login (password step) -> send 2FA code via email -> redirect to 2FA page.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (!Auth::guard('admin')->attempt($credentials)) {
            return back()->withErrors([
                'email' => 'Invalid credentials.',
            ])->withInput();
        }

        $request->session()->regenerate();

        /** @var \App\Models\Admin $admin */
        $admin = Auth::guard('admin')->user();

        // Generate 6-char mixed letters + numbers, uppercase for readability
        $code = strtoupper(Str::random(6));
        // Ensure it's strictly alphanumeric (Str::random is, but keep it explicit)
        $code = preg_replace('/[^A-Z0-9]/', 'A', $code);

        $admin->verification_code = $code;
        // requires DB column verification_code_expires_at (timestamp, nullable)
        $admin->verification_code_expires_at = Carbon::now()->addMinutes(3);
        $admin->is_verified = false;
        $admin->save();

        // Store which admin is pending verification in session
        $request->session()->put('admin_2fa_pending_id', $admin->id);

        // Send the 2FA code via email
        try {
            Mail::raw(
                "Your admin verification code is: {$code}\n\nThis code expires in 3 minutes.",
                function ($message) use ($admin) {
                    $message->to($admin->email)
                        ->subject('Admin Login Verification Code');
                }
            );
        } catch (\Throwable $e) {
            // If email fails, log out for safety and show error
            Auth::guard('admin')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()->withErrors([
                'email' => 'Unable to send verification code. Please try again.',
            ])->withInput();
        }

        // You must add a route named admin.2fa.form in routes/admin.php
        return redirect()->route('admin.2fa.form');
    }

    /**
     * Show 2FA code entry form.
     * Requires route: admin.2fa.form
     */
    public function showTwoFactorForm(Request $request)
    {
        if (!$request->session()->has('admin_2fa_pending_id')) {
            return redirect()->route('admin.login')->with('error', 'Please login again.');
        }

        return view('admin.two-factor');
    }

    /**
     * Verify 2FA code.
     * Requires route: admin.2fa.verify
     */
    public function verifyTwoFactorCode(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        $pendingId = $request->session()->get('admin_2fa_pending_id');
        if (!$pendingId) {
            return redirect()->route('admin.login')->with('error', 'Please login again.');
        }

        /** @var \App\Models\Admin|null $admin */
        $admin = Admin::find($pendingId);
        if (!$admin) {
            Auth::guard('admin')->logout();
            $request->session()->forget('admin_2fa_pending_id');
            return redirect()->route('admin.login')->with('error', 'Please login again.');
        }

        // Expired?
        if (empty($admin->verification_code_expires_at) || Carbon::now()->gt(Carbon::parse($admin->verification_code_expires_at))) {
            return back()->withErrors([
                'code' => 'Code expired. Please resend a new code.',
            ]);
        }

        // Match?
        $input = strtoupper(trim($request->code));
        if ($admin->verification_code !== $input) {
            return back()->withErrors([
                'code' => 'Invalid code.',
            ]);
        }

        // Success
        $admin->is_verified = true;
        $admin->verification_code = null;
        $admin->verification_code_expires_at = null;
        $admin->save();

        $request->session()->forget('admin_2fa_pending_id');

        return redirect()->route('admin.dashboard');
    }

    /**
     * Resend a new 2FA code.
     * Requires route: admin.2fa.resend
     */
    public function resendTwoFactorCode(Request $request)
    {
        $pendingId = $request->session()->get('admin_2fa_pending_id');
        if (!$pendingId) {
            return redirect()->route('admin.login')->with('error', 'Please login again.');
        }

        /** @var \App\Models\Admin|null $admin */
        $admin = Admin::find($pendingId);
        if (!$admin) {
            Auth::guard('admin')->logout();
            $request->session()->forget('admin_2fa_pending_id');
            return redirect()->route('admin.login')->with('error', 'Please login again.');
        }

        $code = strtoupper(Str::random(6));
        $code = preg_replace('/[^A-Z0-9]/', 'A', $code);

        $admin->verification_code = $code;
        $admin->verification_code_expires_at = Carbon::now()->addMinutes(3);
        $admin->is_verified = false;
        $admin->save();

        try {
            Mail::raw(
                "Your admin verification code is: {$code}\n\nThis code expires in 3 minutes.",
                function ($message) use ($admin) {
                    $message->to($admin->email)
                        ->subject('Admin Login Verification Code (Resent)');
                }
            );
        } catch (\Throwable $e) {
            return back()->withErrors([
                'code' => 'Unable to resend verification code. Please try again.',
            ]);
        }

        return back()->with('status', 'A new verification code has been sent to your email.');
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }

    /**
     * Dashboard: block access unless admin completed 2FA (no middleware, controller-level enforcement).
     */
    public function dashboard(Request $request)
    {
        /** @var \App\Models\Admin|null $admin */
        $admin = Auth::guard('admin')->user();

        if (!$admin) {
            return redirect()->route('admin.login')->with('error', 'Please login as admin.');
        }

        if (!$admin->is_verified) {
            // ensure we know who is pending
            $request->session()->put('admin_2fa_pending_id', $admin->id);
            return redirect()->route('admin.2fa.form');
        }

        return view('admin.dashboard');
    }
}