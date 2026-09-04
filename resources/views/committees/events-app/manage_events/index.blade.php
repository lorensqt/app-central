@extends('layouts.app')

@section('title', $event->title . ' - Event Workspace')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    /* Custom scrollbar for table container & lists */
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
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <a href="{{ route('committees.events.index') }}?committee_id={{ $event->committee_id }}" class="hover:text-slate-900 dark:hover:text-white transition-colors">{{ $event->committee->name }} Workspace</a>
        <svg class="w-4 h-4 text-slate-300 dark:text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="text-slate-900 dark:text-slate-300 font-medium">Event Control Room</span>
    </div>

    <!-- Header / Cover Card (Rich Aesthetics) -->
    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 shadow-[0_8px_30px_rgba(15,23,42,0.04)] p-6 sm:p-8 relative overflow-hidden">
        <!-- Floating Ambient Glow background element -->
        <div class="absolute -right-16 -top-16 w-44 h-44 rounded-full bg-purple-500/5 blur-3xl pointer-events-none"></div>

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 pb-6 border-b border-slate-100 dark:border-slate-800/80">
            <div class="space-y-2 max-w-3xl">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="px-2.5 py-0.5 bg-purple-50 dark:bg-purple-950/30 text-purple-700 dark:text-purple-400 text-xs font-semibold rounded-md border border-purple-100 dark:border-purple-900/30">
                        {{ $event->committee->name }}
                    </span>
                    <span class="px-2.5 py-0.5 bg-emerald-50 dark:bg-emerald-950/30 text-emerald-700 dark:text-emerald-400 text-xs font-semibold rounded-md border border-emerald-100 dark:border-emerald-900/30 flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Active RSVP
                    </span>
                </div>
                <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight leading-none">{{ $event->title }}</h1>
                <div class="text-slate-550 dark:text-slate-400 text-sm leading-relaxed whitespace-pre-line max-h-32 overflow-y-auto custom-scrollbar pr-2">{{ $event->description }}</div>
            </div>

            <!-- Header Quick Actions -->
            <div class="flex flex-wrap gap-2.5 w-full md:w-auto shrink-0">
                <!-- Generate Poster Button -->
                @if($event->registration_type === 'venue_confirmation')
                    <button onclick="openCheckInPosterModal()" class="w-full sm:w-auto text-xs font-semibold text-emerald-600 dark:text-emerald-400 hover:text-white hover:bg-emerald-600 bg-emerald-50 dark:bg-emerald-950/20 px-4 py-2.5 rounded-xl border border-emerald-200 dark:border-emerald-900/30 transition duration-150 inline-flex items-center justify-center gap-2 shadow-sm focus:outline-none">
                        <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m-3 3h6M5 12h14M3 7h6v6H3V7zm0 10h6v6H3v-6zm12 0h6v6h-6v-6zm0-10h6v6h-6V7z" />
                        </svg>
                        Get Check-In Poster
                    </button>
                @endif

                <!-- Edit Event Button -->
                <button onclick="openEditEventModal()" class="w-full sm:w-auto text-xs font-semibold text-purple-600 dark:text-purple-400 hover:text-white hover:bg-purple-600 bg-purple-50 dark:bg-purple-950/20 px-4 py-2.5 rounded-xl border border-purple-200 dark:border-purple-900/30 transition duration-150 inline-flex items-center justify-center gap-2 shadow-sm focus:outline-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit Event
                </button>

                <!-- Copy Public Link -->
                <button onclick="copyEventLink('{{ route('events.public_show', $event) }}')" class="w-full sm:w-auto text-xs font-semibold text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white bg-slate-50 dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 px-4 py-2.5 rounded-xl border border-slate-200/80 dark:border-slate-700 transition duration-150 inline-flex items-center justify-center gap-2 shadow-sm focus:outline-none">
                    <svg class="w-4 h-4 text-slate-500 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                    </svg>
                    Copy Event Link
                </button>

                <!-- View Public Page -->
                <a href="{{ route('events.public_show', $event) }}" target="_blank" rel="noopener noreferrer" class="w-full sm:w-auto text-center inline-flex items-center justify-center text-xs font-semibold py-2.5 px-4 rounded-xl bg-slate-900 dark:bg-slate-800 text-white dark:text-slate-200 hover:bg-slate-800 dark:hover:bg-slate-700 border border-transparent dark:border-slate-700 transition duration-150 shadow-sm">
                    View Event Landing Page
                </a>

                <!-- Delete Event Form -->
                <form action="{{ route('committees.events.destroy', $event) }}" method="POST" data-confirm="Are you sure you want to delete this event?" data-confirm-sub="All guest registrations for this assembly will be permanently deleted." data-confirm-title="Delete Scheduled Assembly" class="w-full sm:w-auto inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full sm:w-auto text-xs font-semibold text-red-600 dark:text-red-400 hover:text-white hover:bg-red-600 bg-red-50 dark:bg-red-950/20 hover:border-transparent px-4 py-2.5 rounded-xl border border-red-200 dark:border-red-900/30 transition duration-150 inline-flex items-center justify-center shadow-sm focus:outline-none">
                        Delete Event
                    </button>
                </form>
            </div>
        </div>

        <!-- Event Details Mini-Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 pt-6 text-sm">
            <div class="flex items-center gap-3 text-slate-600 dark:text-slate-400 bg-slate-50/50 dark:bg-slate-950/40 p-3.5 rounded-2xl border border-slate-100 dark:border-slate-800/60">
                <span class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-purple-50 dark:bg-purple-950/30 text-purple-600 dark:text-purple-400 font-bold shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </span>
                <div class="truncate">
                    <p class="text-[10px] uppercase font-bold text-slate-400 dark:text-slate-500 tracking-wider leading-none mb-1">Actual Event Date</p>
                    <p class="font-semibold text-slate-850 dark:text-slate-300 truncate" title="{{ $event->event_date->format('l, F j, Y • g:i A') }}">{{ $event->event_date->format('l, M j • g:i A') }}</p>
                </div>
            </div>

            <div class="flex items-center gap-3 text-slate-600 dark:text-slate-400 bg-slate-50/50 dark:bg-slate-950/40 p-3.5 rounded-2xl border border-slate-100 dark:border-slate-800/60">
                <span class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-purple-50 dark:bg-purple-950/30 text-purple-600 dark:text-purple-400 font-bold shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </span>
                <div class="truncate">
                    <p class="text-[10px] uppercase font-bold text-slate-400 dark:text-slate-500 tracking-wider leading-none mb-1">Venue Location</p>
                    <p class="font-semibold text-slate-850 dark:text-slate-300 truncate" title="{{ $event->location }}">{{ $event->location }}</p>
                </div>
            </div>

            <div class="flex items-center gap-3 text-slate-600 dark:text-slate-400 bg-slate-50/50 dark:bg-slate-950/40 p-3.5 rounded-2xl border border-slate-100 dark:border-slate-800/60 sm:col-span-2 lg:col-span-1">
                <span class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-purple-50 dark:bg-purple-950/30 text-purple-600 dark:text-purple-400 font-bold shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </span>
                <div class="truncate">
                    <p class="text-[10px] uppercase font-bold text-slate-400 dark:text-slate-500 tracking-wider leading-none mb-1">Total Registrants</p>
                    <p class="font-semibold text-slate-850 dark:text-slate-300 truncate"><span class="text-slate-900 dark:text-white font-bold">{{ $event->registrations->count() }}</span> applications received</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Feedback messages -->
    @if ($errors->any())
        <div class="p-4 rounded-xl bg-rose-50 dark:bg-rose-950/20 border border-rose-100 dark:border-rose-900/40 text-rose-800 dark:text-rose-400 text-sm space-y-1 shadow-sm">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <span class="font-bold">Please correct the following errors:</span>
            </div>
            <ul class="list-disc list-inside pl-8 text-xs space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('status'))
        <div class="p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-100 dark:border-emerald-900/40 text-emerald-800 dark:text-emerald-400 text-sm flex items-center gap-3 shadow-sm">
            <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>{{ session('status') }}</span>
        </div>
    @endif

    <!-- Tabbed Layout Container -->
    <div class="space-y-6">
        <!-- Tabs Navigation Row -->
        <div class="flex border-b border-slate-200 dark:border-slate-800 gap-1.5 scrollbar-thin overflow-x-auto select-none">
            <!-- Tab 1 Button: Requests -->
            <button onclick="switchTab('requests')" id="tab-btn-requests" class="tab-btn px-5 py-3 border-b-2 border-purple-600 text-purple-600 dark:text-purple-400 font-bold text-sm transition duration-150 flex items-center gap-2 focus:outline-none whitespace-nowrap">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span>Registration Requests</span>
                <span id="requests-badge-count" class="px-2 py-0.5 bg-purple-100 dark:bg-purple-950 text-purple-700 dark:text-purple-400 text-[10px] font-bold rounded-full">
                    {{ $event->registrations->where('status', 'pending')->count() }}
                </span>
            </button>

            <!-- Tab 2 Button: Summary / Report -->
            <button onclick="switchTab('summary')" id="tab-btn-summary" class="tab-btn px-5 py-3 border-b-2 border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 hover:border-slate-300 dark:hover:border-slate-700 font-semibold text-sm transition duration-150 flex items-center gap-2 focus:outline-none whitespace-nowrap">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                Summary & Analytics
            </button>

            <!-- Tab 3 Button: Manage Questions -->
            <button onclick="switchTab('questions')" id="tab-btn-questions" class="tab-btn px-5 py-3 border-b-2 border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 hover:border-slate-300 dark:hover:border-slate-700 font-semibold text-sm transition duration-150 flex items-center gap-2 focus:outline-none whitespace-nowrap">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                </svg>
                Manage Questions
            </button>
        </div>

        @include('committees.events-app.manage_events.requests')

        @include('committees.events-app.manage_events.summary')

        @include('committees.events-app.manage_events.questions')
    </div>

    <!-- Floating Bulk Actions Bar -->
    <div id="bulk-actions-bar" class="fixed bottom-6 left-1/2 transform -translate-x-1/2 bg-slate-900/95 dark:bg-slate-950/95 text-white py-3.5 px-6 rounded-2xl shadow-2xl border border-slate-800 dark:border-slate-850 z-50 hidden items-center gap-6 transition-all duration-300 backdrop-blur-md">
        <span class="text-xs font-bold uppercase tracking-wider text-slate-300">
            <span id="selected-count" class="text-purple-400 font-extrabold mr-1">0</span> selected
        </span>
        <div class="h-4 w-px bg-slate-800 shrink-0"></div>
        <div class="flex items-center gap-2">
            <button onclick="executeBulkAction('approve')" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-xs font-bold rounded-xl transition duration-150 shadow-md">
                Approve Selected
            </button>
            <button onclick="executeBulkAction('decline')" class="px-4 py-2 bg-red-650 hover:bg-red-750 text-xs font-bold rounded-xl transition duration-150 shadow-md">
                Decline Selected
            </button>
            <button onclick="if(confirm('Are you sure you want to permanently delete all selected registrations?')){ executeBulkAction('delete') }" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-xs font-bold rounded-xl transition duration-150 shadow-md">
                Delete Selected
            </button>
        </div>
        <button onclick="clearSelection()" class="text-slate-400 hover:text-white p-1 hover:bg-slate-800 rounded-lg transition duration-150">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
