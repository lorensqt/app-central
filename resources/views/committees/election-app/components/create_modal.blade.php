{{-- ============================================================
     CREATE ELECTION MODAL
     Contract with the Elections Dashboard (index.blade.php) — keep these as-is:
       ids:   create-election-modal, create-election-modal-content, create-election-form
       calls: closeCreateModal(), submitCreateElection(event)
       field names: title, description, start_date, end_date, status
     ============================================================ --}}
@php
    $statusOptions = [
        'draft' => [
            'label' => 'Draft',
            'desc'  => 'Hidden from public. Still customizable.',
            'dot'   => 'bg-slate-400',
            'on'    => 'peer-checked:border-slate-400 peer-checked:bg-slate-50 peer-checked:ring-slate-400/20 dark:peer-checked:border-slate-500 dark:peer-checked:bg-slate-800/60',
        ],
        'active' => [
            'label' => 'Active',
            'desc'  => 'Visible for public voting.',
            'dot'   => 'bg-emerald-500',
            'on'    => 'peer-checked:border-emerald-500 peer-checked:bg-emerald-50 peer-checked:ring-emerald-500/20 dark:peer-checked:border-emerald-500/70 dark:peer-checked:bg-emerald-500/10',
        ],
        'closed' => [
            'label' => 'Closed',
            'desc'  => 'Voting ended. Results visible.',
            'dot'   => 'bg-rose-500',
            'on'    => 'peer-checked:border-rose-500 peer-checked:bg-rose-50 peer-checked:ring-rose-500/20 dark:peer-checked:border-rose-500/70 dark:peer-checked:bg-rose-500/10',
        ],
    ];

    $inputClass = 'w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-800 shadow-sm transition duration-200 placeholder:text-slate-400 focus:border-purple-500 focus:outline-none focus:ring-4 focus:ring-purple-500/10 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 dark:placeholder:text-slate-600';
@endphp

