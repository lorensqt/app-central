@extends('layouts.app')

@section('title', 'Cast Ballot')

@section('content')
<div class="space-y-6 sm:space-y-8 max-w-4xl mx-auto pb-36 sm:pb-32 px-1 sm:px-0">
    <!-- Header panel (Slightly more compact on mobile) -->
    <div class="relative overflow-hidden bg-white dark:bg-slate-900 rounded-3xl sm:rounded-[2rem] border border-slate-200/80 dark:border-slate-800/80 p-5 sm:p-8 md:p-10 shadow-sm text-center">
        <div class="absolute -top-24 -left-24 w-72 h-72 bg-purple-400/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -right-24 w-72 h-72 bg-indigo-400/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative space-y-2.5">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-purple-500/10 dark:bg-purple-400/10 text-[9px] sm:text-[10px] font-bold text-purple-600 dark:text-purple-400 uppercase tracking-widest">
                Official Secret Ballot
            </span>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight leading-tight">
                {{ $election->title }}
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 max-w-xl mx-auto leading-relaxed">
                Tap on candidates to select them. The ballot automatically limits your choices based on vacancies for each role.
            </p>
        </div>
    </div>

    <!-- Ballot Positions Workspace -->
    <form id="ballot-form" onsubmit="submitBallot(event)" class="space-y-6 sm:space-y-8">
        @csrf
        
        @foreach($positions as $position)
            <div class="bg-white dark:bg-slate-900 rounded-3xl sm:rounded-[2rem] border border-slate-200/60 dark:border-slate-800/80 p-5 sm:p-8 shadow-sm space-y-4 sm:space-y-6">
                <!-- Position Title & Max Selections Instruction -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pb-3 sm:pb-4 border-b border-slate-100 dark:border-slate-800/60">
                    <div>
                        <h2 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white leading-tight">
                            {{ $position->name }}
                        </h2>
                        <p class="text-[11px] sm:text-xs text-slate-400 mt-0.5 leading-snug">
                            Choose candidates for this role.
                        </p>
                    </div>
                    
                    <span class="inline-flex items-center shrink-0 px-2.5 py-1 rounded-full text-[9px] sm:text-[10px] font-bold uppercase tracking-wider bg-purple-500/10 text-purple-600 dark:text-purple-400 self-start sm:self-center">
                        Select up to {{ $position->max_votes }} {{ Str::plural('candidate', $position->max_votes) }}
                    </span>
                </div>

                <!-- Candidates Selection Grid (Grid-cols-2 on Mobile saves massive space and scrolling!) -->
                <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4">
                    @forelse($position->candidates as $candidate)
                        <div onclick="toggleCandidate({{ $position->id }}, {{ $candidate->id }}, {{ $position->max_votes }}, this)"
                            class="candidate-card-btn relative bg-slate-50/50 dark:bg-slate-950/40 border border-slate-100 dark:border-slate-800/80 rounded-2xl p-3.5 sm:p-5 flex flex-col items-center text-center cursor-pointer hover:shadow-sm transition-all duration-200 select-none group border-transparent active:scale-[0.97]"
                            data-position-id="{{ $position->id }}" data-candidate-id="{{ $candidate->id }}">
                            
                            <!-- Selection Check Badge -->
                            <span class="check-badge absolute top-2.5 right-2.5 sm:top-3.5 sm:right-3.5 inline-flex items-center justify-center w-5 h-5 sm:w-5.5 sm:h-5.5 rounded-full bg-slate-200/50 dark:bg-slate-800/60 text-slate-400 group-hover:text-slate-500 transition-colors">
                                <svg class="w-3 sm:w-3.5 h-3 sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                </svg>
                            </span>

                            <!-- Profile Picture Container -->
                            <div class="relative mb-3 sm:mb-4 group/avatar shrink-0">
                                @if($candidate->avatar_path)
                                    <div class="relative w-14 h-14 sm:w-16 sm:h-16 rounded-2xl overflow-hidden border border-slate-200/80 dark:border-slate-700/80 shadow-xs">
                                        <img src="{{ $candidate->avatar_path }}" alt="{{ $candidate->name }}" class="w-full h-full object-cover">
                                        <!-- Hover Inspect Button Overlay -->
                                        <button type="button"
                                            onclick="event.stopPropagation(); viewBallotCandidateImage('{{ addslashes($candidate->avatar_path) }}', '{{ addslashes($candidate->name) }}', '{{ addslashes($candidate->party_affiliation ?? 'Independent') }}', '{{ addslashes($position->name) }}')"
                                            class="absolute inset-0 bg-slate-950/40 opacity-0 group-hover/avatar:opacity-100 flex items-center justify-center transition-opacity duration-200 cursor-zoom-in"
                                            title="Click to view photo">
                                            <svg class="w-4 h-4 text-white drop-shadow-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m3-3H7"/>
                                            </svg>
                                        </button>
                                    </div>
                                    <!-- Zoom Icon Badge (Mobile & Desktop Accessible) -->
                                    <button type="button"
                                        onclick="event.stopPropagation(); viewBallotCandidateImage('{{ addslashes($candidate->avatar_path) }}', '{{ addslashes($candidate->name) }}', '{{ addslashes($candidate->party_affiliation ?? 'Independent') }}', '{{ addslashes($position->name) }}')"
                                        class="absolute -bottom-1.5 -right-1.5 p-1 bg-white dark:bg-slate-800 rounded-lg shadow-xs border border-slate-200 dark:border-slate-700 text-slate-500 hover:text-purple-600 dark:text-slate-400 dark:hover:text-purple-400 transition"
                                        title="View Full Photo">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m3-3H7"/>
                                        </svg>
                                    </button>
                                @else
                                    <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl bg-purple-50 dark:bg-purple-950/40 text-purple-600 dark:text-purple-400 font-extrabold flex items-center justify-center shrink-0 border border-purple-200/60 dark:border-purple-800/50 text-base sm:text-lg uppercase shadow-xs">
                                        {{ substr($candidate->name, 0, 2) }}
                                    </div>
                                @endif
                            </div>

                            <!-- Name & Party -->
                            <h3 class="font-bold text-xs sm:text-sm text-slate-900 dark:text-white truncate max-w-full leading-snug">
                                {{ $candidate->name }}
                            </h3>
                            <p class="text-[10px] sm:text-[11px] text-slate-400 mt-1 truncate max-w-full font-medium leading-none">
                                {{ $candidate->party_affiliation ?? 'Independent' }}
                            </p>
                        </div>
                    @empty
                        <div class="col-span-full py-4 text-center text-xs text-slate-400 italic">
                            No candidates are running for this position.
                        </div>
                    @endforelse
                </div>
            </div>
        @endforeach
    </form>

    <!-- NATIVE-FEEL STICKY BOTTOM SUBMISSION DRAWER (Sticks flat on mobile bottom with zero gaps, floats on tablet/desktop!) -->
    <div class="fixed bottom-0 left-0 right-0 sm:bottom-6 sm:left-6 sm:right-6 md:left-auto md:right-auto md:w-full md:max-w-4xl z-40 bg-white/95 dark:bg-slate-900/95 backdrop-blur-md border-t sm:border border-slate-200/80 dark:border-slate-800/80 p-4 sm:p-5 shadow-2xl flex flex-col sm:flex-row items-center justify-between gap-3.5 sm:gap-4 rounded-none sm:rounded-2xl transition-all duration-300">
        <div class="text-center sm:text-left w-full sm:w-auto">
            <h4 class="font-bold text-sm text-slate-900 dark:text-white tracking-tight leading-tight">Cast Your Secret Ballot</h4>
            <p class="text-[11px] sm:text-xs text-slate-500 dark:text-slate-400 mt-1 leading-none">
                Completed <span class="font-bold text-purple-600 dark:text-purple-400" id="progress-indicator">0</span> of <span class="font-bold">{{ $positions->count() }}</span> positions.
            </p>
        </div>

        <button type="button" onclick="document.getElementById('ballot-form').requestSubmit()" id="cast-vote-btn"
            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-purple-600 hover:bg-purple-700 active:scale-[0.98] text-white font-semibold text-xs sm:text-sm px-6 py-3.5 sm:py-3 rounded-xl sm:rounded-xl shadow-md transition">
            Cast Secure Ballot
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </button>
    </div>

    <!-- Premium Full-Screen Sealing & Encryption Overlay -->
    <div id="loading-overlay" class="fixed inset-0 bg-slate-900/60 dark:bg-slate-950/80 backdrop-blur-md z-50 hidden flex-col items-center justify-center p-6 transition-all duration-300">
        <div class="max-w-xs w-full text-center space-y-6">
            <!-- Loading Animation Spinner with Sealing Ring -->
            <div class="relative w-20 h-20 mx-auto">
                <!-- Sealing Ring Outer -->
                <div class="absolute inset-0 rounded-full border-4 border-purple-500/20 border-t-purple-600 dark:border-purple-400/20 dark:border-t-purple-400 animate-spin"></div>
                <!-- Shimmer Inner Lock -->
                <div class="absolute inset-3 rounded-full bg-purple-500/10 dark:bg-purple-400/10 flex items-center justify-center text-purple-600 dark:text-purple-400">
                    <svg class="w-6 h-6 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
            </div>

            <!-- Encryption & Audit Status Messages -->
            <div class="space-y-2">
                <h3 class="font-bold text-base text-white tracking-tight">Securing Your Ballot</h3>
                <p id="loading-status" class="text-xs text-slate-300 dark:text-slate-400 font-medium animate-pulse">Encrypting selection keys...</p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // State of selections: position_id => Set(candidate_ids)
    const selections = {};
    const totalPositions = {{ $positions->count() }};

    // Initialize state
    @foreach($positions as $position)
        selections[{{ $position->id }}] = new Set();
    @endforeach

    function toggleCandidate(positionId, candidateId, maxVotes, cardElement) {
        const set = selections[positionId];
        const checkBadge = cardElement.querySelector('.check-badge');

        if (set.has(candidateId)) {
            // Deselect
            set.delete(candidateId);
            cardElement.classList.remove('ring-4', 'ring-purple-500/20', 'border-purple-600', 'dark:border-purple-500', 'bg-purple-50/15', 'dark:bg-purple-950/10');
            cardElement.classList.add('border-transparent');
            checkBadge.className = 'check-badge absolute top-2.5 right-2.5 sm:top-3.5 sm:right-3.5 inline-flex items-center justify-center w-5 h-5 sm:w-5.5 sm:h-5.5 rounded-full bg-slate-200/50 dark:bg-slate-800/60 text-slate-400 group-hover:text-slate-500 transition-colors';
            checkBadge.innerHTML = `<svg class="w-3 sm:w-3.5 h-3 sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>`;
        } else {
            // Check limits
            if (set.size >= maxVotes) {
                if (maxVotes === 1) {
                    // Single select: auto-deselect previous selection in this position
                    const previousId = Array.from(set)[0];
                    const prevCard = document.querySelector(`.candidate-card-btn[data-position-id="${positionId}"][data-candidate-id="${previousId}"]`);
                    if (prevCard) {
                        set.delete(previousId);
                        prevCard.classList.remove('ring-4', 'ring-purple-500/20', 'border-purple-600', 'dark:border-purple-500', 'bg-purple-50/15', 'dark:bg-purple-950/10');
                        prevCard.classList.add('border-transparent');
                        const prevBadge = prevCard.querySelector('.check-badge');
                        prevBadge.className = 'check-badge absolute top-2.5 right-2.5 sm:top-3.5 sm:right-3.5 inline-flex items-center justify-center w-5 h-5 sm:w-5.5 sm:h-5.5 rounded-full bg-slate-200/50 dark:bg-slate-800/60 text-slate-400 group-hover:text-slate-500 transition-colors';
                        prevBadge.innerHTML = `<svg class="w-3 sm:w-3.5 h-3 sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>`;
                    }
                } else {
                    // Multi-select limit exceeded
                    if (window.showToast) {
                        window.showToast(`Voting limit reached! You can select a maximum of ${maxVotes} candidates for this position.`, 'error');
                    }
                    return;
                }
            }

            // Select
            set.add(candidateId);
            cardElement.classList.remove('border-transparent');
            cardElement.classList.add('ring-4', 'ring-purple-500/20', 'border-purple-600', 'dark:border-purple-500', 'bg-purple-50/15', 'dark:bg-purple-950/10');
            checkBadge.className = 'check-badge absolute top-2.5 right-2.5 sm:top-3.5 sm:right-3.5 inline-flex items-center justify-center w-5 h-5 sm:w-5.5 sm:h-5.5 rounded-full bg-purple-600 text-white shadow-sm transition-all duration-200';
            checkBadge.innerHTML = `<svg class="w-3 sm:w-3.5 h-3 sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>`;
        }

        updateProgress();
    }

    function updateProgress() {
        let completed = 0;
        for (const posId in selections) {
            if (selections[posId].size > 0) {
                completed++;
            }
        }
        document.getElementById('progress-indicator').textContent = completed;
    }

    function submitBallot(event) {
        event.preventDefault();

        // Check if voter has chosen anything at all
        let totalSelected = 0;
        for (const posId in selections) {
            totalSelected += selections[posId].size;
        }

        if (totalSelected === 0) {
            if (window.showToast) {
                window.showToast('Please select at least one candidate before casting your ballot.', 'error');
            }
            return;
        }

        window.showConfirmModal(
            'Cast Secret Ballot',
            'Are you sure you want to cast your ballot?',
            'Once cast, your selections are sealed and cannot be modified or re-submitted. Your vote remains completely secret and anonymous.',
            () => {
                const btn = document.getElementById('cast-vote-btn');
                const overlay = document.getElementById('loading-overlay');
                const statusEl = document.getElementById('loading-status');
                
                // Show high-security full-screen loading overlay
                overlay.classList.remove('hidden');
                overlay.classList.add('flex');
                btn.disabled = true;
                btn.textContent = 'Submitting Ballot...';

                // Status message cycler
                const statusMessages = [
                    'Encrypting selection keys...',
                    'Sealing secret audit trail...',
                    'Verifying registry consensus...',
                    'Integrating cryptographic signatures...',
                    'Writing transaction to ledger...'
                ];
                let msgIndex = 0;
                const statusInterval = setInterval(() => {
                    msgIndex = (msgIndex + 1) % statusMessages.length;
                    statusEl.textContent = statusMessages[msgIndex];
                }, 1000);

                // Format selections payload
                const votesPayload = {};
                for (const posId in selections) {
                    votesPayload[posId] = Array.from(selections[posId]);
                }

                fetch("{{ route('elections.ballot.submit', $election->id) }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ votes: votesPayload })
                })
                .then(response => response.json())
                .then(res => {
                    clearInterval(statusInterval);
                    if (res.success) {
                        statusEl.textContent = 'Ballot successfully cast!';
                        setTimeout(() => {
                            window.location.href = res.redirect;
                        }, 500);
                    } else {
                        overlay.classList.add('hidden');
                        overlay.classList.remove('flex');
                        window.showToast(res.message || 'Submitting ballot failed.', 'error');
                        btn.disabled = false;
                        btn.textContent = 'Cast Secure Ballot';
                    }
                })
                .catch(err => {
                    clearInterval(statusInterval);
                    overlay.classList.add('hidden');
                    overlay.classList.remove('flex');
                    console.error(err);
                    window.showToast('Server connection failed.', 'error');
                    btn.disabled = false;
                    btn.textContent = 'Cast Secure Ballot';
                });
            }
        );
    }

    // --- BALLOT CANDIDATE PHOTO VIEWER MODAL LOGIC ---
    function viewBallotCandidateImage(url, name, party, positionName) {
        const modal = document.getElementById('ballot-candidate-photo-modal');
        const content = document.getElementById('ballot-candidate-photo-content');
        const img = document.getElementById('ballot-candidate-viewer-img');
        const nameEl = document.getElementById('ballot-candidate-viewer-name');
        const partyEl = document.getElementById('ballot-candidate-viewer-party');
        const posEl = document.getElementById('ballot-candidate-viewer-position');

        if (!modal || !img) return;

        img.src = url;
        img.alt = name ? name + ' photo' : 'Candidate photo';
        if (nameEl) nameEl.textContent = name || 'Candidate Photo';
        if (partyEl) partyEl.textContent = party || 'Independent';
        if (posEl) posEl.textContent = positionName || 'Candidate';

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        requestAnimationFrame(() => {
            modal.classList.remove('opacity-0');
            modal.classList.add('opacity-100');
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        });
    }

    function closeBallotCandidateImage() {
        const modal = document.getElementById('ballot-candidate-photo-modal');
        const content = document.getElementById('ballot-candidate-photo-content');
        if (!modal) return;

        if (content) {
            content.classList.add('scale-95', 'opacity-0');
        }
        modal.classList.add('opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            const img = document.getElementById('ballot-candidate-viewer-img');
            if (img) img.src = '';
        }, 300);
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modal = document.getElementById('ballot-candidate-photo-modal');
            if (modal && !modal.classList.contains('hidden')) {
                closeBallotCandidateImage();
            }
        }
    });
