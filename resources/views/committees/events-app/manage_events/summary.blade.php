<!-- TAB 2: SUMMARY & ANALYTICS PANEL (REPORT MODULE) -->
@php
    $total = $event->registrations->count();
    $approved = $event->registrations->where('status', 'approved')->count();
    $pending = $event->registrations->where('status', 'pending')->count();
    $declined = $event->registrations->where('status', 'declined')->count();
    $appRate = $total > 0 ? round(($approved / $total) * 100) : 0;
    $pendRate = $total > 0 ? round(($pending / $total) * 100) : 0;
    $decRate = $total > 0 ? round(($declined / $total) * 100) : 0;

    // --- Age Demographics ---
    $kids = 0;       // < 12
    $youth = 0;      // 12-17
    $youngAdults = 0;// 18-30
    $adults = 0;     // 31-59
    $seniors = 0;    // 60+
    $unspecifiedAge = 0;

    foreach ($event->registrations as $reg) {
        if ($reg->birthday) {
            $age = $reg->age; // uses the model's age attribute
            if ($age === null) {
                $unspecifiedAge++;
            } elseif ($age < 12) {
                $kids++;
            } elseif ($age <= 17) {
                $youth++;
            } elseif ($age <= 30) {
                $youngAdults++;
            } elseif ($age <= 59) {
                $adults++;
            } else {
                $seniors++;
            }
        } else {
            $unspecifiedAge++;
        }
    }

    $kidsPct = $total > 0 ? round(($kids / $total) * 100) : 0;
    $youthPct = $total > 0 ? round(($youth / $total) * 100) : 0;
    $yaPct = $total > 0 ? round(($youngAdults / $total) * 100) : 0;
    $adultPct = $total > 0 ? round(($adults / $total) * 100) : 0;
    $seniorPct = $total > 0 ? round(($seniors / $total) * 100) : 0;
    $unspAgePct = $total > 0 ? round(($unspecifiedAge / $total) * 100) : 0;

    // --- Division Distribution ---
    $divisions = [];
    foreach ($event->registrations as $reg) {
        $div = trim($reg->division ?? '');
        if ($div === '') {
            $div = 'Unspecified';
        }
        if (!isset($divisions[$div])) {
            $divisions[$div] = 0;
        }
        $divisions[$div]++;
    }
    arsort($divisions); // sort by count descending
@endphp

