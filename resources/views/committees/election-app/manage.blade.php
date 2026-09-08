@extends('layouts.app')

@section('title', 'Manage Election')

@section('content')
<div class="space-y-8">
    <!-- Back to Dashboard Link -->
    <div>
        <a href="{{ route('committees.election.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 hover:text-purple-600 dark:hover:text-purple-400 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Elections
        </a>
    </div>

    <!-- Header Card -->
    <div class="relative overflow-hidden bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-200/80 dark:border-slate-800/80 p-8 sm:p-10 shadow-sm">
        <div class="absolute -top-24 -left-24 w-72 h-72 bg-purple-400/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -right-24 w-72 h-72 bg-indigo-400/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div class="space-y-3 flex-grow">
                <div class="flex items-center gap-3 flex-wrap">
                    @php
                        $badgeClass = '';
                        if ($election->status === 'active') {
                            $badgeClass = 'bg-emerald-500/10 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400';
                        } elseif ($election->status === 'draft') {
                            $badgeClass = 'bg-slate-500/10 text-slate-600 dark:bg-slate-500/10 dark:text-slate-400';
                        } else {
                            $badgeClass = 'bg-rose-500/10 text-rose-600 dark:bg-rose-500/10 dark:text-rose-400';
                        }
                    @endphp
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $badgeClass }}">
                        {{ $election->status }}
                    </span>
                    
                    @if($election->start_date || $election->end_date)
                        <span class="text-xs text-slate-400 dark:text-slate-500 font-medium flex items-center gap-1.5">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            {{ $election->start_date ? $election->start_date->format('M d, Y, h:i A') : 'Start' }} - {{ $election->end_date ? $election->end_date->format('M d, Y, h:i A') : 'End' }}
                        </span>
                    @endif
                </div>
                
                <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight leading-tight">
                    {{ $election->title }}
                </h1>
                
                <p class="text-sm text-slate-500 dark:text-slate-400 max-w-2xl leading-relaxed">
                    {{ $election->description ?? 'No description provided.' }}
                </p>
            </div>

            <!-- Header Action Controls -->
            <div class="flex items-center gap-2.5 flex-wrap shrink-0">
                <a href="{{ route('elections.voter.setup', $election->id) }}" target="_blank"
                    class="inline-flex items-center justify-center gap-1.5 bg-purple-50 hover:bg-purple-100 dark:bg-purple-950/20 dark:hover:bg-purple-950/40 text-purple-600 dark:text-purple-400 font-semibold text-xs px-4 py-2.5 rounded-xl shadow-sm transition border border-transparent hover:border-purple-100 dark:hover:border-purple-900/40">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    View Voting Page
                </a>

                <button onclick="copyVotingLink()"
                    class="inline-flex items-center justify-center gap-1.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-semibold text-xs px-4 py-2.5 rounded-xl shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                    Copy Link
                </button>

                @if($election->status === 'draft')
                    <button onclick="changeElectionStatus('active')"
                        class="inline-flex items-center justify-center gap-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs px-4 py-2.5 rounded-xl shadow-sm transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Start Election
                    </button>
                @elseif($election->status === 'active')
                    <button onclick="changeElectionStatus('closed')"
                        class="inline-flex items-center justify-center gap-1.5 bg-rose-600 hover:bg-rose-700 text-white font-semibold text-xs px-4 py-2.5 rounded-xl shadow-sm transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 10a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"/></svg>
                        End Election
                    </button>
                    <button onclick="changeElectionStatus('draft')"
                        class="inline-flex items-center justify-center gap-1.5 bg-slate-200 hover:bg-slate-300 dark:bg-slate-800 dark:hover:bg-slate-750 text-slate-700 dark:text-slate-300 font-semibold text-xs px-4 py-2.5 rounded-xl shadow-sm transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Pause/Revert to Draft
                    </button>
                @elseif($election->status === 'closed')
                    <button onclick="changeElectionStatus('draft')"
                        class="inline-flex items-center justify-center gap-1.5 bg-slate-200 hover:bg-slate-300 dark:bg-slate-800 dark:hover:bg-slate-750 text-slate-700 dark:text-slate-300 font-semibold text-xs px-4 py-2.5 rounded-xl shadow-sm transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 15.89M9 11l3-3m0 0l3 3m-3-3v12"/></svg>
                        Revert to Draft
                    </button>
                @endif

                <button onclick="openEditElectionModal()"
                    class="inline-flex items-center justify-center gap-1.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-semibold text-xs px-4 py-2.5 rounded-xl shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                    Edit Details
                </button>

                <button onclick="triggerDeleteElectionFromManage()"
                    class="inline-flex items-center justify-center gap-1.5 bg-rose-50 hover:bg-rose-100 dark:bg-rose-950/20 dark:hover:bg-rose-950/40 text-rose-600 dark:text-rose-400 font-semibold text-xs px-4 py-2.5 rounded-xl shadow-sm transition border border-transparent hover:border-rose-100 dark:hover:border-rose-900/40">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    Delete
                </button>
            </div>
        </div>
    </div>

    <!-- Segmented Navigation Controls -->
    <div class="border-b border-slate-200 dark:border-slate-800">
        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
            <button onclick="switchManageTab('structure')" id="tab-btn-structure" class="manage-tab-btn border-purple-600 dark:border-purple-500 text-purple-600 dark:text-purple-400 font-bold border-b-2 py-4 px-1 text-sm focus:outline-none whitespace-nowrap">
                Positions & Candidates
            </button>
            <button onclick="switchManageTab('results')" id="tab-btn-results" class="manage-tab-btn border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 border-b-2 py-4 px-1 text-sm focus:outline-none whitespace-nowrap">
                Election Results
            </button>
            <button onclick="switchManageTab('voters')" id="tab-btn-voters" class="manage-tab-btn border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 border-b-2 py-4 px-1 text-sm focus:outline-none whitespace-nowrap">
                Voters & Analytics
            </button>
        </nav>
    </div>

    <!-- Tab Contents Workspace -->
    <div>
        <!-- PANEL A: STRUCTURE (Positions & Candidates) -->
        <div id="panel-structure" class="manage-tab-panel space-y-6">
            <div class="flex items-center justify-between gap-4">
                <h2 class="text-xl font-bold text-slate-900 dark:text-white">Ballot Structure</h2>
                <button onclick="openCreatePositionModal()" class="inline-flex items-center gap-1.5 bg-purple-600 hover:bg-purple-700 text-white font-semibold text-xs px-4 py-2.5 rounded-xl shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 4v16m8-8H4"/></svg>
                    Add Position
                </button>
            </div>

            <!-- List of Positions -->
            <div class="space-y-6" id="positions-container">
                @forelse($positions as $position)
                    <div class="bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-200/60 dark:border-slate-800/80 p-6 sm:p-8 shadow-sm space-y-6" id="position-block-{{ $position->id }}">
                        <!-- Position Header -->
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-100 dark:border-slate-800/60">
                            <div>
                                <h3 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
                                    {{ $position->name }}
                                    <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400">
                                        Choose Up To {{ $position->max_votes }}
                                    </span>
                                </h3>
                                <p class="text-xs text-slate-400 mt-0.5">Order Priority: {{ $position->sort_order }}</p>
                            </div>

                            <div class="flex items-center gap-2">
                                <button onclick="openCreateCandidateModal({{ $position->id }}, '{{ addslashes($position->name) }}')" class="inline-flex items-center gap-1 text-slate-600 dark:text-slate-300 hover:text-purple-600 dark:hover:text-purple-400 font-bold text-xs py-2 px-3 rounded-lg hover:bg-purple-50 dark:hover:bg-purple-950/20 transition border border-transparent hover:border-purple-100 dark:hover:border-purple-900/40">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    Add Candidate
                                </button>
                                <button onclick="openEditPositionModal({{ json_encode($position) }})" class="p-2 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-xl text-slate-500 hover:text-slate-800 dark:hover:text-slate-200 transition" title="Edit Position">
                                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>
                                <button onclick="deletePosition({{ $position->id }})" class="p-2 hover:bg-rose-50 dark:hover:bg-rose-950/20 rounded-xl text-slate-500 hover:text-rose-600 dark:hover:text-rose-400 transition" title="Delete Position">
                                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                        </div>

                        <!-- Candidate Grid inside Position -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4" id="candidates-grid-{{ $position->id }}">
                            @forelse($position->candidates as $candidate)
                                <div class="relative group/card bg-slate-50/50 dark:bg-slate-950/40 border border-slate-100 dark:border-slate-800/80 rounded-2xl p-4 flex items-center justify-between hover:shadow-sm transition" id="candidate-card-{{ $candidate->id }}">
                                    <div class="flex items-center gap-3.5 min-w-0">
                                        <!-- Avatar -->
                                        @if($candidate->avatar_path)
                                            <img src="{{ $candidate->avatar_path }}" alt="{{ $candidate->name }}" class="w-11 h-11 rounded-full object-cover shrink-0 border border-slate-200 dark:border-slate-850">
                                        @else
                                            <div class="w-11 h-11 rounded-full bg-purple-50 dark:bg-purple-950 text-purple-600 dark:text-purple-400 font-bold flex items-center justify-center shrink-0 border border-purple-100/30 text-sm uppercase">
                                                {{ substr($candidate->name, 0, 2) }}
                                            </div>
                                        @endif
                                        
                                        <div class="min-w-0">
                                            <h4 class="font-bold text-sm text-slate-900 dark:text-white truncate">
                                                {{ $candidate->name }}
                                            </h4>
                                            <p class="text-xs text-slate-500 dark:text-slate-400 truncate">
                                                {{ $candidate->party_affiliation ?? 'Independent' }}
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Candidate Controls -->
                                    <div class="flex items-center gap-1 opacity-0 group-hover/card:opacity-100 transition">
                                        <button onclick="openEditCandidateModal({{ json_encode($candidate) }})" class="p-1.5 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg text-slate-500 hover:text-slate-800 dark:hover:text-slate-200 transition" title="Edit Candidate">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </button>
                                        <button onclick="deleteCandidate({{ $candidate->id }})" class="p-1.5 hover:bg-rose-50 dark:hover:bg-rose-950/20 rounded-lg text-slate-500 hover:text-rose-600 dark:hover:text-rose-400 transition" title="Delete Candidate">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </div>
                                </div>
                            @empty
                                <div class="col-span-full py-4 text-center text-xs text-slate-400 italic" id="candidates-empty-{{ $position->id }}">
                                    No candidates added yet. Click "+ Add Candidate" to configure roles.
                                </div>
                            @endforelse
                        </div>
                    </div>
                @empty
                    <div class="bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-200/60 dark:border-slate-800/80 p-12 text-center max-w-xl mx-auto" id="no-positions-empty">
                        <div class="w-12 h-12 bg-slate-50 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        </div>
                        <h4 class="font-bold text-slate-900 dark:text-white">No Positions Defined</h4>
                        <p class="text-xs text-slate-500 mt-1">Start building the ballot by adding the first position (e.g. Chairman).</p>
                        <button onclick="openCreatePositionModal()" class="mt-4 text-xs font-semibold bg-purple-600 text-white py-2 px-4 rounded-xl hover:bg-purple-700 transition">
                            Create First Position
                        </button>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- PANEL B: RESULTS (Live Results & Analytics) -->
        <div id="panel-results" class="manage-tab-panel hidden space-y-6">
            <div class="flex items-center justify-between gap-4 flex-wrap">
                <div>
                    <h2 class="text-xl font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        Election Results Overview
                        @if($election->status === 'active')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/10 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 dark:bg-emerald-400 animate-pulse"></span>
                                Live Count
                            </span>
                        @endif
                    </h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Real-time tally of all cast ballots and candidate positions.</p>
                </div>

                <a href="{{ route('committees.election.export', $election->id) }}" class="inline-flex items-center gap-1.5 bg-slate-900 hover:bg-slate-800 dark:bg-slate-800 dark:hover:bg-slate-700 text-white dark:text-slate-200 font-semibold text-xs px-4 py-2.5 rounded-xl shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    Export CSV Report
                </a>
            </div>

            <!-- List of Results per Position -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @forelse($positions as $position)
                    <div class="bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-200/60 dark:border-slate-800/80 p-6 sm:p-8 shadow-sm space-y-5">
                        <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-3">
                            <h3 class="font-bold text-slate-900 dark:text-white text-base">
                                {{ $position->name }}
                            </h3>
                            <span class="text-xs text-slate-400 dark:text-slate-500 font-semibold">
                                Total Votes: {{ $position->votes_count }}
                            </span>
                        </div>

                        <div class="space-y-4">
                            @php $totalPositionVotes = $position->votes_count; @endphp
                            @forelse($position->candidates as $candidate)
                                @php
                                    $votes = $candidate->votes_count;
                                    $percent = $totalPositionVotes > 0 ? round(($votes / $totalPositionVotes) * 100, 1) : 0;
                                @endphp
                                <div class="space-y-1">
                                    <div class="flex items-center justify-between text-xs">
                                        <div class="font-semibold text-slate-700 dark:text-slate-300">
                                            {{ $candidate->name }} 
                                            <span class="text-[10px] text-slate-400 font-medium">({{ $candidate->party_affiliation ?? 'Independent' }})</span>
                                        </div>
                                        <div class="font-bold text-slate-900 dark:text-white">
                                            {{ $votes }} {{ Str::plural('vote', $votes) }} ({{ $percent }}%)
                                        </div>
                                    </div>
                                    <!-- Beautiful Progress Bar -->
                                    <div class="w-full bg-slate-100 dark:bg-slate-850 rounded-full h-2.5 overflow-hidden">
                                        <div class="bg-gradient-to-r from-purple-500 to-indigo-600 h-2.5 rounded-full transition-all duration-1000" style="width: {{ $percent }}%"></div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center text-xs text-slate-400 italic py-2">
                                    No candidates declared for this position.
                                </div>
                            @endforelse
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-200/60 dark:border-slate-800/80 p-12 text-center">
                        <p class="text-xs text-slate-400 italic">No ballot positions or candidates have been created yet to measure results.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- PANEL C: VOTERS (Voters & Analytics) -->
        <div id="panel-voters" class="manage-tab-panel hidden space-y-6">
            <div class="flex items-center justify-between gap-4">
                <h2 class="text-xl font-bold text-slate-900 dark:text-white">Voter Register</h2>
                <div class="text-xs font-semibold px-3 py-1 rounded-full bg-purple-500/10 text-purple-600 dark:text-purple-400">
                    Total: <span id="voter-count-display">{{ $voters->count() }}</span>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-[2rem] overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-100 dark:border-slate-800 text-slate-500 dark:text-slate-400 text-xs font-bold uppercase tracking-wider">
                                <th class="px-6 py-4.5">Voter Name</th>
                                <th class="px-6 py-4.5">Email Account</th>
                                <th class="px-6 py-4.5">Division / Workplace</th>
                                <th class="px-6 py-4.5">Corporate Position</th>
                                <th class="px-6 py-4.5">Voted Status</th>
                                <th class="px-6 py-4.5">Submission Time</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-sm text-slate-700 dark:text-slate-300">
                            @forelse($voters as $voter)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition duration-150">
                                    <td class="px-6 py-4.5 font-semibold text-slate-900 dark:text-white">
                                        {{ $voter->user->name }}
                                    </td>
                                    <td class="px-6 py-4.5 text-slate-500 dark:text-slate-400">
                                        {{ $voter->user->email }}
                                    </td>
                                    <td class="px-6 py-4.5">
                                        {{ $voter->division ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4.5">
                                        {{ $voter->current_position ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4.5">
                                        @if($voter->voted_at)
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400">
                                                Voted
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-500/10 text-amber-600 dark:text-amber-400">
                                                In Queue
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4.5 text-xs text-slate-400">
                                        {{ $voter->voted_at ? $voter->voted_at->format('M d, Y • h:i A') : '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-slate-400 italic">
                                        No voters have entered or cast votes in this election yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modals -->
@include('committees.election-app.components.create_position_modal')
@include('committees.election-app.components.edit_position_modal')
@include('committees.election-app.components.create_candidate_modal')
@include('committees.election-app.components.edit_candidate_modal')
@include('committees.election-app.components.edit_modal')

@endsection

@section('scripts')
<script>
    // --- TAB UTILS ---
    function switchManageTab(tabId) {
        document.querySelectorAll('.manage-tab-panel').forEach(panel => {
            panel.classList.add('hidden');
        });
        document.querySelectorAll('.manage-tab-btn').forEach(btn => {
            btn.className = "manage-tab-btn border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 border-b-2 py-4 px-1 text-sm focus:outline-none whitespace-nowrap";
        });

        document.getElementById('panel-' + tabId).classList.remove('hidden');
        document.getElementById('tab-btn-' + tabId).className = "manage-tab-btn border-purple-600 dark:border-purple-500 text-purple-600 dark:text-purple-400 font-bold border-b-2 py-4 px-1 text-sm focus:outline-none whitespace-nowrap";
    }

    // --- POSITION MODALS ---
    function openCreatePositionModal() {
        const modal = document.getElementById('create-position-modal');
        const content = document.getElementById('create-position-modal-content');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        requestAnimationFrame(() => {
            modal.classList.remove('opacity-0');
            modal.classList.add('opacity-100');
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        });
    }

    function closeCreatePositionModal() {
        const modal = document.getElementById('create-position-modal');
        const content = document.getElementById('create-position-modal-content');
        content.classList.add('scale-95', 'opacity-0');
        modal.classList.add('opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.getElementById('create-position-form').reset();
        }, 300);
    }

    function openEditPositionModal(position) {
        const modal = document.getElementById('edit-position-modal');
        const content = document.getElementById('edit-position-modal-content');
        
        document.getElementById('edit_position_id').value = position.id;
        document.getElementById('edit_pos_name').value = position.name;
        document.getElementById('edit_pos_max_votes').value = position.max_votes;
        document.getElementById('edit_pos_sort_order').value = position.sort_order;

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        requestAnimationFrame(() => {
            modal.classList.remove('opacity-0');
            modal.classList.add('opacity-100');
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        });
    }

    function closeEditPositionModal() {
        const modal = document.getElementById('edit-position-modal');
        const content = document.getElementById('edit-position-modal-content');
        content.classList.add('scale-95', 'opacity-0');
        modal.classList.add('opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.getElementById('edit-position-form').reset();
        }, 300);
    }

    // --- CANDIDATE MODALS ---
    function openCreateCandidateModal(positionId, positionName) {
        const modal = document.getElementById('create-candidate-modal');
        const content = document.getElementById('create-candidate-modal-content');
        
        document.getElementById('add_candidate_position_id').value = positionId;
        document.getElementById('add_candidate_position_name').textContent = positionName;

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        requestAnimationFrame(() => {
            modal.classList.remove('opacity-0');
            modal.classList.add('opacity-100');
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        });
    }

    function closeCreateCandidateModal() {
        const modal = document.getElementById('create-candidate-modal');
        const content = document.getElementById('create-candidate-modal-content');
        content.classList.add('scale-95', 'opacity-0');
        modal.classList.add('opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.getElementById('create-candidate-form').reset();
        }, 300);
    }

    function openEditCandidateModal(candidate) {
        const modal = document.getElementById('edit-candidate-modal');
        const content = document.getElementById('edit-candidate-modal-content');
        
        document.getElementById('edit_candidate_id').value = candidate.id;
        document.getElementById('edit_cand_name').value = candidate.name;
        document.getElementById('edit_cand_party').value = candidate.party_affiliation || '';
        document.getElementById('edit_cand_avatar').value = candidate.avatar_path || '';
        document.getElementById('edit_cand_sort_order').value = candidate.sort_order;

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        requestAnimationFrame(() => {
            modal.classList.remove('opacity-0');
            modal.classList.add('opacity-100');
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        });
    }

    function closeEditCandidateModal() {
        const modal = document.getElementById('edit-candidate-modal');
        const content = document.getElementById('edit-candidate-modal-content');
        content.classList.add('scale-95', 'opacity-0');
        modal.classList.add('opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.getElementById('edit-candidate-form').reset();
        }, 300);
    }

    // Modal background click dismiss handlers
    document.getElementById('create-position-modal').onclick = function(e) { if(e.target===this) closeCreatePositionModal(); };
    document.getElementById('edit-position-modal').onclick = function(e) { if(e.target===this) closeEditPositionModal(); };
    document.getElementById('create-candidate-modal').onclick = function(e) { if(e.target===this) closeCreateCandidateModal(); };
    document.getElementById('edit-candidate-modal').onclick = function(e) { if(e.target===this) closeEditCandidateModal(); };
    document.getElementById('edit-election-modal').onclick = function(e) { if(e.target===this) closeEditModal(); };

    // --- POSITIONS AJAX ACTIONS ---
    function submitCreatePosition(event) {
        event.preventDefault();
        const form = event.target;
        const submitBtn = form.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.textContent = 'Adding...';

        const formData = new FormData(form);

        fetch(`/committees/election-app/{{ $election->id }}/positions`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(res => {
            if (res.success) {
                window.showToast(res.message, 'success');
                closeCreatePositionModal();
                setTimeout(() => window.location.reload(), 800);
            } else {
                window.showToast(res.message || 'Error occurred.', 'error');
            }
            submitBtn.disabled = false;
            submitBtn.textContent = 'Add Position';
        })
        .catch(err => {
            console.error(err);
            window.showToast('Connection error.', 'error');
            submitBtn.disabled = false;
            submitBtn.textContent = 'Add Position';
        });
    }

    function submitEditPosition(event) {
        event.preventDefault();
        const form = event.target;
        const submitBtn = form.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.textContent = 'Saving...';

        const id = document.getElementById('edit_position_id').value;
        const formData = new FormData(form);

        fetch(`/committees/election-app/positions/${id}`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(res => {
            if (res.success) {
                window.showToast(res.message, 'success');
                closeEditPositionModal();
                setTimeout(() => window.location.reload(), 800);
            } else {
                window.showToast(res.message || 'Error occurred.', 'error');
            }
            submitBtn.disabled = false;
            submitBtn.textContent = 'Save Changes';
        })
        .catch(err => {
            console.error(err);
            window.showToast('Connection error.', 'error');
            submitBtn.disabled = false;
            submitBtn.textContent = 'Save Changes';
        });
    }

    function deletePosition(id) {
        window.showConfirmModal(
            'Delete Position',
            'Are you sure you want to delete this position?',
            'This will permanently delete this position and all candidates listed under it. Voters will no longer be able to select candidates for this role.',
            () => {
                fetch(`/committees/election-app/positions/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(res => {
                    if (res.success) {
                        window.showToast(res.message, 'success');
                        document.getElementById(`position-block-${id}`).remove();
                        if (document.querySelectorAll('[id^="position-block-"]').length === 0) {
                            window.location.reload();
                        }
                    } else {
                        window.showToast(res.message || 'Error occurred.', 'error');
                    }
                })
                .catch(err => {
                    console.error(err);
                    window.showToast('Connection error.', 'error');
                });
            }
        );
    }

    // --- CANDIDATES AJAX ACTIONS ---
    function submitCreateCandidate(event) {
        event.preventDefault();
        const form = event.target;
        const submitBtn = form.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.textContent = 'Adding...';

        const positionId = document.getElementById('add_candidate_position_id').value;
        const formData = new FormData(form);

        fetch(`/committees/election-app/positions/${positionId}/candidates`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(res => {
            if (res.success) {
                window.showToast(res.message, 'success');
                closeCreateCandidateModal();
                setTimeout(() => window.location.reload(), 800);
            } else {
                window.showToast(res.message || 'Error occurred.', 'error');
            }
            submitBtn.disabled = false;
            submitBtn.textContent = 'Add Candidate';
        })
        .catch(err => {
            console.error(err);
            window.showToast('Connection error.', 'error');
            submitBtn.disabled = false;
            submitBtn.textContent = 'Add Candidate';
        });
    }

    function submitEditCandidate(event) {
        event.preventDefault();
        const form = event.target;
        const submitBtn = form.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.textContent = 'Saving...';

        const id = document.getElementById('edit_candidate_id').value;
        const formData = new FormData(form);

        fetch(`/committees/election-app/candidates/${id}`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(res => {
            if (res.success) {
                window.showToast(res.message, 'success');
                closeEditCandidateModal();
                setTimeout(() => window.location.reload(), 800);
            } else {
                window.showToast(res.message || 'Error occurred.', 'error');
            }
            submitBtn.disabled = false;
            submitBtn.textContent = 'Save Changes';
        })
        .catch(err => {
            console.error(err);
            window.showToast('Connection error.', 'error');
            submitBtn.disabled = false;
            submitBtn.textContent = 'Save Changes';
        });
    }

    function deleteCandidate(id) {
        window.showConfirmModal(
            'Delete Candidate',
            'Are you sure you want to delete this candidate?',
            'This will permanently remove the candidate from the ballot.',
            () => {
                fetch(`/committees/election-app/candidates/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(res => {
                    if (res.success) {
                        window.showToast(res.message, 'success');
                        document.getElementById(`candidate-card-${id}`).remove();
                    } else {
                        window.showToast(res.message || 'Error occurred.', 'error');
                    }
                })
                .catch(err => {
                    console.error(err);
                    window.showToast('Connection error.', 'error');
                });
            }
        );
    }

    // --- ELECTION DIRECT ACTIONS ---
    function changeElectionStatus(newStatus) {
        const formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('_method', 'PUT');
        formData.append('status', newStatus);
        formData.append('title', '{{ addslashes($election->title) }}');
        formData.append('description', '{{ addslashes($election->description ?? "") }}');
        formData.append('start_date', '{{ $election->start_date ? $election->start_date->format("Y-m-d\TH:i") : "" }}');
        formData.append('end_date', '{{ $election->end_date ? $election->end_date->format("Y-m-d\TH:i") : "" }}');
        
        fetch(`/committees/election-app/{{ $election->id }}`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.showToast(`Election marked as ${newStatus} successfully!`, 'success');
                setTimeout(() => window.location.reload(), 800);
            } else {
                window.showToast(data.message || 'Status update failed.', 'error');
            }
        })
        .catch(err => {
            console.error(err);
            window.showToast('Connection error.', 'error');
        });
    }

    function openEditElectionModal() {
        const election = {
            id: '{{ $election->id }}',
            title: '{{ addslashes($election->title) }}',
            description: '{{ addslashes($election->description ?? "") }}',
            status: '{{ $election->status }}',
            start_date: '{{ $election->start_date ? $election->start_date->format("Y-m-d\TH:i") : "" }}',
            end_date: '{{ $election->end_date ? $election->end_date->format("Y-m-d\TH:i") : "" }}'
        };
        
        const modal = document.getElementById('edit-election-modal');
        const content = document.getElementById('edit-election-modal-content');
        
        document.getElementById('edit_election_id').value = election.id;
        document.getElementById('edit_title').value = election.title;
        document.getElementById('edit_description').value = election.description;
        document.getElementById('edit_status').value = election.status;
        document.getElementById('edit_start_date').value = election.start_date;
        document.getElementById('edit_end_date').value = election.end_date;

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        requestAnimationFrame(() => {
            modal.classList.remove('opacity-0');
            modal.classList.add('opacity-100');
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        });
    }

    function closeEditModal() {
        const modal = document.getElementById('edit-election-modal');
        const content = document.getElementById('edit-election-modal-content');
        
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        modal.classList.remove('opacity-100');
        modal.classList.add('opacity-0');
        
        setTimeout(() => {
            modal.classList.remove('flex');
            modal.classList.add('hidden');
            document.getElementById('edit-election-form').reset();
        }, 300);
    }

    function submitEditElection(event) {
        event.preventDefault();
        const form = event.target;
        const submitBtn = form.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.textContent = 'Saving...';

        const formData = new FormData(form);

        fetch(`/committees/election-app/{{ $election->id }}`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(res => {
            if (res.success) {
                window.showToast(res.message, 'success');
                closeEditModal();
                setTimeout(() => window.location.reload(), 800);
            } else {
                window.showToast(res.message || 'Failed to update election.', 'error');
                submitBtn.disabled = false;
                submitBtn.textContent = 'Save Changes';
            }
        })
        .catch(err => {
            console.error(err);
            window.showToast('Server connection failed.', 'error');
            submitBtn.disabled = false;
            submitBtn.textContent = 'Save Changes';
        });
    }

    function triggerDeleteElectionFromManage() {
        const currentStatus = '{{ $election->status }}';
        if (currentStatus === 'active') {
            window.showToast('Cannot delete an ongoing election. Please pause or close it first.', 'error');
            return;
        }

        window.showConfirmModal(
            'Delete Election',
            'Are you sure you want to delete this election?',
            'This will permanently delete this election and all of its associated positions, candidates, and votes. This action cannot be undone.',
            () => {
                fetch(`/committees/election-app/{{ $election->id }}`, {
                    method: 'DELETE',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(res => {
                    if (res.success) {
                        window.showToast(res.message, 'success');
                        setTimeout(() => {
                            window.location.href = "{{ route('committees.election.index') }}";
                        }, 800);
                    } else {
                        window.showToast(res.message || 'Failed to delete election.', 'error');
                    }
                })
                .catch(err => {
                    console.error(err);
                    window.showToast('Server connection failed.', 'error');
                });
            }
        );
    }

    function copyVotingLink() {
        const link = "{{ route('elections.voter.setup', $election->id) }}";
        navigator.clipboard.writeText(link)
            .then(() => {
                window.showToast('Voting link copied to clipboard!', 'success');
            })
            .catch(err => {
                console.error('Failed to copy text: ', err);
                window.showToast('Failed to copy link.', 'error');
            });
    }
</script>
@endsection