<div id="create-election-modal"
    role="dialog" aria-modal="true" aria-labelledby="create-election-heading"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/70 p-4 opacity-0 backdrop-blur-[6px] transition-all duration-300 dark:bg-slate-950/90">

    <div id="create-election-modal-content"
        class="flex max-h-[92vh] w-full max-w-2xl scale-95 transform flex-col overflow-hidden rounded-3xl bg-white opacity-0 shadow-2xl ring-1 ring-slate-200/60 transition-all duration-300 dark:bg-slate-900 dark:ring-slate-800">

        {{-- ===== Header ===== --}}
        <div class="relative shrink-0 overflow-hidden border-b border-slate-100 px-6 py-5 dark:border-slate-800/70 sm:px-8">
            <div class="pointer-events-none absolute -left-10 -top-12 h-36 w-36 rounded-full bg-purple-400/15 blur-3xl"></div>
            <div class="pointer-events-none absolute -bottom-14 right-0 h-36 w-36 rounded-full bg-indigo-400/15 blur-3xl"></div>

            <div class="relative flex items-start justify-between gap-4">
                <div class="flex items-center gap-4">
                    <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-purple-500 to-indigo-600 text-white shadow-lg shadow-purple-600/25">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                    </span>
                    <div class="min-w-0">
                        <h3 id="create-election-heading" class="text-xl font-bold leading-tight tracking-tight text-slate-900 dark:text-white sm:text-2xl">Create new election</h3>
                        <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">Set up a secure internal election for M Lhuillier standing committees.</p>
                    </div>
                </div>

                <button type="button" onclick="closeCreateModal()" aria-label="Close"
                    class="-mr-2 -mt-1 shrink-0 rounded-xl p-2 text-slate-400 transition duration-150 hover:bg-slate-100 hover:text-slate-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-purple-500 active:scale-95 dark:text-slate-500 dark:hover:bg-slate-800 dark:hover:text-slate-200">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>

        <form id="create-election-form" onsubmit="submitCreateElection(event)" class="flex min-h-0 flex-grow flex-col">
            @csrf

            {{-- ===== Scrollable body ===== --}}
            <div class="custom-scrollbar flex-grow space-y-7 overflow-y-auto bg-slate-50/60 p-6 dark:bg-slate-950/30 sm:p-8">

                {{-- ---- 1. Details ---- --}}
                <section class="space-y-4">
                    <h4 class="flex items-center gap-2 text-[11px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500">
                        <span class="flex h-5 w-5 items-center justify-center rounded-md bg-purple-100 text-[10px] text-purple-600 dark:bg-purple-500/15 dark:text-purple-400">1</span>
                        Details
                    </h4>

                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between">
                            <label for="create_title" class="text-xs font-semibold text-slate-600 dark:text-slate-300">Election title <span class="text-rose-500">*</span></label>
                            <span id="create_title_count" class="text-[11px] font-medium tabular-nums text-slate-400 dark:text-slate-500">0/255</span>
                        </div>
                        <input type="text" name="title" id="create_title" required maxlength="255" autocomplete="off"
                            placeholder="e.g. Committee Officers Election 2026"
                            class="{{ $inputClass }}">
                    </div>

                    <div class="space-y-1.5">
                        <label for="create_description" class="text-xs font-semibold text-slate-600 dark:text-slate-300">
                            Description <span class="font-normal text-slate-400 dark:text-slate-500">(optional)</span>
                        </label>
                        <textarea name="description" id="create_description" rows="3"
                            placeholder="Add details about the election, voting guidelines, eligibility..."
                            class="{{ $inputClass }} custom-scrollbar resize-none"></textarea>
                    </div>
                </section>

                {{-- ---- 2. Schedule ---- --}}
                <section class="space-y-4">
                    <div class="flex items-center justify-between gap-3">
                        <h4 class="flex items-center gap-2 text-[11px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500">
                            <span class="flex h-5 w-5 items-center justify-center rounded-md bg-purple-100 text-[10px] text-purple-600 dark:bg-purple-500/15 dark:text-purple-400">2</span>
                            Schedule
                            <span class="font-medium normal-case tracking-normal text-slate-400 dark:text-slate-500">(optional)</span>
                        </h4>
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div class="space-y-1.5">
                            <label for="create_start_date" class="text-xs font-semibold text-slate-600 dark:text-slate-300">Starts</label>
                            <input type="datetime-local" name="start_date" id="create_start_date"
                                class="{{ $inputClass }} [color-scheme:light] dark:[color-scheme:dark]">
                        </div>
                        <div class="space-y-1.5">
                            <label for="create_end_date" class="text-xs font-semibold text-slate-600 dark:text-slate-300">Ends</label>
                            <input type="datetime-local" name="end_date" id="create_end_date"
                                class="{{ $inputClass }} [color-scheme:light] dark:[color-scheme:dark]">
                        </div>
                    </div>

                    {{-- Quick presets --}}
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="text-[11px] font-semibold text-slate-400 dark:text-slate-500">Quick set:</span>
                        @foreach ([1 => '1 day', 3 => '3 days', 7 => '1 week', 14 => '2 weeks'] as $days => $label)
                            <button type="button" onclick="setCreateElectionDuration({{ $days }})"
                                class="rounded-lg border border-slate-200 bg-white px-2.5 py-1 text-[11px] font-semibold text-slate-600 shadow-sm transition hover:border-purple-300 hover:bg-purple-50 hover:text-purple-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-purple-500 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-300 dark:hover:border-purple-500/40 dark:hover:bg-purple-500/10 dark:hover:text-purple-300">
                                {{ $label }}
                            </button>
                        @endforeach
                        <button type="button" onclick="clearCreateElectionSchedule()"
                            class="rounded-lg px-2 py-1 text-[11px] font-semibold text-slate-400 transition hover:text-rose-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-purple-500 dark:text-slate-500 dark:hover:text-rose-400">
                            Clear
                        </button>
                    </div>

                    {{-- Live feedback: voting window length / invalid range --}}
                    <p id="create_schedule_hint" role="status"
                        class="hidden items-center gap-2 rounded-xl px-3 py-2 text-xs font-medium data-[state=ok]:bg-emerald-50 data-[state=ok]:text-emerald-700 data-[state=error]:bg-rose-50 data-[state=error]:text-rose-700 dark:data-[state=ok]:bg-emerald-500/10 dark:data-[state=ok]:text-emerald-400 dark:data-[state=error]:bg-rose-500/10 dark:data-[state=error]:text-rose-400">
                    </p>
                </section>

                {{-- ---- 3. Status ---- --}}
                <fieldset class="space-y-4">
                    <legend class="flex items-center gap-2 text-[11px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500">
                        <span class="flex h-5 w-5 items-center justify-center rounded-md bg-purple-100 text-[10px] text-purple-600 dark:bg-purple-500/15 dark:text-purple-400">3</span>
                        Status
                    </legend>

                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">
                        @foreach ($statusOptions as $value => $opt)
                            <label class="relative block cursor-pointer">
                                <input type="radio" name="status" value="{{ $value }}" class="peer sr-only" {{ $value === 'draft' ? 'checked' : '' }} required>
                                <div class="h-full rounded-2xl border border-slate-200 bg-white p-4 shadow-sm ring-0 transition duration-200 hover:border-slate-300 peer-checked:ring-4 peer-focus-visible:ring-4 peer-focus-visible:ring-purple-500/30 dark:border-slate-800 dark:bg-slate-950 dark:hover:border-slate-700 {{ $opt['on'] }}">
                                    <div class="mb-1.5 flex items-center gap-2">
                                        <span class="h-2.5 w-2.5 rounded-full {{ $opt['dot'] }}"></span>
                                        <span class="text-sm font-bold text-slate-900 dark:text-white">{{ $opt['label'] }}</span>
                                    </div>
                                    <p class="text-[11px] leading-snug text-slate-500 dark:text-slate-400">{{ $opt['desc'] }}</p>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </fieldset>
            </div>

            {{-- ===== Footer ===== --}}
            <div class="flex shrink-0 items-center justify-between gap-3 border-t border-slate-100 bg-white px-6 py-4 dark:border-slate-800 dark:bg-slate-900 sm:px-8">
                <p class="hidden text-[11px] text-slate-400 dark:text-slate-500 sm:block">
                    <kbd class="rounded border border-slate-200 bg-slate-50 px-1.5 py-0.5 font-sans text-[10px] font-semibold dark:border-slate-700 dark:bg-slate-800">Ctrl</kbd>
                    +
                    <kbd class="rounded border border-slate-200 bg-slate-50 px-1.5 py-0.5 font-sans text-[10px] font-semibold dark:border-slate-700 dark:bg-slate-800">Enter</kbd>
                    to create
                </p>

                <div class="ml-auto flex items-center gap-3">
                    <button type="button" onclick="closeCreateModal()"
                        class="rounded-xl border border-slate-200 bg-white px-5 py-3 text-xs font-semibold text-slate-700 shadow-sm transition duration-150 hover:bg-slate-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-purple-500 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-300 dark:hover:bg-slate-900">
                        Cancel
                    </button>
                    {{-- Keep this button text-only: the dashboard swaps its textContent while saving --}}
                    <button type="submit"
                        class="rounded-xl bg-purple-600 px-6 py-3 text-xs font-semibold text-white shadow-lg shadow-purple-600/20 transition duration-150 hover:bg-purple-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-purple-500 focus-visible:ring-offset-2 active:scale-[0.98] disabled:cursor-not-allowed disabled:opacity-60 dark:focus-visible:ring-offset-slate-900">Create Election</button>
                </div>
            </div>
        </form>
    </div>
