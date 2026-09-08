@extends('layouts.app')

@section('title', 'Manage Election')

@section('content')
<div class="space-y-8">
    <!-- Back to Dashboard Link -->
    <div>
        <a href="{{ route('committees.election.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 hover:text-purple-600 dark:hover:text-purple-400 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Elections
        </a>
    </div>

    <!-- Header Card -->
    <div class="relative overflow-hidden bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-200/80 dark:border-slate-800/80 p-8 sm:p-10 shadow-sm">
        <div class="absolute -top-24 -left-24 w-72 h-72 bg-purple-400/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -right-24 w-72 h-72 bg-indigo-400/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div class="space-y-3 flex-grow">
                <div class="flex items-center gap-3 flex-wrap">
                    @php
                        $badgeClass = '';
                        if ($election->status === 'active') {
                            $badgeClass = 'bg-emerald-500/10 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400';
                        } elseif ($election->status === 'draft') {
                            $badgeClass = 'bg-slate-500/10 text-slate-600 dark:bg-slate-500/10 dark:text-slate-400';
                        } else {
                            $badgeClass = 'bg-rose-500/10 text-rose-600 dark:bg-rose-500/10 dark:text-rose-400';
                        }
                    @endphp
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $badgeClass }}">
                        {{ $election->status }}
                    </span>
                    
                    @if($election->start_date || $election->end_date)
                        <span class="text-xs text-slate-400 dark:text-slate-500 font-medium flex items-center gap-1.5">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            {{ $election->start_date ? $election->start_date->format('M d, Y, h:i A') : 'Start' }} - {{ $election->end_date ? $election->end_date->format('M d, Y, h:i A') : 'End' }}
                        </span>
                    @endif
                </div>
                
                <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight leading-tight">
                    {{ $election->title }}
                </h1>
                
                <p class="text-sm text-slate-500 dark:text-slate-400 max-w-2xl leading-relaxed">
                    {{ $election->description ?? 'No description provided.' }}
                </p>
            </div>

            <!-- Header Action Controls -->
            <div class="flex items-center gap-2.5 flex-wrap shrink-0">
                <a href="{{ route('elections.voter.setup', $election->id) }}" target="_blank"
                    class="inline-flex items-center justify-center gap-1.5 bg-purple-50 hover:bg-purple-100 dark:bg-purple-950/20 dark:hover:bg-purple-950/40 text-purple-600 dark:text-purple-400 font-semibold text-xs px-4 py-2.5 rounded-xl shadow-sm transition border border-transparent hover:border-purple-100 dark:hover:border-purple-900/40">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    View Voting Page
                </a>

                <button onclick="copyVotingLink()"
                    class="inline-flex items-center justify-center gap-1.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-semibold text-xs px-4 py-2.5 rounded-xl shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                    Copy Link
                </button>

                @if($election->status === 'draft')
                    <button onclick="confirmElectionStatusChange('active')"
                        class="inline-flex items-center justify-center gap-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs px-4 py-2.5 rounded-xl shadow-sm transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Start Election
                    </button>
                @elseif($election->status === 'active')
                    <button onclick="confirmElectionStatusChange('closed')"
                        class="inline-flex items-center justify-center gap-1.5 bg-rose-600 hover:bg-rose-700 text-white font-semibold text-xs px-4 py-2.5 rounded-xl shadow-sm transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 10a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"/></svg>
                        End Election
                    </button>
                    <button onclick="confirmElectionStatusChange('draft')"
                        class="inline-flex items-center justify-center gap-1.5 bg-slate-200 hover:bg-slate-300 dark:bg-slate-800 dark:hover:bg-slate-750 text-slate-700 dark:text-slate-300 font-semibold text-xs px-4 py-2.5 rounded-xl shadow-sm transition">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                        Pause/Revert to Draft
                    </button>
                @elseif($election->status === 'closed')
                    <button onclick="confirmElectionStatusChange('draft')"
                        class="inline-flex items-center justify-center gap-1.5 bg-slate-200 hover:bg-slate-300 dark:bg-slate-800 dark:hover:bg-slate-750 text-slate-700 dark:text-slate-300 font-semibold text-xs px-4 py-2.5 rounded-xl shadow-sm transition">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                        Revert to Draft
                    </button>
                @endif

                <button onclick="openEditElectionModal()"
                    class="inline-flex items-center justify-center gap-1.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-semibold text-xs px-4 py-2.5 rounded-xl shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                    Edit Details
                </button>

                <button onclick="triggerDeleteElectionFromManage()"
                    class="inline-flex items-center justify-center gap-1.5 bg-rose-50 hover:bg-rose-100 dark:bg-rose-950/20 dark:hover:bg-rose-950/40 text-rose-600 dark:text-rose-400 font-semibold text-xs px-4 py-2.5 rounded-xl shadow-sm transition border border-transparent hover:border-rose-100 dark:hover:border-rose-900/40">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    Delete
                </button>
            </div>
        </div>
    </div>

    <!-- Segmented Navigation Controls -->
    <div class="border-b border-slate-200 dark:border-slate-800">
        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
            <button onclick="switchManageTab('structure')" id="tab-btn-structure" class="manage-tab-btn border-purple-600 dark:border-purple-500 text-purple-600 dark:text-purple-400 font-bold border-b-2 py-4 px-1 text-sm focus:outline-none whitespace-nowrap">
                Positions & Candidates
            </button>
            <button onclick="switchManageTab('results')" id="tab-btn-results" class="manage-tab-btn border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 border-b-2 py-4 px-1 text-sm focus:outline-none whitespace-nowrap">
                Election Results
            </button>
            <button onclick="switchManageTab('voters')" id="tab-btn-voters" class="manage-tab-btn border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 border-b-2 py-4 px-1 text-sm focus:outline-none whitespace-nowrap">
                Voters & Analytics
            </button>
        </nav>
    </div>

    <!-- Tab Contents Workspace -->
    <div>
        @include('committees.election-app.manage-elections.structure')
        @include('committees.election-app.manage-elections.results')
        @include('committees.election-app.manage-elections.voters')
    </div>
