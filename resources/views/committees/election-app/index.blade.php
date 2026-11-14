@extends('layouts.app')

@section('title', 'Elections Dashboard')

@section('styles')
<style>
    /* Cards ease in one after another. `backwards` (not `both`) so the card's own hover transform still works afterwards. */
    @keyframes cardIn {
        from { opacity: 0; transform: translateY(10px); }
        to   { opacity: 1; transform: none; }
    }
    .stagger-in > * { animation: cardIn .45s ease backwards; }
    .stagger-in > *:nth-child(2) { animation-delay: .05s; }
    .stagger-in > *:nth-child(3) { animation-delay: .10s; }
    .stagger-in > *:nth-child(4) { animation-delay: .15s; }
    .stagger-in > *:nth-child(5) { animation-delay: .20s; }
    .stagger-in > *:nth-child(n+6) { animation-delay: .25s; }
    @media (prefers-reduced-motion: reduce) {
        .stagger-in > * { animation: none; }
    }
    .no-scrollbar { scrollbar-width: none; }
    .no-scrollbar::-webkit-scrollbar { display: none; }
</style>
@endsection

@section('content')
@php
    // Counts for the stats strip + filter pills. Pass a `$counts` array from the controller
    // (so they don't change when a filter is applied); falls back to counting what was loaded.
    $loaded = $elections instanceof \Illuminate\Pagination\AbstractPaginator ? $elections->getCollection() : collect($elections);
    $counts = $counts ?? [
        'all'    => $loaded->count(),
        'active' => $loaded->where('status', 'active')->count(),
        'draft'  => $loaded->where('status', 'draft')->count(),
        'closed' => $loaded->where('status', 'closed')->count(),
    ];

    $tiles = [
        'all'    => ['label' => 'Total elections', 'chip' => 'bg-purple-100 text-purple-600 dark:bg-purple-500/15 dark:text-purple-400', 'icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10'],
        'active' => ['label' => 'Live now',        'chip' => 'bg-emerald-100 text-emerald-600 dark:bg-emerald-500/15 dark:text-emerald-400', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
        'draft'  => ['label' => 'Drafts',          'chip' => 'bg-slate-100 text-slate-500 dark:bg-slate-700/40 dark:text-slate-300', 'icon' => 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z'],
        'closed' => ['label' => 'Closed',          'chip' => 'bg-rose-100 text-rose-600 dark:bg-rose-500/15 dark:text-rose-400', 'icon' => 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z'],
    ];

    $pills = ['all' => 'All', 'active' => 'Active', 'draft' => 'Drafts', 'closed' => 'Closed'];
@endphp

<div class="space-y-6">

    {{-- ============ HEADER ============ --}}
    <header class="relative overflow-hidden rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200/70 dark:bg-slate-900 dark:ring-slate-800 sm:p-8">
        <div class="pointer-events-none absolute -left-20 -top-20 h-64 w-64 rounded-full bg-purple-400/10 blur-3xl"></div>
        <div class="pointer-events-none absolute -bottom-24 -right-16 h-64 w-64 rounded-full bg-indigo-400/10 blur-3xl"></div>

        <div class="relative flex flex-col gap-5 md:flex-row md:items-end md:justify-between">
            <div class="min-w-0">
                <nav class="mb-3 flex items-center gap-1.5 text-xs font-medium text-slate-400 dark:text-slate-500" aria-label="Breadcrumb">
                    <a href="{{ route('dashboard') }}" class="transition hover:text-purple-600 dark:hover:text-purple-400">Workspaces</a>
                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                    <span>Committees</span>
                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                    <span class="text-slate-600 dark:text-slate-300">Elections</span>
                </nav>
                <h1 class="text-2xl font-extrabold leading-tight tracking-tight text-slate-900 dark:text-white sm:text-3xl">Elections Dashboard</h1>
                <p class="mt-1.5 max-w-xl text-sm leading-relaxed text-slate-500 dark:text-slate-400">
                    Create and manage secure internal elections for M Lhuillier standing committees.
                </p>
            </div>

            <button type="button" onclick="openCreateModal()"
                class="inline-flex shrink-0 items-center justify-center gap-2 self-start rounded-2xl bg-purple-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-purple-600/20 transition hover:bg-purple-700 hover:shadow-purple-600/30 focus:outline-none focus-visible:ring-2 focus-visible:ring-purple-500 focus-visible:ring-offset-2 active:scale-[0.98] dark:focus-visible:ring-offset-slate-950 md:self-auto">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                New Election
                <kbd class="ml-1 hidden rounded-md bg-white/20 px-1.5 py-0.5 text-[10px] font-bold sm:inline">N</kbd>
            </button>
        </div>
    </header>

    {{-- ============ STATS STRIP (click to filter) ============ --}}
    <section class="grid grid-cols-2 gap-3 lg:grid-cols-4" aria-label="Election summary">
        @foreach ($tiles as $key => $tile)
            <button type="button" data-stat-tile="{{ $key }}" onclick="setStatus('{{ $key }}')"
                class="group flex items-center gap-3.5 rounded-2xl bg-white p-4 text-left shadow-sm ring-1 ring-slate-200/70 transition hover:-translate-y-0.5 hover:shadow-md focus:outline-none focus-visible:ring-2 focus-visible:ring-purple-500 data-[active=true]:ring-2 data-[active=true]:ring-purple-500/70 dark:bg-slate-900 dark:ring-slate-800">
                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl {{ $tile['chip'] }}">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="{{ $tile['icon'] }}"/></svg>
                </span>
                <span class="min-w-0 leading-tight">
                    <span class="block text-2xl font-extrabold tabular-nums text-slate-900 dark:text-white" data-stat="{{ $key }}" data-target="{{ (int) ($counts[$key] ?? 0) }}">{{ number_format($counts[$key] ?? 0) }}</span>
                    <span class="mt-0.5 block text-xs font-medium text-slate-400 dark:text-slate-500">{{ $tile['label'] }}</span>
                </span>
            </button>
        @endforeach
    </section>

    {{-- ============ TOOLBAR (sticky) ============ --}}
    <div class="sticky top-[65px] z-30 -mx-4 bg-slate-50/80 px-4 py-3 backdrop-blur-xl dark:bg-slate-950/80 sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">

            {{-- Search --}}
            <div class="relative w-full md:max-w-md">
                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 dark:text-slate-500">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </span>
                <input type="text" id="search-input" oninput="handleSearchInput()" autocomplete="off"
                    placeholder="Search elections..."
                    class="w-full rounded-2xl border border-slate-200 bg-white py-3 pl-11 pr-12 text-sm text-slate-800 shadow-sm transition duration-150 placeholder:text-slate-400 focus:border-purple-500 focus:outline-none focus:ring-4 focus:ring-purple-500/10 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-200">
                <kbd id="search-hint" class="pointer-events-none absolute inset-y-0 right-3 my-auto hidden h-6 items-center rounded-md border border-slate-200 bg-slate-50 px-2 text-[11px] font-semibold text-slate-400 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-500 sm:inline-flex">/</kbd>
                <button type="button" id="search-clear" onclick="clearSearch()" aria-label="Clear search"
                    class="absolute inset-y-0 right-2 my-auto hidden h-7 w-7 items-center justify-center rounded-lg text-slate-400 transition hover:bg-slate-100 hover:text-slate-700 dark:hover:bg-slate-800 dark:hover:text-slate-200">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="flex items-center gap-2">
                {{-- Status pills with counts --}}
                <div class="no-scrollbar flex min-w-0 flex-1 items-center gap-1 overflow-x-auto rounded-2xl border border-slate-200/50 bg-slate-200/50 p-1 dark:border-slate-800/60 dark:bg-slate-900 md:flex-none" role="group" aria-label="Filter by status">
                    @foreach ($pills as $key => $label)
                        <button type="button" data-status-pill="{{ $key }}" onclick="setStatus('{{ $key }}')" aria-pressed="{{ $key === 'all' ? 'true' : 'false' }}"
                            class="flex shrink-0 items-center gap-1.5 rounded-xl px-3.5 py-2 text-xs font-bold text-slate-500 transition duration-150 hover:text-slate-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-purple-500 data-[active=true]:bg-white data-[active=true]:text-purple-600 data-[active=true]:shadow-sm dark:text-slate-400 dark:hover:text-slate-200 dark:data-[active=true]:bg-slate-800 dark:data-[active=true]:text-purple-400">
                            {{ $label }}
                            <span data-pill-count="{{ $key }}" class="rounded-md bg-slate-200/70 px-1.5 py-0.5 text-[10px] font-bold tabular-nums text-slate-500 dark:bg-slate-700/60 dark:text-slate-400">{{ number_format($counts[$key] ?? 0) }}</span>
                        </button>
                    @endforeach
                </div>

                {{-- Sort --}}
                <div class="relative shrink-0">
                    <select id="sort-select" onchange="handleSortChange()" aria-label="Sort elections"
                        class="appearance-none rounded-2xl border border-slate-200 bg-white py-2.5 pl-3.5 pr-9 text-xs font-bold text-slate-600 shadow-sm transition focus:border-purple-500 focus:outline-none focus:ring-4 focus:ring-purple-500/10 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-300">
                        <option value="latest">Newest</option>
                        <option value="ending">Ending soon</option>
                        <option value="votes">Most votes</option>
                    </select>
                    <svg class="pointer-events-none absolute right-3 top-1/2 h-3.5 w-3.5 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M19 9l-7 7-7-7"/></svg>
                </div>
            </div>
        </div>
    </div>

    {{-- ============ GRID ============ --}}
    <div id="elections-grid" class="stagger-in grid grid-cols-1 gap-6 transition-opacity duration-200 md:grid-cols-2 lg:grid-cols-3" aria-live="polite">
        @forelse($elections as $election)
            @include('committees.election-app.components.election_card', ['election' => $election])
        @empty
            <div class="col-span-full" id="empty-state">
                @include('committees.election-app.components.empty_state')
            </div>
        @endforelse
    </div>
</div>

{{-- Kept in a <template> so the partial can be reused from JS without breaking template literals --}}
<template id="empty-state-template">
    @include('committees.election-app.components.empty_state')
</template>

<!-- Modals -->
@include('committees.election-app.components.create_modal')
@include('committees.election-app.components.edit_modal')

@endsection

@section('scripts')
<script>
    let activeStatus = 'all';
    let searchQuery = '';
    let sortBy = 'latest';
    let searchTimeout = null;
    let fetchController = null;

    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    // =========================================================
    // COUNT-UP (used for the stats strip)
    // =========================================================
    function countTo(el, target) {
        const from = parseInt((el.textContent || '0').replace(/,/g, ''), 10) || 0;
        if (reduceMotion || from === target) {
            el.textContent = target.toLocaleString();
            return;
        }
        const start = performance.now();
        const duration = 900;
        (function frame(now) {
            const t = Math.min((now - start) / duration, 1);
            const eased = 1 - Math.pow(1 - t, 3);
            el.textContent = Math.round(from + (target - from) * eased).toLocaleString();
            if (t < 1) requestAnimationFrame(frame);
        })(start);
    }

    function updateCounts(counts) {
        if (!counts) return;
        ['all', 'active', 'draft', 'closed'].forEach(key => {
            if (counts[key] === undefined) return;
            const n = parseInt(counts[key], 10) || 0;
            document.querySelectorAll(`[data-stat="${key}"]`).forEach(el => countTo(el, n));
            document.querySelectorAll(`[data-pill-count="${key}"]`).forEach(el => el.textContent = n.toLocaleString());
        });
    }

    // =========================================================
    // MODAL UTILS
    // =========================================================
    function openCreateModal() {
        const modal = document.getElementById('create-election-modal');
        const content = document.getElementById('create-election-modal-content');
        modal.classList.remove('hidden');
        modal.classList.add('flex');

        requestAnimationFrame(() => {
            modal.classList.remove('opacity-0');
            modal.classList.add('opacity-100');
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        });
    }

    function closeCreateModal() {
        const modal = document.getElementById('create-election-modal');
        const content = document.getElementById('create-election-modal-content');

        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        modal.classList.remove('opacity-100');
        modal.classList.add('opacity-0');

        setTimeout(() => {
            modal.classList.remove('flex');
            modal.classList.add('hidden');
            document.getElementById('create-election-form').reset();
        }, 300);
    }

    // Laravel serializes dates as UTC ("...Z"); datetime-local inputs need the viewer's local time.
    function toLocalInput(value) {
        if (!value) return '';
        const d = new Date(value);
        if (isNaN(d)) return '';
        const p = n => String(n).padStart(2, '0');
        return `${d.getFullYear()}-${p(d.getMonth() + 1)}-${p(d.getDate())}T${p(d.getHours())}:${p(d.getMinutes())}`;
    }

    function openEditModal(election) {
        const modal = document.getElementById('edit-election-modal');
        const content = document.getElementById('edit-election-modal-content');

        document.getElementById('edit_election_id').value = election.id;
        document.getElementById('edit_title').value = election.title;
        document.getElementById('edit_description').value = election.description || '';
        document.getElementById('edit_status').value = election.status;
        document.getElementById('edit_start_date').value = toLocalInput(election.start_date);
        document.getElementById('edit_end_date').value = toLocalInput(election.end_date);

        modal.classList.remove('hidden');
        modal.classList.add('flex');

        requestAnimationFrame(() => {
            modal.classList.remove('opacity-0');
            modal.classList.add('opacity-100');
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        });
    }

    function closeEditModal() {
        const modal = document.getElementById('edit-election-modal');
        const content = document.getElementById('edit-election-modal-content');

        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        modal.classList.remove('opacity-100');
        modal.classList.add('opacity-0');

        setTimeout(() => {
            modal.classList.remove('flex');
            modal.classList.add('hidden');
            document.getElementById('edit-election-form').reset();
        }, 300);
    }

    // Close on click outside modal card
    document.getElementById('create-election-modal').onclick = function (e) {
        if (e.target === this) closeCreateModal();
    };
    document.getElementById('edit-election-modal').onclick = function (e) {
        if (e.target === this) closeEditModal();
    };

    // =========================================================
    // FILTER / SEARCH / SORT STATE
    // =========================================================
    function paintFilters() {
        document.querySelectorAll('[data-status-pill]').forEach(pill => {
            const on = pill.dataset.statusPill === activeStatus;
            pill.dataset.active = on;
            pill.setAttribute('aria-pressed', on);
        });
        document.querySelectorAll('[data-stat-tile]').forEach(tile => {
            tile.dataset.active = tile.dataset.statTile === activeStatus;
        });

        const hasText = searchQuery.length > 0;
        document.getElementById('search-clear').classList.toggle('hidden', !hasText);
        document.getElementById('search-clear').classList.toggle('flex', hasText);
        // "/" hint: only on sm+ screens and only while the box is empty (the base `hidden` class stays on)
        document.getElementById('search-hint').classList.toggle('sm:inline-flex', !hasText);
    }

    function setStatus(status) {
        activeStatus = status;
        paintFilters();
        fetchElections();
    }

    function handleSearchInput() {
        searchQuery = document.getElementById('search-input').value;
        paintFilters();

        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(fetchElections, 300); // debounce
    }

    function clearSearch() {
        const input = document.getElementById('search-input');
        input.value = '';
        searchQuery = '';
        paintFilters();
        input.focus();
        fetchElections();
    }

    function handleSortChange() {
        sortBy = document.getElementById('sort-select').value;
        fetchElections();
    }

    // Keep the address bar in sync so refresh / back keeps your place.
    function syncUrl() {
        const url = new URL(window.location.href);
        activeStatus !== 'all' ? url.searchParams.set('status', activeStatus) : url.searchParams.delete('status');
        searchQuery.trim() ? url.searchParams.set('search', searchQuery) : url.searchParams.delete('search');
        sortBy !== 'latest' ? url.searchParams.set('sort', sortBy) : url.searchParams.delete('sort');
        history.replaceState(null, '', url);
    }

    // =========================================================
    // LOADING / EMPTY STATES
    // =========================================================
    function skeletonCards(n = 6) {
        const card = `
            <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200/70 dark:bg-slate-900 dark:ring-slate-800">
                <div class="animate-pulse space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="h-6 w-20 rounded-full bg-slate-200 dark:bg-slate-800"></div>
                        <div class="h-6 w-14 rounded-lg bg-slate-100 dark:bg-slate-800/60"></div>
                    </div>
                    <div class="h-5 w-3/4 rounded-lg bg-slate-200 dark:bg-slate-800"></div>
                    <div class="space-y-2">
                        <div class="h-3 w-full rounded bg-slate-100 dark:bg-slate-800/60"></div>
                        <div class="h-3 w-5/6 rounded bg-slate-100 dark:bg-slate-800/60"></div>
                    </div>
                    <div class="grid grid-cols-2 gap-3 pt-2">
                        <div class="h-14 rounded-2xl bg-slate-100 dark:bg-slate-800/60"></div>
                        <div class="h-14 rounded-2xl bg-slate-100 dark:bg-slate-800/60"></div>
                    </div>
                    <div class="h-10 rounded-2xl bg-slate-200 dark:bg-slate-800"></div>
                </div>
            </div>`;
        return Array.from({ length: n }, () => card).join('');
    }

    function emptyStateHtml() {
        const hasFilter = activeStatus !== 'all' || searchQuery.trim() !== '';

        if (!hasFilter) {
            // "No elections yet" — reuse the existing partial
            return `<div class="col-span-full" id="empty-state">${document.getElementById('empty-state-template').innerHTML}</div>`;
        }

        const what = searchQuery.trim()
            ? `No elections match <span class="font-semibold text-slate-700 dark:text-slate-200">&ldquo;${escapeHtml(searchQuery.trim())}&rdquo;</span>`
            : `There are no ${activeStatus === 'draft' ? 'draft' : activeStatus} elections`;

        return `
            <div class="col-span-full">
                <div class="flex flex-col items-center justify-center rounded-3xl border border-dashed border-slate-300 bg-white/60 px-6 py-16 text-center dark:border-slate-700 dark:bg-slate-900/40">
                    <span class="mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-slate-400 dark:bg-slate-800 dark:text-slate-500">
                        <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </span>
                    <h3 class="text-base font-bold text-slate-900 dark:text-white">Nothing here</h3>
                    <p class="mt-1 max-w-sm text-sm text-slate-500 dark:text-slate-400">${what}.</p>
                    <button type="button" onclick="resetFilters()" class="mt-5 rounded-xl bg-slate-900 px-4 py-2.5 text-xs font-bold text-white transition hover:bg-purple-600 dark:bg-slate-800 dark:hover:bg-purple-600">
                        Clear filters
                    </button>
                </div>
            </div>`;
    }

    function resetFilters() {
        activeStatus = 'all';
        searchQuery = '';
        document.getElementById('search-input').value = '';
        paintFilters();
        fetchElections();
    }

    // =========================================================
    // AJAX FETCH
    // =========================================================
    function fetchElections() {
        if (fetchController) fetchController.abort(); // ignore stale responses
        fetchController = new AbortController();

        const grid = document.getElementById('elections-grid');
        grid.style.opacity = '0.6';
        const skeletonTimer = setTimeout(() => { grid.innerHTML = skeletonCards(); }, 150);

        syncUrl();

        const url = new URL(window.location.href);
        url.searchParams.set('status', activeStatus);
        url.searchParams.set('search', searchQuery);
        url.searchParams.set('sort', sortBy);

        fetch(url, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            signal: fetchController.signal
        })
        .then(response => response.json())
        .then(res => {
            clearTimeout(skeletonTimer);

            // Preferred: the server returns rendered cards as `res.html` (same Blade card, one source of truth).
            // Fallback: build cards from `res.data` with the JS renderer below.
            const items = res.data || [];
            const hasHtml = typeof res.html === 'string';
            const isEmpty = hasHtml ? res.html.trim() === '' : items.length === 0;

            if (res.success === false || isEmpty) {
                grid.innerHTML = emptyStateHtml();
            } else {
                grid.innerHTML = hasHtml ? res.html : items.map(renderElectionCard).join('');
                if (window.initCountUps) window.initCountUps(grid); // card count-up animation
            }

            updateCounts(res.counts);
            grid.style.opacity = '1';
        })
        .catch(err => {
            if (err.name === 'AbortError') return;
            clearTimeout(skeletonTimer);
            console.error('Error fetching elections:', err);
            window.showToast('Failed to load elections.', 'error');
            grid.style.opacity = '1';
        });
    }

    // =========================================================
    // FALLBACK CARD RENDERER (only used when the server doesn't return `res.html`)
    // Mirrors committees/election-app/components/election_card.blade.php — keep the two in sync,
    // or return `html` from the controller and delete this function.
    // =========================================================
    function renderElectionCard(election) {
        const themes = {
            active: {
                pill: 'bg-emerald-50 text-emerald-700 ring-emerald-600/20 dark:bg-emerald-500/10 dark:text-emerald-400 dark:ring-emerald-400/20',
                dot: 'bg-emerald-500',
                accent: 'from-emerald-400 via-teal-400 to-cyan-400',
                bar: 'bg-gradient-to-r from-emerald-400 to-teal-500',
                pulse: true
            },
            draft: {
                pill: 'bg-slate-100 text-slate-600 ring-slate-500/20 dark:bg-slate-500/10 dark:text-slate-300 dark:ring-slate-400/20',
                dot: 'bg-slate-400',
                accent: 'from-slate-300 via-slate-400 to-slate-300 dark:from-slate-700 dark:via-slate-600 dark:to-slate-700',
                bar: 'bg-slate-400',
                pulse: false
            }
        };
        const theme = themes[election.status] || {
            pill: 'bg-rose-50 text-rose-700 ring-rose-600/20 dark:bg-rose-500/10 dark:text-rose-400 dark:ring-rose-400/20',
            dot: 'bg-rose-500',
            accent: 'from-rose-400 via-pink-400 to-orange-300',
            bar: 'bg-gradient-to-r from-rose-400 to-pink-500',
            pulse: false
        };

        // ---- Timeline ----
        const toDate = v => { const d = v ? new Date(v) : null; return d && !isNaN(d) ? d : null; };
        const start = toDate(election.start_date);
        const end = toDate(election.end_date);
        const fmt = d => d.toLocaleDateString('en-US', { month: 'short', day: 'numeric' })
            + ', ' + d.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true });

        let progress = null;
        if (start && end && end > start) {
            progress = Math.max(0, Math.min(100, Math.round(((Date.now() - start) / (end - start)) * 100)));
        }

        let timeLeft = null;
        if (election.status === 'active' && end && end.getTime() > Date.now()) {
            const mins = Math.floor((end.getTime() - Date.now()) / 60000);
            const d = Math.floor(mins / 1440), h = Math.floor((mins % 1440) / 60), m = mins % 60;
            timeLeft = (d > 0 ? `${d}d ${h}h` : h > 0 ? `${h}h ${m}m` : `${m}m`) + ' left';
        }

        const timelineHtml = (start || end) ? `
            <div class="mb-4">
                ${progress !== null && election.status === 'active' ? `
                    <div class="mb-2 h-1.5 w-full overflow-hidden rounded-full bg-slate-100 dark:bg-slate-800">
                        <div class="h-full rounded-full ${theme.bar} transition-all duration-700" style="width: ${progress}%"></div>
                    </div>` : ''}
                <div class="flex items-center justify-between gap-3 text-[11px] text-slate-400 dark:text-slate-500">
                    <div class="flex items-center gap-1.5 truncate">
                        <svg class="h-3.5 w-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span class="truncate font-medium">
                            ${start ? fmt(start) : '—'}
                            <span class="mx-0.5 text-slate-300 dark:text-slate-600">→</span>
                            ${end ? fmt(end) : '—'}
                        </span>
                    </div>
                    ${timeLeft ? `<span class="shrink-0 rounded-md bg-emerald-50 px-1.5 py-0.5 font-semibold text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400">${timeLeft}</span>` : ''}
                </div>
            </div>` : `<p class="mb-4 text-[11px] font-medium text-slate-400 dark:text-slate-500">No timeframe set</p>`;

        // Escaped so quotes / ampersands in titles can't break the attribute
        const safePayload = escapeHtml(JSON.stringify(election));
        const positions = parseInt(election.positions_count, 10) || 0;
        const votes = parseInt(election.voters_count, 10) || 0;

        return `
            <div class="group relative flex flex-col justify-between overflow-hidden rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200/70 transition-all duration-300 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-slate-200/60 hover:ring-slate-300 dark:bg-slate-900 dark:ring-slate-800 dark:hover:shadow-black/30 dark:hover:ring-slate-700">
                <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r ${theme.accent}"></div>

                <div>
                    <div class="mb-4 flex items-center justify-between gap-3">
                        <span class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-[11px] font-semibold uppercase tracking-wider ring-1 ring-inset ${theme.pill}">
                            <span class="relative flex h-2 w-2">
                                ${theme.pulse ? `<span class="absolute inline-flex h-full w-full animate-ping rounded-full opacity-60 ${theme.dot}"></span>` : ''}
                                <span class="relative inline-flex h-2 w-2 rounded-full ${theme.dot}"></span>
                            </span>
                            ${escapeHtml(election.status)}
                        </span>

                        <div class="flex items-center gap-1 opacity-100 transition-opacity sm:opacity-0 sm:group-hover:opacity-100 sm:group-focus-within:opacity-100">
                            <button type="button" onclick="openEditModal(${safePayload})"
                                    class="rounded-xl p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-800 focus:outline-none focus-visible:ring-2 focus-visible:ring-purple-500 dark:hover:bg-slate-800 dark:hover:text-slate-200"
                                    title="Edit election" aria-label="Edit election">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </button>
                            <button type="button" onclick="deleteElection(${election.id})"
                                    class="rounded-xl p-2 text-slate-400 transition hover:bg-rose-50 hover:text-rose-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-rose-500 dark:hover:bg-rose-950/30 dark:hover:text-rose-400"
                                    title="Delete election" aria-label="Delete election">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>
                    </div>

                    <h3 class="mb-2 text-lg font-bold leading-snug tracking-tight text-slate-900 transition-colors group-hover:text-purple-600 dark:text-white dark:group-hover:text-purple-400">
                        ${escapeHtml(election.title)}
                    </h3>
                    <p class="mb-5 line-clamp-3 text-sm leading-relaxed text-slate-500 dark:text-slate-400">
                        ${election.description ? escapeHtml(election.description) : 'No description provided.'}
                    </p>

                    <div class="grid grid-cols-2 gap-3">
                        <div class="flex items-center gap-3 rounded-2xl bg-slate-50 p-3 dark:bg-slate-800/50">
                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-purple-100 text-purple-600 dark:bg-purple-500/15 dark:text-purple-400">
                                <svg class="h-[18px] w-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                            </span>
                            <div class="min-w-0 leading-tight">
                                <div class="text-base font-bold tabular-nums text-slate-900 dark:text-white" data-count-up="${positions}">${positions.toLocaleString()}</div>
                                <div class="text-[11px] font-medium text-slate-400 dark:text-slate-500">Positions</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 rounded-2xl bg-slate-50 p-3 dark:bg-slate-800/50">
                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-sky-100 text-sky-600 dark:bg-sky-500/15 dark:text-sky-400">
                                <svg class="h-[18px] w-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            </span>
                            <div class="min-w-0 leading-tight">
                                <div class="text-base font-bold tabular-nums text-slate-900 dark:text-white" data-count-up="${votes}">${votes.toLocaleString()}</div>
                                <div class="text-[11px] font-medium text-slate-400 dark:text-slate-500">Votes cast</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-5 border-t border-slate-100 pt-4 dark:border-slate-800">
                    ${timelineHtml}
                    <a href="/committees/election-app/${election.id}"
                       class="group/cta inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-purple-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-purple-500 focus-visible:ring-offset-2 dark:bg-slate-800 dark:text-slate-100 dark:hover:bg-purple-600 dark:focus-visible:ring-offset-slate-900">
                        Manage election
                        <svg class="h-4 w-4 transition-transform group-hover/cta:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    </a>
                </div>
            </div>`;
    }

    // =========================================================
    // FORM SUBMISSIONS
    // =========================================================
    function submitCreateElection(event) {
        event.preventDefault();
        const form = event.target;
        const submitBtn = form.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.textContent = 'Creating...';

        const formData = new FormData(form);

        fetch("{{ route('committees.election.store') }}", {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(res => {
            if (res.success) {
                window.showToast(res.message, 'success');
                closeCreateModal();
                fetchElections();
            } else {
                window.showToast(res.message || 'Failed to create election.', 'error');
            }
            submitBtn.disabled = false;
            submitBtn.textContent = 'Create Election';
        })
        .catch(err => {
            console.error('Error:', err);
            window.showToast('Server connection failed.', 'error');
            submitBtn.disabled = false;
            submitBtn.textContent = 'Create Election';
        });
    }

    function submitEditElection(event) {
        event.preventDefault();
        const form = event.target;
        const electionId = document.getElementById('edit_election_id').value;
        const submitBtn = form.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.textContent = 'Saving...';

        const formData = new FormData(form);

        fetch(`/committees/election-app/${electionId}`, {
            method: 'POST', // Form spoofing via PUT handles it
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(res => {
            if (res.success) {
                window.showToast(res.message, 'success');
                closeEditModal();
                fetchElections();
            } else {
                window.showToast(res.message || 'Failed to update election.', 'error');
            }
            submitBtn.disabled = false;
            submitBtn.textContent = 'Save Changes';
        })
        .catch(err => {
            console.error('Error:', err);
            window.showToast('Server connection failed.', 'error');
            submitBtn.disabled = false;
            submitBtn.textContent = 'Save Changes';
        });
    }

    function deleteElection(id) {
        window.showConfirmModal(
            'Delete Election',
            'Are you sure you want to delete this election?',
            'This will permanently delete the election, all associated positions, candidates, and all votes cast. This action cannot be undone.',
            () => {
                fetch(`/committees/election-app/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(res => {
                    if (res.success) {
                        window.showToast(res.message, 'success');
                        fetchElections();
                    } else {
                        window.showToast(res.message || 'Failed to delete election.', 'error');
                    }
                })
                .catch(err => {
                    console.error('Error:', err);
                    window.showToast('Server connection failed.', 'error');
                });
            }
        );
    }

    // =========================================================
    // HELPERS
    // =========================================================
    function formatDate(dateStr) {
        const d = new Date(dateStr);
        if (isNaN(d)) return '';
        // Format: Sep 8, 2026, 10:00 AM
        return d.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })
            + ', ' + d.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true });
    }

    function escapeHtml(str) {
        if (str === null || str === undefined) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    // =========================================================
    // KEYBOARD SHORTCUTS:  /  focus search · N  new election · Esc  close modals
    // =========================================================
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            if (!document.getElementById('create-election-modal').classList.contains('hidden')) closeCreateModal();
            if (!document.getElementById('edit-election-modal').classList.contains('hidden')) closeEditModal();
            return;
        }

        if (e.metaKey || e.ctrlKey || e.altKey) return;
        const t = e.target;
        const typing = t.matches && t.matches('input, textarea, select, [contenteditable="true"]');
        const modalOpen = !document.getElementById('create-election-modal').classList.contains('hidden')
            || !document.getElementById('edit-election-modal').classList.contains('hidden')
            || document.querySelector('.swal2-container');
        if (typing || modalOpen) return;

        if (e.key === '/') {
            e.preventDefault();
            document.getElementById('search-input').focus();
        } else if (e.key === 'n' || e.key === 'N') {
            e.preventDefault();
            openCreateModal();
        }
    });

    // =========================================================
    // INIT
    // =========================================================
    (function init() {
        // Restore state from the URL (?status=active&search=...&sort=ending)
        const params = new URLSearchParams(window.location.search);
        const status = params.get('status');
        if (['active', 'draft', 'closed'].includes(status)) activeStatus = status;
        searchQuery = params.get('search') || '';
        const sort = params.get('sort');
        if (['ending', 'votes'].includes(sort)) sortBy = sort;

        document.getElementById('search-input').value = searchQuery;
        document.getElementById('sort-select').value = sortBy;
        paintFilters();

        // Stats strip: count up from 0 (the real value is already in the HTML if JS is off)
        document.querySelectorAll('[data-stat]').forEach(el => {
            const target = parseInt(el.dataset.target, 10) || 0;
            el.textContent = '0';
            countTo(el, target);
        });

        // If the URL carried a non-default filter, make sure the grid matches it
        if (activeStatus !== 'all' || searchQuery !== '' || sortBy !== 'latest') {
            fetchElections();
        }
    })();
</script>
@endsection