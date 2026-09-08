<!-- PREMIUM BACKDROP MODAL: ADD POSITION -->
<div id="create-position-modal"
    class="fixed inset-0 bg-slate-900/70 dark:bg-slate-950/90 backdrop-blur-[6px] z-50 hidden items-center justify-center p-4 transition-all duration-300 opacity-0">
    <div class="bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-100 dark:border-slate-800 max-w-lg w-full shadow-2xl flex flex-col max-h-[92vh] transition-all duration-300 transform scale-95 opacity-0 overflow-hidden"
        id="create-position-modal-content">
        
        <!-- Header -->
        <div class="relative overflow-hidden px-6 py-5 sm:px-8 border-b border-slate-100 dark:border-slate-800/60 shrink-0 bg-gradient-to-r from-purple-50/50 via-transparent to-indigo-50/30 dark:from-purple-950/10 dark:to-transparent">
            <div class="relative flex justify-between items-center">
                <div>
                    <h3 class="font-bold text-slate-900 dark:text-white text-lg sm:text-xl leading-tight">
                        Add New Position
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                        Define an official leadership or committee role.
                    </p>
                </div>
                
                <button type="button" onclick="closeCreatePositionModal()"
                    class="text-slate-400 dark:text-slate-500 hover:text-slate-600 dark:hover:text-slate-200 p-2 hover:bg-slate-50 dark:hover:bg-slate-800/60 rounded-xl transition duration-150 active:scale-95">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <form id="create-position-form" onsubmit="submitCreatePosition(event)" class="flex-grow flex flex-col min-h-0">
            @csrf
            
            <div class="p-6 sm:p-8 space-y-4 bg-slate-50/40 dark:bg-slate-950/20 flex-grow overflow-y-auto custom-scrollbar">
                <!-- Position Name -->
                <div class="space-y-1.5">
                    <label for="pos_name" class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Position Name</label>
                    <input type="text" name="name" id="pos_name" required
                        placeholder="e.g. Chairman, Secretary-General"
                        class="w-full rounded-xl border border-slate-200 dark:border-slate-800/80 py-3 px-4 text-slate-800 dark:text-slate-200 text-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-white dark:bg-slate-950 shadow-sm transition">
                </div>

                <!-- Max Votes -->
                <div class="space-y-1.5">
                    <label for="pos_max_votes" class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Max Selections (Voter Limit)</label>
                    <input type="number" name="max_votes" id="pos_max_votes" required min="1" value="1"
                        class="w-full rounded-xl border border-slate-200 dark:border-slate-800/80 py-3 px-4 text-slate-800 dark:text-slate-200 text-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-white dark:bg-slate-950 shadow-sm transition">
                    <p class="text-[10px] text-slate-400 dark:text-slate-500 leading-relaxed">
                        Specify how many candidates a voter can select for this position (e.g., choose up to 3 directors).
                    </p>
                </div>

                <!-- Sort Order -->
                <div class="space-y-1.5">
                    <label for="pos_sort_order" class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Display Order (Optional)</label>
                    <input type="number" name="sort_order" id="pos_sort_order" placeholder="Default sorting"
                        class="w-full rounded-xl border border-slate-200 dark:border-slate-800/80 py-3 px-4 text-slate-800 dark:text-slate-200 text-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-white dark:bg-slate-950 shadow-sm transition">
                </div>
            </div>

            <!-- Footer -->
            <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/40 flex items-center justify-end gap-3 shrink-0">
                <button type="button" onclick="closeCreatePositionModal()"
                    class="text-xs font-semibold py-3 px-5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-slate-700 dark:text-slate-300 hover:bg-slate-50 transition">
                    Cancel
                </button>
                <button type="submit"
                    class="text-xs font-semibold py-3 px-6 rounded-xl bg-purple-600 hover:bg-purple-700 text-white transition shadow-md">
                    Add Position
                </button>
            </div>
        </form>
    </div>
</div>
