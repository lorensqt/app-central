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

                    <div class="flex items-center gap-2 flex-wrap sm:flex-nowrap">
                        <button type="button" onclick="openCreateCandidateModal({{ $position->id }}, '{{ addslashes($position->name) }}')" class="inline-flex items-center gap-1.5 text-slate-700 dark:text-slate-200 hover:text-purple-600 dark:hover:text-purple-400 font-bold text-xs py-2 px-3 rounded-xl hover:bg-purple-50 dark:hover:bg-purple-950/20 transition border border-transparent hover:border-purple-100 dark:hover:border-purple-900/40">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 4v16m8-8H4"/></svg>
                            Add Candidate
                        </button>
                        <button type="button" onclick="openEditPositionModal({{ $position->id }}, '{{ addslashes($position->name) }}', {{ (int)$position->max_votes }}, {{ (int)$position->sort_order }})" class="inline-flex items-center gap-1.5 text-purple-600 dark:text-purple-400 hover:text-white dark:hover:text-slate-900 hover:bg-purple-600 dark:hover:bg-purple-400 bg-purple-500/10 dark:bg-purple-400/10 border border-purple-500/20 px-3 py-2 rounded-xl transition duration-150 active:scale-95 font-bold text-xs shadow-2xs" title="Edit Position">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            <span>Edit Position</span>
                        </button>
                        <button type="button" onclick="deletePosition({{ $position->id }})" class="p-2 hover:bg-rose-50 dark:hover:bg-rose-950/20 rounded-xl text-slate-400 hover:text-rose-600 dark:hover:text-rose-400 transition" title="Delete Position">
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
                                        onclick="openCandidateProfileModal('{{ addslashes($candidate->avatar_path) }}', '{{ addslashes($candidate->name) }}', '{{ addslashes($candidate->party_affiliation ?? 'Independent') }}', '{{ addslashes($position->name) }}', '{{ addslashes($candidate->background ?? '') }}')"
                                        class="group/avatar relative w-11 h-11 rounded-xl overflow-hidden shrink-0 border border-slate-200 dark:border-slate-700/80 hover:border-purple-500/60 dark:hover:border-purple-400/60 hover:ring-2 hover:ring-purple-500/20 dark:hover:ring-purple-400/20 transition-all duration-200 cursor-pointer shadow-xs focus:outline-none"
                                        title="Click to view candidate details">
                                        <img src="{{ $candidate->avatar_path }}" alt="{{ $candidate->name }}"
                                            class="w-full h-full object-cover group-hover/avatar:scale-110 transition-transform duration-200">
                                        <!-- Hover zoom indicator overlay -->
                                        <div class="absolute inset-0 bg-slate-950/40 opacity-0 group-hover/avatar:opacity-100 flex items-center justify-center transition-opacity duration-200">
                                            <svg class="w-4 h-4 text-white drop-shadow-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </div>
                                    </button>
                                @else
                                    <button type="button"
                                        onclick="openCandidateProfileModal('', '{{ addslashes($candidate->name) }}', '{{ addslashes($candidate->party_affiliation ?? 'Independent') }}', '{{ addslashes($position->name) }}', '{{ addslashes($candidate->background ?? '') }}')"
                                        class="w-11 h-11 rounded-xl bg-purple-50 dark:bg-purple-950/40 text-purple-600 dark:text-purple-400 font-bold flex items-center justify-center shrink-0 border border-purple-200/60 dark:border-purple-800/50 text-sm uppercase shadow-xs hover:border-purple-500/50 transition cursor-pointer"
                                        title="Click to view candidate details">
                                        {{ substr($candidate->name, 0, 2) }}
                                    </button>
                                @endif
                                
                                <div class="min-w-0">
                                    <h4 class="font-bold text-sm text-slate-900 dark:text-white truncate cursor-pointer hover:text-purple-600 dark:hover:text-purple-400 transition"
                                        onclick="openCandidateProfileModal('{{ addslashes($candidate->avatar_path ?? '') }}', '{{ addslashes($candidate->name) }}', '{{ addslashes($candidate->party_affiliation ?? 'Independent') }}', '{{ addslashes($position->name) }}', '{{ addslashes($candidate->background ?? '') }}')">
                                        {{ $candidate->name }}
                                    </h4>
                                    @if(!empty($candidate->party_affiliation))
                                        <p class="text-xs text-slate-500 dark:text-slate-400 truncate">
                                            {{ $candidate->party_affiliation }}
                                        </p>
                                    @endif
                                    @if(!empty($candidate->background))
                                        <button type="button"
                                            onclick="openCandidateProfileModal('{{ addslashes($candidate->avatar_path ?? '') }}', '{{ addslashes($candidate->name) }}', '{{ addslashes($candidate->party_affiliation ?? 'Independent') }}', '{{ addslashes($position->name) }}', '{{ addslashes($candidate->background ?? '') }}')"
                                            class="inline-flex items-center gap-1 text-[10px] font-bold text-purple-600 dark:text-purple-400 hover:text-purple-700 dark:hover:text-purple-300 transition mt-0.5"
                                            title="View Campaign Poster">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            <span>Poster</span>
                                        </button>
                                    @endif
                                </div>
                            </div>

                            <!-- Candidate Controls -->
                            <div class="flex items-center gap-1 opacity-0 group-hover/card:opacity-100 transition">
                                <button type="button" onclick="openEditCandidateModal({{ json_encode([
                                    'id' => $candidate->id,
                                    'name' => $candidate->name,
                                    'party_affiliation' => $candidate->party_affiliation ?? '',
                                    'avatar_path' => $candidate->avatar_path ?? '',
                                    'sort_order' => $candidate->sort_order,
                                    'background' => $candidate->background ?? '',
                                ]) }})" class="p-1.5 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg text-slate-500 hover:text-slate-800 dark:hover:text-slate-200 transition" title="Edit Candidate">
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

