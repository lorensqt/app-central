<!-- PANEL A: STRUCTURE (Positions & Candidates) -->
<div id="panel-structure" class="manage-tab-panel space-y-6">
    <div class="flex items-center justify-between gap-4">
        <h2 class="text-xl font-bold text-slate-900 dark:text-white">Ballot Structure</h2>
        <button onclick="openCreatePositionModal()" class="inline-flex items-center gap-1.5 bg-purple-600 hover:bg-purple-700 text-white font-semibold text-xs px-4 py-2.5 rounded-xl shadow-sm transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 4v16m8-8H4"/></svg>
            Add Position
        </button>
    </div>

    <!-- List of Positions -->
    <div class="space-y-6" id="positions-container">
        @forelse($positions as $position)
            <div class="bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-200/60 dark:border-slate-800/80 p-6 sm:p-8 shadow-sm space-y-6" id="position-block-{{ $position->id }}">
                <!-- Position Header -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-100 dark:border-slate-800/60">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
                            {{ $position->name }}
                            <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400">
                                Choose Up To {{ $position->max_votes }}
                            </span>
                        </h3>
                        <p class="text-xs text-slate-400 mt-0.5">Order Priority: {{ $position->sort_order }}</p>
                    </div>

                    <div class="flex items-center gap-2">
                        <button onclick="openCreateCandidateModal({{ $position->id }}, '{{ addslashes($position->name) }}')" class="inline-flex items-center gap-1 text-slate-600 dark:text-slate-300 hover:text-purple-600 dark:hover:text-purple-400 font-bold text-xs py-2 px-3 rounded-lg hover:bg-purple-50 dark:hover:bg-purple-950/20 transition border border-transparent hover:border-purple-100 dark:hover:border-purple-900/40">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Add Candidate
                        </button>
                        <button onclick="openEditPositionModal({{ json_encode($position) }})" class="p-2 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-xl text-slate-500 hover:text-slate-800 dark:hover:text-slate-200 transition" title="Edit Position">
                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </button>
                        <button onclick="deletePosition({{ $position->id }})" class="p-2 hover:bg-rose-50 dark:hover:bg-rose-950/20 rounded-xl text-slate-500 hover:text-rose-600 dark:hover:text-rose-400 transition" title="Delete Position">
                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>
                </div>

                <!-- Candidate Grid inside Position -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4" id="candidates-grid-{{ $position->id }}">
                    @forelse($position->candidates as $candidate)
                        <div class="relative group/card bg-slate-50/50 dark:bg-slate-950/40 border border-slate-100 dark:border-slate-800/80 rounded-2xl p-4 flex items-center justify-between hover:shadow-sm transition" id="candidate-card-{{ $candidate->id }}">
                            <div class="flex items-center gap-3.5 min-w-0">
                                <!-- Avatar Container (Square & Click to Preview) -->
                                @if($candidate->avatar_path)
                                    <button type="button"
                                        onclick="openCandidateImageViewer('{{ addslashes($candidate->avatar_path) }}', '{{ addslashes($candidate->name) }}', '{{ addslashes($candidate->party_affiliation ?? 'Independent') }}')"
                                        class="group/avatar relative w-11 h-11 rounded-xl overflow-hidden shrink-0 border border-slate-200 dark:border-slate-700/80 hover:border-purple-500/60 dark:hover:border-purple-400/60 hover:ring-2 hover:ring-purple-500/20 dark:hover:ring-purple-400/20 transition-all duration-200 cursor-pointer shadow-xs focus:outline-none"
                                        title="Click to view full photo">
                                        <img src="{{ $candidate->avatar_path }}" alt="{{ $candidate->name }}"
                                            class="w-full h-full object-cover group-hover/avatar:scale-110 transition-transform duration-200">
                                        <!-- Hover zoom indicator overlay -->
                                        <div class="absolute inset-0 bg-slate-950/40 opacity-0 group-hover/avatar:opacity-100 flex items-center justify-center transition-opacity duration-200">
                                            <svg class="w-4 h-4 text-white drop-shadow-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m3-3H7"/>
                                            </svg>
                                        </div>
                                    </button>
                                @else
                                    <div class="w-11 h-11 rounded-xl bg-purple-50 dark:bg-purple-950/40 text-purple-600 dark:text-purple-400 font-bold flex items-center justify-center shrink-0 border border-purple-200/60 dark:border-purple-800/50 text-sm uppercase shadow-xs">
                                        {{ substr($candidate->name, 0, 2) }}
                                    </div>
                                @endif
                                
                                <div class="min-w-0">
                                    <h4 class="font-bold text-sm text-slate-900 dark:text-white truncate">
                                        {{ $candidate->name }}
                                    </h4>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 truncate">
                                        {{ $candidate->party_affiliation ?? 'Independent' }}
                                    </p>
                                </div>
                            </div>

                            <!-- Candidate Controls -->
                            <div class="flex items-center gap-1 opacity-0 group-hover/card:opacity-100 transition">
                                <button onclick="openEditCandidateModal({{ json_encode($candidate) }})" class="p-1.5 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg text-slate-500 hover:text-slate-800 dark:hover:text-slate-200 transition" title="Edit Candidate">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>
                                <button onclick="deleteCandidate({{ $candidate->id }})" class="p-1.5 hover:bg-rose-50 dark:hover:bg-rose-950/20 rounded-lg text-slate-500 hover:text-rose-600 dark:hover:text-rose-400 transition" title="Delete Candidate">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full py-4 text-center text-xs text-slate-400 italic" id="candidates-empty-{{ $position->id }}">
                            No candidates added yet. Click "+ Add Candidate" to configure roles.
                        </div>
                    @endforelse
                </div>
            </div>
        @empty
            <div class="bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-200/60 dark:border-slate-800/80 p-12 text-center max-w-xl mx-auto" id="no-positions-empty">
                <div class="w-12 h-12 bg-slate-50 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                </div>
                <h4 class="font-bold text-slate-900 dark:text-white">No Positions Defined</h4>
                <p class="text-xs text-slate-500 mt-1">Start building the ballot by adding the first position (e.g. Chairman).</p>
                <button onclick="openCreatePositionModal()" class="mt-4 text-xs font-semibold bg-purple-600 text-white py-2 px-4 rounded-xl hover:bg-purple-700 transition">
                    Create First Position
                </button>
            </div>
        @endforelse
    </div>
