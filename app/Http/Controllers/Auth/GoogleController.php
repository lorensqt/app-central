<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Election;
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
        $superAdminEmail = config('app.super_admin_email');
        $isSuperAdmin = ($superAdminEmail && $email === $superAdminEmail);
        $isMlhuillier = str_ends_with(strtolower($email), '@mlhuillier.com');

        // Check if they are a public voter
        if (session()->has('voter_for_election')) {
            $electionId = session()->pull('voter_for_election');
            $election = Election::find($electionId);

            if (!$election) {
                return redirect()->route('login')->with('error', 'The requested election could not be found.');
            }

            // ISO-VOTER ACCESS: Completely isolated session, NO users table record created.
            session([
                'voter_email' => strtolower($email),
                'voter_name' => $googleUser->getName() ?? 'Voter',
            ]);

            // Verify if the email is on the official election masterlist
            $voter = $election->voters()->where('email', strtolower($email))->first();

            if (!$voter) {
                return redirect()->route('elections.public_show', $election->slug ?: $election->id)
                    ->with('masterlist_error', 'Your email (' . $email . ') is not on the official masterlist for this election. Please contact the SAKO Election Committee.');
            }

            // Check if already voted
            if ($voter->voted_at !== null) {
                return redirect()->route('elections.public_show', $election->slug ?: $election->id)
                    ->with('voter_info', 'You have already successfully cast your vote in this election!');
            }

            return redirect()->route('elections.voter.setup', $election->slug ?: $election->id);
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
