<!DOCTYPE html>
<html lang="en" class="min-h-screen bg-slate-950 transition-colors duration-300">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - Sako Central Security</title>
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
        $logoPath = resource_path('views/imgs/letter-s.png');
        $base64Logo = '';
        if (file_exists($logoPath)) {
            $base64Logo = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
        }
    @endphp
    @if($base64Logo)
        <link rel="icon" type="image/png" href="{{ $base64Logo }}">
    @endif
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        @keyframes heroCardEntry {
            from {
                opacity: 0;
                transform: translateY(20px) scale(0.98);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
        .hero-card-entry {
            animation: heroCardEntry 0.45s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        .theme-icon-spin {
            animation: theme-spin 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        @keyframes theme-spin {
            0% { transform: rotate(0deg) scale(0.7); opacity: 0.5; }
            100% { transform: rotate(360deg) scale(1); opacity: 1; }
        }
    </style>
</head>
<body class="min-h-screen flex flex-col justify-between bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 transition-colors duration-300 relative overflow-x-hidden selection:bg-purple-500 selection:text-white">

    <!-- Ambient Background Glow (Responsive Sizing) -->
    <div class="fixed inset-0 pointer-events-none overflow-hidden z-0">
        <div class="absolute -top-32 sm:-top-40 left-1/2 -translate-x-1/2 w-[340px] sm:w-[600px] lg:w-[700px] h-[340px] sm:h-[600px] lg:h-[700px] bg-gradient-to-tr from-purple-600/15 via-indigo-600/15 to-pink-500/10 rounded-full blur-3xl opacity-75 dark:opacity-45 pointer-events-none"></div>
        <div class="absolute -bottom-32 sm:-bottom-40 right-4 sm:right-10 w-[280px] sm:w-[450px] lg:w-[500px] h-[280px] sm:h-[450px] lg:h-[500px] bg-gradient-to-br from-emerald-500/10 via-teal-500/10 to-indigo-500/10 rounded-full blur-3xl opacity-50 dark:opacity-30 pointer-events-none"></div>
    </div>

    <!-- Main Authentication Stage -->
    <main class="relative z-10 flex-grow flex items-center justify-center px-3.5 sm:px-6 py-6 sm:py-12">
        <div class="hero-card-entry w-full max-w-[480px] bg-white/90 dark:bg-slate-900/90 backdrop-blur-2xl rounded-3xl border border-slate-200/90 dark:border-slate-800/80 shadow-2xl shadow-purple-500/5 px-5 py-7 sm:p-10 transition-all duration-300 relative overflow-hidden">
            
            <!-- Embedded Theme Switcher (Glassmorphic Micro-Orb Button) -->
            <div class="absolute top-4 right-4 sm:top-6 sm:right-6 z-20">
                <button type="button" id="theme-toggle"
                    class="w-9 h-9 rounded-2xl border border-slate-200/90 dark:border-slate-800 bg-white/80 dark:bg-slate-800/80 hover:bg-slate-50 dark:hover:bg-slate-700/80 backdrop-blur-md shadow-xs flex items-center justify-center transition-all duration-200 hover:scale-105 active:scale-95 focus:outline-none focus:ring-2 focus:ring-purple-500/30"
                    title="Switch Theme (Light / Dark)" aria-label="Toggle theme">
                    <i id="theme-toggle-icon" class="fa-solid text-sm transition-all duration-300"></i>
                </button>
            </div>

            <!-- Card Header: Logo on Top, followed by Status Pill -->
            <div class="text-center mb-6 sm:mb-8 pt-1 sm:pt-2">
                <!-- 1. Brand Logo Squircle -->
                <div class="relative inline-flex items-center justify-center w-14 h-14 sm:w-16 sm:h-16 rounded-2xl bg-gradient-to-tr from-purple-500/10 via-indigo-500/10 to-pink-500/10 dark:from-purple-950/40 dark:to-indigo-950/40 border border-purple-500/20 dark:border-purple-500/30 p-2 sm:p-2.5 mb-3 sm:mb-4 shadow-inner group transition-transform duration-300 hover:scale-110 hover:rotate-6">
                    @if($base64Logo)
                        <img src="{{ $base64Logo }}" alt="Sako Central" class="w-full h-full object-contain filter drop-shadow-xs" />
                    @else
                        <span class="font-extrabold text-xl sm:text-2xl text-purple-600 dark:text-purple-400 font-mono">S</span>
                    @endif
                    <div class="absolute -inset-1 rounded-2xl bg-purple-500/20 blur-md opacity-0 group-hover:opacity-100 transition-opacity duration-300 -z-10"></div>
                </div>

                <!-- 2. Security Status Pill -->
                <div class="flex justify-center mb-2.5 sm:mb-3">
                    <span class="inline-flex items-center gap-1.5 px-2.5 sm:px-3 py-0.5 sm:py-1 rounded-full text-[9px] sm:text-[10px] font-bold uppercase tracking-wider bg-purple-50 dark:bg-purple-950/40 text-purple-700 dark:text-purple-300 border border-purple-200/60 dark:border-purple-800/40">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-ping"></span>
                        <span>Enterprise SSO • Whitelisted Access</span>
                    </span>
                </div>

                <!-- 3. Headline & Subtitle -->
                <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight leading-tight px-1 sm:whitespace-nowrap">
                    Corporate Identity Sign-In
                </h1>

                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1.5 sm:mt-2 leading-relaxed px-1">
                    Authenticate via your authorized Google Workspace account to enter operational portals.
                </p>
            </div>

            <!-- Error Notifications -->
            @if(session('error'))
                <div class="mb-5 sm:mb-6 p-3.5 sm:p-4 rounded-2xl bg-red-50 dark:bg-red-950/30 border border-red-200 dark:border-red-900/40 text-red-600 dark:text-red-400 text-xs sm:text-sm flex items-start gap-2.5 sm:gap-3 shadow-xs">
                    <i class="fa-solid fa-triangle-exclamation text-sm sm:text-base shrink-0 mt-0.5 text-red-500"></i>
                    <span class="leading-relaxed">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Google OAuth Action Button -->
            <div class="space-y-4 sm:space-y-5">
                <a href="{{ route('auth.google') }}"
                   class="group relative w-full flex items-center justify-center gap-3 bg-white dark:bg-slate-800/90 hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-800 dark:text-white font-bold py-3 sm:py-3.5 px-4 sm:px-5 border border-slate-200 dark:border-slate-700/80 rounded-2xl shadow-sm hover:shadow-xl hover:shadow-purple-500/10 hover:border-purple-500/40 dark:hover:border-purple-500/40 hover:-translate-y-0.5 active:translate-y-0 active:scale-[0.98] transition-all duration-200 focus:outline-none focus:ring-4 focus:ring-purple-500/15 overflow-hidden">
                    
                    <!-- Google Multi-Color SVG -->
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 shrink-0 transition-transform duration-200 group-hover:scale-110" viewBox="0 0 24 24">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22c-.66-.23-1.23-.62-1.68-1.12z"/>
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                    </svg>

                    <span class="text-xs sm:text-sm tracking-tight">Sign in with Google</span>

                    <i class="fa-solid fa-arrow-right text-[11px] sm:text-xs text-slate-400 group-hover:text-purple-600 dark:group-hover:text-purple-400 transform group-hover:translate-x-1 transition-all duration-200 ml-auto"></i>
                </a>

                <!-- Trust Micro-Badges (Responsive Grid) -->
                <div class="pt-4 sm:pt-5 border-t border-slate-100 dark:border-slate-800/60 grid grid-cols-3 gap-1.5 sm:gap-2 text-center">
                    <div class="px-1.5 sm:px-2 py-1.5 sm:py-2 rounded-xl bg-slate-50 dark:bg-slate-950/60 border border-slate-100 dark:border-slate-800/40">
                        <i class="fa-solid fa-shield-halved text-purple-500 text-[10px] sm:text-[11px] mb-0.5 block"></i>
                        <span class="text-[8.5px] sm:text-[9px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-tight block truncate">256-Bit SSL</span>
                    </div>

                    <div class="px-1.5 sm:px-2 py-1.5 sm:py-2 rounded-xl bg-slate-50 dark:bg-slate-950/60 border border-slate-100 dark:border-slate-800/40">
                        <i class="fa-solid fa-user-lock text-emerald-500 text-[10px] sm:text-[11px] mb-0.5 block"></i>
                        <span class="text-[8.5px] sm:text-[9px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-tight block truncate">Whitelisted</span>
                    </div>

                    <div class="px-1.5 sm:px-2 py-1.5 sm:py-2 rounded-xl bg-slate-50 dark:bg-slate-950/60 border border-slate-100 dark:border-slate-800/40">
                        <i class="fa-solid fa-key text-indigo-500 text-[10px] sm:text-[11px] mb-0.5 block"></i>
                        <span class="text-[8.5px] sm:text-[9px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-tight block truncate">2FA PIN Gate</span>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="relative z-10 py-4 sm:py-6 text-center text-xs text-slate-400 dark:text-slate-600 border-t border-slate-200/50 dark:border-slate-800/60 transition-colors duration-300">
        <div class="flex items-center justify-center gap-2 mb-1">
            <i class="fa-solid fa-shield-cat text-purple-500 text-xs"></i>
            <span class="font-medium text-slate-600 dark:text-slate-400">Sako Central Enterprise Security</span>
        </div>
        &copy; {{ date('Y') }} All access attempts are strictly verified and logged.
    </footer>

    <!-- Theme Controller Script -->
    <script>
        const themeToggleBtn = document.getElementById('theme-toggle');
        const themeToggleIcon = document.getElementById('theme-toggle-icon');

        const syncThemeState = () => {
            const isDark = document.documentElement.classList.contains('dark');
            if (themeToggleIcon) {
                themeToggleIcon.className = isDark
                    ? 'fa-solid fa-moon text-sm text-indigo-400'
                    : 'fa-solid fa-sun text-sm text-amber-500';
            }
        };

        const toggleTheme = () => {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            }
            syncThemeState();

            if (themeToggleIcon) {
                themeToggleIcon.classList.remove('theme-icon-spin');
                void themeToggleIcon.offsetWidth;
                themeToggleIcon.classList.add('theme-icon-spin');
            }
        };

        syncThemeState();

        if (themeToggleBtn) {
            themeToggleBtn.addEventListener('click', toggleTheme);
        }
    </script>
</body>
</html>
