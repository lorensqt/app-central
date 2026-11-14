@extends('layouts.voter')

@section('title', 'Voter Authentication - ' . $election->title)

@section('content')
<div class="min-h-[72vh] flex items-center justify-center py-6 sm:py-12 px-3 sm:px-6 lg:px-8 relative overflow-hidden">
    <!-- Decorative Ambient Glows -->
    <div class="absolute top-1/4 left-1/4 w-80 h-72 bg-purple-400/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-1/4 right-1/4 w-80 h-72 bg-indigo-400/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-md w-full space-y-6 relative">
        <!-- Main Card -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-3xl sm:rounded-[2.5rem] shadow-xl p-6 sm:p-10 text-center space-y-6">
            
            <!-- Icon -->
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-purple-600 to-indigo-600 text-white flex items-center justify-center mx-auto shadow-lg shadow-purple-500/25">
                <i class="fa-solid fa-shield-halved text-2xl"></i>
            </div>

            <!-- Title & Election Info -->
            <div class="space-y-1.5">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-purple-500/10 dark:bg-purple-400/10 text-[10px] font-black text-purple-600 dark:text-purple-400 uppercase tracking-widest">
                    Voter Access Portal
                </span>
                <h2 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white tracking-tight leading-snug">
                    Voter Authentication
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-2 max-w-xs mx-auto font-medium">
                    {{ $election->title }}
                </p>
            </div>

            <!-- Flash Notices -->
            @if(session('masterlist_error'))
                <div class="rounded-2xl bg-amber-500/10 border border-amber-500/30 p-4 text-left flex items-start gap-3 text-amber-800 dark:text-amber-200 text-xs animate-fade-in-up">
                    <i class="fa-solid fa-triangle-exclamation text-amber-500 text-base shrink-0 mt-0.5"></i>
                    <div class="space-y-0.5">
                        <strong class="font-bold block">Not on Official Masterlist</strong>
                        <p class="leading-relaxed opacity-95">{{ session('masterlist_error') }}</p>
                    </div>
                </div>
            @endif

            @if(session('voter_info'))
                <div class="rounded-2xl bg-emerald-500/10 border border-emerald-500/30 p-4 text-left flex items-start gap-3 text-emerald-800 dark:text-emerald-200 text-xs animate-fade-in-up">
                    <i class="fa-solid fa-circle-check text-emerald-500 text-base shrink-0 mt-0.5"></i>
                    <div class="space-y-0.5">
                        <strong class="font-bold block">Ballot Already Cast</strong>
                        <p class="leading-relaxed opacity-95">{{ session('voter_info') }}</p>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="rounded-2xl bg-rose-500/10 border border-rose-500/30 p-3.5 text-left flex items-start gap-2.5 text-rose-800 dark:text-rose-200 text-xs">
                    <i class="fa-solid fa-circle-xmark text-rose-500 text-base shrink-0 mt-0.5"></i>
                    <span class="leading-relaxed font-medium">{{ session('error') }}</span>
                </div>
            @endif

            @if(session('status'))
                <div class="rounded-2xl bg-purple-500/10 border border-purple-500/30 p-3.5 text-left flex items-start gap-2.5 text-purple-800 dark:text-purple-200 text-xs">
                    <i class="fa-solid fa-circle-info text-purple-500 text-base shrink-0 mt-0.5"></i>
                    <span class="leading-relaxed font-medium">{{ session('status') }}</span>
                </div>
            @endif

            <!-- Informational Banner -->
            <div class="bg-slate-50 dark:bg-slate-950/60 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl p-3.5 text-left space-y-1">
                <div class="flex items-center gap-1.5 text-purple-600 dark:text-purple-400 font-bold text-[11px] uppercase tracking-wider">
                    <i class="fa-solid fa-envelope-circle-check text-xs"></i>
                    <span>Email OTP Verification Required</span>
                </div>
                <p class="text-[11px] text-slate-500 dark:text-slate-400 leading-relaxed">
                    To guarantee that only registered members cast their ballots, an authentication code (OTP) will be sent to your registered masterlist email.
                </p>
            </div>

            <!-- Email OTP Request Form -->
            <form action="{{ route('elections.voter.send_otp', $election) }}" method="POST" class="space-y-4 text-left" id="voter-login-form">
                @csrf

                <div class="space-y-1.5">
                    <label for="voter-email" class="block text-[11px] font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400">
                        Whitelisted Masterlist Email
                    </label>
                    <div class="relative rounded-xl shadow-xs">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-solid fa-envelope text-sm"></i>
                        </div>
                        <input type="email" name="email" id="voter-email" required autofocus
                            value="{{ old('email', $prefilledEmail ?? '') }}"
                            placeholder="e.g. yourname@mlhuillier.com"
                            class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-white bg-slate-50/50 dark:bg-slate-950 focus:bg-white dark:focus:bg-slate-900 focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none text-sm transition">
                    </div>
                    @error('email')
                        <p class="text-rose-500 text-xs font-medium mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" id="submit-btn"
                    class="w-full inline-flex items-center justify-center gap-2 bg-gradient-to-r from-purple-600 via-indigo-600 to-purple-700 hover:from-purple-700 hover:to-indigo-700 active:scale-[0.98] text-white font-bold text-sm py-3.5 px-6 rounded-xl shadow-md shadow-purple-500/25 hover:shadow-lg transition cursor-pointer">
                    <i class="fa-solid fa-paper-plane text-xs"></i>
                    <span>Send Verification Code</span>
                </button>
            </form>

            <!-- Divider -->
            <div class="relative flex items-center justify-center">
                <div class="border-t border-slate-200 dark:border-slate-800 w-full"></div>
                <span class="bg-white dark:bg-slate-900 px-3 text-[10px] font-bold uppercase tracking-wider text-slate-400 shrink-0">
                    Or Sign In With
                </span>
                <div class="border-t border-slate-200 dark:border-slate-800 w-full"></div>
            </div>

            <!-- Google SSO Option (Also enforces Email OTP) -->
            <div>
                <a href="{{ route('elections.voter.google', $election) }}"
                    class="w-full inline-flex items-center justify-center gap-2.5 bg-slate-50 dark:bg-slate-950 hover:bg-slate-100 dark:hover:bg-slate-800 border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-200 font-bold text-xs sm:text-sm py-3 px-4 rounded-xl transition duration-150 active:scale-[0.98] shadow-2xs">
                    <svg class="w-4 h-4" viewBox="0 0 24 24">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z" />
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z" />
                    </svg>
                    <span>Continue with Google</span>
                </a>
            </div>

            <!-- Footer Compliance Note -->
            <div class="pt-2 border-t border-slate-100 dark:border-slate-800/80">
                <span class="text-[10px] text-slate-400 dark:text-slate-500 font-semibold flex items-center justify-center gap-1.5">
                    <i class="fa-solid fa-lock text-[9px]"></i>
                    <span>Official 2FA Secret Ballot Security • RA 9520 Certified</span>
                </span>
            </div>
        </div>

        <!-- Back to Overview -->
        <div class="text-center">
            <a href="{{ route('elections.public_show', $election) }}"
                class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-purple-600 dark:text-slate-400 dark:hover:text-purple-400 transition">
                <i class="fa-solid fa-arrow-left text-[10px]"></i>
                <span>Return to Election Overview</span>
            </a>
        </div>
    </div>
</div>

<script>
    document.getElementById('voter-login-form').addEventListener('submit', function() {
        const btn = document.getElementById('submit-btn');
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin text-xs"></i><span>Sending Code...</span>';
        }
    });
</script>
@endsection
