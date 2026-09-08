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

                            <!-- Profile Picture -->
                            @if($candidate->avatar_path)
                                <img src="{{ $candidate->avatar_path }}" alt="{{ $candidate->name }}" class="w-12 h-12 sm:w-16 sm:h-16 rounded-full object-cover mb-3 sm:mb-4 border border-slate-200/60 dark:border-slate-850 shadow-sm shrink-0">
                            @else
                                <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-full bg-purple-50 dark:bg-purple-950 text-purple-600 dark:text-purple-400 font-extrabold flex items-center justify-center mb-3 sm:mb-4 shrink-0 border border-purple-100/30 text-base sm:text-lg uppercase shadow-sm">
                                    {{ substr($candidate->name, 0, 2) }}
                                </div>
                            @endif

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
            <h4 class="font-bold text-sm text-slate-900 dark:text-white leading-tight">Cast Your Secret Ballot</h4>
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
                btn.disabled = true;
                btn.textContent = 'Submitting Ballot...';

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
                    if (res.success) {
                        window.location.href = res.redirect;
                    } else {
                        window.showToast(res.message || 'Submitting ballot failed.', 'error');
                        btn.disabled = false;
                        btn.textContent = 'Cast Secure Ballot';
                    }
                })
                .catch(err => {
                    console.error(err);
                    window.showToast('Server connection failed.', 'error');
                    btn.disabled = false;
                    btn.textContent = 'Cast Secure Ballot';
                });
            }
        );
    }
</script>
@endsection
