<!-- PANEL C: VOTERS (Voters & Analytics) -->
<div id="panel-voters" class="manage-tab-panel hidden space-y-6">
    <div class="flex items-center justify-between gap-4 flex-wrap">
        <div>
            <h2 class="text-xl font-bold text-slate-900 dark:text-white">Voter Register</h2>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Total registered or queued participants in this election.</p>
        </div>
        
        <div class="flex items-center gap-3">
            <button type="button" onclick="openCreateVoterModal()" class="inline-flex items-center gap-1.5 bg-purple-600 hover:bg-purple-700 active:scale-95 text-white font-semibold text-xs px-4 py-2.5 rounded-xl shadow-sm transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                Enroll Voter
            </button>
            <button type="button" onclick="confirmVoterCSVDownload()" class="inline-flex items-center gap-1.5 bg-slate-900 hover:bg-slate-800 dark:bg-slate-800 dark:hover:bg-slate-700 text-white dark:text-slate-200 font-semibold text-xs px-4 py-2.5 rounded-xl shadow-sm transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                Export Voters CSV
            </button>
            <div class="text-xs font-bold px-3.5 py-2 rounded-xl bg-purple-500/10 text-purple-600 dark:text-purple-400">
                Total Voters: <span id="voter-count-display">{{ number_format($voters->total()) }}</span>
            </div>
        </div>
    </div>

    <!-- Filters Bar with Custom Smooth Animated Dropdowns -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-5 gap-3.5 bg-slate-50 dark:bg-slate-950/40 p-5 rounded-2xl border border-slate-200/60 dark:border-slate-800/80">
        <!-- Search Filter (Searches across all database records) -->
        <div class="relative flex items-center">
            <div class="pointer-events-none absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 dark:text-slate-500">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            <input type="text" id="voter-filter-search" oninput="onVoterSearchInput()" placeholder="Search voter, email, division..."
                class="pl-10 pr-9 w-full bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 text-xs rounded-xl border border-slate-200 dark:border-slate-800 focus:border-purple-500 dark:focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none py-2.5 transition shadow-xs">
            <button id="voter-search-clear-btn" type="button" onclick="clearVoterSearch()"
                class="hidden absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition"
                title="Clear search">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Custom Dropdown: Division -->
        <div class="relative custom-voter-dropdown" id="dropdown-wrapper-division">
            <input type="hidden" id="voter-filter-division" value="all">
            <button type="button" onclick="toggleVoterDropdown('division')"
                class="w-full bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300 text-xs rounded-xl border border-slate-200 dark:border-slate-800 hover:border-purple-500/50 dark:hover:border-purple-500/50 py-2.5 px-3.5 flex items-center justify-between gap-2 shadow-xs transition-all duration-200 focus:outline-none focus:ring-4 focus:ring-purple-500/10">
                <div class="flex items-center gap-2 truncate">
                    <svg class="w-3.5 h-3.5 text-purple-600 dark:text-purple-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <span id="label-filter-division" class="truncate font-medium">All Divisions</span>
                </div>
                <svg id="chevron-filter-division" class="w-3.5 h-3.5 text-slate-400 dark:text-slate-500 transition-transform duration-200 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <!-- Animated Menu -->
            <div id="menu-filter-division"
                class="hidden opacity-0 scale-95 transition-all duration-200 ease-out origin-top absolute left-0 right-0 mt-2 z-40 bg-white/95 dark:bg-slate-900/95 backdrop-blur-xl border border-slate-200 dark:border-slate-800 rounded-2xl shadow-xl p-1.5 pr-1 max-h-60 overflow-y-auto custom-scrollbar">
                <button type="button" onclick="selectVoterDropdownOption('division', 'all', 'All Divisions')"
                    class="voter-opt-division w-full text-left px-3 py-2 rounded-xl text-xs font-semibold text-purple-600 dark:text-purple-400 bg-purple-50/70 dark:bg-purple-950/40 flex items-center justify-between transition" data-value="all">
                    <span class="truncate">All Divisions / Workplaces</span>
                    <svg class="check-icon w-3.5 h-3.5 text-purple-600 dark:text-purple-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                </button>
                @foreach($divisions as $div)
                    <button type="button" onclick="selectVoterDropdownOption('division', '{{ addslashes($div) }}', '{{ addslashes($div) }}')"
                        class="voter-opt-division w-full text-left px-3 py-2 rounded-xl text-xs font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800/60 hover:text-purple-600 dark:hover:text-purple-400 flex items-center justify-between transition mt-0.5" data-value="{{ $div }}">
                        <span class="truncate">{{ $div }}</span>
                        <svg class="check-icon hidden w-3.5 h-3.5 text-purple-600 dark:text-purple-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Custom Dropdown: Gender -->
        <div class="relative custom-voter-dropdown" id="dropdown-wrapper-gender">
            <input type="hidden" id="voter-filter-gender" value="all">
            <button type="button" onclick="toggleVoterDropdown('gender')"
                class="w-full bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300 text-xs rounded-xl border border-slate-200 dark:border-slate-800 hover:border-purple-500/50 dark:hover:border-purple-500/50 py-2.5 px-3.5 flex items-center justify-between gap-2 shadow-xs transition-all duration-200 focus:outline-none focus:ring-4 focus:ring-purple-500/10">
                <div class="flex items-center gap-2 truncate">
                    <svg class="w-3.5 h-3.5 text-purple-600 dark:text-purple-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span id="label-filter-gender" class="truncate font-medium">All Genders</span>
                </div>
                <svg id="chevron-filter-gender" class="w-3.5 h-3.5 text-slate-400 dark:text-slate-500 transition-transform duration-200 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <!-- Animated Menu -->
            <div id="menu-filter-gender"
                class="hidden opacity-0 scale-95 transition-all duration-200 ease-out origin-top absolute left-0 right-0 mt-2 z-40 bg-white/95 dark:bg-slate-900/95 backdrop-blur-xl border border-slate-200 dark:border-slate-800 rounded-2xl shadow-xl p-1.5 pr-1 max-h-60 overflow-y-auto custom-scrollbar">
                <button type="button" onclick="selectVoterDropdownOption('gender', 'all', 'All Genders')"
                    class="voter-opt-gender w-full text-left px-3 py-2 rounded-xl text-xs font-semibold text-purple-600 dark:text-purple-400 bg-purple-50/70 dark:bg-purple-950/40 flex items-center justify-between transition" data-value="all">
                    <span class="truncate">All Genders</span>
                    <svg class="check-icon w-3.5 h-3.5 text-purple-600 dark:text-purple-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                </button>
                @foreach($genders as $genderOpt)
                    <button type="button" onclick="selectVoterDropdownOption('gender', '{{ addslashes($genderOpt) }}', '{{ addslashes($genderOpt) }}')"
                        class="voter-opt-gender w-full text-left px-3 py-2 rounded-xl text-xs font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800/60 hover:text-purple-600 dark:hover:text-purple-400 flex items-center justify-between transition mt-0.5" data-value="{{ $genderOpt }}">
                        <span class="truncate">{{ $genderOpt }}</span>
                        <svg class="check-icon hidden w-3.5 h-3.5 text-purple-600 dark:text-purple-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Custom Dropdown: Voted Status -->
        <div class="relative custom-voter-dropdown" id="dropdown-wrapper-status">
            <input type="hidden" id="voter-filter-status" value="all">
            <button type="button" onclick="toggleVoterDropdown('status')"
                class="w-full bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300 text-xs rounded-xl border border-slate-200 dark:border-slate-800 hover:border-purple-500/50 dark:hover:border-purple-500/50 py-2.5 px-3.5 flex items-center justify-between gap-2 shadow-xs transition-all duration-200 focus:outline-none focus:ring-4 focus:ring-purple-500/10">
                <div class="flex items-center gap-2 truncate">
                    <svg class="w-3.5 h-3.5 text-purple-600 dark:text-purple-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span id="label-filter-status" class="truncate font-medium">All Statuses</span>
                </div>
                <svg id="chevron-filter-status" class="w-3.5 h-3.5 text-slate-400 dark:text-slate-500 transition-transform duration-200 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <!-- Animated Menu -->
            <div id="menu-filter-status"
                class="hidden opacity-0 scale-95 transition-all duration-200 ease-out origin-top absolute left-0 right-0 mt-2 z-40 bg-white/95 dark:bg-slate-900/95 backdrop-blur-xl border border-slate-200 dark:border-slate-800 rounded-2xl shadow-xl p-1.5 space-y-0.5">
                <button type="button" onclick="selectVoterDropdownOption('status', 'all', 'All Statuses')"
                    class="voter-opt-status w-full text-left px-3 py-2 rounded-xl text-xs font-semibold text-purple-600 dark:text-purple-400 bg-purple-50/70 dark:bg-purple-950/40 flex items-center justify-between transition" data-value="all">
                    <span>All Statuses</span>
                    <svg class="check-icon w-3.5 h-3.5 text-purple-600 dark:text-purple-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                </button>
                <button type="button" onclick="selectVoterDropdownOption('status', 'voted', 'Voted')"
                    class="voter-opt-status w-full text-left px-3 py-2 rounded-xl text-xs font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800/60 hover:text-purple-600 dark:hover:text-purple-400 flex items-center justify-between transition" data-value="voted">
                    <span class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        Voted
                    </span>
                    <svg class="check-icon hidden w-3.5 h-3.5 text-purple-600 dark:text-purple-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                </button>
                <button type="button" onclick="selectVoterDropdownOption('status', 'queue', 'In Queue')"
                    class="voter-opt-status w-full text-left px-3 py-2 rounded-xl text-xs font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800/60 hover:text-purple-600 dark:hover:text-purple-400 flex items-center justify-between transition" data-value="queue">
                    <span class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                        In Queue
                    </span>
                    <svg class="check-icon hidden w-3.5 h-3.5 text-purple-600 dark:text-purple-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                </button>
            </div>
        </div>

        <!-- Custom Dropdown: Sort Order -->
        <div class="relative custom-voter-dropdown" id="dropdown-wrapper-sort">
            <input type="hidden" id="voter-filter-sort" value="desc">
            <button type="button" onclick="toggleVoterDropdown('sort')"
                class="w-full bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300 text-xs rounded-xl border border-slate-200 dark:border-slate-800 hover:border-purple-500/50 dark:hover:border-purple-500/50 py-2.5 px-3.5 flex items-center justify-between gap-2 shadow-xs transition-all duration-200 focus:outline-none focus:ring-4 focus:ring-purple-500/10">
                <div class="flex items-center gap-2 truncate">
                    <svg class="w-3.5 h-3.5 text-purple-600 dark:text-purple-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12" />
                    </svg>
                    <span id="label-filter-sort" class="truncate font-medium">Newest First</span>
                </div>
                <svg id="chevron-filter-sort" class="w-3.5 h-3.5 text-slate-400 dark:text-slate-500 transition-transform duration-200 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <!-- Animated Menu -->
            <div id="menu-filter-sort"
                class="hidden opacity-0 scale-95 transition-all duration-200 ease-out origin-top absolute left-0 right-0 mt-2 z-40 bg-white/95 dark:bg-slate-900/95 backdrop-blur-xl border border-slate-200 dark:border-slate-800 rounded-2xl shadow-xl p-1.5 space-y-0.5">
                <button type="button" onclick="selectVoterDropdownOption('sort', 'desc', 'Newest First')"
                    class="voter-opt-sort w-full text-left px-3 py-2 rounded-xl text-xs font-semibold text-purple-600 dark:text-purple-400 bg-purple-50/70 dark:bg-purple-950/40 flex items-center justify-between transition" data-value="desc">
                    <span>Newest Submission First</span>
                    <svg class="check-icon w-3.5 h-3.5 text-purple-600 dark:text-purple-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                </button>
                <button type="button" onclick="selectVoterDropdownOption('sort', 'asc', 'Oldest First')"
                    class="voter-opt-sort w-full text-left px-3 py-2 rounded-xl text-xs font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800/60 hover:text-purple-600 dark:hover:text-purple-400 flex items-center justify-between transition" data-value="asc">
                    <span>Oldest Submission First</span>
                    <svg class="check-icon hidden w-3.5 h-3.5 text-purple-600 dark:text-purple-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Table Card (Paginated 100 Per Page) -->
    <div id="voters-table-card" class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-[2rem] overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-100 dark:border-slate-800 text-slate-500 dark:text-slate-400 text-xs font-bold uppercase tracking-wider">
                        <th class="px-6 py-4.5">Voter / Account</th>
                        <th class="px-6 py-4.5">Division / Workplace</th>
                        <th class="px-6 py-4.5">Gender</th>
                        <th class="px-6 py-4.5">Age</th>
                        <th class="px-6 py-4.5">Voted Status</th>
                        <th class="px-6 py-4.5">Submission Time</th>
                        <th class="px-6 py-4.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-sm text-slate-700 dark:text-slate-300 transition-opacity duration-150" id="voters-table-body">
                    @forelse($voters as $voter)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition duration-150" id="voter-row-{{ $voter->id }}">
                            <td class="px-6 py-4.5 font-semibold text-slate-800 dark:text-slate-200">
                                <div class="flex items-center gap-3">
                                    <span class="inline-flex items-center justify-center w-8.5 h-8.5 rounded-xl bg-purple-50 dark:bg-purple-950/40 text-purple-600 dark:text-purple-400 font-bold text-xs uppercase shrink-0">
                                        {{ strtoupper(substr($voter->display_name, 0, 2)) }}
                                    </span>
                                    <div class="text-left min-w-0">
                                        <span class="block text-slate-900 dark:text-white font-bold text-xs leading-tight truncate">
                                            {{ $voter->display_name }}
                                        </span>
                                        <span class="block text-[11px] text-slate-400 dark:text-slate-500 font-medium mt-0.5 leading-none truncate">
                                            {{ $voter->email }}
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4.5">
                                {{ $voter->division ?? '-' }}
                            </td>
                            <td class="px-6 py-4.5">
                                {{ $voter->gender ?? '-' }}
                            </td>
                            <td class="px-6 py-4.5">
                                {{ $voter->age ?? '-' }}
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
                            <td class="px-6 py-4.5 text-xs text-slate-400 dark:text-slate-500">
                                {{ $voter->voted_at ? $voter->voted_at->format('M d, Y • h:i A') : '-' }}
                            </td>
                            <td class="px-6 py-4.5 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1.5">
                                    <button type="button" onclick="openViewVoterModal({{ $voter->id }})"
                                        class="text-[11px] font-bold text-purple-600 dark:text-purple-400 hover:text-white dark:hover:text-slate-900 hover:bg-purple-600 dark:hover:bg-purple-600 bg-purple-500/5 dark:bg-purple-400/10 border border-purple-500/20 px-2.5 py-1.5 rounded-xl transition duration-150 active:scale-95 flex items-center gap-1 shadow-2xs"
                                        title="View Voter Details & Ballot Audit">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        <span>View</span>
                                    </button>

                                    <button type="button" onclick="openEditVoterModal({{ json_encode([
                                        'id' => $voter->id,
                                        'name' => $voter->display_name,
                                        'raw_name' => $voter->name ?? ($voter->user ? $voter->user->name : ''),
                                        'email' => $voter->email,
                                        'division' => $voter->division ?? '',
                                        'gender' => $voter->gender ?? '',
                                        'age' => $voter->age ?? '',
                                        'status' => $voter->voted_at ? 'voted' : 'in_queue',
                                    ]) }})"
                                        class="text-[11px] font-bold text-amber-600 dark:text-amber-400 hover:text-white dark:hover:text-slate-900 hover:bg-amber-500 dark:hover:bg-amber-500 bg-amber-500/5 dark:bg-amber-400/10 border border-amber-500/20 px-2.5 py-1.5 rounded-xl transition duration-150 active:scale-95 flex items-center gap-1 shadow-2xs"
                                        title="Edit Voter">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        <span>Edit</span>
                                    </button>

                                    <button type="button" onclick="deleteVoter({{ $voter->id }}, '{{ addslashes($voter->display_name) }}')"
                                        class="text-[11px] font-bold text-rose-600 dark:text-rose-400 hover:text-white hover:bg-rose-600 dark:hover:bg-rose-600 bg-rose-500/5 dark:bg-rose-400/10 border border-rose-500/20 px-2.5 py-1.5 rounded-xl transition duration-150 active:scale-95 flex items-center gap-1 shadow-2xs"
                                        title="Delete Voter">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        <span>Delete</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-400 dark:text-slate-500 italic">
                                No voters have entered or cast votes in this election yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination & Summary Footer -->
        <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-800/80 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-slate-50/50 dark:bg-slate-950/20" id="voters-pagination-container">
            <div class="text-xs text-slate-500 dark:text-slate-400">
                Showing <span id="voters-page-from" class="font-bold text-slate-800 dark:text-slate-200">{{ $voters->firstItem() ?? 0 }}</span> to <span id="voters-page-to" class="font-bold text-slate-800 dark:text-slate-200">{{ $voters->lastItem() ?? 0 }}</span> of <span id="voters-page-total" class="font-bold text-slate-800 dark:text-slate-200">{{ number_format($voters->total()) }}</span> voters
            </div>

            <div class="flex items-center gap-1.5 flex-wrap" id="voters-pagination-buttons">
                <!-- Buttons rendered dynamically via JavaScript -->
            </div>
        </div>
    </div>
</div>

<!-- Floating Back to Top Button -->
<button id="voters-back-to-top" type="button" onclick="scrollVotersToTop()"
    class="fixed bottom-6 right-6 z-40 p-3 rounded-2xl bg-purple-600 hover:bg-purple-700 active:scale-95 text-white shadow-xl shadow-purple-600/30 border border-purple-400/30 opacity-0 pointer-events-none translate-y-4 hover:scale-105 transition-all duration-300 focus:outline-none flex items-center justify-center group"
    title="Back to Top">
    <svg class="w-5 h-5 group-hover:-translate-y-0.5 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7"/>
    </svg>
</button>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        if (typeof renderPaginationButtons === 'function') {
            renderPaginationButtons({
                current_page: {{ $voters->currentPage() }},
                last_page: {{ $voters->lastPage() }},
                total: {{ $voters->total() }},
                from: {{ $voters->firstItem() ?? 0 }},
                to: {{ $voters->lastItem() ?? 0 }}
            });
        }
    });
</script>
