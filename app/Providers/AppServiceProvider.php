<?php

namespace App\Providers;

use App\Models\Event;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Clear sitemap cache when events are created, updated, or deleted
        Event::saved(fn () => Cache::forget('sitemap_xml'));
        Event::deleted(fn () => Cache::forget('sitemap_xml'));

        // --- NAMED RATE LIMITERS ---

        // 1. PIN 2FA Verification (Brute-force protection)
        RateLimiter::for('pin-verification', function (Request $request) {
            return Limit::perMinute(5)->by($request->user()?->id ?: $request->ip());
        });

        // 2. Event Registration (Prevent spam RSVPs)
        RateLimiter::for('event-registration', function (Request $request) {
            return Limit::perMinute(10)->by($request->ip());
        });

        // 3. Email Recovery / Access Request (Prevent mail bombing)
        RateLimiter::for('request-access', function (Request $request) {
            return Limit::perMinute(3)->by($request->ip());
        });

        // 4. Mobile Venue Check-In (Prevent ticket code guessing)
        RateLimiter::for('venue-check-in', function (Request $request) {
            return Limit::perMinute(10)->by($request->ip());
        });

        // 5. Post-Event Survey Submissions (Prevent survey spam)
        RateLimiter::for('survey-submission', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip());
        });

        // 6. Election Setup / Credentials (NAT-Safe: Throttled by Election + Session)
        RateLimiter::for('election-setup', function (Request $request) {
            $election = $request->route('election');
            $electionId = is_object($election) ? $election->id : ($election ?: 'global');
            $voterSession = $request->hasSession() ? $request->session()->getId() : $request->ip();
            return Limit::perMinute(10)->by("election-setup:{$electionId}:{$voterSession}");
        });

        // 7. Election Vote Submission (NAT-Safe: Throttled by Election + Session)
        RateLimiter::for('election-vote', function (Request $request) {
            $election = $request->route('election');
            $electionId = is_object($election) ? $election->id : ($election ?: 'global');
            $voterSession = $request->hasSession() ? $request->session()->getId() : $request->ip();
            return Limit::perMinute(3)->by("election-vote:{$electionId}:{$voterSession}");
        });
    }
}