<!-- 2-COLUMN CANDIDATE DETAILS MODAL (MANAGE STRUCTURE) -->
<div id="candidate-image-viewer-modal"
    onclick="if (event.target === this) closeCandidateImageViewer()"
    class="fixed inset-0 bg-slate-900/80 dark:bg-slate-950/90 backdrop-blur-md z-50 hidden items-center justify-center p-3 sm:p-5 md:p-6 transition-all duration-300 opacity-0"
    role="dialog" aria-modal="true" aria-labelledby="candidate-viewer-name">
    <div class="relative bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 max-w-lg sm:max-w-2xl md:max-w-4xl lg:max-w-5xl w-full shadow-2xl overflow-hidden transition-all duration-300 transform scale-95 opacity-0 flex flex-col max-h-[92vh]"
        id="candidate-image-viewer-content">

        <!-- Modal Header -->
        <div class="px-5 py-3.5 sm:px-6 sm:py-4 border-b border-slate-100 dark:border-slate-800/80 flex items-center justify-between gap-3 bg-slate-50/70 dark:bg-slate-950/40 shrink-0">
            <div class="flex items-center gap-2 flex-wrap min-w-0">
                <span id="candidate-viewer-position" class="inline-flex items-center text-[10px] sm:text-xs font-bold px-2.5 py-1 rounded-lg bg-purple-500/10 text-purple-600 dark:text-purple-400 border border-purple-500/20 uppercase tracking-wide">
                    Candidate Profile
                </span>
                <span id="candidate-viewer-party" class="hidden inline-flex items-center text-[10px] sm:text-xs font-semibold px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-800/90 text-slate-700 dark:text-slate-300 border border-slate-200/80 dark:border-slate-700/80">
                    Party Affiliation
                </span>
            </div>
            
            <button type="button" onclick="closeCandidateImageViewer()"
                class="p-2 text-slate-400 hover:text-slate-700 dark:text-slate-400 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800/80 rounded-xl transition-all duration-200 hover:scale-105 active:scale-95 shrink-0"
                title="Close preview">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- 2-Column Body -->
        <div class="p-4 sm:p-6 overflow-y-auto custom-scrollbar flex-1 grid grid-cols-1 md:grid-cols-12 gap-5 sm:gap-6 items-start">
            
            <!-- LEFT COLUMN: Candidate Portrait & Face (md:col-span-5) -->
            <div class="md:col-span-5 flex flex-col items-center md:items-start text-center md:text-left space-y-4 bg-slate-50/50 dark:bg-slate-950/30 p-4 sm:p-5 rounded-2xl border border-slate-100 dark:border-slate-800/60">
                <div class="w-full flex items-center justify-between">
                    <span class="text-[10px] font-bold text-purple-600 dark:text-purple-400 uppercase tracking-widest flex items-center gap-1.5">
                        <i class="fa-solid fa-user-tie text-[11px]"></i>
                        Candidate Portrait
                    </span>
                    <span class="text-[10px] font-medium text-slate-400 dark:text-slate-500">
                        Tap photo to expand
                    </span>
                </div>

                <!-- Big Candidate Face Display (Clickable for In-Page Closeup) -->
                <div class="w-full flex justify-center">
                    <button type="button"
                        id="candidate-face-btn"
                        onclick="openCandidateFaceLightbox()"
                        class="group/portrait relative w-48 h-48 sm:w-56 sm:h-56 md:w-full md:max-w-[280px] md:h-72 rounded-2xl sm:rounded-3xl overflow-hidden border-2 border-purple-500/20 dark:border-purple-500/30 bg-slate-100 dark:bg-slate-950 shadow-md hover:shadow-xl hover:border-purple-500/50 dark:hover:border-purple-400/50 transition-all duration-300 focus:outline-none flex items-center justify-center cursor-zoom-in"
                        title="Click to view candidate face closeup (stays on this page)">
                        
                        <img id="candidate-viewer-image" src="" alt="Candidate Portrait"
                            class="w-full h-full object-cover transition-transform duration-500 ease-out group-hover/portrait:scale-105">

                        <div id="candidate-viewer-initials" class="hidden w-full h-full flex items-center justify-center text-4xl sm:text-5xl font-black text-purple-600 dark:text-purple-400 bg-purple-50 dark:bg-purple-950/50">
                            --
                        </div>

                        <!-- Hover Visual Lens Overlay -->
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-slate-950/20 to-transparent opacity-0 group-hover/portrait:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-3">
                            <span class="inline-flex items-center justify-center gap-1.5 text-[11px] font-bold text-white bg-purple-600/90 backdrop-blur-md py-1.5 px-3 rounded-xl shadow-lg transform translate-y-2 group-hover/portrait:translate-y-0 transition-transform duration-300">
                                <i class="fa-solid fa-magnifying-glass-plus text-xs"></i>
                                <span>Inspect Face Closeup</span>
                            </span>
                        </div>

                        <!-- Badge on bottom right -->
                        <div class="absolute bottom-2.5 right-2.5 p-1.5 bg-white/90 dark:bg-slate-900/90 backdrop-blur-md rounded-lg shadow-sm border border-slate-200/80 dark:border-slate-700/80 text-slate-600 dark:text-slate-300 group-hover/portrait:hidden transition">
                            <i class="fa-solid fa-expand text-[10px]"></i>
                        </div>
                    </button>
                </div>

                <!-- Name & Details -->
                <div class="w-full space-y-1">
                    <h3 id="candidate-viewer-name" class="font-black text-slate-900 dark:text-white text-lg sm:text-xl md:text-2xl leading-snug break-words">
                        Candidate Name
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">
                        Election Nominee
                    </p>
                </div>

                <div class="w-full p-3 rounded-xl bg-purple-50/70 dark:bg-purple-950/30 border border-purple-200/60 dark:border-purple-800/40 text-[11px] text-purple-800 dark:text-purple-300 flex items-start gap-2 text-left">
                    <i class="fa-solid fa-circle-info text-xs mt-0.5 shrink-0 text-purple-600 dark:text-purple-400"></i>
                    <span>Tap or click portrait above to inspect face details without leaving this page.</span>
                </div>
            </div>

            <!-- RIGHT COLUMN: Campaign Poster & Platform (md:col-span-7) -->
            <div class="md:col-span-7 flex flex-col space-y-3">
                <div class="flex items-center justify-between gap-2 pb-1 border-b border-slate-100 dark:border-slate-800/60">
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 rounded-lg bg-purple-500/10 text-purple-600 dark:text-purple-400 flex items-center justify-center text-xs">
                            <i class="fa-solid fa-image"></i>
                        </div>
                        <h4 class="text-xs sm:text-sm font-bold text-slate-800 dark:text-slate-200 leading-tight">
                            Campaign Poster & Platform
                        </h4>
                    </div>

                    <a id="candidate-viewer-link" href="#" target="_blank" rel="noopener noreferrer"
                        class="hidden inline-flex items-center gap-1.5 text-[11px] font-bold text-purple-600 dark:text-purple-400 hover:text-purple-700 dark:hover:text-purple-300 bg-purple-500/10 hover:bg-purple-500/15 border border-purple-500/20 px-2.5 py-1 rounded-lg transition-all duration-200 hover:scale-[1.02] active:scale-95 shadow-2xs"
                        title="Open full resolution poster in new tab">
                        <span>Open Poster</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                    </a>
                </div>

                <div id="candidate-poster-container" class="relative rounded-2xl overflow-hidden bg-slate-900/5 dark:bg-slate-950 border border-slate-200/80 dark:border-slate-800/80 flex items-center justify-center min-h-[260px] md:min-h-[420px] p-2 sm:p-3">
                    <a id="candidate-poster-img-link" href="#" target="_blank" rel="noopener noreferrer"
                        class="block w-full text-center group cursor-zoom-in"
                        title="Click to open full campaign poster in new tab">
                        <img id="candidate-viewer-poster" src="" alt="Campaign Poster"
                            class="hidden max-h-[50vh] sm:max-h-[58vh] md:max-h-[62vh] w-auto max-w-full mx-auto object-contain rounded-xl shadow-md transition-transform duration-300 group-hover:scale-[1.01]">
                    </a>

                    <div id="candidate-poster-empty" class="hidden flex flex-col items-center justify-center py-10 px-4 text-center">
                        <div class="w-14 h-14 rounded-2xl bg-purple-50 dark:bg-purple-950/40 text-purple-600 dark:text-purple-400 flex items-center justify-center mb-3 shadow-xs">
                            <i class="fa-solid fa-image text-xl"></i>
                        </div>
                        <p class="text-xs sm:text-sm font-bold text-slate-700 dark:text-slate-300">No Campaign Poster Available</p>
                        <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-1 max-w-xs">No campaign poster uploaded yet for this candidate.</p>
                    </div>

                    <div id="candidate-viewer-background" class="hidden w-full p-4 rounded-xl bg-slate-50 dark:bg-slate-900 text-xs sm:text-sm text-slate-700 dark:text-slate-300 leading-relaxed whitespace-pre-line break-words max-h-64 overflow-y-auto custom-scrollbar border border-slate-200/60 dark:border-slate-800">
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="px-5 py-3 sm:px-6 sm:py-3.5 border-t border-slate-100 dark:border-slate-800/80 bg-white dark:bg-slate-900 flex items-center justify-between shrink-0">
            <span class="text-xs text-slate-400 dark:text-slate-500 font-medium">Candidate Profile Details</span>
            <button type="button" onclick="closeCandidateImageViewer()"
                class="px-5 py-2 text-xs font-bold rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 transition-all duration-200 hover:scale-105 active:scale-95 shadow-xs">
                Close
            </button>
        </div>
    </div>
