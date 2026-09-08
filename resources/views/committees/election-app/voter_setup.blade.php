@extends('layouts.app')

@section('title', 'Voter Profiling')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative">
    <!-- Decorative background elements -->
    <div class="absolute top-1/4 left-1/4 w-80 h-72 bg-purple-400/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-1/4 right-1/4 w-80 h-72 bg-indigo-400/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-xl w-full space-y-8 relative">
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-[2.5rem] shadow-xl p-8 sm:p-10 text-center">
            
            <!-- STEP 1: TERMS & CONDITIONS PANEL -->
            <div id="step-terms" class="space-y-6">
                <!-- Icon -->
                <div class="w-16 h-16 rounded-[1.25rem] bg-purple-500/10 text-purple-600 dark:text-purple-400 flex items-center justify-center mx-auto">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>

                <div class="space-y-2">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-purple-500/10 dark:bg-purple-400/10 text-[10px] font-bold text-purple-600 dark:text-purple-400 uppercase tracking-widest">
                        Consent & Compliance
                    </span>
                    <h2 class="text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight leading-tight">
                        Terms & Voting Policy
                    </h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400 max-w-xs mx-auto">
                        Please read and accept the corporate voting terms of agreement before casting your ballot.
                    </p>
                </div>

                <!-- Terms Scrolling Container -->
                <div class="bg-slate-50 dark:bg-slate-950/60 border border-slate-100 dark:border-slate-850 rounded-2xl p-4.5 text-left text-xs text-slate-600 dark:text-slate-400 h-48 overflow-y-auto custom-scrollbar leading-relaxed space-y-3">
                    <p class="font-bold text-slate-900 dark:text-white">1. Absolute Ballot Secrecy</p>
                    <p>This election portal operates under strict Secret Ballot guidelines. Under no circumstances will your verified email address or registry details be tracked or mapped to your individual candidate selections inside the voting database. Your votes remain entirely anonymous.</p>
                    
                    <p class="font-bold text-slate-900 dark:text-white">2. Double-Voting Prevention & Immutability</p>
                    <p>Each authorized employee email is limited to exactly one (1) ballot registry submission per election. Once you submit your ballot, your voting selections are instantly sealed, encrypted, and recorded in the audit trail. <strong>Under no circumstances can your vote be changed, edited, or re-submitted after casting.</strong></p>

                    <p class="font-bold text-slate-900 dark:text-white">3. Authorized Corporate Standing</p>
                    <p>By participating in this election, you certify that you are an active employee of M Lhuillier Financial Services Inc. with a valid @mlhuillier.com domain. Any attempt to participate using unauthorized credentials, automate submissions, or tamper with the voting network constitutes a serious breach of company policy and will be subject to standard disciplinary actions.</p>
                </div>

                <!-- Checkbox -->
                <div class="flex items-start text-left gap-3 pt-2">
                    <input type="checkbox" id="terms_consent" onchange="toggleTermsButton()"
                        class="mt-1 w-4 h-4 rounded text-purple-600 border-slate-350 focus:ring-purple-500 dark:border-slate-800 bg-white dark:bg-slate-950">
                    <label for="terms_consent" class="text-xs font-medium text-slate-600 dark:text-slate-400 select-none leading-relaxed">
                        I explicitly agree to the Terms & Policy, understand that my candidate selections remain entirely secret, and acknowledge that my ballot cannot be changed once cast.
                    </label>
                </div>

                <!-- Next button -->
                <div class="pt-2">
                    <button id="terms-next-btn" disabled onclick="proceedToProfiling()"
                        class="w-full inline-flex items-center justify-center gap-2 bg-slate-200 dark:bg-slate-800 text-slate-400 dark:text-slate-500 font-semibold text-sm px-6 py-3.5 rounded-2xl transition cursor-not-allowed">
                        Accept & Continue
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- STEP 2: VOTER PROFILING PANEL (Initially Hidden) -->
            <div id="step-profiling" class="space-y-6 hidden opacity-0 transition-all duration-300">
                <!-- Icon -->
                <div class="w-16 h-16 rounded-[1.25rem] bg-purple-500/10 text-purple-600 dark:text-purple-400 flex items-center justify-center mx-auto">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>

                <div class="space-y-2">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-purple-500/10 dark:bg-purple-400/10 text-[10px] font-bold text-purple-600 dark:text-purple-400 uppercase tracking-widest">
                        Corporate Verification
                    </span>
                    <h2 class="text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight leading-tight">
                        Voter Registry
                    </h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400 max-w-xs mx-auto">
                        Please provide your division and current position inside M Lhuillier to enter the ballot box.
                    </p>
                </div>

                <!-- Profile Form -->
                <form action="{{ route('elections.voter.setup.submit', $election->id) }}" method="POST" class="space-y-5 text-left">
                    @csrf

                    <!-- Division Input -->
                    <div class="space-y-1.5">
                        <label for="division" class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Division / Department</label>
                        <input type="text" name="division" id="division" required
                            placeholder="e.g. OPEC Division, IT Department, HR"
                            class="w-full rounded-xl border border-slate-200 dark:border-slate-800/80 py-3 px-4 text-slate-800 dark:text-slate-200 text-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-white dark:bg-slate-950 shadow-sm transition">
                    </div>

                    <!-- Position Input -->
                    <div class="space-y-1.5">
                        <label for="current_position" class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Current Position / Role Designation</label>
                        <input type="text" name="current_position" id="current_position" required
                            placeholder="e.g. Area Manager, Software Engineer"
                            class="w-full rounded-xl border border-slate-200 dark:border-slate-800/80 py-3 px-4 text-slate-800 dark:text-slate-200 text-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-white dark:bg-slate-950 shadow-sm transition">
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-2">
                        <button type="submit"
                            class="w-full inline-flex items-center justify-center gap-2 bg-purple-600 hover:bg-purple-700 active:scale-[0.98] text-white font-semibold text-sm px-6 py-3.5 rounded-2xl shadow-md hover:shadow-lg transition">
                            Proceed to Ballot
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </button>
                    </div>
                </form>
            </div>
            
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function toggleTermsButton() {
        const checkbox = document.getElementById('terms_consent');
        const button = document.getElementById('terms-next-btn');

        if (checkbox.checked) {
            button.removeAttribute('disabled');
            button.className = "w-full inline-flex items-center justify-center gap-2 bg-purple-600 hover:bg-purple-700 text-white font-semibold text-sm px-6 py-3.5 rounded-2xl shadow-md hover:shadow-lg transition cursor-pointer active:scale-[0.98]";
        } else {
            button.setAttribute('disabled', 'true');
            button.className = "w-full inline-flex items-center justify-center gap-2 bg-slate-200 dark:bg-slate-800 text-slate-400 dark:text-slate-500 font-semibold text-sm px-6 py-3.5 rounded-2xl transition cursor-not-allowed";
        }
    }

    function proceedToProfiling() {
        const termsPanel = document.getElementById('step-terms');
        const profilingPanel = document.getElementById('step-profiling');

        // Smooth transition out Terms panel
        termsPanel.classList.add('hidden');

        // Transition in Profiling panel
        profilingPanel.classList.remove('hidden');
        requestAnimationFrame(() => {
            profilingPanel.classList.remove('opacity-0');
            profilingPanel.classList.add('opacity-100');
        });
    }
</script>
@endsection
