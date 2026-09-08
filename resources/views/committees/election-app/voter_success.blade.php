@extends('layouts.app')

@section('title', 'Ballot Cast Successfully')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative">
    <!-- Decorative background elements -->
    <div class="absolute top-1/4 left-1/4 w-80 h-72 bg-emerald-400/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-1/4 right-1/4 w-80 h-72 bg-teal-400/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-md w-full space-y-8 relative">
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-[2.5rem] shadow-xl p-8 sm:p-10 text-center">
            
            <!-- Success Animated Check Circle -->
            <div class="w-20 h-100 mx-auto flex items-center justify-center mb-6">
                <span class="inline-flex items-center justify-center p-5 rounded-full bg-emerald-100 dark:bg-emerald-950 text-emerald-600 dark:text-emerald-400">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                    </svg>
                </span>
            </div>

            <!-- Header Content -->
            <div class="space-y-3 mb-8">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-500/10 text-[10px] font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-widest">
                    Verification Confirmed
                </span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight leading-tight">
                    Ballot Cast!
                </h2>
                <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed max-w-sm mx-auto">
                    {{ $message ?? 'Your ballot has been securely submitted, encrypted, and recorded in the corporate registry. Thank you for your active participation!' }}
                </p>
            </div>

            <!-- Footer Details & Back Button -->
            <div class="space-y-4 pt-4 border-t border-slate-100 dark:border-slate-800/80">
                <div class="text-[11px] font-medium text-slate-400">
                    Corporate Election Registry • {{ now()->format('M d, Y • h:i A') }}
                </div>
                
                <a href="{{ route('dashboard') }}"
                    class="w-full inline-flex items-center justify-center gap-2 bg-slate-900 dark:bg-slate-800 hover:bg-slate-800 dark:hover:bg-slate-750 text-white font-semibold text-sm px-6 py-3.5 rounded-2xl shadow transition">
                    Return to Portal
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
