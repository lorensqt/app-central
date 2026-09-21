<!-- TAB 1: REGISTRATION REQUESTS PANEL -->
<div id="tab-panel-requests" class="space-y-6">
    <!-- Filter Actions Strip -->
    <div class="flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4 bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800/80 p-4 shadow-sm">
        <!-- Left Side: Live Search Box -->
        <div class="relative flex-grow max-w-md bg-white dark:bg-slate-950 rounded-xl border border-slate-200/80 dark:border-slate-800/80 hover:border-slate-300 dark:hover:border-slate-700 shadow-[0_2px_8px_rgba(15,23,42,0.01)] transition duration-200">
            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </span>
            <input type="text" id="applicant-search" oninput="filterApplicants()" placeholder="Search applicants by name or email..." class="w-full pl-10 pr-4 py-2.5 rounded-xl border-0 text-slate-600 dark:text-slate-200 text-sm focus:ring-0 focus:outline-none bg-transparent placeholder-slate-400">
        </div>

        <!-- Right Side: Filter Buttons for Quick Toggle (All, Pending, Approved, Declined) -->
        <div class="flex flex-wrap items-center gap-1.5 bg-slate-50 dark:bg-slate-950 p-1 rounded-xl border border-slate-100 dark:border-slate-800/80 shrink-0 select-none">
            <button type="button" onclick="setStatusFilter('all')" id="filter-btn-all" class="px-3.5 py-1.5 text-xs font-bold rounded-lg bg-purple-500/10 dark:bg-purple-950/40 text-purple-600 dark:text-purple-400 border border-purple-500/20 shadow-xs transition duration-150">
                All ({{ $event->registrations->count() }})
            </button>
            <button type="button" onclick="setStatusFilter('pending')" id="filter-btn-pending" class="px-3.5 py-1.5 text-xs font-medium rounded-lg text-slate-500 dark:text-slate-450 hover:bg-amber-500/5 dark:hover:bg-amber-950/20 hover:text-amber-600 dark:hover:text-amber-400 border border-transparent transition duration-150">
                Pending ({{ $event->registrations->where('status', 'pending')->count() }})
            </button>
            <button type="button" onclick="setStatusFilter('approved')" id="filter-btn-approved" class="px-3.5 py-1.5 text-xs font-medium rounded-lg text-slate-500 dark:text-slate-450 hover:bg-emerald-500/5 dark:hover:bg-emerald-950/20 hover:text-emerald-600 dark:hover:text-emerald-400 border border-transparent transition duration-150">
                Approved ({{ $event->registrations->where('status', 'approved')->count() }})
            </button>
            <button type="button" onclick="setStatusFilter('declined')" id="filter-btn-declined" class="px-3.5 py-1.5 text-xs font-medium rounded-lg text-slate-500 dark:text-slate-450 hover:bg-rose-500/5 dark:hover:bg-rose-950/20 hover:text-rose-600 dark:hover:text-rose-450 border border-transparent transition duration-150">
                Declined ({{ $event->registrations->where('status', 'declined')->count() }})
            </button>
        </div>
    </div>

    <!-- Applicants Table (High Aesthetic Layout) -->
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/60 dark:border-slate-800 shadow-[0_4px_12px_rgba(15,23,42,0.02)] overflow-hidden">
        @if($event->registrations->isEmpty())
            <div class="text-center py-16 text-slate-400 dark:text-slate-500 text-sm italic space-y-3">
                <svg class="w-12 h-12 text-slate-300 dark:text-slate-700 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <p>No rsvp applications have been received for this assembly yet.</p>
                <p class="text-xs text-slate-450 dark:text-slate-500 not-italic">Copy the public RSVP link above and share it with potential attendees!</p>
            </div>
        @else
            <!-- Desktop Layout: Table View (Hidden on Mobile) -->
            <div class="hidden sm:block overflow-x-auto custom-scrollbar">
                <table class="w-full text-left text-sm border-collapse">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-950 border-b border-slate-100 dark:border-slate-800/80 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                            <th class="py-3.5 px-6 w-12 text-center select-none text-[10px] tracking-wider text-slate-400">Sel</th>
                            <th class="py-3.5 px-6">Attendee Profile</th>
                            <th class="py-3.5 px-6">Ticket Code</th>
                            <th class="py-3.5 px-6">Gender</th>
                            <th class="py-3.5 px-6">Birthdate & Age</th>
                            <th class="py-3.5 px-6">Submission Time</th>
                            <th class="py-3.5 px-6">Status</th>
                            <th class="py-3.5 px-6 text-right">Moderation Actions</th>
                        </tr>
                    </thead>
                    <tbody id="applicants-table-body" class="divide-y divide-slate-100 dark:divide-slate-800/80">
                        @foreach($event->registrations->sortByDesc('created_at') as $reg)
                            <tr class="applicant-row hover:bg-slate-50/50 dark:hover:bg-slate-800/35 transition duration-150" 
                                data-id="{{ $reg->id }}"
                                data-name="{{ $reg->name }}" 
                                data-email="{{ strtolower($reg->email) }}" 
                                data-code="{{ strtolower($reg->ticket_code) }}"
                                data-status="{{ $reg->status }}">
                                
                                <!-- Selection Checkbox -->
                                <td class="py-4 px-6 text-center select-none">
                                    <input type="checkbox" class="applicant-checkbox w-4 h-4 rounded border-slate-300 dark:border-slate-700/80 text-purple-600 dark:text-purple-400 focus:ring-purple-500/20 dark:focus:ring-purple-500/10 bg-white dark:bg-slate-900 transition duration-150" data-id="{{ $reg->id }}">
                                </td>
                                
                                <!-- Profile / Avatar Initials with Merged Name & Email (Pro-Level Layout) -->
                                <td class="py-4 px-6 font-semibold text-slate-800 dark:text-slate-200">
                                    <div class="flex items-center gap-3">
                                        <span class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-purple-50 dark:bg-purple-950/40 text-purple-600 dark:text-purple-400 font-bold text-xs uppercase shrink-0">
                                            {{ substr($reg->name, 0, 2) }}
                                        </span>
                                        <div class="text-left">
                                            <span class="block text-slate-900 dark:text-slate-100 font-bold text-sm leading-none">{{ $reg->name }}</span>
                                            <span class="block text-[11px] text-slate-400 dark:text-slate-500 font-mono mt-1 leading-none select-all" title="Click to select email">{{ $reg->email }}</span>
                                            
                                            @if($reg->group_code)
                                                <div class="mt-1">
                                                    <span class="inline-flex items-center gap-1 px-1.5 py-0.5 bg-purple-50 dark:bg-purple-950/40 text-purple-600 dark:text-purple-400 text-[9px] font-extrabold rounded-md uppercase tracking-wider border border-purple-100/40 dark:border-purple-900/30 select-none" title="Group Code: {{ $reg->group_code }}">
                                                        👥 {{ $reg->group_code }} @if($reg->is_group_primary) <span class="text-[8px] bg-purple-500 text-white px-1 rounded ml-1">PRIMARY</span> @endif
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                
                                <!-- Ticket Code Column -->
                                <td class="py-4 px-6 font-mono text-xs text-purple-650 dark:text-purple-400 font-bold">
                                    {{ $reg->ticket_code ?? 'N/A' }}
                                </td>
                                
                                <!-- Gender Identity -->
                                <td class="py-4 px-6 text-slate-600 dark:text-slate-400 text-xs">
                                    <span class="px-2 py-1 bg-slate-50 dark:bg-slate-950 border border-slate-100/80 dark:border-slate-800/80 rounded-lg font-medium text-slate-700 dark:text-slate-300 whitespace-nowrap">
                                        {{ $reg->gender ?? 'Unspecified' }}
                                    </span>
                                </td>

                                <!-- Birthdate & Age -->
                                <td class="py-4 px-6 text-slate-650 dark:text-slate-300 font-semibold text-xs whitespace-nowrap">
                                    @if($reg->birthday)
                                        <div class="text-left leading-tight">
                                            <span class="block text-slate-800 dark:text-slate-200 font-bold">🎂 {{ $reg->birthday->format('M d, Y') }}</span>
                                            <span class="block text-[10px] text-purple-650 dark:text-purple-400 font-extrabold mt-0.5 uppercase tracking-wider">{{ $reg->age }} Years Old</span>
                                        </div>
                                    @else
                                        <span class="text-slate-400 dark:text-slate-500 italic">N/A</span>
                                    @endif
                                </td>
                                
                                <!-- Time Stamp -->
                                <td class="py-4 px-6 text-slate-500 dark:text-slate-400 text-xs">
                                    {{ $reg->created_at ? $reg->created_at->format('M j, Y • g:i A') : 'N/A' }}
                                </td>
                                
                                <!-- Status Badge (Desktop status-cell target) -->
                                <td class="status-cell py-4 px-6">
                                    @if($reg->status === 'approved')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 bg-emerald-50 dark:bg-emerald-950/30 text-emerald-700 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-900/30 text-[10px] font-bold rounded-md uppercase tracking-wider">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Approved
                                        </span>
                                        <div class="attendance-badge-wrapper mt-1">
                                            @if($reg->attended)
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 bg-emerald-100 dark:bg-emerald-950/50 text-emerald-800 dark:text-emerald-300 border border-emerald-200/50 dark:border-emerald-900/30 text-[10px] font-bold rounded-md uppercase tracking-wider">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                    Attended
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 bg-slate-100 dark:bg-slate-950/40 text-slate-600 dark:text-slate-400 border border-slate-200/40 dark:border-slate-800/60 text-[10px] font-bold rounded-md uppercase tracking-wider">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-450"></span>
                                                    Absent
                                                </span>
                                            @endif
                                        </div>
                                    @elseif($reg->status === 'declined')
                                        <div class="flex flex-col items-start gap-1">
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 bg-red-50 dark:bg-red-950/30 text-red-700 dark:text-red-400 border border-red-100/30 dark:border-red-900/30 text-[10px] font-bold rounded-md uppercase tracking-wider">
                                                <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                                Declined
                                            </span>
                                            @if($reg->rejection_reason)
                                                <span class="text-[10px] text-red-500 dark:text-red-400 max-w-[150px] truncate block font-semibold leading-tight text-left" title="{{ $reg->rejection_reason }}">
                                                    Reason: {{ $reg->rejection_reason }}
                                                </span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 bg-amber-50 dark:bg-amber-950/30 text-amber-700 dark:text-amber-400 border border-amber-100 dark:border-amber-900/30 text-[10px] font-bold rounded-md uppercase tracking-wider animate-pulse">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                            Pending
                                        </span>
                                    @endif
                                </td>
                                
                                <!-- Actions Column (Desktop actions-cell target) -->
                                <td class="actions-cell py-4 px-6 text-right space-x-1.5 whitespace-nowrap">
                                    @if($reg->status === 'pending')
                                        <!-- Approve Form -->
                                        <form action="{{ route('committees.registrations.approve', $reg) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-xs font-semibold text-emerald-600 dark:text-emerald-400 hover:text-white dark:hover:text-slate-900 hover:bg-emerald-500 dark:hover:bg-emerald-400 border border-emerald-200 dark:border-emerald-800/60 hover:border-transparent dark:hover:border-transparent px-3 py-1.5 rounded-xl transition duration-150">
                                                Approve
                                            </button>
                                        </form>

                                        <!-- Decline Form -->
                                        <form action="{{ route('committees.registrations.decline', $reg) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-xs font-semibold text-red-500 dark:text-red-400 hover:text-white dark:hover:text-slate-900 hover:bg-red-500 dark:hover:bg-red-400 border border-red-200 dark:border-red-900/60 hover:border-transparent dark:hover:border-transparent px-3 py-1.5 rounded-xl transition duration-150">
                                                Decline
                                            </button>
                                        </form>
                                    @elseif($reg->status === 'approved')
                                        <!-- Attendance Check Toggle -->
                                        <form action="{{ route('committees.registrations.toggle_attendance', $reg) }}" method="POST" class="inline toggle-attendance-form">
                                            @csrf
                                            <button type="submit" class="text-xs font-semibold {{ $reg->attended ? 'text-amber-600 dark:text-amber-400 border border-amber-200 dark:border-amber-900/50 hover:bg-amber-500 dark:hover:bg-amber-550' : 'text-purple-600 dark:text-purple-400 border border-purple-200 dark:border-purple-800/80 hover:bg-purple-500 dark:hover:bg-purple-600' }} hover:text-white hover:border-transparent px-3 py-1.5 rounded-xl transition duration-150">
                                                {{ $reg->attended ? 'Mark Absent' : 'Mark Attended' }}
                                            </button>
                                        </form>
                                    @endif

                                    <!-- Delete Registration Form -->
                                    <form action="{{ route('committees.registrations.destroy', $reg) }}" method="POST" class="inline delete-registration-form" data-name="{{ $reg->name }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs font-semibold text-red-600 dark:text-red-400 hover:text-white hover:bg-red-600 border border-red-200 dark:border-red-900/40 hover:border-transparent p-1.5 rounded-xl transition duration-150" title="Delete Registrant">
                                            <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Mobile Layout: Card View (Visible only on Mobile) -->
            <div id="applicants-mobile-cards" class="sm:hidden divide-y divide-slate-100 dark:divide-slate-800/60">
                @foreach($event->registrations->sortByDesc('created_at') as $reg)
                    <div class="applicant-row p-4 space-y-3 bg-white dark:bg-slate-900 transition duration-150"
                        data-id="{{ $reg->id }}"
                        data-name="{{ strtolower($reg->name) }}" 
                        data-email="{{ strtolower($reg->email) }}" 
                        data-code="{{ strtolower($reg->ticket_code) }}"
                        data-status="{{ $reg->status }}">
                        
                        <!-- Top Header Row: Profile Avatar, Name, and Status Badge -->
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <!-- Selection Checkbox & Profile Avatar -->
                                <div class="flex items-center gap-2">
                                    <input type="checkbox" class="applicant-checkbox w-4.5 h-4.5 rounded border-slate-300 dark:border-slate-700/80 text-purple-600 dark:text-purple-400 focus:ring-purple-500/20 dark:focus:ring-purple-500/10 bg-white dark:bg-slate-900 transition duration-150" data-id="{{ $reg->id }}">
                                    
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-purple-50 dark:bg-purple-950/40 text-purple-600 dark:text-purple-400 font-bold text-xs uppercase shrink-0">
                                        {{ substr($reg->name, 0, 2) }}
                                    </span>
                                </div>
                                <div class="text-left">
                                    <h4 class="font-bold text-slate-800 dark:text-slate-200 text-sm leading-snug">{{ $reg->name }}</h4>
                                    <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-0.5">{{ $reg->created_at ? $reg->created_at->format('M j, Y • g:i A') : 'N/A' }}</p>
                                </div>
                            </div>
                            
                            <!-- Status Badge (Mobile status-cell target) -->
                            <div class="status-cell shrink-0 text-right">
                                @if($reg->status === 'approved')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 bg-emerald-50 dark:bg-emerald-950/30 text-emerald-700 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-900/30 text-[10px] font-bold rounded-md uppercase tracking-wider">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Approved
                                    </span>
                                    <div class="attendance-badge-wrapper mt-1">
                                        @if($reg->attended)
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 bg-emerald-100 dark:bg-emerald-950/50 text-emerald-800 dark:text-emerald-300 border border-emerald-200/50 dark:border-emerald-900/30 text-[10px] font-bold rounded-md uppercase tracking-wider">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                Attended
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 bg-slate-100 dark:bg-slate-950/40 text-slate-600 dark:text-slate-400 border border-slate-200/40 dark:border-slate-800/60 text-[10px] font-bold rounded-md uppercase tracking-wider">
                                                <span class="w-1.5 h-1.5 rounded-full bg-slate-450"></span>
                                                Absent
                                            </span>
                                        @endif
                                    </div>
                                @elseif($reg->status === 'declined')
                                    <div class="flex flex-col items-end gap-1">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 bg-red-50 dark:bg-red-950/30 text-red-700 dark:text-red-400 border border-red-100/30 dark:border-red-900/30 text-[10px] font-bold rounded-md uppercase tracking-wider">
                                            <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                            Declined
                                        </span>
                                        @if($reg->rejection_reason)
                                            <span class="text-[9px] text-red-500 dark:text-red-400 max-w-[120px] truncate block font-semibold leading-tight text-right" title="{{ $reg->rejection_reason }}">
                                                Reason: {{ $reg->rejection_reason }}
                                            </span>
                                        @endif
                                    </div>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 bg-amber-50 dark:bg-amber-950/30 text-amber-700 dark:text-amber-400 border border-amber-100 dark:border-amber-900/30 text-[10px] font-bold rounded-md uppercase tracking-wider animate-pulse">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                        Pending
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Applicant Contact Info / Metadata -->
                        <div class="text-xs space-y-1.5 bg-slate-50/50 dark:bg-slate-950/40 p-2.5 rounded-xl border border-slate-100/60 dark:border-slate-800/50 text-left">
                            <div class="flex items-center justify-between gap-2">
                                <span class="text-slate-400 font-medium">Email Address:</span>
                                <span class="font-mono text-slate-600 dark:text-slate-300 select-all truncate max-w-[190px]" title="{{ $reg->email }}">{{ $reg->email }}</span>
                            </div>

                            @if($reg->group_code)
                            <div class="flex items-center justify-between gap-2">
                                <span class="text-slate-400 font-medium">Group Code:</span>
                                <span class="inline-flex items-center gap-1 px-1.5 py-0.5 bg-purple-50 dark:bg-purple-950/40 text-purple-600 dark:text-purple-400 text-[9px] font-extrabold rounded-md uppercase tracking-wider border border-purple-100/30">
                                    👥 {{ $reg->group_code }} @if($reg->is_group_primary) <span class="text-[8px] bg-purple-500 text-white px-1 rounded ml-1">PRIMARY</span> @endif
                                </span>
                            </div>
                            @endif

                            <div class="flex items-center justify-between gap-2">
                                <span class="text-slate-400 font-medium">Ticket Code:</span>
                                <span class="font-mono font-bold text-purple-650 dark:text-purple-400">{{ $reg->ticket_code ?? 'N/A' }}</span>
                            </div>
                            <div class="flex items-center justify-between gap-2">
                                <span class="text-slate-400 font-medium">Gender:</span>
                                <span class="font-semibold text-slate-650 dark:text-slate-300">{{ $reg->gender ?? 'Unspecified' }}</span>
                            </div>
                            <div class="flex items-center justify-between gap-2">
                                <span class="text-slate-400 font-medium">Birthdate & Age:</span>
                                <span class="font-bold text-slate-800 dark:text-slate-200">
                                    @if($reg->birthday)
                                        🎂 {{ $reg->birthday->format('M d, Y') }} ({{ $reg->age }} yrs)
                                    @else
                                        N/A
                                    @endif
                                </span>
                            </div>
                        </div>

                        <!-- Mobile Moderation Actions (Mobile actions-cell target) -->
                        <div class="actions-cell mobile-actions-container flex items-center justify-end gap-2 pt-1 border-t border-slate-50 dark:border-slate-800/40">
                            @if($reg->status === 'pending')
                                <!-- Approve Form -->
                                <form action="{{ route('committees.registrations.approve', $reg) }}" method="POST" class="flex-grow">
                                    @csrf
                                    <button type="submit" class="w-full text-center text-xs font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-50 hover:bg-emerald-500 dark:bg-emerald-950/20 dark:hover:bg-emerald-400 hover:text-white dark:hover:text-slate-900 border border-emerald-100 dark:border-emerald-900/30 py-2 rounded-xl transition duration-150">
                                        Approve
                                    </button>
                                </form>

                                <!-- Decline Form -->
                                <form action="{{ route('committees.registrations.decline', $reg) }}" method="POST" class="flex-grow">
                                    @csrf
                                    <button type="submit" class="w-full text-center text-xs font-bold text-red-500 dark:text-red-400 bg-red-50 hover:bg-red-500 dark:bg-red-950/20 dark:hover:bg-red-400 hover:text-white dark:hover:text-slate-900 border border-red-100/30 dark:border-red-900/30 py-2 rounded-xl transition duration-150">
                                        Decline
                                    </button>
                                </form>
                            @elseif($reg->status === 'approved')
                                <!-- Attendance Toggle -->
                                <form action="{{ route('committees.registrations.toggle_attendance', $reg) }}" method="POST" class="flex-grow toggle-attendance-form">
                                    @csrf
                                    <button type="submit" class="w-full text-center text-xs font-bold {{ $reg->attended ? 'text-amber-600 dark:text-amber-400 border border-amber-200 dark:border-amber-900/30 bg-amber-50 dark:bg-amber-950/20 hover:bg-amber-500 hover:text-white' : 'text-purple-600 dark:text-purple-400 border border-purple-200 dark:border-purple-800 bg-purple-50 dark:bg-purple-950/20 hover:bg-purple-500 hover:text-white' }} py-2 rounded-xl transition duration-150">
                                        {{ $reg->attended ? 'Mark Absent' : 'Mark Attended' }}
                                    </button>
                                </form>
                            @endif

                            <!-- Delete Button -->
                            <form action="{{ route('committees.registrations.destroy', $reg) }}" method="POST" class="delete-registration-form" data-name="{{ $reg->name }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs font-semibold text-red-650 dark:text-red-400 bg-red-50 dark:bg-red-950/20 hover:bg-red-600 hover:text-white border border-red-100 dark:border-red-900/30 p-2 rounded-xl transition duration-150" title="Delete Registrant">
                                    <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Client-side No Results State inside table -->
            <div id="no-applicants-matched" class="hidden text-center py-12 text-slate-400 dark:text-slate-500 text-sm bg-slate-50/50 dark:bg-slate-950/50 border-t border-slate-100 dark:border-slate-800">
                <svg class="w-10 h-10 text-slate-300 dark:text-slate-700 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                No attendee registrations match your filter or search query.
            </div>
        @endif
    </div>
</div>