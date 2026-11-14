@extends('layouts.voter')

@section('title', 'Cast Ballot')

@section('content')
<div class="space-y-4 sm:space-y-8 max-w-4xl mx-auto pb-28 sm:pb-32 px-0 sm:px-0">
    <!-- Header panel (Mobile-First Compact) -->
    <div class="relative overflow-hidden bg-white dark:bg-slate-900 rounded-2xl sm:rounded-[2rem] border border-slate-200/80 dark:border-slate-800/80 p-4 sm:p-8 md:p-10 shadow-sm text-center">
        <div class="absolute -top-24 -left-24 w-72 h-72 bg-purple-400/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -right-24 w-72 h-72 bg-indigo-400/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative space-y-1.5 sm:space-y-2.5">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-purple-500/10 dark:bg-purple-400/10 text-[9px] sm:text-[10px] font-bold text-purple-600 dark:text-purple-400 uppercase tracking-widest">
                Official Secret Ballot
            </span>
            <h1 class="text-xl xs:text-2xl sm:text-3xl font-black text-slate-900 dark:text-white tracking-tight leading-tight">
                {{ $election->title }}
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 max-w-xl mx-auto leading-relaxed">
                Tap on candidates to select them. The ballot automatically limits your choices based on vacancies for each role.
            </p>
        </div>
    </div>

    <!-- Ballot Positions Workspace -->
    <form id="ballot-form" onsubmit="submitBallot(event)" class="space-y-4 sm:space-y-8">
        @csrf
        
        @foreach($positions as $position)
            <div class="bg-white dark:bg-slate-900 rounded-2xl sm:rounded-[2rem] border border-slate-200/60 dark:border-slate-800/80 p-3.5 sm:p-7 md:p-8 shadow-sm space-y-3.5 sm:space-y-6">
                <!-- Position Title & Max Selections Instruction -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 sm:gap-3 pb-3 border-b border-slate-100 dark:border-slate-800/60">
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

                <!-- Candidates Selection Grid (2 Columns on Phone with Scaled Touch Targets) -->
                <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 gap-2.5 sm:gap-4">
                    @forelse($position->candidates as $candidate)
                        <div onclick="toggleCandidate({{ $position->id }}, {{ $candidate->id }}, {{ $position->max_votes }}, this)"
                            class="candidate-card-btn relative bg-slate-50/50 dark:bg-slate-950/40 border border-slate-100 dark:border-slate-800/80 rounded-xl sm:rounded-2xl p-2.5 sm:p-5 flex flex-col items-center text-center cursor-pointer hover:shadow-sm transition-all duration-200 select-none group border-transparent active:scale-[0.97]"
                            data-position-id="{{ $position->id }}"
                            data-candidate-id="{{ $candidate->id }}"
                            data-avatar="{{ $candidate->avatar_path ?? '' }}"
                            data-name="{{ $candidate->name }}"
                            data-party="{{ $candidate->party_affiliation ?? 'Independent' }}"
                            data-position="{{ $position->name }}"
                            data-background="{{ $candidate->background ?? '' }}">
                            
                            <!-- Selection Check Badge -->
                            <span class="check-badge absolute top-2 right-2 sm:top-3.5 sm:right-3.5 inline-flex items-center justify-center w-5 h-5 sm:w-5.5 sm:h-5.5 rounded-full bg-slate-200/50 dark:bg-slate-800/60 text-slate-400 group-hover:text-slate-500 transition-colors">
                                <svg class="w-3 sm:w-3.5 h-3 sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                </svg>
                            </span>

                            <!-- Profile Picture Container -->
                            <div class="relative mb-1.5 sm:mb-3 group/avatar shrink-0">
                                @if($candidate->avatar_path)
                                    <div class="relative w-12 h-12 sm:w-16 sm:h-16 rounded-xl sm:rounded-2xl overflow-hidden border border-slate-200/80 dark:border-slate-700/80 shadow-xs">
                                        <img src="{{ $candidate->avatar_path }}" alt="{{ $candidate->name }}" class="w-full h-full object-cover">
                                        <!-- Hover Inspect Button Overlay -->
                                        <button type="button"
                                            onclick="event.stopPropagation(); openCandidateModalFromCard(this.closest('.candidate-card-btn'))"
                                            class="absolute inset-0 bg-slate-950/40 opacity-0 group-hover/avatar:opacity-100 flex items-center justify-center transition-opacity duration-200 cursor-zoom-in"
                                            title="Click to view profile & background">
                                            <svg class="w-4 h-4 text-white drop-shadow-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </button>
                                    </div>
                                    <!-- Zoom Icon Badge (Mobile & Desktop Accessible) -->
                                    <button type="button"
                                        onclick="event.stopPropagation(); openCandidateModalFromCard(this.closest('.candidate-card-btn'))"
                                        class="absolute -bottom-1 -right-1 sm:-bottom-1.5 sm:-right-1.5 p-1 bg-white dark:bg-slate-800 rounded-md sm:rounded-lg shadow-xs border border-slate-200 dark:border-slate-700 text-slate-500 hover:text-purple-600 dark:text-slate-400 dark:hover:text-purple-400 transition"
                                        title="View Profile & Background">
                                        <svg class="w-2.5 h-2.5 sm:w-3 sm:h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </button>
                                @else
                                    <div class="relative w-12 h-12 sm:w-16 sm:h-16 rounded-xl sm:rounded-2xl bg-purple-50 dark:bg-purple-950/40 text-purple-600 dark:text-purple-400 font-extrabold flex items-center justify-center shrink-0 border border-purple-200/60 dark:border-purple-800/50 text-xs sm:text-lg uppercase shadow-xs">
                                        {{ substr($candidate->name, 0, 2) }}
                                        <button type="button"
                                            onclick="event.stopPropagation(); openCandidateModalFromCard(this.closest('.candidate-card-btn'))"
                                            class="absolute -bottom-1 -right-1 sm:-bottom-1.5 sm:-right-1.5 p-1 bg-white dark:bg-slate-800 rounded-md sm:rounded-lg shadow-xs border border-slate-200 dark:border-slate-700 text-slate-500 hover:text-purple-600 dark:text-slate-400 dark:hover:text-purple-400 transition"
                                            title="View Profile & Background">
                                            <svg class="w-2.5 h-2.5 sm:w-3 sm:h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </button>
                                    </div>
                                @endif
                            </div>

                            <!-- Name & Party -->
                            <h3 class="font-bold text-[11px] sm:text-sm text-slate-900 dark:text-white truncate max-w-full leading-tight">
                                {{ $candidate->name }}
                            </h3>
                            <p class="text-[9px] sm:text-[11px] text-slate-400 mt-0.5 truncate max-w-full font-medium leading-none">
                                {{ $candidate->party_affiliation ?? 'Independent' }}
                            </p>

                            <!-- Profile & Poster Quick Pill -->
                            <button type="button"
                                onclick="event.stopPropagation(); openCandidateModalFromCard(this.closest('.candidate-card-btn'))"
                                class="mt-1.5 sm:mt-2 inline-flex items-center gap-1.5 text-[9px] sm:text-[10px] font-bold text-purple-600 dark:text-purple-400 hover:text-purple-700 dark:hover:text-purple-300 py-1 px-2.5 rounded-lg bg-purple-500/10 hover:bg-purple-500/15 transition border border-purple-500/20 active:scale-95 shadow-2xs">
                                <i class="fa-solid fa-image text-[8px] sm:text-[9px]"></i>
                                <span>Poster</span>
                                <i class="fa-solid fa-chevron-right text-[7px] opacity-70"></i>
                            </button>
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

    <!-- NATIVE-FEEL STICKY BOTTOM SUBMISSION DRAWER -->
    <div class="fixed bottom-0 inset-x-0 sm:bottom-6 sm:inset-x-6 md:left-1/2 md:-translate-x-1/2 md:w-full md:max-w-4xl z-40 bg-white/95 dark:bg-slate-900/95 backdrop-blur-xl border-t sm:border border-slate-200/80 dark:border-slate-800/80 px-4 py-3 sm:px-6 sm:py-4 shadow-2xl flex items-center justify-between gap-3 rounded-none sm:rounded-2xl transition-all duration-300 safe-bottom">
        <div class="text-left min-w-0">
            <div class="flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-purple-600 animate-pulse shrink-0"></span>
                <h4 class="font-extrabold text-xs sm:text-sm text-slate-900 dark:text-white truncate">Ballot Progress</h4>
            </div>
            <p class="text-[10px] sm:text-xs text-slate-500 dark:text-slate-400 mt-0.5 truncate">
                <span class="font-bold text-purple-600 dark:text-purple-400" id="progress-indicator">0</span> of <span class="font-bold">{{ $positions->count() }}</span> positions chosen
            </p>
        </div>

        <button type="button" onclick="document.getElementById('ballot-form').requestSubmit()" id="cast-vote-btn"
            class="shrink-0 inline-flex items-center justify-center gap-2 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 active:scale-[0.98] text-white font-bold text-xs sm:text-sm px-4 sm:px-6 py-2.5 sm:py-3 rounded-xl shadow-md shadow-purple-500/25 transition">
            <span>Cast Ballot</span>
            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </button>
    </div>

    <!-- Premium Full-Screen Sealing & Encryption Overlay -->
    <div id="loading-overlay" class="fixed inset-0 bg-slate-900/60 dark:bg-slate-950/80 backdrop-blur-md z-50 hidden flex-col items-center justify-center p-6 transition-all duration-300">
        <div class="max-w-xs w-full text-center space-y-6">
            <!-- Loading Animation Spinner with Sealing Ring -->
            <div class="relative w-20 h-20 mx-auto">
                <div class="absolute inset-0 rounded-full border-4 border-purple-500/20 border-t-purple-600 dark:border-purple-400/20 dark:border-t-purple-400 animate-spin"></div>
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
            checkBadge.className = 'check-badge absolute top-2 right-2 sm:top-3.5 sm:right-3.5 inline-flex items-center justify-center w-5 h-5 sm:w-5.5 sm:h-5.5 rounded-full bg-slate-200/50 dark:bg-slate-800/60 text-slate-400 group-hover:text-slate-500 transition-colors';
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
                        prevBadge.className = 'check-badge absolute top-2 right-2 sm:top-3.5 sm:right-3.5 inline-flex items-center justify-center w-5 h-5 sm:w-5.5 sm:h-5.5 rounded-full bg-slate-200/50 dark:bg-slate-800/60 text-slate-400 group-hover:text-slate-500 transition-colors';
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
            checkBadge.className = 'check-badge absolute top-2 right-2 sm:top-3.5 sm:right-3.5 inline-flex items-center justify-center w-5 h-5 sm:w-5.5 sm:h-5.5 rounded-full bg-purple-600 text-white shadow-sm transition-all duration-200';
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
                btn.textContent = 'Submitting...';

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
                        btn.textContent = 'Cast Ballot';
                    }
                })
                .catch(err => {
                    clearInterval(statusInterval);
                    overlay.classList.add('hidden');
                    overlay.classList.remove('flex');
                    console.error(err);
                    window.showToast('Server connection failed.', 'error');
                    btn.disabled = false;
                    btn.textContent = 'Cast Ballot';
                });
            }
        );
    }

    // --- BALLOT CANDIDATE PROFILE & POSTER MODAL LOGIC ---
    function openCandidateModalFromCard(card) {
        if (!card) return;
        const url = card.dataset.avatar || '';
        const name = card.dataset.name || '';
        const party = card.dataset.party || '';
        const position = card.dataset.position || '';
        const background = card.dataset.background || '';
        viewBallotCandidateProfile(url, name, party, position, background);
    }

    function isPosterImageUrl(str) {
        if (!str || typeof str !== 'string') return false;
        const trimmed = str.trim();
        if (trimmed.startsWith('data:image/')) return true;
        if (trimmed.startsWith('http://') || trimmed.startsWith('https://') || trimmed.startsWith('/')) {
            return /\.(jpeg|jpg|png|gif|webp|svg)(\?.*)?$/i.test(trimmed) ||
                   trimmed.includes('/candidates') ||
                   trimmed.includes('/candidate-posters') ||
                   trimmed.includes('s3') ||
                   trimmed.includes('storage');
        }
        return false;
    }

    let currentCandidateFaceUrl = '';
    let currentCandidateFaceName = '';

    function openFaceLightbox() {
        if (!currentCandidateFaceUrl) return;
        const lightbox = document.getElementById('ballot-face-lightbox');
        const content = document.getElementById('ballot-face-lightbox-content');
        const img = document.getElementById('ballot-face-lightbox-img');
        const nameEl = document.getElementById('ballot-face-lightbox-name');
        if (!lightbox || !img) return;

        img.src = currentCandidateFaceUrl;
        img.alt = (currentCandidateFaceName || 'Candidate') + ' Face Closeup';
        if (nameEl) nameEl.textContent = currentCandidateFaceName || 'Candidate Portrait';

        lightbox.classList.remove('hidden');
        lightbox.classList.add('flex');
        requestAnimationFrame(() => {
            lightbox.classList.remove('opacity-0');
            lightbox.classList.add('opacity-100');
            if (content) {
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }
        });
    }

    function closeFaceLightbox() {
        const lightbox = document.getElementById('ballot-face-lightbox');
        const content = document.getElementById('ballot-face-lightbox-content');
        if (!lightbox) return;

        if (content) {
            content.classList.add('scale-95', 'opacity-0');
        }
        lightbox.classList.add('opacity-0');
        setTimeout(() => {
            lightbox.classList.add('hidden');
            lightbox.classList.remove('flex');
            const img = document.getElementById('ballot-face-lightbox-img');
            if (img) img.src = '';
        }, 250);
    }

    function viewBallotCandidateProfile(url, name, party, positionName, background) {
        const modal = document.getElementById('ballot-candidate-photo-modal');
        const content = document.getElementById('ballot-candidate-photo-content');
        const img = document.getElementById('ballot-candidate-viewer-img');
        const initialsEl = document.getElementById('ballot-candidate-viewer-initials');
        const nameEl = document.getElementById('ballot-candidate-viewer-name');
        const partyEl = document.getElementById('ballot-candidate-viewer-party');
        const posEl = document.getElementById('ballot-candidate-viewer-position');
        const faceBtn = document.getElementById('ballot-candidate-face-btn');
        
        const posterImg = document.getElementById('ballot-candidate-viewer-poster');
        const posterLink = document.getElementById('ballot-poster-link');
        const posterImgLink = document.getElementById('ballot-poster-img-link');
        const posterEmpty = document.getElementById('ballot-poster-empty');
        const bgText = document.getElementById('ballot-candidate-viewer-background');

        if (!modal) return;

        currentCandidateFaceUrl = (url && url.trim() !== '') ? url.trim() : '';
        currentCandidateFaceName = name || '';

        if (nameEl) nameEl.textContent = name || 'Candidate Profile';
        if (partyEl) {
            if (party && party !== 'Independent') {
                partyEl.textContent = party;
                partyEl.classList.remove('hidden');
            } else {
                partyEl.classList.add('hidden');
            }
        }
        if (posEl) posEl.textContent = positionName || 'Candidate';

        if (currentCandidateFaceUrl) {
            if (img) {
                img.src = currentCandidateFaceUrl;
                img.alt = (name || 'Candidate') + ' Portrait';
                img.classList.remove('hidden');
            }
            if (initialsEl) initialsEl.classList.add('hidden');
            if (faceBtn) {
                faceBtn.classList.remove('cursor-default');
                faceBtn.classList.add('cursor-zoom-in');
                faceBtn.setAttribute('title', 'Click to view candidate face closeup (stays on this page)');
            }
        } else {
            if (img) {
                img.src = '';
                img.classList.add('hidden');
            }
            if (initialsEl) {
                initialsEl.textContent = name ? name.substring(0, 2).toUpperCase() : '??';
                initialsEl.classList.remove('hidden');
            }
            if (faceBtn) {
                faceBtn.classList.remove('cursor-zoom-in');
                faceBtn.classList.add('cursor-default');
                faceBtn.setAttribute('title', 'No portrait image uploaded for this candidate');
            }
        }

        const trimmedBg = (background || '').trim();

        if (trimmedBg && isPosterImageUrl(trimmedBg)) {
            if (posterImg) {
                posterImg.src = trimmedBg;
                posterImg.classList.remove('hidden');
            }
            if (posterLink) {
                posterLink.href = trimmedBg;
                posterLink.classList.remove('hidden');
            }
            if (posterImgLink) {
                posterImgLink.href = trimmedBg;
            }
            if (posterEmpty) posterEmpty.classList.add('hidden');
            if (bgText) bgText.classList.add('hidden');
        } else if (trimmedBg && !isPosterImageUrl(trimmedBg) && trimmedBg !== 'No background details provided.') {
            if (posterImg) {
                posterImg.src = '';
                posterImg.classList.add('hidden');
            }
            if (posterLink) posterLink.classList.add('hidden');
            if (posterEmpty) posterEmpty.classList.add('hidden');
            if (bgText) {
                bgText.textContent = trimmedBg;
                bgText.classList.remove('hidden');
            }
        } else {
            if (posterImg) {
                posterImg.src = '';
                posterImg.classList.add('hidden');
            }
            if (posterLink) posterLink.classList.add('hidden');
            if (posterEmpty) posterEmpty.classList.remove('hidden');
            if (bgText) bgText.classList.add('hidden');
        }

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        requestAnimationFrame(() => {
            modal.classList.remove('opacity-0');
            modal.classList.add('opacity-100');
            if (content) {
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }
        });
    }

    // Alias for backwards compatibility
    const viewBallotCandidateImage = viewBallotCandidateProfile;

    function closeBallotCandidateImage() {
        closeFaceLightbox();

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
            const posterImg = document.getElementById('ballot-candidate-viewer-poster');
            if (img) img.src = '';
            if (posterImg) posterImg.src = '';
            currentCandidateFaceUrl = '';
            currentCandidateFaceName = '';
        }, 300);
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const faceLightbox = document.getElementById('ballot-face-lightbox');
            if (faceLightbox && !faceLightbox.classList.contains('hidden')) {
                closeFaceLightbox();
                return;
            }
            const modal = document.getElementById('ballot-candidate-photo-modal');
            if (modal && !modal.classList.contains('hidden')) {
                closeBallotCandidateImage();
            }
        }
    });
