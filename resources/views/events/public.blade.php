<!DOCTYPE html>
<html lang="en" class="min-h-screen bg-slate-50 dark:bg-slate-950 transition-colors duration-300">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $event->title }} - Event Registration</title>
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

        /* Smooth fade-in-up animations */
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

        @keyframes float {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-12px);
            }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        .animate-float {
            animation: float 6s ease-in-out infinite;
        }

        /* Custom scrollbar for toast notifications or other elements */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: rgba(156, 163, 175, 0.2);
            border-radius: 20px;
        }

        /* Smooth slide down transition for the custom specify gender input */
        #custom-gender-wrapper {
            max-height: 0;
            opacity: 0;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }

        #custom-gender-wrapper.open {
            max-height: 120px;
            opacity: 1;
            margin-top: 1.25rem;
        }
    </style>
</head>
<body class="h-full flex flex-col justify-between relative bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 overflow-x-hidden min-h-screen transition-colors duration-300">
    <!-- Decorative Background Glowing Orbs -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none z-0">
        <div class="absolute -top-40 -left-40 w-[500px] h-[500px] bg-purple-200/40 dark:bg-purple-900/15 rounded-full blur-[100px] animate-pulse" style="animation-duration: 8s;"></div>
        <div class="absolute top-1/3 right-10 w-[400px] h-[400px] bg-indigo-200/30 dark:bg-indigo-900/10 rounded-full blur-[80px] animate-pulse" style="animation-duration: 12s;"></div>
        <div class="absolute -bottom-20 left-1/4 w-[600px] h-[600px] bg-fuchsia-200/20 dark:bg-fuchsia-900/10 rounded-full blur-[120px] animate-pulse" style="animation-duration: 10s;"></div>
    </div>

    <!-- Premium Toast Notification Container -->
    <div id="toast-container" class="fixed top-4 md:top-20 right-4 md:right-6 left-4 md:left-auto z-50 flex flex-col gap-3 max-w-sm pointer-events-none"></div>

    <!-- Header Branding -->
    <header class="w-full max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 sm:pt-8 flex items-center justify-between z-10 relative">
        <a href="#" class="flex items-center gap-2.5 group">
            <span class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-gradient-to-br from-purple-600 to-indigo-600 text-white font-extrabold text-sm shadow-md shadow-purple-500/20 group-hover:scale-105 transition-transform duration-300">
                AC
            </span>
            <span class="font-bold text-slate-800 dark:text-slate-200 group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors duration-300 tracking-tight text-lg">App Central</span>
        </a>
        <div class="flex items-center gap-3">
            <!-- Theme Toggle Button -->
            <button id="theme-toggle" type="button" class="text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-100 p-2 rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 shadow-xs hover:shadow-sm hover:bg-slate-50 dark:hover:bg-slate-800/60 transition duration-150 focus:outline-none shrink-0">
                <svg id="theme-toggle-dark-icon" class="hidden w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                </svg>
                <svg id="theme-toggle-light-icon" class="hidden w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m12.728 0l-.707-.707M6.343 6.343l-.707-.707M14 12a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </button>
            <div class="text-xs font-semibold text-slate-450 dark:text-slate-500 bg-slate-100 dark:bg-slate-900 px-3 py-1.5 rounded-lg border border-slate-200/40 dark:border-slate-800/80 shadow-sm transition-colors duration-300">
                Secure Portal Gateway
            </div>
        </div>
    </header>

    <!-- Main Container -->
    <div class="flex-grow flex items-center justify-center p-4 sm:p-6 lg:p-8 z-10 relative">
        <div class="max-w-6xl w-full grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 items-start opacity-0 translate-y-8 animate-fade-in-up">
            
            <!-- Left Column: Event Showcase Details (7 Columns) -->
            <div class="lg:col-span-7 space-y-6 text-left">
                <!-- Premium Event Hero Showcase Card -->
                <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-slate-900 via-indigo-950 to-purple-950 p-6 sm:p-10 text-white shadow-xl group border border-slate-800"
                     @if($event->image) style="background-image: url('{{ $event->image }}'); background-size: cover; background-position: center;" @endif>
                    
                    @if($event->image)
                        <!-- Dark overlay to ensure text readability on light images -->
                        <div class="absolute inset-0 bg-slate-950/70 z-0"></div>
                    @endif

                    <!-- Mesh background details -->
                    <div class="absolute inset-0 bg-[linear-gradient(to_right,#ffffff05_1px,transparent_1px),linear-gradient(to_bottom,#ffffff05_1px,transparent_1px)] bg-[size:32px_32px] z-0"></div>
                    <div class="absolute -right-16 -bottom-16 w-64 h-64 bg-purple-600/20 rounded-full blur-3xl group-hover:bg-purple-600/30 transition-all duration-700 animate-pulse z-0"></div>
                    <div class="absolute -left-10 -top-10 w-48 h-48 bg-indigo-500/10 rounded-full blur-2xl z-0"></div>
                    
                    <div class="relative z-10 space-y-6">
                        <!-- Division Badge -->
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-white/10 backdrop-blur-md text-purple-200 text-xs font-bold rounded-lg border border-white/10 uppercase tracking-widest">
                            <svg class="w-3.5 h-3.5 text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            {{ $event->committee ? $event->committee->name : 'Division Assembly' }}
                        </span>

                        <!-- Title -->
                        <h1 class="text-3xl sm:text-4.5xl font-extrabold text-white tracking-tight leading-tight">
                            {{ $event->title }}
                        </h1>

                        <p class="text-slate-300 text-sm sm:text-base font-light max-w-2xl leading-relaxed">
                            Official assembly and collaboration directory hosted by committee personnel. Register below to secure your seat and receive the detailed digital schedule and instructions.
                        </p>
                    </div>
                </div>

                <!-- Event Details Stack (Spacious Stack - Revamped to prevent cramping) -->
                <div class="space-y-4">
                    <!-- Date & Time Horizontal Card -->
                    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-150/80 dark:border-slate-800/80 p-5 sm:p-6 shadow-[0_8px_30px_rgba(15,23,42,0.015)] hover:shadow-[0_20px_50px_rgba(168,85,247,0.05)] hover:border-purple-200/80 dark:hover:border-purple-800/60 transition-all duration-300 flex flex-col sm:flex-row sm:items-center justify-between gap-4 group relative bg-gradient-to-r from-white to-slate-50/50 dark:from-slate-900 dark:to-slate-900/50 hover:to-purple-50/20 dark:hover:to-purple-950/20">
                        <!-- Nested backdrop-clip container for the absolute blurred hover background decoration -->
                        <div class="absolute inset-0 rounded-3xl overflow-hidden pointer-events-none z-0">
                            <div class="absolute -top-10 -right-10 w-20 h-20 bg-purple-500/10 rounded-full blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        </div>
                        
                        <div class="flex items-center gap-4 min-w-0">
                            <span class="p-3 rounded-2xl bg-purple-50 dark:bg-purple-950/40 text-purple-600 dark:text-purple-400 border border-purple-100/80 dark:border-purple-900/30 shrink-0 h-12 w-12 flex items-center justify-center shadow-sm group-hover:scale-105 group-hover:bg-purple-600 group-hover:text-white dark:group-hover:bg-purple-500 dark:group-hover:text-slate-950 transition-all duration-300">
                                <svg class="w-5 h-5 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </span>
                            <div class="space-y-0.5 min-w-0">
                                <div class="text-[10px] font-bold text-purple-500 dark:text-purple-400 uppercase tracking-widest leading-none">Date & Schedule</div>
                                <h4 class="text-base sm:text-lg font-extrabold text-slate-800 dark:text-slate-200 leading-snug break-words tracking-tight mt-1">
                                    {{ $event->event_date->format('l, F j, Y') }}
                                </h4>
                            </div>
                        </div>

                        <!-- Right: Time Badge & Calendar Add Button Group -->
                        <div class="flex flex-wrap items-center gap-2 sm:justify-end shrink-0 z-10">
                            <!-- Time Badge -->
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-purple-50/60 dark:bg-purple-950/50 text-purple-700 dark:text-purple-300 border border-purple-100/40 dark:border-purple-900/30 text-xs font-bold whitespace-nowrap">
                                <svg class="w-4 h-4 text-purple-500 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $event->event_date->format('g:i A') }} (EST)
                            </span>

                            <!-- Add to Calendar Selector Button -->
                            <div class="relative inline-block text-left" id="calendar-dropdown-container">
                                <button type="button" onclick="toggleCalendarDropdown(event)"
                                    class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg sm:rounded-xl bg-purple-600 hover:bg-purple-700 active:scale-[0.98] text-white text-[10px] sm:text-xs font-bold transition-all duration-150 shadow-sm whitespace-nowrap">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                    </svg>
                                    Add to Calendar
                                </button>
                                <!-- Dropdown Menu -->
                                <div id="calendar-dropdown-menu" class="hidden absolute right-0 mt-1.5 w-40 rounded-xl bg-white dark:bg-slate-900 border border-slate-150 dark:border-slate-800 shadow-xl z-30 overflow-hidden transform origin-top-right transition-all duration-200">
                                    <button type="button" onclick="triggerAddToCalendar('google')"
                                        class="w-full text-left px-4 py-2 text-[11px] sm:text-xs font-semibold text-slate-700 dark:text-slate-300 hover:bg-purple-50 dark:hover:bg-purple-950/40 hover:text-purple-600 dark:hover:text-purple-400 transition-colors flex items-center gap-2">
                                        <svg class="w-4 h-4 text-purple-500" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 6h3.5v2.5H12V15H9.5V9H12zm5 5h-1v1h-1.5v-1h-1V12.5h1v-1H15v1h1V14z"/>
                                        </svg>
                                        Google
                                    </button>
                                    <button type="button" onclick="triggerAddToCalendar('ical')"
                                        class="w-full text-left px-4 py-2 text-[11px] sm:text-xs font-semibold text-slate-700 dark:text-slate-300 hover:bg-purple-50 dark:hover:bg-purple-950/40 hover:text-purple-600 dark:hover:text-purple-400 transition-colors flex items-center gap-2">
                                        <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        iCal / Apple
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Location / Venue Horizontal Card -->
                    <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($event->location) }}" target="_blank" rel="noopener noreferrer" 
                        class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-150/80 dark:border-slate-800/80 p-5 sm:p-6 shadow-[0_8px_30px_rgba(15,23,42,0.015)] hover:shadow-[0_20px_50px_rgba(99,102,241,0.05)] hover:border-indigo-200/80 dark:hover:border-indigo-800/60 transition-all duration-300 flex flex-col sm:flex-row sm:items-center justify-between gap-4 group relative overflow-hidden bg-gradient-to-r from-white to-slate-50/50 dark:from-slate-900 dark:to-slate-900/50 hover:to-indigo-50/20 dark:hover:to-indigo-950/20" title="Click to open in Google Maps">
                        <div class="absolute -top-10 -right-10 w-20 h-20 bg-indigo-500/10 rounded-full blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        
                        <div class="flex items-center gap-4 min-w-0 flex-1">
                            <span class="p-3 rounded-2xl bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 border border-indigo-100/80 dark:border-indigo-900/30 shrink-0 h-12 w-12 flex items-center justify-center shadow-sm group-hover:scale-105 group-hover:bg-indigo-600 group-hover:text-white dark:group-hover:bg-indigo-500 dark:group-hover:text-slate-950 transition-all duration-300">
                                <svg class="w-5 h-5 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </span>
                            <div class="space-y-0.5 min-w-0">
                                <div class="text-[10px] font-bold text-indigo-500 dark:text-indigo-400 uppercase tracking-widest leading-none">Location & Venue</div>
                                <h4 class="text-base sm:text-lg font-extrabold text-slate-800 dark:text-slate-200 leading-snug break-words tracking-tight mt-1 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                                    {{ $event->location }}
                                </h4>
                            </div>
                        </div>

                        <!-- Right action button -->
                        <div class="shrink-0 self-start sm:self-auto">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-indigo-50/60 dark:bg-indigo-950/50 text-indigo-700 dark:text-indigo-300 border border-indigo-100/30 dark:border-indigo-900/20 text-xs font-bold group-hover:bg-indigo-100 dark:group-hover:bg-indigo-950/60 transition-colors whitespace-nowrap">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                                </svg>
                                Open Maps
                            </span>
                        </div>
                    </a>

                    <!-- Seating Capacity Card (Revamped) -->
                    @php
                        $approvedCount = $event->registrations->where('status', 'approved')->count();
                        $hasLimit = $event->max_participants !== null;
                        $slotsLeft = $hasLimit ? max(0, $event->max_participants - $approvedCount) : null;
                        $isFullyBooked = $hasLimit && ($slotsLeft <= 0);
                        
                        // Compute seat occupancy percentage for progress bar
                        $fillPercent = $hasLimit && $event->max_participants > 0 ? min(100, round(($approvedCount / $event->max_participants) * 100)) : 0;
                        
                        // Dynamically theme card depending on seating state
                        if ($isFullyBooked) {
                            $themeColor = 'red';
                            $titleText = 'Fully Booked';
                            $subtext = 'Capacity limit reached';
                        } elseif ($hasLimit) {
                            if ($slotsLeft <= ($event->max_participants * 0.2)) {
                                $themeColor = 'amber';
                                $titleText = $slotsLeft . ' Seats Left';
                                $subtext = 'Filling up very fast!';
                            } else {
                                $themeColor = 'emerald';
                                $titleText = $slotsLeft . ' Seats Left';
                                $subtext = 'of ' . $event->max_participants . ' total seats';
                            }
                        } else {
                            $themeColor = 'teal';
                            $titleText = 'Unlimited Space';
                            $subtext = 'Unlimited capacity';
                        }
                    @endphp

                    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-150/80 dark:border-slate-800/80 p-5 sm:p-6 shadow-[0_8px_30px_rgba(15,23,42,0.015)] hover:shadow-[0_20px_50px_rgba(var(--hover-shadow-rgb),0.05)] hover:border-{{ $themeColor }}-200/80 dark:hover:border-{{ $themeColor }}-800/60 transition-all duration-300 flex flex-col sm:flex-row sm:items-center justify-between gap-5 group min-w-0 relative overflow-hidden bg-gradient-to-r from-white to-slate-50/50 dark:from-slate-900 dark:to-slate-900/50 hover:to-{{ $themeColor }}-50/20 dark:hover:to-{{ $themeColor }}-950/20"
                         style="--hover-shadow-rgb: @if($themeColor === 'red') 239, 68, 68 @elseif($themeColor === 'amber') 245, 158, 11 @elseif($themeColor === 'emerald') 16, 185, 129 @else 20, 184, 166 @endif">
                        <div class="absolute -top-10 -right-10 w-20 h-20 bg-{{ $themeColor }}-500/10 rounded-full blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        
                        <div class="flex items-center gap-4 min-w-0">
                            <span class="p-3 rounded-2xl bg-{{ $themeColor }}-50 dark:bg-{{ $themeColor }}-950/40 text-{{ $themeColor }}-600 dark:text-{{ $themeColor }}-400 border border-{{ $themeColor }}-100/80 dark:border-{{ $themeColor }}-900/30 shrink-0 h-12 w-12 flex items-center justify-center shadow-sm group-hover:scale-105 group-hover:bg-{{ $themeColor }}-600 group-hover:text-white dark:group-hover:bg-{{ $themeColor }}-500 dark:group-hover:text-slate-950 transition-all duration-300">
                                @if($isFullyBooked)
                                    <svg class="w-5 h-5 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                    </svg>
                                @elseif($hasLimit)
                                    <svg class="w-5 h-5 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                    </svg>
                                @else
                                    <svg class="w-5 h-5 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 10a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z" />
                                    </svg>
                                @endif
                            </span>
                            <div class="space-y-0.5 min-w-0">
                                <div class="text-[10px] font-bold text-{{ $themeColor }}-500 dark:text-{{ $themeColor }}-400 uppercase tracking-widest leading-none">Seat Registration</div>
                                <h4 class="text-base sm:text-lg font-extrabold text-slate-800 dark:text-slate-200 leading-snug break-words tracking-tight mt-1">
                                    {{ $titleText }}
                                </h4>
                            </div>
                        </div>

                        <!-- Right details/Progress bar -->
                        <div class="w-full sm:w-48 shrink-0">
                            @if($hasLimit && !$isFullyBooked)
                                <div class="flex justify-between items-center text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase tracking-wider mb-1">
                                    <span>Occupancy</span>
                                    <span>{{ $fillPercent }}%</span>
                                </div>
                                <div class="w-full bg-slate-100 dark:bg-slate-800 h-2 rounded-full overflow-hidden border border-slate-200/40 dark:border-slate-700/50">
                                    <div class="bg-gradient-to-r @if($themeColor === 'amber') from-amber-500 to-orange-500 @else from-emerald-500 to-teal-500 @endif h-full rounded-full transition-all duration-500" style="width: {{ $fillPercent }}%"></div>
                                </div>
                                <div class="text-[10px] text-right text-slate-400 dark:text-slate-500 mt-1 font-semibold">
                                    {{ $subtext }}
                                </div>
                            @elseif($isFullyBooked)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-red-50/60 dark:bg-red-950/50 text-red-700 dark:text-red-300 border border-red-100/30 dark:border-red-900/20 text-xs font-bold whitespace-nowrap w-full justify-center">
                                    Registration Closed
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-teal-50/60 dark:bg-teal-950/50 text-teal-700 dark:text-teal-300 border border-teal-100/30 dark:border-teal-900/20 text-xs font-bold whitespace-nowrap w-full justify-center">
                                    Unlimited Capacity
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Description / Detailed Block -->
                <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 p-6 sm:p-8 shadow-[0_8px_30px_rgba(15,23,42,0.02)] space-y-4">
                    <h3 class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest border-b border-slate-100 dark:border-slate-800 pb-3">About the Assembly</h3>
                    <div class="text-slate-600 dark:text-slate-300 text-sm leading-relaxed space-y-4 whitespace-pre-line font-normal">
                        {{ $event->description }}
                    </div>
                </div>

                <!-- Arrival / Entry Instructions Card -->
                @if($event->location_type !== 'virtual' && $event->arrival_instructions)
                <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 p-6 sm:p-8 shadow-[0_8px_30px_rgba(15,23,42,0.02)] space-y-4">
                    <div class="flex items-center gap-2 border-b border-slate-100 dark:border-slate-800 pb-3">
                        <span class="p-1 rounded bg-purple-50 dark:bg-purple-950/40 text-purple-600 dark:text-purple-400 shrink-0">
                            <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </span>
                        <h3 class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Entry & Arrival Instructions</h3>
                    </div>
                    <div class="p-4 rounded-2xl bg-slate-50/50 dark:bg-slate-950/20 border border-slate-200/60 dark:border-slate-800/80 text-slate-600 dark:text-slate-300 text-sm leading-relaxed whitespace-pre-line font-medium shadow-inner">
                        {{ $event->arrival_instructions }}
                    </div>
                </div>
                @endif

                <!-- Google Maps Embed Visualization -->
                @if($event->location_type !== 'virtual')
                <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 p-6 sm:p-8 shadow-[0_8px_30px_rgba(15,23,42,0.02)] space-y-4">
                    <h3 class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest border-b border-slate-100 dark:border-slate-800 pb-3">Venue Map</h3>
                    <div class="rounded-2xl overflow-hidden border border-slate-100 dark:border-slate-800 h-64 shadow-inner">
                        <iframe 
                            width="100%" 
                            height="100%" 
                            frameborder="0" 
                            style="border:0" 
                            src="https://maps.google.com/maps?q={{ urlencode($event->location) }}&t=&z=15&ie=UTF8&iwloc=&output=embed" 
                            allowfullscreen>
                        </iframe>
                    </div>
                </div>
                @endif
            </div>

            <!-- Right Column: Registration Form Card (5 Columns) -->
            <div class="lg:col-span-5 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-3xl p-6 sm:p-8 shadow-[0_12px_40px_rgba(15,23,42,0.03)] hover:shadow-[0_16px_48px_rgba(15,23,42,0.06)] transition-all duration-500 h-fit lg:sticky lg:top-8">
                @if($isFullyBooked)
                    <!-- Fully Booked Status Panel -->
                    <div class="text-center py-8 space-y-6">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-rose-50 dark:bg-rose-950/30 text-rose-600 dark:text-rose-400 border border-rose-100 dark:border-rose-900/30 animate-pulse shrink-0">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <div class="space-y-2 text-center">
                            <h2 class="text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight leading-none">Registration Closed</h2>
                            <p class="text-sm text-slate-500 dark:text-slate-400 max-w-sm mx-auto leading-relaxed mt-2">
                                We appreciate your interest, but this assembly has reached its maximum seat capacity limit of <span class="font-bold text-slate-800 dark:text-slate-200">{{ $event->max_participants }} participants</span>.
                            </p>
                        </div>
                        <div class="px-4 py-3 bg-rose-50/50 dark:bg-rose-950/15 border border-rose-100/40 dark:border-rose-900/20 rounded-2xl text-xs text-rose-700 dark:text-rose-400 font-semibold shadow-xs">
                            No remaining seats available
                        </div>
                    </div>
                @else
                    <div>
                        <h2 class="text-xl font-bold text-slate-900 dark:text-white tracking-tight flex items-center gap-2">
                            <span>Register Attendance</span>
                        </h2>
                        <p class="text-xs text-slate-400 dark:text-slate-500 mt-1.5 mb-6">Submit your registration details to receive formal verification and digital seat tickets.</p>

                        <form id="registration-form" action="{{ route('events.public_register', $event) }}" method="POST" class="space-y-5">
                            @csrf
                            <!-- Full Name -->
                            <div class="space-y-2">
                                <label for="name" class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Full Name</label>
                                <div class="relative">
                                    <input type="text" name="name" id="name" required value="{{ old('name') }}" placeholder="e.g. Jane Smith" 
                                        class="w-full rounded-xl border border-slate-200 dark:border-slate-800 py-3 pl-4 pr-10 text-slate-700 dark:text-slate-200 text-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-slate-50/50 dark:bg-slate-950/50 focus:bg-white dark:focus:bg-slate-950 transition-all duration-300">
                                    <span class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-slate-450 dark:text-slate-500">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </span>
                                </div>
                            </div>

                            <!-- Email Address -->
                            <div class="space-y-2">
                                <label for="email" class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Email Address</label>
                                <div class="relative">
                                    <input type="email" name="email" id="email" required value="{{ old('email') }}" placeholder="e.g. jane.smith@domain.com" 
                                        class="w-full rounded-xl border border-slate-200 dark:border-slate-800 py-3 pl-4 pr-10 text-slate-700 dark:text-slate-200 text-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-slate-50/50 dark:bg-slate-950/50 focus:bg-white dark:focus:bg-slate-950 transition-all duration-300">
                                    <span class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-slate-450 dark:text-slate-500">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                    </span>
                                </div>
                            </div>

                            <!-- Gender Identity Dropdown -->
                            <div class="space-y-2">
                                <label for="gender-select" class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Gender Identity</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                        <svg class="w-4 h-4 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                        </svg>
                                    </span>
                                    <select id="gender-select" onchange="handleGenderChange(this)" required 
                                        class="w-full rounded-xl border border-slate-200 dark:border-slate-800 py-3 pl-10 pr-10 text-slate-600 dark:text-slate-300 text-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-slate-50/50 dark:bg-slate-950/50 focus:bg-white dark:focus:bg-slate-950 transition-all duration-300 appearance-none cursor-pointer">
                                        <option value="" disabled {{ old('gender') ? '' : 'selected' }}>Select your gender</option>
                                        <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                                        <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                                        <option value="LGBTQ+" {{ old('gender') == 'LGBTQ+' ? 'selected' : '' }}>LGBTQ+</option>
                                        <option value="Others" {{ (old('gender') && !in_array(old('gender'), ['Male', 'Female', 'LGBTQ+'])) ? 'selected' : '' }}>Others (Please Specify)</option>
                                    </select>
                                    <span class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-slate-455 dark:text-slate-500">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </span>
                                </div>
                                <!-- Hidden actual input submitted with form -->
                                <input type="hidden" name="gender" id="gender-actual" value="{{ old('gender') }}">
                            </div>

                            <!-- Custom Gender Input (Slides down smoothly if "Others" is selected) -->
                            <div id="custom-gender-wrapper" class="space-y-2">
                                <label for="custom-gender" class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Specify Gender</label>
                                <input type="text" id="custom-gender" onkeyup="updateActualGender(this.value)" placeholder="Enter your gender identity" 
                                    class="w-full rounded-xl border border-slate-200 dark:border-slate-800 py-3 px-4 text-slate-700 dark:text-slate-200 text-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-white dark:bg-slate-950/50 transition-all duration-300">
                            </div>

                            <!-- Inclusion Badge with Heart Icon -->
                            <div class="flex items-center gap-3 px-4 py-3 bg-purple-50/50 dark:bg-purple-950/20 border border-purple-100 dark:border-purple-900/30 rounded-2xl text-xs text-purple-700 dark:text-purple-400 font-medium shadow-sm transition-colors duration-300">
                                <svg class="w-5 h-5 text-purple-600 dark:text-purple-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                                <span>Open for All • Inclusivity Assured</span>
                            </div>

                            <!-- Action Button -->
                            <button type="submit" class="group w-full inline-flex items-center justify-center gap-2 text-sm font-semibold py-3.5 px-4 rounded-xl bg-purple-600 hover:bg-purple-700 active:scale-[0.98] text-white transition-all duration-300 shadow-md hover:shadow-lg hover:shadow-purple-500/25 focus:outline-none focus:ring-2 focus:ring-purple-500/20">
                                <span>Submit Registration Request</span>
                                <svg class="w-4 h-4 transition-transform duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                @endif

                <!-- Anti-spam Disclaimer -->
                <div class="mt-6 pt-5 border-t border-slate-100 dark:border-slate-800 text-[10px] text-slate-400 dark:text-slate-500 flex items-start gap-2">
                    <svg class="w-4 h-4 text-slate-400 dark:text-slate-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m0-6h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>Only one registration request is permitted per email address to ensure fair and accurate seating distribution.</span>
                </div>
            </div>

        </div>
    </div>

    <!-- Terms & Conditions Consent Modal -->
    <div id="terms-consent-modal" 
        class="fixed inset-0 bg-slate-900/80 dark:bg-slate-950/90 backdrop-blur-[6px] z-50 hidden items-center justify-center p-4 transition-all duration-300 opacity-0">
        <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-150/80 dark:border-slate-850/80 max-w-lg w-full shadow-2xl flex flex-col max-h-[85vh] transition-all duration-300 transform scale-95 opacity-0 overflow-hidden"
            id="terms-consent-modal-content">
            
            <!-- Header -->
            <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-850 shrink-0 bg-slate-50/50 dark:bg-slate-900/20">
                <h3 class="font-bold text-slate-900 dark:text-white text-lg leading-tight tracking-tight">
                    Review Event Terms & Policy
                </h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                    Please read and confirm the following policies before completing your registration.
                </p>
            </div>

            <!-- Scrollable Terms Box -->
            <div class="p-6 overflow-y-auto flex-grow min-h-0 space-y-4 bg-slate-50/20 dark:bg-slate-950/10">
                <div id="terms-container" class="max-h-60 overflow-y-auto p-4 rounded-2xl border border-slate-200 dark:border-slate-800/80 bg-white dark:bg-slate-950 text-xs sm:text-sm text-slate-600 dark:text-slate-300 leading-relaxed custom-scrollbar whitespace-pre-line select-text">
                    {{ $event->terms_and_policy ?: "By registering for this event, you agree to abide by the event organizer's code of conduct and standard community guidelines. Please ensure that all information provided is accurate." }}
                </div>

                <!-- Agreement Checkbox -->
                <div class="flex items-start gap-2.5 pt-2 border-t border-slate-100 dark:border-slate-800/60">
                    <div class="relative flex items-center">
                        <input type="checkbox" id="terms-checkbox" disabled
                            class="w-4 h-4 rounded text-purple-600 border-slate-300 dark:border-slate-700 focus:ring-purple-500 focus:ring-offset-0 disabled:opacity-50 disabled:cursor-not-allowed transition duration-150">
                    </div>
                    <label for="terms-checkbox" class="text-xs text-slate-600 dark:text-slate-400 font-medium leading-normal cursor-pointer select-none">
                        I have scrolled to the bottom and agree to the event's terms and privacy policy. <span class="text-purple-500 font-semibold">*</span>
                    </label>
                </div>
            </div>

            <!-- Footer Buttons -->
            <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-slate-100 dark:border-slate-850 shrink-0 bg-slate-50/50 dark:bg-slate-900/20">
                <button type="button" onclick="closeTermsModal()"
                    class="text-xs font-bold py-2.5 px-4 rounded-xl border border-slate-200 dark:border-slate-850 bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800/80 text-slate-700 dark:text-slate-300 transition duration-155 active:scale-[0.98]">
                    Cancel
                </button>
                <button type="button" id="confirm-submit-btn" disabled onclick="submitRegistration()"
                    class="inline-flex items-center gap-1.5 text-xs font-bold py-2.5 px-5 rounded-xl bg-purple-600 hover:bg-purple-700 disabled:bg-slate-200 dark:disabled:bg-slate-800 disabled:text-slate-400 dark:disabled:text-slate-500 text-white transition-all duration-200 shadow-md hover:shadow-lg hover:shadow-purple-500/20 disabled:shadow-none disabled:cursor-not-allowed active:scale-[0.98]">
                    <span>Confirm & Register</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                    </svg>
                </button>
            </div>

        </div>
    </div>

    <!-- Footer -->
    <footer class="py-6 text-center text-xs text-slate-400 dark:text-slate-500 border-t border-slate-100/80 dark:border-slate-800/80 z-10 relative">
        &copy; {{ date('Y') }} App Central. All rights reserved.
    </footer>

    <!-- Toast Notifications script & Gender logic -->
    <script>
        // Theme Toggle Functionality
        document.addEventListener('DOMContentLoaded', () => {
            const themeToggleBtn = document.getElementById('theme-toggle');
            const themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
            const themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

            if (themeToggleBtn && themeToggleDarkIcon && themeToggleLightIcon) {
                // Show the appropriate icon based on the current theme
                if (document.documentElement.classList.contains('dark')) {
                    themeToggleLightIcon.classList.remove('hidden');
                } else {
                    themeToggleDarkIcon.classList.remove('hidden');
                }

                // Add toggle click listener
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

        window.showToast = function(message, type = 'success') {
            const container = document.getElementById('toast-container');
            if (!container) return;

            // Revamped Premium Spacious Toast Card
            const toast = document.createElement('div');
            toast.className = 'pointer-events-auto bg-white/95 dark:bg-slate-900/95 backdrop-blur-md rounded-2xl border border-slate-100 dark:border-slate-800/80 shadow-[0_16px_48px_rgba(15,23,42,0.12)] p-5 flex items-start gap-4 translate-x-12 opacity-0 transition-all duration-300 ease-out max-w-md w-full relative overflow-hidden';

            let iconSvg = '';
            let progressBarGradient = '';
            let categoryLabel = '';
            
            if (type === 'success') {
                progressBarGradient = 'from-emerald-400 to-teal-500';
                categoryLabel = 'Success';
                iconSvg = `
                    <span class="p-2.5 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </span>
                `;
            } else {
                progressBarGradient = 'from-rose-400 to-red-500';
                categoryLabel = 'System Notice';
                iconSvg = `
                    <span class="p-2.5 rounded-xl bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </span>
                `;
            }

            toast.innerHTML = `
                ${iconSvg}
                <div class="flex-grow pr-2 pb-0.5 text-left">
                    <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500 leading-none mb-1.5">${categoryLabel}</p>
                    <p class="text-sm font-semibold text-slate-800 dark:text-slate-200 tracking-tight leading-relaxed">${message}</p>
                </div>
                <button onclick="this.parentElement.remove()" class="text-slate-400 dark:text-slate-500 hover:text-slate-600 dark:hover:text-slate-350 transition-colors shrink-0 p-1 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-850/60">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
                <div class="progress-bar absolute bottom-0 left-0 h-1 bg-gradient-to-r ${progressBarGradient} rounded-b-2xl" style="width: 100%; transition: width 4000ms linear;"></div>
            `;

            container.appendChild(toast);

            setTimeout(() => {
                toast.classList.remove('translate-x-12', 'opacity-0');
                const progressBar = toast.querySelector('.progress-bar');
                if (progressBar) progressBar.style.width = '0%';
            }, 50);

            setTimeout(() => {
                toast.classList.add('translate-x-12', 'opacity-0');
                setTimeout(() => {
                    toast.remove();
                }, 300);
            }, 4000);
        };

        function handleGenderChange(selectEl) {
            const val = selectEl.value;
            const actualInput = document.getElementById('gender-actual');
            const customWrapper = document.getElementById('custom-gender-wrapper');
            const customInput = document.getElementById('custom-gender');

            if (val === 'Others') {
                customWrapper.classList.add('open');
                customInput.setAttribute('required', 'required');
                actualInput.value = customInput.value;
            } else {
                customWrapper.classList.remove('open');
                customInput.removeAttribute('required');
                actualInput.value = val;
            }
        }

        function updateActualGender(val) {
            document.getElementById('gender-actual').value = val;
        }

        function openTermsModal() {
            const modal = document.getElementById('terms-consent-modal');
            const content = document.getElementById('terms-consent-modal-content');
            if (modal && content) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                requestAnimationFrame(() => {
                    modal.classList.remove('opacity-0');
                    content.classList.remove('scale-95', 'opacity-0');
                });
            }
        }

        function closeTermsModal() {
            const modal = document.getElementById('terms-consent-modal');
            const content = document.getElementById('terms-consent-modal-content');
            if (modal && content) {
                modal.classList.add('opacity-0');
                content.classList.add('scale-95', 'opacity-0');
                setTimeout(() => {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                }, 300);
            }
        }

        function submitRegistration() {
            const form = document.getElementById('registration-form');
            if (form) {
                form.submit();
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            const selectEl = document.getElementById('gender-select');
            if (selectEl) {
                handleGenderChange(selectEl);
            }

            const form = document.getElementById('registration-form');
            const termsContainer = document.getElementById('terms-container');
            const termsCheckbox = document.getElementById('terms-checkbox');
            const confirmBtn = document.getElementById('confirm-submit-btn');

            if (form) {
                form.addEventListener('submit', (e) => {
                    if (!termsCheckbox || !termsCheckbox.checked) {
                        e.preventDefault();
                        openTermsModal();
                    }
                });
            }

            if (termsContainer && termsCheckbox) {
                function checkTermsScroll() {
                    // Give a tolerance of 15px for zoom/sub-pixels
                    if (termsContainer.scrollHeight - termsContainer.scrollTop <= termsContainer.clientHeight + 15) {
                        termsCheckbox.removeAttribute('disabled');
                    }
                }

                termsContainer.addEventListener('scroll', checkTermsScroll);

                // Initial check in case text is short enough and doesn't need scroll
                setTimeout(() => {
                    if (termsContainer.scrollHeight <= termsContainer.clientHeight + 15) {
                        termsCheckbox.removeAttribute('disabled');
                    }
                }, 150);
            }

            if (termsCheckbox && confirmBtn) {
                termsCheckbox.addEventListener('change', () => {
                    if (termsCheckbox.checked) {
                        confirmBtn.removeAttribute('disabled');
                    } else {
                        confirmBtn.setAttribute('disabled', 'disabled');
                    }
                });
            }

            @if(session('success'))
                window.showToast("{{ session('success') }}", 'success');
            @endif

            @if(session('error'))
                window.showToast("{{ session('error') }}", 'error');
            @endif
        });
    </script>
</body>
</html>
