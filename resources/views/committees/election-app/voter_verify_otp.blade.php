@extends('layouts.voter')

@section('title', 'Verify Identity Code - ' . $election->title)

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
                <i class="fa-solid fa-key text-2xl"></i>
            </div>

            <!-- Title & Subtitle -->
            <div class="space-y-1.5">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-purple-500/10 dark:bg-purple-400/10 text-[10px] font-black text-purple-600 dark:text-purple-400 uppercase tracking-widest">
                    Identity Verification
                </span>
                <h2 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white tracking-tight leading-snug">
                    Enter Verification Code
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 max-w-xs mx-auto leading-relaxed">
                    We sent a 6-digit access code to <strong class="text-slate-700 dark:text-slate-200">{{ $maskedEmail }}</strong>. Enter it below to access your ballot.
                </p>
            </div>

            <!-- Flash Error / Status -->
            @if(session('error'))
                <div class="rounded-2xl bg-rose-500/10 border border-rose-500/30 p-3.5 text-left flex items-start gap-2.5 text-rose-800 dark:text-rose-200 text-xs animate-shake">
                    <i class="fa-solid fa-circle-xmark text-rose-500 text-base shrink-0 mt-0.5"></i>
                    <span class="leading-relaxed font-medium">{{ session('error') }}</span>
                </div>
            @endif

            @if(session('status'))
                <div class="rounded-2xl bg-emerald-500/10 border border-emerald-500/30 p-3.5 text-left flex items-start gap-2.5 text-emerald-800 dark:text-emerald-200 text-xs animate-fade-in-up">
                    <i class="fa-solid fa-circle-check text-emerald-500 text-base shrink-0 mt-0.5"></i>
                    <span class="leading-relaxed font-medium">{{ session('status') }}</span>
                </div>
            @endif

            <!-- 6-Digit OTP Form -->
            <form action="{{ route('elections.voter.verify_otp.submit', $election) }}" method="POST" id="otp-form" class="space-y-6">
                @csrf
                <input type="hidden" name="otp" id="combined-otp" value="">

                <!-- 6 Digit Inputs Box -->
                <div class="flex items-center justify-center gap-2 sm:gap-2.5">
                    @for($i = 0; $i < 6; $i++)
                        <input type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1"
                            class="otp-digit w-11 h-14 sm:w-13 sm:h-16 text-center text-xl sm:text-2xl font-black rounded-xl sm:rounded-2xl border-2 border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-white focus:bg-white dark:focus:bg-slate-900 focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none transition-all select-all shadow-xs"
                            data-index="{{ $i }}" autocomplete="one-time-code" required>
                    @endfor
                </div>

                <!-- Submit Button -->
                <button type="submit" id="verify-btn"
                    class="w-full inline-flex items-center justify-center gap-2 bg-gradient-to-r from-purple-600 via-indigo-600 to-purple-700 hover:from-purple-700 hover:to-indigo-700 active:scale-[0.98] text-white font-bold text-sm py-3.5 px-6 rounded-xl shadow-md shadow-purple-500/25 hover:shadow-lg transition cursor-pointer">
                    <i class="fa-solid fa-shield-check text-xs"></i>
                    <span>Verify Code & Access Ballot</span>
                </button>
            </form>

            <!-- Expiry & Resend Controls -->
            <div class="pt-2 border-t border-slate-100 dark:border-slate-800/80 space-y-3">
                <div class="text-[11px] text-slate-400 dark:text-slate-500 flex items-center justify-center gap-1.5 font-medium">
                    <i class="fa-regular fa-clock text-xs"></i>
                    <span>Code expires in: <strong id="countdown-timer" class="text-purple-600 dark:text-purple-400 font-bold">10:00</strong></span>
                </div>

                <div class="flex items-center justify-center gap-4 text-xs font-semibold">
                    <form action="{{ route('elections.voter.resend_otp', $election) }}" method="POST" id="resend-form">
                        @csrf
                        <button type="submit" id="resend-btn"
                            class="text-purple-600 hover:text-purple-700 dark:text-purple-400 dark:hover:text-purple-300 transition cursor-pointer disabled:opacity-40 disabled:cursor-not-allowed">
                            <span>Resend Code</span>
                            <span id="resend-cooldown" class="hidden text-slate-400 font-normal"> (60s)</span>
                        </button>
                    </form>
                    <span class="text-slate-300 dark:text-slate-700">•</span>
                    <a href="{{ route('elections.voter.login', $election) }}"
                        class="text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200 transition">
                        Use Different Email
                    </a>
                </div>
            </div>
        </div>

        <!-- Back to Overview -->
        <div class="text-center">
            <a href="{{ route('elections.public_show', $election) }}"
                class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-purple-600 dark:text-slate-400 dark:hover:text-purple-400 transition">
                <i class="fa-solid fa-arrow-left text-[10px]"></i>
                <span>Cancel & Return to Overview</span>
            </a>
        </div>
    </div>
