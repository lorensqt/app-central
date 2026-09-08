@extends('layouts.app')

@section('title', 'Elections Dashboard')

@section('content')
<div class="space-y-8">
    <!-- Premium Header Panel (Visual Splendor with Subtle Gradients) -->
    <div class="relative overflow-hidden bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-200/80 dark:border-slate-800/80 p-8 sm:p-10 shadow-sm">
        <div class="absolute -top-24 -left-24 w-72 h-72 bg-purple-400/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -right-24 w-72 h-72 bg-indigo-400/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div class="space-y-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-purple-500/10 dark:bg-purple-400/10 text-[10px] font-bold text-purple-600 dark:text-purple-400 uppercase tracking-widest">
                    Corporate Governance
                </span>
                <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight leading-tight">
                    Elections Dashboard
                </h1>
                <p class="text-sm sm:text-base text-slate-500 dark:text-slate-400 max-w-2xl leading-relaxed">
                    Create, manage, and configure secure, internal group elections for M Lhuillier standing committees.
                </p>
            </div>

            <button onclick="openCreateModal()"
                class="inline-flex items-center justify-center gap-2 bg-purple-600 hover:bg-purple-700 active:scale-[0.98] text-white font-semibold text-sm px-6 py-3.5 rounded-2xl shadow-md hover:shadow-lg transition duration-150 shrink-0 self-start md:self-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                New Election
            </button>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4">
        <!-- Search Workspace -->
        <div class="relative flex-grow max-w-md">
            <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 dark:text-slate-500">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </span>
            <input type="text" id="search-input" oninput="handleSearchInput()"
                placeholder="Search elections by title or details..."
                class="w-full pl-11 pr-4 py-3.5 rounded-2xl border border-slate-200 dark:border-slate-800/80 text-slate-800 dark:text-slate-200 text-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-white dark:bg-slate-900 shadow-sm transition duration-150">
        </div>

        <!-- Segmented Tab Pills -->
        <div class="flex items-center gap-1.5 p-1 bg-slate-200/50 dark:bg-slate-900 rounded-2xl self-start md:self-auto border border-slate-200/40 dark:border-slate-800/60">
            <button onclick="filterByStatus('all', this)" class="status-pill px-4 py-2.5 rounded-xl text-xs font-bold transition duration-150 bg-white dark:bg-slate-800 text-purple-600 dark:text-purple-400 shadow-sm">
                All Elections
            </button>
            <button onclick="filterByStatus('active', this)" class="status-pill px-4 py-2.5 rounded-xl text-xs font-bold transition duration-150 text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200">
                Active
            </button>
            <button onclick="filterByStatus('draft', this)" class="status-pill px-4 py-2.5 rounded-xl text-xs font-bold transition duration-150 text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200">
                Drafts
            </button>
            <button onclick="filterByStatus('closed', this)" class="status-pill px-4 py-2.5 rounded-xl text-xs font-bold transition duration-150 text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200">
                Closed
            </button>
        </div>
    </div>

    <!-- Elections Grid Context -->
    <div id="elections-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 transition-all duration-300">
        <!-- Will be loaded dynamically. Initial server-side load: -->
        @forelse($elections as $election)
            @include('committees.election-app.components.election_card', ['election' => $election])
        @empty
            <div class="col-span-full" id="empty-state">
                @include('committees.election-app.components.empty_state')
            </div>
        @endforelse
    </div>
</div>

<!-- Modals -->
@include('committees.election-app.components.create_modal')
@include('committees.election-app.components.edit_modal')

@endsection

