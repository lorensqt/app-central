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
                <h3 class="text-3xl font-extrabold text-slate-900 dark:text-white" id="stats-total-count">{{ $event->registrations->count() }}</h3>
                <p class="text-xs text-slate-550 dark:text-slate-400 mt-1">Total RSVP forms filled</p>
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
                <h3 class="text-3xl font-extrabold text-slate-900 dark:text-white font-sans" id="stats-approved-count">
                    {{ $event->registrations->where('status', 'approved')->count() }}
                </h3>
                <p class="text-xs text-slate-550 dark:text-slate-400 mt-1">
                    @php
                        $total = $event->registrations->count();
                        $approved = $event->registrations->where('status', 'approved')->count();
                        $appRate = $total > 0 ? round(($approved / $total) * 100) : 0;
                    @endphp
                    Approval rate is <span class="font-bold text-emerald-600 dark:text-emerald-400" id="stats-app-rate">{{ $appRate }}%</span>
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
                <h3 class="text-3xl font-extrabold text-slate-900 dark:text-white font-sans" id="stats-pending-count">
                    {{ $event->registrations->where('status', 'pending')->count() }}
                </h3>
                <p class="text-xs text-slate-550 dark:text-slate-400 mt-1">Applications in queue</p>
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
                <h3 class="text-3xl font-extrabold text-slate-900 dark:text-white font-sans" id="stats-declined-count">
                    {{ $event->registrations->where('status', 'declined')->count() }}
                </h3>
                <p class="text-xs text-slate-550 dark:text-slate-400 mt-1">Requests rejected</p>
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
                        <span class="text-slate-900 dark:text-white" id="progress-approved-text">{{ $approved }} seats ({{ $appRate }}%)</span>
                    </div>
                    <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-3 overflow-hidden">
                        <div class="bg-emerald-500 h-full rounded-full transition-all duration-500" id="progress-approved-bar" style="width: {{ $appRate }}%"></div>
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
                        <span class="text-slate-900 dark:text-white" id="progress-pending-text">{{ $pending }} in-queue ({{ $pendRate }}%)</span>
                    </div>
                    <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-3 overflow-hidden">
                        <div class="bg-amber-400 h-full rounded-full transition-all duration-500" id="progress-pending-bar" style="width: {{ $pendRate }}%"></div>
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
                        <span class="text-slate-900 dark:text-white" id="progress-declined-text">{{ $declined }} requests ({{ $decRate }}%)</span>
                    </div>
                    <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-3 overflow-hidden">
                        <div class="bg-red-500 h-full rounded-full transition-all duration-500" id="progress-declined-bar" style="width: {{ $decRate }}%"></div>
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
            <p class="text-xs text-slate-550 dark:text-slate-400 max-w-xl">Need to share attendee metrics and gender demographics with your divisional leads? Print or save a PDF summary containing all active registrations, pending applicants, and venue locations.</p>
        </div>

        <button onclick="window.print()" class="w-full sm:w-auto shrink-0 inline-flex items-center justify-center gap-2 text-xs font-semibold py-3 px-5 rounded-xl bg-purple-600 hover:bg-purple-700 text-white transition duration-150 shadow-md hover:shadow-lg focus:outline-none">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
            </svg>
            Print/Save PDF Report
        </button>
    </div>
</div>