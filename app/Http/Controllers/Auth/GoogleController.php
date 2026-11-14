<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Election;
use App\Mail\VoterLoginOtp;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
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
        // Identify if this is a public voter election authentication
        $state = request('state');
        $voterElectionId = session('voter_for_election');

        if (!$voterElectionId && is_string($state) && str_starts_with($state, 'voter_election_')) {
            $voterElectionId = (int) str_replace('voter_election_', '', $state);
        }

        $voterElection = $voterElectionId ? Election::find($voterElectionId) : null;

        // Handle Google OAuth cancellation or error query params (e.g. user pressed cancel)
        if (request()->has('error') || request('error') === 'access_denied') {
            if ($voterElection) {
                session()->forget(['voter_for_election', 'voter_for_election_slug']);
                return redirect()->route('elections.voter.login', $voterElection->slug ?: $voterElection->id)
                    ->with('error', 'Google authentication was cancelled. You can sign in using your registered email address below.');
            }

            return redirect()->route('login')->with('error', 'Google authentication was cancelled.');
        }

        try {
            if ($voterElection) {
                $googleUser = Socialite::driver('google')->stateless()->user();
            } else {
                $googleUser = Socialite::driver('google')->user();
            }
        } catch (Exception $e) {
            Log::warning('Google OAuth failure: ' . $e->getMessage());

            if ($voterElection) {
                session()->forget(['voter_for_election', 'voter_for_election_slug']);
                return redirect()->route('elections.voter.login', $voterElection->slug ?: $voterElection->id)
                    ->with('error', 'Google authentication could not be completed. Please try again or use your masterlist email below.');
            }

            return redirect()->route('login')->with('error', 'Google authentication failed. Please try again.');
        }

        $email = $googleUser->getEmail();
        $superAdminEmail = config('app.super_admin_email');
        $isSuperAdmin = ($superAdminEmail && $email === $superAdminEmail);
        $isMlhuillier = str_ends_with(strtolower($email), '@mlhuillier.com');

        // Handle isolated public voter flow
        if ($voterElection) {
            session()->forget(['voter_for_election', 'voter_for_election_slug']);
            $normalizedEmail = strtolower($email);

            // Verify if the email is on the official election masterlist
            $voter = $voterElection->voters()->where('email', $normalizedEmail)->first();

            if (!$voter) {
                return redirect()->route('elections.public_show', $voterElection->slug ?: $voterElection->id)
                    ->with('masterlist_error', 'Your email (' . $email . ') is not on the official masterlist for this election. Please contact the SAKO Election Committee.');
            }

            // Check if already voted
            if ($voter->voted_at !== null) {
                return redirect()->route('elections.public_show', $voterElection->slug ?: $voterElection->id)
                    ->with('voter_info', 'You have already successfully cast your vote in this election on ' . $voter->voted_at->format('M d, Y h:i A') . '!');
            }

            // Generate 6-digit Email OTP
            $otp = sprintf('%06d', random_int(0, 999999));
            Cache::put("voter_otp_{$voterElection->id}_{$normalizedEmail}", [
                'code' => $otp,
                'attempts' => 0,
                'generated_at' => now(),
            ], now()->addMinutes(10));

            session([
                'voter_pending_otp_election' => $voterElection->id,
                'voter_pending_otp_email' => $normalizedEmail,
                'voter_name' => $googleUser->getName() ?? $voter->display_name,
            ]);

            try {
                Mail::to($normalizedEmail)->send(new VoterLoginOtp($voterElection, $voter, $otp));
            } catch (Exception $e) {
                Log::error('Failed to send voter login OTP via Google SSO: ' . $e->getMessage());
            }

            return redirect()->route('elections.voter.verify_otp', $voterElection->slug ?: $voterElection->id)
                ->with('status', 'We sent a 6-digit verification code to ' . $email . '. Please enter it below to confirm your identity.');
        }

        // Check if the user is in the database
        $user = User::where('email', $email)->first();

        if (! $user) {
            if ($isSuperAdmin) {
                // Primary Super Admin is allowed to auto-create to bootstrap the system
                $user = User::create([
                    'name' => $googleUser->getName() ?? 'John Laurence Castillo',
                    'email' => $email,
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                ]);
            } else {
                // Reject anyone who is not pre-whitelisted (manually added) in the database
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
        $isAjax = $request->expectsJson() || $request->ajax();

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

                if ($isAjax) {
                    return response()->json([
                        'success' => false,
                        'locked_out' => true,
                        'redirect' => route('login'),
                        'message' => 'Authentication Lockout: Entered incorrect PIN 3 times. Automatically logged out.',
                    ], 403);
                }

                return redirect()->route('login')->with('error', 'Authentication Lockout: Entered incorrect PIN 3 times. Automatically logged out.');
            }

            if ($isAjax) {
                return response()->json([
                    'success' => false,
                    'locked_out' => false,
                    'attempts_left' => 3 - $attempts,
                    'message' => 'Incorrect PIN code. ' . (3 - $attempts) . ' attempts remaining.',
                ], 422);
            }

            return redirect()->route('pin.verify')->with('error', 'Incorrect PIN code. ' . (3 - $attempts) . ' attempts remaining.');
        }

        // Verification successful
        session()->forget('pin_attempts');
        session(['pin_verified' => true]);

        if ($isAjax) {
            return response()->json([
                'success' => true,
                'redirect' => route('dashboard'),
                'message' => 'PIN verified successfully.',
            ]);
        }

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

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'redirect' => route('dashboard'),
                'message' => 'Your 6-digit access PIN has been successfully configured!',
            ]);
        }

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
