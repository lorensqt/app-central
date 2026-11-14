{{-- ============================================================
     EDIT ELECTION MODAL
     Contract with the Elections Dashboard (index.blade.php) — keep these as-is:
       ids:   edit-election-modal, edit-election-modal-content, edit-election-form,
              edit_election_id, edit_title, edit_description, edit_status,
              edit_start_date, edit_end_date
       calls: closeEditModal(), submitEditElection(event)
       The dashboard fills the fields by id and then un-hides the modal; this partial
       listens for that and syncs its own UI (status cards, counters, change tracking).
     ============================================================ --}}
@php
    $statusOptions = [
        'draft' => [
            'label' => 'Draft',
            'desc'  => 'Hidden from public. Still customizable.',
            'dot'   => 'bg-slate-400',
            'on'    => 'data-[active=true]:border-slate-400 data-[active=true]:bg-slate-50 data-[active=true]:ring-4 data-[active=true]:ring-slate-400/20 dark:data-[active=true]:border-slate-500 dark:data-[active=true]:bg-slate-800/60',
        ],
        'active' => [
            'label' => 'Active',
            'desc'  => 'Visible for public voting.',
            'dot'   => 'bg-emerald-500',
            'on'    => 'data-[active=true]:border-emerald-500 data-[active=true]:bg-emerald-50 data-[active=true]:ring-4 data-[active=true]:ring-emerald-500/20 dark:data-[active=true]:border-emerald-500/70 dark:data-[active=true]:bg-emerald-500/10',
        ],
        'closed' => [
            'label' => 'Closed',
            'desc'  => 'Voting ended. Results visible.',
            'dot'   => 'bg-rose-500',
            'on'    => 'data-[active=true]:border-rose-500 data-[active=true]:bg-rose-50 data-[active=true]:ring-4 data-[active=true]:ring-rose-500/20 dark:data-[active=true]:border-rose-500/70 dark:data-[active=true]:bg-rose-500/10',
        ],
    ];

    $inputClass = 'w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-800 shadow-sm transition duration-200 placeholder:text-slate-400 focus:border-purple-500 focus:outline-none focus:ring-4 focus:ring-purple-500/10 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 dark:placeholder:text-slate-600';
@endphp

