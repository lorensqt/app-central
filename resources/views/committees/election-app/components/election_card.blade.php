@php
    // ---- Status theme -------------------------------------------------------
    $themes = [
        'active' => [
            'pill'   => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20 dark:bg-emerald-500/10 dark:text-emerald-400 dark:ring-emerald-400/20',
            'dot'    => 'bg-emerald-500',
            'accent' => 'from-emerald-400 via-teal-400 to-cyan-400',
            'bar'    => 'bg-gradient-to-r from-emerald-400 to-teal-500',
            'pulse'  => true,
        ],
        'draft' => [
            'pill'   => 'bg-slate-100 text-slate-600 ring-slate-500/20 dark:bg-slate-500/10 dark:text-slate-300 dark:ring-slate-400/20',
            'dot'    => 'bg-slate-400',
            'accent' => 'from-slate-300 via-slate-400 to-slate-300 dark:from-slate-700 dark:via-slate-600 dark:to-slate-700',
            'bar'    => 'bg-slate-400',
            'pulse'  => false,
        ],
    ];
    $theme = $themes[$election->status] ?? [
        'pill'   => 'bg-rose-50 text-rose-700 ring-rose-600/20 dark:bg-rose-500/10 dark:text-rose-400 dark:ring-rose-400/20',
        'dot'    => 'bg-rose-500',
        'accent' => 'from-rose-400 via-pink-400 to-orange-300',
        'bar'    => 'bg-gradient-to-r from-rose-400 to-pink-500',
        'pulse'  => false,
    ];

    // ---- Timeline -----------------------------------------------------------
    $start = $election->start_date;
    $end   = $election->end_date;

    // Progress through the voting window (only meaningful for active elections)
    $progress = null;
    if ($start && $end && $end->gt($start)) {
        $total    = $start->diffInSeconds($end);
        $elapsed  = $start->diffInSeconds(now(), false);
        $progress = (int) max(0, min(100, round(($elapsed / $total) * 100)));
    }

    $timeLeft = ($election->status === 'active' && $end && $end->isFuture())
        ? $end->diffForHumans(['parts' => 2, 'short' => true, 'syntax' => \Carbon\CarbonInterface::DIFF_ABSOLUTE]) . ' left'
        : null;
@endphp

