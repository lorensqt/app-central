<!-- PREMIUM BACKDROP MODAL: EDIT REGISTRATION DETAILS -->
<div id="edit-registration-modal"
    class="fixed inset-0 bg-slate-900/70 dark:bg-slate-950/90 backdrop-blur-[6px] z-[160] hidden items-center justify-center p-4 transition-all duration-300 opacity-0"
    style="top: 0 !important; left: 0 !important; right: 0 !important; bottom: 0 !important; margin: 0 !important;">
    <div class="bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-100 dark:border-slate-800 max-w-xl w-full shadow-2xl flex flex-col max-h-[92vh] transition-all duration-300 transform scale-95 opacity-0 overflow-hidden"
        id="edit-registration-modal-content">
        
        <!-- Premium Header Panel (Visual Splendor with Subtle Gradients) -->
        <div class="relative overflow-hidden px-6 py-5 sm:px-8 border-b border-slate-100 dark:border-slate-800/60 shrink-0 bg-gradient-to-r from-purple-50/50 via-transparent to-indigo-50/30 dark:from-purple-950/10 dark:to-transparent">
            <!-- Decorative blur balls for background interest -->
            <div class="absolute -top-12 -left-12 w-32 h-32 bg-purple-400/10 rounded-full blur-2xl pointer-events-none"></div>
            <div class="absolute -bottom-12 -right-12 w-32 h-32 bg-indigo-400/10 rounded-full blur-2xl pointer-events-none"></div>
            
            <div class="relative flex justify-between items-center">
                <div>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-purple-500/10 dark:bg-purple-400/10 text-[10px] font-bold text-purple-600 dark:text-purple-400 uppercase tracking-widest mb-2">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Admin Control Deck
                    </span>
                    <h3 class="font-bold text-slate-900 dark:text-white text-xl leading-tight tracking-tight">
                        Edit Registration Info
                    </h3>
                    <p class="text-xs text-slate-550 dark:text-slate-400 mt-1">
                        Manually update registration details, division assignments, and questionnaire answers.
                    </p>
                </div>
                
                <button type="button" onclick="closeEditRegistrationModal()"
                    class="text-slate-400 dark:text-slate-500 hover:text-slate-600 dark:hover:text-slate-200 p-2 hover:bg-slate-50 dark:hover:bg-slate-800/60 rounded-xl transition duration-150 active:scale-95 shadow-sm border border-transparent hover:border-slate-100 dark:hover:border-slate-700/50">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <form id="edit-registration-form" action="" method="POST" class="flex-grow flex flex-col min-h-0">
            @csrf
            @method('PUT')
            
            <!-- Scrollable Premium Form Console -->
            <div class="flex-grow overflow-y-auto custom-scrollbar p-6 sm:p-8 space-y-6 bg-slate-50/40 dark:bg-slate-950/20">
                
                <!-- Section 1: Personal Details -->
                <div class="space-y-4">
                    <div class="flex items-center gap-2 pb-1 border-b border-slate-100 dark:border-slate-800/60">
                        <span class="flex items-center justify-center w-5 h-5 rounded-full bg-purple-500/10 text-purple-600 dark:text-purple-400 font-bold text-[10px]">1</span>
                        <span class="text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Personal Details</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Full Name -->
                        <div class="space-y-1.5">
                            <label for="edit_reg_name"
                                class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Full Name</label>
                            <input type="text" name="name" id="edit_reg_name" required
                                placeholder="e.g. John Doe"
                                class="w-full rounded-xl border border-slate-200 dark:border-slate-800/80 py-3 px-4 text-slate-800 dark:text-slate-200 text-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-white dark:bg-slate-950 shadow-sm transition-all duration-300">
                        </div>

                        <!-- Email Address -->
                        <div class="space-y-1.5">
                            <label for="edit_reg_email"
                                class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Email Address</label>
                            <input type="email" name="email" id="edit_reg_email" required
                                placeholder="e.g. johndoe@example.com"
                                class="w-full rounded-xl border border-slate-200 dark:border-slate-800/80 py-3 px-4 text-slate-800 dark:text-slate-200 text-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-white dark:bg-slate-950 shadow-sm transition-all duration-300">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Gender -->
                        <div class="space-y-1.5">
                            <label for="edit_reg_gender"
                                class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Gender</label>
                            <select name="gender" id="edit_reg_gender"
                                class="w-full rounded-xl border border-slate-200 dark:border-slate-800/80 py-3 px-4 text-slate-800 dark:text-slate-200 text-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-white dark:bg-slate-950 shadow-sm transition-all duration-300">
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="LGBTQ+">LGBTQ+</option>
                                <option value="Others">Others</option>
                                <option value="Unspecified">Unspecified</option>
                            </select>
                        </div>

                        <!-- Birthday -->
                        <div class="space-y-1.5">
                            <label for="edit_reg_birthday"
                                class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Birth Date</label>
                            <input type="date" name="birthday" id="edit_reg_birthday"
                                class="w-full rounded-xl border border-slate-200 dark:border-slate-800/80 py-3 px-4 text-slate-800 dark:text-slate-200 text-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-white dark:bg-slate-950 shadow-sm transition-all duration-300">
                        </div>
                    </div>
                </div>

                <!-- Section 2: Division & Ticket Code Logistics -->
                <div class="space-y-4">
                    <div class="flex items-center gap-2 pb-1 border-b border-slate-100 dark:border-slate-800/60">
                        <span class="flex items-center justify-center w-5 h-5 rounded-full bg-purple-500/10 text-purple-600 dark:text-purple-400 font-bold text-[10px]">2</span>
                        <span class="text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Event Logistics</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Division -->
                        <div class="space-y-1.5">
                            <label for="edit_reg_division"
                                class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Division Placement</label>
                            <select name="division" id="edit_reg_division" onchange="handleDivisionInputSwitch()"
                                class="w-full rounded-xl border border-slate-200 dark:border-slate-800/80 py-3 px-4 text-slate-800 dark:text-slate-200 text-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-white dark:bg-slate-950 shadow-sm transition-all duration-300">
                                <option value="">Unspecified</option>
                                @foreach(array_keys($divisions) as $divName)
                                    @if($divName !== 'Unspecified')
                                        <option value="{{ $divName }}">{{ $divName }}</option>
                                    @endif
                                @endforeach
                                <option value="__custom__">Custom Division...</option>
                            </select>

                            <!-- Custom Division Input (Hidden by default) -->
                            <div id="edit_reg_division_custom_wrapper" class="hidden mt-2.5 animate-fade-in">
                                <input type="text" id="edit_reg_division_custom" placeholder="Type custom division..."
                                    class="w-full rounded-xl border border-slate-200 dark:border-slate-800/80 py-3 px-4 text-slate-850 dark:text-slate-200 text-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-white dark:bg-slate-950 shadow-sm transition-all duration-300">
                            </div>
                        </div>

                        <!-- Ticket Code -->
                        <div class="space-y-1.5">
                            <label for="edit_reg_ticket_code"
                                class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Ticket Code</label>
                            <input type="text" name="ticket_code" id="edit_reg_ticket_code"
                                placeholder="e.g. TC-123456"
                                class="w-full rounded-xl border border-slate-200 dark:border-slate-800/80 py-3 px-4 text-slate-850 dark:text-slate-200 text-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-white dark:bg-slate-950 shadow-sm transition-all duration-300">
                        </div>
                    </div>
                </div>

                <!-- Section 3: Dynamic Questionnaire Answers -->
                <div class="space-y-4">
                    <div class="flex items-center gap-2 pb-1 border-b border-slate-100 dark:border-slate-800/60">
                        <span class="flex items-center justify-center w-5 h-5 rounded-full bg-purple-500/10 text-purple-600 dark:text-purple-400 font-bold text-[10px]">3</span>
                        <span class="text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Questionnaire Answers</span>
                    </div>

                    <!-- Dynamic form inputs loaded via JavaScript -->
                    <div id="edit-custom-fields-container" class="space-y-4">
                        <!-- JS populated fields -->
                    </div>
                </div>
            </div>

            <!-- Footer Action Bar -->
            <div class="px-6 py-5 sm:px-8 bg-slate-50 dark:bg-slate-950 border-t border-slate-100 dark:border-slate-800/60 flex items-center justify-end gap-3 shrink-0">
                <button type="button" onclick="closeEditRegistrationModal()"
                    class="text-xs font-bold text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 px-5 py-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800/80 transition-all duration-150 active:scale-[0.98] shadow-sm">
                    Cancel
                </button>
                <button type="submit"
                    class="inline-flex items-center gap-2 text-xs font-bold py-3 px-6 rounded-xl bg-purple-600 hover:bg-purple-700 text-white transition-all duration-150 shadow-sm hover:shadow-md active:scale-[0.98] focus:outline-none">
                    <svg class="w-4 h-4 text-purple-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