</div>

@once
<script>
    (function () {
        const modal = document.getElementById('create-election-modal');
        const form = document.getElementById('create-election-form');
        if (!modal || !form) return;

        const title = document.getElementById('create_title');
        const titleCount = document.getElementById('create_title_count');
        const start = document.getElementById('create_start_date');
        const end = document.getElementById('create_end_date');
        const hint = document.getElementById('create_schedule_hint');

        const pad = n => String(n).padStart(2, '0');
        const toInput = d => `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())}T${pad(d.getHours())}:${pad(d.getMinutes())}`;
        const plural = (n, w) => `${n} ${w}${n === 1 ? '' : 's'}`;

        function describe(ms) {
            const mins = Math.round(ms / 60000);
            const d = Math.floor(mins / 1440);
            const h = Math.floor((mins % 1440) / 60);
            const m = mins % 60;
            const parts = [];
            if (d) parts.push(plural(d, 'day'));
            if (h) parts.push(plural(h, 'hour'));
            if (m && !d) parts.push(plural(m, 'minute'));
            return parts.join(' ') || 'under a minute';
        }

        function showHint(state, text) {
            if (!state) {
                hint.classList.add('hidden');
                hint.classList.remove('flex');
                hint.textContent = '';
                delete hint.dataset.state;
                return;
            }
            hint.dataset.state = state;
            hint.textContent = text;
            hint.classList.remove('hidden');
            hint.classList.add('flex');
        }

        function updateCount() {
            titleCount.textContent = `${title.value.length}/${title.maxLength}`;
        }

        function validateSchedule() {
            start.value ? end.setAttribute('min', start.value) : end.removeAttribute('min');
            end.setCustomValidity('');

            if (start.value && end.value) {
                const diff = new Date(end.value) - new Date(start.value);
                if (isNaN(diff)) { showHint(null); return; }
                if (diff <= 0) {
                    end.setCustomValidity('The end must be after the start.');
                    showHint('error', 'The end date must be after the start date.');
                } else {
                    showHint('ok', `Voting window: ${describe(diff)}`);
                }
            } else {
                showHint(null);
            }
        }

        // Quick-set: keeps the chosen start (or starts at the next quarter-hour) and sets the end N days later
        window.setCreateElectionDuration = function (days) {
            let s = start.value ? new Date(start.value) : null;
            if (!s || isNaN(s)) {
                s = new Date();
                s.setSeconds(0, 0);
                s.setMinutes(Math.ceil(s.getMinutes() / 15) * 15);
                start.value = toInput(s);
            }
            end.value = toInput(new Date(s.getTime() + days * 86400000));
            validateSchedule();
        };

        window.clearCreateElectionSchedule = function () {
            start.value = '';
            end.value = '';
            validateSchedule();
        };

        title.addEventListener('input', updateCount);
        start.addEventListener('input', validateSchedule);
        end.addEventListener('input', validateSchedule);

        // The dashboard calls form.reset() after closing; refresh the derived UI afterwards
        form.addEventListener('reset', () => setTimeout(() => { updateCount(); validateSchedule(); }, 0));

        // Ctrl/Cmd + Enter submits from anywhere in the form
        form.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' && (e.ctrlKey || e.metaKey)) {
                e.preventDefault();
                form.requestSubmit();
            }
        });

        // Focus the title when the modal opens (the dashboard just toggles the `hidden` class)
        let wasHidden = modal.classList.contains('hidden');
        new MutationObserver(() => {
            const isHidden = modal.classList.contains('hidden');
            if (wasHidden && !isHidden) setTimeout(() => title.focus(), 120);
            wasHidden = isHidden;
        }).observe(modal, { attributes: true, attributeFilter: ['class'] });

        updateCount();
    })();
</script>
@endonce