</div>

@include('committees.events-app.events-components.edit_event_modal')
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
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

    // Interactive Tab Switching Logic
    let currentTab = 'requests';
    function switchTab(targetTab) {
        const tabRequestsBtn = document.getElementById('tab-btn-requests');
        const tabSummaryBtn = document.getElementById('tab-btn-summary');
        const tabQuestionsBtn = document.getElementById('tab-btn-questions');
        
        const panelRequests = document.getElementById('tab-panel-requests');
        const panelSummary = document.getElementById('tab-panel-summary');
        const panelQuestions = document.getElementById('tab-panel-questions');

        if (!tabRequestsBtn || !tabSummaryBtn || !tabQuestionsBtn || !panelRequests || !panelSummary || !panelQuestions) return;

        // Reset Styles
        const btns = [tabRequestsBtn, tabSummaryBtn, tabQuestionsBtn];
        btns.forEach(btn => {
            btn.className = "tab-btn px-5 py-3 border-b-2 border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 hover:border-slate-300 dark:hover:border-slate-700 font-semibold text-sm transition duration-150 flex items-center gap-2 focus:outline-none whitespace-nowrap";
        });
        
        panelRequests.classList.add('hidden');
        panelSummary.classList.add('hidden');
        panelQuestions.classList.add('hidden');

        // Apply active Styles
        if (targetTab === 'requests') {
            tabRequestsBtn.className = "tab-btn px-5 py-3 border-b-2 border-purple-600 dark:border-purple-400 text-purple-600 dark:text-purple-400 font-bold text-sm transition duration-150 flex items-center gap-2 focus:outline-none whitespace-nowrap";
            panelRequests.classList.remove('hidden');
        } else if (targetTab === 'summary') {
            tabSummaryBtn.className = "tab-btn px-5 py-3 border-b-2 border-purple-600 dark:border-purple-400 text-purple-600 dark:text-purple-400 font-bold text-sm transition duration-150 flex items-center gap-2 focus:outline-none whitespace-nowrap";
            panelSummary.classList.remove('hidden');
        } else if (targetTab === 'questions') {
            tabQuestionsBtn.className = "tab-btn px-5 py-3 border-b-2 border-purple-600 dark:border-purple-400 text-purple-600 dark:text-purple-400 font-bold text-sm transition duration-150 flex items-center gap-2 focus:outline-none whitespace-nowrap";
            panelQuestions.classList.remove('hidden');
        }

        currentTab = targetTab;
    }

    // Auto-switch to requested tab from query parameter on page load
    document.addEventListener('DOMContentLoaded', () => {
        const urlParams = new URLSearchParams(window.location.search);
        const tab = urlParams.get('tab');
        if (tab) {
            switchTab(tab);
        }

        // Initialize Premium Custom Flatpickr Datetime Pickers for edit modal
        const flatpickrConfig = {
            enableTime: true,
            dateFormat: "Y-m-d H:i",
            altInput: true,
            altFormat: "F j, Y • h:i K",
            minDate: "today",
            altInputClass: "w-full rounded-xl border border-slate-300/80 dark:border-slate-800 py-3 pl-10 pr-4 text-slate-700 dark:text-slate-200 text-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-slate-50/50 dark:bg-slate-950/50 focus:bg-white dark:focus:bg-slate-950 transition-all duration-300 cursor-pointer shadow-sm hover:scale-[1.002]",
            locale: {
                firstDayOfWeek: 1
            }
        };

        flatpickr("#edit_event_date", flatpickrConfig);
        flatpickr("#edit_registration_deadline", flatpickrConfig);
    });

    // Client-side filtering & searching inside registration applicants table
    let activeStatusFilter = 'all';

    function setStatusFilter(status) {
        activeStatusFilter = status;

        // Reset background & styles of all buttons
        const statuses = ['all', 'pending', 'approved', 'declined'];
        statuses.forEach(st => {
            const btn = document.getElementById('filter-btn-' + st);
            if (btn) {
                if (st === status) {
                    btn.className = "px-3.5 py-1.5 text-xs font-semibold rounded-lg bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200 shadow-sm transition duration-150";
                } else {
                    btn.className = "px-3.5 py-1.5 text-xs font-semibold rounded-lg text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 hover:bg-white/40 dark:hover:bg-slate-800/40 transition duration-150";
                }
            }
        });

        // Run matching list update
        filterApplicants();
    }

    function filterApplicants() {
        const query = document.getElementById('applicant-search') ? document.getElementById('applicant-search').value.toLowerCase() : '';
        const rows = document.querySelectorAll('.applicant-row');
        const noResults = document.getElementById('no-applicants-matched');

        let matchesCount = 0;

        rows.forEach(row => {
            const name = row.getAttribute('data-name') || '';
            const email = row.getAttribute('data-email') || '';
            const code = row.getAttribute('data-code') || '';
            const status = row.getAttribute('data-status') || '';

            const matchesSearch = name.includes(query) || email.includes(query) || code.includes(query);
            const matchesStatus = activeStatusFilter === 'all' || status === activeStatusFilter;

            if (matchesSearch && matchesStatus) {
                row.classList.remove('hidden');
                matchesCount++;
            } else {
                row.classList.add('hidden');
            }
        });

        if (noResults) {
            if (matchesCount === 0 && rows.length > 0) {
                noResults.classList.remove('hidden');
            } else {
                noResults.classList.add('hidden');
            }
        }
    }

    // Modal Control: Edit Event Details
    function openEditEventModal() {
        const modal = document.getElementById('edit-event-modal');
        const content = document.getElementById('edit-event-modal-content');
        if (!modal || !content) return;

        modal.classList.remove('hidden');
        modal.classList.add('flex');

        setTimeout(() => {
            modal.classList.remove('opacity-0');
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeEditEventModal() {
        const modal = document.getElementById('edit-event-modal');
        const content = document.getElementById('edit-event-modal-content');
        if (!modal || !content) return;

        modal.classList.add('opacity-0');
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');

        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 200);
    }

    function handleEditCapacityChange(selectEl) {
        const val = selectEl.value;
        const wrapper = document.getElementById('edit-capacity-input-wrapper');
        const input = document.getElementById('edit_max_participants');

        if (val === 'limited') {
            wrapper.classList.remove('hidden');
            input.setAttribute('required', 'required');
        } else {
            wrapper.classList.add('hidden');
            input.removeAttribute('required');
            input.value = '';
        }
    }

    function toggleEditArrivalInstructions() {
        const wrapper = document.getElementById('edit-arrival-instructions-wrapper');
        if (!wrapper) return;
        
        if (wrapper.classList.contains('hidden')) {
            wrapper.classList.remove('hidden');
            document.getElementById('edit_arrival_instructions').focus();
        } else {
            wrapper.classList.add('hidden');
            document.getElementById('edit_arrival_instructions').value = '';
        }
    }

    // Close modals on background backdrop click
    window.addEventListener('click', function(event) {
        const modal = document.getElementById('edit-event-modal');
        if (event.target === modal) {
            closeEditEventModal();
        }
    });

    // Checkbox selections, live smooth single approvals/declines & bulk action execution
    document.addEventListener('DOMContentLoaded', () => {
        const selectAllCheckbox = document.getElementById('select-all-checkbox');
        const applicantCheckboxes = document.querySelectorAll('.applicant-checkbox');
        const bulkBar = document.getElementById('bulk-actions-bar');
        const selectedCountSpan = document.getElementById('selected-count');

        // Helper to update floating action bar state
        function updateBulkBarState() {
            const checkedCount = document.querySelectorAll('.applicant-checkbox:checked').length;
            if (checkedCount > 0) {
                selectedCountSpan.innerText = checkedCount;
                bulkBar.classList.remove('hidden');
                bulkBar.classList.add('flex');
            } else {
                bulkBar.classList.remove('flex');
                bulkBar.classList.add('hidden');
            }
        }

        // Individual checkbox change (forcing manual selection)
        applicantCheckboxes.forEach(cb => {
            cb.addEventListener('change', () => {
                updateBulkBarState();
            });
        });

        // Intercept inline single moderation and attendance forms to run them via AJAX smoothly
        document.addEventListener('submit', function(e) {
            const form = e.target;
            const isSingleApprove = form.action.includes('/committees/registrations/') && form.action.includes('/approve');
            const isSingleDecline = form.action.includes('/committees/registrations/') && form.action.includes('/decline');
            const isToggleAttendance = form.action.includes('/committees/registrations/') && form.action.includes('/toggle-attendance');

            if (isSingleApprove || isSingleDecline) {
                e.preventDefault();
                
                const row = form.closest('.applicant-row');
                const action = isSingleApprove ? 'approved' : 'declined';
                const submitBtn = form.querySelector('button');
                if (!submitBtn || submitBtn.disabled) return;

                const originalHTML = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerText = 'Syncing...';

                fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        animateRowStatusUpdate(row, action);
                        window.showToast(data.message, 'success');
                    } else {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalHTML;
                        window.showToast(data.message || 'Action failed.', 'error');
                    }
                })
                .catch(err => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalHTML;
                    window.showToast('Server connection failed.', 'error');
                });
            } else if (isToggleAttendance) {
                e.preventDefault();

                const row = form.closest('.applicant-row');
                const submitBtn = form.querySelector('button');
                if (!submitBtn || submitBtn.disabled) return;

                const originalHTML = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerText = 'Updating...';

                fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    submitBtn.disabled = false;
                    if (data.success) {
                        // Dynamically update toggle button layout
                        if (data.attended) {
                            submitBtn.className = "text-xs font-semibold text-amber-600 dark:text-amber-400 border border-amber-200 dark:border-amber-900/50 hover:bg-amber-500 dark:hover:bg-amber-550 hover:text-white hover:border-transparent px-3 py-1.5 rounded-xl transition duration-150";
                            submitBtn.innerText = 'Mark Absent';
                        } else {
                            submitBtn.className = "text-xs font-semibold text-purple-600 dark:text-purple-400 border border-purple-200 dark:border-purple-800/80 hover:bg-purple-500 dark:hover:bg-purple-600 hover:text-white hover:border-transparent px-3 py-1.5 rounded-xl transition duration-150";
                            submitBtn.innerText = 'Mark Attended';
                        }

                        // Update attendance badge
                        const statusCell = row.children[6]; // Shifted index because of Ticket Code column addition (was 5, now 6!)
                        const badgeWrapper = statusCell.querySelector('.attendance-badge-wrapper');
                        if (badgeWrapper) {
                            badgeWrapper.style.opacity = '0';
                            setTimeout(() => {
                                if (data.attended) {
                                    badgeWrapper.innerHTML = `
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 bg-emerald-100 dark:bg-emerald-950/50 text-emerald-800 dark:text-emerald-300 border border-emerald-200/50 dark:border-emerald-900/30 text-[10px] font-bold rounded-md uppercase tracking-wider">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Attended
                                        </span>
                                    `;
                                } else {
                                    badgeWrapper.innerHTML = `
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 bg-slate-100 dark:bg-slate-950/40 text-slate-600 dark:text-slate-400 border border-slate-200/40 dark:border-slate-800/60 text-[10px] font-bold rounded-md uppercase tracking-wider">
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-450"></span>
                                            Absent
                                        </span>
                                    `;
                                }
                                badgeWrapper.style.transition = 'opacity 200ms ease';
                                badgeWrapper.style.opacity = '1';
                            }, 100);
                        }

                        window.showToast(data.message, 'success');
                    } else {
                        submitBtn.innerHTML = originalHTML;
                        window.showToast(data.message || 'Action failed.', 'error');
                    }
                })
                .catch(err => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalHTML;
                    window.showToast('Server connection failed.', 'error');
                });
            } else if (form.classList.contains('delete-registration-form')) {
                e.preventDefault();

                const name = form.getAttribute('data-name') || 'this registrant';
                if (!confirm(`Are you sure you want to permanently delete ${name}? All reservation data will be lost.`)) {
                    return;
                }

                const row = form.closest('.applicant-row');
                const submitBtn = form.querySelector('button');
                if (!submitBtn || submitBtn.disabled) return;

                submitBtn.disabled = true;

                fetch(form.action, {
                    method: 'POST', // Rails/Laravel DELETE emulation via _method field in headers or form data
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-HTTP-Method-Override': 'DELETE'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        row.style.transition = 'all 300ms ease';
                        row.style.opacity = '0';
                        row.style.transform = 'translateX(20px)';
                        setTimeout(() => {
                            row.remove();
                            recalculateAnalytics();
                            window.showToast(data.message, 'success');
                        }, 300);
                    } else {
                        submitBtn.disabled = false;
                        window.showToast(data.message || 'Action failed.', 'error');
                    }
                })
                .catch(err => {
                    submitBtn.disabled = false;
                    window.showToast('Server connection failed.', 'error');
                });
            }
        });

        // Clear selections handler
        window.clearSelection = function() {
            applicantCheckboxes.forEach(cb => {
                cb.checked = false;
            });
            updateBulkBarState();
        };

        // Smooth status badge and button update with fade-in effect
        function animateRowStatusUpdate(row, action) {
            row.setAttribute('data-status', action);
            
            // Checkbox disable/uncheck
            const cb = row.querySelector('.applicant-checkbox');
            if (cb) {
                cb.checked = false;
                cb.disabled = true;
                cb.classList.add('opacity-50', 'cursor-not-allowed');
            }

            // Update Status Badge TD (Index 6 since Ticket Code was added)
            const statusCell = row.children[6];
            statusCell.style.opacity = '0';
            setTimeout(() => {
                if (action === 'approved') {
                    statusCell.innerHTML = `
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 bg-emerald-50 dark:bg-emerald-950/30 text-emerald-700 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-900/30 text-[10px] font-bold rounded-md uppercase tracking-wider">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            Approved
                        </span>
                        <div class="attendance-badge-wrapper mt-1">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 bg-slate-100 dark:bg-slate-950/40 text-slate-600 dark:text-slate-400 border border-slate-200/40 dark:border-slate-800/60 text-[10px] font-bold rounded-md uppercase tracking-wider">
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-455"></span>
                                Absent
                            </span>
                        </div>
                    `;
                } else {
                    statusCell.innerHTML = `
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 bg-red-50 dark:bg-red-950/30 text-red-700 dark:text-red-400 border border-red-100/30 dark:border-red-900/30 text-[10px] font-bold rounded-md uppercase tracking-wider">
                            <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                            Declined
                        </span>
                    `;
                }
                statusCell.style.transition = 'opacity 300ms ease';
                statusCell.style.opacity = '1';
            }, 150);

            // Update Actions Column TD (Index 7 since Ticket Code was added)
            const actionsCell = row.children[7];
            actionsCell.style.opacity = '0';
            setTimeout(() => {
                if (action === 'approved') {
                    const regId = row.getAttribute('data-id');
                    const toggleUrl = `{{ url('/committees/registrations') }}/${regId}/toggle-attendance`;
                    const destroyUrl = `{{ url('/committees/registrations') }}/${regId}`;
                    actionsCell.innerHTML = `
                        <form action="${toggleUrl}" method="POST" class="inline toggle-attendance-form">
                            @csrf
                            <button type="submit" class="text-xs font-semibold text-purple-600 dark:text-purple-400 border border-purple-200 dark:border-purple-800/80 hover:bg-purple-500 dark:hover:bg-purple-600 hover:text-white hover:border-transparent px-3 py-1.5 rounded-xl transition duration-150">
                                Mark Attended
                            </button>
                        </form>
                        <form action="${destroyUrl}" method="POST" class="inline delete-registration-form" data-name="${row.getAttribute('data-name')}">
                            @csrf
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="text-xs font-semibold text-red-600 dark:text-red-400 hover:text-white hover:bg-red-650 border border-red-200 dark:border-red-900/40 hover:border-transparent p-1.5 rounded-xl transition duration-150" title="Delete Registrant">
                                <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </form>
                    `;
                } else {
                    const regId = row.getAttribute('data-id');
                    const destroyUrl = `{{ url('/committees/registrations') }}/${regId}`;
                    actionsCell.innerHTML = `
                        <form action="${destroyUrl}" method="POST" class="inline delete-registration-form" data-name="${row.getAttribute('data-name')}">
                            @csrf
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="text-xs font-semibold text-red-650 dark:text-red-400 hover:text-white hover:bg-red-605 border border-red-200 dark:border-red-900/40 hover:border-transparent p-1.5 rounded-xl transition duration-150" title="Delete Registrant">
                                <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </form>
                    `;
                }
                actionsCell.style.transition = 'opacity 300ms ease';
                actionsCell.style.opacity = '1';
            }, 150);

            // Dynamically recalculate and update summary analytics cards & badges
            recalculateAnalytics();
            updateBulkBarState();
        }

        // Dynamic analytics counter update
        function recalculateAnalytics() {
            const rows = document.querySelectorAll('.applicant-row');
            let approvedCount = 0;
            let pendingCount = 0;
            let declinedCount = 0;

            rows.forEach(r => {
                const status = r.getAttribute('data-status');
                if (status === 'approved') approvedCount++;
                else if (status === 'pending') pendingCount++;
                else if (status === 'declined') declinedCount++;
            });

            // Update headers
            const approvedHeader = document.getElementById('stats-approved-count');
            const pendingHeader = document.getElementById('stats-pending-count');
            const declinedHeader = document.getElementById('stats-declined-count');
            const requestsTabBadge = document.getElementById('requests-badge-count');

            if (approvedHeader) approvedHeader.innerText = approvedCount;
            if (pendingHeader) pendingHeader.innerText = pendingCount;
            if (declinedHeader) declinedHeader.innerText = declinedCount;
            if (requestsTabBadge) requestsTabBadge.innerText = pendingCount;
        }

        // Execute bulk approve, decline, or delete via POST AJAX to bulk endpoints
        window.executeBulkAction = function(action) {
            const checkedCheckboxes = document.querySelectorAll('.applicant-checkbox:checked');
            const ids = Array.from(checkedCheckboxes).map(cb => cb.getAttribute('data-id'));
            if (ids.length === 0) return;

            let url = '';
            if (action === 'approve') {
                url = '{{ route('committees.registrations.bulk_approve') }}';
            } else if (action === 'decline') {
                url = '{{ route('committees.registrations.bulk_decline') }}';
            } else if (action === 'delete') {
                url = '{{ route('committees.registrations.bulk_delete') }}';
            }

            // Show loading indicators inside the bulk actions bar
            const bulkButtons = bulkBar.querySelectorAll('button');
            bulkButtons.forEach(btn => btn.disabled = true);

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ ids: ids })
            })
            .then(res => res.json())
            .then(data => {
                bulkButtons.forEach(btn => btn.disabled = false);
                if (data.success) {
                    if (action === 'delete') {
                        checkedCheckboxes.forEach(cb => {
                            const row = cb.closest('.applicant-row');
                            row.style.transition = 'all 300ms ease';
                            row.style.opacity = '0';
                            row.style.transform = 'translateX(20px)';
                            setTimeout(() => {
                                row.remove();
                                recalculateAnalytics();
                            }, 300);
                        });
                    } else {
                        checkedCheckboxes.forEach(cb => {
                            const row = cb.closest('.applicant-row');
                            animateRowStatusUpdate(row, action === 'approve' ? 'approved' : 'declined');
                        });
                    }
                    clearSelection();
                    window.showToast(data.message, 'success');
                } else {
                    window.showToast(data.message || 'Bulk action failed.', 'error');
                }
            })
            .catch(err => {
                bulkButtons.forEach(btn => btn.disabled = false);
                window.showToast('Server connection failed.', 'error');
            });
        };
    });

    // Poster Modal Control
    let posterQrGenerated = false;

    function openCheckInPosterModal() {
        const modal = document.getElementById('check-in-poster-modal');
        const content = document.getElementById('check-in-poster-modal-content');
        if (!modal || !content) return;

        modal.classList.remove('hidden');
        modal.classList.add('flex');

        setTimeout(() => {
            modal.classList.remove('opacity-0');
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);

        // Generate QR code if not already done
        if (!posterQrGenerated) {
            const qrTarget = "{{ route('events.check_in', $event) }}";
            new QRCode(document.getElementById("poster-qrcode"), {
                text: qrTarget,
                width: 160,
                height: 160,
                colorDark : "#000000",
                colorLight : "#ffffff",
                correctLevel : QRCode.CorrectLevel.M
            });
            posterQrGenerated = true;
        }
    }

    function closeCheckInPosterModal() {
        const modal = document.getElementById('check-in-poster-modal');
        const content = document.getElementById('check-in-poster-modal-content');
        if (!modal || !content) return;

        modal.classList.add('opacity-0');
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');

        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 200);
    }

    function printPoster() {
        const printContent = document.getElementById('print-area').innerHTML;
        const originalContent = document.body.innerHTML;

        document.body.innerHTML = `
            <div style="font-family: 'Inter', sans-serif; display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100vh; text-align: center; color: black; background: white; padding: 40px; box-sizing: border-box;">
                \${printContent}
            </div>
        `;
        window.print();
        document.body.innerHTML = originalContent;
        window.location.reload(); // Reload to safely restore state
    }

    let questionIndex = parseInt(document.getElementById('custom-questions-container')?.dataset.count || 0);

    function reindexCustomQuestions() {
        const container = document.getElementById('custom-questions-container');
        if (!container) return;

        const rows = container.querySelectorAll('[id^="field-row-"]');
        rows.forEach((row, index) => {
            row.id = `field-row-${index}`;
            
            // Update hidden ID input name
            const idInput = row.querySelector('input[type="hidden"]');
            if (idInput) {
                idInput.name = `registration_fields[${index}][id]`;
            }

            // Update label input name
            const labelInput = row.querySelector('input[type="text"]');
            if (labelInput) {
                labelInput.name = `registration_fields[${index}][label]`;
            }

            // Update checkbox name
            const checkboxInput = row.querySelector('input[type="checkbox"]');
            if (checkboxInput) {
                checkboxInput.name = `registration_fields[${index}][required]`;
            }

            // Update remove button onclick
            const removeBtn = row.querySelector('button[onclick^="removeQuestionField"]');
            if (removeBtn) {
                removeBtn.setAttribute('onclick', `removeQuestionField('field-row-${index}')`);
            }
        });

        questionIndex = rows.length;
        container.dataset.count = rows.length;
    }

    function addQuestionField() {
        const container = document.getElementById('custom-questions-container');
        if (!container) return;

        const uniqueId = 'field_' + Date.now() + Math.random().toString(36).substr(2, 5);
        const index = questionIndex++;
        
        const row = document.createElement('div');
        row.className = "py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 dark:border-slate-800/60 animate-fade-in";
        row.id = `field-row-${index}`;
        
        row.innerHTML = `
            <div class="flex-grow flex items-center gap-3">
                <span class="text-xs font-bold text-slate-400">#</span>
                <input type="hidden" name="registration_fields[${index}][id]" value="${uniqueId}">
                <input type="text" name="registration_fields[${index}][label]" required
                    placeholder="e.g. Address"
                    class="flex-grow rounded-xl border border-slate-300 dark:border-slate-800 py-2.5 px-4 text-slate-700 dark:text-slate-200 text-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-white dark:bg-slate-950 focus:bg-white dark:focus:bg-slate-950 transition-all duration-300">
            </div>
            <div class="flex items-center gap-4 justify-between sm:justify-end shrink-0">
                <label class="inline-flex items-center gap-1.5 cursor-pointer">
                    <input type="checkbox" name="registration_fields[${index}][required]" value="1"
                        class="w-4 h-4 rounded border-slate-300 dark:border-slate-800 text-purple-650 bg-white dark:bg-slate-950 focus:ring-purple-500/20">
                    <span class="text-xs text-slate-550 dark:text-slate-400 font-semibold">Required?</span>
                </label>
                <button type="button" onclick="removeQuestionField('field-row-${index}')"
                    class="text-rose-600 dark:text-rose-400 hover:text-white hover:bg-rose-600 dark:hover:bg-rose-500 bg-rose-50 dark:bg-rose-950/30 p-2 rounded-xl border border-rose-100 dark:border-rose-900/30 transition duration-150 flex items-center justify-center shrink-0 shadow-sm"
                    title="Remove question">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            </div>
        `;
        
        container.appendChild(row);
        reindexCustomQuestions();
    }

    function removeQuestionField(id) {
        const element = document.getElementById(id);
        if (element) {
            element.remove();
            reindexCustomQuestions();
        }
    }

    // Dynamically render the custom questions list using data from the server
    function renderCustomQuestionsList(fields) {
        const container = document.getElementById('custom-questions-container');
        if (!container) return;

        container.innerHTML = '';
        
        fields.forEach((field, index) => {
            const row = document.createElement('div');
            row.className = "py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 dark:border-slate-800/60 animate-fade-in";
            row.id = `field-row-${index}`;
            
            const isChecked = field.required ? 'checked' : '';
            
            row.innerHTML = `
                <div class="flex-grow flex items-center gap-3">
                    <span class="text-xs font-bold text-slate-400">#</span>
                    <input type="hidden" name="registration_fields[${index}][id]" value="${field.id || ''}">
                    <input type="text" name="registration_fields[${index}][label]" value="${field.label || ''}" required
                        placeholder="e.g. Address"
                        class="flex-grow rounded-xl border border-slate-300 dark:border-slate-800 py-2.5 px-4 text-slate-700 dark:text-slate-200 text-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-white dark:bg-slate-950 focus:bg-white dark:focus:bg-slate-950 transition-all duration-300">
                </div>
                <div class="flex items-center gap-4 justify-between sm:justify-end shrink-0">
                    <label class="inline-flex items-center gap-1.5 cursor-pointer">
                        <input type="checkbox" name="registration_fields[${index}][required]" value="1" ${isChecked}
                            class="w-4 h-4 rounded border-slate-300 dark:border-slate-800 text-purple-650 bg-white dark:bg-slate-950 focus:ring-purple-500/20">
                        <span class="text-xs text-slate-550 dark:text-slate-400 font-semibold">Required?</span>
                    </label>
                    <button type="button" onclick="removeQuestionField('field-row-${index}')"
                        class="text-rose-600 dark:text-rose-400 hover:text-white hover:bg-rose-600 dark:hover:bg-rose-500 bg-rose-50 dark:bg-rose-950/30 p-2 rounded-xl border border-rose-100 dark:border-rose-900/30 transition duration-150 flex items-center justify-center shrink-0 shadow-sm"
                        title="Remove question">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </div>
            `;
            container.appendChild(row);
        });

        questionIndex = fields.length;
        container.dataset.count = fields.length;
    }

    // Intercept Custom Questions Form submission to make it AJAX/Live
    document.addEventListener('DOMContentLoaded', () => {
        const questionsForm = document.querySelector('#tab-panel-questions form');
        if (questionsForm) {
            questionsForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const submitBtn = questionsForm.querySelector('button[type="submit"]');
                if (!submitBtn || submitBtn.disabled) return;

                const originalHTML = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerText = 'Saving Configuration...';

                const formData = new FormData(questionsForm);

                fetch(questionsForm.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalHTML;
                    if (data.success) {
                        window.showToast(data.message, 'success');
                        
                        // Dynamically update the custom questions list in real-time without refreshing the page!
                        if (data.registration_fields) {
                            renderCustomQuestionsList(data.registration_fields);
                        }
                    } else {
                        window.showToast(data.message || 'Failed to save configuration.', 'error');
                    }
                })
                .catch(err => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalHTML;
                    window.showToast('Server connection failed.', 'error');
                });
            });
        }
    });
