<!DOCTYPE html>
<html lang="en" class="min-h-screen bg-slate-50 dark:bg-slate-950 transition-colors duration-300">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Venue Check-In Gateway - App Central</title>
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
        <div class="absolute -top-40 -left-40 w-[500px] h-[500px] bg-purple-200/30 dark:bg-purple-900/10 rounded-full blur-[100px]"></div>
        <div class="absolute -bottom-20 right-1/4 w-[600px] h-[600px] bg-indigo-200/20 dark:bg-indigo-900/10 rounded-full blur-[120px]"></div>
    </div>

    <!-- Header -->
    <header class="w-full max-w-xl mx-auto px-4 pt-6 flex items-center justify-between z-10 relative">
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gradient-to-br from-purple-600 to-indigo-600 text-white font-extrabold text-xs">
                AC
            </span>
            <span class="font-bold text-slate-800 dark:text-slate-200 text-sm tracking-tight">App Central</span>
        </div>
        <span class="text-[10px] font-extrabold uppercase tracking-widest text-purple-600 dark:text-purple-400 bg-purple-50 dark:bg-purple-950/40 border border-purple-150 dark:border-purple-900/30 px-2 py-0.5 rounded">
            Venue Portal
        </span>
    </header>

    <!-- Check-In Main Container -->
    <main class="flex-grow flex items-center justify-center p-4 z-10 relative">
        <div class="max-w-md w-full space-y-6">

            <!-- Title & Welcome Card -->
            <div class="text-center space-y-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-purple-50 dark:bg-purple-950/30 text-purple-700 dark:text-purple-400 text-xs font-semibold rounded-full border border-purple-100 dark:border-purple-900/30">
                    📍 Arrived at Venue
                </span>
                <h1 class="text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight leading-none mt-2">
                    Confirm Attendance
                </h1>
                <p class="text-xs text-slate-500 dark:text-slate-400 max-w-sm mx-auto leading-relaxed">
                    Verify your entry pass for <strong>{{ $event->title }}</strong> to mark yourself as attended.
                </p>
            </div>

            <!-- Validation Errors and Alerts -->
            @if(session('error'))
                <div class="p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/20 border border-rose-100/60 dark:border-rose-900/40 text-rose-800 dark:text-rose-400 text-xs font-semibold">
                    ⚠️ {{ session('error') }}
                </div>
            @endif

            <!-- Check-In verification form card -->
            <div class="bg-white dark:bg-slate-900 border border-slate-150 dark:border-slate-800/80 rounded-3xl p-6 sm:p-8 shadow-xl">
                <form action="{{ route('events.submit_check_in', $event) }}" method="POST" class="space-y-4">
                    @csrf
                    
                    <!-- Email Address -->
                    <div class="space-y-1.5">
                        <label for="email" class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Registered Email</label>
                        <input type="email" name="email" id="email" required value="{{ old('email') }}"
                            placeholder="e.g. john@yourcompany.com"
                            class="w-full rounded-xl border border-slate-300/85 dark:border-slate-800 py-3 px-4 text-slate-700 dark:text-slate-200 text-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-white dark:bg-slate-950/40 focus:bg-white dark:focus:bg-slate-950 transition-all duration-300">
                    </div>

                    <!-- Ticket code -->
                    <div class="space-y-1.5">
                        <label for="ticket_code" class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">6-Digit Ticket Code</label>
                        <input type="text" name="ticket_code" id="ticket_code" required value="{{ old('ticket_code') }}"
                            placeholder="e.g. AC-9F3B"
                            class="w-full rounded-xl border border-slate-300/85 dark:border-slate-800 py-3 px-4 text-slate-700 dark:text-slate-200 text-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-white dark:bg-slate-950/40 focus:bg-white dark:focus:bg-slate-950 transition-all duration-300 font-mono tracking-widest uppercase">
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full py-3.5 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-xl text-xs tracking-wider transition duration-150 shadow-md shadow-purple-500/10 active:scale-[0.99]">
                        VERIFY & ENTER ASSEMBLY
                    </button>
                </form>

                <!-- Help link / Not registered option -->
                <div class="mt-6 pt-5 border-t border-slate-100 dark:border-slate-800/80 text-center text-xs space-y-1.5">
                    <p class="text-slate-500 dark:text-slate-400">Not registered yet?</p>
                    <a href="{{ route('events.public_show', $event) }}" class="font-bold text-purple-600 dark:text-purple-400 hover:underline inline-flex items-center gap-1">
                        Register Immediately On the Spot
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Lost link resend trigger -->
            <div class="text-center">
                <button onclick="document.getElementById('resend-modal').classList.remove('hidden')" class="text-xs text-slate-400 dark:text-slate-500 hover:text-purple-600 dark:hover:text-purple-400 transition-colors font-semibold">
                    Lost your Ticket Code? Resend to Email
                </button>
            </div>

        </div>
    </main>

    <!-- Modal for Resending access details -->
    <div id="resend-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/40 backdrop-blur-xs p-4 hidden">
        <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-3xl p-6 max-w-sm w-full space-y-4 shadow-2xl">
            <div class="space-y-1">
                <h3 class="font-extrabold text-slate-900 dark:text-white text-base">Recover Your Pass</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400">Enter your registered email and we'll dispatch your ticket and code immediately.</p>
            </div>
            
            <form action="{{ route('events.request_access', $event) }}" method="POST" class="space-y-3">
                @csrf
                <input type="email" name="email" required placeholder="your.email@company.com" 
                    class="w-full rounded-xl border border-slate-300 dark:border-slate-800 py-2.5 px-3.5 text-slate-700 dark:text-slate-200 text-sm focus:outline-none focus:border-purple-500 bg-transparent">
                
                <div class="flex gap-2 justify-end pt-2">
                    <button type="button" onclick="document.getElementById('resend-modal').classList.add('hidden')" class="py-2 px-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-800/50">Cancel</button>
                    <button type="submit" class="py-2 px-4 text-xs font-bold rounded-lg bg-purple-600 hover:bg-purple-700 text-white shadow-sm">Send Pass</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <footer class="w-full max-w-xl mx-auto px-4 py-6 text-center text-xs text-slate-400 dark:text-slate-500 z-10 relative">
        &copy; 2026 App Central. Secure Verification Gateway.
    </footer>
</body>
</html>
