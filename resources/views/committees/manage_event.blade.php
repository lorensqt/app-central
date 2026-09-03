@extends('layouts.app')

@section('title', $event->title . ' - Event Workspace')

@section('content')
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
                <div class="text-slate-500 dark:text-slate-400 text-sm leading-relaxed whitespace-pre-line max-h-32 overflow-y-auto custom-scrollbar pr-2">{{ $event->description }}</div>
            </div>

            <!-- Header Quick Actions -->
            <div class="flex flex-wrap gap-2.5 w-full md:w-auto shrink-0">
                <!-- Generate Poster Button -->
                @if($event->registration_type === 'venue_confirmation')
                    <button onclick="openCheckInPosterModal()" class="w-full sm:w-auto text-xs font-semibold text-emerald-650 dark:text-emerald-400 hover:text-white hover:bg-emerald-600 bg-emerald-50 dark:bg-emerald-950/20 px-4 py-2.5 rounded-xl border border-emerald-250 dark:border-emerald-900/30 transition duration-150 inline-flex items-center justify-center gap-2 shadow-sm focus:outline-none">
                        <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m0 8v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m12.728 0l-.707-.707M6.343 6.343l-.707-.707M14 12a2 2 0 11-4 0 2 2 0 014 0z" />
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
                    <p class="text-[10px] uppercase font-bold text-slate-400 dark:text-slate-500 tracking-wider leading-none mb-1">Date & Time</p>
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

            <!-- Tab 3 Button: Inactive Placeholder -->
            <button class="px-5 py-3 border-b-2 border-transparent text-slate-300 dark:text-slate-700 font-semibold text-sm cursor-not-allowed flex items-center gap-2 whitespace-nowrap" disabled title="Settings modules will be added in a future revision.">
                <svg class="w-4 h-4 text-slate-300 dark:text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                </svg>
                Advanced Settings
                <span class="px-1.5 py-0.5 bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-500 text-[8px] font-bold rounded uppercase tracking-wider">UI Only</span>
            </button>
        </div>

        <!-- TAB 1: REGISTRATION REQUESTS PANEL -->
        <div id="tab-panel-requests" class="space-y-6">
            <!-- Filter Actions Strip -->
            <div class="flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4 bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800/80 p-4 shadow-sm">
                <!-- Left Side: Live Search Box -->
                <div class="relative flex-grow max-w-md bg-white dark:bg-slate-950 rounded-xl border border-slate-200/80 dark:border-slate-800/80 hover:border-slate-300 dark:hover:border-slate-700 shadow-[0_2px_8px_rgba(15,23,42,0.01)] transition duration-200">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </span>
                    <input type="text" id="applicant-search" onkeyup="filterApplicants()" placeholder="Search applicants by name or email..." class="w-full pl-10 pr-4 py-2.5 rounded-xl border-0 text-slate-600 dark:text-slate-200 text-sm focus:ring-0 focus:outline-none bg-transparent placeholder-slate-400">
                </div>

                <!-- Right Side: Filter Buttons for Quick Toggle (All, Pending, Approved, Declined) -->
                <div class="flex flex-wrap items-center gap-1.5 bg-slate-50 dark:bg-slate-950 p-1 rounded-xl border border-slate-100 dark:border-slate-800/80 shrink-0">
                    <button type="button" onclick="setStatusFilter('all')" id="filter-btn-all" class="px-3.5 py-1.5 text-xs font-semibold rounded-lg bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200 shadow-sm transition duration-150">
                        All ({{ $event->registrations->count() }})
                    </button>
                    <button type="button" onclick="setStatusFilter('pending')" id="filter-btn-pending" class="px-3.5 py-1.5 text-xs font-semibold rounded-lg text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 hover:bg-white/40 dark:hover:bg-slate-800/40 transition duration-150">
                        Pending ({{ $event->registrations->where('status', 'pending')->count() }})
                    </button>
                    <button type="button" onclick="setStatusFilter('approved')" id="filter-btn-approved" class="px-3.5 py-1.5 text-xs font-semibold rounded-lg text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 hover:bg-white/40 dark:hover:bg-slate-800/40 transition duration-150">
                        Approved ({{ $event->registrations->where('status', 'approved')->count() }})
                    </button>
                    <button type="button" onclick="setStatusFilter('declined')" id="filter-btn-declined" class="px-3.5 py-1.5 text-xs font-semibold rounded-lg text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 hover:bg-white/40 dark:hover:bg-slate-800/40 transition duration-150">
                        Declined ({{ $event->registrations->where('status', 'declined')->count() }})
                    </button>
                </div>
            </div>

            <!-- Applicants Table (High Aesthetic Layout) -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/60 dark:border-slate-800 shadow-[0_4px_12px_rgba(15,23,42,0.02)] overflow-hidden">
                @if($event->registrations->isEmpty())
                    <div class="text-center py-16 text-slate-400 dark:text-slate-500 text-sm italic space-y-3">
                        <svg class="w-12 h-12 text-slate-300 dark:text-slate-700 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <p>No rsvp applications have been received for this assembly yet.</p>
                        <p class="text-xs text-slate-450 dark:text-slate-500 not-italic">Copy the public RSVP link above and share it with potential attendees!</p>
                    </div>
                @else
                    <div class="overflow-x-auto custom-scrollbar">
                        <table class="w-full text-left text-sm border-collapse">
                            <thead>
                                <tr class="bg-slate-50 dark:bg-slate-950 border-b border-slate-100 dark:border-slate-800/80 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                    <th class="py-3.5 px-6 w-12 text-center select-none text-[10px] tracking-wider text-slate-400">Sel</th>
                                    <th class="py-3.5 px-6">Attendee Profile</th>
                                    <th class="py-3.5 px-6">Email Address</th>
                                    <th class="py-3.5 px-6">Ticket Code</th>
                                    <th class="py-3.5 px-6">Gender</th>
                                    <th class="py-3.5 px-6">Submission Time</th>
                                    <th class="py-3.5 px-6">Status</th>
                                    <th class="py-3.5 px-6 text-right">Moderation Actions</th>
                                </tr>
                            </thead>
                            <tbody id="applicants-table-body" class="divide-y divide-slate-100 dark:divide-slate-800/80">
                                @foreach($event->registrations->sortByDesc('created_at') as $reg)
                                    <tr class="applicant-row hover:bg-slate-50/50 dark:hover:bg-slate-800/35 transition duration-150" 
                                        data-id="{{ $reg->id }}"
                                        data-name="{{ strtolower($reg->name) }}" 
                                        data-email="{{ strtolower($reg->email) }}" 
                                        data-code="{{ strtolower($reg->ticket_code) }}"
                                        data-status="{{ $reg->status }}">
                                        
                                        <!-- Selection Checkbox -->
                                        <td class="py-4 px-6 text-center select-none">
                                            <input type="checkbox" class="applicant-checkbox w-4 h-4 rounded border-slate-300 dark:border-slate-700/80 text-purple-600 dark:text-purple-400 focus:ring-purple-500/20 dark:focus:ring-purple-500/10 bg-white dark:bg-slate-900 transition duration-150" data-id="{{ $reg->id }}">
                                        </td>
                                        
                                        <!-- Profile / Avatar Initials -->
                                        <td class="py-4 px-6 font-semibold text-slate-800 dark:text-slate-200">
                                            <div class="flex items-center gap-3">
                                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-purple-50 dark:bg-purple-950/40 text-purple-600 dark:text-purple-400 font-bold text-xs uppercase shrink-0">
                                                    {{ substr($reg->name, 0, 2) }}
                                                </span>
                                                <span class="truncate text-slate-800 dark:text-slate-200 font-semibold">{{ $reg->name }}</span>
                                            </div>
                                        </td>
                                        
                                        <!-- Email Address -->
                                        <td class="py-4 px-6 text-slate-500 dark:text-slate-400 font-mono text-xs">{{ $reg->email }}</td>
                                        
                                        <!-- Ticket Code Column -->
                                        <td class="py-4 px-6 font-mono text-xs text-purple-650 dark:text-purple-400 font-bold">
                                            {{ $reg->ticket_code ?? 'N/A' }}
                                        </td>
                                        
                                        <!-- Gender Identity -->
                                        <td class="py-4 px-6 text-slate-600 dark:text-slate-400 text-xs">
                                            <span class="px-2 py-1 bg-slate-50 dark:bg-slate-950 border border-slate-100/80 dark:border-slate-800/80 rounded-lg font-medium text-slate-700 dark:text-slate-300 whitespace-nowrap">
                                                {{ $reg->gender ?? 'Unspecified' }}
                                            </span>
                                        </td>
                                        
                                        <!-- Time Stamp -->
                                        <td class="py-4 px-6 text-slate-500 dark:text-slate-400 text-xs">
                                            {{ $reg->created_at ? $reg->created_at->format('M j, Y • g:i A') : 'N/A' }}
                                        </td>
                                        
                                        <!-- Status Badge -->
                                        <td class="py-4 px-6">
                                            @if($reg->status === 'approved')
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 bg-emerald-50 dark:bg-emerald-950/30 text-emerald-700 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-900/30 text-[10px] font-bold rounded-md uppercase tracking-wider">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                    Approved
                                                </span>
                                                <div class="attendance-badge-wrapper mt-1">
                                                    @if($reg->attended)
                                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 bg-emerald-100 dark:bg-emerald-950/50 text-emerald-800 dark:text-emerald-300 border border-emerald-200/50 dark:border-emerald-900/30 text-[10px] font-bold rounded-md uppercase tracking-wider">
                                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                            Attended
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 bg-slate-100 dark:bg-slate-950/40 text-slate-600 dark:text-slate-400 border border-slate-200/40 dark:border-slate-800/60 text-[10px] font-bold rounded-md uppercase tracking-wider">
                                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-450"></span>
                                                            Absent
                                                        </span>
                                                    @endif
                                                </div>
                                            @elseif($reg->status === 'declined')
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 bg-red-50 dark:bg-red-950/30 text-red-700 dark:text-red-400 border border-red-100 dark:border-red-900/30 text-[10px] font-bold rounded-md uppercase tracking-wider">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                                    Declined
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 bg-amber-50 dark:bg-amber-950/30 text-amber-700 dark:text-amber-400 border border-amber-100 dark:border-amber-900/30 text-[10px] font-bold rounded-md uppercase tracking-wider animate-pulse">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                                    Pending
                                                </span>
                                            @endif
                                        </td>
                                        
                                        <!-- Actions Column -->
                                        <td class="py-4 px-6 text-right space-x-1.5 whitespace-nowrap">
                                            @if($reg->status === 'pending')
                                                <!-- Approve Form -->
                                                <form action="{{ route('committees.registrations.approve', $reg) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="text-xs font-semibold text-emerald-600 dark:text-emerald-400 hover:text-white dark:hover:text-slate-900 hover:bg-emerald-500 dark:hover:bg-emerald-400 border border-emerald-200 dark:border-emerald-800/60 hover:border-transparent dark:hover:border-transparent px-3 py-1.5 rounded-xl transition duration-150">
                                                        Approve
                                                    </button>
                                                </form>

                                                <!-- Decline Form -->
                                                <form action="{{ route('committees.registrations.decline', $reg) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="text-xs font-semibold text-red-500 dark:text-red-400 hover:text-white dark:hover:text-slate-900 hover:bg-red-500 dark:hover:bg-red-400 border border-red-200 dark:border-red-900/60 hover:border-transparent dark:hover:border-transparent px-3 py-1.5 rounded-xl transition duration-150">
                                                        Decline
                                                    </button>
                                                </form>
                                            @elseif($reg->status === 'approved')
                                                <!-- Attendance Check Toggle -->
                                                <form action="{{ route('committees.registrations.toggle_attendance', $reg) }}" method="POST" class="inline toggle-attendance-form">
                                                    @csrf
                                                    <button type="submit" class="text-xs font-semibold {{ $reg->attended ? 'text-amber-600 dark:text-amber-400 border border-amber-200 dark:border-amber-900/50 hover:bg-amber-500 dark:hover:bg-amber-550' : 'text-purple-600 dark:text-purple-400 border border-purple-200 dark:border-purple-800/80 hover:bg-purple-500 dark:hover:bg-purple-600' }} hover:text-white hover:border-transparent px-3 py-1.5 rounded-xl transition duration-150">
                                                        {{ $reg->attended ? 'Mark Absent' : 'Mark Attended' }}
                                                    </button>
                                                </form>
                                            @endif

                                            <!-- Delete Registration Form -->
                                            <form action="{{ route('committees.registrations.destroy', $reg) }}" method="POST" class="inline delete-registration-form" data-name="{{ $reg->name }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-xs font-semibold text-red-600 dark:text-red-400 hover:text-white hover:bg-red-600 border border-red-200 dark:border-red-900/40 hover:border-transparent p-1.5 rounded-xl transition duration-150" title="Delete Registrant">
                                                    <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Client-side No Results State inside table -->
                    <div id="no-applicants-matched" class="hidden text-center py-12 text-slate-400 dark:text-slate-500 text-sm bg-slate-50/50 dark:bg-slate-950/50 border-t border-slate-100 dark:border-slate-800">
                        <svg class="w-10 h-10 text-slate-300 dark:text-slate-700 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        No attendee registrations match your filter or search query.
                    </div>
                @endif
            </div>
        </div>

        <!-- TAB 2: SUMMARY & ANALYTICS PANEL (REPORT MODULE) -->
        <div id="tab-panel-summary" class="hidden space-y-6">
            <!-- Grid of Analytics Stats Card -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Card 1: Total Submissions -->
                <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 p-6 shadow-sm flex flex-col justify-between">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-xs font-bold uppercase text-slate-400 dark:text-slate-500 tracking-wider">Total Submissions</span>
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400">
                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 4v12l-4-2-4 2V4M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </span>
                    </div>
                    <div>
                        <h3 class="text-3xl font-extrabold text-slate-900 dark:text-white">{{ $event->registrations->count() }}</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Total RSVP forms filled</p>
                    </div>
                </div>

                <!-- Card 2: Approved Attendee Queue -->
                <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 p-6 shadow-sm flex flex-col justify-between">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-xs font-bold uppercase text-slate-400 dark:text-slate-500 tracking-wider">Approved Seats</span>
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400">
                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </span>
                    </div>
                    <div>
                        <h3 class="text-3xl font-extrabold text-slate-900 dark:text-white font-sans">
                            {{ $event->registrations->where('status', 'approved')->count() }}
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                            @php
                                $total = $event->registrations->count();
                                $approved = $event->registrations->where('status', 'approved')->count();
                                $appRate = $total > 0 ? round(($approved / $total) * 100) : 0;
                            @endphp
                            Approval rate is <span class="font-bold text-emerald-600 dark:text-emerald-400">{{ $appRate }}%</span>
                        </p>
                    </div>
                </div>

                <!-- Card 3: Pending Queue -->
                <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 p-6 shadow-sm flex flex-col justify-between">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-xs font-bold uppercase text-slate-400 dark:text-slate-500 tracking-wider">Awaiting Review</span>
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400">
                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </span>
                    </div>
                    <div>
                        <h3 class="text-3xl font-extrabold text-slate-900 dark:text-white font-sans">
                            {{ $event->registrations->where('status', 'pending')->count() }}
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Applications in queue</p>
                    </div>
                </div>

                <!-- Card 4: Declined Count -->
                <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 p-6 shadow-sm flex flex-col justify-between">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-xs font-bold uppercase text-slate-400 dark:text-slate-500 tracking-wider">Declined Seats</span>
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-red-50 dark:bg-red-950/40 text-red-600 dark:text-red-400">
                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </span>
                    </div>
                    <div>
                        <h3 class="text-3xl font-extrabold text-slate-900 dark:text-white font-sans">
                            {{ $event->registrations->where('status', 'declined')->count() }}
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Requests rejected</p>
                    </div>
                </div>
            </div>

            <!-- Detailed Visual breakdown -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Bar Breakdown of registrations -->
                <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 p-6 shadow-sm lg:col-span-2 space-y-6">
                    <h4 class="font-bold text-slate-900 dark:text-white text-base">Seat Occupancy Breakdown</h4>
                    
                    <div class="space-y-4">
                        <!-- Progress 1: Approved -->
                        <div class="space-y-1.5">
                            <div class="flex justify-between text-xs font-semibold">
                                <span class="text-slate-600 dark:text-slate-400">Approved Attendance</span>
                                <span class="text-slate-900 dark:text-white">{{ $approved }} seats ({{ $appRate }}%)</span>
                            </div>
                            <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-3 overflow-hidden">
                                <div class="bg-emerald-500 h-full rounded-full transition-all duration-500" style="width: {{ $appRate }}%"></div>
                            </div>
                        </div>

                        <!-- Progress 2: Pending -->
                        @php
                            $pending = $event->registrations->where('status', 'pending')->count();
                            $pendRate = $total > 0 ? round(($pending / $total) * 100) : 0;
                        @endphp
                        <div class="space-y-1.5">
                            <div class="flex justify-between text-xs font-semibold">
                                <span class="text-slate-600 dark:text-slate-400">Pending Review Pipeline</span>
                                <span class="text-slate-900 dark:text-white">{{ $pending }} in-queue ({{ $pendRate }}%)</span>
                            </div>
                            <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-3 overflow-hidden">
                                <div class="bg-amber-400 h-full rounded-full transition-all duration-500" style="width: {{ $pendRate }}%"></div>
                            </div>
                        </div>

                        <!-- Progress 3: Declined -->
                        @php
                            $declined = $event->registrations->where('status', 'declined')->count();
                            $decRate = $total > 0 ? round(($declined / $total) * 100) : 0;
                        @endphp
                        <div class="space-y-1.5">
                            <div class="flex justify-between text-xs font-semibold">
                                <span class="text-slate-600 dark:text-slate-400">Declined Requests</span>
                                <span class="text-slate-900 dark:text-white">{{ $declined }} requests ({{ $decRate }}%)</span>
                            </div>
                            <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-3 overflow-hidden">
                                <div class="bg-red-500 h-full rounded-full transition-all duration-500" style="width: {{ $decRate }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Gender Demographics Breakdown -->
                <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 p-6 shadow-sm space-y-6">
                    <h4 class="font-bold text-slate-900 dark:text-white text-base">Gender Demographics</h4>
                    
                    <div class="space-y-4.5">
                        @php
                            $males = $event->registrations->where('gender', 'Male')->count();
                            $females = $event->registrations->where('gender', 'Female')->count();
                            $lgbtq = $event->registrations->where('gender', 'LGBTQ+')->count();
                            $unspecified = $event->registrations->whereNull('gender')->count();
                            $others = $total - ($males + $females + $lgbtq + $unspecified);
                            
                            $malePct = $total > 0 ? round(($males / $total) * 100) : 0;
                            $femalePct = $total > 0 ? round(($females / $total) * 100) : 0;
                            $lgbtPct = $total > 0 ? round(($lgbtq / $total) * 100) : 0;
                            $otherPct = $total > 0 ? round(($others / $total) * 100) : 0;
                            $unspPct = $total > 0 ? round(($unspecified / $total) * 100) : 0;
                        @endphp

                        <!-- Male Progress -->
                        <div class="space-y-1.5">
                            <div class="flex justify-between text-xs font-semibold">
                                <span class="text-slate-600 dark:text-slate-400 flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full bg-blue-500"></span> Male
                                </span>
                                <span class="text-slate-800 dark:text-slate-200">{{ $males }} ({{ $malePct }}%)</span>
                            </div>
                            <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-2 overflow-hidden">
                                <div class="bg-blue-500 h-full rounded-full transition-all duration-500" style="width: {{ $malePct }}%"></div>
                            </div>
                        </div>

                        <!-- Female Progress -->
                        <div class="space-y-1.5">
                            <div class="flex justify-between text-xs font-semibold">
                                <span class="text-slate-600 dark:text-slate-400 flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full bg-pink-500"></span> Female
                                </span>
                                <span class="text-slate-800 dark:text-slate-200">{{ $females }} ({{ $femalePct }}%)</span>
                            </div>
                            <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-2 overflow-hidden">
                                <div class="bg-pink-500 h-full rounded-full transition-all duration-500" style="width: {{ $femalePct }}%"></div>
                            </div>
                        </div>

                        <!-- LGBTQ+ Progress -->
                        <div class="space-y-1.5">
                            <div class="flex justify-between text-xs font-semibold">
                                <span class="text-slate-600 dark:text-slate-400 flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full bg-purple-500"></span> LGBTQ+
                                </span>
                                <span class="text-slate-800 dark:text-slate-200">{{ $lgbtq }} ({{ $lgbtPct }}%)</span>
                            </div>
                            <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-2 overflow-hidden">
                                <div class="bg-purple-500 h-full rounded-full transition-all duration-500" style="width: {{ $lgbtPct }}%"></div>
                            </div>
                        </div>

                        <!-- Others Progress -->
                        @if($others > 0)
                        <div class="space-y-1.5">
                            <div class="flex justify-between text-xs font-semibold">
                                <span class="text-slate-600 dark:text-slate-400 flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full bg-indigo-400"></span> Others (Specified)
                                </span>
                                <span class="text-slate-800 dark:text-slate-200">{{ $others }} ({{ $otherPct }}%)</span>
                            </div>
                            <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-2 overflow-hidden">
                                <div class="bg-indigo-400 h-full rounded-full transition-all duration-500" style="width: {{ $otherPct }}%"></div>
                            </div>
                        </div>
                        @endif

                        <!-- Unspecified/Prior Progress -->
                        @if($unspecified > 0)
                        <div class="space-y-1.5">
                            <div class="flex justify-between text-xs font-semibold">
                                <span class="text-slate-600 dark:text-slate-400 flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full bg-slate-400"></span> Unspecified
                                </span>
                                <span class="text-slate-800 dark:text-slate-200">{{ $unspecified }} ({{ $unspPct }}%)</span>
                            </div>
                            <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-2 overflow-hidden">
                                <div class="bg-slate-400 h-full rounded-full transition-all duration-500" style="width: {{ $unspPct }}%"></div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Print Actions Banner -->
            <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 p-6 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="space-y-1 text-left w-full sm:w-auto">
                    <h4 class="font-bold text-slate-900 dark:text-white text-base">Print Summary Report</h4>
                    <p class="text-xs text-slate-500 dark:text-slate-400 max-w-xl">Need to share attendee metrics and gender demographics with your divisional leads? Print or save a PDF summary containing all active registrations, pending applicants, and venue locations.</p>
                </div>

                <button onclick="window.print()" class="w-full sm:w-auto shrink-0 inline-flex items-center justify-center gap-2 text-xs font-semibold py-3 px-5 rounded-xl bg-purple-600 hover:bg-purple-700 text-white transition duration-150 shadow-md hover:shadow-lg focus:outline-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    Print/Save PDF Report
                </button>
            </div>
        </div>
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

    <!-- BACKDROP MODAL: EDIT EVENT DETAILS -->
    <div id="edit-event-modal"
        class="fixed inset-0 bg-slate-900/60 dark:bg-slate-950/80 backdrop-blur-[4px] z-50 hidden items-center justify-center p-4 transition-all duration-300 opacity-0">
        <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 max-w-lg w-full shadow-2xl p-5 sm:p-8 flex flex-col max-h-[90vh] transition-all duration-300 transform scale-95 opacity-0"
            id="edit-event-modal-content">
            <div class="flex justify-between items-center pb-4 border-b border-slate-100 dark:border-slate-800/60 shrink-0">
                <div>
                    <h3 class="font-bold text-slate-900 dark:text-white text-lg leading-snug">Edit Assembly Details</h3>
                    <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">Modify scheduling, venue, cover image, or seat limits.</p>
                </div>
                <button onclick="closeEditEventModal()"
                    class="text-slate-400 dark:text-slate-500 hover:text-slate-600 dark:hover:text-slate-200 p-1.5 hover:bg-slate-50 dark:hover:bg-slate-800/60 rounded-xl transition duration-150">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form action="{{ route('committees.events.update', $event) }}" method="POST" class="flex-grow flex flex-col min-h-0 mt-4 sm:mt-6">
                @csrf
                @method('PUT')

                <!-- Scrollable Form Fields Content Wrapper -->
                <div class="flex-grow overflow-y-auto custom-scrollbar space-y-5 pr-1 -mr-1">
                    <!-- Event Title -->
                    <div class="space-y-2">
                        <label for="edit_event_title"
                            class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Event Title</label>
                        <input type="text" name="title" id="edit_event_title" required
                            value="{{ $event->title }}"
                            placeholder="e.g. Q3 Strategic Planning Assembly"
                            class="w-full rounded-xl border border-slate-300/80 dark:border-slate-800 py-3 px-4 text-slate-700 dark:text-slate-200 text-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-slate-50/50 dark:bg-slate-950/50 focus:bg-white dark:focus:bg-slate-950 transition-all duration-300">
                    </div>

                <!-- Event Description -->
                <div class="space-y-2">
                    <label for="edit_event_desc"
                        class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Description</label>
                    <textarea name="description" id="edit_event_desc" required rows="4"
                        placeholder="Describe the assembly purpose..."
                        class="w-full rounded-xl border border-slate-300/80 dark:border-slate-800 py-3 px-4 text-slate-700 dark:text-slate-200 text-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-slate-50/50 dark:bg-slate-950/50 focus:bg-white dark:focus:bg-slate-950 transition-all duration-300">{{ $event->description }}</textarea>
                </div>

                <!-- Event Cover Image URL -->
                <div class="space-y-2">
                    <label for="edit_event_image"
                        class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Event Cover Image URL (Optional)</label>
                    <input type="url" name="image" id="edit_event_image"
                        value="{{ $event->image }}"
                        placeholder="e.g. https://images.unsplash.com/..."
                        class="w-full rounded-xl border border-slate-300/80 dark:border-slate-800 py-3 px-4 text-slate-700 dark:text-slate-200 text-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-slate-50/50 dark:bg-slate-950/50 focus:bg-white dark:focus:bg-slate-950 transition-all duration-300">
                </div>

                <!-- Registration Type Selection -->
                <div class="space-y-2">
                    <label for="edit_registration_type" class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Registration Type</label>
                    <div class="relative">
                        <select id="edit_registration_type" name="registration_type" required 
                            class="w-full rounded-xl border border-slate-300/80 dark:border-slate-800 py-3 pl-4 pr-10 text-slate-700 dark:text-slate-200 text-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-slate-50/50 dark:bg-slate-950/50 focus:bg-white dark:focus:bg-slate-950 transition-all duration-300 appearance-none">
                            <option value="admin_approval" {{ $event->registration_type === 'admin_approval' ? 'selected' : '' }}>Approval by Admin (Traditional Pipe)</option>
                            <option value="venue_confirmation" {{ $event->registration_type === 'venue_confirmation' ? 'selected' : '' }}>Confirmation on Venue (Self Check-in / QR)</option>
                        </select>
                        <span class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-slate-455 dark:text-slate-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </span>
                    </div>
                </div>

                <!-- Registration Deadline / Cancellation Cutoff -->
                <div class="space-y-2">
                    <label for="edit_registration_deadline" class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Registration & Cancellation Deadline (Optional)</label>
                    <input type="datetime-local" name="registration_deadline" id="edit_registration_deadline"
                        value="{{ $event->registration_deadline ? $event->registration_deadline->format('Y-m-d\TH:i') : '' }}"
                        class="w-full rounded-xl border border-slate-300/80 dark:border-slate-800 py-3 px-4 text-slate-700 dark:text-slate-200 text-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-slate-50/50 dark:bg-slate-950/50 focus:bg-white dark:focus:bg-slate-950 transition-all duration-300">
                </div>

                <!-- Event Seating Capacity -->
                <div class="space-y-2">
                    <label for="edit-capacity-select" class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Attendance Capacity</label>
                    <div class="relative">
                        <select id="edit-capacity-select" onchange="handleEditCapacityChange(this)" required 
                            class="w-full rounded-xl border border-slate-300/80 dark:border-slate-800 py-3 pl-4 pr-10 text-slate-700 dark:text-slate-200 text-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-slate-50/50 dark:bg-slate-950/50 focus:bg-white dark:focus:bg-slate-950 transition-all duration-300 appearance-none">
                            <option value="unlimited" {{ $event->max_participants === null ? 'selected' : '' }}>No Limit (Unlimited Capacity)</option>
                            <option value="limited" {{ $event->max_participants !== null ? 'selected' : '' }}>Limited Capacity (Specify Seats)</option>
                        </select>
                        <span class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-slate-455 dark:text-slate-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </span>
                    </div>
                </div>

                <!-- Custom Capacity Input -->
                <div id="edit-capacity-input-wrapper" class="space-y-2 {{ $event->max_participants === null ? 'hidden' : '' }}">
                    <label for="edit_max_participants" class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Max Participants</label>
                    <input type="number" name="max_participants" id="edit_max_participants" min="1" 
                        value="{{ $event->max_participants }}"
                        placeholder="e.g. 50"
                        class="w-full rounded-xl border border-slate-300/80 dark:border-slate-800 py-3 px-4 text-slate-700 dark:text-slate-200 text-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-slate-50/50 dark:bg-slate-950/50 focus:bg-white dark:focus:bg-slate-950 transition-all duration-300"
                        {{ $event->max_participants !== null ? 'required' : '' }}>
                </div>

                <!-- Grid: Date & Location -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Event Date -->
                    <div class="space-y-2">
                        <label for="edit_event_date"
                            class="block text-xs font-semibold text-slate-550 dark:text-slate-400 uppercase tracking-wider">Date & Time</label>
                        <input type="datetime-local" name="event_date" id="edit_event_date" required
                            value="{{ $event->event_date->format('Y-m-d\TH:i') }}"
                            class="w-full rounded-xl border border-slate-300/80 dark:border-slate-800 py-3 px-4 text-slate-700 dark:text-slate-200 text-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-slate-50/50 dark:bg-slate-950/50 focus:bg-white dark:focus:bg-slate-950 transition-all duration-300">
                    </div>

                    <!-- Location -->
                    <div class="space-y-2">
                        <label for="edit_event_location"
                            class="block text-xs font-semibold text-slate-550 dark:text-slate-400 uppercase tracking-wider">Venue Location</label>
                        <input type="text" name="location" id="edit_event_location" required
                            value="{{ $event->location }}"
                            placeholder="e.g. Boardroom C, Zoom Video"
                            class="w-full rounded-xl border border-slate-300/80 dark:border-slate-800 py-3 px-4 text-slate-700 dark:text-slate-200 text-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-slate-50/50 dark:bg-slate-950/50 focus:bg-white dark:focus:bg-slate-950 transition-all duration-300">
                    </div>
                </div>
                </div> <!-- Close scrollable content wrapper -->

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100 dark:border-slate-800 shrink-0 mt-4 sm:mt-6">
                    <button type="button" onclick="closeEditEventModal()"
                        class="text-xs font-semibold py-2.5 px-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 transition duration-150 active:scale-[0.98]">
                        Cancel
                    </button>
                    <button type="submit"
                        class="text-xs font-semibold py-2.5 px-5 rounded-xl bg-purple-600 hover:bg-purple-700 text-white transition duration-150 shadow-sm active:scale-[0.98]">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
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
        const panelRequests = document.getElementById('tab-panel-requests');
        const panelSummary = document.getElementById('tab-panel-summary');

        if (!tabRequestsBtn || !tabSummaryBtn || !panelRequests || !panelSummary) return;

        // Reset Styles
        tabRequestsBtn.className = "tab-btn px-5 py-3 border-b-2 border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 hover:border-slate-300 dark:hover:border-slate-700 font-semibold text-sm transition duration-150 flex items-center gap-2 focus:outline-none whitespace-nowrap";
        tabSummaryBtn.className = "tab-btn px-5 py-3 border-b-2 border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 hover:border-slate-300 dark:hover:border-slate-700 font-semibold text-sm transition duration-150 flex items-center gap-2 focus:outline-none whitespace-nowrap";
        panelRequests.classList.add('hidden');
        panelSummary.classList.add('hidden');

        // Apply active Styles
        if (targetTab === 'requests') {
            tabRequestsBtn.className = "tab-btn px-5 py-3 border-b-2 border-purple-600 dark:border-purple-400 text-purple-600 dark:text-purple-400 font-bold text-sm transition duration-150 flex items-center gap-2 focus:outline-none whitespace-nowrap";
            panelRequests.classList.remove('hidden');
        } else if (targetTab === 'summary') {
            tabSummaryBtn.className = "tab-btn px-5 py-3 border-b-2 border-purple-600 dark:border-purple-400 text-purple-600 dark:text-purple-400 font-bold text-sm transition duration-150 flex items-center gap-2 focus:outline-none whitespace-nowrap";
            panelSummary.classList.remove('hidden');
        }

        currentTab = targetTab;
    }

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
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-450"></span>
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
                            <button type="submit" class="text-xs font-semibold text-red-600 dark:text-red-400 hover:text-white hover:bg-red-600 border border-red-200 dark:border-red-900/40 hover:border-transparent p-1.5 rounded-xl transition duration-150" title="Delete Registrant">
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
                            <button type="submit" class="text-xs font-semibold text-red-600 dark:text-red-400 hover:text-white hover:bg-red-600 border border-red-200 dark:border-red-900/40 hover:border-transparent p-1.5 rounded-xl transition duration-150" title="Delete Registrant">
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
                ${printContent}
            </div>
        `;
        window.print();
        document.body.innerHTML = originalContent;
        window.location.reload(); // Reload to safely restore state
    }
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
                <p class="text-[10px] text-slate-500 leading-relaxed max-w-xs mx-auto">Please scan this barcode with your smartphone, enter your registered email address, and verify your pass code to mark yourself attended.</p>
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