@section('scripts')
<script>
    let activeStatus = 'all';
    let searchQuery = '';
    let searchTimeout = null;

    // --- MODAL UTILS ---
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

    function openEditModal(election) {
        const modal = document.getElementById('edit-election-modal');
        const content = document.getElementById('edit-election-modal-content');
        
        // Populate inputs
        document.getElementById('edit_election_id').value = election.id;
        document.getElementById('edit_title').value = election.title;
        document.getElementById('edit_description').value = election.description || '';
        document.getElementById('edit_status').value = election.status;
        
        if (election.start_date) {
            document.getElementById('edit_start_date').value = election.start_date.substring(0, 16);
        } else {
            document.getElementById('edit_start_date').value = '';
        }
        
        if (election.end_date) {
            document.getElementById('edit_end_date').value = election.end_date.substring(0, 16);
        } else {
            document.getElementById('edit_end_date').value = '';
        }

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
    document.getElementById('create-election-modal').onclick = function(e) {
        if (e.target === this) closeCreateModal();
    };
    document.getElementById('edit-election-modal').onclick = function(e) {
        if (e.target === this) closeEditModal();
    };

    // --- FILTER ENGINE ---
    function filterByStatus(status, element) {
        activeStatus = status;

        // Reset pills
        document.querySelectorAll('.status-pill').forEach(pill => {
            pill.className = "status-pill px-4 py-2.5 rounded-xl text-xs font-bold transition duration-150 text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200";
        });

        // Activate selected
        element.className = "status-pill px-4 py-2.5 rounded-xl text-xs font-bold transition duration-150 bg-white dark:bg-slate-800 text-purple-600 dark:text-purple-400 shadow-sm";

        fetchElections();
    }

    function handleSearchInput() {
        searchQuery = document.getElementById('search-input').value;
        
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            fetchElections();
        }, 300); // Debounce search
    }

    // --- AJAX FETCH ---
    function fetchElections() {
        const grid = document.getElementById('elections-grid');
        grid.style.opacity = '0.5';

        const url = new URL(window.location.href);
        url.searchParams.set('status', activeStatus);
        url.searchParams.set('search', searchQuery);

        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(res => {
            grid.innerHTML = '';
            
            if (res.success && res.data.length > 0) {
                res.data.forEach(election => {
                    grid.innerHTML += renderElectionCard(election);
                });
            } else {
                grid.innerHTML = `<div class="col-span-full">@include('committees.election-app.components.empty_state')</div>`;
            }
            grid.style.opacity = '1';
        })
        .catch(err => {
            console.error('Error fetching elections:', err);
            window.showToast('Failed to load elections.', 'error');
            grid.style.opacity = '1';
        });
    }

    // --- CARD RENDERER ---
    function renderElectionCard(election) {
        // Status Badge Style
        let badgeClass = '';
        let badgeIcon = '';
        if (election.status === 'active') {
            badgeClass = 'bg-emerald-500/10 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400';
            badgeIcon = `<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>`;
        } else if (election.status === 'draft') {
            badgeClass = 'bg-slate-500/10 text-slate-600 dark:bg-slate-500/10 dark:text-slate-400';
            badgeIcon = `<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>`;
        } else {
            badgeClass = 'bg-rose-500/10 text-rose-600 dark:bg-rose-500/10 dark:text-rose-400';
            badgeIcon = `<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>`;
        }

        // Timeline String
        let startStr = election.start_date ? formatDate(election.start_date) : null;
        let endStr = election.end_date ? formatDate(election.end_date) : null;
        let timelineText = 'No timeframe set';
        
        if (startStr && endStr) {
            timelineText = `${startStr} - ${endStr}`;
        } else if (startStr) {
            timelineText = `Starts ${startStr}`;
        } else if (endStr) {
            timelineText = `Ends ${endStr}`;
        }

        // JS payload string safely serialized
        const safePayload = JSON.stringify(election).replace(/"/g, '&quot;');

        // HTML Markup
        return `
            <div class="group bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-200/60 dark:border-slate-800/80 p-6 shadow-sm hover:shadow-md hover:border-slate-300 dark:hover:border-slate-700 transition-all duration-300 flex flex-col justify-between">
                <div>
                    <!-- Card Top Header -->
                    <div class="flex items-center justify-between gap-4 mb-4">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider ${badgeClass}">
                            ${badgeIcon}
                            ${election.status}
                        </span>
                        
                        <div class="flex items-center gap-1.5 opacity-0 group-hover:opacity-100 transition-opacity">
                            <button onclick="openEditModal(${safePayload})" class="p-1.5 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg text-slate-500 hover:text-slate-800 dark:hover:text-slate-200 transition" title="Edit Properties">
                                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>
                            <button onclick="deleteElection(${election.id})" class="p-1.5 hover:bg-rose-50 dark:hover:bg-rose-950/20 rounded-lg text-slate-500 hover:text-rose-600 dark:hover:text-rose-400 transition" title="Delete Election">
                                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Title & Details -->
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white leading-tight mb-2 group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">
                        ${escapeHtml(election.title)}
                    </h3>
                    
                    <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed line-clamp-3 mb-5">
                        ${election.description ? escapeHtml(election.description) : 'No description provided.'}
                    </p>
                </div>

                <!-- Footer Stats & Timeline -->
                <div class="mt-4 pt-4 border-t border-slate-100 dark:border-slate-800/80">
                    <div class="flex items-center gap-5 text-slate-400 dark:text-slate-500 text-[11px] font-semibold mb-4">
                        <span class="flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                            ${election.positions_count || 0} Positions
                        </span>
                        <span class="flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            ${election.voters_count || 0} Votes Cast
                        </span>
                    </div>

                    <div class="flex items-center justify-between gap-4">
                        <span class="flex items-center gap-1.5 text-[10px] text-slate-400 dark:text-slate-500 font-medium">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span class="truncate max-w-[140px]" title="${timelineText}">${timelineText}</span>
                        </span>

                        <a href="/committees/election-app/${election.id}" class="inline-flex items-center justify-center text-xs font-bold py-2 px-4 rounded-xl bg-slate-900 hover:bg-slate-800 dark:bg-slate-800 dark:hover:bg-slate-700 text-white dark:text-slate-200 shadow-sm transition">
                            Manage
                        </a>
                    </div>
                </div>
            </div>
        `;
    }

    // --- FORM SUBMISSIONS ---
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

    // --- HELPER UTILS ---
    function formatDate(dateStr) {
        const d = new Date(dateStr);
        if (isNaN(d)) return '';
        // Format: Sep 8, 2026, 10:00 AM
        return d.toLocaleDateString('en-US', {
            month: 'short',
            day: 'numeric',
            year: 'numeric'
        }) + ', ' + d.toLocaleTimeString('en-US', {
            hour: 'numeric',
            minute: '2-digit',
            hour12: true
        });
    }

    function escapeHtml(str) {
        if (!str) return '';
        return str
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }
</script>
@endsection