<div class="group relative flex flex-col justify-between overflow-hidden rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200/70 transition-all duration-300 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-slate-200/60 hover:ring-slate-300 dark:bg-slate-900 dark:ring-slate-800 dark:hover:shadow-black/30 dark:hover:ring-slate-700">

    {{-- Accent strip --}}
    <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r {{ $theme['accent'] }}"></div>

    <div>
        {{-- Header: status + actions --}}
        <div class="mb-4 flex items-center justify-between gap-3">
            <span class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-[11px] font-semibold uppercase tracking-wider ring-1 ring-inset {{ $theme['pill'] }}">
                <span class="relative flex h-2 w-2">
                    @if ($theme['pulse'])
                        <span class="absolute inline-flex h-full w-full animate-ping rounded-full opacity-60 {{ $theme['dot'] }}"></span>
                    @endif
                    <span class="relative inline-flex h-2 w-2 rounded-full {{ $theme['dot'] }}"></span>
                </span>
                {{ $election->status }}
            </span>

            <div class="flex items-center gap-1 opacity-100 transition-opacity sm:opacity-0 sm:group-hover:opacity-100 sm:group-focus-within:opacity-100">
                <button type="button"
                        onclick="openEditModal(@js($election))"
                        class="rounded-xl p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-800 focus:outline-none focus-visible:ring-2 focus-visible:ring-purple-500 dark:hover:bg-slate-800 dark:hover:text-slate-200"
                        title="Edit election" aria-label="Edit election">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </button>
                <button type="button"
                        onclick="deleteElection({{ $election->id }})"
                        class="rounded-xl p-2 text-slate-400 transition hover:bg-rose-50 hover:text-rose-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-rose-500 dark:hover:bg-rose-950/30 dark:hover:text-rose-400"
                        title="Delete election" aria-label="Delete election">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
            </div>
        </div>

        {{-- Title & description --}}
        <h3 class="mb-2 text-lg font-bold leading-snug tracking-tight text-slate-900 transition-colors group-hover:text-purple-600 dark:text-white dark:group-hover:text-purple-400">
            {{ $election->title }}
        </h3>
        <p class="mb-5 line-clamp-3 text-sm leading-relaxed text-slate-500 dark:text-slate-400">
            {{ $election->description ?: 'No description provided.' }}
        </p>

        {{-- Stat tiles --}}
        <div class="grid grid-cols-2 gap-3">
            <div class="flex items-center gap-3 rounded-2xl bg-slate-50 p-3 dark:bg-slate-800/50">
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-purple-100 text-purple-600 dark:bg-purple-500/15 dark:text-purple-400">
                    <svg class="h-4.5 h-[18px] w-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                </span>
                <div class="min-w-0 leading-tight">
                    <div class="text-base font-bold tabular-nums text-slate-900 dark:text-white" data-count-up="{{ (int) ($election->positions_count ?? 0) }}">{{ number_format($election->positions_count ?? 0) }}</div>
                    <div class="text-[11px] font-medium text-slate-400 dark:text-slate-500">Positions</div>
                </div>
            </div>

            <div class="flex items-center gap-3 rounded-2xl bg-slate-50 p-3 dark:bg-slate-800/50">
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-sky-100 text-sky-600 dark:bg-sky-500/15 dark:text-sky-400">
                    <svg class="h-[18px] w-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </span>
                <div class="min-w-0 leading-tight">
                    <div class="text-base font-bold tabular-nums text-slate-900 dark:text-white" data-count-up="{{ (int) ($election->voters_count ?? 0) }}">{{ number_format($election->voters_count ?? 0) }}</div>
                    <div class="text-[11px] font-medium text-slate-400 dark:text-slate-500">Votes cast</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Footer: timeline + CTA --}}
    <div class="mt-5 border-t border-slate-100 pt-4 dark:border-slate-800">

        @if ($start || $end)
            <div class="mb-4">
                @if ($progress !== null && $election->status === 'active')
                    <div class="mb-2 h-1.5 w-full overflow-hidden rounded-full bg-slate-100 dark:bg-slate-800">
                        <div class="h-full rounded-full {{ $theme['bar'] }} transition-all duration-700" style="width: {{ $progress }}%"></div>
                    </div>
                @endif

                <div class="flex items-center justify-between gap-3 text-[11px] text-slate-400 dark:text-slate-500">
                    <div class="flex items-center gap-1.5 truncate">
                        <svg class="h-3.5 w-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span class="truncate font-medium">
                            {{ $start ? $start->format('M j, g:i A') : '—' }}
                            <span class="mx-0.5 text-slate-300 dark:text-slate-600">→</span>
                            {{ $end ? $end->format('M j, g:i A') : '—' }}
                        </span>
                    </div>
                    @if ($timeLeft)
                        <span class="shrink-0 rounded-md bg-emerald-50 px-1.5 py-0.5 font-semibold text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400">{{ $timeLeft }}</span>
                    @endif
                </div>
            </div>
        @else
            <p class="mb-4 text-[11px] font-medium text-slate-400 dark:text-slate-500">No timeframe set</p>
        @endif

        <a href="/committees/election-app/{{ $election->id }}"
           class="group/cta inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-purple-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-purple-500 focus-visible:ring-offset-2 dark:bg-slate-800 dark:text-slate-100 dark:hover:bg-purple-600 dark:focus-visible:ring-offset-slate-900">
            Manage election
            <svg class="h-4 w-4 transition-transform group-hover/cta:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
        </a>
    </div>
</div>

{{-- Count-up animation (script is only output once, even with many cards) --}}
@once
<script>
    (function () {
        const DURATION = 1200; // ms
        const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        const easeOutCubic = t => 1 - Math.pow(1 - t, 3);

        function animate(el) {
            const target = parseInt(el.dataset.countUp, 10) || 0;
            if (reduceMotion || target === 0) {
                el.textContent = target.toLocaleString();
                return;
            }
            const startTime = performance.now();
            el.textContent = '0';

            function frame(now) {
                const t = Math.min((now - startTime) / DURATION, 1);
                el.textContent = Math.round(target * easeOutCubic(t)).toLocaleString();
                if (t < 1) requestAnimationFrame(frame);
            }
            requestAnimationFrame(frame);
        }

        const observer = 'IntersectionObserver' in window
            ? new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        animate(entry.target);
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.4 })
            : null;

        // Call window.initCountUps() again if you inject cards via JS/AJAX.
        window.initCountUps = function (root = document) {
            root.querySelectorAll('[data-count-up]:not([data-count-bound])').forEach(el => {
                el.dataset.countBound = '1';
                observer ? observer.observe(el) : animate(el);
            });
        };

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => window.initCountUps());
        } else {
            window.initCountUps();
        }
    })();
</script>
@endonce