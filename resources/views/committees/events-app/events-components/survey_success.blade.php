<!DOCTYPE html>
<html lang="en" class="min-h-screen bg-slate-50 dark:bg-slate-950 transition-colors duration-300">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You - Feedback Submitted</title>
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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(24px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .animate-fade-in-up {
            animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
    </style>
</head>
<body class="h-full flex flex-col justify-between relative bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 overflow-x-hidden min-h-screen transition-colors duration-300">
    <!-- Decorative Background Glowing Orbs -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none z-0">
        <div class="absolute -top-40 -left-40 w-[500px] h-[500px] bg-purple-200/40 dark:bg-purple-900/15 rounded-full blur-[100px] animate-pulse" style="animation-duration: 8s;"></div>
        <div class="absolute -bottom-20 left-1/4 w-[600px] h-[600px] bg-fuchsia-200/20 dark:bg-fuchsia-900/10 rounded-full blur-[120px] animate-pulse" style="animation-duration: 10s;"></div>
    </div>

    <!-- Header Branding -->
    <header class="w-full max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pt-4 sm:pt-6 lg:pt-8 flex items-center justify-between z-10 relative">
        <div class="flex items-center gap-2 group">
            <span class="inline-flex items-center justify-center w-8 h-8 sm:w-10 sm:h-10 rounded-lg sm:rounded-xl bg-gradient-to-br from-purple-600 to-indigo-600 text-white font-extrabold text-xs sm:text-sm shadow-md shadow-purple-500/20">
                AC
            </span>
            <span class="font-bold text-slate-800 dark:text-slate-200 tracking-tight text-sm sm:text-base lg:text-lg">App Central</span>
        </div>
        <div class="flex items-center gap-2">
            <!-- Theme Toggle Button -->
            <button id="theme-toggle" type="button" class="text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-100 p-1.5 sm:p-2 rounded-lg sm:rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 shadow-xs hover:shadow-sm hover:bg-slate-50 dark:hover:bg-slate-800/60 transition duration-150 focus:outline-none shrink-0">
                <svg id="theme-toggle-dark-icon" class="hidden w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                </svg>
                <svg id="theme-toggle-light-icon" class="hidden w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m12.728 0l-.707-.707M6.343 6.343l-.707-.707M14 12a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </button>
        </div>
    </header>

    <!-- Main Container -->
    <div class="flex-grow flex items-center justify-center p-4 sm:p-6 lg:p-8 z-10 relative">
        <div class="max-w-md w-full bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 p-6 sm:p-10 rounded-3xl shadow-2xl text-center space-y-6 opacity-0 translate-y-8 animate-fade-in-up">
            
            <div class="flex justify-center">
                <span class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-emerald-50 dark:bg-emerald-950/30 text-emerald-500 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-900/30 shadow-md">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </span>
            </div>

            <div class="space-y-2">
                <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white">
                    @if(!empty($already_submitted))
                        Responses Already Logged!
                    @else
                        Thank You for Your Feedback!
                    @endif
                </h1>
                <p class="text-xs text-slate-550 dark:text-slate-400 leading-relaxed">
                    @if(!empty($already_submitted))
                        Your feedback answers have already been successfully logged for <strong>{{ $event->title }}</strong>. We highly appreciate your contribution!
                    @else
                        Your valuable feedback has been successfully registered for <strong>{{ $event->title }}</strong>. It will help us make future committee assemblies even more impactful.
                    @endif
                </p>
            </div>

            <div class="pt-4 border-t border-slate-100 dark:border-slate-850">
                <p class="text-[11px] text-slate-400 dark:text-slate-500 font-medium">You can safely close this browser tab now.</p>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="w-full max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6 flex flex-col sm:flex-row justify-between items-center gap-3 border-t border-slate-200/50 dark:border-slate-850/60 z-10 relative">
        <p class="text-[10px] sm:text-xs text-slate-450 dark:text-slate-500 font-semibold">&copy; {{ date('Y') }} App Central. All rights reserved.</p>
    </footer>

    <script>
        const themeToggleBtn = document.getElementById('theme-toggle');
        const darkIcon = document.getElementById('theme-toggle-dark-icon');
        const lightIcon = document.getElementById('theme-toggle-light-icon');

        if (document.documentElement.classList.contains('dark')) {
            lightIcon.classList.remove('hidden');
        } else {
            darkIcon.classList.remove('hidden');
        }

        themeToggleBtn.addEventListener('click', function() {
            darkIcon.classList.toggle('hidden');
            lightIcon.classList.toggle('hidden');

            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            }
        });
    </script>
</body>
</html>