</script>

<!-- BALLOT CANDIDATE PHOTO PREVIEW MODAL -->
<div id="ballot-candidate-photo-modal"
    onclick="if (event.target === this) closeBallotCandidateImage()"
    class="fixed inset-0 bg-slate-900/80 dark:bg-slate-950/90 backdrop-blur-md z-50 hidden items-center justify-center p-4 sm:p-6 transition-all duration-300 opacity-0"
    role="dialog" aria-modal="true" aria-labelledby="ballot-candidate-viewer-name">
    <div class="relative bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-200/80 dark:border-slate-800 max-w-lg sm:max-w-xl w-full shadow-2xl overflow-hidden transition-all duration-300 transform scale-95 opacity-0 flex flex-col max-h-[90vh]"
        id="ballot-candidate-photo-content">

        <!-- Spacious Modal Header -->
        <div class="px-6 sm:px-8 py-5 sm:py-6 border-b border-slate-100 dark:border-slate-800/80 flex items-start justify-between gap-4 bg-slate-50/60 dark:bg-slate-950/40 shrink-0">
            <div class="min-w-0 pr-2 space-y-1">
                <span class="text-[10px] sm:text-[11px] font-bold text-purple-600 dark:text-purple-400 uppercase tracking-widest block">
                    Candidate Profile
                </span>
                <h3 id="ballot-candidate-viewer-name" class="font-extrabold text-slate-900 dark:text-white text-lg sm:text-xl truncate leading-snug">
                    Candidate Photo
                </h3>
                <div class="flex items-center gap-2 pt-1 flex-wrap">
                    <span id="ballot-candidate-viewer-position" class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1 rounded-lg bg-purple-500/10 text-purple-600 dark:text-purple-400 border border-purple-500/20 uppercase tracking-wide">
                        Position
                    </span>
                    <span id="ballot-candidate-viewer-party" class="inline-flex items-center gap-1.5 text-xs font-medium px-3 py-1 rounded-lg bg-slate-100 dark:bg-slate-800/90 text-slate-700 dark:text-slate-300 border border-slate-200/80 dark:border-slate-700/80">
                        Party Affiliation
                    </span>
                </div>
            </div>
            
            <button type="button" onclick="closeBallotCandidateImage()"
                class="p-2.5 text-slate-400 hover:text-slate-700 dark:text-slate-400 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800/80 rounded-xl transition shrink-0 -mr-1"
                title="Close preview">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Image Viewing Area -->
        <div class="p-5 sm:p-8 bg-slate-100/60 dark:bg-slate-950/70 flex items-center justify-center overflow-hidden flex-1 min-h-[240px]">
            <img id="ballot-candidate-viewer-img" src="" alt="Candidate Portrait"
                class="max-h-[58vh] max-w-full w-auto h-auto object-contain rounded-2xl shadow-lg border border-slate-200/60 dark:border-slate-800 bg-white dark:bg-slate-900 transition-all duration-300">
        </div>

        <!-- Modal Footer -->
        <div class="px-6 sm:px-8 py-4 border-t border-slate-100 dark:border-slate-800/80 bg-white dark:bg-slate-900 flex items-center justify-between shrink-0">
            <span class="text-xs text-slate-400 dark:text-slate-500 font-medium">Candidate Profile Picture</span>
            <button type="button" onclick="closeBallotCandidateImage()"
                class="px-5 py-2.5 text-xs font-semibold rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 transition">
                Close
            </button>
        </div>
    </div>
</div>
@endsection
