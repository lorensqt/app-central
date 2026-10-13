<!-- PREMIUM BACKDROP MODAL: ENROLL VOTER -->
<div id="create-voter-modal"
    class="fixed inset-0 bg-slate-900/70 dark:bg-slate-950/90 backdrop-blur-[6px] z-50 hidden items-center justify-center p-4 transition-all duration-300 opacity-0">
    <div class="bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-100 dark:border-slate-800 max-w-lg w-full shadow-2xl flex flex-col max-h-[92vh] transition-all duration-300 transform scale-95 opacity-0 overflow-hidden"
        id="create-voter-modal-content">
        
        <!-- Header -->
        <div class="relative overflow-hidden px-6 py-5 sm:px-8 border-b border-slate-100 dark:border-slate-800/60 shrink-0 bg-gradient-to-r from-purple-50/50 via-transparent to-indigo-50/30 dark:from-purple-950/10 dark:to-transparent">
            <div class="relative flex justify-between items-center">
                <div>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-purple-500/10 dark:bg-purple-400/10 text-[10px] font-bold text-purple-600 dark:text-purple-400 uppercase tracking-wider mb-1">
                        <i class="fa-solid fa-user-plus text-[9px]"></i>
                        Voter Enrollment
                    </span>
                    <h3 class="font-bold text-slate-900 dark:text-white text-lg sm:text-xl leading-tight">
                        Enroll New Voter
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                        Add an authorized participant to this election's voter register.
                    </p>
                </div>
                
                <button type="button" onclick="closeCreateVoterModal()"
                    class="text-slate-400 dark:text-slate-500 hover:text-slate-600 dark:hover:text-slate-200 p-2 hover:bg-slate-50 dark:hover:bg-slate-800/60 rounded-xl transition duration-150 active:scale-95">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <form id="create-voter-form" onsubmit="submitCreateVoter(event)" class="flex-grow flex flex-col min-h-0">
            @csrf
            
            <div class="p-6 sm:p-8 space-y-4 bg-slate-50/40 dark:bg-slate-950/20 flex-grow overflow-y-auto custom-scrollbar">
                <!-- Full Name -->
                <div class="space-y-1.5">
                    <label for="create_voter_name" class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                        Full Name <span class="text-slate-400 text-[10px] font-normal lowercase">(optional)</span>
                    </label>
                    <input type="text" name="name" id="create_voter_name"
                        placeholder="e.g. Juan dela Cruz"
                        class="w-full rounded-xl border border-slate-200 dark:border-slate-800/80 py-3 px-4 text-slate-800 dark:text-slate-200 text-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-white dark:bg-slate-950 shadow-sm transition">
                </div>

                <!-- Email Address -->
                <div class="space-y-1.5">
                    <label for="create_voter_email" class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                        Email Address <span class="text-rose-500">*</span>
                    </label>
                    <input type="email" name="email" id="create_voter_email" required
                        placeholder="e.g. juan@mlhuillier.com"
                        class="w-full rounded-xl border border-slate-200 dark:border-slate-800/80 py-3 px-4 text-slate-800 dark:text-slate-200 text-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-white dark:bg-slate-950 shadow-sm transition">
                    <p class="text-[10px] text-slate-400 dark:text-slate-500">
                        Must be unique to this election. If the voter already has a system account, their profile will link automatically.
                    </p>
                </div>

                <!-- Division / Workplace with datalist -->
                <div class="space-y-1.5">
                    <label for="create_voter_division" class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                        Division / Workplace
                    </label>
                    <input type="text" name="division" id="create_voter_division" list="create-voter-division-list"
                        placeholder="Select or type workplace division..."
                        class="w-full rounded-xl border border-slate-200 dark:border-slate-800/80 py-3 px-4 text-slate-800 dark:text-slate-200 text-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-white dark:bg-slate-950 shadow-sm transition">
                    <datalist id="create-voter-division-list">
                        @foreach($divisions ?? [] as $div)
                            <option value="{{ $div }}"></option>
                        @endforeach
                    </datalist>
                </div>

                <!-- Gender & Age 2-Column Row -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Gender -->
                    <div class="space-y-1.5">
                        <label for="create_voter_gender" class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                            Gender
                        </label>
                        <select name="gender" id="create_voter_gender"
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
                        <label for="create_voter_age" class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                            Age
                        </label>
                        <input type="number" name="age" id="create_voter_age" min="18" max="120"
                            placeholder="e.g. 30"
                            class="w-full rounded-xl border border-slate-200 dark:border-slate-800/80 py-3 px-4 text-slate-800 dark:text-slate-200 text-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-white dark:bg-slate-950 shadow-sm transition">
                    </div>
                </div>

                <!-- Initial Voting Status -->
                <div class="space-y-1.5 pt-1">
                    <label for="create_voter_status" class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                        Initial Status
                    </label>
                    <select name="status" id="create_voter_status"
                        class="w-full rounded-xl border border-slate-200 dark:border-slate-800/80 py-3 px-4 text-slate-800 dark:text-slate-200 text-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-white dark:bg-slate-950 shadow-sm transition">
                        <option value="in_queue" selected>In Queue (Eligible to Vote)</option>
                        <option value="voted">Mark as Voted (Manual Ballot Cast)</option>
                    </select>
                </div>
            </div>

            <!-- Footer -->
            <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/40 flex items-center justify-end gap-3 shrink-0">
                <button type="button" onclick="closeCreateVoterModal()"
                    class="text-xs font-semibold py-3 px-5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition">
                    Cancel
                </button>
                <button type="submit" id="btn-submit-create-voter"
                    class="text-xs font-semibold py-3 px-6 rounded-xl bg-purple-600 hover:bg-purple-700 text-white transition shadow-md flex items-center gap-2">
                    <i class="fa-solid fa-user-check text-xs"></i>
                    <span>Enroll Voter</span>
                </button>
            </div>
        </form>
    </div>
</div>
