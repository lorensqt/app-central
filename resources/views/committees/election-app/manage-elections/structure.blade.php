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
                                <!-- Avatar -->
                                @if($candidate->avatar_path)
                                    <img src="{{ $candidate->avatar_path }}" alt="{{ $candidate->name }}" class="w-11 h-11 rounded-full object-cover shrink-0 border border-slate-200 dark:border-slate-850">
                                @else
                                    <div class="w-11 h-11 rounded-full bg-purple-50 dark:bg-purple-950 text-purple-600 dark:text-purple-400 font-bold flex items-center justify-center shrink-0 border border-purple-100/30 text-sm uppercase">
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
