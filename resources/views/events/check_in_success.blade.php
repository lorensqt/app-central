<!DOCTYPE html>
<html lang="en" class="min-h-screen bg-slate-50 dark:bg-slate-950 transition-colors duration-300">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check-In Success! - App Central</title>
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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="h-full flex flex-col justify-between relative bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 overflow-x-hidden min-h-screen transition-colors duration-300">
    
    <!-- Background glowing aesthetics -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none z-0">
        <div class="absolute -top-40 -left-40 w-[500px] h-[500px] bg-emerald-200/20 dark:bg-emerald-900/10 rounded-full blur-[100px]"></div>
        <div class="absolute -bottom-20 right-1/4 w-[600px] h-[600px] bg-teal-200/20 dark:bg-teal-900/10 rounded-full blur-[120px]"></div>
    </div>

    <!-- Header -->
    <header class="w-full max-w-xl mx-auto px-4 pt-6 flex items-center justify-between z-10 relative">
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gradient-to-br from-purple-600 to-indigo-600 text-white font-extrabold text-xs">
                AC
            </span>
            <span class="font-bold text-slate-800 dark:text-slate-200 text-sm tracking-tight">App Central</span>
        </div>
    </header>

    <!-- Success Main Content -->
    <main class="flex-grow flex items-center justify-center p-4 z-10 relative">
        <div class="max-w-md w-full text-center space-y-6">

            <!-- Satisfying Checkmark circle -->
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-emerald-50 dark:bg-emerald-950/30 text-emerald-600 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-900/30 shadow-lg shrink-0 scale-100 animate-bounce">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                </svg>
            </div>

            <!-- Header Titles -->
            <div class="space-y-2">
                <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">
                    @if(request('already'))
                        Already Verified!
                    @else
                        Check-In Confirmed!
                    @endif
                </h1>
                <p class="text-xs text-slate-500 dark:text-slate-400 max-w-xs mx-auto leading-relaxed">
                    @if(request('already'))
                        You are already verified and checked in for this assembly.
                    @else
                        Your attendance has been logged successfully on our systems.
                    @endif
                </p>
            </div>

            <!-- Welcome Pass Card -->
            <div class="bg-white dark:bg-slate-900 border border-slate-150 dark:border-slate-800/80 rounded-3xl p-6 shadow-xl space-y-4">
                <div class="space-y-1">
                    <p class="text-[10px] font-bold uppercase tracking-widest text-emerald-600 dark:text-emerald-400 leading-none">Checked In Successfully</p>
                    <h2 class="text-lg font-extrabold text-slate-900 dark:text-white tracking-tight leading-snug mt-1.5">{{ $event->title }}</h2>
                </div>

                <div class="py-3 px-4 bg-slate-50 dark:bg-slate-950 rounded-2xl border border-slate-100 dark:border-slate-800 text-left space-y-2 text-xs">
                    <div class="flex justify-between">
                        <span class="text-slate-400 dark:text-slate-500 font-medium">Location Venue:</span>
                        <strong class="text-slate-800 dark:text-slate-200 font-semibold truncate max-w-[200px]">{{ $event->location }}</strong>
                    </div>
                    <div class="flex justify-between border-t border-slate-100 dark:border-slate-800 pt-2">
                        <span class="text-slate-400 dark:text-slate-500 font-medium">Verify Time:</span>
                        <strong class="text-slate-800 dark:text-slate-200 font-semibold">{{ now()->format('g:i A \E\S\T') }}</strong>
                    </div>
                </div>

                <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed italic">
                    "Welcome! Please proceed inside the venue hall. Enjoy the division assembly!"
                </p>
            </div>

            <!-- Action buttons -->
            <div class="text-center">
                <a href="{{ route('events.public_show', $event) }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-purple-600 dark:text-purple-400 hover:underline">
                    Return to Event Details Page
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </a>
            </div>

        </div>
    </main>

    <!-- Footer -->
    <footer class="w-full max-w-xl mx-auto px-4 py-6 text-center text-xs text-slate-400 dark:text-slate-500 z-10 relative">
        &copy; 2026 App Central. Secure Verification Gateway.
    </footer>
</body>
</html>
