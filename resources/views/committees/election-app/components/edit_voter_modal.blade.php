<!-- PREMIUM BACKDROP MODAL: EDIT VOTER -->
<div id="edit-voter-modal"
    class="fixed inset-0 bg-slate-900/70 dark:bg-slate-950/90 backdrop-blur-[6px] z-50 hidden items-center justify-center p-4 transition-all duration-300 opacity-0">
    <div class="bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-100 dark:border-slate-800 max-w-lg w-full shadow-2xl flex flex-col max-h-[92vh] transition-all duration-300 transform scale-95 opacity-0 overflow-hidden"
        id="edit-voter-modal-content">
        
        <!-- Header -->
        <div class="relative overflow-hidden px-6 py-5 sm:px-8 border-b border-slate-100 dark:border-slate-800/60 shrink-0 bg-gradient-to-r from-amber-50/50 via-transparent to-purple-50/30 dark:from-amber-950/10 dark:to-transparent">
            <div class="relative flex justify-between items-center">
                <div>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-amber-500/10 dark:bg-amber-400/10 text-[10px] font-bold text-amber-600 dark:text-amber-400 uppercase tracking-wider mb-1">
                        <i class="fa-solid fa-user-pen text-[9px]"></i>
                        Voter Record
                    </span>
                    <h3 class="font-bold text-slate-900 dark:text-white text-lg sm:text-xl leading-tight">
                        Edit Voter Details
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                        Update profile, division assignment, or reset voting status.
                    </p>
                </div>
                
                <button type="button" onclick="closeEditVoterModal()"
                    class="text-slate-400 dark:text-slate-500 hover:text-slate-600 dark:hover:text-slate-200 p-2 hover:bg-slate-50 dark:hover:bg-slate-800/60 rounded-xl transition duration-150 active:scale-95">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <form id="edit-voter-form" onsubmit="submitEditVoter(event)" class="flex-grow flex flex-col min-h-0">
            @csrf
            @method('PUT')
            
            <input type="hidden" name="voter_id" id="edit_voter_id">
            
            <div class="p-6 sm:p-8 space-y-4 bg-slate-50/40 dark:bg-slate-950/20 flex-grow overflow-y-auto custom-scrollbar">
                <!-- Full Name -->
                <div class="space-y-1.5">
                    <label for="edit_voter_name" class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                        Full Name
                    </label>
                    <input type="text" name="name" id="edit_voter_name"
                        placeholder="e.g. Juan dela Cruz"
                        class="w-full rounded-xl border border-slate-200 dark:border-slate-800/80 py-3 px-4 text-slate-800 dark:text-slate-200 text-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-white dark:bg-slate-950 shadow-sm transition">
                </div>

                <!-- Email Address -->
                <div class="space-y-1.5">
                    <label for="edit_voter_email" class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                        Email Address <span class="text-rose-500">*</span>
                    </label>
                    <input type="email" name="email" id="edit_voter_email" required
                        placeholder="e.g. juan@mlhuillier.com"
                        class="w-full rounded-xl border border-slate-200 dark:border-slate-800/80 py-3 px-4 text-slate-800 dark:text-slate-200 text-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-white dark:bg-slate-950 shadow-sm transition">
                </div>

                <!-- Division / Workplace with datalist -->
                <div class="space-y-1.5">
                    <label for="edit_voter_division" class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                        Division / Workplace
                    </label>
                    <input type="text" name="division" id="edit_voter_division" list="edit-voter-division-list"
                        placeholder="Select or type workplace division..."
                        class="w-full rounded-xl border border-slate-200 dark:border-slate-800/80 py-3 px-4 text-slate-800 dark:text-slate-200 text-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-white dark:bg-slate-950 shadow-sm transition">
                    <datalist id="edit-voter-division-list">
                        @foreach($divisions ?? [] as $div)
                            <option value="{{ $div }}"></option>
                        @endforeach
                    </datalist>
                </div>

                <!-- Gender & Age 2-Column Row -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Gender -->
                    <div class="space-y-1.5">
                        <label for="edit_voter_gender" class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                            Gender
                        </label>
                        <select name="gender" id="edit_voter_gender"
                            class="w-full rounded-xl border border-slate-200 dark:border-slate-800/80 py-3 px-4 text-slate-800 dark:text-slate-200 text-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-white dark:bg-slate-950 shadow-sm transition">
                            <option value="">-- Select Gender --</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="LGBTQ">LGBTQ</option>
                            <option value="Others">Others</option>
                        </select>
                    </div>

                    <!-- Age -->
                    <div class="space-y-1.5">
                        <label for="edit_voter_age" class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                            Age
                        </label>
                        <input type="number" name="age" id="edit_voter_age" min="18" max="120"
                            placeholder="e.g. 30"
                            class="w-full rounded-xl border border-slate-200 dark:border-slate-800/80 py-3 px-4 text-slate-800 dark:text-slate-200 text-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-white dark:bg-slate-950 shadow-sm transition">
                    </div>
                </div>

                <!-- Voting Status Management with warning note -->
                <div class="space-y-1.5 pt-1">
                    <label for="edit_voter_status" class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                        Voting Status
                    </label>
                    <select name="status" id="edit_voter_status" onchange="onEditVoterStatusChange(this.value)"
                        class="w-full rounded-xl border border-slate-200 dark:border-slate-800/80 py-3 px-4 text-slate-800 dark:text-slate-200 text-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-white dark:bg-slate-950 shadow-sm transition">
                        <option value="in_queue">In Queue (Eligible to Vote)</option>
                        <option value="voted">Voted (Ballot Recorded)</option>
                    </select>
                    <div id="edit-voter-reset-warning" class="hidden p-3 rounded-xl bg-amber-500/10 border border-amber-500/20 text-[11px] text-amber-700 dark:text-amber-300 flex items-start gap-2 mt-2">
                        <i class="fa-solid fa-triangle-exclamation text-amber-500 mt-0.5 shrink-0"></i>
                        <span>
                            <strong>Note:</strong> Resetting status to "In Queue" will clear any recorded ballot votes for this voter, allowing them to cast their ballot again.
                        </span>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/40 flex items-center justify-end gap-3 shrink-0">
                <button type="button" onclick="closeEditVoterModal()"
                    class="text-xs font-semibold py-3 px-5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition">
                    Cancel
                </button>
                <button type="submit" id="btn-submit-edit-voter"
                    class="text-xs font-semibold py-3 px-6 rounded-xl bg-purple-600 hover:bg-purple-700 text-white transition shadow-md flex items-center gap-2">
                    <i class="fa-solid fa-check text-xs"></i>
                    <span>Save Changes</span>
                </button>
            </div>
        </form>
    </div>
</div>