<div id="tab-panel-summary" class="hidden space-y-6">
    <!-- Grid of Analytics Stats Cards (Row 1 KPI Bento) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Card 1: Total Submissions -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 p-4 shadow-sm flex flex-col justify-center hover:shadow-md transition-all duration-300">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-bold uppercase text-slate-400 dark:text-slate-500 tracking-wider">Total Submissions</span>
                <span class="inline-flex items-center justify-center w-7.5 h-7.5 rounded-lg bg-purple-50 dark:bg-purple-950/40 text-purple-600 dark:text-purple-400 border border-purple-100/50 dark:border-purple-900/30">
                    <i class="fa-solid fa-users text-xs"></i>
                </span>
            </div>
            <div class="mt-2.5">
                <h3 class="text-2xl font-extrabold text-slate-900 dark:text-white" id="stats-total-count">{{ $total }}</h3>
                <p class="text-[10px] text-slate-450 dark:text-slate-500 mt-0.5">Total RSVP forms filled</p>
            </div>
        </div>

        <!-- Card 2: Approved Attendee Queue -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 p-4 shadow-sm flex flex-col justify-center hover:shadow-md transition-all duration-300">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-bold uppercase text-slate-400 dark:text-slate-500 tracking-wider">Approved Seats</span>
                <span class="inline-flex items-center justify-center w-7.5 h-7.5 rounded-lg bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 border border-emerald-100/50 dark:border-emerald-900/30">
                    <i class="fa-solid fa-circle-check text-xs"></i>
                </span>
            </div>
            <div class="mt-2.5">
                <h3 class="text-2xl font-extrabold text-slate-900 dark:text-white font-sans" id="stats-approved-count">{{ $approved }}</h3>
                <p class="text-[10px] text-slate-450 dark:text-slate-500 mt-0.5">
                    Approval rate is <span class="font-bold text-emerald-600 dark:text-emerald-400" id="stats-app-rate">{{ $appRate }}%</span>
                </p>
            </div>
        </div>

        <!-- Card 3: Pending Queue -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 p-4 shadow-sm flex flex-col justify-center hover:shadow-md transition-all duration-300">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-bold uppercase text-slate-400 dark:text-slate-500 tracking-wider">Awaiting Review</span>
                <span class="inline-flex items-center justify-center w-7.5 h-7.5 rounded-lg bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 border border-amber-100/50 dark:border-amber-900/30">
                    <i class="fa-solid fa-hourglass-half text-xs"></i>
                </span>
            </div>
            <div class="mt-2.5">
                <h3 class="text-2xl font-extrabold text-slate-900 dark:text-white font-sans" id="stats-pending-count">{{ $pending }}</h3>
                <p class="text-[10px] text-slate-450 dark:text-slate-500 mt-0.5">Applications in queue</p>
            </div>
        </div>

        <!-- Card 4: Declined Count -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 p-4 shadow-sm flex flex-col justify-center hover:shadow-md transition-all duration-300">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-bold uppercase text-slate-400 dark:text-slate-500 tracking-wider">Declined Seats</span>
                <span class="inline-flex items-center justify-center w-7.5 h-7.5 rounded-lg bg-red-50 dark:bg-red-950/40 text-red-600 dark:text-red-400 border border-red-100/50 dark:border-red-900/30">
                    <i class="fa-solid fa-circle-xmark text-xs"></i>
                </span>
            </div>
            <div class="mt-2.5">
                <h3 class="text-2xl font-extrabold text-slate-900 dark:text-white font-sans" id="stats-declined-count">{{ $declined }}</h3>
                <p class="text-[10px] text-slate-450 dark:text-slate-500 mt-0.5">Requests rejected</p>
            </div>
        </div>
    </div>

    <!-- Row 2: Bento Grid for Seat, Gender, and Age Breakdown -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Seat Occupancy Breakdown -->
        <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 p-6 shadow-sm lg:col-span-2 space-y-6 hover:shadow-md transition-all duration-300">
            <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-3.5">
                <h4 class="font-bold text-slate-900 dark:text-white text-base flex items-center gap-2">
                    <i class="fa-solid fa-chair text-purple-600 dark:text-purple-400 text-sm"></i>
                    <span>Seat Occupancy Breakdown</span>
                </h4>
                <span class="text-[10px] font-extrabold uppercase px-2 py-0.5 rounded-md bg-purple-50 dark:bg-purple-950/40 text-purple-600 dark:text-purple-400">Capacity Overview</span>
            </div>
            
            <div class="space-y-5">
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

        <!-- Gender Demographics -->
        <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 p-6 shadow-sm space-y-6 hover:shadow-md transition-all duration-300">
            <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-3.5">
                <h4 class="font-bold text-slate-900 dark:text-white text-base flex items-center gap-2">
                    <i class="fa-solid fa-venus-mars text-purple-600 dark:text-purple-400 text-sm"></i>
                    <span>Gender Demographics</span>
                </h4>
                <span class="text-[10px] font-extrabold uppercase px-2 py-0.5 rounded-md bg-purple-50 dark:bg-purple-950/40 text-purple-600 dark:text-purple-400">Genders</span>
            </div>
            
            <div class="space-y-4">
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
                <div class="space-y-1">
                    <div class="flex justify-between text-xs font-semibold">
                        <span class="text-slate-650 dark:text-slate-400 flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span> Male
                        </span>
                        <span class="text-slate-800 dark:text-slate-200">{{ $males }} ({{ $malePct }}%)</span>
                    </div>
                    <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-1.5 overflow-hidden">
                        <div class="bg-blue-500 h-full rounded-full transition-all duration-500" style="width: {{ $malePct }}%"></div>
                    </div>
                </div>

                <!-- Female Progress -->
                <div class="space-y-1">
                    <div class="flex justify-between text-xs font-semibold">
                        <span class="text-slate-650 dark:text-slate-400 flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-pink-500"></span> Female
                        </span>
                        <span class="text-slate-800 dark:text-slate-200">{{ $females }} ({{ $femalePct }}%)</span>
                    </div>
                    <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-1.5 overflow-hidden">
                        <div class="bg-pink-500 h-full rounded-full transition-all duration-500" style="width: {{ $femalePct }}%"></div>
                    </div>
                </div>

                <!-- LGBTQ+ Progress -->
                <div class="space-y-1">
                    <div class="flex justify-between text-xs font-semibold">
                        <span class="text-slate-650 dark:text-slate-400 flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-purple-500"></span> LGBTQ+
                        </span>
                        <span class="text-slate-800 dark:text-slate-200">{{ $lgbtq }} ({{ $lgbtPct }}%)</span>
                    </div>
                    <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-1.5 overflow-hidden">
                        <div class="bg-purple-500 h-full rounded-full transition-all duration-500" style="width: {{ $lgbtPct }}%"></div>
                    </div>
                </div>

                <!-- Others/Unspecified Progress combined for space -->
                @if($others > 0 || $unspecified > 0)
                <div class="space-y-1">
                    <div class="flex justify-between text-xs font-semibold">
                        <span class="text-slate-650 dark:text-slate-400 flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Others / Unspec.
                        </span>
                        <span class="text-slate-800 dark:text-slate-200">{{ $others + $unspecified }} ({{ $otherPct + $unspPct }}%)</span>
                    </div>
                    <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-1.5 overflow-hidden">
                        <div class="bg-slate-400 h-full rounded-full transition-all duration-500" style="width: {{ $otherPct + $unspPct }}%"></div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Age Distribution -->
        <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 p-6 shadow-sm space-y-6 hover:shadow-md transition-all duration-300">
            <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-3.5">
                <h4 class="font-bold text-slate-900 dark:text-white text-base flex items-center gap-2">
                    <i class="fa-solid fa-cake-candles text-purple-600 dark:text-purple-400 text-sm"></i>
                    <span>Age Distribution</span>
                </h4>
                <span class="text-[10px] font-extrabold uppercase px-2 py-0.5 rounded-md bg-purple-50 dark:bg-purple-950/40 text-purple-600 dark:text-purple-400">Ages</span>
            </div>

            <div class="space-y-3">
                <!-- Kids -->
                <div class="space-y-1">
                    <div class="flex justify-between text-xs font-semibold">
                        <span class="text-slate-655 dark:text-slate-400 flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Kids (<12)
                        </span>
                        <span class="text-slate-800 dark:text-slate-200">{{ $kids }} ({{ $kidsPct }}%)</span>
                    </div>
                    <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-1.5 overflow-hidden">
                        <div class="bg-rose-500 h-full rounded-full transition-all duration-500" style="width: {{ $kidsPct }}%"></div>
                    </div>
                </div>

                <!-- Youth -->
                <div class="space-y-1">
                    <div class="flex justify-between text-xs font-semibold">
                        <span class="text-slate-655 dark:text-slate-400 flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Youth (12-17)
                        </span>
                        <span class="text-slate-800 dark:text-slate-200">{{ $youth }} ({{ $youthPct }}%)</span>
                    </div>
                    <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-1.5 overflow-hidden">
                        <div class="bg-amber-500 h-full rounded-full transition-all duration-500" style="width: {{ $youthPct }}%"></div>
                    </div>
                </div>

                <!-- Young Adults -->
                <div class="space-y-1">
                    <div class="flex justify-between text-xs font-semibold">
                        <span class="text-slate-655 dark:text-slate-400 flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-purple-500"></span> Young Adults (18-30)
                        </span>
                        <span class="text-slate-800 dark:text-slate-200">{{ $youngAdults }} ({{ $yaPct }}%)</span>
                    </div>
                    <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-1.5 overflow-hidden">
                        <div class="bg-purple-500 h-full rounded-full transition-all duration-500" style="width: {{ $yaPct }}%"></div>
                    </div>
                </div>

                <!-- Adults -->
                <div class="space-y-1">
                    <div class="flex justify-between text-xs font-semibold">
                        <span class="text-slate-655 dark:text-slate-400 flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Adults (31-59)
                        </span>
                        <span class="text-slate-800 dark:text-slate-200">{{ $adults }} ({{ $adultPct }}%)</span>
                    </div>
                    <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-1.5 overflow-hidden">
                        <div class="bg-emerald-500 h-full rounded-full transition-all duration-500" style="width: {{ $adultPct }}%"></div>
                    </div>
                </div>

                <!-- Seniors -->
                <div class="space-y-1">
                    <div class="flex justify-between text-xs font-semibold">
                        <span class="text-slate-655 dark:text-slate-400 flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span> Seniors (60+)
                        </span>
                        <span class="text-slate-800 dark:text-slate-200">{{ $seniors }} ({{ $seniorPct }}%)</span>
                    </div>
                    <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-1.5 overflow-hidden">
                        <div class="bg-blue-500 h-full rounded-full transition-all duration-500" style="width: {{ $seniorPct }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Row 3: Division Distribution Bento Block -->
    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 p-6 shadow-sm space-y-6 hover:shadow-md transition-all duration-300">
        <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-3.5">
            <h4 class="font-bold text-slate-900 dark:text-white text-base flex items-center gap-2">
                <i class="fa-solid fa-diagram-project text-purple-600 dark:text-purple-400 text-sm"></i>
                <span>Division Distribution</span>
            </h4>
            <span class="text-[10px] font-extrabold uppercase px-2 py-0.5 rounded-md bg-purple-50 dark:bg-purple-950/40 text-purple-600 dark:text-purple-400">Divisions</span>
        </div>

        @if(count($divisions) === 0 || (count($divisions) === 1 && isset($divisions['Unspecified']) && $divisions['Unspecified'] === 0))
            <div class="text-center py-6 text-slate-400 dark:text-slate-500 text-xs italic">
                No division data available for current registrants.
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach($divisions as $divisionName => $count)
                    @php
                        $divPct = $total > 0 ? round(($count / $total) * 100) : 0;
                    @endphp
                    <div class="p-4 rounded-2xl bg-slate-50/50 dark:bg-slate-950/20 border border-slate-200/50 dark:border-slate-800/80 flex flex-col justify-between space-y-3">
                        <div class="flex justify-between items-start gap-2">
                            <span class="text-xs font-bold text-slate-700 dark:text-slate-350 truncate" title="{{ $divisionName }}">{{ $divisionName }}</span>
                            <span class="px-2 py-0.5 bg-purple-50 dark:bg-purple-950/50 border border-purple-100/50 dark:border-purple-900/30 text-purple-600 dark:text-purple-400 text-[10px] font-extrabold rounded-md whitespace-nowrap shrink-0">{{ $count }} {{ Str::plural('attendee', $count) }}</span>
                        </div>
                        <div class="space-y-1">
                            <div class="w-full bg-slate-150 dark:bg-slate-800 h-1.5 rounded-full overflow-hidden">
                                <div class="bg-purple-500 h-full rounded-full transition-all duration-500" style="width: {{ $divPct }}%"></div>
                            </div>
                            <span class="text-[9px] font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wide">{{ $divPct }}% of total</span>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
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

    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 p-6 sm:p-8 shadow-sm space-y-6">
        <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4 border-b border-slate-100 dark:border-slate-850 pb-4.5">
            <div class="text-left">
                <h4 class="font-bold text-slate-900 dark:text-white text-base flex items-center gap-2">
                    <i class="fa-solid fa-address-book text-purple-600 dark:text-purple-400 text-sm"></i>
                    <span>Attendee Directory & Responses</span>
                </h4>
                <p class="text-xs text-slate-550 dark:text-slate-400 mt-1">Directory of all registered attendees. Filter by status, division, gender, or age group.</p>
            </div>
            
            <!-- Filters & Actions Header Group -->
            <div class="flex flex-wrap items-center gap-3 shrink-0">
                <!-- Status Buttons -->
                <div class="flex flex-wrap items-center gap-1 bg-slate-50 dark:bg-slate-950 p-1 rounded-xl border border-slate-100 dark:border-slate-800/80 select-none text-[11px] font-semibold">
                    <button type="button" onclick="setAnalyticsStatusFilter('all')" id="analytics-filter-btn-all" class="px-2.5 py-1 rounded-lg bg-purple-500/10 dark:bg-purple-950/40 text-purple-600 dark:text-purple-400 border border-purple-500/20 transition duration-150">
                        All ({{ $total }})
                    </button>
                    <button type="button" onclick="setAnalyticsStatusFilter('approved')" id="analytics-filter-btn-approved" class="px-2.5 py-1 rounded-lg text-slate-550 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-850 transition duration-150">
                        Approved ({{ $approved }})
                    </button>
                    <button type="button" onclick="setAnalyticsStatusFilter('pending')" id="analytics-filter-btn-pending" class="px-2.5 py-1 rounded-lg text-slate-550 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-850 transition duration-150">
                        Pending ({{ $pending }})
                    </button>
                    <button type="button" onclick="setAnalyticsStatusFilter('declined')" id="analytics-filter-btn-declined" class="px-2.5 py-1 rounded-lg text-slate-550 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-850 transition duration-150">
                        Declined ({{ $declined }})
                    </button>
                </div>

                <!-- Export PDF Action Button -->
                <a href="{{ route('committees.events.export_summary_pdf', $event) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-purple-600 hover:bg-purple-750 text-white text-[11px] font-semibold border border-transparent shadow-xs transition duration-150 shrink-0">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span>Export PDF</span>
                </a>
            </div>
        </div>

        <!-- Shaded 4-Column Filters Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 bg-slate-50 dark:bg-slate-950 p-4.5 rounded-2xl border border-slate-200/60 dark:border-slate-800/80 shadow-xs animate-fade-in">
            <!-- Search Filter -->
            <div class="relative bg-white dark:bg-slate-900 rounded-xl border border-slate-200/80 dark:border-slate-800/80 focus-within:border-purple-500 dark:focus-within:border-purple-400 focus-within:ring-4 focus-within:ring-purple-500/10 dark:focus-within:ring-purple-500/10 hover:border-slate-300 dark:hover:border-slate-700 shadow-xs transition duration-200">
                <span class="absolute inset-y-0 left-3.5 flex items-center pointer-events-none text-slate-400 dark:text-slate-500">
                    <i class="fa-solid fa-magnifying-glass text-xs text-purple-600/80 dark:text-purple-400/80"></i>
                </span>
                <input type="text" id="analytics-search" oninput="filterAnalytics()" placeholder="Search attendees/responses..." class="w-full pl-10 pr-4 py-3 rounded-xl border-0 text-slate-700 dark:text-slate-200 text-xs focus:ring-0 focus:outline-none bg-transparent placeholder-slate-400 dark:placeholder-slate-500">
            </div>

            <!-- Gender Filter -->
            <div class="relative bg-white dark:bg-slate-900 rounded-xl border border-slate-200/80 dark:border-slate-800/80 focus-within:border-purple-500 dark:focus-within:border-purple-500 focus-within:ring-4 focus-within:ring-purple-500/10 hover:border-slate-300 dark:hover:border-slate-700 shadow-xs transition duration-200">
                <span class="absolute inset-y-0 left-3.5 flex items-center pointer-events-none text-slate-400 dark:text-slate-500">
                    <i class="fa-solid fa-venus-mars text-xs text-purple-600/80 dark:text-purple-400/80"></i>
                </span>
                <select id="analytics-filter-gender" onchange="filterAnalytics()" class="w-full pl-10 pr-8 py-3 rounded-xl border-0 text-slate-700 dark:text-slate-200 text-xs focus:ring-0 focus:outline-none bg-white dark:bg-slate-900 appearance-none cursor-pointer">
                    <option value="all" class="bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200">All Genders</option>
                    <option value="Male" class="bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200">Male</option>
                    <option value="Female" class="bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200">Female</option>
                    <option value="LGBTQ+" class="bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200">LGBTQ+</option>
                    <option value="Others" class="bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200">Others</option>
                </select>
                <span class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-slate-400 dark:text-slate-500">
                    <i class="fa-solid fa-chevron-down text-[10px]"></i>
                </span>
            </div>

            <!-- Division Filter -->
            <div class="relative bg-white dark:bg-slate-900 rounded-xl border border-slate-200/80 dark:border-slate-800/80 focus-within:border-purple-500 dark:focus-within:border-purple-500 focus-within:ring-4 focus-within:ring-purple-500/10 hover:border-slate-300 dark:hover:border-slate-700 shadow-xs transition duration-200">
                <span class="absolute inset-y-0 left-3.5 flex items-center pointer-events-none text-slate-400 dark:text-slate-500">
                    <i class="fa-solid fa-diagram-project text-xs text-purple-600/80 dark:text-purple-400/80"></i>
                </span>
                <select id="analytics-filter-division" onchange="filterAnalytics()" class="w-full pl-10 pr-8 py-3 rounded-xl border-0 text-slate-700 dark:text-slate-200 text-xs focus:ring-0 focus:outline-none bg-white dark:bg-slate-900 appearance-none cursor-pointer">
                    <option value="all" class="bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200">All Divisions</option>
                    @foreach(array_keys($divisions) as $divName)
                        @if($divName !== 'Unspecified')
                            <option value="{{ $divName }}" class="bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200">{{ $divName }}</option>
                        @endif
                    @endforeach
                    <option value="Unspecified" class="bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200">Unspecified / Blank</option>
                </select>
                <span class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-slate-400 dark:text-slate-500">
                    <i class="fa-solid fa-chevron-down text-[10px]"></i>
                </span>
            </div>

            <!-- Age Filter -->
            <div class="relative bg-white dark:bg-slate-900 rounded-xl border border-slate-200/80 dark:border-slate-800/80 focus-within:border-purple-500 dark:focus-within:border-purple-500 focus-within:ring-4 focus-within:ring-purple-500/10 hover:border-slate-300 dark:hover:border-slate-700 shadow-xs transition duration-200">
                <span class="absolute inset-y-0 left-3.5 flex items-center pointer-events-none text-slate-400 dark:text-slate-500">
                    <i class="fa-solid fa-cake-candles text-xs text-purple-600/80 dark:text-purple-400/80"></i>
                </span>
                <select id="analytics-filter-age" onchange="filterAnalytics()" class="w-full pl-10 pr-8 py-3 rounded-xl border-0 text-slate-700 dark:text-slate-200 text-xs focus:ring-0 focus:outline-none bg-white dark:bg-slate-900 appearance-none cursor-pointer">
                    <option value="all" class="bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200">All Ages</option>
                    <option value="kids" class="bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200">Kids (< 12 yrs)</option>
                    <option value="youth" class="bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200">Youth (12-17 yrs)</option>
                    <option value="young_adults" class="bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200">Young Adults (18-30 yrs)</option>
                    <option value="adults" class="bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200">Adults (31-59 yrs)</option>
                    <option value="seniors" class="bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200">Seniors (60+ yrs)</option>
                </select>
                <span class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-slate-400 dark:text-slate-500">
                    <i class="fa-solid fa-chevron-down text-[10px]"></i>
                </span>
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
                    <table class="w-full text-left text-xs border-collapse min-w-[1000px]">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-950 border-b border-slate-100 dark:border-slate-800/80 font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 text-[10px]">
                                <th class="py-4 px-6">Attendee Profile</th>
                                <th class="py-4 px-6">Ticket Code</th>
                                <th class="py-4 px-6">Age</th>
                                <th class="py-4 px-6">Gender</th>
                                <th class="py-4 px-6">Division</th>
                                <th class="py-4 px-6">Status</th>
                                <th class="py-4 px-6 text-right">Actions</th>
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
                                    data-id="{{ $reg->id }}"
                                    data-name="{{ strtolower($reg->name) }}"
                                    data-email="{{ strtolower($reg->email) }}"
                                    data-code="{{ strtolower($reg->ticket_code ?? '') }}"
                                    data-status="{{ $reg->status }}"
                                    data-gender="{{ $reg->gender ?? 'Unspecified' }}"
                                    data-division="{{ $reg->division ?? 'Unspecified' }}"
                                    data-age="{{ $reg->birthday ? $reg->age : -1 }}"
                                    data-answers="{{ strtolower($answersText) }}">
                                    
                                    <!-- Profile (Initials Avatar, Name & Email) -->
                                    <td class="py-5 px-6 font-semibold text-slate-800 dark:text-slate-200">
                                        <div class="flex items-center gap-3">
                                            <span class="inline-flex items-center justify-center w-8.5 h-8.5 rounded-xl bg-purple-50 dark:bg-purple-950/40 text-purple-600 dark:text-purple-400 font-bold text-xs uppercase shrink-0">
                                                {{ substr($reg->name, 0, 2) }}
                                            </span>
                                            <div class="text-left">
                                                <span class="block text-slate-900 dark:text-slate-100 font-bold text-xs leading-tight">{{ $reg->name }}</span>
                                                <span class="block text-[10px] text-slate-400 dark:text-slate-500/80 font-mono mt-1 leading-none select-all">{{ $reg->email }}</span>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Ticket Code -->
                                    <td class="py-5 px-6">
                                        @if($reg->ticket_code)
                                            <span class="px-2 py-1 bg-purple-50 dark:bg-purple-950/30 border border-purple-100/50 dark:border-purple-900/20 rounded-md font-mono font-bold text-[11px] text-purple-600 dark:text-purple-400">
                                                {{ $reg->ticket_code }}
                                            </span>
                                        @else
                                            <span class="text-slate-400 dark:text-slate-600 font-medium">—</span>
                                        @endif
                                    </td>

                                    <!-- Age -->
                                    <td class="py-5 px-6 font-semibold text-slate-700 dark:text-slate-300">
                                        {{ $reg->birthday ? $reg->age . ' yrs' : '—' }}
                                    </td>

                                    <!-- Gender -->
                                    <td class="py-5 px-6">
                                        @if(($reg->gender ?? 'Unspecified') === 'Male')
                                            <span class="px-2.5 py-1 bg-blue-500/10 border border-blue-500/20 rounded-lg font-semibold text-blue-600 dark:text-blue-400 text-[10px] whitespace-nowrap">
                                                Male
                                            </span>
                                        @elseif(($reg->gender ?? 'Unspecified') === 'Female')
                                            <span class="px-2.5 py-1 bg-pink-500/10 border border-pink-500/20 rounded-lg font-semibold text-pink-600 dark:text-pink-400 text-[10px] whitespace-nowrap">
                                                Female
                                            </span>
                                        @elseif(($reg->gender ?? 'Unspecified') === 'LGBTQ+')
                                            <span class="px-2.5 py-1 bg-purple-500/10 border border-purple-500/20 rounded-lg font-semibold text-purple-600 dark:text-purple-400 text-[10px] whitespace-nowrap">
                                                LGBTQ+
                                            </span>
                                        @else
                                            <span class="px-2.5 py-1 bg-slate-500/10 border border-slate-500/20 rounded-lg font-semibold text-slate-655 dark:text-slate-400 text-[10px] whitespace-nowrap">
                                                {{ $reg->gender ?? 'Unspecified' }}
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Division -->
                                    <td class="py-5 px-6">
                                        @if($reg->division)
                                            <span class="px-2.5 py-1 bg-purple-50 dark:bg-purple-950/30 text-purple-700 dark:text-purple-400 border border-purple-100/50 dark:border-purple-900/20 rounded-lg font-bold text-[10px] whitespace-nowrap">
                                                {{ $reg->division }}
                                            </span>
                                        @else
                                            <span class="text-slate-400 dark:text-slate-600 font-medium">—</span>
                                        @endif
                                    </td>

                                    <!-- Status Badge -->
                                    <td class="py-5 px-6">
                                        @if($reg->status === 'approved')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-400 border border-emerald-100/80 dark:border-emerald-900/30 text-[10px] font-bold rounded-lg uppercase tracking-wide">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                Approved
                                            </span>
                                        @elseif($reg->status === 'declined')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-red-50 dark:bg-red-950/40 text-red-700 dark:text-red-400 border border-red-100/80 dark:border-red-900/30 text-[10px] font-bold rounded-lg uppercase tracking-wide">
                                                <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                                Declined
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-400 border border-amber-100/80 dark:border-amber-900/30 text-[10px] font-bold rounded-lg uppercase tracking-wide animate-pulse">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                                Pending
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Actions (Edit & View) -->
                                    <td class="py-5 px-6 text-right whitespace-nowrap">
                                        <div class="flex items-center justify-end gap-2">
                                            <button type="button" onclick="openEditRegistrationModal({{ $reg->id }})" class="text-[11px] font-bold text-amber-600 dark:text-amber-400 hover:text-white dark:hover:text-slate-900 hover:bg-amber-500 dark:hover:bg-amber-500 bg-amber-500/5 border border-amber-500/20 px-3 py-1.5 rounded-xl transition duration-150 active:scale-95 flex items-center gap-1 shadow-sm">
                                                <i class="fa-solid fa-pen text-[9px]"></i> Edit
                                            </button>
                                            <button type="button" onclick="openAttendeeModal({{ $reg->id }})" class="text-[11px] font-bold text-purple-600 dark:text-purple-400 hover:text-white dark:hover:text-slate-900 hover:bg-purple-600 dark:hover:bg-purple-600 bg-purple-500/5 border border-purple-500/20 px-3 py-1.5 rounded-xl transition duration-150 active:scale-95 flex items-center gap-1 shadow-sm">
                                                <i class="fa-solid fa-eye text-[9px]"></i> View
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Table empty search state -->
                <div id="no-analytics-matched" class="hidden text-center py-12 text-slate-450 dark:text-slate-500 italic">
                    No matching attendees or responses found.
                </div>
            @endif
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

    <!-- Edit Attendee Registration Modal -->
    @include('committees.events-app.events-components.edit_registration_modal')

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

        function openEditRegistrationModal(id) {
            const attendee = attendeeData[id];
            if (!attendee) return;

            const form = document.getElementById('edit-registration-form');
            if (form) {
                form.action = `/committees/registrations/${id}`;
            }

            // Populate text/select inputs
            const nameInput = document.getElementById('edit_reg_name');
            const emailInput = document.getElementById('edit_reg_email');
            const ticketInput = document.getElementById('edit_reg_ticket_code');
            const genderInput = document.getElementById('edit_reg_gender');
            const divisionInput = document.getElementById('edit_reg_division');
            const birthdayInput = document.getElementById('edit_reg_birthday');

            if (nameInput) nameInput.value = attendee.name;
            if (emailInput) emailInput.value = attendee.email;
            if (ticketInput) ticketInput.value = attendee.ticket_code || '';
            if (genderInput) genderInput.value = attendee.gender || 'Unspecified';

            if (divisionInput) {
                const divisionVal = attendee.division || '';
                const optionExists = Array.from(divisionInput.options).some(opt => opt.value === divisionVal);
                
                const customWrapper = document.getElementById('edit_reg_division_custom_wrapper');
                const customInput = document.getElementById('edit_reg_division_custom');

                if (divisionVal === '') {
                    divisionInput.value = '';
                    if (customWrapper) customWrapper.classList.add('hidden');
                    divisionInput.setAttribute('name', 'division');
                    if (customInput) {
                        customInput.removeAttribute('name');
                        customInput.value = '';
                    }
                } else if (optionExists) {
                    divisionInput.value = divisionVal;
                    if (customWrapper) customWrapper.classList.add('hidden');
                    divisionInput.setAttribute('name', 'division');
                    if (customInput) {
                        customInput.removeAttribute('name');
                        customInput.value = '';
                    }
                } else {
                    divisionInput.value = '__custom__';
                    if (customWrapper) customWrapper.classList.remove('hidden');
                    divisionInput.removeAttribute('name');
                    if (customInput) {
                        customInput.setAttribute('name', 'division');
                        customInput.value = divisionVal;
                    }
                }
            }

            if (birthdayInput) {
                if (attendee.birthday) {
                    const d = new Date(attendee.birthday);
                    const yyyy = d.getFullYear();
                    const mm = String(d.getMonth() + 1).padStart(2, '0');
                    const dd = String(d.getDate()).padStart(2, '0');
                    birthdayInput.value = `${yyyy}-${mm}-${dd}`;
                } else {
                    birthdayInput.value = '';
                }
            }

            // Populate custom fields dynamically
            const customContainer = document.getElementById('edit-custom-fields-container');
            if (customContainer) {
                customContainer.innerHTML = '';
                if (attendee.custom_fields && Object.keys(attendee.custom_fields).length > 0) {
                    for (const [key, value] of Object.entries(attendee.custom_fields)) {
                        customContainer.innerHTML += `
                            <div class="space-y-1.5 text-left">
                                <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">${key}</label>
                                <input type="text" name="custom_fields[${key}]" value="${value || ''}" 
                                    class="w-full rounded-xl border border-slate-200 dark:border-slate-800/80 py-3 px-4 text-slate-850 dark:text-slate-200 text-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-white dark:bg-slate-950 focus:bg-white dark:focus:bg-slate-950 shadow-sm transition-all duration-300">
                            </div>
                        `;
                    }
                } else {
                    customContainer.innerHTML = `<p class="text-xs text-slate-450 dark:text-slate-500 italic text-left">No custom fields filled for this registration.</p>`;
                }
            }

            // Handle group & companions
            const groupToggle = document.getElementById('edit-reg-group-toggle');
            const toggleWrapper = document.getElementById('edit-reg-companions-wrapper');
            const companionsContainer = document.getElementById('edit-reg-companions-container');
            const companionNotice = document.getElementById('edit-reg-companion-notice');
            const toggleTitle = document.getElementById('edit-reg-group-toggle-title');
            const toggleDesc = document.getElementById('edit-reg-group-toggle-desc');
            const enabledHidden = document.getElementById('edit-reg-companions-enabled-hidden');

            if (groupToggle && companionsContainer) {
                companionsContainer.innerHTML = '';
                editCompanionIndex = 0;

                if (attendee.group_code) {
                    if (attendee.is_group_primary) {
                        // It is a primary group registrant
                        if (companionNotice) companionNotice.classList.add('hidden');
                        if (toggleTitle) toggleTitle.innerText = "Register with companions / group?";
                        if (toggleDesc) toggleDesc.innerText = "Enable this to register multiple people under a single group code.";

                        // Find all companions
                        const companions = [];
                        for (const [key, value] of Object.entries(attendeeData)) {
                            if (value.group_code === attendee.group_code && !value.is_group_primary) {
                                companions.push(value);
                            }
                        }

                        if (companions.length > 0) {
                            groupToggle.checked = true;
                            if (enabledHidden) enabledHidden.value = "1";
                            if (toggleWrapper) toggleWrapper.classList.remove('hidden');

                            companions.forEach(comp => {
                                addEditCompanionField(comp);
                            });
                        } else {
                            groupToggle.checked = false;
                            if (enabledHidden) enabledHidden.value = "0";
                            if (toggleWrapper) toggleWrapper.classList.add('hidden');
                        }
                    } else {
                        // It is a companion registrant
                        if (companionNotice) companionNotice.classList.remove('hidden');
                        if (toggleTitle) toggleTitle.innerText = "Detach from Group Registration?";
                        if (toggleDesc) toggleDesc.innerText = "Enable this to convert this companion into an independent individual attendee.";

                        groupToggle.checked = false; // By default we keep them in the group, unless they toggle to detach
                        if (enabledHidden) enabledHidden.value = "0";
                        if (toggleWrapper) toggleWrapper.classList.add('hidden');
                    }
                } else {
                    // It is an individual registrant
                    if (companionNotice) companionNotice.classList.add('hidden');
                    if (toggleTitle) toggleTitle.innerText = "Register with companions / group?";
                    if (toggleDesc) toggleDesc.innerText = "Enable this to register multiple people under a single group code.";

                    groupToggle.checked = false;
                    if (enabledHidden) enabledHidden.value = "0";
                    if (toggleWrapper) toggleWrapper.classList.add('hidden');
                }
            }

            // Open Modal Anim
            const modal = document.getElementById('edit-registration-modal');
            const content = document.getElementById('edit-registration-modal-content');
            if (!modal || !content) return;

            modal.classList.remove('hidden');
            modal.classList.add('flex');

            setTimeout(() => {
                modal.classList.remove('opacity-0');
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function closeEditRegistrationModal() {
            const modal = document.getElementById('edit-registration-modal');
            const content = document.getElementById('edit-registration-modal-content');
            if (!modal || !content) return;

            modal.classList.add('opacity-0');
            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-95', 'opacity-0');

            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }, 250);
        }

        // Companion Edit Helper Functions
        let editCompanionIndex = 0;

        function toggleEditRegistrationGroup(checkbox) {
            const wrapper = document.getElementById('edit-reg-companions-wrapper');
            const hiddenInput = document.getElementById('edit-reg-companions-enabled-hidden');
            if (!wrapper || !hiddenInput) return;

            if (checkbox.checked) {
                wrapper.classList.remove('hidden');
                hiddenInput.value = "1";
                const container = document.getElementById('edit-reg-companions-container');
                if (container && container.children.length === 0) {
                    addEditCompanionField();
                }
            } else {
                wrapper.classList.add('hidden');
                hiddenInput.value = "0";
            }
        }

        function addEditCompanionField(comp = null) {
            const container = document.getElementById('edit-reg-companions-container');
            if (!container) return;

            const card = document.createElement('div');
            card.className = 'bg-white dark:bg-slate-900 border border-slate-150 dark:border-slate-800 p-5 rounded-2xl space-y-4 shadow-sm relative animate-fade-in text-left';
            card.id = `edit-companion-card-${editCompanionIndex}`;

            const compId = comp ? comp.id : '';
            const compName = comp ? comp.name : '';
            const compEmail = comp ? comp.email : '';
            // If companion email is a virtual sako companion email, we show it as empty
            const displayEmail = (compEmail && compEmail.includes('@sako-companion.local')) ? '' : compEmail;
            const compGender = comp ? comp.gender : '';
            const compBirthday = comp ? comp.birthday : '';
            const compDivision = comp ? comp.division : '';

            let bdayVal = '';
            if (compBirthday) {
                const d = new Date(compBirthday);
                const yyyy = d.getFullYear();
                const mm = String(d.getMonth() + 1).padStart(2, '0');
                const dd = String(d.getDate()).padStart(2, '0');
                bdayVal = `${yyyy}-${mm}-${dd}`;
            }

            card.innerHTML = `
                <input type="hidden" name="companions[${editCompanionIndex}][id]" value="${compId}">
                <div class="flex items-center justify-between border-b border-slate-50 dark:border-slate-850 pb-2.5">
                    <span class="text-[10px] font-extrabold text-purple-650 dark:text-purple-400 uppercase tracking-widest">Companion #${editCompanionIndex + 1}</span>
                    <button type="button" onclick="removeEditCompanionField(${editCompanionIndex})" class="text-rose-500 hover:text-rose-650 hover:bg-rose-50 dark:hover:bg-rose-950/25 p-1 rounded-lg transition">
                        <i class="fa-solid fa-trash-can text-sm"></i>
                    </button>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    <!-- Companion Name -->
                    <div class="space-y-1.5">
                        <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Full Name <span class="text-rose-500">*</span></label>
                        <input type="text" name="companions[${editCompanionIndex}][name]" value="${compName}" required placeholder="Full Name"
                            class="w-full rounded-xl border border-slate-200 dark:border-slate-800/80 py-2.5 px-3.5 text-slate-850 dark:text-slate-200 text-xs focus:border-purple-500 focus:outline-none bg-slate-50/50 dark:bg-slate-950 focus:bg-white transition-all duration-300">
                    </div>

                    <!-- Companion Email -->
                    <div class="space-y-1.5">
                        <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Email Address</label>
                        <input type="email" name="companions[${editCompanionIndex}][email]" value="${displayEmail}" placeholder="Leave blank if children/elderly"
                            class="w-full rounded-xl border border-slate-200 dark:border-slate-800/80 py-2.5 px-3.5 text-slate-850 dark:text-slate-200 text-xs focus:border-purple-500 focus:outline-none bg-slate-50/50 dark:bg-slate-950 focus:bg-white transition-all duration-300">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    <!-- Companion Gender -->
                    <div class="space-y-1.5">
                        <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Gender Identity <span class="text-rose-500">*</span></label>
                        <select name="companions[${editCompanionIndex}][gender]" required
                            class="w-full rounded-xl border border-slate-200 dark:border-slate-800/80 py-2.5 px-3.5 text-slate-850 dark:text-slate-200 text-xs focus:border-purple-500 focus:outline-none bg-slate-50/50 dark:bg-slate-950 focus:bg-white transition-all duration-300 cursor-pointer">
                            <option value="" disabled ${!compGender ? 'selected' : ''}>Select gender...</option>
                            <option value="Male" ${compGender === 'Male' ? 'selected' : ''}>Male</option>
                            <option value="Female" ${compGender === 'Female' ? 'selected' : ''}>Female</option>
                            <option value="LGBTQ+" ${compGender === 'LGBTQ+' ? 'selected' : ''}>LGBTQ+</option>
                            <option value="Others" ${compGender === 'Others' ? 'selected' : ''}>Others</option>
                        </select>
                    </div>

                    <!-- Companion Birthday -->
                    <div class="space-y-1.5">
                        <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Birth Date <span class="text-rose-500">*</span></label>
                        <input type="date" name="companions[${editCompanionIndex}][birthday]" value="${bdayVal}" required max="${new Date().toISOString().split('T')[0]}"
                            class="w-full rounded-xl border border-slate-200 dark:border-slate-800/80 py-2.5 px-3.5 text-slate-850 dark:text-slate-200 text-xs focus:border-purple-500 focus:outline-none bg-slate-50/50 dark:bg-slate-950 focus:bg-white transition-all duration-300 cursor-pointer">
                    </div>
                </div>

                <!-- Companion Division -->
                <div class="space-y-1.5">
                    <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Division Placement (Optional)</label>
                    <input type="text" name="companions[${editCompanionIndex}][division]" value="${compDivision}" placeholder="e.g. Youth Sector / OPEC Visayas (Optional)"
                        class="w-full rounded-xl border border-slate-200 dark:border-slate-800/80 py-2.5 px-3.5 text-slate-850 dark:text-slate-200 text-xs focus:border-purple-500 focus:outline-none bg-slate-50/50 dark:bg-slate-950 focus:bg-white transition-all duration-300">
                </div>
            `;

            container.appendChild(card);
            editCompanionIndex++;
        }

        function removeEditCompanionField(index) {
            const card = document.getElementById(`edit-companion-card-${index}`);
            if (card) {
                card.remove();
                reindexEditCompanions();
            }
        }

        function reindexEditCompanions() {
            const container = document.getElementById('edit-reg-companions-container');
            if (!container) return;

            const cards = container.children;
            editCompanionIndex = 0;

            Array.from(cards).forEach((card, idx) => {
                card.id = `edit-companion-card-${idx}`;
                
                const title = card.querySelector('span');
                if (title) title.innerText = `Companion #${idx + 1}`;

                const deleteBtn = card.querySelector('button[onclick^="removeEditCompanionField"]');
                if (deleteBtn) deleteBtn.setAttribute('onclick', `removeEditCompanionField(${idx})`);

                // Update input names for correct indexing on array submit
                const inputs = card.querySelectorAll('input, select');
                inputs.forEach(input => {
                    const nameAttr = input.getAttribute('name');
                    if (nameAttr) {
                        const newName = nameAttr.replace(/companions\[\d+\]/, `companions[${idx}]`);
                        input.setAttribute('name', newName);
                    }
                });

                editCompanionIndex++;
            });

            if (editCompanionIndex === 0) {
                const toggle = document.getElementById('edit-reg-group-toggle');
                if (toggle) toggle.checked = false;
                toggleEditRegistrationGroup(toggle);
            }
        }

        function handleDivisionInputSwitch() {
            const select = document.getElementById('edit_reg_division');
            const customWrapper = document.getElementById('edit_reg_division_custom_wrapper');
            const customInput = document.getElementById('edit_reg_division_custom');

            if (!select || !customWrapper || !customInput) return;

            if (select.value === '__custom__') {
                customWrapper.classList.remove('hidden');
                select.removeAttribute('name');
                customInput.setAttribute('name', 'division');
                customInput.focus();
            } else {
                customWrapper.classList.add('hidden');
                select.setAttribute('name', 'division');
                customInput.removeAttribute('name');
                customInput.value = '';
            }
        }

        // Close on backdrop click for both modals
        window.addEventListener('click', function(e) {
            const attendeeModal = document.getElementById('attendee-details-modal');
            const editModal = document.getElementById('edit-registration-modal');
            if (e.target === attendeeModal) {
                closeAttendeeModal();
            }
            if (e.target === editModal) {
                closeEditRegistrationModal();
            }
        });

        // AJAX Submit handler for editing registrations
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('edit-registration-form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const submitBtn = form.querySelector('button[type="submit"]');
                    if (!submitBtn || submitBtn.disabled) return;

                    const originalHTML = submitBtn.innerHTML;
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = `
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline-block" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Saving...
                    `;

                    const formData = new FormData(form);
                    const url = form.action;

                    fetch(url, {
                        method: 'POST', // Spoofed to PUT via _method field in form data
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        },
                        body: formData
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            const reg = data.registration;
                            
                            // Update local cache
                            attendeeData[reg.id] = reg;

                            // Update corresponding table row in DOM
                            const row = document.querySelector(`.analytics-row[data-id="${reg.id}"]`);
                            if (row) {
                                // 1. Avatar, Name, Email
                                const avatar = row.children[0].querySelector('span');
                                if (avatar) avatar.innerText = reg.name.substring(0, 2).toUpperCase();
                                const nameSpan = row.children[0].querySelector('span.block.text-slate-900, span.block.text-slate-900.dark\\:text-slate-100');
                                if (nameSpan) nameSpan.innerText = reg.name;
                                const emailSpan = row.children[0].querySelector('span.block.font-mono');
                                if (emailSpan) emailSpan.innerText = reg.email;

                                // 2. Ticket Code
                                const ticketTd = row.children[1];
                                if (ticketTd) {
                                    if (reg.ticket_code) {
                                        ticketTd.innerHTML = `
                                            <span class="px-2 py-1 bg-purple-50 dark:bg-purple-950/30 border border-purple-100/50 dark:border-purple-900/20 rounded-md font-mono font-bold text-[11px] text-purple-600 dark:text-purple-400">
                                                ${reg.ticket_code}
                                            </span>
                                        `;
                                    } else {
                                        ticketTd.innerHTML = `<span class="text-slate-400 dark:text-slate-600 font-medium">—</span>`;
                                    }
                                }

                                // 3. Age
                                const ageTd = row.children[2];
                                if (ageTd) {
                                    let ageText = '—';
                                    if (reg.birthday) {
                                        const bday = new Date(reg.birthday);
                                        const today = new Date();
                                        let age = today.getFullYear() - bday.getFullYear();
                                        const m = today.getMonth() - bday.getMonth();
                                        if (m < 0 || (m === 0 && today.getDate() < bday.getDate())) {
                                            age--;
                                        }
                                        ageText = `${age} yrs`;
                                        row.setAttribute('data-age', age);
                                    } else {
                                        row.setAttribute('data-age', -1);
                                    }
                                    ageTd.innerText = ageText;
                                }

                                // 4. Gender
                                const genderTd = row.children[3];
                                if (genderTd) {
                                    const gender = reg.gender || 'Unspecified';
                                    if (gender === 'Male') {
                                        genderTd.innerHTML = `<span class="px-2.5 py-1 bg-blue-500/10 border border-blue-500/20 rounded-lg font-semibold text-blue-600 dark:text-blue-400 text-[10px] whitespace-nowrap">Male</span>`;
                                    } else if (gender === 'Female') {
                                        genderTd.innerHTML = `<span class="px-2.5 py-1 bg-pink-500/10 border border-pink-500/20 rounded-lg font-semibold text-pink-600 dark:text-pink-400 text-[10px] whitespace-nowrap">Female</span>`;
                                    } else if (gender === 'LGBTQ+') {
                                        genderTd.innerHTML = `<span class="px-2.5 py-1 bg-purple-500/10 border border-purple-500/20 rounded-lg font-semibold text-purple-600 dark:text-purple-400 text-[10px] whitespace-nowrap">LGBTQ+</span>`;
                                    } else {
                                        genderTd.innerHTML = `<span class="px-2.5 py-1 bg-slate-500/10 border border-slate-500/20 rounded-lg font-semibold text-slate-655 dark:text-slate-400 text-[10px] whitespace-nowrap">${gender}</span>`;
                                    }
                                }

                                // 5. Division
                                const divisionTd = row.children[4];
                                if (divisionTd) {
                                    if (reg.division) {
                                        divisionTd.innerHTML = `
                                            <span class="px-2.5 py-1 bg-purple-50 dark:bg-purple-950/30 text-purple-700 dark:text-purple-400 border border-purple-100/50 dark:border-purple-900/20 rounded-lg font-bold text-[10px] whitespace-nowrap">
                                                ${reg.division}
                                            </span>
                                        `;
                                    } else {
                                        divisionTd.innerHTML = `<span class="text-slate-400 dark:text-slate-600 font-medium">—</span>`;
                                    }
                                }

                                // Row attributes for filters
                                row.setAttribute('data-name', reg.name.toLowerCase());
                                row.setAttribute('data-email', reg.email.toLowerCase());
                                row.setAttribute('data-code', (reg.ticket_code || '').toLowerCase());
                                row.setAttribute('data-gender', reg.gender || 'Unspecified');
                                row.setAttribute('data-division', reg.division || 'Unspecified');
                                
                                let answersText = '';
                                if (reg.custom_fields && typeof reg.custom_fields === 'object') {
                                    answersText = Object.values(reg.custom_fields).join(' ').toLowerCase();
                                }
                                row.setAttribute('data-answers', answersText);
                            }

                            // Close modal & run filter matching to refresh results
                            closeEditRegistrationModal();
                            filterAnalytics();

                            // Trigger global status toast and reload the page to perfectly sync companion lists
                            if (window.showToast) {
                                window.showToast(data.message, 'success');
                            } else {
                                alert(data.message);
                            }

                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);
                        } else {
                            alert(data.message || 'Error occurred while saving changes.');
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        alert('Server communication error.');
                    })
                    .finally(() => {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalHTML;
                    });
                });
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
            const filterGender = document.getElementById('analytics-filter-gender') ? document.getElementById('analytics-filter-gender').value : 'all';
            const filterDivision = document.getElementById('analytics-filter-division') ? document.getElementById('analytics-filter-division').value : 'all';
            const filterAge = document.getElementById('analytics-filter-age') ? document.getElementById('analytics-filter-age').value : 'all';
            
            const rows = document.querySelectorAll('.analytics-row');
            const noResults = document.getElementById('no-analytics-matched');

            let matchesCount = 0;

            rows.forEach(row => {
                const name = row.getAttribute('data-name') || '';
                const email = row.getAttribute('data-email') || '';
                const code = row.getAttribute('data-code') || '';
                const status = row.getAttribute('data-status') || '';
                const gender = row.getAttribute('data-gender') || 'Unspecified';
                const division = row.getAttribute('data-division') || 'Unspecified';
                const age = parseInt(row.getAttribute('data-age') || '-1');
                const answers = row.getAttribute('data-answers') || '';

                const matchesSearch = name.includes(query) || email.includes(query) || code.includes(query) || answers.includes(query);
                const matchesStatus = activeAnalyticsStatusFilter === 'all' || status === activeAnalyticsStatusFilter;
                
                // Gender matching
                let matchesGender = false;
                if (filterGender === 'all') {
                    matchesGender = true;
                } else if (filterGender === 'Others') {
                    matchesGender = gender !== 'Male' && gender !== 'Female' && gender !== 'LGBTQ+' && gender !== 'Unspecified';
                } else {
                    matchesGender = gender === filterGender;
                }

                // Division matching
                const matchesDivision = filterDivision === 'all' || division === filterDivision;

                // Age matching
                let matchesAge = false;
                if (filterAge === 'all') {
                    matchesAge = true;
                } else if (filterAge === 'kids') {
                    matchesAge = age >= 0 && age < 12;
                } else if (filterAge === 'youth') {
                    matchesAge = age >= 12 && age <= 17;
                } else if (filterAge === 'young_adults') {
                    matchesAge = age >= 18 && age <= 30;
                } else if (filterAge === 'adults') {
                    matchesAge = age >= 31 && age <= 59;
                } else if (filterAge === 'seniors') {
                    matchesAge = age >= 60;
                }

                if (matchesSearch && matchesStatus && matchesGender && matchesDivision && matchesAge) {
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