</div>

<!-- CANDIDATE PHOTO PREVIEW MODAL -->
<div id="candidate-image-viewer-modal"
    onclick="if (event.target === this) closeCandidateImageViewer()"
    class="fixed inset-0 bg-slate-900/75 dark:bg-slate-950/85 backdrop-blur-[6px] z-50 hidden items-center justify-center p-4 transition-all duration-300 opacity-0"
    role="dialog" aria-modal="true" aria-labelledby="candidate-viewer-name">
    <div class="relative bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-200/80 dark:border-slate-800 max-w-lg w-full shadow-2xl overflow-hidden transition-all duration-300 transform scale-95 opacity-0 flex flex-col max-h-[90vh]"
        id="candidate-image-viewer-content">

        <!-- Modal Header -->
        <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800/80 flex items-center justify-between bg-slate-50/60 dark:bg-slate-950/40 shrink-0">
            <div class="min-w-0 pr-3">
                <h3 id="candidate-viewer-name" class="font-bold text-slate-900 dark:text-white text-base sm:text-lg truncate leading-tight">
                    Candidate Photo
                </h3>
                <div class="flex items-center gap-2 mt-1">
                    <span id="candidate-viewer-party" class="text-xs font-semibold px-2.5 py-0.5 rounded-md bg-purple-50 dark:bg-purple-950/60 text-purple-600 dark:text-purple-400 border border-purple-200/60 dark:border-purple-800/40 truncate">
                        Party Affiliation
                    </span>
                </div>
            </div>
            
            <div class="flex items-center gap-1 shrink-0">
                <a id="candidate-viewer-link" href="#" target="_blank" rel="noopener noreferrer"
                    class="p-2 text-slate-400 hover:text-purple-600 dark:text-slate-500 dark:hover:text-purple-400 hover:bg-slate-100 dark:hover:bg-slate-800/80 rounded-xl transition"
                    title="Open original image">
                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                </a>
                <button type="button" onclick="closeCandidateImageViewer()"
                    class="p-2 text-slate-400 hover:text-slate-600 dark:text-slate-500 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800/80 rounded-xl transition"
                    title="Close preview">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Image Viewing Area -->
        <div class="p-4 sm:p-6 bg-slate-100/60 dark:bg-slate-950/70 flex items-center justify-center overflow-hidden flex-1 min-h-[220px]">
            <img id="candidate-viewer-image" src="" alt="Candidate Portrait"
                class="max-h-[60vh] max-w-full w-auto h-auto object-contain rounded-2xl shadow-lg border border-slate-200/60 dark:border-slate-800 bg-white dark:bg-slate-900 transition-all duration-300">
        </div>

        <!-- Modal Footer -->
        <div class="px-6 py-3.5 border-t border-slate-100 dark:border-slate-800/80 bg-white dark:bg-slate-900 flex items-center justify-between shrink-0">
            <span class="text-[11px] text-slate-400 dark:text-slate-500 font-medium">Candidate Profile Picture</span>
            <button type="button" onclick="closeCandidateImageViewer()"
                class="px-4 py-2 text-xs font-semibold rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 transition">
                Close
            </button>
        </div>
    </div>
</div>

<script>
    function openCandidateImageViewer(url, name, party) {
        const modal = document.getElementById('candidate-image-viewer-modal');
        const content = document.getElementById('candidate-image-viewer-content');
        const img = document.getElementById('candidate-viewer-image');
        const nameEl = document.getElementById('candidate-viewer-name');
        const partyEl = document.getElementById('candidate-viewer-party');
        const linkEl = document.getElementById('candidate-viewer-link');

        if (!modal || !img) return;

        img.src = url;
        img.alt = name ? name + ' photo' : 'Candidate photo';
        if (nameEl) nameEl.textContent = name || 'Candidate Photo';
        if (partyEl) partyEl.textContent = party || 'Independent';
        if (linkEl) linkEl.href = url;

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        requestAnimationFrame(() => {
            modal.classList.remove('opacity-0');
            modal.classList.add('opacity-100');
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        });
    }

    function closeCandidateImageViewer() {
        const modal = document.getElementById('candidate-image-viewer-modal');
        const content = document.getElementById('candidate-image-viewer-content');
        if (!modal) return;

        if (content) {
            content.classList.add('scale-95', 'opacity-0');
        }
        modal.classList.add('opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            const img = document.getElementById('candidate-viewer-image');
            if (img) img.src = '';
        }, 300);
    }

    // Dismiss on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modal = document.getElementById('candidate-image-viewer-modal');
            if (modal && !modal.classList.contains('hidden')) {
                closeCandidateImageViewer();
            }
        }
    });
</script>
