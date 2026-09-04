@extends('layouts.app')

@section('title', $committee ? $committee->name . ' - Workspace' : 'Events Management')

@section('content')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        /* Custom scrollbar for custom dropdown menu */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: rgba(156, 163, 175, 0.25);
            border-radius: 20px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background-color: rgba(156, 163, 175, 0.45);
        }

        .dark .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: rgba(156, 163, 175, 0.15);
        }

        /* PREMIUM FLATPICKR LIGHT & DARK MODE ACCENTED OVERRIDES (COMPACT & UN-BLOATED) */
        .flatpickr-calendar {
            background: #ffffff !important;
            border: 1px solid rgba(226, 232, 240, 0.8) !important;
            border-radius: 1.25rem !important;
            box-shadow: 0 15px 30px -5px rgba(0, 0, 0, 0.08), 0 6px 10px -6px rgba(0, 0, 0, 0.08) !important;
            font-family: inherit !important;
            padding: 4px !important;
            width: 260px !important;
        }
        .dark .flatpickr-calendar {
            background: #0f172a !important; /* slate-900 */
            border: 1px solid rgba(51, 65, 85, 0.5) !important; /* slate-700 */
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5) !important;
        }
        .flatpickr-days {
            width: 252px !important;
        }
        .dayContainer {
            width: 252px !important;
            min-width: 252px !important;
            max-width: 252px !important;
        }
        .flatpickr-day {
            border-radius: 0.5rem !important;
            height: 32px !important;
            line-height: 32px !important;
            max-width: 32px !important;
            margin: 1px 2px !important;
            font-size: 11px !important;
            font-weight: 600 !important;
        }
        .flatpickr-day.selected, .flatpickr-day.startRange, .flatpickr-day.endRange, .flatpickr-day.selected.inRange, .flatpickr-day.startRange.inRange, .flatpickr-day.endRange.inRange, .flatpickr-day.selected:focus, .flatpickr-day.startRange:focus, .flatpickr-day.endRange:focus, .flatpickr-day.selected:hover, .flatpickr-day.startRange:hover, .flatpickr-day.endRange:hover, .flatpickr-day.prevMonthDay.selected, .flatpickr-day.nextMonthDay.selected {
            background: #9333ea !important; /* purple-600 */
            border-color: #9333ea !important;
            color: #ffffff !important;
            border-radius: 0.5rem !important;
        }
        .flatpickr-day:hover {
            background: rgba(147, 51, 234, 0.1) !important;
            border-color: transparent !important;
            border-radius: 0.5rem !important;
        }
        .dark .flatpickr-day {
            color: #f1f5f9 !important; /* slate-100 */
        }
        .dark .flatpickr-day.flatpickr-disabled, .dark .flatpickr-day.flatpickr-disabled:hover {
            color: rgba(255, 255, 255, 0.15) !important;
        }
        .flatpickr-months {
            padding: 4px 0 !important;
        }
        .flatpickr-months .flatpickr-month {
            color: #0f172a !important;
            height: 28px !important;
        }
        .dark .flatpickr-months .flatpickr-month {
            color: #ffffff !important;
        }
        .flatpickr-current-month {
            font-size: 12px !important;
            padding: 2px 0 0 0 !important;
        }
        .flatpickr-current-month .flatpickr-monthDropdown-months {
            font-weight: 700 !important;
        }
        .flatpickr-current-month input.cur-year {
            font-weight: 700 !important;
        }
        .dark .flatpickr-current-month input.cur-year, .dark .flatpickr-current-month .flatpickr-monthDropdown-months {
            color: #ffffff !important;
        }
        .flatpickr-time {
            border-top: 1px solid rgba(226, 232, 240, 0.8) !important;
            height: 36px !important;
            line-height: 36px !important;
        }
        .dark .flatpickr-time {
            border-top: 1px solid rgba(51, 65, 85, 0.5) !important;
            background: #0f172a !important;
        }
        .flatpickr-time input {
            font-size: 12px !important;
        }
        .flatpickr-time .numInputWrapper {
            height: 36px !important;
        }
        .dark .flatpickr-time input, .dark .flatpickr-time .flatpickr-am-pm {
            color: #ffffff !important;
        }
        .dark .flatpickr-time input:hover, .dark .flatpickr-time .flatpickr-am-pm:hover, .dark .flatpickr-time input:focus, .dark .flatpickr-time .flatpickr-am-pm:focus {
            background: rgba(147, 51, 234, 0.1) !important;
        }
        .flatpickr-day.today {
            border-color: #c084fc !important; /* purple-400 */
            border-radius: 0.5rem !important;
        }
        .flatpickr-months .flatpickr-prev-month, .flatpickr-months .flatpickr-next-month {
            fill: #64748b !important;
            padding: 4px !important;
        }
        .dark .flatpickr-months .flatpickr-prev-month, .dark .flatpickr-months .flatpickr-next-month {
            fill: #cbd5e1 !important;
        }
        .flatpickr-day.flatpickr-disabled, .flatpickr-day.flatpickr-disabled:hover {
            color: #cbd5e1 !important;
        }
        .flatpickr-weekday {
            font-weight: 700 !important;
            color: #64748b !important;
            font-size: 10px !important;
        }
        .dark .flatpickr-weekday {
            color: #94a3b8 !important;
        }
    </style>
    <div class="space-y-8 max-w-7xl mx-auto">
        <!-- Breadcrumbs -->
        <div class="flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400">
            <a href="{{ route('dashboard') }}?tab=committees" class="hover:text-slate-900 dark:hover:text-white transition-colors">Portal</a>
            <svg class="w-4 h-4 text-slate-300 dark:text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
            <span class="text-slate-900 dark:text-slate-300 font-medium">{{ $committee ? $committee->name : 'Events Management' }}</span>
        </div>

        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between border-b border-slate-200/60 dark:border-slate-800/80 pb-6 gap-4">
            <div>
                <h1 class="text-3xl font-bold text-slate-900 dark:text-white tracking-tight flex items-center gap-3">
                    <span
                        class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-purple-50 dark:bg-purple-950/30 text-purple-600 dark:text-purple-400 font-bold text-lg">
                        {{ $committee ? substr($committee->name, 0, 2) : 'EM' }}
                    </span>
                    {{ $committee ? $committee->name . ' Workspace' : 'Events Management' }}
                </h1>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1.5">Official collaboration cockpit and events directory for committee
                    personnel.</p>
            </div>

            <div class="flex flex-wrap items-center gap-4">
                @if (isset($committees) && $committees->isNotEmpty())
                    <form id="committee-select-form" action="{{ route('committees.events.index') }}" method="GET" class="relative select-none shrink-0">
                        <input type="hidden" name="committee_id" id="committee-filter" value="{{ $committee ? $committee->id : '' }}">
                        <button type="button" id="committee-dropdown-btn" onclick="toggleDropdown('committee')"
                            class="w-full sm:w-auto flex items-center justify-between gap-3 bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800/80 px-4 py-2.5 text-slate-600 dark:text-slate-300 text-sm shadow-[0_4px_12px_rgba(15,23,42,0.02)] hover:shadow-[0_6px_18px_rgba(15,23,42,0.04)] hover:border-slate-200/80 dark:hover:border-slate-700 transition-all duration-300 focus:outline-none">
                            <span id="committee-dropdown-label" class="font-medium">{{ $committee ? $committee->name : 'Select Committee' }}</span>
                            <svg id="committee-dropdown-arrow" class="w-4 h-4 text-slate-400 dark:text-slate-500 transition-transform duration-200"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div id="committee-dropdown-menu"
                            class="absolute right-0 mt-2 py-1 bg-white dark:bg-slate-900 rounded-2xl border border-slate-100/80 dark:border-slate-800/80 shadow-[0_10px_30px_rgba(15,23,42,0.08)] z-30 hidden max-h-60 w-64 overflow-y-auto custom-scrollbar transition-all duration-200">
                            @foreach ($committees as $c)
                                <button type="button" onclick="selectCommitteeOption('{{ $c->id }}', '{{ addslashes($c->name) }}')"
                                    class="w-full text-left px-4 py-2 text-sm {{ $committee && $committee->id == $c->id ? 'bg-purple-50 dark:bg-purple-950/40 text-purple-700 dark:text-purple-400 font-semibold hover:bg-purple-50/80 dark:hover:bg-purple-950/60' : 'text-slate-600 dark:text-slate-300 hover:bg-purple-50/60 dark:hover:bg-purple-950/20 hover:text-purple-700 dark:hover:text-purple-400' }} transition duration-150">
                                    {{ $c->name }}
                                </button>
                            @endforeach
                        </div>
                    </form>
                @endif

                <a href="{{ route('dashboard') }}?tab=committees"
                    class="inline-flex items-center gap-2 text-sm font-semibold py-2.5 px-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:border-slate-300 dark:hover:border-slate-700 transition duration-150">
                    <svg class="w-4 h-4 text-slate-500 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Portal
                </a>
            </div>
        </div>

        <!-- Feedback messages -->

        @if (session('error'))
            <div
                class="p-4 rounded-xl bg-red-50 dark:bg-red-950/20 border border-red-100 dark:border-red-900/40 text-red-800 dark:text-red-400 text-sm flex items-center gap-3 shadow-sm">
                <svg class="w-5 h-5 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="p-4 rounded-xl bg-red-50 dark:bg-red-950/20 border border-red-100 dark:border-red-900/40 text-red-800 dark:text-red-400 text-sm flex flex-col gap-1 shadow-sm">
                @foreach ($errors->all() as $error)
                    <div class="flex items-start gap-2">
                        <svg class="w-4 h-4 shrink-0 mt-0.5 text-red-500" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <span>{{ $error }}</span>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Filters and Actions Bar (Highly Responsive, Floating Style with Soft Edges) -->
        <div
            class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800/80 p-4 shadow-[0_8px_30px_rgba(15,23,42,0.04)]">
            <!-- Left Side: Search Filter, Custom Month and Year Filters -->
            <div class="flex flex-col md:flex-row items-stretch md:items-center gap-3.5 flex-grow max-w-4xl">
                <!-- Search Text Box Wrapper (Floating) -->
                <div
                    class="relative flex-grow bg-white dark:bg-slate-950 rounded-2xl border border-slate-100 dark:border-slate-800/80 hover:border-slate-200/80 dark:hover:border-slate-700 shadow-[0_4px_12px_rgba(15,23,42,0.02)] hover:shadow-[0_6px_18px_rgba(15,23,42,0.04)] transition-all duration-300">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </span>
                    <input type="text" id="search-input" onkeyup="filterEvents()"
                        placeholder="Search scheduled assemblies..."
                        class="w-full pl-10 pr-4 py-2.5 rounded-2xl border-0 text-slate-600 dark:text-slate-200 text-sm focus:ring-0 focus:outline-none bg-transparent placeholder-slate-400 dark:placeholder-slate-500 transition-all duration-300">
                </div>

                <!-- Custom Styled Month Dropdown (Non-Native, Rich Aesthetics) -->
                <div class="relative w-full md:w-44 shrink-0 select-none">
                    <input type="hidden" id="month-filter" value="">
                    <button type="button" id="month-dropdown-btn" onclick="toggleDropdown('month')"
                        class="w-full flex items-center justify-between bg-white dark:bg-slate-950 rounded-2xl border border-slate-100 dark:border-slate-800/80 px-4 py-2.5 text-slate-600 dark:text-slate-300 text-sm shadow-[0_4px_12px_rgba(15,23,42,0.02)] hover:shadow-[0_6px_18px_rgba(15,23,42,0.04)] hover:border-slate-200/80 dark:hover:border-slate-700 transition-all duration-300 focus:outline-none">
                        <span id="month-dropdown-label" class="font-medium">All Months</span>
                        <svg id="month-dropdown-arrow" class="w-4 h-4 text-slate-400 dark:text-slate-500 transition-transform duration-200"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div id="month-dropdown-menu"
                        class="absolute left-0 right-0 mt-2 py-1 bg-white dark:bg-slate-900 rounded-2xl border border-slate-100/80 dark:border-slate-800/80 shadow-[0_10px_30px_rgba(15,23,42,0.08)] z-30 hidden max-h-60 overflow-y-auto custom-scrollbar transition-all duration-200">
                        <button type="button" onclick="selectDropdownOption('month', '', 'All Months')"
                            class="w-full text-left px-4 py-2 text-sm bg-purple-50 dark:bg-purple-950/40 text-purple-700 dark:text-purple-400 font-semibold hover:bg-purple-50/80 dark:hover:bg-purple-950/60 transition duration-150">All
                            Months</button>
                        <button type="button" onclick="selectDropdownOption('month', '01', 'January')"
                            class="w-full text-left px-4 py-2 text-sm text-slate-600 dark:text-slate-300 hover:bg-purple-50/60 dark:hover:bg-purple-950/20 hover:text-purple-700 dark:hover:text-purple-400 transition duration-150">January</button>
                        <button type="button" onclick="selectDropdownOption('month', '02', 'February')"
                            class="w-full text-left px-4 py-2 text-sm text-slate-600 dark:text-slate-300 hover:bg-purple-50/60 dark:hover:bg-purple-950/20 hover:text-purple-700 dark:hover:text-purple-400 transition duration-150">February</button>
                        <button type="button" onclick="selectDropdownOption('month', '03', 'March')"
                            class="w-full text-left px-4 py-2 text-sm text-slate-600 dark:text-slate-300 hover:bg-purple-50/60 dark:hover:bg-purple-950/20 hover:text-purple-700 dark:hover:text-purple-400 transition duration-150">March</button>
                        <button type="button" onclick="selectDropdownOption('month', '04', 'April')"
                            class="w-full text-left px-4 py-2 text-sm text-slate-600 dark:text-slate-300 hover:bg-purple-50/60 dark:hover:bg-purple-950/20 hover:text-purple-700 dark:hover:text-purple-400 transition duration-150">April</button>
                        <button type="button" onclick="selectDropdownOption('month', '05', 'May')"
                            class="w-full text-left px-4 py-2 text-sm text-slate-600 dark:text-slate-300 hover:bg-purple-50/60 dark:hover:bg-purple-950/20 hover:text-purple-700 dark:hover:text-purple-400 transition duration-150">May</button>
                        <button type="button" onclick="selectDropdownOption('month', '06', 'June')"
                            class="w-full text-left px-4 py-2 text-sm text-slate-600 dark:text-slate-300 hover:bg-purple-50/60 dark:hover:bg-purple-950/20 hover:text-purple-700 dark:hover:text-purple-400 transition duration-150">June</button>
                        <button type="button" onclick="selectDropdownOption('month', '07', 'July')"
                            class="w-full text-left px-4 py-2 text-sm text-slate-600 dark:text-slate-300 hover:bg-purple-50/60 dark:hover:bg-purple-950/20 hover:text-purple-700 dark:hover:text-purple-400 transition duration-150">July</button>
                        <button type="button" onclick="selectDropdownOption('month', '08', 'August')"
                            class="w-full text-left px-4 py-2 text-sm text-slate-600 dark:text-slate-300 hover:bg-purple-50/60 dark:hover:bg-purple-950/20 hover:text-purple-700 dark:hover:text-purple-400 transition duration-150">August</button>
                        <button type="button" onclick="selectDropdownOption('month', '09', 'September')"
                            class="w-full text-left px-4 py-2 text-sm text-slate-600 dark:text-slate-300 hover:bg-purple-50/60 dark:hover:bg-purple-950/20 hover:text-purple-700 dark:hover:text-purple-400 transition duration-150">September</button>
                        <button type="button" onclick="selectDropdownOption('month', '10', 'October')"
                            class="w-full text-left px-4 py-2 text-sm text-slate-600 dark:text-slate-300 hover:bg-purple-50/60 dark:hover:bg-purple-950/20 hover:text-purple-700 dark:hover:text-purple-400 transition duration-150">October</button>
                        <button type="button" onclick="selectDropdownOption('month', '11', 'November')"
                            class="w-full text-left px-4 py-2 text-sm text-slate-600 dark:text-slate-300 hover:bg-purple-50/60 dark:hover:bg-purple-950/20 hover:text-purple-700 dark:hover:text-purple-400 transition duration-150">November</button>
                        <button type="button" onclick="selectDropdownOption('month', '12', 'December')"
                            class="w-full text-left px-4 py-2 text-sm text-slate-600 dark:text-slate-300 hover:bg-purple-50/60 dark:hover:bg-purple-950/20 hover:text-purple-700 dark:hover:text-purple-400 transition duration-150">December</button>
                    </div>
                </div>

                <!-- Custom Styled Year Dropdown (Non-Native, Rich Aesthetics) -->
                @php
                    $years = $events->map(fn($e) => $e->event_date->format('Y'))->unique()->sort();
                @endphp
                <div class="relative w-full md:w-36 shrink-0 select-none">
                    <input type="hidden" id="year-filter" value="">
                    <button type="button" id="year-dropdown-btn" onclick="toggleDropdown('year')"
                        class="w-full flex items-center justify-between bg-white dark:bg-slate-950 rounded-2xl border border-slate-100 dark:border-slate-800/80 px-4 py-2.5 text-slate-600 dark:text-slate-300 text-sm shadow-[0_4px_12px_rgba(15,23,42,0.02)] hover:shadow-[0_6px_18px_rgba(15,23,42,0.04)] hover:border-slate-200/80 dark:hover:border-slate-700 transition-all duration-300 focus:outline-none">
                        <span id="year-dropdown-label" class="font-medium">All Years</span>
                        <svg id="year-dropdown-arrow" class="w-4 h-4 text-slate-400 dark:text-slate-500 transition-transform duration-200"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div id="year-dropdown-menu"
                        class="absolute left-0 right-0 mt-2 py-1 bg-white dark:bg-slate-900 rounded-2xl border border-slate-100/80 dark:border-slate-800/80 shadow-[0_10px_30px_rgba(15,23,42,0.08)] z-30 hidden max-h-60 overflow-y-auto custom-scrollbar transition-all duration-200">
                        <button type="button" onclick="selectDropdownOption('year', '', 'All Years')"
                            class="w-full text-left px-4 py-2 text-sm bg-purple-50 dark:bg-purple-950/40 text-purple-700 dark:text-purple-400 font-semibold hover:bg-purple-50/80 dark:hover:bg-purple-950/60 transition duration-150">All
                            Years</button>
                        @foreach ($years as $year)
                            <button type="button"
                                onclick="selectDropdownOption('year', '{{ $year }}', '{{ $year }}')"
                                class="w-full text-left px-4 py-2 text-sm text-slate-600 dark:text-slate-300 hover:bg-purple-50/60 dark:hover:bg-purple-950/20 hover:text-purple-700 dark:hover:text-purple-400 transition duration-150">{{ $year }}</button>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Right Side: Add Event Button with Rolling Cross & Hover Glow / Scale Animations -->
            @if ($committee)
                <div class="shrink-0 w-full lg:w-auto">
                    <button onclick="openAddEventModal()"
                        class="group w-full lg:w-auto inline-flex items-center justify-center gap-2 text-sm font-semibold py-2.5 px-5 rounded-2xl bg-purple-600 hover:bg-purple-700 text-white transition-all duration-300 transform hover:scale-105 active:scale-95 shadow-md hover:shadow-lg hover:shadow-purple-500/30 focus:outline-none focus:ring-2 focus:ring-purple-500/20">
                        <svg class="w-4 h-4 transition-transform duration-500 ease-out group-hover:rotate-180"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                        </svg>
                        Add Event
                    </button>
                </div>
            @endif
        </div>

        <!-- Main List/Directory Area -->
        <div class="space-y-6">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-bold text-slate-900 dark:text-white tracking-tight">Assemblies Directory</h2>
                <span class="px-2.5 py-1 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 rounded-lg text-xs font-semibold border border-slate-200/50 dark:border-slate-700/50">Showing: <span
                        id="visible-count">{{ $events->count() }}</span> of {{ $events->count() }}</span>
            </div>

            <!-- Empty State Container for No Events Scheduled -->
            @if ($events->isEmpty())
                <div
                    class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800/80 p-12 text-center text-slate-400 dark:text-slate-500 text-sm shadow-sm">
                    <svg class="w-12 h-12 text-slate-300 dark:text-slate-700 mx-auto mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v12a2 2 0 002 2z" />
                    </svg>
                    No assemblies are currently scheduled for the
                    {{ $committee ? $committee->name : 'selected committee' }}. Click "Add Event" to schedule the first
                    one!
                </div>
            @else
                <!-- Responsive Grid for Scheduled Events -->
                <div id="events-list-container" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach ($events as $event)
                        <!-- Event Card Component -->
                        <div class="event-card bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800/80 shadow-sm hover:shadow-md transition-all duration-300 flex flex-col justify-between overflow-hidden"
                            data-title="{{ strtolower($event->title) }}"
                            data-description="{{ strtolower($event->description) }}"
                            data-month="{{ $event->event_date->format('m') }}"
                            data-year="{{ $event->event_date->format('Y') }}">
                            
                            @if($event->image)
                                <div class="h-40 w-full overflow-hidden relative shrink-0">
                                    <img src="{{ $event->image }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 dark:from-slate-950/70 to-transparent"></div>
                                </div>
                            @endif

                            <div class="p-6 flex-grow flex flex-col justify-between space-y-6">
                                <div>
                                    <div class="flex items-start justify-between gap-4">
                                        <div class="truncate pr-2">
                                            <h3 class="text-lg font-bold text-slate-900 dark:text-white tracking-tight truncate"
                                                title="{{ $event->title }}">{{ $event->title }}</h3>
                                            <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">Scheduled by Secretariat</p>
                                        </div>
                                        <span
                                            class="shrink-0 px-2 py-0.5 bg-emerald-50 dark:bg-emerald-950/30 text-emerald-700 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-900/30 text-[10px] font-bold rounded uppercase tracking-wider">Active
                                            Registration</span>
                                    </div>

                                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-3.5 leading-relaxed line-clamp-3">
                                        {{ $event->description }}
                                    </p>

                                    <!-- Details grid -->
                                    <div
                                        class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-6 pt-5 border-t border-slate-100 dark:border-slate-800/60 text-xs">
                                        <div class="flex items-center gap-2 text-slate-600 dark:text-slate-400">
                                            <svg class="w-4 h-4 text-slate-400 dark:text-slate-500 shrink-0" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <span class="truncate">{{ $event->event_date->format('l, M j • g:i A') }}</span>
                                        </div>
                                        @if($event->location_type === 'virtual')
                                            <a href="{{ $event->location }}" target="_blank" rel="noopener noreferrer" class="flex items-center gap-2 text-slate-600 dark:text-slate-400 hover:text-purple-600 dark:hover:text-purple-400 transition-colors" title="Join Zoom/Teams Virtual Assembly">
                                                <svg class="w-4 h-4 text-slate-400 dark:text-slate-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                                </svg>
                                                <span class="truncate underline decoration-dotted font-semibold text-purple-600 dark:text-purple-400">Join Virtual Assembly</span>
                                            </a>
                                        @else
                                            <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($event->location) }}" target="_blank" rel="noopener noreferrer" class="flex items-center gap-2 text-slate-600 dark:text-slate-400 hover:text-purple-600 dark:hover:text-purple-400 transition-colors" title="Open Location in Google Maps">
                                                <svg class="w-4 h-4 text-slate-400 dark:text-slate-500 shrink-0" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                                <span class="truncate underline decoration-dotted" title="{{ $event->location }}">{{ $event->location }}</span>
                                            </a>
                                        @endif
                                    </div>
                                    @if($event->location_type === 'physical' && !empty($event->arrival_instructions))
                                        <div class="mt-3 p-3 bg-slate-50 dark:bg-slate-950/40 border border-slate-100 dark:border-slate-800 rounded-xl text-xs text-slate-500 dark:text-slate-400 leading-relaxed text-left flex items-start gap-2">
                                            <svg class="w-4 h-4 text-purple-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <div>
                                                <span class="font-bold text-slate-700 dark:text-slate-300">Arrival Instructions:</span>
                                                {{ $event->arrival_instructions }}
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <!-- Actions Row -->
                                <div
                                    class="pt-5 border-t border-slate-100 dark:border-slate-800/60 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                    <div class="flex items-center gap-2 w-full sm:w-auto">
                                        <!-- Manage Event Link -->
                                        <a href="{{ route('committees.events.manage', $event) }}"
                                            class="w-full sm:w-auto text-xs font-semibold text-purple-700 dark:text-purple-400 hover:text-purple-900 bg-purple-50 dark:bg-purple-950/30 hover:bg-purple-100/80 dark:hover:bg-purple-950/50 px-3.5 py-2.5 rounded-xl border border-purple-100 dark:border-purple-900/30 transition duration-150 inline-flex items-center justify-center gap-1.5 shadow-sm">
                                            <span>Manage Event</span>
                                            <span
                                                class="px-2 py-0.5 bg-purple-600 text-white text-[10px] rounded-full font-bold">
                                                {{ $event->registrations->where('status', 'pending')->count() }}
                                            </span>
                                        </a>

                                        <!-- Copy Shareable Link with Customizable Image Icon -->
                                        <button onclick="copyEventLink('{{ route('events.public_show', $event) }}')"
                                            class="w-full sm:w-auto text-xs font-semibold text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 bg-slate-50 dark:bg-slate-800/40 hover:bg-slate-100 dark:hover:bg-slate-800/80 px-3.5 py-2.5 rounded-xl border border-slate-200/80 dark:border-slate-700/80 transition duration-150 inline-flex items-center justify-center gap-2 shadow-sm">
                                            <svg class="w-3.5 h-3.5 text-slate-500 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                                            </svg>
                                            Share Link
                                        </button>
                                    </div>

                                    <div class="flex items-center gap-2 w-full sm:w-auto justify-end">
                                        <!-- Invitation Preview -->
                                        <a href="{{ route('events.public_show', $event) }}" target="_blank"
                                            rel="noopener noreferrer"
                                            class="inline-flex items-center justify-center gap-2 px-5 py-2.5 text-xs font-medium text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-lg shadow-sm hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white hover:border-slate-300 dark:hover:border-slate-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200 group">
                                            <span>Invitation Preview</span>
                                            <svg class="w-4 h-4 text-slate-400 dark:text-slate-500 group-hover:text-slate-600 dark:group-hover:text-slate-400 transition-colors"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Dynamic Empty State (shown when filters don't match anything) -->
                <div id="dynamic-empty-state"
                    class="hidden bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800/80 p-12 text-center text-slate-400 dark:text-slate-500 text-sm shadow-sm">
                    <svg class="w-12 h-12 text-slate-300 dark:text-slate-700 mx-auto mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    No scheduled assemblies match your filters. Try clearing your search query or selecting a different
                    month/year.
                </div>
            @endif
        </div>
    </div>

    <!-- BACKDROP MODAL: SCHEDULE NEW EVENT (Add Event Modal) -->
    @if ($committee)
        @include('committees.events-app.events-components.add_event_modal')
    @endif
@endsection

@section('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        // Custom Dropdown Handling
        function toggleDropdown(type) {
            const dropdowns = ['month', 'year', 'committee'];
            dropdowns.forEach(t => {
                const menu = document.getElementById(t + '-dropdown-menu');
                const arrow = document.getElementById(t + '-dropdown-arrow');
                if (!menu) return;

                if (t === type) {
                    const isHidden = menu.classList.contains('hidden');
                    if (isHidden) {
                        menu.classList.remove('hidden');
                        if (arrow) arrow.classList.add('rotate-180');
                    } else {
                        menu.classList.add('hidden');
                        if (arrow) arrow.classList.remove('rotate-180');
                    }
                } else {
                    menu.classList.add('hidden');
                    if (arrow) arrow.classList.remove('rotate-180');
                }
            });
        }

        // Toggle Capacity input field visibility
        function handleCapacityChange(selectEl) {
            const val = selectEl.value;
            const wrapper = document.getElementById('capacity-input-wrapper');
            const input = document.getElementById('max_participants');

            if (val === 'limited') {
                wrapper.classList.remove('hidden');
                input.setAttribute('required', 'required');
            } else {
                wrapper.classList.add('hidden');
                input.removeAttribute('required');
                input.value = '';
            }
        }

        function selectDropdownOption(type, value, label) {
            const input = document.getElementById(type + '-filter');
            const labelEl = document.getElementById(type + '-dropdown-label');
            const menu = document.getElementById(type + '-dropdown-menu');
            const arrow = document.getElementById(type + '-dropdown-arrow');

            if (input && labelEl && menu) {
                input.value = value;
                labelEl.innerText = label;

                // Highlight selected in dropdown menu
                const buttons = menu.querySelectorAll('button');
                buttons.forEach(btn => {
                    if (btn.innerText === label) {
                        btn.className =
                            "w-full text-left px-4 py-2 text-sm bg-purple-50 dark:bg-purple-950/40 text-purple-700 dark:text-purple-400 font-semibold hover:bg-purple-50/80 dark:hover:bg-purple-950/60 transition duration-150";
                    } else {
                        btn.className =
                            "w-full text-left px-4 py-2 text-sm text-slate-600 dark:text-slate-300 hover:bg-purple-50/60 dark:hover:bg-purple-950/20 hover:text-purple-700 dark:hover:text-purple-400 transition duration-150";
                    }
                });

                menu.classList.add('hidden');
                if (arrow) arrow.classList.remove('rotate-180');

                // Trigger the original filter logic
                filterEvents();
            }
        }

        function selectCommitteeOption(value, label) {
            const input = document.getElementById('committee-filter');
            const labelEl = document.getElementById('committee-dropdown-label');
            const menu = document.getElementById('committee-dropdown-menu');
            const arrow = document.getElementById('committee-dropdown-arrow');

            if (input && labelEl && menu) {
                input.value = value;
                labelEl.innerText = label;

                // Highlight selected in dropdown menu
                const buttons = menu.querySelectorAll('button');
                buttons.forEach(btn => {
                    if (btn.innerText.trim() === label) {
                        btn.className =
                            "w-full text-left px-4 py-2 text-sm bg-purple-50 dark:bg-purple-950/40 text-purple-700 dark:text-purple-400 font-semibold hover:bg-purple-50/80 dark:hover:bg-purple-950/60 transition duration-150";
                    } else {
                        btn.className =
                            "w-full text-left px-4 py-2 text-sm text-slate-600 dark:text-slate-300 hover:bg-purple-50/60 dark:hover:bg-purple-950/20 hover:text-purple-700 dark:hover:text-purple-400 transition duration-150";
                    }
                });

                menu.classList.add('hidden');
                if (arrow) arrow.classList.remove('rotate-180');

                // Submit the form to reload workspace with selected committee
                document.getElementById('committee-select-form').submit();
            }
        }

        // Copy event shareable link to clipboard
        function copyEventLink(url) {
            navigator.clipboard.writeText(url).then(() => {
                if (window.showToast) {
                    window.showToast("Shareable event link copied to clipboard!", "success");
                } else {
                    alert("Shareable link copied to clipboard!");
                }
            }).catch(err => {
                alert("Failed to copy link: " + err);
            });
        }

        // Leaflet-Google Hybrid Global Instances
        let map;
        let marker;
        let debounceTimeout;

        function initHybridMap() {
            if (map) {
                setTimeout(() => {
                    map.invalidateSize();
                }, 100);
                return;
            }

            const defaultCoords = [14.5995, 120.9842]; // Manila Centered

            // Initialize Leaflet map
            map = L.map('google-map').setView(defaultCoords, 13);

            // POINT TO GOOGLE MAPS PUBLIC ROAD TILES SERVER (100% Free, No Keys!)
            L.tileLayer('https://mt1.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
                maxZoom: 21,
                attribution: '© Google Maps'
            }).addTo(map);

            // Interactive Pin Marker
            marker = L.marker(defaultCoords, { draggable: true }).addTo(map);

            // Marker Drag reverse geocoding
            marker.on('dragend', function() {
                const pos = marker.getLatLng();
                reverseGeocode(pos.lat, pos.lng);
            });

            // Map click geocoding
            map.on('click', function(e) {
                marker.setLatLng(e.latlng);
                reverseGeocode(e.latlng.lat, e.latlng.lng);
            });
        }

        // Live Free autocomplete Nominatim searches
        function autocompleteLocation(query) {
            clearTimeout(debounceTimeout);
            const suggestionsContainer = document.getElementById('map-search-suggestions');
            if (!query || query.trim().length < 3) {
                suggestionsContainer.innerHTML = '';
                suggestionsContainer.classList.add('hidden');
                return;
            }

            debounceTimeout = setTimeout(() => {
                fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=10&countrycodes=ph`)
                    .then(res => res.json())
                    .then(data => {
                        suggestionsContainer.innerHTML = '';
                        if (data && data.length > 0) {
                            suggestionsContainer.classList.remove('hidden');
                            data.forEach(item => {
                                const btn = document.createElement('button');
                                btn.type = 'button';
                                btn.className = 'w-full text-left px-4 py-3 text-xs text-slate-700 dark:text-slate-200 hover:bg-purple-50 dark:hover:bg-purple-950/20 transition-all duration-150 flex items-start gap-2.5';
                                
                                const addressParts = item.display_name.split(',');
                                const title = addressParts[0] || '';
                                const subtitle = addressParts.slice(1).join(',').trim();

                                btn.innerHTML = `
                                    <span class="text-purple-500 mt-0.5 select-none">📍</span>
                                    <div class="truncate flex-grow">
                                        <div class="font-bold text-slate-800 dark:text-slate-100 truncate">${title}</div>
                                        ${subtitle ? `<div class="text-[10px] text-slate-400 dark:text-slate-500 truncate mt-0.5">${subtitle}</div>` : ''}
                                    </div>
                                `;
                                btn.onclick = () => {
                                    selectAddress(item.display_name, parseFloat(item.lat), parseFloat(item.lon));
                                };
                                suggestionsContainer.appendChild(btn);
                            });
                        } else {
                            suggestionsContainer.classList.add('hidden');
                        }
                    })
                    .catch(err => console.error(err));
            }, 400);
        }

        function selectAddress(address, lat, lon) {
            document.getElementById('event_location').value = address;
            document.getElementById('map-search-suggestions').innerHTML = '';
            document.getElementById('map-search-suggestions').classList.add('hidden');

            if (map) {
                const latlng = [lat, lon];
                map.setView(latlng, 16);
                marker.setLatLng(latlng);
            }
        }

        function reverseGeocode(lat, lon) {
            fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}`)
                .then(res => res.json())
                .then(data => {
                    if (data && data.display_name) {
                        document.getElementById('event_location').value = data.display_name;
                    }
                })
                .catch(err => console.error(err));
        }

        // Toggle Physical vs Virtual
        function toggleLocationType(type) {
            const physicalWrapper = document.getElementById('physical-location-wrapper');
            const virtualWrapper = document.getElementById('virtual-location-wrapper');
            const inputLocation = document.getElementById('event_location');
            const inputMeetingLink = document.getElementById('event_meeting_link');

            const labelPhysical = document.getElementById('label_location_type_physical');
            const labelVirtual = document.getElementById('label_location_type_virtual');

            if (type === 'physical') {
                physicalWrapper.classList.remove('hidden');
                virtualWrapper.classList.add('hidden');
                inputLocation.setAttribute('required', 'required');
                inputMeetingLink.removeAttribute('required');

                // Style active state
                labelPhysical.className = "flex items-center justify-center gap-2 p-3.5 bg-slate-50 dark:bg-slate-950 border-2 border-purple-500 dark:border-purple-500 rounded-2xl cursor-pointer transition-all duration-200 select-none text-slate-800 dark:text-slate-200 font-bold text-xs";
                labelVirtual.className = "flex items-center justify-center gap-2 p-3.5 bg-slate-50 dark:bg-slate-950 border-2 border-transparent hover:border-slate-200 dark:hover:border-slate-800 cursor-pointer transition-all duration-200 select-none text-slate-550 dark:text-slate-400 font-semibold text-xs";

                setTimeout(() => {
                    if (map && marker) {
                        map.invalidateSize();
                        map.setView(marker.getLatLng(), map.getZoom());
                    }
                }, 100);
            } else {
                physicalWrapper.classList.add('hidden');
                virtualWrapper.classList.remove('hidden');
                inputLocation.removeAttribute('required');
                inputMeetingLink.setAttribute('required', 'required');

                // Style active state
                labelPhysical.className = "flex items-center justify-center gap-2 p-3.5 bg-slate-50 dark:bg-slate-950 border-2 border-transparent hover:border-slate-200 dark:hover:border-slate-800 cursor-pointer transition-all duration-200 select-none text-slate-550 dark:text-slate-400 font-semibold text-xs";
                labelVirtual.className = "flex items-center justify-center gap-2 p-3.5 bg-slate-50 dark:bg-slate-950 border-2 border-purple-500 dark:border-purple-500 rounded-2xl cursor-pointer transition-all duration-200 select-none text-slate-800 dark:text-slate-200 font-bold text-xs";
            }
        }

        // Toggle Arrival / Entry instructions text block
        function toggleArrivalInstructions() {
            const wrapper = document.getElementById('arrival-instructions-wrapper');
            wrapper.classList.toggle('hidden');
        }

        // Intercept form submission to copy meeting link to location input when virtual is selected
        document.addEventListener('DOMContentLoaded', () => {
            const addEventForm = document.querySelector('#add-event-modal form');
            if (addEventForm) {
                addEventForm.addEventListener('submit', function(e) {
                    const locType = document.querySelector('input[name="location_type"]:checked').value;
                    if (locType === 'virtual') {
                        const meetingLink = document.getElementById('event_meeting_link').value;
                        document.getElementById('event_location').value = meetingLink;
                    }
                });
            }

            // Initialize Premium Custom Flatpickr Datetime Pickers
            const flatpickrConfig = {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                altInput: true,
                altFormat: "F j, Y • h:i K",
                minDate: "today",
                altInputClass: "w-full rounded-xl border border-slate-200 dark:border-slate-800 py-2.5 pl-10 pr-4 text-slate-700 dark:text-slate-200 text-xs focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-slate-50 dark:bg-slate-950 focus:bg-white dark:focus:bg-slate-950 transition-all duration-300 cursor-pointer shadow-sm hover:scale-[1.002]",
                locale: {
                    firstDayOfWeek: 1
                }
            };

            flatpickr("#event_date", flatpickrConfig);
            flatpickr("#registration_deadline", flatpickrConfig);
        });

        // Modal Control: Smooth animation transitions
        function openAddEventModal() {
            const modal = document.getElementById('add-event-modal');
            const content = document.getElementById('add-event-modal-content');
            if (!modal || !content) return;

            modal.classList.remove('hidden');
            modal.classList.add('flex');

            setTimeout(() => {
                modal.classList.remove('opacity-0');
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
                
                // Load/draw interactive Leaflet-Google Hybrid Map
                initHybridMap();
            }, 10);
        }

        // Close Add Event Modal
        function closeAddEventModal() {
            const modal = document.getElementById('add-event-modal');
            const content = document.getElementById('add-event-modal-content');
            if (!modal || !content) return;

            modal.classList.add('opacity-0');
            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-95', 'opacity-0');

            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }, 200);
        }

        // Real-Time Client-Side Filtering (Search, Month, and Year)
        function filterEvents() {
            const query = document.getElementById('search-input').value.toLowerCase();
            const month = document.getElementById('month-filter').value;
            const year = document.getElementById('year-filter') ? document.getElementById('year-filter').value : '';
            const cards = document.querySelectorAll('.event-card');
            const listContainer = document.getElementById('events-list-container');
            const dynamicEmpty = document.getElementById('dynamic-empty-state');

            let visibleCount = 0;

            cards.forEach(card => {
                const title = card.getAttribute('data-title') || '';
                const desc = card.getAttribute('data-description') || '';
                const cardMonth = card.getAttribute('data-month') || '';
                const cardYear = card.getAttribute('data-year') || '';

                const matchesSearch = title.includes(query) || desc.includes(query);
                const matchesMonth = month === '' || cardMonth === month;
                const matchesYear = year === '' || cardYear === year;

                if (matchesSearch && matchesMonth && matchesYear) {
                    card.classList.remove('hidden');
                    visibleCount++;
                } else {
                    card.classList.add('hidden');
                }
            });

            // Update counts
            const visibleCountBadge = document.getElementById('visible-count');
            if (visibleCountBadge) {
                visibleCountBadge.innerText = visibleCount;
            }

            // Toggle list visibility vs empty state
            if (visibleCount === 0) {
                if (listContainer) listContainer.classList.add('hidden');
                if (dynamicEmpty) dynamicEmpty.classList.remove('hidden');
            } else {
                if (listContainer) listContainer.classList.remove('hidden');
                if (dynamicEmpty) dynamicEmpty.classList.add('hidden');
            }
        }

        // Close modals and dropdowns on background backdrop click
        window.addEventListener('click', function(event) {
            const modal = document.getElementById('add-event-modal');
            if (event.target === modal) {
                closeAddEventModal();
            }

            // Dropdown clicks handling
            const dropdowns = ['month', 'year', 'committee'];
            dropdowns.forEach(t => {
                const menu = document.getElementById(t + '-dropdown-menu');
                const btn = document.getElementById(t + '-dropdown-btn');
                const arrow = document.getElementById(t + '-dropdown-arrow');

                if (menu && !menu.classList.contains('hidden')) {
                    if (btn && !btn.contains(event.target) && !menu.contains(event.target)) {
                        menu.classList.add('hidden');
                        if (arrow) arrow.classList.remove('rotate-180');
                    }
                }
            });
        });
    </script>
@endsection
