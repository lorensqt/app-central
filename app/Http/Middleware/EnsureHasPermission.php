<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureHasPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $permission
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if (! $user->hasPermission($permission)) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized workspace access. You do not have permission to access this application.',
                ], 403);
            }

            return redirect()->route('dashboard')
                ->with('error', 'Unauthorized access: You do not have permission to access that application workspace.');
        }

        return $next($request);
    }
}
