<!-- PANEL C: VOTERS (Voters & Analytics) -->
<div id="panel-voters" class="manage-tab-panel hidden space-y-6">
    <div class="flex items-center justify-between gap-4 flex-wrap">
        <div>
            <h2 class="text-xl font-bold text-slate-900 dark:text-white">Voter Register</h2>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Total registered or queued participants in this election.</p>
        </div>
        
        <div class="flex items-center gap-3">
            <button type="button" onclick="confirmVoterCSVDownload()" class="inline-flex items-center gap-1.5 bg-slate-900 hover:bg-slate-800 dark:bg-slate-800 dark:hover:bg-slate-700 text-white dark:text-slate-200 font-semibold text-xs px-4 py-2.5 rounded-xl shadow-sm transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                Export Voters CSV
            </button>
            <div class="text-xs font-bold px-3.5 py-2 rounded-xl bg-purple-500/10 text-purple-600 dark:text-purple-400">
                Total Displayed: <span id="voter-count-display">{{ $voters->count() }}</span>
            </div>
        </div>
    </div>

    <!-- Filters Bar -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 bg-slate-50 dark:bg-slate-950/20 p-5 rounded-2xl border border-slate-200/60 dark:border-slate-800/80">
        <!-- Search -->
        <div class="relative">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </span>
            <input type="text" id="voter-filter-search" oninput="fetchFilteredVoters()" placeholder="Search voter, email, role..." class="pl-9 w-full bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 text-xs rounded-xl border border-slate-200 dark:border-slate-800 focus:border-purple-500 dark:focus:border-purple-500 focus:ring-purple-500/20 focus:outline-none py-2.5 transition">
        </div>

        <!-- Division -->
        <div>
            <select id="voter-filter-division" onchange="fetchFilteredVoters()" class="w-full bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300 text-xs rounded-xl border border-slate-200 dark:border-slate-800 focus:border-purple-500 dark:focus:border-purple-500 focus:ring-purple-500/20 focus:outline-none py-2.5 px-3 transition">
                <option value="all">All Divisions / Workplaces</option>
                @foreach($divisions as $div)
                    <option value="{{ $div }}">{{ $div }}</option>
                @endforeach
            </select>
        </div>

        <!-- Voted Status -->
        <div>
            <select id="voter-filter-status" onchange="fetchFilteredVoters()" class="w-full bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300 text-xs rounded-xl border border-slate-200 dark:border-slate-800 focus:border-purple-500 dark:focus:border-purple-500 focus:ring-purple-500/20 focus:outline-none py-2.5 px-3 transition">
                <option value="all">All Statuses</option>
                <option value="voted">Voted</option>
                <option value="queue">In Queue</option>
            </select>
        </div>

        <!-- Sort Order -->
        <div>
            <select id="voter-filter-sort" onchange="fetchFilteredVoters()" class="w-full bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300 text-xs rounded-xl border border-slate-200 dark:border-slate-800 focus:border-purple-500 dark:focus:border-purple-500 focus:ring-purple-500/20 focus:outline-none py-2.5 px-3 transition">
                <option value="desc">Newest Submission First</option>
                <option value="asc">Oldest Submission First</option>
            </select>
        </div>
    </div>

    <!-- Table -->
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
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-sm text-slate-700 dark:text-slate-300" id="voters-table-body">
                    @forelse($voters as $voter)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition duration-150">
                            <td class="px-6 py-4.5 font-semibold text-slate-900 dark:text-white">
                                {{ $voter->user ? $voter->user->name : 'Anonymous Voter' }}
                            </td>
                            <td class="px-6 py-4.5 text-slate-500 dark:text-slate-400">
                                {{ $voter->email }}
                            </td>
                            <td class="px-6 py-4.5">
                                {{ $voter->division ?? '-' }}
                            </td>
                            <td class="px-6 py-4.5">
                                {{ $voter->current_position ?? '-' }}
                            </td>
                            <td class="px-6 py-4.5">
                                @if($voter->voted_at)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/10 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400">
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