<div id="edit-election-modal"
    role="dialog" aria-modal="true" aria-labelledby="edit-election-heading"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/70 p-4 opacity-0 backdrop-blur-[6px] transition-all duration-300 dark:bg-slate-950/90">

    <div id="edit-election-modal-content"
        class="flex max-h-[92vh] w-full max-w-2xl scale-95 transform flex-col overflow-hidden rounded-3xl bg-white opacity-0 shadow-2xl ring-1 ring-slate-200/60 transition-all duration-300 dark:bg-slate-900 dark:ring-slate-800">

        {{-- ===== Header ===== --}}
        <div class="relative shrink-0 overflow-hidden border-b border-slate-100 px-6 py-5 dark:border-slate-800/70 sm:px-8">
            <div class="pointer-events-none absolute -left-10 -top-12 h-36 w-36 rounded-full bg-purple-400/15 blur-3xl"></div>
            <div class="pointer-events-none absolute -bottom-14 right-0 h-36 w-36 rounded-full bg-indigo-400/15 blur-3xl"></div>

            <div class="relative flex items-start justify-between gap-4">
                <div class="flex min-w-0 items-center gap-4">
                    <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-purple-500 to-indigo-600 text-white shadow-lg shadow-purple-600/25">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </span>
                    <div class="min-w-0">
                        <h3 id="edit-election-heading" class="text-xl font-bold leading-tight tracking-tight text-slate-900 dark:text-white sm:text-2xl">Edit election</h3>
                        <p class="mt-0.5 truncate text-xs text-slate-500 dark:text-slate-400">
                            Editing <span id="edit_election_name" class="font-semibold text-slate-700 dark:text-slate-200">this election</span>
                        </p>
                    </div>
                </div>

                <button type="button" onclick="closeEditModal()" aria-label="Close"
                    class="-mr-2 -mt-1 shrink-0 rounded-xl p-2 text-slate-400 transition duration-150 hover:bg-slate-100 hover:text-slate-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-purple-500 active:scale-95 dark:text-slate-500 dark:hover:bg-slate-800 dark:hover:text-slate-200">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>

        <form id="edit-election-form" onsubmit="submitEditElection(event)" class="flex min-h-0 flex-grow flex-col">
            @csrf
            @method('PUT')

            <input type="hidden" id="edit_election_id" name="id">

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
                            <label for="edit_title" class="text-xs font-semibold text-slate-600 dark:text-slate-300">Election title <span class="text-rose-500">*</span></label>
                            <span id="edit_title_count" class="text-[11px] font-medium tabular-nums text-slate-400 dark:text-slate-500">0/255</span>
                        </div>
                        <input type="text" name="title" id="edit_title" required maxlength="255" autocomplete="off"
                            placeholder="e.g. Committee Officers Election 2026"
                            class="{{ $inputClass }}">
                    </div>

                    <div class="space-y-1.5">
                        <label for="edit_description" class="text-xs font-semibold text-slate-600 dark:text-slate-300">
                            Description <span class="font-normal text-slate-400 dark:text-slate-500">(optional)</span>
                        </label>
                        <textarea name="description" id="edit_description" rows="3"
                            placeholder="Add details about the election, voting guidelines, eligibility..."
                            class="{{ $inputClass }} custom-scrollbar resize-none"></textarea>
                    </div>
                </section>

                {{-- ---- 2. Schedule ---- --}}
                <section class="space-y-4">
                    <h4 class="flex items-center gap-2 text-[11px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500">
                        <span class="flex h-5 w-5 items-center justify-center rounded-md bg-purple-100 text-[10px] text-purple-600 dark:bg-purple-500/15 dark:text-purple-400">2</span>
                        Schedule
                        <span class="font-medium normal-case tracking-normal text-slate-400 dark:text-slate-500">(optional)</span>
                    </h4>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div class="space-y-1.5">
                            <label for="edit_start_date" class="text-xs font-semibold text-slate-600 dark:text-slate-300">Starts</label>
                            <input type="datetime-local" name="start_date" id="edit_start_date"
                                class="{{ $inputClass }} [color-scheme:light] dark:[color-scheme:dark]">
                        </div>
                        <div class="space-y-1.5">
                            <label for="edit_end_date" class="text-xs font-semibold text-slate-600 dark:text-slate-300">Ends</label>
                            <input type="datetime-local" name="end_date" id="edit_end_date"
                                class="{{ $inputClass }} [color-scheme:light] dark:[color-scheme:dark]">
                        </div>
                    </div>

                    {{-- Extend the end date from wherever it currently is --}}
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="text-[11px] font-semibold text-slate-400 dark:text-slate-500">Extend end by:</span>
                        @foreach ([1 => '+1 day', 3 => '+3 days', 7 => '+1 week', 14 => '+2 weeks'] as $days => $label)
                            <button type="button" onclick="extendEditElection({{ $days }})"
                                class="rounded-lg border border-slate-200 bg-white px-2.5 py-1 text-[11px] font-semibold text-slate-600 shadow-sm transition hover:border-purple-300 hover:bg-purple-50 hover:text-purple-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-purple-500 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-300 dark:hover:border-purple-500/40 dark:hover:bg-purple-500/10 dark:hover:text-purple-300">
                                {{ $label }}
                            </button>
                        @endforeach
                        <button type="button" onclick="clearEditElectionSchedule()"
                            class="rounded-lg px-2 py-1 text-[11px] font-semibold text-slate-400 transition hover:text-rose-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-purple-500 dark:text-slate-500 dark:hover:text-rose-400">
                            Clear dates
                        </button>
                    </div>

                    {{-- Live feedback: voting window length / invalid range / end already passed --}}
                    <p id="edit_schedule_hint" role="status"
                        class="hidden items-center gap-2 rounded-xl px-3 py-2 text-xs font-medium data-[state=ok]:bg-emerald-50 data-[state=ok]:text-emerald-700 data-[state=warn]:bg-amber-50 data-[state=warn]:text-amber-700 data-[state=error]:bg-rose-50 data-[state=error]:text-rose-700 dark:data-[state=ok]:bg-emerald-500/10 dark:data-[state=ok]:text-emerald-400 dark:data-[state=warn]:bg-amber-500/10 dark:data-[state=warn]:text-amber-400 dark:data-[state=error]:bg-rose-500/10 dark:data-[state=error]:text-rose-400">
                    </p>
                </section>

                {{-- ---- 3. Status ---- --}}
                <fieldset class="space-y-4">
                    <legend class="flex items-center gap-2 text-[11px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500">
                        <span class="flex h-5 w-5 items-center justify-center rounded-md bg-purple-100 text-[10px] text-purple-600 dark:bg-purple-500/15 dark:text-purple-400">3</span>
                        Status
                    </legend>

                    {{-- The real, submitted field. The dashboard sets its .value by id; the cards below mirror it. --}}
                    <select name="status" id="edit_status" required tabindex="-1" aria-hidden="true" class="sr-only">
                        <option value="draft">Draft</option>
                        <option value="active">Active</option>
                        <option value="closed">Closed</option>
                    </select>

                    <div role="radiogroup" aria-label="Election status" class="grid grid-cols-1 gap-3 sm:grid-cols-3">
                        @foreach ($statusOptions as $value => $opt)
                            <button type="button" role="radio" aria-checked="false"
                                data-status-card="{{ $value }}" data-active="false"
                                onclick="setEditElectionStatus('{{ $value }}')"
                                class="group relative h-full rounded-2xl border border-slate-200 bg-white p-4 text-left shadow-sm transition duration-200 hover:border-slate-300 focus:outline-none focus-visible:ring-4 focus-visible:ring-purple-500/30 dark:border-slate-800 dark:bg-slate-950 dark:hover:border-slate-700 {{ $opt['on'] }}">
                                <span class="absolute right-3 top-3 flex h-5 w-5 items-center justify-center rounded-full bg-purple-600 text-white opacity-0 transition group-data-[active=true]:opacity-100">
                                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3.5" d="M5 13l4 4L19 7"/></svg>
                                </span>
                                <span class="mb-1.5 flex items-center gap-2">
                                    <span class="h-2.5 w-2.5 rounded-full {{ $opt['dot'] }}"></span>
                                    <span class="text-sm font-bold text-slate-900 dark:text-white">{{ $opt['label'] }}</span>
                                </span>
                                <span class="block text-[11px] leading-snug text-slate-500 dark:text-slate-400">{{ $opt['desc'] }}</span>
                            </button>
                        @endforeach
                    </div>

                    {{-- Shown only when the status differs from what the election has now --}}
                    <p id="edit_status_note" role="status"
                        class="hidden items-center gap-2 rounded-xl bg-amber-50 px-3 py-2 text-xs font-medium text-amber-700 dark:bg-amber-500/10 dark:text-amber-400"></p>
                </fieldset>
            </div>

            {{-- ===== Footer ===== --}}
            <div class="flex shrink-0 items-center justify-between gap-3 border-t border-slate-100 bg-white px-6 py-4 dark:border-slate-800 dark:bg-slate-900 sm:px-8">
                <p id="edit_unsaved" data-dirty="false"
                    class="flex items-center gap-2 text-[11px] font-semibold text-slate-400 dark:text-slate-500 data-[dirty=true]:text-amber-600 dark:data-[dirty=true]:text-amber-400">
                    <span class="h-2 w-2 rounded-full bg-slate-300 dark:bg-slate-600 [[data-dirty=true]_&]:bg-amber-500"></span>
                    <span id="edit_unsaved_text">No changes yet</span>
                </p>

                <div class="flex items-center gap-3">
                    <button type="button" onclick="closeEditModal()"
                        class="rounded-xl border border-slate-200 bg-white px-5 py-3 text-xs font-semibold text-slate-700 shadow-sm transition duration-150 hover:bg-slate-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-purple-500 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-300 dark:hover:bg-slate-900">
                        Cancel
                    </button>
                    {{-- Keep this button text-only: the dashboard swaps its textContent while saving --}}
                    <button type="submit"
                        class="rounded-xl bg-purple-600 px-6 py-3 text-xs font-semibold text-white shadow-lg shadow-purple-600/20 transition duration-150 hover:bg-purple-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-purple-500 focus-visible:ring-offset-2 active:scale-[0.98] disabled:cursor-not-allowed disabled:opacity-60 dark:focus-visible:ring-offset-slate-900">Save Changes</button>
                </div>
            </div>
        </form>
    </div>
