<!DOCTYPE html>
<html lang="en" class="min-h-screen bg-slate-50 dark:bg-slate-950 transition-colors duration-300">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>@yield('title', 'Election Portal') - SAKO Cooperative</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class'
        }
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
            -webkit-tap-highlight-color: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar {
            width: 5px;
            height: 5px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: rgba(156, 163, 175, 0.25);
            border-radius: 20px;
        }
        /* Mobile safe-area padding */
        .safe-bottom {
            padding-bottom: max(0.75rem, env(safe-area-inset-bottom));
        }
    </style>
</head>

<body class="min-h-screen flex flex-col justify-between relative bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 overflow-x-hidden transition-colors duration-300">
    <!-- Decorative Glowing Orbs -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none z-0">
        <div class="absolute -top-32 -left-32 sm:-top-40 sm:-left-40 w-72 sm:w-[550px] h-72 sm:h-[550px] bg-purple-300/15 dark:bg-purple-900/15 rounded-full blur-[90px] sm:blur-[120px]"></div>
        <div class="absolute top-1/3 -right-20 sm:right-0 w-64 sm:w-[450px] h-64 sm:h-[450px] bg-indigo-300/15 dark:bg-indigo-900/15 rounded-full blur-[80px] sm:blur-[100px]"></div>
        <div class="absolute -bottom-20 left-1/4 w-80 sm:w-[600px] h-80 sm:h-[600px] bg-fuchsia-300/10 dark:bg-fuchsia-900/10 rounded-full blur-[90px] sm:blur-[130px]"></div>
    </div>

    <!-- Isolated Public Voter Navigation Bar (No Workspaces, No Admin Links) -->
    <header class="sticky top-0 z-40 bg-white/90 dark:bg-slate-900/90 backdrop-blur-xl border-b border-slate-200/70 dark:border-slate-800/70 transition-colors duration-300">
        <div class="max-w-6xl mx-auto px-3.5 sm:px-6 lg:px-8 py-2.5 sm:py-3 flex items-center justify-between gap-3">
            <!-- Brand / Logo -->
            <a href="{{ isset($election) ? route('elections.public_show', $election) : '#' }}" class="flex items-center gap-2 sm:gap-3 group select-none shrink-0">
                <img src="{{ asset('img/sako-logo-nobg.png') }}" alt="SAKO Logo" class="h-8 sm:h-10 w-auto object-contain transition-transform duration-300 group-hover:scale-105 drop-shadow-xs" />
                <div class="block text-left">
                    <span class="block text-[10px] sm:text-xs font-black tracking-wider uppercase text-purple-600 dark:text-purple-400 leading-tight">SAKO Cooperative</span>
                    <span class="block text-xs sm:text-sm font-extrabold text-slate-900 dark:text-white leading-tight">Secret Ballot Booth</span>
                </div>
            </a>

            <!-- Right Controls: Voter Status, Theme Toggle, Exit Link -->
            <div class="flex items-center gap-2 sm:gap-3 shrink-0">
                @if(isset($voter) && $voter->email)
                    <div class="hidden sm:flex items-center gap-2 px-3 py-1.5 rounded-xl bg-purple-50 dark:bg-purple-950/40 border border-purple-200/60 dark:border-purple-800/60 text-xs">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        <span class="font-medium text-slate-600 dark:text-slate-300 max-w-[140px] truncate">{{ $voter->email }}</span>
                    </div>
                @endif

                <!-- Theme Toggle Button -->
                <button id="theme-toggle" type="button" aria-label="Toggle Theme"
                    class="text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-100 p-2 sm:p-2.5 rounded-xl bg-slate-100/80 dark:bg-slate-800/80 border border-slate-200/80 dark:border-slate-700/80 shadow-xs hover:bg-slate-200/60 dark:hover:bg-slate-700/60 transition focus:outline-none shrink-0 active:scale-95">
                    <i id="theme-toggle-dark-icon" class="fas fa-moon hidden w-3.5 h-3.5 sm:w-4 sm:h-4 flex items-center justify-center"></i>
                    <i id="theme-toggle-light-icon" class="fas fa-sun hidden w-3.5 h-3.5 sm:w-4 sm:h-4 flex items-center justify-center"></i>
                </button>

                @if(isset($election))
                    <a href="{{ route('elections.public_show', $election) }}"
                        class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-2 rounded-xl text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition border border-slate-200/60 dark:border-slate-700/60">
                        <i class="fa-solid fa-arrow-left text-[10px]"></i>
                        <span class="hidden xs:inline">Exit to</span> <span>Overview</span>
                    </a>
                @endif
            </div>
        </div>
    </header>

    <!-- Main Content Container -->
    <main class="flex-grow z-10 w-full px-3.5 sm:px-6 lg:px-8 py-6 sm:py-8">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="w-full border-t border-slate-200/80 dark:border-slate-800/80 bg-white/50 dark:bg-slate-900/50 backdrop-blur-md py-5 sm:py-6 z-10 transition-colors">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-3 text-center sm:text-left">
            <div class="flex items-center gap-2.5">
                <img src="{{ asset('img/sako-logo-nobg.png') }}" alt="SAKO Logo" class="h-6 w-auto object-contain opacity-80" />
                <p class="text-[11px] sm:text-xs text-slate-500 dark:text-slate-400">
                    &copy; {{ date('Y') }} SAKO Cooperative • Electronic Secret Ballot
                </p>
            </div>
            <div class="flex items-center gap-3 text-[11px] sm:text-xs font-semibold text-slate-400 dark:text-slate-500">
                <span>Secret Ballot Protocol</span>
                <span>•</span>
                <span>One Member, One Vote</span>
            </div>
        </div>
    </footer>

    <!-- SweetAlert2 Toast & Confirm Helpers -->
    <script>
        window.showToast = function(message, type = 'success') {
            const isDark = document.documentElement.classList.contains('dark');
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3500,
                timerProgressBar: true,
                background: isDark ? '#1e293b' : '#ffffff',
                color: isDark ? '#f1f5f9' : '#0f172a',
                customClass: {
                    popup: 'rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xl'
                }
            });

            let swalType = 'info';
            if (type === 'success') swalType = 'success';
            if (type === 'error' || type === 'danger') swalType = 'error';
            if (type === 'warning') swalType = 'warning';

            Toast.fire({
                icon: swalType,
                title: message
            });
        };

        window.showConfirmModal = function(title, message, subtext, onConfirm) {
            const isDark = document.documentElement.classList.contains('dark');
            Swal.fire({
                title: title,
                html: `
                    <div class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed">${message}</div>
                    ${subtext ? `<div class="text-xs text-slate-400 dark:text-slate-500 mt-2 font-medium">${subtext}</div>` : ''}
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#9333ea',
                cancelButtonColor: isDark ? '#334155' : '#e2e8f0',
                confirmButtonText: 'Yes, Cast My Vote',
                cancelButtonText: 'Review Choices',
                background: isDark ? '#1e293b' : '#ffffff',
                color: isDark ? '#f1f5f9' : '#0f172a',
                customClass: {
                    popup: 'rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-2xl',
                    confirmButton: 'rounded-xl text-xs font-bold px-5 py-3 mx-1 text-white',
                    cancelButton: 'rounded-xl text-xs font-semibold px-4 py-3 mx-1 ' + (isDark ? 'text-slate-300' : 'text-slate-700')
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    onConfirm();
                }
            });
        };

        // Theme Switcher Engine
        const themeToggleBtn = document.getElementById('theme-toggle');
        const themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
        const themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

        function updateThemeIcons() {
            if (document.documentElement.classList.contains('dark')) {
                themeToggleLightIcon.classList.remove('hidden');
                themeToggleDarkIcon.classList.add('hidden');
            } else {
                themeToggleLightIcon.classList.add('hidden');
                themeToggleDarkIcon.classList.remove('hidden');
            }
        }

        updateThemeIcons();

        if (themeToggleBtn) {
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
        }
    </script>
    @yield('scripts')
</body>
</html>