</script>

<!-- BALLOT CANDIDATE PROFILE & POSTER MODAL (2-Column Modern Revamped Layout) -->
<div id="ballot-candidate-photo-modal"
    onclick="if (event.target === this) closeBallotCandidateImage()"
    class="fixed inset-0 bg-slate-900/80 dark:bg-slate-950/90 backdrop-blur-md z-50 hidden items-center justify-center p-3 sm:p-5 md:p-6 transition-all duration-300 opacity-0"
    role="dialog" aria-modal="true" aria-labelledby="ballot-candidate-viewer-name">
    <div class="relative bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 max-w-lg sm:max-w-2xl md:max-w-4xl lg:max-w-5xl w-full shadow-2xl overflow-hidden transition-all duration-300 transform scale-95 opacity-0 flex flex-col max-h-[92vh]"
        id="ballot-candidate-photo-content">

        <!-- Modal Header Bar -->
        <div class="px-5 py-3.5 sm:px-6 sm:py-4 border-b border-slate-100 dark:border-slate-800/80 flex items-center justify-between gap-3 bg-slate-50/70 dark:bg-slate-950/40 shrink-0">
            <div class="flex items-center gap-2 flex-wrap min-w-0">
                <span id="ballot-candidate-viewer-position" class="inline-flex items-center text-[10px] sm:text-xs font-bold px-2.5 py-1 rounded-lg bg-purple-500/10 text-purple-600 dark:text-purple-400 border border-purple-500/20 uppercase tracking-wide">
                    Position
                </span>
                <span id="ballot-candidate-viewer-party" class="hidden inline-flex items-center text-[10px] sm:text-xs font-semibold px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-800/90 text-slate-700 dark:text-slate-300 border border-slate-200/80 dark:border-slate-700/80">
                    Party Affiliation
                </span>
                <span class="inline-flex items-center gap-1.5 text-[10px] sm:text-xs font-medium text-slate-400 dark:text-slate-500 hidden sm:inline-flex">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    Verified Candidate
                </span>
            </div>
            
            <button type="button" onclick="closeBallotCandidateImage()"
                class="p-2 text-slate-400 hover:text-slate-700 dark:text-slate-400 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800/80 rounded-xl transition-all duration-200 hover:scale-105 active:scale-95 shrink-0"
                title="Close profile">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Scrollable Modal Body (2 Columns on Desktop / Tablet) -->
        <div class="p-4 sm:p-6 overflow-y-auto custom-scrollbar flex-1 grid grid-cols-1 md:grid-cols-12 gap-5 sm:gap-6 items-start">
            
            <!-- LEFT COLUMN: Candidate Face Portrait & Identity (md:col-span-5) -->
            <div class="md:col-span-5 flex flex-col items-center md:items-start text-center md:text-left space-y-4 bg-slate-50/50 dark:bg-slate-950/30 p-4 sm:p-5 rounded-2xl border border-slate-100 dark:border-slate-800/60">
                <!-- Eyebrow Tag -->
                <div class="w-full flex items-center justify-between">
                    <span class="text-[10px] font-bold text-purple-600 dark:text-purple-400 uppercase tracking-widest flex items-center gap-1.5">
                        <i class="fa-solid fa-user-tie text-[11px]"></i>
                        Candidate Portrait
                    </span>
                    <span class="text-[10px] font-medium text-slate-400 dark:text-slate-500">
                        Tap photo to expand
                    </span>
                </div>

                <!-- Big Candidate Face Display (Clickable for in-page Face Inspection) -->
                <div class="w-full flex justify-center">
                    <button type="button"
                        id="ballot-candidate-face-btn"
                        onclick="openFaceLightbox()"
                        class="group/portrait relative w-48 h-48 sm:w-56 sm:h-56 md:w-full md:max-w-[280px] md:h-72 rounded-2xl sm:rounded-3xl overflow-hidden border-2 border-purple-500/20 dark:border-purple-500/30 bg-slate-100 dark:bg-slate-950 shadow-md hover:shadow-xl hover:border-purple-500/50 dark:hover:border-purple-400/50 transition-all duration-300 focus:outline-none flex items-center justify-center cursor-zoom-in"
                        title="Click to view candidate face closeup (stays on this page)">
                        
                        <!-- Candidate Portrait Image -->
                        <img id="ballot-candidate-viewer-img" src="" alt="Candidate Portrait"
                            class="w-full h-full object-cover transition-transform duration-500 ease-out group-hover/portrait:scale-105">

                        <!-- Initials Fallback if no avatar image -->
                        <div id="ballot-candidate-viewer-initials" class="hidden w-full h-full flex items-center justify-center text-4xl sm:text-5xl font-black text-purple-600 dark:text-purple-400 bg-purple-50 dark:bg-purple-950/50">
                            --
                        </div>

                        <!-- Hover Visual Lens Overlay & Floating Pill Badge -->
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-slate-950/20 to-transparent opacity-0 group-hover/portrait:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-3">
                            <span class="inline-flex items-center justify-center gap-1.5 text-[11px] font-bold text-white bg-purple-600/90 backdrop-blur-md py-1.5 px-3 rounded-xl shadow-lg transform translate-y-2 group-hover/portrait:translate-y-0 transition-transform duration-300">
                                <i class="fa-solid fa-magnifying-glass-plus text-xs"></i>
                                <span>Inspect Face Closeup</span>
                            </span>
                        </div>

                        <!-- Permanent subtle inspection badge on bottom right -->
                        <div class="absolute bottom-2.5 right-2.5 p-1.5 bg-white/90 dark:bg-slate-900/90 backdrop-blur-md rounded-lg shadow-sm border border-slate-200/80 dark:border-slate-700/80 text-slate-600 dark:text-slate-300 group-hover/portrait:hidden transition">
                            <i class="fa-solid fa-expand text-[10px]"></i>
                        </div>
                    </button>
                </div>

                <!-- Candidate Name & Details Block -->
                <div class="w-full space-y-1">
                    <h3 id="ballot-candidate-viewer-name" class="font-black text-slate-900 dark:text-white text-lg sm:text-xl md:text-2xl leading-snug break-words">
                        Candidate Name
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">
                        Official Nominee for Election
                    </p>
                </div>

                <!-- Quick Interactive Tip Box -->
                <div class="w-full p-3 rounded-xl bg-purple-50/70 dark:bg-purple-950/30 border border-purple-200/60 dark:border-purple-800/40 text-[11px] text-purple-800 dark:text-purple-300 flex items-start gap-2 text-left">
                    <i class="fa-solid fa-circle-info text-xs mt-0.5 shrink-0 text-purple-600 dark:text-purple-400"></i>
                    <span>Tap or click the candidate portrait above to inspect facial details up close without leaving this ballot.</span>
                </div>
            </div>

            <!-- RIGHT COLUMN: Campaign Poster & Platform (md:col-span-7) -->
            <div class="md:col-span-7 flex flex-col space-y-3">
                <!-- Section Header with Poster Title + Open Original Button -->
                <div class="flex items-center justify-between gap-2 pb-1 border-b border-slate-100 dark:border-slate-800/60">
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 rounded-lg bg-purple-500/10 text-purple-600 dark:text-purple-400 flex items-center justify-center text-xs">
                            <i class="fa-solid fa-image"></i>
                        </div>
                        <div>
                            <h4 class="text-xs sm:text-sm font-bold text-slate-800 dark:text-slate-200 leading-tight">
                                Campaign Poster & Platform
                            </h4>
                        </div>
                    </div>

                    <!-- Open Full Size in New Tab (specifically for Campaign Poster) -->
                    <a id="ballot-poster-link" href="#" target="_blank" rel="noopener noreferrer"
                        class="hidden inline-flex items-center gap-1.5 text-[11px] font-bold text-purple-600 dark:text-purple-400 hover:text-purple-700 dark:hover:text-purple-300 bg-purple-500/10 hover:bg-purple-500/15 border border-purple-500/20 px-2.5 py-1 rounded-lg transition-all duration-200 hover:scale-[1.02] active:scale-95 shadow-2xs"
                        title="Open full resolution poster in new tab">
                        <span>Open Poster</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                    </a>
                </div>

                <!-- Poster Canvas Container -->
                <div id="ballot-poster-container" class="relative rounded-2xl overflow-hidden bg-slate-900/5 dark:bg-slate-950 border border-slate-200/80 dark:border-slate-800/80 flex items-center justify-center min-h-[260px] md:min-h-[420px] p-2 sm:p-3">
                    <!-- Poster Image (Clickable for full resolution in new tab) -->
                    <a id="ballot-poster-img-link" href="#" target="_blank" rel="noopener noreferrer"
                        class="block w-full text-center group cursor-zoom-in"
                        title="Click to open full campaign poster in new browser tab">
                        <img id="ballot-candidate-viewer-poster" src="" alt="Candidate Campaign Poster"
                            class="hidden max-h-[50vh] sm:max-h-[58vh] md:max-h-[62vh] w-auto max-w-full mx-auto object-contain rounded-xl shadow-md transition-transform duration-300 group-hover:scale-[1.01]">
                    </a>

                    <!-- Empty State (No Poster) -->
                    <div id="ballot-poster-empty" class="hidden flex flex-col items-center justify-center py-10 px-4 text-center">
                        <div class="w-14 h-14 rounded-2xl bg-purple-50 dark:bg-purple-950/40 text-purple-600 dark:text-purple-400 flex items-center justify-center mb-3 shadow-xs">
                            <i class="fa-solid fa-image text-xl"></i>
                        </div>
                        <p class="text-xs sm:text-sm font-bold text-slate-700 dark:text-slate-300">No Campaign Poster Available</p>
                        <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-1 max-w-xs">The candidate has not published an official campaign poster yet.</p>
                    </div>

                    <!-- Fallback Text Bio (if legacy text is present instead of an image URL) -->
                    <div id="ballot-candidate-viewer-background" class="hidden w-full p-4 rounded-xl bg-slate-50 dark:bg-slate-900 text-xs sm:text-sm text-slate-700 dark:text-slate-300 leading-relaxed whitespace-pre-line break-words max-h-64 overflow-y-auto custom-scrollbar border border-slate-200/60 dark:border-slate-800">
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="px-5 py-3 sm:px-6 sm:py-3.5 border-t border-slate-100 dark:border-slate-800/80 bg-white dark:bg-slate-900 flex items-center justify-between shrink-0">
            <div class="flex items-center gap-2 text-[11px] text-slate-400 dark:text-slate-500 font-medium">
                <i class="fa-solid fa-shield-halved text-purple-500/70"></i>
                <span>SAKO Cooperative • Secret Ballot Protocol</span>
            </div>
            <button type="button" onclick="closeBallotCandidateImage()"
                class="px-5 py-2 text-xs font-bold rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 transition-all duration-200 hover:scale-105 active:scale-95 shadow-xs">
                Close
            </button>
        </div>
    </div>
