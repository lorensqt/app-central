@php
    // Determine Status Badge Style
    $badgeClass = '';
    $badgeIcon = '';
    if ($election->status === 'active') {
        $badgeClass = 'bg-emerald-500/10 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400';
        $badgeIcon = '<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
    } elseif ($election->status === 'draft') {
        $badgeClass = 'bg-slate-500/10 text-slate-600 dark:bg-slate-500/10 dark:text-slate-400';
        $badgeIcon = '<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>';
    } else {
        $badgeClass = 'bg-rose-500/10 text-rose-600 dark:bg-rose-500/10 dark:text-rose-400';
        $badgeIcon = '<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
    }

    // Timeline Text
    $startStr = $election->start_date ? $election->start_date->format('M j, Y, g:i A') : null;
    $endStr = $election->end_date ? $election->end_date->format('M j, Y, g:i A') : null;
    $timelineText = 'No timeframe set';

    if ($startStr && $endStr) {
        $timelineText = "{$startStr} - {$endStr}";
    } elseif ($startStr) {
        $timelineText = "Starts {$startStr}";
    } elseif ($endStr) {
        $timelineText = "Ends {$endStr}";
    }

    // JS payload safe serialization
    $safePayload = json_encode($election);
@endphp

<div class="group bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-200/60 dark:border-slate-800/80 p-6 shadow-sm hover:shadow-md hover:border-slate-300 dark:hover:border-slate-700 transition-all duration-300 flex flex-col justify-between">
    <div>
        <!-- Card Top Header -->
        <div class="flex items-center justify-between gap-4 mb-4">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $badgeClass }}">
                {!! $badgeIcon !!}
                {{ $election->status }}
            </span>
            
            <div class="flex items-center gap-1.5 opacity-0 group-hover:opacity-100 transition-opacity">
                <button onclick="openEditModal({{ $safePayload }})" class="p-1.5 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg text-slate-500 hover:text-slate-800 dark:hover:text-slate-200 transition" title="Edit Properties">
                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </button>
                <button onclick="deleteElection({{ $election->id }})" class="p-1.5 hover:bg-rose-50 dark:hover:bg-rose-950/20 rounded-lg text-slate-500 hover:text-rose-600 dark:hover:text-rose-400 transition" title="Delete Election">
                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Title & Details -->
        <h3 class="text-lg font-bold text-slate-900 dark:text-white leading-tight mb-2 group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">
            {{ $election->title }}
        </h3>
        
        <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed line-clamp-3 mb-5">
            {{ $election->description ?? 'No description provided.' }}
        </p>
    </div>

    <!-- Footer Stats & Timeline -->
    <div class="mt-4 pt-4 border-t border-slate-100 dark:border-slate-800/80">
        <div class="flex items-center gap-5 text-slate-400 dark:text-slate-500 text-[11px] font-semibold mb-4">
            <span class="flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                {{ $election->positions_count ?? 0 }} Positions
            </span>
            <span class="flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                {{ $election->voters_count ?? 0 }} Votes Cast
            </span>
        </div>

        <div class="flex items-center justify-between gap-4">
            <span class="flex items-center gap-1.5 text-[10px] text-slate-400 dark:text-slate-500 font-medium">
                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span class="truncate max-w-[140px]" title="{{ $timelineText }}">{{ $timelineText }}</span>
            </span>

            <a href="/committees/election-app/{{ $election->id }}" class="inline-flex items-center justify-center text-xs font-bold py-2 px-4 rounded-xl bg-slate-900 hover:bg-slate-800 dark:bg-slate-800 dark:hover:bg-slate-700 text-white dark:text-slate-200 shadow-sm transition">
                Manage
            </a>
        </div>
    </div>
</div>
