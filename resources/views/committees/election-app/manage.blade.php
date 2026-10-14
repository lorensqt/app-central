@extends('layouts.app')

@section('title', 'Manage Election')

@section('content')
    <div class="space-y-8">
        <!-- Back to Dashboard Link -->
        <div>
            <a href="{{ route('committees.election.index') }}"
                class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 hover:text-purple-600 dark:hover:text-purple-400 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Elections
            </a>
        </div>

        <!-- Header Card -->
        <div
            class="relative overflow-hidden bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-200/80 dark:border-slate-800/80 p-8 sm:p-10 shadow-sm">
            <div class="absolute -top-24 -left-24 w-72 h-72 bg-purple-400/10 rounded-full blur-3xl pointer-events-none">
            </div>
            <div class="absolute -bottom-24 -right-24 w-72 h-72 bg-indigo-400/10 rounded-full blur-3xl pointer-events-none">
            </div>

            <div class="relative flex flex-col lg:flex-row lg:items-start lg:justify-between gap-8">
                <!-- Left Panel: Details -->
                <div class="space-y-3 flex-grow">
                    <div class="flex items-center gap-3 flex-wrap">
                        @php
                            $badgeClass = '';
                            if ($election->status === 'active') {
                                $badgeClass =
                                    'bg-emerald-500/10 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400';
                            } elseif ($election->status === 'draft') {
                                $badgeClass = 'bg-slate-500/10 text-slate-600 dark:bg-slate-500/10 dark:text-slate-400';
                            } else {
                                $badgeClass = 'bg-rose-500/10 text-rose-600 dark:bg-rose-500/10 dark:text-rose-400';
                            }
                        @endphp
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $badgeClass }}">
                            {{ $election->status }}
                        </span>

                        @if ($election->start_date || $election->end_date)
                            <span class="text-xs text-slate-400 dark:text-slate-500 font-medium flex items-center gap-1.5">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                {{ $election->start_date ? $election->start_date->format('M d, Y, h:i A') : 'Start' }} -
                                {{ $election->end_date ? $election->end_date->format('M d, Y, h:i A') : 'End' }}
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

                <!-- Right Panel: Election Performance Snippet -->
                <div
                    class="bg-gradient-to-br from-purple-500/5 to-indigo-500/5 dark:from-purple-500/10 dark:to-indigo-500/10 rounded-[2.5rem] border-2 border-purple-500/20 dark:border-purple-400/25 p-7 flex items-center gap-6 max-w-sm w-full lg:w-96 shrink-0 shadow-sm shadow-purple-500/5 dark:shadow-none self-stretch">
                    <!-- Circular Turnout representation (Enlarged and Crisp) -->
                    <div class="relative flex items-center justify-center shrink-0 w-20 h-20">
                        <!-- SVG Circle Progress (r=34 has Circumference of 213.6) -->
                        <svg class="w-full h-full transform -rotate-90">
                            <circle cx="40" cy="40" r="34" class="text-slate-200/60 dark:text-slate-800/80"
                                stroke-width="6.5" stroke="currentColor" fill="transparent"></circle>
                            <circle cx="40" cy="40" r="34"
                                class="text-purple-600 dark:text-purple-400 transition-all duration-500 ease-out"
                                stroke-width="7" stroke-dasharray="213.6"
                                stroke-dashoffset="{{ 213.6 - (213.6 * $stats['turnout_rate']) / 100 }}"
                                stroke-linecap="round" stroke="currentColor" fill="transparent"></circle>
                        </svg>
                        <span
                            class="absolute text-sm font-black text-slate-800 dark:text-slate-100 leading-none">{{ $stats['turnout_rate'] }}%</span>
                    </div>
                    <div class="space-y-1">
                        <span
                            class="block text-[10px] font-black text-purple-600/80 dark:text-purple-400/85 uppercase tracking-widest leading-none">Turnout
                            Performance</span>
                        <span class="block text-2xl font-black text-slate-900 dark:text-white leading-none">
                            {{ $stats['voted_voters'] }} <span
                                class="text-slate-400 dark:text-slate-500 text-sm font-medium">/
                                {{ $stats['total_voters'] }}</span>
                        </span>
                        <span class="block text-xs text-slate-500 dark:text-slate-400 font-semibold leading-normal">Official
                            ballots cast</span>
                    </div>
                </div>
            </div>

            <!-- Action Control Bar -->
            <div
                class="border-t border-slate-200 dark:border-slate-800 pt-6 mt-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <!-- Left Side: Primary Reader & Quick Tools -->
                <div class="flex items-center gap-2.5 flex-wrap">
                    <a href="{{ route('elections.public_show', $election) }}" target="_blank"
                        class="inline-flex items-center justify-center gap-2 bg-purple-600 hover:bg-purple-700 active:scale-[0.98] text-white font-semibold text-xs px-5 py-2.5 rounded-xl shadow-sm transition duration-150">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                        View Voting Page
                    </a>

                    <button onclick="copyVotingLink()"
                        class="inline-flex items-center justify-center gap-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 font-semibold text-xs px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700/60 transition duration-150">
                        <svg class="w-4 h-4 text-slate-500 dark:text-slate-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                        </svg>
                        Copy Link
                    </button>
                </div>

                <!-- Right Side: State Changers & Overflow Options -->
                <div class="flex items-center gap-2.5 flex-wrap sm:ml-auto">
                    @if ($election->status === 'draft')
                        <button onclick="confirmElectionStatusChange('active')"
                            class="inline-flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 active:scale-[0.98] text-white font-semibold text-xs px-5 py-2.5 rounded-xl shadow-sm transition duration-150">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Start Election
                        </button>
                    @elseif($election->status === 'active')
                        <button onclick="confirmElectionStatusChange('closed')"
                            class="inline-flex items-center justify-center gap-2 bg-rose-600 hover:bg-rose-700 active:scale-[0.98] text-white font-semibold text-xs px-5 py-2.5 rounded-xl shadow-sm transition duration-150">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 10a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z" />
                            </svg>
                            End Election
                        </button>
                    @elseif($election->status === 'closed')
                        <button onclick="confirmElectionStatusChange('draft')"
                            class="inline-flex items-center justify-center gap-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 font-semibold text-xs px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700/60 transition duration-150">
                            <svg class="w-4 h-4 text-slate-500 dark:text-slate-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                            </svg>
                            Revert to Draft
                        </button>
                    @endif

                    <!-- Advanced / More Actions Dropdown -->
                    <div class="relative inline-block text-left" id="more-actions-container">
                        <button type="button" onclick="toggleMoreActionsDropdown(event)" id="more-actions-btn"
                            class="inline-flex items-center justify-center gap-1.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 font-semibold text-xs px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700/60 transition duration-150">
                            More Actions
                            <svg class="w-3.5 h-3.5 text-slate-500 dark:text-slate-400" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <!-- Dropdown Panel -->
                        <div id="more-actions-dropdown"
                            class="hidden absolute right-0 bottom-full mb-2 w-56 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-xl z-50 overflow-hidden divide-y divide-slate-100 dark:divide-slate-800/60">
                            <!-- Configurations -->
                            <div class="py-1">
                                <button onclick="openEditElectionModal(); closeMoreActionsDropdown()"
                                    class="w-full flex items-center gap-2.5 px-3.5 py-2 text-xs text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 text-left font-medium transition">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                    Edit Election Details
                                </button>

                                @if ($election->status === 'active')
                                    <button onclick="confirmElectionStatusChange('draft'); closeMoreActionsDropdown()"
                                        class="w-full flex items-center gap-2.5 px-3.5 py-2 text-xs text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 text-left font-medium transition">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                                        </svg>
                                        Pause / Revert to Draft
                                    </button>
                                @endif
                            </div>

                            <!-- Data Exports -->
                            <div class="py-1">
                                <button onclick="confirmCSVDownload(); closeMoreActionsDropdown()"
                                    class="w-full flex items-center gap-2.5 px-3.5 py-2 text-xs text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 text-left font-medium transition">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                    Export Results CSV
                                </button>
                                <button onclick="confirmVoterCSVDownload(); closeMoreActionsDropdown()"
                                    class="w-full flex items-center gap-2.5 px-3.5 py-2 text-xs text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 text-left font-medium transition">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Export Voter Registry CSV
                                </button>
                                <a href="{{ route('committees.election.export_pdf', $election->id) }}" target="_blank"
                                    onclick="closeMoreActionsDropdown()"
                                    class="w-full flex items-center gap-2.5 px-3.5 py-2 text-xs text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 text-left font-medium transition">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                    Export PDF Report
                                </a>
                            </div>

                            <!-- Destructive Action -->
                            <div class="py-1">
                                <button onclick="triggerDeleteElectionFromManage(); closeMoreActionsDropdown()"
                                    class="w-full flex items-center gap-2.5 px-3.5 py-2 text-xs text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/40 text-left font-semibold transition">
                                    <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Delete Election
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Premium Glassmorphic Segmented Tabs Navigation Row -->
        <div id="manage-tabs-nav-container" class="relative flex items-center bg-slate-100/90 dark:bg-slate-950 p-1.5 rounded-2xl border border-slate-200 dark:border-slate-800/80 custom-scrollbar overflow-x-auto select-none w-full gap-1.5 shadow-[0_2px_8px_rgba(15,23,42,0.02)]">
            <!-- Dynamic sliding active tab indicator -->
            <div id="manage-tab-active-indicator" class="absolute bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-[0_4px_12px_rgba(15,23,42,0.06)] rounded-xl transition-all duration-300 ease-out z-0 pointer-events-none"></div>

            <!-- Tab 1 Button: Positions & Candidates -->
            <button onclick="switchManageTab('structure')" id="tab-btn-structure"
                class="manage-tab-btn relative z-10 px-5 py-3 rounded-xl text-purple-600 dark:text-purple-400 font-extrabold text-xs transition duration-200 flex items-center gap-2.5 focus:outline-none whitespace-nowrap">
                <i class="fa-solid fa-layer-group text-xs"></i>
                <span>Positions & Candidates</span>
                <span class="px-2 py-0.5 bg-purple-100 dark:bg-purple-950 text-purple-700 dark:text-purple-400 text-[10px] font-extrabold rounded-full transition duration-200">
                    {{ $positions->count() }}
                </span>
            </button>

            <!-- Tab 2 Button: Election Results -->
            <button onclick="switchManageTab('results')" id="tab-btn-results"
                class="manage-tab-btn relative z-10 px-5 py-3 rounded-xl text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-white/50 dark:hover:bg-slate-900/40 font-semibold text-xs transition duration-200 flex items-center gap-2.5 focus:outline-none whitespace-nowrap">
                <i class="fa-solid fa-square-poll-vertical text-xs"></i>
                <span>Election Results</span>
                @if($election->status === 'active')
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                @endif
            </button>

            <!-- Tab 3 Button: Voters & Analytics -->
            <button onclick="switchManageTab('voters')" id="tab-btn-voters"
                class="manage-tab-btn relative z-10 px-5 py-3 rounded-xl text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-white/50 dark:hover:bg-slate-900/40 font-semibold text-xs transition duration-200 flex items-center gap-2.5 focus:outline-none whitespace-nowrap">
                <i class="fa-solid fa-address-card text-xs"></i>
                <span>Voters & Analytics</span>
                <span id="tab-voters-badge-count" class="px-2 py-0.5 bg-slate-200 dark:bg-slate-800 text-slate-600 dark:text-slate-400 text-[10px] font-extrabold rounded-full transition duration-200">
                    {{ number_format($voters->total()) }}
                </span>
            </button>

            <!-- Tab 4 Button: Reports & Statistics -->
            <button onclick="switchManageTab('statistics')" id="tab-btn-statistics"
                class="manage-tab-btn relative z-10 px-5 py-3 rounded-xl text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-white/50 dark:hover:bg-slate-900/40 font-semibold text-xs transition duration-200 flex items-center gap-2.5 focus:outline-none whitespace-nowrap">
                <i class="fa-solid fa-chart-pie text-xs"></i>
                <span>Reports & Statistics</span>
            </button>
        </div>

        <!-- Tab Contents Workspace -->
        <div>
            @include('committees.election-app.manage-elections.structure')
            @include('committees.election-app.manage-elections.results')
            @include('committees.election-app.manage-elections.voters')
            @include('committees.election-app.manage-elections.statistics')
        </div>
    </div>

    <!-- Modals -->
    @include('committees.election-app.components.create_position_modal')
    @include('committees.election-app.components.edit_position_modal')
    @include('committees.election-app.components.create_candidate_modal')
    @include('committees.election-app.components.edit_candidate_modal')
    @include('committees.election-app.components.edit_modal')
    @include('committees.election-app.components.create_voter_modal')
    @include('committees.election-app.components.edit_voter_modal')
    @include('committees.election-app.components.view_voter_modal')