</div>

<!-- IN-PAGE FACE INSPECTION LIGHTBOX (Stays 100% on the page, no new tab) -->
<div id="ballot-face-lightbox"
    onclick="if (event.target === this) closeFaceLightbox()"
    class="fixed inset-0 bg-slate-950/85 backdrop-blur-md z-[60] hidden items-center justify-center p-4 transition-all duration-300 opacity-0"
    role="dialog" aria-modal="true" aria-labelledby="ballot-face-lightbox-name">
    <div id="ballot-face-lightbox-content"
        class="relative bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 max-w-md w-full shadow-2xl overflow-hidden transition-all duration-300 transform scale-95 opacity-0 flex flex-col">
        
        <!-- Header -->
        <div class="px-4 py-3 border-b border-slate-100 dark:border-slate-800/80 flex items-center justify-between bg-slate-50/70 dark:bg-slate-950/40">
            <div class="min-w-0 pr-2">
                <span class="text-[9px] font-bold text-purple-600 dark:text-purple-400 uppercase tracking-wider block">Candidate Face Closeup</span>
                <h4 id="ballot-face-lightbox-name" class="font-bold text-slate-900 dark:text-white text-sm truncate">
                    Candidate Portrait
                </h4>
            </div>
            <button type="button" onclick="closeFaceLightbox()"
                class="p-1.5 text-slate-400 hover:text-slate-700 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition"
                title="Back to Candidate Profile">
                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- High-clarity Face Image -->
        <div class="p-3 bg-slate-950 flex items-center justify-center overflow-hidden">
            <img id="ballot-face-lightbox-img" src="" alt="Candidate Face Closeup"
                class="max-h-[65vh] w-auto max-w-full object-contain rounded-2xl shadow-xl">
        </div>

        <!-- Footer -->
        <div class="px-4 py-2.5 border-t border-slate-100 dark:border-slate-800/80 bg-slate-50/50 dark:bg-slate-950/40 flex items-center justify-between">
            <span class="text-[10px] text-slate-400">Viewing within ballot page</span>
            <button type="button" onclick="closeFaceLightbox()"
                class="px-3.5 py-1.5 text-xs font-semibold rounded-lg bg-slate-200/80 dark:bg-slate-800 hover:bg-slate-300 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 transition">
                Done
            </button>
        </div>
    </div>
</div>
@endsection