</div>

<!-- IN-PAGE CANDIDATE FACE LIGHTBOX (Stays on page) -->
<div id="candidate-face-lightbox"
    onclick="if (event.target === this) closeCandidateFaceLightbox()"
    class="fixed inset-0 bg-slate-950/85 backdrop-blur-md z-[60] hidden items-center justify-center p-4 transition-all duration-300 opacity-0"
    role="dialog" aria-modal="true" aria-labelledby="candidate-face-lightbox-name">
    <div id="candidate-face-lightbox-content"
        class="relative bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 max-w-md w-full shadow-2xl overflow-hidden transition-all duration-300 transform scale-95 opacity-0 flex flex-col">
        
        <div class="px-4 py-3 border-b border-slate-100 dark:border-slate-800/80 flex items-center justify-between bg-slate-50/70 dark:bg-slate-950/40">
            <div class="min-w-0 pr-2">
                <span class="text-[9px] font-bold text-purple-600 dark:text-purple-400 uppercase tracking-wider block">Candidate Face Closeup</span>
                <h4 id="candidate-face-lightbox-name" class="font-bold text-slate-900 dark:text-white text-sm truncate">
                    Candidate Portrait
                </h4>
            </div>
            <button type="button" onclick="closeCandidateFaceLightbox()"
                class="p-1.5 text-slate-400 hover:text-slate-700 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition"
                title="Back to Candidate Profile">
                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="p-3 bg-slate-950 flex items-center justify-center overflow-hidden">
            <img id="candidate-face-lightbox-img" src="" alt="Candidate Face Closeup"
                class="max-h-[65vh] w-auto max-w-full object-contain rounded-2xl shadow-xl">
        </div>

        <div class="px-4 py-2.5 border-t border-slate-100 dark:border-slate-800/80 bg-slate-50/50 dark:bg-slate-950/40 flex items-center justify-between">
            <span class="text-[10px] text-slate-400">Viewing within manage page</span>
            <button type="button" onclick="closeCandidateFaceLightbox()"
                class="px-3.5 py-1.5 text-xs font-semibold rounded-lg bg-slate-200/80 dark:bg-slate-800 hover:bg-slate-300 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 transition">
                Done
            </button>
        </div>
    </div>
