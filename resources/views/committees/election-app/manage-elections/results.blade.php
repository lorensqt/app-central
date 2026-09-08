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
                @if($election->status === 'closed')
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-purple-500/10 text-purple-600 dark:bg-purple-500/10 dark:text-purple-400">
                        Official Certified
                    </span>
                @endif
            </h2>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Real-time tally of all cast ballots and candidate positions.</p>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            <button type="button" onclick="confirmCSVDownload()" class="inline-flex items-center gap-1.5 bg-slate-900 hover:bg-slate-800 dark:bg-slate-800 dark:hover:bg-slate-700 text-white dark:text-slate-200 font-semibold text-xs px-4 py-2.5 rounded-xl shadow-sm transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                Export CSV Report
            </button>

            <a href="{{ route('committees.election.export_pdf', $election->id) }}" target="_blank" class="inline-flex items-center gap-1.5 bg-rose-600 hover:bg-rose-700 text-white font-semibold text-xs px-4 py-2.5 rounded-xl shadow-sm transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Export PDF Report
            </a>
        </div>
    </div>

    @if($election->status === 'closed')
        <!-- CERTIFIED ELECTION WINNERS SHOWCASE -->
        <div class="bg-gradient-to-r from-purple-900 to-indigo-950 rounded-[2rem] border border-purple-500/20 p-8 text-white space-y-6 shadow-md relative overflow-hidden">
            <div class="absolute -top-12 -right-12 w-64 h-64 bg-purple-500/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-12 -left-12 w-64 h-64 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>

            <div class="flex items-center gap-3.5 pb-4 border-b border-white/10">
                <div class="w-11 h-11 bg-white/10 rounded-full flex items-center justify-center text-yellow-400 shrink-0 border border-white/15">
                    <svg class="w-5.5 h-5.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-extrabold tracking-tight">Certified Election Winners</h3>
                    <p class="text-xs text-purple-200/80 mt-0.5">The democratic process has concluded. Here are the officially declared winners per position.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($positions as $position)
                    @php
                        // Sort candidates by votes_count descending, only taking the top max_votes
                        $winners = $position->candidates->sortByDesc('votes_count')->take($position->max_votes);
                        $hasVotesCast = $position->votes_count > 0;
                    @endphp
                    <div class="bg-white/5 border border-white/10 rounded-2xl p-5 space-y-4">
                        <div class="border-b border-white/5 pb-2.5">
                            <h4 class="font-extrabold text-sm text-purple-200 uppercase tracking-wider truncate">
                                {{ $position->name }}
                            </h4>
                            <p class="text-[10px] text-white/40 mt-0.5">Seats: {{ $position->max_votes }} • Total Votes: {{ $position->votes_count }}</p>
                        </div>

                        <div class="space-y-3">
                            @if($hasVotesCast)
                                @foreach($winners as $winner)
                                    @php
                                        $percent = $position->votes_count > 0 ? round(($winner->votes_count / $position->votes_count) * 100, 1) : 0;
                                    @endphp
                                    <div class="flex items-center justify-between gap-3.5 bg-white/5 border border-white/5 rounded-xl p-3">
                                        <div class="flex items-center gap-3 min-w-0">
                                            <div class="w-6 h-6 rounded-full bg-yellow-400/10 text-yellow-400 border border-yellow-400/20 flex items-center justify-center text-[10px] font-bold shrink-0">
                                                🏆
                                            </div>
                                            <div class="min-w-0">
                                                <div class="font-bold text-xs text-white truncate">{{ $winner->name }}</div>
                                                <div class="text-[9px] text-white/50 truncate">{{ $winner->party_affiliation ?? 'Independent' }}</div>
                                            </div>
                                        </div>
                                        <div class="text-right shrink-0">
                                            <div class="font-bold text-xs text-yellow-400">{{ $winner->votes_count }} votes</div>
                                            <div class="text-[9px] text-white/40">{{ $percent }}% share</div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center py-4 text-xs text-white/40 italic">
                                    No votes recorded for this position.
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

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
