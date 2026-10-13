<!-- PREMIUM BACKDROP MODAL: VIEW VOTER PROFILE & BALLOT AUDIT -->
<div id="view-voter-modal"
    class="fixed inset-0 bg-slate-900/70 dark:bg-slate-950/90 backdrop-blur-[6px] z-50 hidden items-center justify-center p-4 transition-all duration-300 opacity-0">
    <div class="bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-100 dark:border-slate-800 max-w-lg w-full shadow-2xl flex flex-col max-h-[92vh] transition-all duration-300 transform scale-95 opacity-0 overflow-hidden"
        id="view-voter-modal-content">
        
        <!-- Header -->
        <div class="relative overflow-hidden px-6 py-5 sm:px-8 border-b border-slate-100 dark:border-slate-800/60 shrink-0 bg-gradient-to-r from-purple-50/50 via-transparent to-indigo-50/30 dark:from-purple-950/10 dark:to-transparent">
            <div class="relative flex justify-between items-center">
                <div>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-purple-500/10 dark:bg-purple-400/10 text-[10px] font-bold text-purple-600 dark:text-purple-400 uppercase tracking-wider mb-1">
                        <i class="fa-solid fa-id-badge text-[9px]"></i>
                        Voter Profile & Audit
                    </span>
                    <h3 class="font-bold text-slate-900 dark:text-white text-lg sm:text-xl leading-tight">
                        Participant Details
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                        Overview of voter registration and ballot activity.
                    </p>
                </div>
                
                <button type="button" onclick="closeViewVoterModal()"
                    class="text-slate-400 dark:text-slate-500 hover:text-slate-600 dark:hover:text-slate-200 p-2 hover:bg-slate-50 dark:hover:bg-slate-800/60 rounded-xl transition duration-150 active:scale-95">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <div class="p-6 sm:p-8 space-y-5 bg-slate-50/40 dark:bg-slate-950/20 flex-grow overflow-y-auto custom-scrollbar">
            <!-- Loading Indicator -->
            <div id="view-voter-loading" class="py-12 flex flex-col items-center justify-center gap-3 text-slate-400">
                <i class="fa-solid fa-circle-notch fa-spin text-2xl text-purple-600"></i>
                <span class="text-xs font-semibold">Loading voter record...</span>
            </div>

            <!-- Content Body (Populated dynamically) -->
            <div id="view-voter-body" class="hidden space-y-5">
                <!-- Profile Header Banner -->
                <div class="flex items-center gap-4 p-4 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-xs">
                    <div class="w-13 h-13 rounded-2xl bg-purple-600/10 text-purple-600 dark:text-purple-400 flex items-center justify-center font-bold text-lg shrink-0" id="view-voter-avatar-container">
                        <i class="fa-solid fa-user text-xl"></i>
                    </div>
                    <div class="min-w-0 flex-1">
                        <h4 class="font-bold text-base text-slate-900 dark:text-white truncate" id="view-voter-name">-</h4>
                        <p class="text-xs text-slate-500 dark:text-slate-400 truncate" id="view-voter-email">-</p>
                        <div class="flex items-center gap-2 mt-2">
                            <span id="view-voter-status-badge" class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold">
                                -
                            </span>
                            <span id="view-voter-division-badge" class="inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-semibold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400">
                                -
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Attributes Grid -->
                <div class="grid grid-cols-2 gap-3">
                    <div class="p-3.5 rounded-xl bg-white dark:bg-slate-900 border border-slate-200/70 dark:border-slate-800 shadow-2xs">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 block mb-1">Gender</span>
                        <span class="text-xs font-semibold text-slate-800 dark:text-slate-200" id="view-voter-gender">-</span>
                    </div>
                    <div class="p-3.5 rounded-xl bg-white dark:bg-slate-900 border border-slate-200/70 dark:border-slate-800 shadow-2xs">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 block mb-1">Age</span>
                        <span class="text-xs font-semibold text-slate-800 dark:text-slate-200" id="view-voter-age">-</span>
                    </div>
                    <div class="p-3.5 rounded-xl bg-white dark:bg-slate-900 border border-slate-200/70 dark:border-slate-800 shadow-2xs">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 block mb-1">Enrolled On</span>
                        <span class="text-xs font-semibold text-slate-800 dark:text-slate-200" id="view-voter-created-at">-</span>
                    </div>
                    <div class="p-3.5 rounded-xl bg-white dark:bg-slate-900 border border-slate-200/70 dark:border-slate-800 shadow-2xs">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 block mb-1">Ballot Cast Time</span>
                        <span class="text-xs font-semibold text-slate-800 dark:text-slate-200" id="view-voter-voted-at">-</span>
                    </div>
                </div>

                <!-- Ballot Cast Audit Section -->
                <div class="space-y-2">
                    <div class="flex items-center justify-between pb-1">
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 flex items-center gap-1.5">
                            <i class="fa-solid fa-check-to-slot text-purple-600 dark:text-purple-400"></i>
                            Ballot Selections Audit
                        </span>
                        <span class="text-[10px] font-semibold text-slate-400" id="view-voter-votes-count">-</span>
                    </div>

                    <div id="view-voter-votes-list" class="space-y-2 max-h-48 overflow-y-auto custom-scrollbar p-1">
                        <!-- Populated dynamically via JS -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/40 flex items-center justify-between shrink-0">
            <button type="button" onclick="switchToEditFromView()"
                class="text-xs font-semibold py-2.5 px-4 rounded-xl border border-amber-200 dark:border-amber-800/80 bg-amber-500/10 text-amber-700 dark:text-amber-400 hover:bg-amber-500 hover:text-white transition flex items-center gap-1.5">
                <i class="fa-solid fa-pen text-[10px]"></i>
                <span>Edit Voter</span>
            </button>
            <button type="button" onclick="closeViewVoterModal()"
                class="text-xs font-semibold py-2.5 px-5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition">
                Close
            </button>
        </div>
    </div>
</div>
