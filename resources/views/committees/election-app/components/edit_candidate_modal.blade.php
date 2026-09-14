<!-- PREMIUM BACKDROP MODAL: EDIT CANDIDATE -->
<div id="edit-candidate-modal"
    class="fixed inset-0 bg-slate-900/70 dark:bg-slate-950/90 backdrop-blur-[6px] z-50 hidden items-center justify-center p-4 transition-all duration-300 opacity-0">
    <div class="bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-100 dark:border-slate-800 max-w-2xl w-full shadow-2xl flex flex-col max-h-[92vh] transition-all duration-300 transform scale-95 opacity-0 overflow-hidden"
        id="edit-candidate-modal-content">
        
        <!-- Header -->
        <div class="relative overflow-hidden px-6 py-5 sm:px-8 border-b border-slate-100 dark:border-slate-800/60 shrink-0 bg-gradient-to-r from-purple-50/50 via-transparent to-indigo-50/30 dark:from-purple-950/10 dark:to-transparent">
            <div class="relative flex justify-between items-center">
                <div>
                    <h3 class="font-bold text-slate-900 dark:text-white text-lg sm:text-xl leading-tight">
                        Edit Candidate
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                        Update candidate properties and ballot sorting.
                    </p>
                </div>
                
                <button type="button" onclick="closeEditCandidateModal()"
                    class="text-slate-400 dark:text-slate-500 hover:text-slate-600 dark:hover:text-slate-200 p-2 hover:bg-slate-50 dark:hover:bg-slate-800/60 rounded-xl transition duration-150 active:scale-95">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <form id="edit-candidate-form" enctype="multipart/form-data" onsubmit="submitEditCandidate(event)" class="flex-grow flex flex-col min-h-0">
            @csrf
            @method('PUT')
            
            <input type="hidden" id="edit_candidate_id" name="id">
            
            <div class="p-6 sm:p-8 bg-slate-50/40 dark:bg-slate-950/20 flex-grow overflow-y-auto custom-scrollbar">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                    <!-- Left Side: Form inputs (7 cols) -->
                    <div class="md:col-span-7 space-y-4">
                        <!-- Candidate Name -->
                        <div class="space-y-1.5">
                            <label for="edit_cand_name" class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Candidate Name</label>
                            <input type="text" name="name" id="edit_cand_name" required
                                placeholder="e.g. Juan dela Cruz"
                                class="w-full rounded-xl border border-slate-200 dark:border-slate-800/80 py-3 px-4 text-slate-800 dark:text-slate-200 text-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-white dark:bg-slate-950 shadow-sm transition">
                        </div>

                        <!-- Party Affiliation -->
                        <div class="space-y-1.5">
                            <label for="edit_cand_party" class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Party / Alliance (Optional)</label>
                            <input type="text" name="party_affiliation" id="edit_cand_party"
                                placeholder="e.g. Reform Alliance, Independent"
                                class="w-full rounded-xl border border-slate-200 dark:border-slate-800/80 py-3 px-4 text-slate-800 dark:text-slate-200 text-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-white dark:bg-slate-950 shadow-sm transition">
                        </div>

                        <!-- Sort Order -->
                        <div class="space-y-1.5">
                            <label for="edit_cand_sort_order" class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Ballot Order</label>
                            <input type="number" name="sort_order" id="edit_cand_sort_order" required
                                class="w-full rounded-xl border border-slate-200 dark:border-slate-800/80 py-3 px-4 text-slate-800 dark:text-slate-200 text-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-white dark:bg-slate-950 shadow-sm transition">
                        </div>
                    </div>

                    <!-- Right Side: Live Image Preview & Upload Controls (5 cols) -->
                    <div class="md:col-span-5 flex flex-col items-center justify-start space-y-4 border-t md:border-t-0 md:border-l border-slate-100 dark:border-slate-800/60 pt-6 md:pt-0 md:pl-6">
                        <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider self-start md:self-center">Candidate Portrait</label>
                        
                        <!-- Premium Interactive Live Image Previewer -->
                        <div class="relative group w-32 h-32 rounded-full overflow-hidden border-4 border-purple-100 dark:border-purple-950/40 shadow-md bg-slate-100 dark:bg-slate-950 flex items-center justify-center shrink-0">
                            <!-- Live Preview Image Element -->
                            <img id="edit_cand_avatar_preview" src="https://api.dicebear.com/7.x/adventurer/svg?seed=placeholder" class="w-full h-full object-cover" alt="Avatar Preview">
                            
                            <!-- Loading Spinner Overlay -->
                            <div id="edit_avatar_loader" class="absolute inset-0 bg-slate-900/60 backdrop-blur-[2px] hidden flex flex-col items-center justify-center text-white transition-opacity duration-200">
                                <svg class="animate-spin h-7 w-7 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span class="text-[9px] font-bold tracking-wider uppercase mt-1.5 text-purple-200">Saving...</span>
                            </div>
                        </div>

                        <!-- Inputs box -->
                        <div class="w-full space-y-3">
                            <!-- File Upload Option -->
                            <div class="space-y-1">
                                <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider block">Upload New Image File</span>
                                <input type="file" name="avatar_file" id="edit_cand_avatar_file" accept="image/*" onchange="previewEditAvatarFile(event)"
                                    class="w-full py-2 px-3 rounded-xl border border-slate-200 dark:border-slate-800/80 text-slate-700 dark:text-slate-200 text-xs focus:border-purple-500 focus:outline-none bg-white dark:bg-slate-950 shadow-sm file:mr-2 file:py-1 file:px-2 file:rounded-lg file:border-0 file:text-[10px] file:font-semibold file:bg-purple-50 file:text-purple-700 dark:file:bg-purple-950/30 dark:file:text-purple-400 hover:file:bg-purple-100 transition">
                            </div>

                            <!-- URL Option -->
                            <div class="space-y-1">
                                <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider block">Or, Current/External Image URL</span>
                                <div class="relative rounded-xl shadow-sm">
                                    <input type="url" name="avatar_path" id="edit_cand_avatar" oninput="previewEditAvatarUrl(this.value)"
                                        placeholder="e.g. https://api.dicebear.com/..."
                                        class="w-full pr-3 pl-3 py-2 rounded-xl border border-slate-200 dark:border-slate-800/80 text-slate-700 dark:text-slate-200 text-xs focus:border-purple-500 focus:outline-none bg-white dark:bg-slate-950 shadow-sm transition">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/40 flex items-center justify-end gap-3 shrink-0">
                <button type="button" onclick="closeEditCandidateModal()"
                    class="text-xs font-semibold py-3 px-5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-slate-700 dark:text-slate-300 hover:bg-slate-50 transition">
                    Cancel
                </button>
                <button type="submit"
                    class="text-xs font-semibold py-3 px-6 rounded-xl bg-purple-600 hover:bg-purple-700 text-white transition shadow-md">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function previewEditAvatarFile(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('edit_cand_avatar_preview').src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    }

    function previewEditAvatarUrl(url) {
        if (url && url.trim() !== '') {
            document.getElementById('edit_cand_avatar_preview').src = url;
        } else {
            document.getElementById('edit_cand_avatar_preview').src = 'https://api.dicebear.com/7.x/adventurer/svg?seed=placeholder';
        }
    }
</script>
