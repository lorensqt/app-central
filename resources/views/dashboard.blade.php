@extends('layouts.app')

@section('title', 'Application Center')

@section('styles')
<style>
    @keyframes cardFadeIn {
        from {
            opacity: 0;
            transform: translateY(16px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    .app-card-entry {
        animation: cardFadeIn 0.45s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
    .app-card-entry:nth-child(1) { animation-delay: 0.05s; }
    .app-card-entry:nth-child(2) { animation-delay: 0.12s; }
    .app-card-entry:nth-child(3) { animation-delay: 0.19s; }
</style>
@endsection

@section('content')
@php
    $hour = now()->hour;
    $greeting = 'Welcome back';
    if ($hour >= 5 && $hour < 12) {
        $greeting = 'Good morning';
    } elseif ($hour >= 12 && $hour < 17) {
        $greeting = 'Good afternoon';
    } elseif ($hour >= 17 || $hour < 5) {
        $greeting = 'Good evening';
    }

    $canEvents = Auth::user()->hasPermission('events.access');
    $canElections = Auth::user()->hasPermission('elections.access');
    $canAdmin = Auth::user()->isSuperAdmin();

    $authorizedCount = ($canEvents ? 1 : 0) + ($canElections ? 1 : 0) + ($canAdmin ? 1 : 0);
@endphp

<div class="space-y-8 pb-16">
    <!-- Hero Header Banner -->
    <div class="relative bg-white/70 dark:bg-slate-900/70 backdrop-blur-xl border border-slate-200/80 dark:border-slate-800/80 rounded-3xl p-6 sm:p-8 shadow-xs overflow-hidden">
        <!-- Ambient Background Glow -->
        <div class="absolute -top-24 -right-24 w-80 h-80 bg-gradient-to-br from-purple-500/10 via-indigo-500/10 to-pink-500/5 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <!-- Left: Greeting & Context -->
            <div class="space-y-2.5 max-w-2xl">
                <div class="flex items-center gap-2 text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">
                    <span class="inline-flex items-center justify-center w-5 h-5 rounded-md bg-purple-50 dark:bg-purple-950/40 text-purple-600 dark:text-purple-400">
                        <i class="fa-solid fa-shapes text-[10px]"></i>
                    </span>
                    <span>Sako Central • Application Hub</span>
                    <span class="text-slate-300 dark:text-slate-700">•</span>
                    <span class="text-slate-500 dark:text-slate-400 font-medium">{{ now()->format('l, F j, Y') }}</span>
                </div>

                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight leading-tight">
                    {{ $greeting }}, <span class="bg-gradient-to-r from-purple-600 via-indigo-600 to-purple-500 dark:from-purple-400 dark:via-indigo-400 dark:to-pink-400 bg-clip-text text-transparent">{{ explode(' ', Auth::user()->name)[0] }}</span>!
                </h1>

                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 leading-relaxed">
                    Access your authorized organizational applications below. All workspace portals are secured with your verified corporate credentials.
                </p>
            </div>

            <!-- Right: Executive Access & Clearance Capsule -->
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 shrink-0">
                <div class="flex items-center gap-3.5 px-4 py-3 rounded-2xl bg-slate-50/90 dark:bg-slate-950/60 border border-slate-200/70 dark:border-slate-800/70 shadow-xs hover:border-purple-500/30 dark:hover:border-purple-500/30 transition-all duration-200">
                    <!-- Icon Tile with Ambient Glow -->
                    <div class="w-11 h-11 rounded-xl flex items-center justify-center shrink-0 shadow-xs {{ $canAdmin ? 'bg-gradient-to-tr from-purple-500/15 via-indigo-500/15 to-pink-500/10 text-purple-600 dark:text-purple-400 border border-purple-500/25' : 'bg-gradient-to-tr from-emerald-500/15 via-teal-500/15 to-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/25' }}">
                        @if($canAdmin)
                            <i class="fa-solid fa-shield-halved text-lg"></i>
                        @else
                            <i class="fa-solid fa-user-check text-base"></i>
                        @endif
                    </div>

                    <!-- Identity Clearance Details -->
                    <div class="min-w-0 pr-1">
                        <div class="flex items-center gap-1.5">
                            <span class="relative flex h-2 w-2 shrink-0">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                            </span>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 truncate">
                                @if($canAdmin)
                                    Platform Administrator
                                @else
                                    Authorized Personnel
                                @endif
                            </span>
                        </div>

                        <div class="text-xs sm:text-sm font-extrabold text-slate-800 dark:text-slate-100 leading-tight mt-0.5 truncate">
                            @if(Auth::user()->title)
                                {{ Auth::user()->title->title }}
                            @elseif($canAdmin)
                                Global System Access
                            @else
                                {{ $authorizedCount }} {{ Str::plural('Workspace', $authorizedCount) }} Accessible
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Real-Time Search & Filter Bar -->
        @if($authorizedCount > 0)
            <div class="relative z-10 mt-6 pt-6 border-t border-slate-100 dark:border-slate-800/60 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="relative flex-1 max-w-md">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400 dark:text-slate-500">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    </div>
                    <input type="text"
                           id="app-search-input"
                           placeholder="Filter applications (e.g. events, elections, admin)..."
                           class="w-full pl-9 pr-8 py-2 rounded-xl text-xs bg-slate-50/80 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-200 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-purple-500 focus:ring-2 focus:ring-purple-500/10 transition-all">
                    <button type="button"
                            id="app-search-clear"
                            class="hidden absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 text-xs focus:outline-none">
                        <i class="fa-solid fa-circle-xmark"></i>
                    </button>
                </div>

                <div class="flex items-center gap-2 text-xs text-slate-400 dark:text-slate-500">
                    <span id="app-counter" class="font-bold text-slate-700 dark:text-slate-300">{{ $authorizedCount }}</span>
                    <span>active workspaces accessible</span>
                </div>
            </div>
        @endif
    </div>

    <!-- Centralized Applications Grid -->
    @if($authorizedCount > 0)
        <div id="applications-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            <!-- APP 1: EVENT MANAGEMENT (EMERALD THEME) -->
            @if($canEvents)
                <div class="app-card app-card-entry group relative bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800/80 p-7 shadow-xs hover:shadow-xl hover:shadow-emerald-500/10 hover:border-emerald-500/50 dark:hover:border-emerald-500/40 hover:-translate-y-2 transition-all duration-300 ease-[cubic-bezier(0.16,1,0.3,1)] flex flex-col justify-between overflow-hidden"
                     data-name="event management events assemblies conferences attendance check-in rsvp surveys"
                     data-category="operational">
                    
                    <!-- Ambient Radial Glow -->
                    <div class="absolute -right-16 -bottom-16 w-44 h-44 rounded-full bg-emerald-500/5 dark:bg-emerald-500/10 blur-3xl group-hover:bg-emerald-500/20 group-hover:scale-125 transition-all duration-500 pointer-events-none"></div>

                    <div>
                        <!-- Top Header Row: Icon & Status Pill -->
                        <div class="flex items-center justify-between mb-6">
                            <div class="w-14 h-14 rounded-2xl border border-emerald-200 dark:border-emerald-900/50 text-emerald-600 dark:text-emerald-400 flex items-center justify-center bg-emerald-50/50 dark:bg-emerald-950/40 group-hover:bg-gradient-to-tr group-hover:from-emerald-500 group-hover:to-teal-600 group-hover:text-white group-hover:border-transparent group-hover:scale-110 group-hover:rotate-6 transition-all duration-300 shadow-sm shadow-emerald-500/10">
                                <i class="fa-solid fa-calendar-check text-xl"></i>
                            </div>

                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-800/40">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                Operations
                            </span>
                        </div>

                        <!-- App Title & Description -->
                        <h2 class="text-xl font-bold text-slate-900 dark:text-white tracking-tight group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors duration-200">
                            Event Management
                        </h2>

                        <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed mt-2.5">
                            Coordinate assemblies, schedule corporate gatherings, oversee attendance lists, and approve registration RSVPs in a centralized operational hub.
                        </p>

                        <!-- Feature Badges -->
                        <div class="flex flex-wrap gap-1.5 mt-5">
                            <span class="px-2 py-0.5 rounded-lg text-[10px] font-semibold bg-slate-100 dark:bg-slate-800/80 text-slate-600 dark:text-slate-400 border border-slate-200/60 dark:border-slate-700/60">
                                <i class="fa-solid fa-ticket mr-1 text-[9px] text-emerald-500"></i>RSVP Approvals
                            </span>
                            <span class="px-2 py-0.5 rounded-lg text-[10px] font-semibold bg-slate-100 dark:bg-slate-800/80 text-slate-600 dark:text-slate-400 border border-slate-200/60 dark:border-slate-700/60">
                                <i class="fa-solid fa-qrcode mr-1 text-[9px] text-emerald-500"></i>Digital Passes
                            </span>
                            <span class="px-2 py-0.5 rounded-lg text-[10px] font-semibold bg-slate-100 dark:bg-slate-800/80 text-slate-600 dark:text-slate-400 border border-slate-200/60 dark:border-slate-700/60">
                                <i class="fa-solid fa-poll mr-1 text-[9px] text-emerald-500"></i>Post Surveys
                            </span>
                        </div>
                    </div>

                    <!-- Footer Action Link -->
                    <div class="mt-8 pt-4 border-t border-slate-100 dark:border-slate-800/60 flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-400 group-hover:text-emerald-700 dark:group-hover:text-emerald-300 flex items-center gap-1.5 transition-colors">
                            Launch Workspace
                            <svg class="w-4 h-4 transform group-hover:translate-x-1.5 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </span>
                        <span class="w-8 h-8 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-xs group-hover:bg-emerald-600 group-hover:text-white transition-all duration-200 shadow-xs">
                            <i class="fa-solid fa-arrow-right"></i>
                        </span>
                    </div>

                    <!-- Full Click Target -->
                    <a href="{{ route('committees.events.index') }}" class="after:absolute after:inset-0 focus:outline-none" aria-label="Open Event Management"></a>
                </div>
            @endif

            <!-- APP 2: ELECTION MANAGEMENT (INDIGO THEME) -->
            @if($canElections)
                <div class="app-card app-card-entry group relative bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800/80 p-7 shadow-xs hover:shadow-xl hover:shadow-indigo-500/10 hover:border-indigo-500/50 dark:hover:border-indigo-500/40 hover:-translate-y-2 transition-all duration-300 ease-[cubic-bezier(0.16,1,0.3,1)] flex flex-col justify-between overflow-hidden"
                     data-name="election management elections voting ballots candidates voters results"
                     data-category="governance">
                    
                    <!-- Ambient Radial Glow -->
                    <div class="absolute -right-16 -bottom-16 w-44 h-44 rounded-full bg-indigo-500/5 dark:bg-indigo-500/10 blur-3xl group-hover:bg-indigo-500/20 group-hover:scale-125 transition-all duration-500 pointer-events-none"></div>

                    <div>
                        <!-- Top Header Row: Icon & Status Pill -->
                        <div class="flex items-center justify-between mb-6">
                            <div class="w-14 h-14 rounded-2xl border border-indigo-200 dark:border-indigo-900/50 text-indigo-600 dark:text-indigo-400 flex items-center justify-center bg-indigo-50/50 dark:bg-indigo-950/40 group-hover:bg-gradient-to-tr group-hover:from-indigo-500 group-hover:to-purple-600 group-hover:text-white group-hover:border-transparent group-hover:scale-110 group-hover:rotate-6 transition-all duration-300 shadow-sm shadow-indigo-500/10">
                                <i class="fa-solid fa-people-line text-xl"></i>
                            </div>

                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-indigo-50 dark:bg-indigo-950/40 text-indigo-700 dark:text-indigo-300 border border-indigo-200/60 dark:border-indigo-800/40">
                                <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                                Governance
                            </span>
                        </div>

                        <!-- App Title & Description -->
                        <h2 class="text-xl font-bold text-slate-900 dark:text-white tracking-tight group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors duration-200">
                            Election Management
                        </h2>

                        <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed mt-2.5">
                            Establish encrypted ballots, register designated candidates, verify authorized voting rosters, and monitor live voter turnout with PDF reporting.
                        </p>

                        <!-- Feature Badges -->
                        <div class="flex flex-wrap gap-1.5 mt-5">
                            <span class="px-2 py-0.5 rounded-lg text-[10px] font-semibold bg-slate-100 dark:bg-slate-800/80 text-slate-600 dark:text-slate-400 border border-slate-200/60 dark:border-slate-700/60">
                                <i class="fa-solid fa-check-to-slot mr-1 text-[9px] text-indigo-500"></i>Secure Ballots
                            </span>
                            <span class="px-2 py-0.5 rounded-lg text-[10px] font-semibold bg-slate-100 dark:bg-slate-800/80 text-slate-600 dark:text-slate-400 border border-slate-200/60 dark:border-slate-700/60">
                                <i class="fa-solid fa-chart-pie mr-1 text-[9px] text-indigo-500"></i>Real-Time Tally
                            </span>
                            <span class="px-2 py-0.5 rounded-lg text-[10px] font-semibold bg-slate-100 dark:bg-slate-800/80 text-slate-600 dark:text-slate-400 border border-slate-200/60 dark:border-slate-700/60">
                                <i class="fa-solid fa-file-pdf mr-1 text-[9px] text-indigo-500"></i>Official Reports
                            </span>
                        </div>
                    </div>

                    <!-- Footer Action Link -->
                    <div class="mt-8 pt-4 border-t border-slate-100 dark:border-slate-800/60 flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-indigo-600 dark:text-indigo-400 group-hover:text-indigo-700 dark:group-hover:text-indigo-300 flex items-center gap-1.5 transition-colors">
                            Launch Workspace
                            <svg class="w-4 h-4 transform group-hover:translate-x-1.5 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </span>
                        <span class="w-8 h-8 rounded-xl bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-xs group-hover:bg-indigo-600 group-hover:text-white transition-all duration-200 shadow-xs">
                            <i class="fa-solid fa-arrow-right"></i>
                        </span>
                    </div>

                    <!-- Full Click Target -->
                    <a href="{{ route('committees.election.index') }}" class="after:absolute after:inset-0 focus:outline-none" aria-label="Open Election Management"></a>
                </div>
            @endif

            <!-- APP 3: PLATFORM COCKPIT / ADMIN (PURPLE THEME, SUPER ADMIN ONLY) -->
            @if($canAdmin)
                <div class="app-card app-card-entry group relative bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800/80 p-7 shadow-xs hover:shadow-xl hover:shadow-purple-500/10 hover:border-purple-500/50 dark:hover:border-purple-500/40 hover:-translate-y-2 transition-all duration-300 ease-[cubic-bezier(0.16,1,0.3,1)] flex flex-col justify-between overflow-hidden"
                     data-name="platform administration cockpit admin users permissions titles rbac committees"
                     data-category="administration">
                    
                    <!-- Ambient Radial Glow -->
                    <div class="absolute -right-16 -bottom-16 w-44 h-44 rounded-full bg-purple-500/5 dark:bg-purple-500/10 blur-3xl group-hover:bg-purple-500/20 group-hover:scale-125 transition-all duration-500 pointer-events-none"></div>

                    <div>
                        <!-- Top Header Row: Icon & Status Pill -->
                        <div class="flex items-center justify-between mb-6">
                            <div class="w-14 h-14 rounded-2xl border border-purple-200 dark:border-purple-900/50 text-purple-600 dark:text-purple-400 flex items-center justify-center bg-purple-50/50 dark:bg-purple-950/40 group-hover:bg-gradient-to-tr group-hover:from-purple-500 group-hover:to-pink-600 group-hover:text-white group-hover:border-transparent group-hover:scale-110 group-hover:rotate-6 transition-all duration-300 shadow-sm shadow-purple-500/10">
                                <i class="fa-solid fa-gauge-high text-xl"></i>
                            </div>

                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-purple-50 dark:bg-purple-950/40 text-purple-700 dark:text-purple-300 border border-purple-200/60 dark:border-purple-800/40">
                                <span class="w-1.5 h-1.5 rounded-full bg-purple-500"></span>
                                Super Admin
                            </span>
                        </div>

                        <!-- App Title & Description -->
                        <h2 class="text-xl font-bold text-slate-900 dark:text-white tracking-tight group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors duration-200">
                            Platform Administration
                        </h2>

                        <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed mt-2.5">
                            Manage authorized corporate personnel, assign granular page access permissions, configure dynamic operational committees, and govern corporate designations.
                        </p>

                        <!-- Feature Badges -->
                        <div class="flex flex-wrap gap-1.5 mt-5">
                            <span class="px-2 py-0.5 rounded-lg text-[10px] font-semibold bg-slate-100 dark:bg-slate-800/80 text-slate-600 dark:text-slate-400 border border-slate-200/60 dark:border-slate-700/60">
                                <i class="fa-solid fa-user-shield mr-1 text-[9px] text-purple-500"></i>Personnel & RBAC
                            </span>
                            <span class="px-2 py-0.5 rounded-lg text-[10px] font-semibold bg-slate-100 dark:bg-slate-800/80 text-slate-600 dark:text-slate-400 border border-slate-200/60 dark:border-slate-700/60">
                                <i class="fa-solid fa-sitemap mr-1 text-[9px] text-purple-500"></i>Committees
                            </span>
                            <span class="px-2 py-0.5 rounded-lg text-[10px] font-semibold bg-slate-100 dark:bg-slate-800/80 text-slate-600 dark:text-slate-400 border border-slate-200/60 dark:border-slate-700/60">
                                <i class="fa-solid fa-id-badge mr-1 text-[9px] text-purple-500"></i>Designations
                            </span>
                        </div>
                    </div>

                    <!-- Footer Action Link -->
                    <div class="mt-8 pt-4 border-t border-slate-100 dark:border-slate-800/60 flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-purple-600 dark:text-purple-400 group-hover:text-purple-700 dark:group-hover:text-purple-300 flex items-center gap-1.5 transition-colors">
                            Launch Cockpit
                            <svg class="w-4 h-4 transform group-hover:translate-x-1.5 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </span>
                        <span class="w-8 h-8 rounded-xl bg-purple-50 dark:bg-purple-950/40 text-purple-600 dark:text-purple-400 flex items-center justify-center text-xs group-hover:bg-purple-600 group-hover:text-white transition-all duration-200 shadow-xs">
                            <i class="fa-solid fa-arrow-right"></i>
                        </span>
                    </div>

                    <!-- Full Click Target -->
                    <a href="{{ route('admin.index') }}" class="after:absolute after:inset-0 focus:outline-none" aria-label="Open Platform Cockpit"></a>
                </div>
            @endif

        </div>

        <!-- No Matching Search Results State -->
        <div id="no-search-results" class="hidden bg-white/70 dark:bg-slate-900/70 backdrop-blur-xl rounded-3xl border border-dashed border-slate-200 dark:border-slate-800 p-12 text-center max-w-md mx-auto shadow-xs">
            <div class="w-12 h-12 rounded-2xl bg-purple-50 dark:bg-purple-950/40 text-purple-600 dark:text-purple-400 flex items-center justify-center mx-auto mb-4 text-lg">
                <i class="fa-solid fa-magnifying-glass"></i>
            </div>
            <h3 class="text-base font-bold text-slate-900 dark:text-white">No Matching Workspaces Found</h3>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1.5">No applications matched your filter query. Try searching with different terms.</p>
        </div>
    @else
        <!-- Empty State for Users without Any Permissions Assigned -->
        <div class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-2xl rounded-3xl border border-slate-200/80 dark:border-slate-800/80 p-10 sm:p-16 text-center max-w-lg mx-auto shadow-xl shadow-purple-500/5">
            <div class="relative inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 mb-5 shadow-inner border border-amber-200/60 dark:border-amber-800/40">
                <i class="fa-solid fa-shield-halved text-2xl"></i>
            </div>

            <div class="text-[11px] font-bold text-amber-600 dark:text-amber-400 uppercase tracking-wider mb-1">
                Workspace Access Restricted
            </div>

            <h2 class="text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight">
                No Applications Assigned Yet
            </h2>

            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-2.5 leading-relaxed">
                Your corporate account has been authenticated, but an administrator has not yet assigned workspace access permissions to your profile.
            </p>

            <div class="mt-8 pt-6 border-t border-slate-100 dark:border-slate-800 flex flex-col sm:flex-row items-center justify-center gap-3">
                <button type="button" onclick="window.location.reload()"
                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 text-xs font-bold py-2.5 px-5 rounded-xl bg-purple-600 hover:bg-purple-700 text-white shadow-xs active:scale-95 transition-all">
                    <i class="fa-solid fa-rotate-right"></i>
                    Refresh Permissions
                </button>
            </div>
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const searchInput = document.getElementById('app-search-input');
        const clearBtn = document.getElementById('app-search-clear');
        const counterEl = document.getElementById('app-counter');
        const cards = Array.from(document.querySelectorAll('.app-card'));
        const noResults = document.getElementById('no-search-results');

        if (!searchInput) return;

        function filterApps() {
            const query = searchInput.value.toLowerCase().trim();
            let visibleCount = 0;

            if (clearBtn) {
                if (query.length > 0) {
                    clearBtn.classList.remove('hidden');
                } else {
                    clearBtn.classList.add('hidden');
                }
            }

            cards.forEach(card => {
                const searchData = (card.getAttribute('data-name') || '').toLowerCase();
                const matches = searchData.includes(query);

                if (matches) {
                    card.classList.remove('hidden');
                    visibleCount++;
                } else {
                    card.classList.add('hidden');
                }
            });

            if (counterEl) {
                counterEl.textContent = visibleCount;
            }

            if (noResults) {
                if (visibleCount === 0 && query.length > 0) {
                    noResults.classList.remove('hidden');
                } else {
                    noResults.classList.add('hidden');
                }
            }
        }

        searchInput.addEventListener('input', filterApps);

        if (clearBtn) {
            clearBtn.addEventListener('click', () => {
                searchInput.value = '';
                filterApps();
                searchInput.focus();
            });
        }

        // Keyboard Shortcut: press '/' to focus search input
        window.addEventListener('keydown', (e) => {
            if (e.key === '/' && document.activeElement !== searchInput && !['INPUT', 'TEXTAREA'].includes(document.activeElement.tagName)) {
                e.preventDefault();
                searchInput.focus();
            }
        });
    });
</script>
@endsection