</div>

<!-- Modals -->
@include('committees.election-app.components.create_position_modal')
@include('committees.election-app.components.edit_position_modal')
@include('committees.election-app.components.create_candidate_modal')
@include('committees.election-app.components.edit_candidate_modal')
@include('committees.election-app.components.edit_modal')

@endsection

@section('scripts')
<script>
    // --- VOTERS AJAX FILTERING ---
    let fetchVotersTimeout = null;

    function fetchFilteredVoters() {
        clearTimeout(fetchVotersTimeout);
        fetchVotersTimeout = setTimeout(() => {
            const search = document.getElementById('voter-filter-search').value;
            const division = document.getElementById('voter-filter-division').value;
            const status = document.getElementById('voter-filter-status').value;
            const sort = document.getElementById('voter-filter-sort').value;

            const url = new URL(`/committees/election-app/{{ $election->id }}/voters`, window.location.origin);
            if (search) url.searchParams.append('search', search);
            if (division) url.searchParams.append('division', division);
            if (status) url.searchParams.append('status', status);
            if (sort) url.searchParams.append('sort', sort);

            const tbody = document.getElementById('voters-table-body');
            const countDisplay = document.getElementById('voter-count-display');

            tbody.classList.add('opacity-40');

            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(res => {
                tbody.classList.remove('opacity-40');
                if (res.success) {
                    countDisplay.textContent = res.voters.length;
                    
                    if (res.voters.length === 0) {
                        tbody.innerHTML = `
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-slate-400 italic">
                                    No voters match the specified criteria.
                                </td>
                            </tr>
                        `;
                        return;
                    }

                    tbody.innerHTML = res.voters.map(voter => {
                        const badgeClass = voter.status === 'Voted' 
                            ? 'bg-emerald-500/10 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400' 
                            : 'bg-amber-500/10 text-amber-600 dark:bg-amber-500/10 dark:text-amber-400';

                        return `
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition duration-150">
                                <td class="px-6 py-4.5 font-semibold text-slate-900 dark:text-white">
                                    ${voter.name}
                                </td>
                                <td class="px-6 py-4.5 text-slate-500 dark:text-slate-400">
                                    ${voter.email}
                                </td>
                                <td class="px-6 py-4.5">
                                    ${voter.division}
                                </td>
                                <td class="px-6 py-4.5">
                                    ${voter.current_position}
                                </td>
                                <td class="px-6 py-4.5">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold ${badgeClass}">
                                        ${voter.status}
                                    </span>
                                </td>
                                <td class="px-6 py-4.5 text-xs text-slate-400">
                                    ${voter.voted_at_formatted}
                                </td>
                            </tr>
                        `;
                    }).join('');
                } else {
                    window.showToast('Failed to fetch voter list.', 'error');
                }
            })
            .catch(err => {
                tbody.classList.remove('opacity-40');
                console.error(err);
                window.showToast('Connection error while fetching voters.', 'error');
            });
        }, 300);
    }

    // --- TAB UTILS ---
    function switchManageTab(tabId) {
        document.querySelectorAll('.manage-tab-panel').forEach(panel => {
            panel.classList.add('hidden');
        });
        document.querySelectorAll('.manage-tab-btn').forEach(btn => {
            btn.className = "manage-tab-btn border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 border-b-2 py-4 px-1 text-sm focus:outline-none whitespace-nowrap";
        });

        document.getElementById('panel-' + tabId).classList.remove('hidden');
        document.getElementById('tab-btn-' + tabId).className = "manage-tab-btn border-purple-600 dark:border-purple-500 text-purple-600 dark:text-purple-400 font-bold border-b-2 py-4 px-1 text-sm focus:outline-none whitespace-nowrap";
    }

    // --- POSITION MODALS ---
    function openCreatePositionModal() {
        const modal = document.getElementById('create-position-modal');
        const content = document.getElementById('create-position-modal-content');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        requestAnimationFrame(() => {
            modal.classList.remove('opacity-0');
            modal.classList.add('opacity-100');
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        });
    }

    function closeCreatePositionModal() {
        const modal = document.getElementById('create-position-modal');
        const content = document.getElementById('create-position-modal-content');
        content.classList.add('scale-95', 'opacity-0');
        modal.classList.add('opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.getElementById('create-position-form').reset();
        }, 300);
    }

    // --- POSITION MODALS ---
    function openEditPositionModal(position) {
        const modal = document.getElementById('edit-position-modal');
        const content = document.getElementById('edit-position-modal-content');
        
        document.getElementById('edit_position_id').value = position.id;
        document.getElementById('edit_pos_name').value = position.name;
        document.getElementById('edit_pos_max_votes').value = position.max_votes;
        document.getElementById('edit_pos_sort_order').value = position.sort_order;

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        requestAnimationFrame(() => {
            modal.classList.remove('opacity-0');
            modal.classList.add('opacity-100');
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        });
    }

    function closeEditPositionModal() {
        const modal = document.getElementById('edit-position-modal');
        const content = document.getElementById('edit-position-modal-content');
        content.classList.add('scale-95', 'opacity-0');
        modal.classList.add('opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.getElementById('edit-position-form').reset();
        }, 300);
    }

    // --- CANDIDATE MODALS ---
    function openCreateCandidateModal(positionId, positionName) {
        const modal = document.getElementById('create-candidate-modal');
        const content = document.getElementById('create-candidate-modal-content');
        
        document.getElementById('add_candidate_position_id').value = positionId;
        document.getElementById('add_candidate_position_name').textContent = positionName;

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        requestAnimationFrame(() => {
            modal.classList.remove('opacity-0');
            modal.classList.add('opacity-100');
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        });
    }

    function closeCreateCandidateModal() {
        const modal = document.getElementById('create-candidate-modal');
        const content = document.getElementById('create-candidate-modal-content');
        content.classList.add('scale-95', 'opacity-0');
        modal.classList.add('opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.getElementById('create-candidate-form').reset();
        }, 300);
    }

    function openEditCandidateModal(candidate) {
        const modal = document.getElementById('edit-candidate-modal');
        const content = document.getElementById('edit-candidate-modal-content');
        
        document.getElementById('edit_candidate_id').value = candidate.id;
        document.getElementById('edit_cand_name').value = candidate.name;
        document.getElementById('edit_cand_party').value = candidate.party_affiliation || '';
        document.getElementById('edit_cand_avatar').value = candidate.avatar_path || '';
        document.getElementById('edit_cand_sort_order').value = candidate.sort_order;

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        requestAnimationFrame(() => {
            modal.classList.remove('opacity-0');
            modal.classList.add('opacity-100');
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        });
    }

    function closeEditCandidateModal() {
        const modal = document.getElementById('edit-candidate-modal');
        const content = document.getElementById('edit-candidate-modal-content');
        content.classList.add('scale-95', 'opacity-0');
        modal.classList.add('opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.getElementById('edit-candidate-form').reset();
        }, 300);
    }

    // Modal background click dismiss handlers
    document.getElementById('create-position-modal').onclick = function(e) { if(e.target===this) closeCreatePositionModal(); };
    document.getElementById('edit-position-modal').onclick = function(e) { if(e.target===this) closeEditPositionModal(); };
    document.getElementById('create-candidate-modal').onclick = function(e) { if(e.target===this) closeCreateCandidateModal(); };
    document.getElementById('edit-candidate-modal').onclick = function(e) { if(e.target===this) closeEditCandidateModal(); };
    document.getElementById('edit-election-modal').onclick = function(e) { if(e.target===this) closeEditModal(); };

    // --- POSITIONS AJAX ACTIONS ---
    function submitCreatePosition(event) {
        event.preventDefault();
        const form = event.target;
        const submitBtn = form.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.textContent = 'Adding...';

        const formData = new FormData(form);

        fetch(`/committees/election-app/{{ $election->id }}/positions`, {
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
                closeCreatePositionModal();
                setTimeout(() => window.location.reload(), 800);
            } else {
                window.showToast(res.message || 'Error occurred.', 'error');
            }
            submitBtn.disabled = false;
            submitBtn.textContent = 'Add Position';
        })
        .catch(err => {
            console.error(err);
            window.showToast('Connection error.', 'error');
            submitBtn.disabled = false;
            submitBtn.textContent = 'Add Position';
        });
    }

    function submitEditPosition(event) {
        event.preventDefault();
        const form = event.target;
        const submitBtn = form.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.textContent = 'Saving...';

        const id = document.getElementById('edit_position_id').value;
        const formData = new FormData(form);

        fetch(`/committees/election-app/positions/${id}`, {
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
                closeEditPositionModal();
                setTimeout(() => window.location.reload(), 800);
            } else {
                window.showToast(res.message || 'Error occurred.', 'error');
            }
            submitBtn.disabled = false;
            submitBtn.textContent = 'Save Changes';
        })
        .catch(err => {
            console.error(err);
            window.showToast('Connection error.', 'error');
            submitBtn.disabled = false;
            submitBtn.textContent = 'Save Changes';
        });
    }

    function deletePosition(id) {
        window.showConfirmModal(
            'Delete Position',
            'Are you sure you want to delete this position?',
            'This will permanently delete this position and all candidates listed under it. Voters will no longer be able to select candidates for this role.',
            () => {
                fetch(`/committees/election-app/positions/${id}`, {
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
                        document.getElementById(`position-block-${id}`).remove();
                        if (document.querySelectorAll('[id^="position-block-"]').length === 0) {
                            window.location.reload();
                        }
                    } else {
                        window.showToast(res.message || 'Error occurred.', 'error');
                    }
                })
                .catch(err => {
                    console.error(err);
                    window.showToast('Connection error.', 'error');
                });
            }
        );
    }

    // --- CANDIDATES AJAX ACTIONS ---
    function submitCreateCandidate(event) {
        event.preventDefault();
        const form = event.target;
        const submitBtn = form.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.textContent = 'Adding...';

        const positionId = document.getElementById('add_candidate_position_id').value;
        const formData = new FormData(form);

        fetch(`/committees/election-app/positions/${positionId}/candidates`, {
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
                closeCreateCandidateModal();
                setTimeout(() => window.location.reload(), 800);
            } else {
                window.showToast(res.message || 'Error occurred.', 'error');
            }
            submitBtn.disabled = false;
            submitBtn.textContent = 'Add Candidate';
        })
        .catch(err => {
            console.error(err);
            window.showToast('Connection error.', 'error');
            submitBtn.disabled = false;
            submitBtn.textContent = 'Add Candidate';
        });
    }

    // --- CANDIDATES AJAX ACTIONS ---
    function submitEditCandidate(event) {
        event.preventDefault();
        const form = event.target;
        const submitBtn = form.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.textContent = 'Saving...';

        const id = document.getElementById('edit_candidate_id').value;
        const formData = new FormData(form);

        fetch(`/committees/election-app/candidates/${id}`, {
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
                closeEditCandidateModal();
                setTimeout(() => window.location.reload(), 800);
            } else {
                window.showToast(res.message || 'Error occurred.', 'error');
            }
            submitBtn.disabled = false;
            submitBtn.textContent = 'Save Changes';
        })
        .catch(err => {
            console.error(err);
            window.showToast('Connection error.', 'error');
            submitBtn.disabled = false;
            submitBtn.textContent = 'Save Changes';
        });
    }

    function deleteCandidate(id) {
        window.showConfirmModal(
            'Delete Candidate',
            'Are you sure you want to delete this candidate?',
            'This will permanently remove the candidate from the ballot.',
            () => {
                fetch(`/committees/election-app/candidates/${id}`, {
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
                        document.getElementById(`candidate-card-${id}`).remove();
                    } else {
                        window.showToast(res.message || 'Error occurred.', 'error');
                    }
                })
                .catch(err => {
                    console.error(err);
                    window.showToast('Connection error.', 'error');
                });
            }
        );
    }

    // --- ELECTION DIRECT ACTIONS ---
    function confirmElectionStatusChange(newStatus) {
        let title = '';
        let message = '';
        let description = '';

        if (newStatus === 'active') {
            title = 'Start Election';
            message = 'Are you sure you want to start this election?';
            description = 'This will open the public voting portal. Eligible voters will be able to cast their ballots in real-time.';
        } else if (newStatus === 'closed') {
            title = 'End Election';
            message = 'Are you sure you want to end this election?';
            description = 'This will close the public voting portal. No further ballots can be cast, and official winners will be certified.';
        } else if (newStatus === 'draft') {
            title = 'Revert to Draft / Pause';
            message = 'Are you sure you want to revert this election to draft?';
            description = 'This will pause or suspend the public voting portal. Voters will not be able to access the ballot booth while the election is in draft.';
        }

        window.showConfirmModal(
            title,
            message,
            description,
            () => {
                executeElectionStatusChange(newStatus);
            }
        );
    }

    function executeElectionStatusChange(newStatus) {
        const formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('_method', 'PUT');
        formData.append('status', newStatus);
        formData.append('title', '{{ addslashes($election->title) }}');
        formData.append('description', '{{ addslashes($election->description ?? "") }}');
        formData.append('start_date', '{{ $election->start_date ? $election->start_date->format("Y-m-d\TH:i") : "" }}');
        formData.append('end_date', '{{ $election->end_date ? $election->end_date->format("Y-m-d\TH:i") : "" }}');
        
        fetch(`/committees/election-app/{{ $election->id }}`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.showToast(`Election marked as ${newStatus} successfully!`, 'success');
                setTimeout(() => window.location.reload(), 800);
            } else {
                window.showToast(data.message || 'Status update failed.', 'error');
            }
        })
        .catch(err => {
            console.error(err);
            window.showToast('Connection error.', 'error');
        });
    }

    function openEditElectionModal() {
        const election = {
            id: '{{ $election->id }}',
            title: '{{ addslashes($election->title) }}',
            description: '{{ addslashes($election->description ?? "") }}',
            status: '{{ $election->status }}',
            start_date: '{{ $election->start_date ? $election->start_date->format("Y-m-d\TH:i") : "" }}',
            end_date: '{{ $election->end_date ? $election->end_date->format("Y-m-d\TH:i") : "" }}'
        };
        
        const modal = document.getElementById('edit-election-modal');
        const content = document.getElementById('edit-election-modal-content');
        
        document.getElementById('edit_election_id').value = election.id;
        document.getElementById('edit_title').value = election.title;
        document.getElementById('edit_description').value = election.description;
        document.getElementById('edit_status').value = election.status;
        document.getElementById('edit_start_date').value = election.start_date;
        document.getElementById('edit_end_date').value = election.end_date;

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

    function submitEditElection(event) {
        event.preventDefault();
        const form = event.target;
        const submitBtn = form.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.textContent = 'Saving...';

        const formData = new FormData(form);

        fetch(`/committees/election-app/{{ $election->id }}`, {
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
                closeEditModal();
                setTimeout(() => window.location.reload(), 800);
            } else {
                window.showToast(res.message || 'Failed to update election.', 'error');
                submitBtn.disabled = false;
                submitBtn.textContent = 'Save Changes';
            }
        })
        .catch(err => {
            console.error(err);
            window.showToast('Server connection failed.', 'error');
            submitBtn.disabled = false;
            submitBtn.textContent = 'Save Changes';
        });
    }

    function triggerDeleteElectionFromManage() {
        const currentStatus = '{{ $election->status }}';
        if (currentStatus === 'active') {
            window.showToast('Cannot delete an ongoing election. Please pause or close it first.', 'error');
            return;
        }

        window.showConfirmModal(
            'Delete Election',
            'Are you sure you want to delete this election?',
            'This will permanently delete this election and all of its associated positions, candidates, and votes. This action cannot be undone.',
            () => {
                fetch(`/committees/election-app/{{ $election->id }}`, {
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
                        setTimeout(() => {
                            window.location.href = "{{ route('committees.election.index') }}";
                        }, 800);
                    } else {
                        window.showToast(res.message || 'Failed to delete election.', 'error');
                    }
                })
                .catch(err => {
                    console.error(err);
                    window.showToast('Server connection failed.', 'error');
                });
            }
        );
    }

    function copyVotingLink() {
        const link = "{{ route('elections.voter.setup', $election->id) }}";
        navigator.clipboard.writeText(link)
            .then(() => {
                window.showToast('Voting link copied to clipboard!', 'success');
            })
            .catch(err => {
                console.error('Failed to copy text: ', err);
                window.showToast('Failed to copy link.', 'error');
            });
    }

    function confirmCSVDownload() {
        window.showConfirmModal(
            'Export CSV Tally Report',
            'Are you sure you want to download the CSV report?',
            'This will compile the active election results, vote totals, and share percentages, and download a secure spreadsheet file to your device.',
            () => {
                window.location.href = "{{ route('committees.election.export', $election->id) }}";
            }
        );
    }

    function confirmVoterCSVDownload() {
        window.showConfirmModal(
            'Export Voter Registry CSV',
            'Are you sure you want to download the Voter Registry CSV report?',
            'This will compile the complete register of voters for this election, including names, emails, workplaces, positions, voting statuses, and ballot submission times, and download a secure spreadsheet file.',
            () => {
                window.location.href = "{{ route('committees.election.export_voters', $election->id) }}";
            }
        );
    }
</script>
@endsection