@endsection

@section('scripts')
    <script>
        // --- VOTERS AJAX FILTERING, PAGINATION & CUSTOM DROPDOWNS ---
        let fetchVotersTimeout = null;
        let currentVoterPage = 1;

        function fetchFilteredVoters(page = 1) {
            currentVoterPage = page;
            clearTimeout(fetchVotersTimeout);
            fetchVotersTimeout = setTimeout(() => {
                const searchInput = document.getElementById('voter-filter-search');
                const search = searchInput ? searchInput.value.trim() : '';
                const division = document.getElementById('voter-filter-division') ? document.getElementById('voter-filter-division').value : 'all';
                const gender = document.getElementById('voter-filter-gender') ? document.getElementById('voter-filter-gender').value : 'all';
                const status = document.getElementById('voter-filter-status') ? document.getElementById('voter-filter-status').value : 'all';
                const sort = document.getElementById('voter-filter-sort') ? document.getElementById('voter-filter-sort').value : 'desc';

                const url = new URL(`/committees/election-app/{{ $election->id }}/voters`, window.location.origin);
                if (search) url.searchParams.append('search', search);
                if (division && division !== 'all') url.searchParams.append('division', division);
                if (gender && gender !== 'all') url.searchParams.append('gender', gender);
                if (status && status !== 'all') url.searchParams.append('status', status);
                if (sort) url.searchParams.append('sort', sort);
                url.searchParams.append('page', currentVoterPage);

                const tbody = document.getElementById('voters-table-body');
                const countDisplay = document.getElementById('voter-count-display');
                const pageFrom = document.getElementById('voters-page-from');
                const pageTo = document.getElementById('voters-page-to');
                const pageTotal = document.getElementById('voters-page-total');
                const tableCard = document.getElementById('voters-table-card');

                if (tbody) tbody.classList.add('opacity-40');

                fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(res => {
                        if (tbody) tbody.classList.remove('opacity-40');
                        if (res.success) {
                            const total = res.pagination ? res.pagination.total : res.voters.length;
                            if (countDisplay) countDisplay.textContent = total.toLocaleString();
                            const tabBadge = document.getElementById('tab-voters-badge-count');
                            if (tabBadge) tabBadge.textContent = total.toLocaleString();
                            if (pageFrom) pageFrom.textContent = res.pagination ? (res.pagination.from || 0).toLocaleString() : (res.voters.length > 0 ? '1' : '0');
                            if (pageTo) pageTo.textContent = res.pagination ? (res.pagination.to || 0).toLocaleString() : res.voters.length.toLocaleString();
                            if (pageTotal) pageTotal.textContent = total.toLocaleString();

                            if (res.voters.length === 0) {
                                tbody.innerHTML = `
                                    <tr>
                                        <td colspan="7" class="px-6 py-12 text-center text-slate-400 dark:text-slate-500 italic">
                                            No voters match the specified criteria.
                                        </td>
                                    </tr>
                                `;
                            } else {
                                tbody.innerHTML = res.voters.map(voter => {
                                    const badgeClass = voter.status === 'Voted' ?
                                        'bg-emerald-500/10 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400' :
                                        'bg-amber-500/10 text-amber-600 dark:bg-amber-500/10 dark:text-amber-400';

                                    const safeVoter = {
                                        id: voter.id,
                                        name: voter.name,
                                        raw_name: voter.raw_name,
                                        email: voter.email,
                                        division: voter.raw_division || (voter.division !== '-' ? voter.division : ''),
                                        gender: voter.raw_gender || (voter.gender !== '-' ? voter.gender : ''),
                                        age: voter.raw_age || (voter.age !== '-' ? voter.age : ''),
                                        status: voter.is_voted ? 'voted' : 'in_queue'
                                    };
                                    const voterJson = JSON.stringify(safeVoter).replace(/"/g, '&quot;');
                                    const safeName = (voter.name || '').replace(/'/g, "\\'");
                                    const initials = (voter.name || 'V').substring(0, 2).toUpperCase();

                                    return `
                                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition duration-150" id="voter-row-${voter.id}">
                                            <td class="px-6 py-4.5 font-semibold text-slate-800 dark:text-slate-200">
                                                <div class="flex items-center gap-3">
                                                    <span class="inline-flex items-center justify-center w-8.5 h-8.5 rounded-xl bg-purple-50 dark:bg-purple-950/40 text-purple-600 dark:text-purple-400 font-bold text-xs uppercase shrink-0">
                                                        ${initials}
                                                    </span>
                                                    <div class="text-left min-w-0">
                                                        <span class="block text-slate-900 dark:text-white font-bold text-xs leading-tight truncate">
                                                            ${voter.name}
                                                        </span>
                                                        <span class="block text-[11px] text-slate-400 dark:text-slate-500 font-medium mt-0.5 leading-none truncate">
                                                            ${voter.email}
                                                        </span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4.5">
                                                ${voter.division}
                                            </td>
                                            <td class="px-6 py-4.5">
                                                ${voter.gender}
                                            </td>
                                            <td class="px-6 py-4.5">
                                                ${voter.age}
                                            </td>
                                            <td class="px-6 py-4.5">
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold ${badgeClass}">
                                                    ${voter.status}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4.5 text-xs text-slate-400 dark:text-slate-500">
                                                ${voter.voted_at_formatted}
                                            </td>
                                            <td class="px-6 py-4.5 text-right whitespace-nowrap">
                                                <div class="flex items-center justify-end gap-1.5">
                                                    <button type="button" onclick="openViewVoterModal(${voter.id})"
                                                        class="text-[11px] font-bold text-purple-600 dark:text-purple-400 hover:text-white dark:hover:text-slate-900 hover:bg-purple-600 dark:hover:bg-purple-600 bg-purple-500/5 dark:bg-purple-400/10 border border-purple-500/20 px-2.5 py-1.5 rounded-xl transition duration-150 active:scale-95 flex items-center gap-1 shadow-2xs"
                                                        title="View Voter Details & Ballot Audit">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                        <span>View</span>
                                                    </button>

                                                    <button type="button" onclick="openEditVoterModal(${voterJson})"
                                                        class="text-[11px] font-bold text-amber-600 dark:text-amber-400 hover:text-white dark:hover:text-slate-900 hover:bg-amber-500 dark:hover:bg-amber-500 bg-amber-500/5 dark:bg-amber-400/10 border border-amber-500/20 px-2.5 py-1.5 rounded-xl transition duration-150 active:scale-95 flex items-center gap-1 shadow-2xs"
                                                        title="Edit Voter">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                        <span>Edit</span>
                                                    </button>

                                                    <button type="button" onclick="deleteVoter(${voter.id}, '${safeName}')"
                                                        class="text-[11px] font-bold text-rose-600 dark:text-rose-400 hover:text-white hover:bg-rose-600 dark:hover:bg-rose-600 bg-rose-500/5 dark:bg-rose-400/10 border border-rose-500/20 px-2.5 py-1.5 rounded-xl transition duration-150 active:scale-95 flex items-center gap-1 shadow-2xs"
                                                        title="Delete Voter">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                        <span>Delete</span>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    `;
                                }).join('');
                            }

                            if (res.pagination) {
                                renderPaginationButtons(res.pagination);
                            }

                            if (currentVoterPage > 1 && tableCard) {
                                tableCard.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                            }
                        } else {
                            window.showToast('Failed to fetch voter list.', 'error');
                        }
                    })
                    .catch(err => {
                        if (tbody) tbody.classList.remove('opacity-40');
                        console.error(err);
                        window.showToast('Connection error while fetching voters.', 'error');
                    });
            }, 250);
        }

        function renderPaginationButtons(pagination) {
            currentVoterPage = pagination.current_page;
            const totalPages = pagination.last_page;
            const container = document.getElementById('voters-pagination-buttons');
            if (!container) return;

            if (totalPages <= 1) {
                container.innerHTML = '';
                return;
            }

            let html = '';

            // Previous Button
            const prevDisabled = currentVoterPage <= 1;
            html += `
                <button type="button" onclick="fetchFilteredVoters(${currentVoterPage - 1})" ${prevDisabled ? 'disabled' : ''}
                    class="px-3 py-1.5 rounded-xl text-xs font-semibold border transition ${prevDisabled ? 'opacity-40 cursor-not-allowed border-slate-200 dark:border-slate-800 text-slate-400 dark:text-slate-600 bg-white dark:bg-slate-900' : 'border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-900 hover:bg-purple-50 dark:hover:bg-purple-950/30 hover:border-purple-400 hover:text-purple-600 dark:hover:text-purple-400'}">
                    <i class="fa-solid fa-chevron-left text-[10px] mr-1"></i> Prev
                </button>
            `;

            // Smart window calculation
            const delta = 2;
            const range = [];
            const rangeWithDots = [];
            let l;

            for (let i = 1; i <= totalPages; i++) {
                if (i === 1 || i === totalPages || (i >= currentVoterPage - delta && i <= currentVoterPage + delta)) {
                    range.push(i);
                }
            }

            for (let i of range) {
                if (l) {
                    if (i - l === 2) {
                        rangeWithDots.push(l + 1);
                    } else if (i - l !== 1) {
                        rangeWithDots.push('...');
                    }
                }
                rangeWithDots.push(i);
                l = i;
            }

            for (let p of rangeWithDots) {
                if (p === '...') {
                    html += `<span class="px-2 py-1 text-xs text-slate-400 dark:text-slate-600 select-none">...</span>`;
                } else {
                    const isActive = p === currentVoterPage;
                    html += `
                        <button type="button" onclick="fetchFilteredVoters(${p})"
                            class="min-w-[32px] h-8 px-2 rounded-xl text-xs font-bold border transition ${isActive ? 'bg-purple-600 dark:bg-purple-600 text-white border-purple-600 shadow-sm shadow-purple-500/20' : 'bg-white dark:bg-slate-900 border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-300 hover:bg-purple-50 dark:hover:bg-purple-950/30 hover:border-purple-400 hover:text-purple-600 dark:hover:text-purple-400'}">
                            ${p}
                        </button>
                    `;
                }
            }

            // Next Button
            const nextDisabled = currentVoterPage >= totalPages;
            html += `
                <button type="button" onclick="fetchFilteredVoters(${currentVoterPage + 1})" ${nextDisabled ? 'disabled' : ''}
                    class="px-3 py-1.5 rounded-xl text-xs font-semibold border transition ${nextDisabled ? 'opacity-40 cursor-not-allowed border-slate-200 dark:border-slate-800 text-slate-400 dark:text-slate-600 bg-white dark:bg-slate-900' : 'border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-900 hover:bg-purple-50 dark:hover:bg-purple-950/30 hover:border-purple-400 hover:text-purple-600 dark:hover:text-purple-400'}">
                    Next <i class="fa-solid fa-chevron-right text-[10px] ml-1"></i>
                </button>
            `;

            container.innerHTML = html;
        }

        // --- CUSTOM ANIMATED DROPDOWNS LOGIC ---
        function toggleVoterDropdown(name) {
            const menu = document.getElementById(`menu-filter-${name}`);
            const chevron = document.getElementById(`chevron-filter-${name}`);
            if (!menu) return;

            const isHidden = menu.classList.contains('hidden');
            closeAllVoterDropdowns();

            if (isHidden) {
                menu.classList.remove('hidden');
                requestAnimationFrame(() => {
                    menu.classList.remove('opacity-0', 'scale-95');
                    menu.classList.add('opacity-100', 'scale-100');
                    if (chevron) chevron.classList.add('rotate-180');
                });
            }
        }

        function closeAllVoterDropdowns() {
            ['division', 'gender', 'status', 'sort'].forEach(name => {
                const menu = document.getElementById(`menu-filter-${name}`);
                const chevron = document.getElementById(`chevron-filter-${name}`);
                if (menu && !menu.classList.contains('hidden')) {
                    menu.classList.remove('opacity-100', 'scale-100');
                    menu.classList.add('opacity-0', 'scale-95');
                    if (chevron) chevron.classList.remove('rotate-180');
                    setTimeout(() => menu.classList.add('hidden'), 200);
                }
            });
        }

        function selectVoterDropdownOption(name, value, label) {
            const input = document.getElementById(`voter-filter-${name}`);
            if (input) input.value = value;

            const labelEl = document.getElementById(`label-filter-${name}`);
            if (labelEl) labelEl.textContent = label;

            const options = document.querySelectorAll(`.voter-opt-${name}`);
            options.forEach(opt => {
                const optVal = opt.getAttribute('data-value');
                const checkIcon = opt.querySelector('.check-icon');
                if (optVal === value) {
                    opt.classList.add('bg-purple-50/70', 'dark:bg-purple-950/40', 'text-purple-600', 'dark:text-purple-400', 'font-semibold');
                    opt.classList.remove('text-slate-700', 'dark:text-slate-300', 'font-medium');
                    if (checkIcon) checkIcon.classList.remove('hidden');
                } else {
                    opt.classList.remove('bg-purple-50/70', 'dark:bg-purple-950/40', 'text-purple-600', 'dark:text-purple-400', 'font-semibold');
                    opt.classList.add('text-slate-700', 'dark:text-slate-300', 'font-medium');
                    if (checkIcon) checkIcon.classList.add('hidden');
                }
            });

            closeAllVoterDropdowns();
            fetchFilteredVoters(1);
        }

        // --- SEARCH INPUT HELPERS ---
        function onVoterSearchInput() {
            const input = document.getElementById('voter-filter-search');
            const clearBtn = document.getElementById('voter-search-clear-btn');
            if (clearBtn && input) {
                if (input.value.length > 0) {
                    clearBtn.classList.remove('hidden');
                } else {
                    clearBtn.classList.add('hidden');
                }
            }
            fetchFilteredVoters(1);
        }

        function clearVoterSearch() {
            const input = document.getElementById('voter-filter-search');
            const clearBtn = document.getElementById('voter-search-clear-btn');
            if (input) {
                input.value = '';
                if (clearBtn) clearBtn.classList.add('hidden');
                fetchFilteredVoters(1);
            }
        }

        // --- BACK TO TOP LOGIC ---
        function scrollVotersToTop() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        window.addEventListener('scroll', () => {
            const btn = document.getElementById('voters-back-to-top');
            if (!btn) return;
            if (window.scrollY > 350) {
                btn.classList.remove('opacity-0', 'pointer-events-none', 'translate-y-4');
                btn.classList.add('opacity-100', 'translate-y-0');
            } else {
                btn.classList.add('opacity-0', 'pointer-events-none', 'translate-y-4');
                btn.classList.remove('opacity-100', 'translate-y-0');
            }
        });

        // Close dropdowns on click outside or escape key
        document.addEventListener('click', (e) => {
            if (!e.target.closest('.custom-voter-dropdown')) {
                closeAllVoterDropdowns();
            }
        });
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                closeAllVoterDropdowns();
            }
        });

        // --- ANIMATED SEGMENTED TAB SWITCHING LOGIC ---
        let currentManageTab = 'structure';

        function positionActiveManageTabIndicator(targetTab = null) {
            const activeTab = targetTab || currentManageTab;
            const activeBtn = document.getElementById('tab-btn-' + activeTab);
            const indicator = document.getElementById('manage-tab-active-indicator');
            const container = document.getElementById('manage-tabs-nav-container');

            if (activeBtn && indicator && container) {
                const containerRect = container.getBoundingClientRect();
                const btnRect = activeBtn.getBoundingClientRect();

                indicator.style.left = (btnRect.left - containerRect.left + container.scrollLeft) + 'px';
                indicator.style.width = btnRect.width + 'px';
                indicator.style.height = btnRect.height + 'px';
                indicator.style.top = (btnRect.top - containerRect.top) + 'px';
            }
        }

        function switchManageTab(targetTab) {
            const tabs = ['structure', 'results', 'voters', 'statistics'];

            tabs.forEach(tabId => {
                const panel = document.getElementById('panel-' + tabId);
                const btn = document.getElementById('tab-btn-' + tabId);
                if (panel) panel.classList.add('hidden');
                if (btn) {
                    btn.className = "manage-tab-btn relative z-10 px-5 py-3 rounded-xl text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-white/50 dark:hover:bg-slate-900/40 font-semibold text-xs transition duration-200 flex items-center gap-2.5 focus:outline-none whitespace-nowrap";
                }
            });

            const activePanel = document.getElementById('panel-' + targetTab);
            const activeBtn = document.getElementById('tab-btn-' + targetTab);

            if (activePanel) activePanel.classList.remove('hidden');
            if (activeBtn) {
                activeBtn.className = "manage-tab-btn relative z-10 px-5 py-3 rounded-xl text-purple-600 dark:text-purple-400 font-extrabold text-xs transition duration-200 flex items-center gap-2.5 focus:outline-none whitespace-nowrap";
            }

            // Sync URL query param without reload
            const url = new URL(window.location);
            url.searchParams.set('tab', targetTab);
            window.history.replaceState({}, '', url);

            currentManageTab = targetTab;
            positionActiveManageTabIndicator(targetTab);
        }

        window.addEventListener('resize', () => positionActiveManageTabIndicator());

        document.addEventListener('DOMContentLoaded', () => {
            const urlParams = new URLSearchParams(window.location.search);
            const tab = urlParams.get('tab');
            if (tab && ['structure', 'results', 'voters', 'statistics'].includes(tab)) {
                switchManageTab(tab);
            } else {
                switchManageTab('structure');
            }
            setTimeout(() => positionActiveManageTabIndicator(), 80);
        });

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

        // --- POSITION MODALS ---
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
            
            // Set default preview placeholder
            const preview = document.getElementById('cand_avatar_preview');
            if (preview) {
                preview.src = 'https://api.dicebear.com/7.x/adventurer/svg?seed=placeholder';
            }

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
            document.getElementById('edit_cand_background').value = candidate.background || '';

            // Load candidate portrait preview
            const preview = document.getElementById('edit_cand_avatar_preview');
            if (preview) {
                preview.src = candidate.avatar_path || 'https://api.dicebear.com/7.x/adventurer/svg?seed=placeholder';
            }

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
        document.getElementById('create-position-modal').onclick = function(e) {
            if (e.target === this) closeCreatePositionModal();
        };
        document.getElementById('edit-position-modal').onclick = function(e) {
            if (e.target === this) closeEditPositionModal();
        };
        document.getElementById('create-candidate-modal').onclick = function(e) {
            if (e.target === this) closeCreateCandidateModal();
        };
        document.getElementById('edit-candidate-modal').onclick = function(e) {
            if (e.target === this) closeEditCandidateModal();
        };
        document.getElementById('edit-election-modal').onclick = function(e) {
            if (e.target === this) closeEditModal();
        };
        const createVoterModalEl = document.getElementById('create-voter-modal');
        if (createVoterModalEl) {
            createVoterModalEl.onclick = function(e) {
                if (e.target === this) closeCreateVoterModal();
            };
        }
        const editVoterModalEl = document.getElementById('edit-voter-modal');
        if (editVoterModalEl) {
            editVoterModalEl.onclick = function(e) {
                if (e.target === this) closeEditVoterModal();
            };
        }
        const viewVoterModalEl = document.getElementById('view-voter-modal');
        if (viewVoterModalEl) {
            viewVoterModalEl.onclick = function(e) {
                if (e.target === this) closeViewVoterModal();
            };
        }

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

            const loader = document.getElementById('create_avatar_loader');
            if (loader) loader.classList.remove('hidden');

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
                .then(response => {
                    return response.json().then(res => {
                        if (!response.ok) {
                            let errMsg = res.message || 'Validation failed.';
                            if (res.errors) {
                                errMsg = Object.values(res.errors).flat().join(' | ');
                            }
                            return { success: false, message: errMsg };
                        }
                        return res;
                    });
                })
                .then(res => {
                    if (loader) loader.classList.add('hidden');
                    if (res.success) {
                        window.showToast(res.message, 'success');
                        closeCreateCandidateModal();
                        setTimeout(() => window.location.reload(), 800);
                    } else {
                        window.showToast(res.message, 'error');
                        submitBtn.disabled = false;
                        submitBtn.textContent = 'Add Candidate';
                    }
                })
                .catch(err => {
                    if (loader) loader.classList.add('hidden');
                    console.error(err);
                    window.showToast('Connection error.', 'error');
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Add Candidate';
                });
        }

        // --- CANDIDATES AJAX ACTIONS ---
        function submitEditCandidate(event) {
            event.preventDefault();
            const form = event.target;
            const submitBtn = form.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.textContent = 'Saving...';

            const loader = document.getElementById('edit_avatar_loader');
            if (loader) loader.classList.remove('hidden');

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
                .then(response => {
                    return response.json().then(res => {
                        if (!response.ok) {
                            let errMsg = res.message || 'Validation failed.';
                            if (res.errors) {
                                errMsg = Object.values(res.errors).flat().join(' | ');
                            }
                            return { success: false, message: errMsg };
                        }
                        return res;
                    });
                })
                .then(res => {
                    if (loader) loader.classList.add('hidden');
                    if (res.success) {
                        window.showToast(res.message, 'success');
                        closeEditCandidateModal();
                        setTimeout(() => window.location.reload(), 800);
                    } else {
                        window.showToast(res.message, 'error');
                        submitBtn.disabled = false;
                        submitBtn.textContent = 'Save Changes';
                    }
                })
                .catch(err => {
                    if (loader) loader.classList.add('hidden');
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
        function confirmElectionStatusChange(newStatus) {
            let title = '';
            let message = '';
            let description = '';

            if (newStatus === 'active') {
                title = 'Start Election';
                message = 'Are you sure you want to start this election?';
                description =
                    'This will open the public voting portal. Eligible voters will be able to cast their ballots in real-time.';
            } else if (newStatus === 'closed') {
                title = 'End Election';
                message = 'Are you sure you want to end this election?';
                description =
                    'This will close the public voting portal. No further ballots can be cast, and official winners will be certified.';
            } else if (newStatus === 'draft') {
                title = 'Revert to Draft / Pause';
                message = 'Are you sure you want to revert this election to draft?';
                description =
                    'This will pause or suspend the public voting portal. Voters will not be able to access the ballot booth while the election is in draft.';
            }

            window.showConfirmModal(
                title,
                message,
                description,
                () => {
                    executeElectionStatusChange(newStatus);
                }
            );
        }

        function executeElectionStatusChange(newStatus) {
            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('_method', 'PUT');
            formData.append('status', newStatus);
            formData.append('title', '{{ addslashes($election->title) }}');
            formData.append('description', '{{ addslashes($election->description ?? '') }}');
            formData.append('start_date',
            '{{ $election->start_date ? $election->start_date->format('Y-m-d\TH:i') : '' }}');
            formData.append('end_date', '{{ $election->end_date ? $election->end_date->format('Y-m-d\TH:i') : '' }}');

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
                description: '{{ addslashes($election->description ?? '') }}',
                status: '{{ $election->status }}',
                start_date: '{{ $election->start_date ? $election->start_date->format('Y-m-d\TH:i') : '' }}',
                end_date: '{{ $election->end_date ? $election->end_date->format('Y-m-d\TH:i') : '' }}'
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
            const link = "{{ route('elections.public_show', $election) }}";
            navigator.clipboard.writeText(link)
                .then(() => {
                    window.showToast('Voting link copied to clipboard!', 'success');
                })
                .catch(err => {
                    console.error('Failed to copy text: ', err);
                    window.showToast('Failed to copy link.', 'error');
                });
        }

        function confirmCSVDownload() {
            window.showConfirmModal(
                'Export CSV Tally Report',
                'Are you sure you want to download the CSV report?',
                'This will compile the active election results, vote totals, and share percentages, and download a secure spreadsheet file to your device.',
                () => {
                    window.location.href = "{{ route('committees.election.export', $election->id) }}";
                }
            );
        }

        // --- VOTER CRUD ACTIONS & MODALS ---
        let currentViewVoter = null;

        function openCreateVoterModal() {
            const modal = document.getElementById('create-voter-modal');
            const content = document.getElementById('create-voter-modal-content');
            const form = document.getElementById('create-voter-form');
            if (form) form.reset();

            modal.classList.remove('hidden');
            modal.classList.add('flex');
            requestAnimationFrame(() => {
                modal.classList.remove('opacity-0');
                modal.classList.add('opacity-100');
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            });
        }

        function closeCreateVoterModal() {
            const modal = document.getElementById('create-voter-modal');
            const content = document.getElementById('create-voter-modal-content');
            if (!modal || !content) return;
            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-95', 'opacity-0');
            modal.classList.remove('opacity-100');
            modal.classList.add('opacity-0');
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                const form = document.getElementById('create-voter-form');
                if (form) form.reset();
            }, 300);
        }

        function submitCreateVoter(event) {
            event.preventDefault();
            const form = event.target;
            const submitBtn = document.getElementById('btn-submit-create-voter');
            const originalText = submitBtn.innerHTML;

            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin text-xs"></i> Enrolling...';

            const formData = new FormData(form);

            fetch(`/committees/election-app/{{ $election->id }}/voters`, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => response.json().then(data => ({ status: response.status, body: data })))
            .then(({ status, body }) => {
                if (status === 200 || body.success) {
                    window.showToast(body.message || 'Voter enrolled successfully!', 'success');
                    closeCreateVoterModal();
                    fetchFilteredVoters(currentVoterPage);
                } else {
                    let errMsg = body.message || 'Validation failed.';
                    if (body.errors) {
                        const firstKey = Object.keys(body.errors)[0];
                        errMsg = body.errors[firstKey][0];
                    }
                    window.showToast(errMsg, 'error');
                }
            })
            .catch(err => {
                console.error(err);
                window.showToast('Network error while enrolling voter.', 'error');
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
        }

        function openEditVoterModal(voter) {
            const modal = document.getElementById('edit-voter-modal');
            const content = document.getElementById('edit-voter-modal-content');

            document.getElementById('edit_voter_id').value = voter.id;
            document.getElementById('edit_voter_name').value = voter.raw_name || voter.name || '';
            document.getElementById('edit_voter_email').value = voter.email || '';
            document.getElementById('edit_voter_division').value = voter.raw_division || (voter.division !== '-' ? voter.division : '') || '';
            document.getElementById('edit_voter_gender').value = voter.raw_gender || (voter.gender !== '-' ? voter.gender : '') || '';
            document.getElementById('edit_voter_age').value = voter.raw_age || (voter.age !== '-' ? voter.age : '') || '';
            
            const statusSelect = document.getElementById('edit_voter_status');
            const statusValue = (voter.status === 'voted' || voter.status === 'Voted' || voter.is_voted) ? 'voted' : 'in_queue';
            statusSelect.value = statusValue;
            onEditVoterStatusChange(statusValue);

            modal.classList.remove('hidden');
            modal.classList.add('flex');
            requestAnimationFrame(() => {
                modal.classList.remove('opacity-0');
                modal.classList.add('opacity-100');
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            });
        }

        function closeEditVoterModal() {
            const modal = document.getElementById('edit-voter-modal');
            const content = document.getElementById('edit-voter-modal-content');
            if (!modal || !content) return;
            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-95', 'opacity-0');
            modal.classList.remove('opacity-100');
            modal.classList.add('opacity-0');
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                const form = document.getElementById('edit-voter-form');
                if (form) form.reset();
            }, 300);
        }

        function onEditVoterStatusChange(value) {
            const warning = document.getElementById('edit-voter-reset-warning');
            if (!warning) return;
            if (value === 'in_queue') {
                warning.classList.remove('hidden');
            } else {
                warning.classList.add('hidden');
            }
        }

        function submitEditVoter(event) {
            event.preventDefault();
            const form = event.target;
            const voterId = document.getElementById('edit_voter_id').value;
            const submitBtn = document.getElementById('btn-submit-edit-voter');
            const originalText = submitBtn.innerHTML;

            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin text-xs"></i> Saving...';

            const formData = new FormData(form);

            fetch(`/committees/election-app/voters/${voterId}`, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => response.json().then(data => ({ status: response.status, body: data })))
            .then(({ status, body }) => {
                if (status === 200 || body.success) {
                    window.showToast(body.message || 'Voter updated successfully!', 'success');
                    closeEditVoterModal();
                    fetchFilteredVoters(currentVoterPage);
                } else {
                    let errMsg = body.message || 'Validation failed.';
                    if (body.errors) {
                        const firstKey = Object.keys(body.errors)[0];
                        errMsg = body.errors[firstKey][0];
                    }
                    window.showToast(errMsg, 'error');
                }
            })
            .catch(err => {
                console.error(err);
                window.showToast('Network error while updating voter.', 'error');
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
        }

        function openViewVoterModal(voterId) {
            const modal = document.getElementById('view-voter-modal');
            const content = document.getElementById('view-voter-modal-content');
            const loading = document.getElementById('view-voter-loading');
            const body = document.getElementById('view-voter-body');

            loading.classList.remove('hidden');
            body.classList.add('hidden');

            modal.classList.remove('hidden');
            modal.classList.add('flex');
            requestAnimationFrame(() => {
                modal.classList.remove('opacity-0');
                modal.classList.add('opacity-100');
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            });

            fetch(`/committees/election-app/voters/${voterId}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(res => {
                if (res.success && res.voter) {
                    const voter = res.voter;
                    currentViewVoter = voter;

                    document.getElementById('view-voter-name').textContent = voter.name;
                    document.getElementById('view-voter-email').textContent = voter.email;
                    document.getElementById('view-voter-division-badge').textContent = voter.division !== '-' ? voter.division : 'General';

                    const statusBadge = document.getElementById('view-voter-status-badge');
                    if (voter.is_voted) {
                        statusBadge.className = 'inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/10 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400';
                        statusBadge.innerHTML = '<span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Voted';
                    } else {
                        statusBadge.className = 'inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-500/10 text-amber-600 dark:bg-amber-500/10 dark:text-amber-400';
                        statusBadge.innerHTML = '<span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> In Queue';
                    }

                    document.getElementById('view-voter-gender').textContent = voter.gender !== '-' ? voter.gender : 'Unspecified';
                    document.getElementById('view-voter-age').textContent = voter.age !== '-' ? `${voter.age} yrs old` : 'Unspecified';
                    document.getElementById('view-voter-created-at').textContent = voter.created_at || 'Registered';
                    document.getElementById('view-voter-voted-at').textContent = voter.voted_at || 'Not yet cast';

                    const votesCount = document.getElementById('view-voter-votes-count');
                    const votesList = document.getElementById('view-voter-votes-list');

                    if (voter.votes && voter.votes.length > 0) {
                        votesCount.textContent = `${voter.votes.length} selection(s) recorded`;
                        votesList.innerHTML = voter.votes.map(v => `
                            <div class="p-2.5 rounded-xl bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800 flex items-center justify-between gap-3 text-xs">
                                <div>
                                    <span class="text-[10px] uppercase tracking-wider font-bold text-purple-600 dark:text-purple-400 block">${v.position}</span>
                                    <span class="font-semibold text-slate-800 dark:text-slate-200">${v.candidate}</span>
                                </div>
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400">
                                    Confirmed
                                </span>
                            </div>
                        `).join('');
                    } else {
                        votesCount.textContent = '0 selections';
                        votesList.innerHTML = `
                            <div class="py-6 text-center text-slate-400 dark:text-slate-500 text-xs italic bg-white dark:bg-slate-900 rounded-xl border border-slate-200/40 dark:border-slate-800/60">
                                ${voter.is_voted ? 'Voter marked as voted (no selections logged).' : 'No ballot cast yet. Voter is eligible and in queue.'}
                            </div>
                        `;
                    }

                    loading.classList.add('hidden');
                    body.classList.remove('hidden');
                } else {
                    window.showToast('Failed to load voter details.', 'error');
                    closeViewVoterModal();
                }
            })
            .catch(err => {
                console.error(err);
                window.showToast('Connection error.', 'error');
                closeViewVoterModal();
            });
        }

        function closeViewVoterModal() {
            const modal = document.getElementById('view-voter-modal');
            const content = document.getElementById('view-voter-modal-content');
            if (!modal || !content) return;
            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-95', 'opacity-0');
            modal.classList.remove('opacity-100');
            modal.classList.add('opacity-0');
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }, 300);
        }

        function switchToEditFromView() {
            if (!currentViewVoter) return;
            const voterToEdit = currentViewVoter;
            closeViewVoterModal();
            setTimeout(() => {
                openEditVoterModal(voterToEdit);
            }, 300);
        }

        function deleteVoter(id, name) {
            window.showConfirmModal(
                'Remove Voter',
                `Are you sure you want to remove "${name}" from the voter registry?`,
                'This will permanently delete this voter record. If this voter has already cast votes, their ballots will also be removed from the election results.',
                () => {
                    fetch(`/committees/election-app/voters/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(res => {
                        if (res.success) {
                            window.showToast(res.message || 'Voter removed successfully!', 'success');
                            const row = document.getElementById(`voter-row-${id}`);
                            if (row) row.remove();
                            fetchFilteredVoters(currentVoterPage);
                        } else {
                            window.showToast(res.message || 'Failed to remove voter.', 'error');
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        window.showToast('Connection error.', 'error');
                    });
                }
            );
        }

        function confirmVoterCSVDownload() {
            window.showConfirmModal(
                'Export Voter Registry CSV',
                'Are you sure you want to download the Voter Registry CSV report?',
                'This will compile the complete register of voters for this election, including names, emails, workplaces, positions, voting statuses, and ballot submission times, and download a secure spreadsheet file.',
                () => {
                    window.location.href = "{{ route('committees.election.export_voters', $election->id) }}";
                }
            );
        }

        // --- MORE ACTIONS DROPDOWN TOGGLERS ---
        function toggleMoreActionsDropdown(event) {
            event.stopPropagation();
            const dropdown = document.getElementById('more-actions-dropdown');
            const isHidden = dropdown.classList.contains('hidden');

            if (isHidden) {
                dropdown.classList.remove('hidden');
                document.addEventListener('click', documentClickMoreActionsHandler);
            } else {
                closeMoreActionsDropdown();
            }
        }

        function closeMoreActionsDropdown() {
            const dropdown = document.getElementById('more-actions-dropdown');
            if (dropdown) {
                dropdown.classList.add('hidden');
            }
            document.removeEventListener('click', documentClickMoreActionsHandler);
        }

        function documentClickMoreActionsHandler(event) {
            const container = document.getElementById('more-actions-container');
            if (container && !container.contains(event.target)) {
                closeMoreActionsDropdown();
            }
        }
    </script>
@endsection
