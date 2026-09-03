<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google.
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (Exception $e) {
            return redirect()->route('login')->with('error', 'Google authentication failed. Please try again.');
        }

        $email = $googleUser->getEmail();
        $isSuperAdmin = ($email === 'castillojohnlaurence0@gmail.com');

        // Check if the user is in the database
        $user = User::where('email', $email)->first();

        if (! $user) {
            if ($isSuperAdmin) {
                // If they are super admin and don't exist in the database, automatically create them
                $user = User::create([
                    'name' => $googleUser->getName() ?? 'Super Admin',
                    'email' => $email,
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                ]);
            } else {
                // Not authorized
                return redirect()->route('login')->with('error', 'Access Denied: Your email is not authorized to access this application. Please contact an administrator.');
            }
        } else {
            // Update Google ID and Avatar if they've changed or weren't set
            $user->update([
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
            ]);
        }

        Auth::login($user, true);

        // Force PIN verification on new session logins
        session(['pin_verified' => false]);

        return redirect()->route('dashboard');
    }

    /**
     * Show the PIN verification form.
     */
    public function showVerifyPin()
    {
        if (Auth::user()->pin === null) {
            return redirect()->route('pin.setup');
        }

        if (session('pin_verified', false) === true) {
            return redirect()->route('dashboard');
        }

        return view('auth.verify_pin');
    }

    /**
     * Handle the submission of the PIN verification.
     */
    public function submitVerifyPin(\Illuminate\Http\Request $request)
    {
        $validated = $request->validate([
            'pin' => 'required|string|size:6|regex:/^[0-9]{6}$/',
        ]);

        $user = Auth::user();

        if ($user->pin !== $validated['pin']) {
            // Track incorrect attempt
            $attempts = session('pin_attempts', 0) + 1;
            session(['pin_attempts' => $attempts]);

            if ($attempts >= 3) {
                // Clear attempts, logout and redirect with critical lockout message
                session()->forget('pin_attempts');
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')->with('error', 'Authentication Lockout: Entered incorrect PIN 3 times. Automatically logged out.');
            }

            return redirect()->route('pin.verify')->with('error', 'Incorrect PIN code. ' . (3 - $attempts) . ' attempts remaining.');
        }

        // Verification successful
        session()->forget('pin_attempts');
        session(['pin_verified' => true]);

        return redirect()->route('dashboard');
    }

    /**
     * Show the first-time 6-digit PIN configuration page.
     */
    public function showSetupPin()
    {
        if (Auth::user()->pin !== null) {
            return redirect()->route('dashboard');
        }

        return view('auth.setup_pin');
    }

    /**
     * Save the newly configured 6-digit PIN.
     */
    public function saveSetupPin(\Illuminate\Http\Request $request)
    {
        $validated = $request->validate([
            'pin' => 'required|string|size:6|regex:/^[0-9]{6}$/',
            'pin_confirmation' => 'required|string|same:pin',
        ], [
            'pin.required' => 'A 6-digit PIN is required.',
            'pin.size' => 'The PIN must be exactly 6 digits.',
            'pin.regex' => 'The PIN must consist of numbers only.',
            'pin_confirmation.same' => 'The PIN confirmation does not match.',
        ]);

        $user = Auth::user();
        $user->update([
            'pin' => $validated['pin'],
        ]);

        // Automatically mark as verified since they just configured it
        session(['pin_verified' => true]);

        return redirect()->route('dashboard')->with('status', 'Your 6-digit access PIN has been successfully configured!');
    }

    /**
     * Log the user out of the application.
     */
    public function logout()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('login');
    }
}