</div>

@once
<script>
    (function () {
        const modal = document.getElementById('edit-election-modal');
        const form = document.getElementById('edit-election-form');
        if (!modal || !form) return;

        const title = document.getElementById('edit_title');
        const titleCount = document.getElementById('edit_title_count');
        const desc = document.getElementById('edit_description');
        const start = document.getElementById('edit_start_date');
        const end = document.getElementById('edit_end_date');
        const status = document.getElementById('edit_status');
        const hint = document.getElementById('edit_schedule_hint');
        const statusNote = document.getElementById('edit_status_note');
        const unsaved = document.getElementById('edit_unsaved');
        const unsavedText = document.getElementById('edit_unsaved_text');
        const nameEl = document.getElementById('edit_election_name');
        const cards = form.querySelectorAll('[data-status-card]');

        const LABELS = { draft: 'Draft', active: 'Active', closed: 'Closed' };
        const pad = n => String(n).padStart(2, '0');
        const toInput = d => `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())}T${pad(d.getHours())}:${pad(d.getMinutes())}`;
        const plural = (n, w) => `${n} ${w}${n === 1 ? '' : 's'}`;

        // What the election looked like when the modal opened (to detect changes)
        let baseline = null;
        const snapshot = () => ({
            title: title.value, description: desc.value,
            start: start.value, end: end.value, status: status.value
        });

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

        function paintStatus() {
            cards.forEach(card => {
                const on = card.dataset.statusCard === status.value;
                card.dataset.active = on;
                card.setAttribute('aria-checked', on);
            });
        }

        function updateCount() {
            titleCount.textContent = `${title.value.length}/${title.maxLength}`;
        }

        function validateSchedule() {
            start.value ? end.setAttribute('min', start.value) : end.removeAttribute('min');
            end.setCustomValidity('');
            let hasError = false;

            if (start.value && end.value) {
                const diff = new Date(end.value) - new Date(start.value);
                if (isNaN(diff)) {
                    showHint(null);
                } else if (diff <= 0) {
                    hasError = true;
                    end.setCustomValidity('The end must be after the start.');
                    showHint('error', 'The end date must be after the start date.');
                } else {
                    showHint('ok', `Voting window: ${describe(diff)}`);
                }
            } else {
                showHint(null);
            }

            // An active election whose end date is already behind us is worth a heads-up
            if (!hasError && end.value && status.value === 'active' && new Date(end.value) < new Date()) {
                showHint('warn', 'This end date has already passed.');
            }
        }

        function updateStatusNote() {
            if (baseline && status.value !== baseline.status) {
                statusNote.textContent = `Status will change from ${LABELS[baseline.status] || baseline.status} to ${LABELS[status.value] || status.value}.`;
                statusNote.classList.remove('hidden');
                statusNote.classList.add('flex');
            } else {
                statusNote.classList.add('hidden');
                statusNote.classList.remove('flex');
                statusNote.textContent = '';
            }
        }

        function updateDirty() {
            let dirty = false;
            if (baseline) {
                const now = snapshot();
                dirty = Object.keys(now).some(k => now[k] !== baseline[k]);
            }
            unsaved.dataset.dirty = dirty;
            unsavedText.textContent = dirty ? 'Unsaved changes' : 'No changes yet';
        }

        function refresh() {
            updateCount();
            validateSchedule();
            paintStatus();
            updateStatusNote();
            updateDirty();
        }

        // ---- Actions used by the buttons in the markup ----
        window.setEditElectionStatus = function (value) {
            status.value = value;
            refresh();
        };

        // Pushes the end date forward from its current value (or the start, or now if both are empty)
        window.extendEditElection = function (days) {
            let base = end.value ? new Date(end.value) : (start.value ? new Date(start.value) : new Date());
            if (isNaN(base)) base = new Date();
            base.setSeconds(0, 0);
            end.value = toInput(new Date(base.getTime() + days * 86400000));
            refresh();
        };

        window.clearEditElectionSchedule = function () {
            start.value = '';
            end.value = '';
            refresh();
        };

        // ---- Wiring ----
        form.addEventListener('input', refresh);
        form.addEventListener('change', refresh);

        // The dashboard calls form.reset() after closing
        form.addEventListener('reset', () => setTimeout(refresh, 0));

        // Ctrl/Cmd + Enter saves from anywhere in the form
        form.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' && (e.ctrlKey || e.metaKey)) {
                e.preventDefault();
                form.requestSubmit();
            }
        });

        // The dashboard fills the fields by id, then removes the `hidden` class. On that moment,
        // take a snapshot as the baseline and sync everything that was set programmatically.
        let wasHidden = modal.classList.contains('hidden');
        new MutationObserver(() => {
            const isHidden = modal.classList.contains('hidden');
            if (wasHidden && !isHidden) {
                baseline = snapshot();
                nameEl.textContent = baseline.title || 'this election';
                refresh();
                setTimeout(() => title.focus(), 120);
            }
            wasHidden = isHidden;
        }).observe(modal, { attributes: true, attributeFilter: ['class'] });

        refresh();
    })();
</script>
@endonce