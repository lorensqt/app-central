<!DOCTYPE html>
<html lang="en" class="min-h-screen bg-slate-50 dark:bg-slate-950 transition-colors duration-300">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Access PIN - App Central</title>
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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col justify-between bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 transition-colors duration-300 relative">
    
    <!-- Theme Toggle and Logout -->
    <div class="absolute top-6 right-6 flex items-center gap-3">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="text-xs font-semibold py-2 px-3 rounded-lg border border-slate-200 dark:border-slate-800 text-slate-500 hover:text-red-600 bg-white dark:bg-slate-900 transition duration-150">
                Cancel Login
            </button>
        </form>
        <button id="theme-toggle" type="button" class="text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-100 p-2 rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 shadow-xs hover:shadow-sm hover:bg-slate-50 dark:hover:bg-slate-800/60 transition duration-150 focus:outline-none shrink-0">
            <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
            </svg>
            <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m12.728 0l-.707-.707M6.343 6.343l-.707-.707M14 12a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
        </button>
    </div>

    <div class="flex-grow flex items-center justify-center p-6">
        <div class="w-full max-w-md bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800/80 shadow-sm p-8 sm:p-10 transition-all duration-300">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-purple-50 dark:bg-purple-950/30 text-purple-600 dark:text-purple-400 font-semibold text-lg mb-4 shadow-sm border border-purple-100/10">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight font-sans">Two-Factor Authentication</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-2">Enter your 6-digit access PIN to complete verification.</p>
            </div>

            <!-- Error Alerts / Warning chances -->
            @if(session('error'))
                <div class="mb-6 p-4 rounded-xl bg-red-50 dark:bg-red-950/20 border border-red-100 dark:border-red-900/40 text-red-600 dark:text-red-400 text-sm flex items-start gap-3">
                    <svg class="w-5 h-5 shrink-0 mt-0.5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <!-- Attempts Alert -->
            @php
                $attempts = session('pin_attempts', 0);
                $chancesLeft = 3 - $attempts;
            @endphp
            <div class="mb-6 p-3.5 rounded-xl text-center text-xs font-semibold border {{ $chancesLeft === 1 ? 'bg-red-50/50 dark:bg-red-950/10 border-red-200 dark:border-red-900/30 text-red-600 dark:text-red-400' : 'bg-amber-50/40 dark:bg-amber-950/10 border-amber-200/60 dark:border-amber-900/20 text-amber-700 dark:text-amber-400' }}">
                @if($chancesLeft === 1)
                    CRITICAL WARNING: This is your last attempt. An incorrect entry will automatically log you out.
                @else
                    Security Check: You have {{ $chancesLeft }} of 3 verification attempts remaining.
                @endif
            </div>

            <!-- Form -->
            <form action="{{ route('pin.verify.submit') }}" method="POST" class="space-y-5">
                @csrf

                <!-- PIN Input -->
                <div class="space-y-2">
                    <label for="pin" class="block text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">6-Digit PIN</label>
                    <input type="password" name="pin" id="pin" required placeholder="&bull; &bull; &bull; &bull; &bull; &bull;" pattern="[0-9]{6}" maxlength="6" inputmode="numeric" autofocus autocomplete="off"
                        class="w-full tracking-[0.5em] text-center rounded-xl border border-slate-200 dark:border-slate-800 py-3 px-4 text-slate-700 dark:text-slate-200 text-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-slate-50/50 dark:bg-slate-950/50 focus:bg-white dark:focus:bg-slate-950 transition-all duration-300">
                </div>

                <button type="submit" class="w-full inline-flex items-center justify-center text-sm font-semibold py-3.5 px-4 rounded-xl bg-purple-600 hover:bg-purple-700 text-white shadow-sm hover:shadow active:scale-[0.98] transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500/20">
                    Verify Access
                </button>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <div class="py-6 text-center text-xs text-slate-400 dark:text-slate-500 border-t border-slate-100 dark:border-slate-800/80 transition-colors duration-300">
        &copy; {{ date('Y') }} App Central. All rights reserved.
    </div>

    <script>
        const themeToggleBtn = document.getElementById('theme-toggle');
        const themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
        const themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

        if (document.documentElement.classList.contains('dark')) {
            themeToggleLightIcon.classList.remove('hidden');
        } else {
            themeToggleDarkIcon.classList.remove('hidden');
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
    </script>
</body>
</html>
