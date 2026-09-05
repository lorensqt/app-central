<!DOCTYPE html>
<html lang="en" class="min-h-screen bg-slate-50 dark:bg-slate-950 transition-colors duration-300">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback Survey - {{ $event->title }}</title>
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
        <div class="absolute top-1/3 right-10 w-[400px] h-[400px] bg-indigo-200/30 dark:bg-indigo-900/10 rounded-full blur-[80px] animate-pulse" style="animation-duration: 12s;"></div>
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
        <div class="max-w-5xl w-full grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 items-start opacity-0 translate-y-8 animate-fade-in-up">
            
            <!-- Left Column: Event details (5 Columns) -->
            <div class="lg:col-span-5 space-y-6 text-left">
                <div class="bg-gradient-to-br from-slate-900 via-indigo-950 to-purple-950 p-6 sm:p-8 rounded-3xl text-white border border-slate-800 shadow-xl space-y-6">
                    <div class="space-y-3">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-white/10 backdrop-blur-md text-purple-200 text-xs font-bold rounded-lg border border-white/10 uppercase tracking-widest">
                            Feedback Questionnaire
                        </span>
                        <h1 class="text-2xl sm:text-3.5xl font-extrabold tracking-tight leading-tight">
                            {{ $event->title }}
                        </h1>
                        <p class="text-xs text-slate-350 dark:text-slate-400 font-semibold uppercase tracking-wider mb-2">Attendee: {{ $registration->name }}</p>
                    </div>

                    <div class="border-t border-white/10 pt-6 space-y-4 text-sm text-slate-300">
                        <div class="flex items-start gap-3">
                            <span class="w-8 h-8 rounded-lg bg-white/5 border border-white/10 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4 text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </span>
                            <div>
                                <p class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Date of Assembly</p>
                                <p class="font-semibold text-white">{{ $event->event_date->format('l, F j, Y') }}</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <span class="w-8 h-8 rounded-lg bg-white/5 border border-white/10 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4 text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                </svg>
                            </span>
                            <div>
                                <p class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Venue Location</p>
                                <p class="font-semibold text-white">{{ $event->location }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Survey Form (7 Columns) -->
            <div class="lg:col-span-7 bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 p-6 sm:p-8 rounded-3xl shadow-xl space-y-6 text-left">
                <div class="space-y-1">
                    <h2 class="text-xl font-extrabold tracking-tight text-slate-900 dark:text-white">We Value Your Thoughts</h2>
                    <p class="text-xs text-slate-550 dark:text-slate-400">Please share your honest feedback to help us design better future sessions.</p>
                </div>

                @if ($errors->any())
                    <div class="p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/20 border border-rose-100 dark:border-rose-900/40 text-rose-800 dark:text-rose-400 text-sm space-y-1">
                        @foreach ($errors->all() as $error)
                            <p class="text-xs font-semibold leading-relaxed">{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form action="{{ URL::signedRoute('events.survey_submit', ['registration' => $registration->id]) }}" method="POST" class="space-y-5">
                    @csrf

                    @foreach($event->survey_questions ?? [] as $question)
                        @php
                            $fieldId = $question['id'] ?? '';
                            $label = $question['label'] ?? '';
                            $required = !empty($question['required']);
                        @endphp
                        @if($fieldId && $label)
                            <div class="space-y-2">
                                <label for="answer_{{ $fieldId }}" class="block text-xs font-bold text-slate-700 dark:text-slate-300">
                                    {{ $label }}
                                    @if($required)
                                        <span class="text-rose-550 dark:text-rose-450 ml-0.5">*</span>
                                    @endif
                                </label>
                                <textarea name="answers[{{ $fieldId }}]" id="answer_{{ $fieldId }}" rows="3"
                                    {{ $required ? 'required' : '' }}
                                    placeholder="Type your response here..."
                                    class="w-full rounded-2xl border border-slate-300 dark:border-slate-800 py-3 px-4 text-slate-700 dark:text-slate-200 text-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-slate-50/50 dark:bg-slate-950/50 focus:bg-white dark:focus:bg-slate-950 transition-all duration-300 placeholder:text-slate-400"></textarea>
                            </div>
                        @endif
                    @endforeach

                    <div class="pt-4 border-t border-slate-100 dark:border-slate-850">
                        <button type="submit" class="w-full inline-flex items-center justify-center text-xs font-bold py-3.5 px-6 rounded-2xl bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white transition duration-150 shadow-lg shadow-purple-500/15 focus:outline-none">
                            Submit Feedback Survey
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <!-- Footer -->
    <footer class="w-full max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6 flex flex-col sm:flex-row justify-between items-center gap-3 border-t border-slate-200/50 dark:border-slate-850/60 z-10 relative">
        <p class="text-[10px] sm:text-xs text-slate-450 dark:text-slate-500 font-semibold">&copy; {{ date('Y') }} App Central. All rights reserved.</p>
        <p class="text-[10px] sm:text-xs text-slate-400 dark:text-slate-500 leading-normal font-medium">This secure feedback route uses signed hashes to protect guest identities.</p>
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
