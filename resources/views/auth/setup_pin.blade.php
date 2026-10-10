<!DOCTYPE html>
<html lang="en" class="min-h-screen bg-slate-950 transition-colors duration-300">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configure Access PIN - Sako Central Security</title>
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

        <!-- Controls: Sign out & Theme -->
        <div class="flex items-center gap-2">
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="text-xs font-semibold py-1.5 px-3 sm:py-2 sm:px-3.5 rounded-xl border border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-400 hover:text-red-600 dark:hover:text-red-400 hover:border-red-200 dark:hover:border-red-900/40 bg-white/80 dark:bg-slate-900/80 backdrop-blur-md transition-all duration-150 shadow-xs active:scale-95">
                    <i class="fa-solid fa-arrow-right-from-bracket mr-1 text-[10px]"></i>
                    Logout
                </button>
            </form>

            <button id="theme-toggle" type="button" class="text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-100 p-2 rounded-xl bg-white/80 dark:bg-slate-900/80 border border-slate-200/80 dark:border-slate-800/80 shadow-xs hover:shadow-sm hover:bg-slate-50 dark:hover:bg-slate-800 transition duration-150 focus:outline-none shrink-0 backdrop-blur-md active:scale-95" title="Toggle theme">
                <i id="theme-toggle-dark-icon" class="fa-solid fa-moon text-xs hidden"></i>
                <i id="theme-toggle-light-icon" class="fa-solid fa-sun text-xs hidden text-amber-400"></i>
            </button>
        </div>
    </header>

    <!-- Main Configuration Stage -->
    <main class="relative z-10 flex-grow flex items-center justify-center px-3.5 sm:px-6 py-6 sm:py-12">
        <div id="setup-card" class="w-full max-w-[480px] bg-white/90 dark:bg-slate-900/90 backdrop-blur-2xl rounded-3xl border border-slate-200/90 dark:border-slate-800/80 shadow-2xl shadow-purple-500/5 px-4 py-6 sm:px-8 sm:py-10 transition-all duration-300 relative overflow-hidden">
            
            <!-- Shield Status Badge -->
            <div class="text-center mb-6">
                <div id="setup-icon-container" class="relative inline-flex items-center justify-center w-14 h-14 sm:w-16 sm:h-16 rounded-2xl bg-purple-50 dark:bg-purple-950/40 text-purple-600 dark:text-purple-400 mb-3 sm:mb-4 shadow-inner border border-purple-100/60 dark:border-purple-800/40 transition-all duration-300">
                    <i id="setup-icon" class="fa-solid fa-key text-xl sm:text-2xl transition-all duration-300"></i>
                    <div id="setup-glow" class="absolute -inset-1 rounded-2xl bg-purple-500/20 blur-md opacity-0 transition-opacity duration-300 -z-10"></div>
                </div>

                <div class="flex items-center justify-center gap-2 mb-2">
                    <!-- Step Progress Indicator Pills -->
                    <span id="step-pill-1" class="h-1.5 w-8 rounded-full bg-purple-600 transition-all duration-300"></span>
                    <span id="step-pill-2" class="h-1.5 w-8 rounded-full bg-slate-200 dark:bg-slate-800 transition-all duration-300"></span>
                </div>

                <h1 id="stage-title" class="text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight transition-all duration-200">
                    Create 6-Digit PIN
                </h1>
                <p id="stage-desc" class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1.5 leading-relaxed transition-all duration-200">
                    Establish a secure numeric PIN for frictionless two-factor logins.
                </p>
            </div>

            <!-- Form Wrapper with 2 Sliding Stages -->
            <form id="setup-form" action="{{ route('pin.save') }}" method="POST" autocomplete="off">
                @csrf
                <input type="hidden" name="pin" id="hidden_pin" value="">
                <input type="hidden" name="pin_confirmation" id="hidden_pin_confirmation" value="">

                <!-- STAGE 1: CREATE PIN -->
                <div id="stage-1" class="transition-all duration-300 space-y-4 sm:space-y-5">
                    <div id="cells-wrapper-1" class="flex items-center justify-between gap-1.5 sm:gap-2.5">
                        @for ($i = 0; $i < 6; $i++)
                            <input type="password"
                                   id="create-digit-{{ $i }}"
                                   data-index="{{ $i }}"
                                   inputmode="numeric"
                                   pattern="[0-9]*"
                                   maxlength="1"
                                   autocomplete="off"
                                   class="pin-cell-create flex-1 min-w-0 max-w-[56px] h-13 sm:h-16 rounded-xl sm:rounded-2xl border-2 border-slate-200 dark:border-slate-800 bg-slate-50/80 dark:bg-slate-950/60 text-slate-900 dark:text-white font-mono-num text-xl sm:text-2xl font-bold text-center focus:outline-none focus:border-purple-600 dark:focus:border-purple-500 focus:bg-white dark:focus:bg-slate-900 focus:ring-4 focus:ring-purple-500/15 focus:scale-105 transition-all duration-200 caret-transparent shadow-xs"
                                   aria-label="Create PIN Digit {{ $i + 1 }}">
                        @endfor
                    </div>

                    <div id="feedback-step-1" class="min-h-[20px] text-center text-xs font-semibold text-slate-400 dark:text-slate-500">
                        Choose a memorizable 6-digit passcode
                    </div>
                </div>

                <!-- STAGE 2: CONFIRM PIN (Initially Hidden) -->
                <div id="stage-2" class="hidden transition-all duration-300 space-y-4 sm:space-y-5">
                    <div class="flex items-center justify-between px-1">
                        <button type="button" id="btn-back-to-step1" class="text-xs font-bold text-purple-600 dark:text-purple-400 hover:text-purple-700 flex items-center gap-1.5 focus:outline-none transition-colors">
                            <i class="fa-solid fa-arrow-left text-[10px]"></i>
                            <span>Edit initial PIN</span>
                        </button>
                        <span class="text-[11px] font-semibold text-slate-400 dark:text-slate-500">Step 2 of 2</span>
                    </div>

                    <div id="cells-wrapper-2" class="flex items-center justify-between gap-1.5 sm:gap-2.5">
                        @for ($i = 0; $i < 6; $i++)
                            <input type="password"
                                   id="confirm-digit-{{ $i }}"
                                   data-index="{{ $i }}"
                                   inputmode="numeric"
                                   pattern="[0-9]*"
                                   maxlength="1"
                                   autocomplete="off"
                                   class="pin-cell-confirm flex-1 min-w-0 max-w-[56px] h-13 sm:h-16 rounded-xl sm:rounded-2xl border-2 border-slate-200 dark:border-slate-800 bg-slate-50/80 dark:bg-slate-950/60 text-slate-900 dark:text-white font-mono-num text-xl sm:text-2xl font-bold text-center focus:outline-none focus:border-purple-600 dark:focus:border-purple-500 focus:bg-white dark:focus:bg-slate-900 focus:ring-4 focus:ring-purple-500/15 focus:scale-105 transition-all duration-200 caret-transparent shadow-xs"
                                   aria-label="Confirm PIN Digit {{ $i + 1 }}">
                        @endfor
                    </div>

                    <div id="feedback-step-2" class="min-h-[20px] text-center text-xs font-semibold text-slate-400 dark:text-slate-500">
                        Re-enter the 6 digits to verify match
                    </div>
                </div>

                <!-- Action Button -->
                <div class="mt-7">
                    <button type="button" id="stage-btn" class="w-full inline-flex items-center justify-center gap-2 text-sm font-bold py-3.5 px-4 rounded-xl bg-purple-600 hover:bg-purple-700 active:bg-purple-800 text-white shadow-md shadow-purple-600/20 active:scale-[0.98] transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-purple-500/40 disabled:opacity-50 disabled:cursor-not-allowed">
                        <span id="stage-btn-text">Continue</span>
                        <i id="stage-btn-spinner" class="fa-solid fa-circle-notch fa-spin text-sm hidden"></i>
                    </button>
                </div>
            </form>
        </div>
    </main>

    <!-- Footer -->
    <footer class="relative z-10 py-6 text-center text-xs text-slate-400 dark:text-slate-600 border-t border-slate-200/50 dark:border-slate-800/60 transition-colors duration-300">
        <div class="flex items-center justify-center gap-2 mb-1">
            <i class="fa-solid fa-lock text-purple-500 text-xs"></i>
            <span class="font-medium text-slate-600 dark:text-slate-400">Encrypted PIN Credentials Storage</span>
        </div>
        &copy; {{ date('Y') }} Sako Central. All rights reserved.
    </footer>

    <!-- Interactive Fintech Setup Wizard Script -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const createCells = Array.from(document.querySelectorAll('.pin-cell-create'));
            const confirmCells = Array.from(document.querySelectorAll('.pin-cell-confirm'));
            const stage1 = document.getElementById('stage-1');
            const stage2 = document.getElementById('stage-2');
            const stageTitle = document.getElementById('stage-title');
            const stageDesc = document.getElementById('stage-desc');
            const stepPill1 = document.getElementById('step-pill-1');
            const stepPill2 = document.getElementById('step-pill-2');
            const btnBack = document.getElementById('btn-back-to-step1');
            const stageBtn = document.getElementById('stage-btn');
            const stageBtnText = document.getElementById('stage-btn-text');
            const stageBtnSpinner = document.getElementById('stage-btn-spinner');
            const feedback1 = document.getElementById('feedback-step-1');
            const feedback2 = document.getElementById('feedback-step-2');
            const cellsWrapper1 = document.getElementById('cells-wrapper-1');
            const cellsWrapper2 = document.getElementById('cells-wrapper-2');
            const setupIconContainer = document.getElementById('setup-icon-container');
            const setupIcon = document.getElementById('setup-icon');
            const setupGlow = document.getElementById('setup-glow');
            const hiddenPin = document.getElementById('hidden_pin');
            const hiddenPinConfirm = document.getElementById('hidden_pin_confirmation');
            const form = document.getElementById('setup-form');

            let currentStep = 1;
            let pinVal = '';
            let confirmVal = '';
            let isSubmitting = false;

            // Auto-focus first cell
            setTimeout(() => {
                if (createCells[0]) createCells[0].focus();
            }, 100);

            // Cell states sync
            function syncCells(cellsList) {
                cellsList.forEach(c => {
                    if (c.value) {
                        c.classList.add('border-slate-400', 'dark:border-slate-600', 'bg-white', 'dark:bg-slate-900');
                    } else {
                        c.classList.remove('border-slate-400', 'dark:border-slate-600', 'bg-white', 'dark:bg-slate-900');
                    }
                });
            }

            // Wire input events for a set of cells
            function setupCellHandlers(cellsList, onComplete) {
                cellsList.forEach((cell, idx) => {
                    cell.addEventListener('input', (e) => {
                        const val = cell.value.replace(/[^0-9]/g, '');
                        cell.value = val ? val.slice(-1) : '';
                        syncCells(cellsList);

                        if (cell.value && idx < cellsList.length - 1) {
                            cellsList[idx + 1].focus();
                        }

                        const currentVal = cellsList.map(c => c.value).join('');
                        if (currentVal.length === 6) {
                            onComplete(currentVal);
                        }
                    });

                    cell.addEventListener('keydown', (e) => {
                        if (e.key === 'Backspace') {
                            if (!cell.value && idx > 0) {
                                cellsList[idx - 1].focus();
                                cellsList[idx - 1].value = '';
                                syncCells(cellsList);
                                e.preventDefault();
                            } else {
                                cell.value = '';
                                syncCells(cellsList);
                            }
                        } else if (e.key === 'ArrowLeft' && idx > 0) {
                            cellsList[idx - 1].focus();
                        } else if (e.key === 'ArrowRight' && idx < cellsList.length - 1) {
                            cellsList[idx + 1].focus();
                        }
                    });

                    cell.addEventListener('focus', () => {
                        cell.select();
                    });

                    cell.addEventListener('paste', (e) => {
                        e.preventDefault();
                        const pasted = (e.clipboardData || window.clipboardData).getData('text').replace(/[^0-9]/g, '');
                        if (pasted) {
                            for (let i = 0; i < cellsList.length; i++) {
                                cellsList[i].value = pasted[i] || '';
                            }
                            syncCells(cellsList);
                            const nextIndex = Math.min(pasted.length, cellsList.length - 1);
                            cellsList[nextIndex].focus();

                            const currentVal = cellsList.map(c => c.value).join('');
                            if (currentVal.length === 6) {
                                onComplete(currentVal);
                            }
                        }
                    });
                });
            }

            // Setup Step 1 Handlers
            setupCellHandlers(createCells, (pin) => {
                pinVal = pin;
                hiddenPin.value = pin;
                // Auto-advance to Step 2 smoothly
                goToStep2();
            });

            // Setup Step 2 Handlers
            setupCellHandlers(confirmCells, (confirm) => {
                confirmVal = confirm;
                hiddenPinConfirm.value = confirm;
                // Auto-check match immediately
                checkAndSavePin();
            });

            function goToStep1() {
                currentStep = 1;
                stage2.classList.add('hidden');
                stage1.classList.remove('hidden');

                stepPill1.className = 'h-1.5 w-8 rounded-full bg-purple-600 transition-all duration-300';
                stepPill2.className = 'h-1.5 w-8 rounded-full bg-slate-200 dark:bg-slate-800 transition-all duration-300';

                stageTitle.innerText = 'Create 6-Digit PIN';
                stageDesc.innerText = 'Establish a secure numeric PIN for frictionless two-factor logins.';
                stageBtnText.innerText = 'Continue';

                setupIcon.className = 'fa-solid fa-key text-2xl transition-all duration-300';

                // Focus last cell or first
                createCells[createCells.length - 1].focus();
            }

            function goToStep2() {
                currentStep = 2;
                stage1.classList.add('hidden');
                stage2.classList.remove('hidden');

                stepPill1.className = 'h-1.5 w-8 rounded-full bg-purple-200 dark:bg-purple-900/60 transition-all duration-300';
                stepPill2.className = 'h-1.5 w-8 rounded-full bg-purple-600 transition-all duration-300';

                stageTitle.innerText = 'Confirm Your PIN';
                stageDesc.innerText = 'Re-enter the 6 digits to verify and activate your PIN.';
                stageBtnText.innerText = 'Save & Activate';

                setupIcon.className = 'fa-solid fa-shield-check text-2xl transition-all duration-300';

                // Clear confirm cells and focus first
                confirmCells.forEach(c => {
                    c.value = '';
                    c.classList.remove('border-red-500', 'bg-red-50/20', 'text-red-500');
                });
                syncCells(confirmCells);

                setTimeout(() => {
                    confirmCells[0].focus();
                }, 100);
            }

            btnBack.addEventListener('click', goToStep1);

            // Check match and submit via AJAX
            function checkAndSavePin() {
                if (isSubmitting) return;

                if (confirmVal !== pinVal) {
                    // Mismatch!
                    cellsWrapper2.classList.remove('animate-shake');
                    void cellsWrapper2.offsetWidth; // Trigger reflow
                    cellsWrapper2.classList.add('animate-shake');

                    confirmCells.forEach(c => {
                        c.classList.add('border-red-500', 'bg-red-50/20', 'text-red-500');
                    });

                    feedback2.innerHTML = '<span class="text-red-600 dark:text-red-400 font-semibold"><i class="fa-solid fa-triangle-exclamation mr-1"></i> Passcodes do not match. Please try again.</span>';

                    setTimeout(() => {
                        confirmCells.forEach(c => {
                            c.value = '';
                            c.classList.remove('border-red-500', 'bg-red-50/20', 'text-red-500');
                        });
                        syncCells(confirmCells);
                        confirmCells[0].focus();
                    }, 800);
                    return;
                }

                // Match! Save via AJAX
                isSubmitting = true;
                setSavingState(true);

                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        pin: pinVal,
                        pin_confirmation: confirmVal
                    })
                })
                .then(async (response) => {
                    const data = await response.json();
                    if (response.ok && data.success) {
                        handleSaveSuccess(data);
                    } else {
                        handleSaveError(data);
                    }
                })
                .catch((err) => {
                    console.error('PIN save error:', err);
                    handleSaveError({ message: 'Network error occurred. Please try again.' });
                });
            }

            function setSavingState(loading) {
                confirmCells.forEach(c => c.disabled = loading);
                if (loading) {
                    stageBtnText.innerText = 'Securing Credentials...';
                    stageBtnSpinner.classList.remove('hidden');
                    feedback2.innerHTML = '<span class="text-purple-600 dark:text-purple-400"><i class="fa-solid fa-spinner fa-spin mr-1"></i> Encrypting access PIN...</span>';
                    setupIcon.className = 'fa-solid fa-circle-notch fa-spin text-2xl text-purple-600 dark:text-purple-400';
                    setupGlow.classList.remove('opacity-0');
                } else {
                    stageBtnText.innerText = 'Save & Activate';
                    stageBtnSpinner.classList.add('hidden');
                    setupIcon.className = 'fa-solid fa-shield-check text-2xl text-purple-600 dark:text-purple-400';
                    setupGlow.classList.add('opacity-0');
                }
            }

            function handleSaveSuccess(data) {
                // Success Emerald Aura
                setupIconContainer.className = 'relative inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 mb-4 shadow-inner border border-emerald-200 dark:border-emerald-800 animate-success-pop';
                setupIcon.className = 'fa-solid fa-check text-2xl text-emerald-600 dark:text-emerald-400';

                confirmCells.forEach(c => {
                    c.classList.remove('border-slate-200', 'dark:border-slate-800', 'border-slate-400');
                    c.classList.add('border-emerald-500', 'bg-emerald-50/40', 'dark:bg-emerald-950/30', 'text-emerald-600', 'dark:text-emerald-400');
                });

                stageTitle.innerText = 'PIN Configured!';
                stageDesc.innerText = 'Your security passcode is active. Entering dashboard...';
                feedback2.innerHTML = '<span class="text-emerald-600 dark:text-emerald-400 font-bold"><i class="fa-solid fa-circle-check mr-1"></i> Success! Redirecting...</span>';
                stageBtn.className = 'w-full inline-flex items-center justify-center gap-2 text-sm font-bold py-3.5 px-4 rounded-xl bg-emerald-600 text-white shadow-md shadow-emerald-600/20';
                stageBtnText.innerText = 'Security Activated';
                stageBtnSpinner.classList.add('hidden');

                setTimeout(() => {
                    window.location.href = data.redirect || '/dashboard';
                }, 500);
            }

            function handleSaveError(data) {
                setSavingState(false);
                isSubmitting = false;

                cellsWrapper2.classList.remove('animate-shake');
                void cellsWrapper2.offsetWidth;
                cellsWrapper2.classList.add('animate-shake');

                feedback2.innerHTML = `<span class="text-red-600 dark:text-red-400"><i class="fa-solid fa-triangle-exclamation mr-1"></i> ${data.message || 'Configuration error.'}</span>`;
            }

            // Stage Button click action
            stageBtn.addEventListener('click', () => {
                if (currentStep === 1) {
                    const pin = createCells.map(c => c.value).join('');
                    if (pin.length === 6) {
                        pinVal = pin;
                        hiddenPin.value = pin;
                        goToStep2();
                    } else {
                        createCells.find(c => !c.value)?.focus();
                    }
                } else {
                    const confirm = confirmCells.map(c => c.value).join('');
                    if (confirm.length === 6) {
                        confirmVal = confirm;
                        hiddenPinConfirm.value = confirm;
                        checkAndSavePin();
                    } else {
                        confirmCells.find(c => !c.value)?.focus();
                    }
                }
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
