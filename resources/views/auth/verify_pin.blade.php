<!DOCTYPE html>
<html lang="en" class="min-h-screen bg-slate-950 transition-colors duration-300">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Access PIN - Sako Central Security</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class'
        }
        if (localStorage.getItem('theme') === 'light') {
            document.documentElement.classList.remove('dark');
        } else {
            document.documentElement.classList.add('dark');
        }
    </script>
    @php
        $faviconPath = resource_path('views/imgs/letter-s.png');
        $base64Favicon = '';
        if (file_exists($faviconPath)) {
            $base64Favicon = 'data:image/png;base64,' . base64_encode(file_get_contents($faviconPath));
        }
    @endphp
    @if($base64Favicon)
        <link rel="icon" type="image/png" href="{{ $base64Favicon }}">
    @endif
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .font-mono-num {
            font-family: 'JetBrains Mono', monospace;
        }
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            15% { transform: translateX(-10px); }
            30% { transform: translateX(10px); }
            45% { transform: translateX(-8px); }
            60% { transform: translateX(8px); }
            75% { transform: translateX(-4px); }
            90% { transform: translateX(4px); }
        }
        .animate-shake {
            animation: shake 0.5s cubic-bezier(0.36, 0.07, 0.19, 0.97) both;
        }
        @keyframes successPop {
            0% { transform: scale(0.9); opacity: 0; }
            50% { transform: scale(1.08); }
            100% { transform: scale(1); opacity: 1; }
        }
        .animate-success-pop {
            animation: successPop 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col justify-between bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 transition-colors duration-300 relative overflow-x-hidden selection:bg-purple-500 selection:text-white">

    <!-- Ambient Fintech Background Glow -->
    <div class="fixed inset-0 pointer-events-none overflow-hidden z-0">
        <div class="absolute -top-40 left-1/2 -translate-x-1/2 w-[650px] h-[650px] bg-gradient-to-tr from-purple-600/15 via-indigo-600/15 to-pink-500/10 rounded-full blur-3xl opacity-70 dark:opacity-40 pointer-events-none"></div>
        <div class="absolute -bottom-40 right-10 w-[450px] h-[450px] bg-gradient-to-br from-emerald-500/10 via-teal-500/10 to-indigo-500/10 rounded-full blur-3xl opacity-50 dark:opacity-30 pointer-events-none"></div>
    </div>

    <!-- Top Navigation Header -->
    <header class="relative z-10 w-full max-w-6xl mx-auto px-4 py-4 sm:px-6 sm:py-6 flex items-center justify-between">
        <!-- Brand Badge -->
        <div class="flex items-center gap-2.5 sm:gap-3">
            <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-gradient-to-tr from-purple-600/10 via-indigo-600/10 to-pink-500/10 dark:from-purple-900/40 dark:to-indigo-900/40 border border-purple-500/20 dark:border-purple-500/30 flex items-center justify-center p-1.5 shadow-md shadow-purple-500/10">
                @if($base64Favicon)
                    <img src="{{ $base64Favicon }}" alt="Sako Central Logo" class="w-full h-full object-contain" />
                @else
                    <span class="font-extrabold text-sm text-purple-600 dark:text-purple-400 font-mono">S</span>
                @endif
            </div>
            <div class="leading-none">
                <span class="text-sm font-black tracking-tight text-slate-900 dark:text-white block">Sako Central</span>
            </div>
        </div>

        <!-- Controls: Cancel & Theme -->
        <div class="flex items-center gap-2">
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="text-xs font-semibold py-1.5 px-3 sm:py-2 sm:px-3.5 rounded-xl border border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-400 hover:text-red-600 dark:hover:text-red-400 hover:border-red-200 dark:hover:border-red-900/40 bg-white/80 dark:bg-slate-900/80 backdrop-blur-md transition-all duration-150 shadow-xs active:scale-95">
                    <i class="fa-solid fa-arrow-right-from-bracket mr-1 text-[10px]"></i>
                    <span class="hidden sm:inline">Cancel </span>Login
                </button>
            </form>

            <button id="theme-toggle" type="button" class="text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-100 p-2 rounded-xl bg-white/80 dark:bg-slate-900/80 border border-slate-200/80 dark:border-slate-800/80 shadow-xs hover:shadow-sm hover:bg-slate-50 dark:hover:bg-slate-800 transition duration-150 focus:outline-none shrink-0 backdrop-blur-md active:scale-95" title="Toggle theme">
                <i id="theme-toggle-dark-icon" class="fa-solid fa-moon text-xs hidden"></i>
                <i id="theme-toggle-light-icon" class="fa-solid fa-sun text-xs hidden text-amber-400"></i>
            </button>
        </div>
    </header>

    <!-- Main Verification Stage -->
    <main class="relative z-10 flex-grow flex items-center justify-center px-3.5 sm:px-6 py-6 sm:py-12">
        <div id="pin-card" class="w-full max-w-[460px] bg-white/90 dark:bg-slate-900/90 backdrop-blur-2xl rounded-3xl border border-slate-200/90 dark:border-slate-800/80 shadow-2xl shadow-purple-500/5 px-4 py-6 sm:px-8 sm:py-10 transition-all duration-300 relative">
            
            <!-- Shield Status Badge -->
            <div class="text-center mb-6 sm:mb-7">
                <div id="shield-container" class="relative inline-flex items-center justify-center w-14 h-14 sm:w-16 sm:h-16 rounded-2xl bg-purple-50 dark:bg-purple-950/40 text-purple-600 dark:text-purple-400 mb-4 sm:mb-5 shadow-inner border border-purple-100/60 dark:border-purple-800/40 transition-all duration-300">
                    <i id="shield-icon" class="fa-solid fa-shield-halved text-xl sm:text-2xl transition-all duration-300"></i>
                    <div id="shield-glow" class="absolute -inset-1 rounded-2xl bg-purple-500/20 blur-md opacity-0 transition-opacity duration-300 -z-10"></div>
                </div>

                <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight">Two-Factor Authentication</h1>
                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1.5 sm:mt-2 leading-relaxed">
                    Enter your <span class="font-semibold text-slate-700 dark:text-slate-300">6-digit access PIN</span> to verify and unlock your workspace session.
                </p>
            </div>

            <!-- Attempts & Security Alert Banner -->
            @php
                $initialAttempts = session('pin_attempts', 0);
                $initialRemaining = 3 - $initialAttempts;
            @endphp
            <div id="attempts-badge" class="mb-5 sm:mb-6 py-1.5 sm:py-2 px-3 sm:px-4 rounded-xl text-center text-xs font-bold transition-all duration-300 flex items-center justify-center gap-2 {{ $initialRemaining === 1 ? 'bg-red-50 dark:bg-red-950/30 text-red-600 dark:text-red-400 border border-red-200 dark:border-red-900/40' : 'bg-slate-100/80 dark:bg-slate-800/60 text-slate-600 dark:text-slate-400 border border-slate-200/60 dark:border-slate-700/60' }}">
                <span class="w-2 h-2 rounded-full {{ $initialRemaining === 1 ? 'bg-red-500 animate-ping' : 'bg-purple-500' }}"></span>
                <span id="attempts-text">
                    @if($initialRemaining === 1)
                        CRITICAL: Final attempt remaining
                    @else
                        {{ $initialRemaining }} of 3 security attempts remaining
                    @endif
                </span>
            </div>

            <!-- Interactive PIN Input Matrix -->
            <form id="pin-form" action="{{ route('pin.verify.submit') }}" method="POST" autocomplete="off">
                @csrf
                <input type="hidden" name="pin" id="real-pin-value" value="">

                <div class="space-y-4">
                    <!-- 6-Cell Matrix (Fluid Flex Grid) -->
                    <div id="pin-cells-wrapper" class="flex items-center justify-between gap-1.5 sm:gap-2.5 transition-all duration-200">
                        @for ($i = 0; $i < 6; $i++)
                            <input type="password"
                                   id="digit-{{ $i }}"
                                   data-index="{{ $i }}"
                                   inputmode="numeric"
                                   pattern="[0-9]*"
                                   maxlength="1"
                                   autocomplete="one-time-code"
                                   class="pin-digit-cell flex-1 min-w-0 max-w-[56px] h-13 sm:h-16 rounded-xl sm:rounded-2xl border-2 border-slate-200 dark:border-slate-800 bg-slate-50/80 dark:bg-slate-950/60 text-slate-900 dark:text-white font-mono-num text-xl sm:text-2xl font-bold text-center focus:outline-none focus:border-purple-600 dark:focus:border-purple-500 focus:bg-white dark:focus:bg-slate-900 focus:ring-4 focus:ring-purple-500/15 focus:scale-105 transition-all duration-200 caret-transparent shadow-xs"
                                   aria-label="PIN Digit {{ $i + 1 }}">
                        @endfor
                    </div>

                    <!-- Live Dynamic Feedback Line -->
                    <div id="feedback-message" class="min-h-[22px] text-center text-xs font-semibold text-slate-400 dark:text-slate-500 transition-all duration-200">
                        Type or paste your 6-digit PIN code
                    </div>
                </div>

                <!-- Fallback Submit Button -->
                <div class="mt-6">
                    <button type="submit" id="submit-btn" class="w-full inline-flex items-center justify-center gap-2 text-sm font-bold py-3.5 px-4 rounded-xl bg-purple-600 hover:bg-purple-700 active:bg-purple-800 text-white shadow-md shadow-purple-600/20 active:scale-[0.98] transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-purple-500/40 disabled:opacity-50 disabled:cursor-not-allowed">
                        <span id="btn-text">Verify Access</span>
                        <i id="btn-spinner" class="fa-solid fa-circle-notch fa-spin text-sm hidden"></i>
                    </button>
                </div>
            </form>

            <!-- Locked Screen Overlay (Revealed on 3 Failed Attempts) -->
            <div id="lockout-overlay" class="absolute inset-0 bg-white/95 dark:bg-slate-900/95 backdrop-blur-md rounded-3xl p-8 flex flex-col items-center justify-center text-center hidden z-20">
                <div class="w-16 h-16 rounded-2xl bg-red-100 dark:bg-red-950/40 text-red-600 dark:text-red-400 flex items-center justify-center mb-4 text-2xl">
                    <i class="fa-solid fa-lock"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-900 dark:text-white">Authentication Locked</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-2 max-w-xs leading-relaxed">
                    Maximum attempts exceeded. Your session has been automatically invalidated for security. Redirecting...
                </p>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="relative z-10 py-6 text-center text-xs text-slate-400 dark:text-slate-600 border-t border-slate-200/50 dark:border-slate-800/60 transition-colors duration-300">
        <div class="flex items-center justify-center gap-2 mb-1">
            <i class="fa-solid fa-shield-cat text-purple-500 text-xs"></i>
            <span class="font-medium text-slate-600 dark:text-slate-400">Sako Central Enterprise Security</span>
        </div>
        &copy; {{ date('Y') }} All access strictly logged and monitored.
    </footer>

    <!-- Interactive Fintech Logic Script -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const cells = Array.from(document.querySelectorAll('.pin-digit-cell'));
            const form = document.getElementById('pin-form');
            const realPinInput = document.getElementById('real-pin-value');
            const feedbackEl = document.getElementById('feedback-message');
            const attemptsBadge = document.getElementById('attempts-badge');
            const attemptsText = document.getElementById('attempts-text');
            const cellsWrapper = document.getElementById('pin-cells-wrapper');
            const shieldContainer = document.getElementById('shield-container');
            const shieldIcon = document.getElementById('shield-icon');
            const shieldGlow = document.getElementById('shield-glow');
            const submitBtn = document.getElementById('submit-btn');
            const btnText = document.getElementById('btn-text');
            const btnSpinner = document.getElementById('btn-spinner');
            const lockoutOverlay = document.getElementById('lockout-overlay');

            let isVerifying = false;

            // Focus first cell on launch
            setTimeout(() => {
                if (cells[0]) cells[0].focus();
            }, 100);

            // Collect current 6 digits
            function getEnteredPin() {
                return cells.map(c => c.value).join('');
            }

            // Update cells styling based on filled state
            function refreshCellStates() {
                cells.forEach(c => {
                    if (c.value) {
                        c.classList.add('border-slate-400', 'dark:border-slate-600', 'bg-white', 'dark:bg-slate-900');
                    } else {
                        c.classList.remove('border-slate-400', 'dark:border-slate-600', 'bg-white', 'dark:bg-slate-900');
                    }
                });
            }

            // Keyboard navigation & auto-advance
            cells.forEach((cell, idx) => {
                cell.addEventListener('input', (e) => {
                    const val = cell.value.replace(/[^0-9]/g, '');
                    cell.value = val ? val.slice(-1) : '';

                    refreshCellStates();

                    if (cell.value && idx < cells.length - 1) {
                        cells[idx + 1].focus();
                    }

                    checkAndAutoVerify();
                });

                cell.addEventListener('keydown', (e) => {
                    if (e.key === 'Backspace') {
                        if (!cell.value && idx > 0) {
                            cells[idx - 1].focus();
                            cells[idx - 1].value = '';
                            refreshCellStates();
                            e.preventDefault();
                        } else {
                            cell.value = '';
                            refreshCellStates();
                        }
                    } else if (e.key === 'ArrowLeft' && idx > 0) {
                        cells[idx - 1].focus();
                    } else if (e.key === 'ArrowRight' && idx < cells.length - 1) {
                        cells[idx + 1].focus();
                    }
                });

                // Focus styling helper
                cell.addEventListener('focus', () => {
                    cell.select();
                });

                // Paste support across all 6 cells
                cell.addEventListener('paste', (e) => {
                    e.preventDefault();
                    const pastedData = (e.clipboardData || window.clipboardData).getData('text').replace(/[^0-9]/g, '');
                    if (pastedData) {
                        for (let i = 0; i < cells.length; i++) {
                            cells[i].value = pastedData[i] || '';
                        }
                        refreshCellStates();
                        const nextIndex = Math.min(pastedData.length, cells.length - 1);
                        cells[nextIndex].focus();
                        checkAndAutoVerify();
                    }
                });
            });

            // Auto-Verify when 6 digits are complete
            function checkAndAutoVerify() {
                const pin = getEnteredPin();
                realPinInput.value = pin;

                if (pin.length === 6 && !isVerifying) {
                    performVerification(pin);
                }
            }

            // Asynchronous verification with live animations
            function performVerification(pin) {
                isVerifying = true;
                setLoadingState(true);

                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ pin: pin })
                })
                .then(async (response) => {
                    const data = await response.json();
                    if (response.ok && data.success) {
                        handleSuccess(data);
                    } else {
                        handleError(data, response.status);
                    }
                })
                .catch((err) => {
                    console.error('PIN verification error:', err);
                    handleError({ message: 'Network connection error. Please try again.' });
                });
            }

            function setLoadingState(loading) {
                cells.forEach(c => c.disabled = loading);
                if (loading) {
                    btnText.innerText = 'Verifying...';
                    btnSpinner.classList.remove('hidden');
                    feedbackEl.innerHTML = '<span class="text-purple-600 dark:text-purple-400"><i class="fa-solid fa-spinner fa-spin mr-1"></i> Authorizing credentials...</span>';
                    shieldIcon.className = 'fa-solid fa-circle-notch fa-spin text-2xl text-purple-600 dark:text-purple-400';
                    shieldGlow.classList.remove('opacity-0');
                } else {
                    btnText.innerText = 'Verify Access';
                    btnSpinner.classList.add('hidden');
                    shieldIcon.className = 'fa-solid fa-shield-halved text-2xl text-purple-600 dark:text-purple-400';
                    shieldGlow.classList.add('opacity-0');
                }
            }

            function handleSuccess(data) {
                // Success Emerald Aura
                shieldContainer.className = 'relative inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 mb-5 shadow-inner border border-emerald-200 dark:border-emerald-800 animate-success-pop';
                shieldIcon.className = 'fa-solid fa-check text-2xl text-emerald-600 dark:text-emerald-400';

                cells.forEach(c => {
                    c.classList.remove('border-slate-200', 'dark:border-slate-800', 'border-slate-400');
                    c.classList.add('border-emerald-500', 'bg-emerald-50/40', 'dark:bg-emerald-950/30', 'text-emerald-600', 'dark:text-emerald-400');
                });

                feedbackEl.innerHTML = '<span class="text-emerald-600 dark:text-emerald-400 font-bold"><i class="fa-solid fa-circle-check mr-1"></i> Identity Verified! Redirecting...</span>';
                submitBtn.className = 'w-full inline-flex items-center justify-center gap-2 text-sm font-bold py-3.5 px-4 rounded-xl bg-emerald-600 text-white shadow-md shadow-emerald-600/20';
                btnText.innerText = 'Access Authorized';
                btnSpinner.classList.add('hidden');

                setTimeout(() => {
                    window.location.href = data.redirect || '/dashboard';
                }, 400);
            }

            function handleError(data, status) {
                setLoadingState(false);
                isVerifying = false;

                // Lockout Check
                if (data.locked_out || status === 403) {
                    lockoutOverlay.classList.remove('hidden');
                    setTimeout(() => {
                        window.location.href = data.redirect || '/';
                    }, 1200);
                    return;
                }

                // Error Shake
                cellsWrapper.classList.remove('animate-shake');
                void cellsWrapper.offsetWidth; // Trigger reflow
                cellsWrapper.classList.add('animate-shake');

                cells.forEach(c => {
                    c.classList.add('border-red-500', 'bg-red-50/20', 'text-red-500');
                });

                // Update attempts left badge
                if (data.attempts_left !== undefined) {
                    const remaining = data.attempts_left;
                    if (remaining === 1) {
                        attemptsBadge.className = 'mb-6 py-2 px-4 rounded-xl text-center text-xs font-bold transition-all duration-300 flex items-center justify-center gap-2 bg-red-50 dark:bg-red-950/30 text-red-600 dark:text-red-400 border border-red-200 dark:border-red-900/40';
                        attemptsText.innerText = 'CRITICAL: Final attempt remaining';
                    } else {
                        attemptsText.innerText = `${remaining} of 3 security attempts remaining`;
                    }
                }

                feedbackEl.innerHTML = `<span class="text-red-600 dark:text-red-400"><i class="fa-solid fa-triangle-exclamation mr-1"></i> ${data.message || 'Incorrect PIN code.'}</span>`;

                // Auto-clear and reset focus to digit 1
                setTimeout(() => {
                    cells.forEach(c => {
                        c.value = '';
                        c.classList.remove('border-red-500', 'bg-red-50/20', 'text-red-500');
                    });
                    refreshCellStates();
                    cells[0].focus();
                }, 700);
            }

            // Handle manual form submit
            form.addEventListener('submit', (e) => {
                e.preventDefault();
                checkAndAutoVerify();
            });
        });

        // Theme Toggle Controller
        const themeToggleBtn = document.getElementById('theme-toggle');
        const themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
        const themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

        function updateThemeIcons() {
            if (document.documentElement.classList.contains('dark')) {
                themeToggleLightIcon.classList.remove('hidden');
                themeToggleDarkIcon.classList.add('hidden');
            } else {
                themeToggleDarkIcon.classList.remove('hidden');
                themeToggleLightIcon.classList.add('hidden');
            }
        }
        updateThemeIcons();

        themeToggleBtn.addEventListener('click', function() {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            }
            updateThemeIcons();
        });
    </script>
</body>
</html>