</div>

<script>
    let currentAdminFaceUrl = '';
    let currentAdminFaceName = '';

    function openCandidateFaceLightbox() {
        if (!currentAdminFaceUrl) return;
        const lightbox = document.getElementById('candidate-face-lightbox');
        const content = document.getElementById('candidate-face-lightbox-content');
        const img = document.getElementById('candidate-face-lightbox-img');
        const nameEl = document.getElementById('candidate-face-lightbox-name');
        if (!lightbox || !img) return;

        img.src = currentAdminFaceUrl;
        img.alt = (currentAdminFaceName || 'Candidate') + ' Face Closeup';
        if (nameEl) nameEl.textContent = currentAdminFaceName || 'Candidate Portrait';

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

    function closeCandidateFaceLightbox() {
        const lightbox = document.getElementById('candidate-face-lightbox');
        const content = document.getElementById('candidate-face-lightbox-content');
        if (!lightbox) return;

        if (content) {
            content.classList.add('scale-95', 'opacity-0');
        }
        lightbox.classList.add('opacity-0');
        setTimeout(() => {
            lightbox.classList.add('hidden');
            lightbox.classList.remove('flex');
            const img = document.getElementById('candidate-face-lightbox-img');
            if (img) img.src = '';
        }, 250);
    }

    function isPosterUrlCheck(str) {
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

    function openCandidateProfileModal(avatarUrl, name, party, positionName, background) {
        const modal = document.getElementById('candidate-image-viewer-modal');
        const content = document.getElementById('candidate-image-viewer-content');
        const img = document.getElementById('candidate-viewer-image');
        const initialsEl = document.getElementById('candidate-viewer-initials');
        const nameEl = document.getElementById('candidate-viewer-name');
        const partyEl = document.getElementById('candidate-viewer-party');
        const posEl = document.getElementById('candidate-viewer-position');
        const faceBtn = document.getElementById('candidate-face-btn');

        const posterImg = document.getElementById('candidate-viewer-poster');
        const posterLink = document.getElementById('candidate-viewer-link');
        const posterImgLink = document.getElementById('candidate-poster-img-link');
        const posterEmpty = document.getElementById('candidate-poster-empty');
        const bgText = document.getElementById('candidate-viewer-background');

        if (!modal) return;

        currentAdminFaceUrl = (avatarUrl && avatarUrl.trim() !== '') ? avatarUrl.trim() : '';
        currentAdminFaceName = name || '';

        if (nameEl) nameEl.textContent = name || 'Candidate Profile';
        if (partyEl) {
            if (party && party !== 'Independent') {
                partyEl.textContent = party;
                partyEl.classList.remove('hidden');
            } else {
                partyEl.textContent = '';
                partyEl.classList.add('hidden');
            }
        }
        if (posEl) posEl.textContent = positionName || 'Candidate Profile';

        if (currentAdminFaceUrl) {
            if (img) {
                img.src = currentAdminFaceUrl;
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
                faceBtn.setAttribute('title', 'No portrait image uploaded');
            }
        }

        const trimmedBg = (background || '').trim();

        if (trimmedBg && isPosterUrlCheck(trimmedBg)) {
            if (posterImg) {
                posterImg.src = trimmedBg;
                posterImg.classList.remove('hidden');
            }
            if (posterLink) {
                posterLink.href = trimmedBg;
                posterLink.classList.remove('hidden');
            }
            if (posterImgLink) posterImgLink.href = trimmedBg;
            if (posterEmpty) posterEmpty.classList.add('hidden');
            if (bgText) bgText.classList.add('hidden');
        } else if (trimmedBg && !isPosterUrlCheck(trimmedBg) && trimmedBg !== 'No background details provided.') {
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

    // Backwards-compatibility wrapper
    function openCandidateImageViewer(url, name, party) {
        if (!url) return;
        if (isPosterUrlCheck(url)) {
            openCandidateProfileModal('', name, party, 'Candidate Profile', url);
        } else {
            openCandidateProfileModal(url, name, party, 'Candidate Profile', '');
        }
    }

    function closeCandidateImageViewer() {
        closeCandidateFaceLightbox();

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
            const posterImg = document.getElementById('candidate-viewer-poster');
            if (img) img.src = '';
            if (posterImg) posterImg.src = '';
            currentAdminFaceUrl = '';
            currentAdminFaceName = '';
        }, 300);
    }

    // Dismiss on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const faceLightbox = document.getElementById('candidate-face-lightbox');
            if (faceLightbox && !faceLightbox.classList.contains('hidden')) {
                closeCandidateFaceLightbox();
                return;
            }
            const modal = document.getElementById('candidate-image-viewer-modal');
            if (modal && !modal.classList.contains('hidden')) {
                closeCandidateImageViewer();
            }
        }
    });
</script>
