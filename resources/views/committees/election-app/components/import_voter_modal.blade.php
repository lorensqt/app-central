<!-- PREMIUM BACKDROP MODAL: IMPORT VOTER MASTERLIST -->
<div id="import-voter-modal"
    class="fixed inset-0 bg-slate-900/70 dark:bg-slate-950/90 backdrop-blur-[6px] z-50 hidden items-center justify-center p-3.5 sm:p-4 transition-all duration-300 opacity-0">
    <div class="bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-100 dark:border-slate-800 max-w-xl w-full shadow-2xl flex flex-col max-h-[92vh] transition-all duration-300 transform scale-95 opacity-0 overflow-hidden"
        id="import-voter-modal-content">
        
        <!-- Header -->
        <div class="relative overflow-hidden px-6 py-5 sm:px-8 border-b border-slate-100 dark:border-slate-800/60 shrink-0 bg-gradient-to-r from-purple-50/50 via-transparent to-indigo-50/30 dark:from-purple-950/10 dark:to-transparent">
            <div class="relative flex justify-between items-center">
                <div>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-indigo-500/10 dark:bg-indigo-400/10 text-[10px] font-bold text-indigo-600 dark:text-indigo-400 uppercase tracking-wider mb-1">
                        <i class="fa-solid fa-file-csv text-[10px]"></i>
                        Batch Masterlist Upload
                    </span>
                    <h3 class="font-bold text-slate-900 dark:text-white text-lg sm:text-xl leading-tight">
                        Import Voter Masterlist
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                        Bulk-enroll authorized participants into this election's masterlist.
                    </p>
                </div>
                
                <button type="button" onclick="closeImportVoterModal()"
                    class="text-slate-400 dark:text-slate-500 hover:text-slate-600 dark:hover:text-slate-200 p-2 hover:bg-slate-50 dark:hover:bg-slate-800/60 rounded-xl transition duration-150 active:scale-95">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <form id="import-voter-form" onsubmit="submitImportVoter(event)" enctype="multipart/form-data" class="flex-grow flex flex-col min-h-0">
            @csrf
            
            <div class="p-5 sm:p-7 space-y-4 bg-slate-50/40 dark:bg-slate-950/20 flex-grow overflow-y-auto custom-scrollbar">
                
                <!-- Explicit Guidelines & Formatting Requirements Box -->
                <div class="rounded-2xl bg-amber-500/10 dark:bg-amber-500/5 border border-amber-500/20 p-4 sm:p-4.5 space-y-2.5 text-left">
                    <div class="flex items-center justify-between gap-2 flex-wrap">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-circle-exclamation text-amber-600 dark:text-amber-400 text-sm"></i>
                            <span class="text-xs font-bold text-amber-900 dark:text-amber-200 uppercase tracking-wide">
                                Required CSV File Format
                            </span>
                        </div>
                        <a href="{{ route('committees.election.voters.sample_csv', $election->id) }}"
                            class="inline-flex items-center gap-1.5 text-[11px] font-bold text-indigo-600 dark:text-indigo-400 hover:underline">
                            <i class="fa-solid fa-download text-[10px]"></i>
                            <span>Download Sample CSV</span>
                        </a>
                    </div>

                    <p class="text-xs text-amber-800 dark:text-amber-300 leading-relaxed">
                        To guarantee successful processing, your file must contain <strong>pure raw tabular data</strong> without design, colors, merged cells, or summary rows.
                    </p>

                    <!-- Columns Checklist -->
                    <div class="space-y-1.5 text-xs text-slate-700 dark:text-slate-300 bg-white/70 dark:bg-slate-900/60 rounded-xl p-3 border border-amber-500/20">
                        <p class="font-bold text-[11px] text-slate-900 dark:text-white uppercase tracking-wider">
                            Row 1 must be the exact header row matching:
                        </p>
                        <div class="font-mono text-[11px] bg-slate-100 dark:bg-slate-950 p-2 rounded-lg text-purple-700 dark:text-purple-300 border border-slate-200 dark:border-slate-800 overflow-x-auto">
                            Voter / Account, Division / Workplace, Gender, Age
                        </div>
                        <ul class="space-y-1 text-[11px] pt-1 text-slate-600 dark:text-slate-400 leading-snug">
                            <li>• <strong class="text-slate-800 dark:text-slate-200">Voter / Account</strong>: Official email address (e.g. <span class="font-mono">juan.delacruz@mlhuillier.com</span>). <em>Required</em>.</li>
                            <li>• <strong class="text-slate-800 dark:text-slate-200">Division / Workplace</strong>: Workplace name (e.g. <span class="font-mono">Head Office - ICT</span>). <em>Optional</em>.</li>
                            <li>• <strong class="text-slate-800 dark:text-slate-200">Gender</strong>: <span class="font-mono">Male</span>, <span class="font-mono">Female</span>, <span class="font-mono">LGBTQ+</span>, or <span class="font-mono">Others</span>. <em>Optional</em>.</li>
                            <li>• <strong class="text-slate-800 dark:text-slate-200">Age</strong>: Numbers between 18 and 120. <em>Optional</em>.</li>
                        </ul>
                    </div>
                </div>

                <!-- File Dropzone -->
                <div class="space-y-2">
                    <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                        Select CSV File <span class="text-rose-500">*</span>
                    </label>

                    <div id="csv-dropzone"
                        onclick="document.getElementById('import_voter_file').click()"
                        class="border-2 border-dashed border-slate-200 dark:border-slate-800 rounded-2xl p-6 text-center cursor-pointer hover:border-purple-500 dark:hover:border-purple-400 bg-white dark:bg-slate-950 transition group">
                        
                        <input type="file" name="csv_file" id="import_voter_file" accept=".csv, text/csv, text/plain" required
                            class="hidden" onchange="onCsvFileSelected(this)">

                        <div class="space-y-2" id="csv-dropzone-prompt">
                            <div class="w-12 h-12 rounded-2xl bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 flex items-center justify-center mx-auto text-xl group-hover:scale-105 transition-transform">
                                <i class="fa-solid fa-cloud-arrow-up"></i>
                            </div>
                            <div>
                                <span class="text-xs font-bold text-slate-800 dark:text-slate-200 block">
                                    Click to browse or drop your CSV file here
                                </span>
                                <span class="text-[11px] text-slate-400 dark:text-slate-500 block mt-0.5">
                                    Standard .CSV format only • Maximum 10MB
                                </span>
                            </div>
                        </div>

                        <!-- Selected File Preview Info -->
                        <div id="csv-dropzone-selected" class="hidden items-center justify-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-lg shrink-0">
                                <i class="fa-solid fa-file-csv"></i>
                            </div>
                            <div class="text-left min-w-0">
                                <span id="csv-file-name" class="block text-xs font-bold text-slate-900 dark:text-white truncate max-w-[280px]">filename.csv</span>
                                <span id="csv-file-size" class="block text-[11px] text-slate-400">0 KB</span>
                            </div>
                            <button type="button" onclick="event.stopPropagation(); clearSelectedCsvFile()" class="p-1.5 text-slate-400 hover:text-rose-500 rounded-lg transition" title="Remove file">
                                <i class="fa-solid fa-xmark text-sm"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Notice -->
                <p class="text-[11px] text-slate-400 dark:text-slate-500 leading-relaxed">
                    <i class="fa-solid fa-shield-halved text-purple-500 mr-1"></i>
                    Duplicate emails will automatically update voter profiles on the masterlist. Existing votes cast remain preserved.
                </p>

            </div>

            <!-- Footer Buttons -->
            <div class="p-4 sm:p-5 px-6 sm:px-8 border-t border-slate-100 dark:border-slate-800/80 bg-white dark:bg-slate-900 flex items-center justify-end gap-3 shrink-0">
                <button type="button" onclick="closeImportVoterModal()"
                    class="text-xs font-semibold py-2.5 px-4 rounded-xl border border-slate-200 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-800/60 text-slate-700 dark:text-slate-300 transition">
                    Cancel
                </button>
                <button type="submit" id="btn-submit-import-voters"
                    class="inline-flex items-center gap-2 text-xs font-semibold py-2.5 px-5 rounded-xl bg-indigo-600 hover:bg-indigo-700 active:scale-[0.98] text-white transition shadow-sm">
                    <i class="fa-solid fa-upload text-xs"></i>
                    <span>Verify & Import Masterlist</span>
                </button>
            </div>
        </form>
    </div>
</div>
