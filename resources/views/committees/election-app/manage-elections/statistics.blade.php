<!-- PANEL D: REPORTS & STATISTICS -->
<div id="panel-statistics" class="manage-tab-panel hidden space-y-6">
    <!-- Header Block -->
    <div>
        <h2 class="text-xl font-bold text-slate-900 dark:text-white">Reports & Statistics</h2>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Real-time demographic and turnout insights of the Voter Registry.</p>
    </div>

    <!-- Overview Statistics Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Card 1: Total Registered -->
        <div class="relative overflow-hidden bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 p-5 rounded-[2rem] shadow-sm flex items-center gap-4">
            <div class="p-3 bg-purple-500/10 text-purple-600 dark:text-purple-400 rounded-2xl shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <div>
                <span class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Registered Pool</span>
                <span class="block text-2xl font-black text-slate-900 dark:text-white leading-none mt-1">{{ $stats['total_voters'] }}</span>
                <span class="block text-[11px] text-slate-400 mt-1">Active Profiles</span>
            </div>
        </div>

        <!-- Card 2: Ballots Cast -->
        <div class="relative overflow-hidden bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 p-5 rounded-[2rem] shadow-sm flex items-center gap-4">
            <div class="p-3 bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 rounded-2xl shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <span class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Ballots Cast</span>
                <span class="block text-2xl font-black text-slate-900 dark:text-white leading-none mt-1">{{ $stats['voted_voters'] }}</span>
                <span class="block text-[11px] text-emerald-600 dark:text-emerald-400 mt-1 font-semibold">Completed Votes</span>
            </div>
        </div>

        <!-- Card 3: Turnout Rate -->
        <div class="relative overflow-hidden bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 p-5 rounded-[2rem] shadow-sm flex items-center gap-4">
            <div class="p-3 bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 rounded-2xl shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.001 0 0120.488 9z" />
                </svg>
            </div>
            <div class="flex-grow">
                <span class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Turnout Rate</span>
                <span class="block text-2xl font-black text-slate-900 dark:text-white leading-none mt-1">{{ $stats['turnout_rate'] }}%</span>
                <!-- Visual Turnout Mini Bar -->
                <div class="w-full bg-slate-100 dark:bg-slate-800 h-1.5 rounded-full mt-2 overflow-hidden">
                    <div class="bg-indigo-600 dark:bg-indigo-500 h-full rounded-full" style="width: {{ $stats['turnout_rate'] }}%"></div>
                </div>
            </div>
        </div>

        <!-- Card 4: Queue Status -->
        <div class="relative overflow-hidden bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 p-5 rounded-[2rem] shadow-sm flex items-center gap-4">
            <div class="p-3 bg-amber-500/10 text-amber-600 dark:text-amber-400 rounded-2xl shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <span class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Voters In Queue</span>
                <span class="block text-2xl font-black text-slate-900 dark:text-white leading-none mt-1">{{ $stats['queued_voters'] }}</span>
                <span class="block text-[11px] text-amber-600 dark:text-amber-400 mt-1 font-semibold">Ballot Pending</span>
            </div>
        </div>
    </div>

    <!-- Main Demographics Breakdown Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- GENDER DIVERSITY REPORT -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-[2rem] p-6 shadow-sm space-y-6">
            <div>
                <h3 class="text-base font-extrabold text-slate-900 dark:text-white">Gender Diversity & Turnout</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Registered proportions and individual voting rates per gender.</p>
            </div>

            <div class="space-y-4.5">
                @foreach(['Male' => 'blue', 'Female' => 'pink', 'LGBTQ' => 'purple', 'Others' => 'emerald'] as $genderName => $color)
                    @php
                        $genderData = $stats['gender'][$genderName];
                        $poolShare = $stats['total_voters'] > 0 ? round(($genderData['count'] / $stats['total_voters']) * 100, 1) : 0;
                        $groupTurnout = $genderData['count'] > 0 ? round(($genderData['voted'] / $genderData['count']) * 100, 1) : 0;

                        // Specific tailwind class colors mapping
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
                                <span class="text-slate-400 font-medium">({{ $genderData['count'] }} voters)</span>
                            </div>
                            <span class="text-slate-900 dark:text-white font-extrabold text-sm">{{ $poolShare }}% share</span>
                        </div>

                        <!-- Proportion Bar -->
                        <div class="w-full bg-slate-200/80 dark:bg-slate-800 h-2.5 rounded-full overflow-hidden">
                            <div class="{{ $barColorClass }} h-full rounded-full" style="width: {{ $poolShare }}%"></div>
                        </div>

                        <!-- Turnout indicators -->
                        <div class="flex items-center justify-between text-[11px] pt-1 border-t border-slate-100/50 dark:border-slate-800/20 mt-1">
                            <span class="text-slate-400 font-semibold uppercase tracking-wider">Turnout within segment:</span>
                            <div class="flex items-center gap-1.5">
                                <span class="font-extrabold text-slate-800 dark:text-slate-200">{{ $groupTurnout }}%</span>
                                <span class="inline-flex px-1.5 py-0.5 rounded-md text-[9px] font-bold {{ $badgeColorClass }}">
                                    {{ $genderData['voted'] }} / {{ $genderData['count'] }} Voted
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
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-[2rem] p-6 shadow-sm space-y-6">
            <div>
                <h3 class="text-base font-extrabold text-slate-900 dark:text-white">Age Demographic Brackets</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">voter distribution segments and voting rates per bracket.</p>
            </div>

            <div class="space-y-4.5">
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
                                <span class="text-slate-400 font-medium">({{ $bracketLabel }}, {{ $bracketData['count'] }} voters)</span>
                            </div>
                            <span class="text-slate-900 dark:text-white font-extrabold text-sm">{{ $bracketShare }}% share</span>
                        </div>

                        <!-- Progress Bar -->
                        <div class="w-full bg-slate-200/80 dark:bg-slate-800 h-2.5 rounded-full overflow-hidden">
                            <div class="bg-purple-600 dark:bg-purple-500 h-full rounded-full" style="width: {{ $bracketShare }}%"></div>
                        </div>

                        <!-- Turnout within segment -->
                        <div class="flex items-center justify-between text-[11px] pt-1 border-t border-slate-100/50 dark:border-slate-800/20 mt-1">
                            <span class="text-slate-400 font-semibold uppercase tracking-wider">Turnout within segment:</span>
                            <div class="flex items-center gap-1.5">
                                <span class="font-extrabold text-slate-800 dark:text-slate-200">{{ $bracketTurnout }}%</span>
                                <span class="inline-flex px-1.5 py-0.5 rounded-md text-[9px] font-bold bg-purple-500/10 text-purple-600 dark:text-purple-400">
                                    {{ $bracketData['voted'] }} / {{ $bracketData['count'] }} Voted
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- ADVANCED DEMOGRAPHIC TURNED INSIGHTS MATRIX -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-[2rem] p-6 shadow-sm">
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
                        Based on current registration, the voter turnout rate is at <strong class="font-extrabold text-purple-600 dark:text-purple-400">{{ $stats['turnout_rate'] }}%</strong>.
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
