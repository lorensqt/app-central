<!-- PREMIUM BACKDROP MODAL: CREATE ELECTION -->
<div id="create-election-modal"
    class="fixed inset-0 bg-slate-900/70 dark:bg-slate-950/90 backdrop-blur-[6px] z-50 hidden items-center justify-center p-4 transition-all duration-300 opacity-0">
    <div class="bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-100 dark:border-slate-800 max-w-2xl w-full shadow-2xl flex flex-col max-h-[92vh] transition-all duration-300 transform scale-95 opacity-0 overflow-hidden"
        id="create-election-modal-content">
        
        <!-- Header -->
        <div class="relative overflow-hidden px-6 py-5 sm:px-8 border-b border-slate-100 dark:border-slate-800/60 shrink-0 bg-gradient-to-r from-purple-50/50 via-transparent to-indigo-50/30 dark:from-purple-950/10 dark:to-transparent">
            <div class="absolute -top-12 -left-12 w-32 h-32 bg-purple-400/10 rounded-full blur-2xl pointer-events-none"></div>
            <div class="absolute -bottom-12 -right-12 w-32 h-32 bg-indigo-400/10 rounded-full blur-2xl pointer-events-none"></div>
            
            <div class="relative flex justify-between items-center">
                <div>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-purple-500/10 dark:bg-purple-400/10 text-[10px] font-bold text-purple-600 dark:text-purple-400 uppercase tracking-widest mb-2">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Elections Manager
                    </span>
                    <h3 class="font-bold text-slate-900 dark:text-white text-xl sm:text-2xl leading-tight tracking-tight">
                        Create New Election
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                        Configure a secure, internal election portal for M Lhuillier.
                    </p>
                </div>
                
                <button type="button" onclick="closeCreateModal()"
                    class="text-slate-400 dark:text-slate-500 hover:text-slate-600 dark:hover:text-slate-200 p-2 hover:bg-slate-50 dark:hover:bg-slate-800/60 rounded-xl transition duration-150 active:scale-95 shadow-sm border border-transparent hover:border-slate-100 dark:hover:border-slate-700/50">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <form id="create-election-form" onsubmit="submitCreateElection(event)" class="flex-grow flex flex-col min-h-0">
            @csrf
            
            <!-- Scrollable Form Content -->
            <div class="flex-grow overflow-y-auto custom-scrollbar p-6 sm:p-8 space-y-5 bg-slate-50/40 dark:bg-slate-950/20">
                <!-- Election Title -->
                <div class="space-y-1.5">
                    <label for="create_title" class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Election Title</label>
                    <input type="text" name="title" id="create_title" required
                        placeholder="e.g. Committee Officers Election 2026"
                        class="w-full rounded-xl border border-slate-200 dark:border-slate-800/80 py-3 px-4 text-slate-800 dark:text-slate-200 text-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-white dark:bg-slate-950 shadow-sm transition-all duration-300">
                </div>

                <!-- Description -->
                <div class="space-y-1.5">
                    <label for="create_description" class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Description</label>
                    <textarea name="description" id="create_description" rows="3"
                        placeholder="Provide details about the election, voting guidelines, etc..."
                        class="w-full rounded-xl border border-slate-200 dark:border-slate-800/80 py-3 px-4 text-slate-800 dark:text-slate-200 text-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-white dark:bg-slate-950 shadow-sm transition-all duration-300 custom-scrollbar"></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Start Date -->
                    <div class="space-y-1.5">
                        <label for="create_start_date" class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Start Date & Time (Optional)</label>
                        <input type="datetime-local" name="start_date" id="create_start_date"
                            class="w-full rounded-xl border border-slate-200 dark:border-slate-800/80 py-3 px-4 text-slate-800 dark:text-slate-200 text-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-white dark:bg-slate-950 shadow-sm transition-all duration-300">
                    </div>

                    <!-- End Date -->
                    <div class="space-y-1.5">
                        <label for="create_end_date" class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">End Date & Time (Optional)</label>
                        <input type="datetime-local" name="end_date" id="create_end_date"
                            class="w-full rounded-xl border border-slate-200 dark:border-slate-800/80 py-3 px-4 text-slate-800 dark:text-slate-200 text-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-white dark:bg-slate-950 shadow-sm transition-all duration-300">
                    </div>
                </div>

                <!-- Status -->
                <div class="space-y-1.5">
                    <label for="create_status" class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Status</label>
                    <select name="status" id="create_status" required
                        class="w-full rounded-xl border border-slate-200 dark:border-slate-800/80 py-3 px-4 text-slate-800 dark:text-slate-200 text-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-white dark:bg-slate-950 shadow-sm transition-all duration-300">
                        <option value="draft" selected>Draft (Hidden from public, customizable)</option>
                        <option value="active">Active (Visible for public voting)</option>
                        <option value="closed">Closed (Voting ended, results visible)</option>
                    </select>
                </div>
            </div>

            <!-- Footer / Actions -->
            <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/40 flex items-center justify-end gap-3 shrink-0">
                <button type="button" onclick="closeCreateModal()"
                    class="text-xs font-semibold py-3 px-5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 hover:bg-slate-50 dark:hover:bg-slate-900 text-slate-700 dark:text-slate-300 transition duration-150 shadow-sm">
                    Cancel
                </button>
                <button type="submit"
                    class="text-xs font-semibold py-3 px-6 rounded-xl bg-purple-600 hover:bg-purple-700 text-white transition duration-150 shadow-md">
                    Create Election
                </button>
            </div>
        </form>
    </div>
</div>
