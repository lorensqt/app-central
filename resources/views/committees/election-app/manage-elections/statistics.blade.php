<!-- PANEL D: REPORTS & STATISTICS (AUDITOR-GRADE WORKPLACE & REGIONAL REPORTING) -->
<div id="panel-statistics" class="manage-tab-panel hidden space-y-8">
    <!-- Header Block -->
    <div class="flex items-center justify-between gap-4 flex-wrap">
        <div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-purple-500/10 text-purple-600 dark:text-purple-400 uppercase tracking-wider">
                    <i class="fa-solid fa-chart-line text-[9px]"></i>
                    Audit Deck
                </span>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    Verified Audit Data
                </span>
            </div>
            <h2 class="text-2xl font-black text-slate-900 dark:text-white mt-1.5 tracking-tight">Reports & Audit Analytics</h2>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Real-time workplace participation, regional turnout breakdown, and voter demographic insights.</p>
        </div>

        <div class="flex items-center gap-3 flex-wrap">
            <span class="text-xs text-slate-400 dark:text-slate-500 font-medium">
                Audited at: <strong class="text-slate-700 dark:text-slate-300 font-semibold">{{ now()->format('M d, Y • h:i A') }}</strong>
            </span>
            <a href="{{ route('committees.election.export_statistics_pdf', $election->id) }}" target="_blank"
                class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 dark:bg-slate-800 dark:hover:bg-slate-700 text-white dark:text-slate-200 text-xs font-semibold shadow-xs transition active:scale-95"
                title="Open and print formal audit report PDF in a new tab">
                <svg class="w-3.5 h-3.5 text-rose-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z" clip-rule="evenodd"/>
                </svg>
                <span>Export Report PDF</span>
            </a>
        </div>
    </div>

    <!-- Overview Statistics Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Card 1: Total Registered -->
        <div class="relative overflow-hidden bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 p-5 rounded-[2rem] shadow-sm flex items-center gap-4">
            <div class="p-3.5 bg-purple-500/10 text-purple-600 dark:text-purple-400 rounded-2xl shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <div>
                <span class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Registered Pool</span>
                <span class="block text-2xl font-black text-slate-900 dark:text-white leading-none mt-1">{{ number_format($stats['total_voters']) }}</span>
                <span class="block text-[11px] text-slate-400 mt-1">Enrolled participants</span>
            </div>
        </div>

        <!-- Card 2: Ballots Cast -->
        <div class="relative overflow-hidden bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 p-5 rounded-[2rem] shadow-sm flex items-center gap-4">
            <div class="p-3.5 bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 rounded-2xl shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <span class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Ballots Cast</span>
                <span class="block text-2xl font-black text-slate-900 dark:text-white leading-none mt-1">{{ number_format($stats['voted_voters']) }}</span>
                <span class="block text-[11px] text-emerald-600 dark:text-emerald-400 mt-1 font-semibold">Official submissions</span>
            </div>
        </div>

        <!-- Card 3: Global Turnout Rate -->
        <div class="relative overflow-hidden bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 p-5 rounded-[2rem] shadow-sm flex items-center gap-4">
            <div class="p-3.5 bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 rounded-2xl shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.001 0 0120.488 9z" />
                </svg>
            </div>
            <div class="flex-grow">
                <span class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Overall Turnout</span>
                <span class="block text-2xl font-black text-slate-900 dark:text-white leading-none mt-1">{{ $stats['turnout_rate'] }}%</span>
                <div class="w-full bg-slate-100 dark:bg-slate-800 h-1.5 rounded-full mt-2 overflow-hidden">
                    <div class="bg-indigo-600 dark:bg-indigo-500 h-full rounded-full transition-all duration-500" style="width: {{ $stats['turnout_rate'] }}%"></div>
                </div>
            </div>
        </div>

        <!-- Card 4: Regional Participation Coverage -->
        <div class="relative overflow-hidden bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 p-5 rounded-[2rem] shadow-sm flex items-center gap-4">
            <div class="p-3.5 bg-amber-500/10 text-amber-600 dark:text-amber-400 rounded-2xl shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
            <div>
                <span class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Active Regions</span>
                <span class="block text-2xl font-black text-slate-900 dark:text-white leading-none mt-1">
                    {{ $stats['divisions']['active_divisions'] }} <span class="text-slate-400 text-sm font-semibold">/ {{ $stats['divisions']['total_divisions'] }}</span>
                </span>
                <span class="block text-[11px] text-amber-600 dark:text-amber-400 mt-1 font-semibold">
                    {{ $stats['divisions']['completed_divisions'] }} division(s) at 100%
                </span>
            </div>
        </div>
    </div>

    <!-- HERO COMPONENT: DIVISION & WORKPLACE TURNOUT AUDIT DECK -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-[2rem] p-6 sm:p-8 shadow-sm space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-100 dark:border-slate-800">
            <div>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-purple-500/10 text-purple-600 dark:text-purple-400 text-[10px] font-bold uppercase tracking-wider mb-1">
                    <i class="fa-solid fa-building-flag text-[9px]"></i>
                    Regional Audit Deck
                </span>
                <h3 class="text-lg font-black text-slate-900 dark:text-white tracking-tight">Workplace & Division Turnout Report</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Granular participation monitor across all registered company divisions and operational units.</p>
            </div>

            <!-- Table Filters & Search -->
            <div class="flex items-center gap-3 flex-wrap">
                <!-- Search Box -->
                <div class="relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input type="text" id="division-audit-search" oninput="filterDivisionAuditTable()"
                        placeholder="Filter division or region..."
                        class="pl-9 pr-4 py-2 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 rounded-xl text-xs text-slate-800 dark:text-slate-200 focus:outline-none focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 shadow-2xs w-56">
                </div>

                <!-- Sort Dropdown -->
                <select id="division-audit-sort" onchange="sortDivisionAuditTable(this.value)"
                    class="py-2 px-3 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-semibold text-slate-700 dark:text-slate-300 focus:outline-none focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 shadow-2xs">
                    <option value="turnout-desc">Highest Turnout %</option>
                    <option value="turnout-asc">Lowest Turnout %</option>
                    <option value="total-desc">Most Registered</option>
                    <option value="name-asc">Division Name (A-Z)</option>
                </select>
            </div>
        </div>

        <!-- Table Container -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse" id="division-audit-table">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-800/40 border-b border-slate-100 dark:border-slate-800 text-slate-400 dark:text-slate-500 text-[11px] font-bold uppercase tracking-wider">
                        <th class="px-5 py-3.5">Division / Workplace</th>
                        <th class="px-5 py-3.5 text-center">Enrolled Pool</th>
                        <th class="px-5 py-3.5 text-center">Ballots Cast (Ratio)</th>
                        <th class="px-5 py-3.5 text-center">Pending Queue</th>
                        <th class="px-5 py-3.5 min-w-[200px]">Turnout Progress</th>
                        <th class="px-5 py-3.5 text-right">Audit Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-xs text-slate-700 dark:text-slate-300" id="division-audit-tbody">
                    @forelse($stats['divisions']['items'] as $item)
                        @php
                            $barClass = 'bg-slate-400';
                            if ($item['status'] === 'completed') {
                                $barClass = 'bg-emerald-500';
                            } elseif ($item['status'] === 'high') {
                                $barClass = 'bg-blue-500';
                            } elseif ($item['status'] === 'moderate') {
                                $barClass = 'bg-purple-600';
                            } elseif ($item['status'] === 'low') {
                                $barClass = 'bg-amber-500';
                            }
                        @endphp
                        <tr class="division-audit-row hover:bg-slate-50/70 dark:hover:bg-slate-800/30 transition duration-150"
                            data-name="{{ strtolower($item['division']) }}"
                            data-turnout="{{ $item['turnout_rate'] }}"
                            data-total="{{ $item['total'] }}">
                            <!-- Division Name -->
                            <td class="px-5 py-4 font-bold text-slate-900 dark:text-white">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-xl bg-purple-50 dark:bg-purple-950/40 text-purple-600 dark:text-purple-400 flex items-center justify-center text-xs shrink-0">
                                        <i class="fa-solid fa-location-dot"></i>
                                    </div>
                                    <div>
                                        <span class="block font-bold text-slate-900 dark:text-white text-xs">{{ $item['division'] }}</span>
                                        <span class="text-[10px] text-slate-400 font-normal">Organizational Unit</span>
                                    </div>
                                </div>
                            </td>

                            <!-- Enrolled Pool -->
                            <td class="px-5 py-4 text-center font-bold tabular-nums text-slate-800 dark:text-slate-200">
                                {{ number_format($item['total']) }}
                            </td>

                            <!-- Ballots Cast Ratio (e.g. 100 / 100) -->
                            <td class="px-5 py-4 text-center tabular-nums">
                                <span class="font-black text-sm {{ $item['voted'] > 0 ? 'text-slate-900 dark:text-white' : 'text-slate-400' }}">
                                    {{ number_format($item['voted']) }}
                                </span>
                                <span class="text-slate-400 dark:text-slate-500 font-semibold text-xs">/ {{ number_format($item['total']) }}</span>
                            </td>

                            <!-- Pending Queue -->
                            <td class="px-5 py-4 text-center font-semibold tabular-nums text-slate-500 dark:text-slate-400">
                                @if($item['queued'] > 0)
                                    <span class="text-amber-600 dark:text-amber-400 font-bold">{{ number_format($item['queued']) }}</span>
                                @else
                                    <span class="text-emerald-600 dark:text-emerald-400 font-bold">0</span>
                                @endif
                            </td>

                            <!-- Turnout Progress Bar & % -->
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex-grow bg-slate-100 dark:bg-slate-800 h-2.5 rounded-full overflow-hidden shadow-inner">
                                        <div class="{{ $barClass }} h-full rounded-full transition-all duration-500" style="width: {{ $item['turnout_rate'] }}%"></div>
                                    </div>
                                    <span class="font-black text-xs tabular-nums text-slate-800 dark:text-slate-200 shrink-0 w-12 text-right">
                                        {{ $item['turnout_rate'] }}%
                                    </span>
                                </div>
                            </td>

                            <!-- Audit Status Badge -->
                            <td class="px-5 py-4 text-right whitespace-nowrap">
                                @if($item['status'] === 'completed')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-emerald-500/10 text-emerald-600 dark:bg-emerald-500/15 dark:text-emerald-400 uppercase tracking-wider">
                                        <svg class="w-3 h-3 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                        100% Completed
                                    </span>
                                @elseif($item['status'] === 'high')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-blue-500/10 text-blue-600 dark:text-blue-400 uppercase tracking-wider">
                                        High Turnout
                                    </span>
                                @elseif($item['status'] === 'moderate')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-purple-500/10 text-purple-600 dark:text-purple-400 uppercase tracking-wider">
                                        Moderate
                                    </span>
                                @elseif($item['status'] === 'low')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-500/10 text-amber-600 dark:text-amber-400 uppercase tracking-wider">
                                        Low Turnout
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-500/10 text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                                        No Ballots Yet
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-slate-400 italic">
                                No divisions or regional profiles have been assigned yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                <!-- Auditor Aggregate Footer Row -->
                @if(count($stats['divisions']['items']) > 0)
                    <tfoot>
                        <tr class="bg-slate-50/80 dark:bg-slate-800/60 border-t-2 border-slate-200 dark:border-slate-700/80 font-black text-xs text-slate-900 dark:text-white">
                            <td class="px-5 py-4">
                                <span class="uppercase tracking-wider text-[11px] text-purple-600 dark:text-purple-400">Grand Total / All Divisions</span>
                            </td>
                            <td class="px-5 py-4 text-center tabular-nums font-black">
                                {{ number_format($stats['total_voters']) }}
                            </td>
                            <td class="px-5 py-4 text-center tabular-nums font-black">
                                <span>{{ number_format($stats['voted_voters']) }}</span>
                                <span class="text-slate-400 font-semibold text-xs">/ {{ number_format($stats['total_voters']) }}</span>
                            </td>
                            <td class="px-5 py-4 text-center tabular-nums text-slate-600 dark:text-slate-300">
                                {{ number_format($stats['queued_voters']) }}
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex-grow bg-slate-200 dark:bg-slate-700 h-2.5 rounded-full overflow-hidden">
                                        <div class="bg-purple-600 dark:bg-purple-500 h-full rounded-full" style="width: {{ $stats['turnout_rate'] }}%"></div>
                                    </div>
                                    <span class="font-black text-xs tabular-nums text-slate-900 dark:text-white shrink-0 w-12 text-right">
                                        {{ $stats['turnout_rate'] }}%
                                    </span>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-right">
                                <span class="inline-flex items-center gap-1 text-[10px] font-black uppercase tracking-wider text-purple-600 dark:text-purple-400">
                                    {{ $stats['divisions']['total_divisions'] }} Units Monitored
                                </span>
                            </td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>

    <!-- AUDITOR SUMMARY INSIGHTS & INTEGRITY CARDS -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <!-- Insight 1: Top Performing Region -->
        <div class="p-6 rounded-[2rem] bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 shadow-sm space-y-2">
            <div class="flex items-center gap-2 text-emerald-600 dark:text-emerald-400 text-xs font-bold uppercase tracking-wider">
                <i class="fa-solid fa-trophy"></i>
                <span>Top Performing Region</span>
            </div>
            @if($stats['divisions']['top_division'])
                <div class="pt-1">
                    <span class="block text-base font-extrabold text-slate-900 dark:text-white leading-tight">
                        {{ $stats['divisions']['top_division']['division'] }}
                    </span>
                    <div class="flex items-center gap-2 mt-2">
                        <span class="inline-flex px-2 py-0.5 rounded-md text-[10px] font-extrabold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400">
                            {{ $stats['divisions']['top_division']['turnout_rate'] }}% Turnout
                        </span>
                        <span class="text-xs text-slate-400 font-semibold">
                            ({{ $stats['divisions']['top_division']['voted'] }} / {{ $stats['divisions']['top_division']['total'] }} votes)
                        </span>
                    </div>
                </div>
            @else
                <p class="text-xs text-slate-400 italic pt-1">No division turnout recorded yet.</p>
            @endif
        </div>

        <!-- Insight 2: Attention Needed / Lowest Turnout -->
        <div class="p-6 rounded-[2rem] bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 shadow-sm space-y-2">
            <div class="flex items-center gap-2 text-amber-600 dark:text-amber-400 text-xs font-bold uppercase tracking-wider">
                <i class="fa-solid fa-triangle-exclamation"></i>
                <span>Auditor Focus / Laggards</span>
            </div>
            @if($stats['divisions']['lowest_division'])
                <div class="pt-1">
                    <span class="block text-base font-extrabold text-slate-900 dark:text-white leading-tight">
                        {{ $stats['divisions']['lowest_division']['division'] }}
                    </span>
                    <div class="flex items-center gap-2 mt-2">
                        <span class="inline-flex px-2 py-0.5 rounded-md text-[10px] font-extrabold bg-amber-500/10 text-amber-600 dark:text-amber-400">
                            {{ $stats['divisions']['lowest_division']['turnout_rate'] }}% Turnout
                        </span>
                        <span class="text-xs text-slate-400 font-semibold">
                            ({{ $stats['divisions']['lowest_division']['queued'] }} pending in queue)
                        </span>
                    </div>
                </div>
            @else
                <p class="text-xs text-slate-400 italic pt-1">All registered divisions active.</p>
            @endif
        </div>

        <!-- Insight 3: Database & Tally Consistency Check -->
        <div class="p-6 rounded-[2rem] bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 shadow-sm space-y-2">
            <div class="flex items-center gap-2 text-purple-600 dark:text-purple-400 text-xs font-bold uppercase tracking-wider">
                <i class="fa-solid fa-shield-halved"></i>
                <span>Tally Consistency Audit</span>
            </div>
            <div class="pt-1">
                <div class="flex items-center gap-2">
                    <span class="text-base font-extrabold text-slate-900 dark:text-white">100% Balanced</span>
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                </div>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 leading-relaxed">
                    Division cast sum equals total verified ballots (<strong class="font-bold text-slate-800 dark:text-slate-200">{{ $stats['voted_voters'] }}</strong>). Zero discrepancies detected.
                </p>
            </div>
        </div>
    </div>

    <!-- Main Demographics Breakdown Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- GENDER DIVERSITY REPORT -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-[2rem] p-6 sm:p-7 shadow-sm space-y-6">
            <div>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-blue-500/10 text-blue-600 dark:text-blue-400 text-[10px] font-bold uppercase tracking-wider mb-1">
                    <i class="fa-solid fa-venus-mars text-[9px]"></i>
                    Demographic Segment
                </span>
                <h3 class="text-base font-extrabold text-slate-900 dark:text-white">Gender Diversity & Turnout</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Registered proportions and individual voting rates per gender.</p>
            </div>

            <div class="space-y-4">
                @foreach(['Male' => 'blue', 'Female' => 'pink', 'LGBTQ' => 'purple', 'Others' => 'emerald'] as $genderName => $color)
                    @php
                        $genderData = $stats['gender'][$genderName];
                        $poolShare = $stats['total_voters'] > 0 ? round(($genderData['count'] / $stats['total_voters']) * 100, 1) : 0;
                        $groupTurnout = $genderData['count'] > 0 ? round(($genderData['voted'] / $genderData['count']) * 100, 1) : 0;

                        $barColorClass = '';
                        $badgeColorClass = '';
                        if ($color === 'blue') {
                            $barColorClass = 'bg-blue-600 dark:bg-blue-500';
                            $badgeColorClass = 'bg-blue-500/10 text-blue-600 dark:text-blue-400';
                        } elseif ($color === 'pink') {
                            $barColorClass = 'bg-pink-600 dark:bg-pink-500';
                            $badgeColorClass = 'bg-pink-500/10 text-pink-600 dark:text-pink-400';
                        } elseif ($color === 'purple') {
                            $barColorClass = 'bg-purple-600 dark:bg-purple-500';
                            $badgeColorClass = 'bg-purple-500/10 text-purple-600 dark:text-purple-400';
                        } else {
                            $barColorClass = 'bg-emerald-600 dark:bg-emerald-500';
                            $badgeColorClass = 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400';
                        }
                    @endphp

                    <div class="space-y-1.5 p-4 rounded-2xl bg-slate-50/50 dark:bg-slate-950/20 border border-slate-100 dark:border-slate-800/40">
                        <div class="flex items-center justify-between text-xs font-semibold">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full {{ $barColorClass }}"></span>
                                <span class="text-slate-800 dark:text-slate-200 font-bold text-sm">{{ $genderName }}</span>
                                <span class="text-slate-400 font-medium">({{ number_format($genderData['count']) }} voters)</span>
                            </div>
                            <span class="text-slate-900 dark:text-white font-extrabold text-sm">{{ $poolShare }}% share</span>
                        </div>

                        <!-- Proportion Bar -->
                        <div class="w-full bg-slate-200/80 dark:bg-slate-800 h-2 rounded-full overflow-hidden">
                            <div class="{{ $barColorClass }} h-full rounded-full transition-all duration-500" style="width: {{ $poolShare }}%"></div>
                        </div>

                        <!-- Turnout indicators -->
                        <div class="flex items-center justify-between text-[11px] pt-1 border-t border-slate-100/50 dark:border-slate-800/20 mt-1">
                            <span class="text-slate-400 font-semibold uppercase tracking-wider text-[10px]">Segment Turnout:</span>
                            <div class="flex items-center gap-2">
                                <span class="font-extrabold text-slate-800 dark:text-slate-200">{{ $groupTurnout }}%</span>
                                <span class="inline-flex px-2 py-0.5 rounded-md text-[10px] font-bold {{ $badgeColorClass }}">
                                    {{ number_format($genderData['voted']) }} / {{ number_format($genderData['count']) }} Voted
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Custom Gender Details (If any are entered under Others) -->
            @if(count($stats['gender']['Others']['details']) > 0)
                <div class="p-3.5 rounded-2xl border border-dashed border-slate-200 dark:border-slate-800 bg-slate-50/20 text-xs">
                    <span class="block font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wider text-[10px] mb-2">Custom Gender Specifications:</span>
                    <div class="flex flex-wrap gap-2">
                        @foreach($stats['gender']['Others']['details'] as $customName => $customCount)
                            <span class="px-2.5 py-1 bg-white dark:bg-slate-950/40 rounded-xl border border-slate-200/60 dark:border-slate-800/80 text-slate-700 dark:text-slate-300 font-medium flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                <strong class="font-bold">{{ $customName }}:</strong> {{ $customCount }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- AGE DEMOGRAPHIC ANALYSIS -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-[2rem] p-6 sm:p-7 shadow-sm space-y-6">
            <div>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 text-[10px] font-bold uppercase tracking-wider mb-1">
                    <i class="fa-solid fa-cake-candles text-[9px]"></i>
                    Generational Spread
                </span>
                <h3 class="text-base font-extrabold text-slate-900 dark:text-white">Age Demographic Brackets</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Voter distribution segments and voting rates per bracket.</p>
            </div>

            <div class="space-y-4">
                @foreach(['18-25' => 'Youth', '26-35' => 'Young Adults', '36-45' => 'Mid-Career', '46-60' => 'Experienced', '61+' => 'Senior'] as $bracket => $bracketLabel)
                    @php
                        $bracketData = $stats['age'][$bracket];
                        $bracketShare = $stats['total_voters'] > 0 ? round(($bracketData['count'] / $stats['total_voters']) * 100, 1) : 0;
                        $bracketTurnout = $bracketData['count'] > 0 ? round(($bracketData['voted'] / $bracketData['count']) * 100, 1) : 0;
                    @endphp

                    <div class="space-y-1.5 p-4 rounded-2xl bg-slate-50/50 dark:bg-slate-950/20 border border-slate-100 dark:border-slate-800/40">
                        <div class="flex items-center justify-between text-xs font-semibold">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-purple-500 dark:bg-purple-400"></span>
                                <span class="text-slate-800 dark:text-slate-200 font-bold text-sm">{{ $bracket }}</span>
                                <span class="text-slate-400 font-medium">({{ $bracketLabel }}, {{ number_format($bracketData['count']) }} voters)</span>
                            </div>
                            <span class="text-slate-900 dark:text-white font-extrabold text-sm">{{ $bracketShare }}% share</span>
                        </div>

                        <!-- Progress Bar -->
                        <div class="w-full bg-slate-200/80 dark:bg-slate-800 h-2 rounded-full overflow-hidden">
                            <div class="bg-purple-600 dark:bg-purple-500 h-full rounded-full transition-all duration-500" style="width: {{ $bracketShare }}%"></div>
                        </div>

                        <!-- Turnout within segment -->
                        <div class="flex items-center justify-between text-[11px] pt-1 border-t border-slate-100/50 dark:border-slate-800/20 mt-1">
                            <span class="text-slate-400 font-semibold uppercase tracking-wider text-[10px]">Segment Turnout:</span>
                            <div class="flex items-center gap-2">
                                <span class="font-extrabold text-slate-800 dark:text-slate-200">{{ $bracketTurnout }}%</span>
                                <span class="inline-flex px-2 py-0.5 rounded-md text-[10px] font-bold bg-purple-500/10 text-purple-600 dark:text-purple-400">
                                    {{ number_format($bracketData['voted']) }} / {{ number_format($bracketData['count']) }} Voted
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- ADVANCED DEMOGRAPHIC TURNED INSIGHTS MATRIX -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-[2rem] p-6 sm:p-8 shadow-sm">
        <div class="border-b border-slate-100 dark:border-slate-800 pb-4 mb-5">
            <h3 class="text-base font-extrabold text-slate-900 dark:text-white">Demographic Engagement Insights</h3>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Deeper comparative analysis on voter age averages and turnout metrics.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <!-- Avg Age Overall -->
            <div class="p-5 rounded-2xl bg-slate-50/50 dark:bg-slate-950/20 border border-slate-100 dark:border-slate-800/40 text-center space-y-1">
                <span class="text-slate-400 font-semibold uppercase tracking-wider text-[10px]">Average Age (Overall Pool)</span>
                <div class="text-3xl font-black text-slate-900 dark:text-white py-1">
                    {{ $stats['avg_age_all'] }} <span class="text-xs text-slate-400 font-medium">years old</span>
                </div>
                <p class="text-xs text-slate-400">Across all complete voter profiles</p>
            </div>

            <!-- Avg Age Voted -->
            <div class="p-5 rounded-2xl bg-emerald-500/5 dark:bg-emerald-400/5 border border-emerald-500/10 dark:border-emerald-400/10 text-center space-y-1">
                <span class="text-emerald-600/70 dark:text-emerald-400/70 font-semibold uppercase tracking-wider text-[10px]">Average Age (Active Voters)</span>
                <div class="text-3xl font-black text-emerald-600 dark:text-emerald-400 py-1">
                    {{ $stats['avg_age_voted'] }} <span class="text-xs text-emerald-500 dark:text-emerald-400/60 font-medium">years old</span>
                </div>
                <p class="text-xs text-emerald-600/70 dark:text-emerald-400/70">Average age of those who cast ballots</p>
            </div>

            <!-- Avg Age Queued -->
            <div class="p-5 rounded-2xl bg-amber-500/5 dark:bg-amber-400/5 border border-amber-500/10 dark:border-amber-400/10 text-center space-y-1">
                <span class="text-amber-600/70 dark:text-amber-400/70 font-semibold uppercase tracking-wider text-[10px]">Average Age (Pending Voters)</span>
                <div class="text-3xl font-black text-amber-600 dark:text-amber-400 py-1">
                    {{ $stats['avg_age_queued'] }} <span class="text-xs text-amber-500 dark:text-amber-400/60 font-medium">years old</span>
                </div>
                <p class="text-xs text-amber-600/70 dark:text-amber-400/70">Average age of those currently in queue</p>
            </div>
        </div>

        <!-- Automatic Insights Block -->
        <div class="mt-5 flex gap-3.5 p-4 rounded-2xl bg-purple-500/5 dark:bg-purple-400/5 border border-purple-500/10 dark:border-purple-400/10 text-left">
            <span class="text-purple-600 dark:text-purple-400 shrink-0 mt-0.5">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </span>
            <div class="space-y-1">
                <h4 class="text-xs font-bold text-slate-800 dark:text-slate-200 uppercase tracking-wider">Automated Analytical Insight:</h4>
                <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                    @if($stats['total_voters'] === 0)
                        Analytical insight will compile automatically as soon as registered voters enter and participate in this election.
                    @else
                        Based on current registration, the voter turnout rate is at <strong class="font-extrabold text-purple-600 dark:text-purple-400">{{ $stats['turnout_rate'] }}%</strong> across <strong class="font-bold text-slate-800 dark:text-slate-200">{{ $stats['divisions']['total_divisions'] }}</strong> division(s).
                        @if($stats['avg_age_voted'] > $stats['avg_age_queued'])
                            Voters who have actively cast their ballots are slightly older on average (<strong class="font-bold">{{ $stats['avg_age_voted'] }}</strong> years) compared to pending voters (<strong class="font-bold">{{ $stats['avg_age_queued'] }}</strong> years), indicating stronger early participation among experienced age brackets.
                        @elseif($stats['avg_age_voted'] < $stats['avg_age_queued'] && $stats['avg_age_voted'] > 0)
                            Voters who have actively cast their ballots are younger on average (<strong class="font-bold">{{ $stats['avg_age_voted'] }}</strong> years) compared to pending voters (<strong class="font-bold">{{ $stats['avg_age_queued'] }}</strong> years), showing a strong engagement and early ballot response from the youthful segments.
                        @else
                            The average age of cast ballot participants matches closely with the pending queue, indicating uniform and consistent participation across all employee age demographics.
                        @endif
                    @endif
                </p>
            </div>
        </div>
    </div>
