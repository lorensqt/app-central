<!DOCTYPE html>
<html lang="en" class="min-h-screen bg-slate-50 dark:bg-slate-950 transition-colors duration-300">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - App Central</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class'
        }
        // Prevent FOUC (Flash of Unstyled Content)
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
    @yield('styles')
</head>

<body class="min-h-screen flex flex-col justify-between relative bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 transition-colors duration-300">
    <!-- Premium Toast Notification Container -->
    <div id="toast-container" class="fixed top-20 right-6 z-50 flex flex-col gap-3 max-w-sm w-full pointer-events-none">
    </div>

    <!-- Premium Custom Confirm Modal Backdrop -->
    <div id="confirm-modal"
        class="fixed inset-0 bg-slate-900/40 dark:bg-slate-950/60 backdrop-blur-[2px] z-50 hidden items-center justify-center p-4 transition-all duration-300">
        <div id="confirm-modal-card"
            class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800/85 max-w-sm w-full shadow-xl p-6 space-y-6 transform scale-95 opacity-0 transition-all duration-300">
            <!-- Icon & Header -->
            <div class="flex items-start gap-4">
                <span class="p-3 rounded-xl bg-rose-50 dark:bg-rose-950/20 text-rose-600 dark:text-rose-400 shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </span>
                <div class="text-left">
                    <h3 id="confirm-modal-title" class="font-bold text-slate-900 dark:text-white text-lg">Confirm Action</h3>
                    <p id="confirm-modal-message" class="text-sm text-slate-500 dark:text-slate-400 mt-1.5 leading-relaxed"></p>
                    <p id="confirm-modal-sub" class="text-xs text-slate-400 dark:text-slate-500 mt-2 font-medium hidden"></p>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100 dark:border-slate-800">
                <button id="confirm-modal-cancel" type="button"
                    class="text-xs font-semibold py-2.5 px-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800/60 text-slate-700 dark:text-slate-300 transition duration-150">
                    Cancel
                </button>
                <button id="confirm-modal-approve" type="button"
                    class="text-xs font-semibold py-2.5 px-4 rounded-xl bg-red-600 hover:bg-red-700 text-white transition duration-150 shadow-sm">
                    Confirm Action
                </button>
            </div>
        </div>
    </div>

    <div>
        <!-- Top Navbar -->
        <nav class="bg-white dark:bg-slate-900 border-b border-slate-200/80 dark:border-slate-800/80 sticky top-0 z-40 transition-colors duration-300">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <!-- Left: Brand -->
                    <div class="flex items-center gap-3">
                        <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5">
                            <span
                                class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-slate-900 dark:bg-white text-white dark:text-slate-900 font-bold text-sm">
                                AC
                            </span>
                            <span class="font-semibold text-slate-900 dark:text-white tracking-tight text-lg">App Central</span>
                        </a>
                    </div>

                    <!-- Right: Theme Switcher, User Info & Logout -->
                    <div class="flex items-center gap-4">
                        <!-- Theme Toggle Button -->
                        <button id="theme-toggle" type="button"
                            class="text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-100 p-2 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800/60 transition duration-150 focus:outline-none focus:ring-2 focus:ring-purple-500/20 shrink-0">
                            <!-- Moon Icon (Visible in Light Mode) -->
                            <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                            </svg>
                            <!-- Sun Icon (Visible in Dark Mode) -->
                            <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m12.728 0l-.707-.707M6.343 6.343l-.707-.707M14 12a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </button>

                        @auth
                            <div class="flex items-center gap-3 pr-2 border-r border-slate-200 dark:border-slate-800">
                                @if (Auth::user()->avatar)
                                    <img class="w-8 h-8 rounded-full border border-slate-200 dark:border-slate-700"
                                        src="{{ Auth::user()->avatar }}" alt="{{ Auth::user()->name }}">
                                @else
                                    <div
                                        class="w-8 h-8 rounded-full bg-slate-900 dark:bg-slate-800 text-white dark:text-slate-300 flex items-center justify-center text-xs font-semibold uppercase">
                                        {{ substr(Auth::user()->name, 0, 2) }}
                                    </div>
                                @endif
                                <div class="hidden sm:block text-left">
                                    <div class="text-xs font-semibold text-slate-800 dark:text-slate-200 leading-none">
                                        {{ Auth::user()->name }}
                                        @if (Auth::user()->isSuperAdmin())
                                            <span
                                                class="ml-1 px-1.5 py-0.5 bg-red-100 dark:bg-red-950/40 text-red-700 dark:text-red-400 text-[10px] rounded font-bold uppercase tracking-wider">Super
                                                Admin</span>
                                        @endif
                                    </div>
                                    <div class="text-[10px] text-slate-500 dark:text-slate-400 mt-0.5 font-medium">
                                        {{ Auth::user()->title ? Auth::user()->title->title : (Auth::user()->isSuperAdmin() ? 'Platform Administrator' : 'Guest') }}
                                    </div>
                                </div>
                            </div>
                            <form action="{{ route('logout') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit"
                                    class="text-sm font-medium text-slate-600 dark:text-slate-400 hover:text-red-600 dark:hover:text-red-500 transition-colors py-1.5 px-3 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800/60">
                                    Logout
                                </button>
                            </form>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content Wrapper -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            @yield('content')
        </main>
    </div>

    <!-- Footer -->
    <footer class="bg-white dark:bg-slate-900 border-t border-slate-200/80 dark:border-slate-800/80 py-6 text-center text-xs text-slate-400 dark:text-slate-500 mt-12 transition-colors duration-300">
        &copy; {{ date('Y') }} App Central. All rights reserved.
    </footer>

    <!-- Toast & Confirm Engines -->
    <script>
        // --- TOAST NOTIFICATIONS ENGINE ---
        window.showToast = function(message, type = 'success') {
            const container = document.getElementById('toast-container');
            if (!container) return;

            // Custom config per notification type
            const config = {
                success: {
                    label: 'Success',
                    badgeBg: 'bg-emerald-50 text-emerald-600 ring-emerald-500/20',
                    progressBg: 'bg-emerald-500',
                    glow: 'shadow-emerald-500/5',
                    icon: `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>`
                },
                error: {
                    label: 'System Notice',
                    badgeBg: 'bg-rose-50 text-rose-600 ring-rose-500/20',
                    progressBg: 'bg-rose-500',
                    glow: 'shadow-rose-500/5',
                    icon: `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>`
                },
                danger: {
                    label: 'System Notice',
                    badgeBg: 'bg-rose-50 text-rose-600 ring-rose-500/20',
                    progressBg: 'bg-rose-500',
                    glow: 'shadow-rose-500/5',
                    icon: `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>`
                },
                warning: {
                    label: 'Warning',
                    badgeBg: 'bg-amber-50 text-amber-600 ring-amber-500/20',
                    progressBg: 'bg-amber-500',
                    glow: 'shadow-amber-500/5',
                    icon: `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>`
                },
                info: {
                    label: 'Notification',
                    badgeBg: 'bg-sky-50 text-sky-600 ring-sky-500/20',
                    progressBg: 'bg-sky-500',
                    glow: 'shadow-sky-500/5',
                    icon: `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>`
                }
            };

            const currentConfig = config[type] || config.info;
            const duration = 4000;

            // Toast Card Component
            const toast = document.createElement('div');
            toast.className =
                `group pointer-events-auto relative flex items-start gap-3.5 w-full max-w-sm p-4 overflow-hidden rounded-2xl bg-white/95 dark:bg-slate-900/95 backdrop-blur-xl border border-slate-200/80 dark:border-slate-800/80 shadow-xl ${currentConfig.glow} transition-all duration-300 ease-[cubic-bezier(0.16,1,0.3,1)] opacity-0 translate-x-8 scale-95 hover:border-slate-300 dark:hover:border-slate-700`;

            toast.innerHTML = `
            <!-- Icon -->
            <div class="flex items-center justify-center p-2 rounded-xl ring-1 ring-inset ${currentConfig.badgeBg} shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    ${currentConfig.icon}
                </svg>
            </div>

            <!-- Content -->
            <div class="flex-1 min-w-0 pt-0.5">
                <div class="flex items-center justify-between gap-2 mb-0.5">
                    <span class="text-[11px] font-semibold tracking-wider uppercase text-slate-400 dark:text-slate-500">${currentConfig.label}</span>
                </div>
                <p class="text-sm font-medium text-slate-700 dark:text-slate-200 leading-snug break-words">${message}</p>
            </div>

            <!-- Close Button -->
            <button class="toast-close text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 p-1.5 -mr-1 -mt-1 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            <!-- Progress Bar -->
            <div class="absolute bottom-0 left-0 right-0 h-0.5 bg-slate-100 dark:bg-slate-800">
                <div class="progress-bar h-full ${currentConfig.progressBg} transition-all ease-linear" style="width: 100%; duration: ${duration}ms;"></div>
            </div>
        `;

            // Manual dismiss handling with pause behavior
            let autoDismissTimer;
            let startTime = Date.now();
            let remainingTime = duration;

            const dismissToast = () => {
                toast.classList.add('opacity-0', 'translate-x-8', 'scale-95');
                setTimeout(() => toast.remove(), 300);
            };

            const startTimer = () => {
                startTime = Date.now();
                const progressBar = toast.querySelector('.progress-bar');
                if (progressBar) {
                    progressBar.style.transition = `width ${remainingTime}ms linear`;
                    progressBar.style.width = '0%';
                }
                autoDismissTimer = setTimeout(dismissToast, remainingTime);
            };

            const pauseTimer = () => {
                clearTimeout(autoDismissTimer);
                remainingTime -= Date.now() - startTime;
                const progressBar = toast.querySelector('.progress-bar');
                if (progressBar) {
                    const computedWidth = getComputedStyle(progressBar).width;
                    progressBar.style.transition = 'none';
                    progressBar.style.width = computedWidth;
                }
            };

            toast.querySelector('.toast-close').onclick = dismissToast;

            // Hover to pause countdown
            toast.addEventListener('mouseenter', pauseTimer);
            toast.addEventListener('mouseleave', () => {
                if (remainingTime > 0) startTimer();
            });

            container.appendChild(toast);

            // Entry animation frame
            requestAnimationFrame(() => {
                requestAnimationFrame(() => {
                    toast.classList.remove('opacity-0', 'translate-x-8', 'scale-95');
                    startTimer();
                });
            });
        };

        // --- PREMIUM CONFIRM MODAL ENGINE ---
        window.showConfirmModal = function(title, message, subtext, onConfirm) {
            const modal = document.getElementById('confirm-modal');
            const card = document.getElementById('confirm-modal-card');
            const titleEl = document.getElementById('confirm-modal-title');
            const messageEl = document.getElementById('confirm-modal-message');
            const subEl = document.getElementById('confirm-modal-sub');
            const cancelBtn = document.getElementById('confirm-modal-cancel');
            const approveBtn = document.getElementById('confirm-modal-approve');

            if (!modal || !card) return;

            titleEl.textContent = title;
            messageEl.textContent = message;

            if (subtext) {
                subEl.textContent = subtext;
                subEl.classList.remove('hidden');
            } else {
                subEl.classList.add('hidden');
            }

            modal.classList.remove('hidden');
            modal.classList.add('flex');

            requestAnimationFrame(() => {
                card.classList.remove('scale-95', 'opacity-0');
                card.classList.add('scale-100', 'opacity-100');
            });

            const closeModal = () => {
                card.classList.remove('scale-100', 'opacity-100');
                card.classList.add('scale-95', 'opacity-0');
                setTimeout(() => {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                }, 200);
            };

            cancelBtn.onclick = closeModal;
            approveBtn.onclick = () => {
                onConfirm();
                closeModal();
            };

            modal.onclick = function(e) {
                if (e.target === modal) closeModal();
            };
        };

        // Declarative submit listener for forms using data-confirm
        document.addEventListener('submit', function(e) {
            const form = e.target;
            if (form.hasAttribute('data-confirm')) {
                if (form.dataset.confirmed === 'true') {
                    return;
                }

                e.preventDefault();

                const title = form.getAttribute('data-confirm-title') || 'Confirm Deletion';
                const message = form.getAttribute('data-confirm');
                const subtext = form.getAttribute('data-confirm-sub') || '';

                window.showConfirmModal(title, message, subtext, () => {
                    form.dataset.confirmed = 'true';
                    form.submit();
                });
            }
        });

        // Automate flash toasts on load and initialize theme toggle buttons
        document.addEventListener('DOMContentLoaded', () => {
            @if (session('status') || session('success'))
                window.showToast("{{ session('status') ?? session('success') }}", 'success');
            @endif

            @if (session('error'))
                window.showToast("{{ session('error') }}", 'error');
            @endif

            // Theme Toggle Logic
            const themeToggleBtn = document.getElementById('theme-toggle');
            if (themeToggleBtn) {
                const themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
                const themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

                // Toggle visibility of sun/moon icon based on active theme
                if (document.documentElement.classList.contains('dark')) {
                    themeToggleLightIcon.classList.remove('hidden');
                    themeToggleDarkIcon.classList.add('hidden');
                } else {
                    themeToggleDarkIcon.classList.remove('hidden');
                    themeToggleLightIcon.classList.add('hidden');
                }

                themeToggleBtn.addEventListener('click', function() {
                    if (document.documentElement.classList.contains('dark')) {
                        document.documentElement.classList.remove('dark');
                        localStorage.setItem('theme', 'light');
                        themeToggleDarkIcon.classList.remove('hidden');
                        themeToggleLightIcon.classList.add('hidden');
                    } else {
                        document.documentElement.classList.add('dark');
                        localStorage.setItem('theme', 'dark');
                        themeToggleLightIcon.classList.remove('hidden');
                        themeToggleDarkIcon.classList.add('hidden');
                    }
                });
            }
        });
    </script>

    @yield('scripts')
</body>

</html>
