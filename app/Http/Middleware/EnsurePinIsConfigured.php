<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsurePinIsConfigured
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            // 1. If user doesn't have a PIN configured yet, they must configure it
            if ($user->pin === null) {
                if (!$request->routeIs('pin.setup', 'pin.save', 'logout')) {
                    return redirect()->route('pin.setup');
                }
            } 
            // 2. If user has a PIN but hasn't verified it in the current session, they must verify it
            elseif (session('pin_verified', false) === false) {
                if (!$request->routeIs('pin.verify', 'pin.verify.submit', 'logout')) {
                    return redirect()->route('pin.verify');
                }
            }
        }

        return $next($request);
    }
}