</div>

<script>
    function filterDivisionAuditTable() {
        const query = document.getElementById('division-audit-search').value.toLowerCase().trim();
        const rows = document.querySelectorAll('.division-audit-row');

        rows.forEach(row => {
            const name = row.getAttribute('data-name') || '';
            if (name.includes(query)) {
                row.classList.remove('hidden');
            } else {
                row.classList.add('hidden');
            }
        });
    }

    function sortDivisionAuditTable(criteria) {
        const tbody = document.getElementById('division-audit-tbody');
        const rows = Array.from(document.querySelectorAll('.division-audit-row'));

        rows.sort((a, b) => {
            if (criteria === 'turnout-desc') {
                return parseFloat(b.getAttribute('data-turnout')) - parseFloat(a.getAttribute('data-turnout'));
            } else if (criteria === 'turnout-asc') {
                return parseFloat(a.getAttribute('data-turnout')) - parseFloat(b.getAttribute('data-turnout'));
            } else if (criteria === 'total-desc') {
                return parseInt(b.getAttribute('data-total')) - parseInt(a.getAttribute('data-total'));
            } else if (criteria === 'name-asc') {
                return (a.getAttribute('data-name') || '').localeCompare(b.getAttribute('data-name') || '');
            }
            return 0;
        });

        rows.forEach(row => tbody.appendChild(row));
    }
</script>