</script>

<!-- BACKDROP MODAL: VENUE CHECK-IN POSTER -->
<div id="check-in-poster-modal"
    class="fixed inset-0 bg-slate-900/60 dark:bg-slate-950/80 backdrop-blur-[4px] z-50 hidden items-center justify-center p-4 transition-all duration-300 opacity-0">
    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 max-w-md w-full shadow-2xl p-6 sm:p-8 flex flex-col transition-all duration-300 transform scale-95 opacity-0"
        id="check-in-poster-modal-content">
        
        <div class="flex justify-between items-center pb-4 border-b border-slate-100 dark:border-slate-800/60 shrink-0">
            <h3 class="font-bold text-slate-900 dark:text-white text-lg">Check-In Poster</h3>
            <button onclick="closeCheckInPosterModal()" class="text-slate-400 hover:text-slate-650 p-1.5 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-xl transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Print Preview Container -->
        <div id="print-area" class="p-6 bg-white text-slate-950 rounded-2xl border border-slate-100 mt-4 space-y-6 text-center">
            <div class="space-y-1">
                <span class="text-[9px] font-extrabold uppercase tracking-widest text-purple-650">App Central Venue Entry</span>
                <h2 class="text-xl font-extrabold tracking-tight leading-snug">{{ $event->title }}</h2>
                <p class="text-xs text-slate-550">{{ $event->location }}</p>
            </div>

            <div class="flex justify-center p-2 bg-white rounded-xl inline-block border border-slate-100">
                <div id="poster-qrcode" class="w-40 h-40 flex items-center justify-center"></div>
            </div>

            <div class="space-y-1">
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Scan QR to Self Check-In</p>
                <p class="text-[10px] text-slate-550 leading-relaxed max-w-xs mx-auto">Please scan this barcode with your smartphone, enter your registered email address, and verify your pass code to mark yourself attended.</p>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100 dark:border-slate-800 shrink-0 mt-6">
            <button type="button" onclick="closeCheckInPosterModal()" class="text-xs font-semibold py-2.5 px-4 rounded-xl border border-slate-200 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 transition">
                Close
            </button>
            <button type="button" onclick="printPoster()" class="text-xs font-semibold py-2.5 px-5 rounded-xl bg-purple-600 hover:bg-purple-700 text-white transition duration-150 shadow-sm">
                Print Poster
            </button>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
@endsection