</div>

<style>
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        20%, 60% { transform: translateX(-4px); }
        40%, 80% { transform: translateX(4px); }
    }
    .animate-shake {
        animation: shake 0.4s ease-in-out;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const digits = Array.from(document.querySelectorAll('.otp-digit'));
        const combinedInput = document.getElementById('combined-otp');
        const form = document.getElementById('otp-form');
        const verifyBtn = document.getElementById('verify-btn');

        if (digits.length > 0) {
            digits[0].focus();
        }

        function updateCombined() {
            const code = digits.map(d => d.value).join('');
            combinedInput.value = code;
            return code;
        }

        digits.forEach((digit, index) => {
            digit.addEventListener('input', (e) => {
                const val = e.target.value.replace(/[^0-9]/g, '');
                e.target.value = val ? val[val.length - 1] : '';

                if (e.target.value && index < digits.length - 1) {
                    digits[index + 1].focus();
                    digits[index + 1].select();
                }

                const code = updateCombined();
                if (code.length === 6) {
                    form.requestSubmit();
                }
            });

            digit.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && !digit.value && index > 0) {
                    digits[index - 1].focus();
                    digits[index - 1].value = '';
                    updateCombined();
                } else if (e.key === 'ArrowLeft' && index > 0) {
                    digits[index - 1].focus();
                } else if (e.key === 'ArrowRight' && index < digits.length - 1) {
                    digits[index + 1].focus();
                }
            });

            digit.addEventListener('paste', (e) => {
                e.preventDefault();
                const paste = (e.clipboardData || window.clipboardData).getData('text');
                const cleaned = paste.replace(/[^0-9]/g, '').slice(0, 6);
                
                cleaned.split('').forEach((char, idx) => {
                    if (digits[idx]) {
                        digits[idx].value = char;
                    }
                });

                const code = updateCombined();
                if (cleaned.length < 6) {
                    digits[Math.min(cleaned.length, digits.length - 1)].focus();
                } else if (code.length === 6) {
                    form.requestSubmit();
                }
            });
        });

        form.addEventListener('submit', () => {
            updateCombined();
            if (verifyBtn) {
                verifyBtn.disabled = true;
                verifyBtn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin text-xs"></i><span>Verifying...</span>';
            }
        });

        // 10-Minute Expiry Countdown Timer
        let secondsRemaining = 600;
        const timerEl = document.getElementById('countdown-timer');

        const timerInterval = setInterval(() => {
            secondsRemaining--;
            if (secondsRemaining <= 0) {
                clearInterval(timerInterval);
                if (timerEl) {
                    timerEl.textContent = 'Expired';
                    timerEl.classList.add('text-rose-500');
                }
            } else {
                const m = Math.floor(secondsRemaining / 60);
                const s = secondsRemaining % 60;
                if (timerEl) {
                    timerEl.textContent = `${m}:${s < 10 ? '0' : ''}${s}`;
                }
            }
        }, 1000);

        // Resend Cooldown Timer (60s)
        const resendBtn = document.getElementById('resend-btn');
        const resendCooldown = document.getElementById('resend-cooldown');
        let cooldown = 60;

        resendBtn.disabled = true;
        resendCooldown.classList.remove('hidden');

        const cooldownInterval = setInterval(() => {
            cooldown--;
            if (cooldown <= 0) {
                clearInterval(cooldownInterval);
                resendBtn.disabled = false;
                resendCooldown.classList.add('hidden');
            } else {
                resendCooldown.textContent = ` (${cooldown}s)`;
            }
        }, 1000);
    });
</script>
@endsection
