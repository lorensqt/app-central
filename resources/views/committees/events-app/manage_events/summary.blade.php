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

    <!-- Attendee Details & Custom Answers Table -->
    @php
        $fieldsConfig = $event->registration_fields ?? [];
        $isNewFormat = false;
        if (is_array($fieldsConfig)) {
            foreach ($fieldsConfig as $k => $v) {
                if (is_array($v) && isset($v['label'])) {
                    $isNewFormat = true;
                    break;
                }
            }
        }
        
        $customHeaders = [];
        if ($isNewFormat) {
            foreach ($fieldsConfig as $field) {
                if (!empty($field['label'])) {
                    $customHeaders[] = $field['label'];
                }
            }
        } else {
            if (!empty($fieldsConfig['phone']['enabled'])) $customHeaders[] = 'Phone Number';
            if (!empty($fieldsConfig['job_title']['enabled'])) $customHeaders[] = 'Corporate Title / Position';
            if (!empty($fieldsConfig['company']['enabled'])) $customHeaders[] = 'Company / Department';
            if (!empty($fieldsConfig['birthday']['enabled'])) $customHeaders[] = 'Birth Date';
        }

        // Capture any stored custom fields that aren't in current active configuration
        foreach ($event->registrations as $reg) {
            if (!empty($reg->custom_fields) && is_array($reg->custom_fields)) {
                foreach (array_keys($reg->custom_fields) as $key) {
                    if (!in_array($key, $customHeaders)) {
                        $customHeaders[] = $key;
                    }
                }
            }
        }
    @endphp

    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 p-6 shadow-sm space-y-6">
        <div class="flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-4">
            <div class="text-left">
                <h4 class="font-bold text-slate-900 dark:text-white text-base">Attendee Directory & Responses</h4>
                <p class="text-xs text-slate-550 dark:text-slate-400 mt-1">Directory of all registered attendees. Click "View Responses" to see full registration questionnaires.</p>
            </div>
            
            <!-- Search and filters -->
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                <!-- Search Box -->
                <div class="relative bg-white dark:bg-slate-950 rounded-xl border border-slate-200/80 dark:border-slate-800/80 hover:border-slate-300 dark:hover:border-slate-700 shadow-[0_2px_8px_rgba(15,23,42,0.01)] transition duration-200 w-full sm:w-64">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </span>
                    <input type="text" id="analytics-search" oninput="filterAnalytics()" placeholder="Search attendees/responses..." class="w-full pl-9 pr-3 py-2 rounded-xl border-0 text-slate-600 dark:text-slate-200 text-xs focus:ring-0 focus:outline-none bg-transparent placeholder-slate-400">
                </div>

                <!-- Status Buttons -->
                <div class="flex flex-wrap items-center gap-1 bg-slate-50 dark:bg-slate-950 p-1 rounded-xl border border-slate-100 dark:border-slate-800/80 select-none text-[11px] font-semibold shrink-0">
                    <button type="button" onclick="setAnalyticsStatusFilter('all')" id="analytics-filter-btn-all" class="px-2.5 py-1 rounded-lg bg-purple-500/10 dark:bg-purple-950/40 text-purple-600 dark:text-purple-400 border border-purple-500/20 transition duration-150">
                        All ({{ $event->registrations->count() }})
                    </button>
                    <button type="button" onclick="setAnalyticsStatusFilter('approved')" id="analytics-filter-btn-approved" class="px-2.5 py-1 rounded-lg text-slate-500 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-850 transition duration-150">
                        Approved ({{ $event->registrations->where('status', 'approved')->count() }})
                    </button>
                    <button type="button" onclick="setAnalyticsStatusFilter('pending')" id="analytics-filter-btn-pending" class="px-2.5 py-1 rounded-lg text-slate-500 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-850 transition duration-150">
                        Pending ({{ $event->registrations->where('status', 'pending')->count() }})
                    </button>
                    <button type="button" onclick="setAnalyticsStatusFilter('declined')" id="analytics-filter-btn-declined" class="px-2.5 py-1 rounded-lg text-slate-500 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-850 transition duration-150">
                        Declined ({{ $event->registrations->where('status', 'declined')->count() }})
                    </button>
                </div>
            </div>
        </div>

        <!-- Scrollable dynamic table -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/60 dark:border-slate-800 shadow-[0_4px_12px_rgba(15,23,42,0.02)] overflow-hidden">
            @if($event->registrations->isEmpty())
                <div class="text-center py-12 text-slate-450 dark:text-slate-500 text-sm italic">
                    No registrations yet.
                </div>
            @else
                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-950 border-b border-slate-100 dark:border-slate-800/80 font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 text-[10px]">
                                <th class="py-3 px-4">Attendee Profile</th>
                                <th class="py-3 px-4">Ticket Code</th>
                                <th class="py-3 px-4">Gender</th>
                                <th class="py-3 px-4">Status</th>
                                <th class="py-3 px-4 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody id="analytics-table-body" class="divide-y divide-slate-100 dark:divide-slate-800/80">
                            @foreach($event->registrations->sortByDesc('created_at') as $reg)
                                @php
                                    $answersText = '';
                                    if (!empty($reg->custom_fields) && is_array($reg->custom_fields)) {
                                        $answersText = implode(' ', array_values($reg->custom_fields));
                                    }
                                @endphp
                                <tr class="analytics-row hover:bg-slate-50/50 dark:hover:bg-slate-800/35 transition duration-150"
                                    data-name="{{ strtolower($reg->name) }}"
                                    data-email="{{ strtolower($reg->email) }}"
                                    data-code="{{ strtolower($reg->ticket_code ?? '') }}"
                                    data-status="{{ $reg->status }}"
                                    data-answers="{{ strtolower($answersText) }}">
                                    
                                    <!-- Profile (Initials Avatar, Name & Email) -->
                                    <td class="py-3.5 px-4 font-semibold text-slate-800 dark:text-slate-200">
                                        <div class="flex items-center gap-2.5">
                                            <span class="inline-flex items-center justify-center w-7.5 h-7.5 rounded-lg bg-purple-50 dark:bg-purple-950/40 text-purple-600 dark:text-purple-400 font-bold text-[10px] uppercase shrink-0">
                                                {{ substr($reg->name, 0, 2) }}
                                            </span>
                                            <div class="text-left">
                                                <span class="block text-slate-900 dark:text-slate-100 font-bold text-xs leading-none">{{ $reg->name }}</span>
                                                <span class="block text-[10px] text-slate-400 dark:text-slate-500 font-mono mt-1 leading-none select-all">{{ $reg->email }}</span>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Ticket Code -->
                                    <td class="py-3.5 px-4 font-mono font-bold text-purple-650 dark:text-purple-400">
                                        {{ $reg->ticket_code ?? '—' }}
                                    </td>

                                    <!-- Gender -->
                                    <td class="py-3.5 px-4">
                                        <span class="px-1.5 py-0.5 bg-slate-50 dark:bg-slate-950 border border-slate-100/80 dark:border-slate-800/80 rounded font-medium text-slate-600 dark:text-slate-400 text-[10px] whitespace-nowrap">
                                            {{ $reg->gender ?? 'Unspecified' }}
                                        </span>
                                    </td>

                                    <!-- Status Badge -->
                                    <td class="py-3.5 px-4">
                                        @if($reg->status === 'approved')
                                            <span class="inline-flex items-center gap-1 px-1.5 py-0.5 bg-emerald-50 dark:bg-emerald-950/30 text-emerald-700 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-900/30 text-[9px] font-bold rounded uppercase tracking-wider">
                                                Approved
                                            </span>
                                        @elseif($reg->status === 'declined')
                                            <span class="inline-flex items-center gap-1 px-1.5 py-0.5 bg-red-50 dark:bg-red-950/30 text-red-700 dark:text-red-400 border border-red-100/30 dark:border-red-900/30 text-[9px] font-bold rounded uppercase tracking-wider">
                                                Declined
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-1.5 py-0.5 bg-amber-50 dark:bg-amber-950/30 text-amber-700 dark:text-amber-400 border border-amber-100 dark:border-amber-900/30 text-[9px] font-bold rounded uppercase tracking-wider animate-pulse">
                                                Pending
                                            </span>
                                        @endif
                                    </td>

                                    <!-- View Details Action Button -->
                                    <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                        <button type="button" onclick="openAttendeeModal({{ $reg->id }})" class="text-[11px] font-bold text-purple-600 dark:text-purple-400 hover:text-white dark:hover:text-slate-900 hover:bg-purple-600 dark:hover:bg-purple-500 border border-purple-200 dark:border-purple-800/60 hover:border-transparent px-3 py-1.5 rounded-xl transition duration-150">
                                            View Responses
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Table empty search state -->
                <div id="no-analytics-matched" class="hidden text-center py-12 text-slate-440 dark:text-slate-500 italic">
                    No matching attendees or responses found.
                </div>
            @endif
        </div>
    </div>

    <!-- Print Actions Banner -->
    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 p-6 shadow-sm flex flex-col lg:flex-row items-center justify-between gap-4">
        <div class="space-y-1 text-left w-full lg:w-auto">
            <h4 class="font-bold text-slate-900 dark:text-white text-base">Export Comprehensive Report</h4>
            <p class="text-xs text-slate-550 dark:text-slate-400 max-w-xl">Need to share attendee metrics, gender demographics, and dynamic questionnaire responses with divisional leads? Download a comprehensive executive-ready PDF report.</p>
        </div>

        <div class="flex flex-col sm:flex-row items-stretch gap-2.5 w-full lg:w-auto">
            <!-- Dompdf Download Button -->
            <a href="{{ route('committees.events.export_summary_pdf', $event) }}" target="_blank" class="w-full sm:w-auto shrink-0 inline-flex items-center justify-center gap-2 text-xs font-semibold py-3 px-5 rounded-xl bg-purple-600 hover:bg-purple-700 text-white transition duration-150 shadow-md hover:shadow-lg focus:outline-none">
                <svg class="w-4 h-4 text-purple-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Export Executive PDF
            </a>
        </div>
    </div>

    <!-- Attendee Details Modal -->
    <div id="attendee-details-modal" class="hidden fixed inset-0 z-[150] w-full h-full overflow-y-auto items-center justify-center p-4 bg-slate-900/75 dark:bg-slate-950/80 backdrop-blur-md transition-opacity duration-300 ease-out opacity-0" style="top: 0 !important; left: 0 !important; right: 0 !important; bottom: 0 !important; margin: 0 !important;">
        <div id="attendee-details-modal-content" class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800/80 p-6 sm:p-8 max-w-lg w-full shadow-2xl transition-all duration-300 ease-out transform scale-95 opacity-0 text-left space-y-6">
            <!-- Header -->
            <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800/80 pb-4">
                <h3 class="text-base font-extrabold text-slate-900 dark:text-white">Registration Details</h3>
                <button onclick="closeAttendeeModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-1.5 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-xl transition duration-150">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            <!-- Content Body (populated dynamically) -->
            <div id="attendee-modal-body" class="space-y-5">
                <!-- Javascript will load this dynamically -->
            </div>
        </div>
    </div>

    <!-- Client-Side Search & Filter Scripts for Analytics -->
    <script>
        // Inject registrations data securely for fast, interactive response
        const attendeeData = @json($event->registrations->keyBy('id'));
        let activeAnalyticsStatusFilter = 'all';

        function openAttendeeModal(id) {
            const attendee = attendeeData[id];
            if (!attendee) return;

            const body = document.getElementById('attendee-modal-body');
            if (!body) return;

            // Generate status badge
            let statusBadge = '';
            if (attendee.status === 'approved') {
                statusBadge = `<span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 bg-emerald-50 dark:bg-emerald-950/30 text-emerald-700 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-900/30 text-[10px] font-bold rounded-md uppercase tracking-wider"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Approved</span>`;
            } else if (attendee.status === 'declined') {
                statusBadge = `<span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 bg-red-50 dark:bg-red-950/30 text-red-700 dark:text-red-400 border border-red-100/30 dark:border-red-900/30 text-[10px] font-bold rounded-md uppercase tracking-wider"><span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Declined</span>`;
            } else {
                statusBadge = `<span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 bg-amber-50 dark:bg-amber-950/30 text-amber-700 dark:text-amber-400 border border-amber-100 dark:border-amber-900/30 text-[10px] font-bold rounded-md uppercase tracking-wider animate-pulse"><span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Pending</span>`;
            }

            // Attendance Status
            let attendanceBadge = '';
            if (attendee.status === 'approved') {
                attendanceBadge = attendee.attended
                    ? `<span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 bg-emerald-100 dark:bg-emerald-950/50 text-emerald-800 dark:text-emerald-300 border border-emerald-200/50 dark:border-emerald-900/30 text-[10px] font-bold rounded-md uppercase tracking-wider"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Attended</span>`
                    : `<span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 bg-slate-100 dark:bg-slate-950/40 text-slate-600 dark:text-slate-400 border border-slate-200/40 dark:border-slate-800/60 text-[10px] font-bold rounded-md uppercase tracking-wider"><span class="w-1.5 h-1.5 rounded-full bg-slate-450"></span> Absent</span>`;
            }

            // Custom Questionnaire responses html
            let customFieldsHtml = '';
            if (attendee.custom_fields && Object.keys(attendee.custom_fields).length > 0) {
                customFieldsHtml = `
                    <div class="space-y-2 pt-3 border-t border-slate-100 dark:border-slate-800/80">
                        <h4 class="text-[10px] font-bold uppercase text-slate-400 dark:text-slate-500 tracking-wider">Questionnaire Responses</h4>
                        <div class="space-y-3 bg-slate-50 dark:bg-slate-950 p-4 rounded-2xl border border-slate-100 dark:border-slate-800/80">
                `;
                
                for (const [key, value] of Object.entries(attendee.custom_fields)) {
                    if (value !== null && value !== '') {
                        customFieldsHtml += `
                            <div class="flex flex-col gap-0.5 text-left">
                                <span class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase tracking-wider">${key}</span>
                                <span class="text-xs text-slate-800 dark:text-slate-200 font-semibold mt-0.5 leading-relaxed">${value}</span>
                            </div>
                        `;
                    }
                }
                
                customFieldsHtml += `
                        </div>
                    </div>
                `;
            } else {
                customFieldsHtml = `
                    <div class="space-y-2 pt-3 border-t border-slate-100 dark:border-slate-800/80">
                        <h4 class="text-[10px] font-bold uppercase text-slate-400 dark:text-slate-500 tracking-wider">Questionnaire Responses</h4>
                        <p class="text-xs text-slate-400 dark:text-slate-500 italic text-left">No custom questionnaire responses supplied for this registration.</p>
                    </div>
                `;
            }

            // Parse Date
            let regDate = 'N/A';
            if (attendee.created_at) {
                const d = new Date(attendee.created_at);
                regDate = d.toLocaleDateString(undefined, { year: 'numeric', month: 'short', day: 'numeric' }) + ' • ' + d.toLocaleTimeString(undefined, { hour: 'numeric', minute: '2-digit' });
            }

            body.innerHTML = `
                <div class="flex items-center gap-3.5">
                    <span class="inline-flex items-center justify-center w-10 h-10 rounded-2xl bg-purple-50 dark:bg-purple-950/40 text-purple-600 dark:text-purple-400 font-extrabold text-sm uppercase shrink-0">
                        ${attendee.name.substring(0, 2)}
                    </span>
                    <div class="text-left">
                        <h4 class="text-sm font-bold text-slate-900 dark:text-white leading-none">${attendee.name}</h4>
                        <p class="text-[11px] text-slate-400 dark:text-slate-500 font-mono mt-2 leading-none select-all">${attendee.email}</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4.5 text-left border-t border-slate-100 dark:border-slate-800/80 pt-4.5">
                    <div class="flex flex-col gap-0.5">
                        <span class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase tracking-wider">Ticket Code</span>
                        <span class="text-xs font-mono font-bold text-purple-650 dark:text-purple-400">${attendee.ticket_code || '—'}</span>
                    </div>
                    <div class="flex flex-col gap-0.5">
                        <span class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase tracking-wider">Gender</span>
                        <span class="text-xs font-bold text-slate-700 dark:text-slate-200">${attendee.gender || 'Unspecified'}</span>
                    </div>
                    <div class="flex flex-col gap-0.5">
                        <span class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase tracking-wider">Submission Date</span>
                        <span class="text-xs text-slate-600 dark:text-slate-400 font-semibold">${regDate}</span>
                    </div>
                    <div class="flex flex-col gap-0.5">
                        <span class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase tracking-wider">Status & Attendance</span>
                        <div class="flex flex-wrap items-center gap-1.5 mt-0.5">
                            ${statusBadge}
                            ${attendanceBadge}
                        </div>
                    </div>
                </div>

                ${customFieldsHtml}
            `;

            // Open Modal Anim
            const modal = document.getElementById('attendee-details-modal');
            const content = document.getElementById('attendee-details-modal-content');
            if (!modal || !content) return;

            modal.classList.remove('hidden');
            modal.classList.add('flex');

            setTimeout(() => {
                modal.classList.remove('opacity-0');
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function closeAttendeeModal() {
            const modal = document.getElementById('attendee-details-modal');
            const content = document.getElementById('attendee-details-modal-content');
            if (!modal || !content) return;

            modal.classList.add('opacity-0');
            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-95', 'opacity-0');

            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }, 250);
        }

        // Close on backdrop click
        window.addEventListener('click', function(e) {
            const modal = document.getElementById('attendee-details-modal');
            if (e.target === modal) {
                closeAttendeeModal();
            }
        });

        function setAnalyticsStatusFilter(status) {
            activeAnalyticsStatusFilter = status;

            // Reset background & styles of all buttons
            const statuses = ['all', 'approved', 'pending', 'declined'];
            statuses.forEach(st => {
                const btn = document.getElementById('analytics-filter-btn-' + st);
                if (btn) {
                    if (st === status) {
                        btn.className = "px-2.5 py-1 rounded-lg bg-purple-500/10 dark:bg-purple-950/40 text-purple-600 dark:text-purple-400 border border-purple-500/20 transition duration-150";
                    } else {
                        btn.className = "px-2.5 py-1 rounded-lg text-slate-600 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-850 transition duration-150";
                    }
                }
            });

            // Run matching list update
            filterAnalytics();
        }

        function filterAnalytics() {
            const query = document.getElementById('analytics-search') ? document.getElementById('analytics-search').value.toLowerCase().trim() : '';
            const rows = document.querySelectorAll('.analytics-row');
            const noResults = document.getElementById('no-analytics-matched');

            let matchesCount = 0;

            rows.forEach(row => {
                const name = row.getAttribute('data-name') || '';
                const email = row.getAttribute('data-email') || '';
                const code = row.getAttribute('data-code') || '';
                const status = row.getAttribute('data-status') || '';
                const answers = row.getAttribute('data-answers') || '';

                const matchesSearch = name.includes(query) || email.includes(query) || code.includes(query) || answers.includes(query);
                const matchesStatus = activeAnalyticsStatusFilter === 'all' || status === activeAnalyticsStatusFilter;

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
    </script>
</div>