@extends('layouts.app')

@section('title', 'Voter Profiling')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center py-6 sm:py-12 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
    <!-- Decorative background elements -->
    <div class="absolute top-1/4 left-1/4 w-80 h-72 bg-purple-400/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-1/4 right-1/4 w-80 h-72 bg-indigo-400/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-xl w-full space-y-8 relative">
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-3xl sm:rounded-[2.5rem] shadow-xl p-5 sm:p-10 text-center">
            
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
                    <h2 class="text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight leading-tight">
                        Terms & Voting Policy
                    </h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400 max-w-xs mx-auto">
                        Please read and accept the corporate voting terms of agreement before casting your ballot.
                    </p>
                </div>

                <!-- Terms Scrolling Container -->
                <div class="bg-slate-50/50 dark:bg-slate-950/40 border border-slate-200 dark:border-slate-800/80 rounded-2xl p-4 sm:p-5 text-left text-xs text-slate-600 dark:text-slate-400 h-60 sm:h-72 overflow-y-auto custom-scrollbar leading-relaxed space-y-4">
                    <div class="pb-2 border-b border-slate-200/60 dark:border-slate-800/60 text-center">
                        <span class="text-[10px] font-bold tracking-wider text-purple-600 dark:text-purple-400 uppercase">Cooperative Development Authority (CDA) & Corporate Election Rules</span>
                    </div>

                    <div class="space-y-1.5">
                        <p class="font-bold text-slate-900 dark:text-slate-200">1. "One Member, One Vote" Principle</p>
                        <p>In strict compliance with Chapter IV, Article 36 of Republic Act No. 9520 (Philippine Cooperative Code), every qualified member of this committee or standing body is entitled to exactly one (1) vote. No member is permitted to hold multiple voting profiles, and all votes carry equal weight regardless of position, tenure, or department.</p>
                    </div>
                    
                    <div class="space-y-1.5">
                        <p class="font-bold text-slate-900 dark:text-slate-200">2. Strict Prohibition of Proxy Voting</p>
                        <p>As mandated by the CDA standard bylaws for primary organizations and committees, <strong>voting by proxy is strictly prohibited</strong>. To maintain democratic transparency, members must log in and cast their ballots individually using their verified corporate credentials. Any attempted proxy representation will void the corresponding ballot registry.</p>
                    </div>

                    <div class="space-y-1.5">
                        <p class="font-bold text-slate-900 dark:text-slate-200">3. Absolute Secret Ballot Guidelines</p>
                        <p>To preserve the democratic integrity of this election, all votes are cast via a secure secret ballot system. Your verified email registry is used solely to audit and verify voter eligibility and prevent duplicate voting; it is mathematically decoupled from your specific candidate choices in the ballot database. Your individual selections remain 100% confidential and anonymous.</p>
                    </div>

                    <div class="space-y-1.5">
                        <p class="font-bold text-slate-900 dark:text-slate-200">4. Member in Good Standing (MIGS) Qualification</p>
                        <p>Only active employees of M Lhuillier Financial Services Inc. holding valid @mlhuillier.com credentials and meeting the cooperative criteria of a Member in Good Standing (MIGS)—free from administrative suspensions, active disciplinary cases, or membership delinquency—are permitted to participate in this election.</p>
                    </div>

                    <div class="space-y-1.5">
                        <p class="font-bold text-slate-900 dark:text-slate-200">5. Electoral Immutability & Sealing</p>
                        <p>Once a ballot is officially submitted, it is immediately encrypted, sealed, and integrated into the election audit trail. <strong>Under no circumstances can your ballot selections be retrieved, edited, modified, or re-submitted.</strong> All votes cast are final and binding.</p>
                    </div>

                    <div class="space-y-1.5">
                        <p class="font-bold text-slate-900 dark:text-slate-200">6. Electoral Oversight & Independent Election Committee</p>
                        <p>This election is conducted under the direct supervision of the independent Group Election Committee. Any instances of vote-buying, systematic automated submissions, credential-sharing, or attempts to compromise the electronic registry represent severe violations of CDA standards and the Corporate Code of Conduct, and will be escalated immediately for disciplinary action.</p>
                    </div>
                </div>

                <!-- Checkbox -->
                <div class="flex items-start text-left gap-3 pt-2">
                    <input type="checkbox" id="terms_consent" onchange="toggleTermsButton()"
                        class="mt-1 w-4 h-4 rounded text-purple-600 border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900/50 focus:ring-purple-500 dark:focus:ring-purple-500/30 focus:ring-offset-0 dark:focus:ring-offset-0 transition cursor-pointer">
                    <label for="terms_consent" class="text-xs font-medium text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-300 select-none leading-relaxed cursor-pointer transition-colors">
                        I explicitly agree to the CDA & Corporate Voting Policy, certify my standing, understand that my candidate selections remain entirely secret, and acknowledge that my ballot cannot be changed once cast.
                    </label>
                </div>

                <!-- Next button -->
                <div class="pt-2">
                    <button id="terms-next-btn" disabled onclick="proceedToProfiling()"
                        class="w-full inline-flex items-center justify-center gap-2 bg-slate-200 dark:bg-slate-800 text-slate-400 dark:text-slate-500 font-semibold text-sm px-6 py-3 sm:py-3.5 rounded-2xl transition cursor-not-allowed">
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
                    <h2 class="text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight leading-tight">
                        Voter Registry
                    </h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400 max-w-xs mx-auto">
                        Please provide your division, age, and gender inside M Lhuillier to enter the ballot box.
                    </p>
                </div>

                <!-- Privacy Advisory Notice -->
                <div class="flex gap-3 p-3.5 rounded-2xl bg-purple-500/5 dark:bg-purple-400/5 border border-purple-500/10 dark:border-purple-400/10 text-left">
                    <span class="text-purple-600 dark:text-purple-400 shrink-0 mt-0.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </span>
                    <p class="text-[11px] leading-normal text-slate-500 dark:text-slate-400">
                        <strong>Privacy Safeguard:</strong> This registry profiling data is used solely to audit turnout representation metrics. Please note that age and gender are required for statistics reports purposes only. Under no circumstances is this data mapped to your confidential ballot selection inside the secure database.
                    </p>
                </div>

                <!-- Profile Form -->
                <form action="{{ route('elections.voter.setup.submit', $election->id) }}" method="POST" class="space-y-5 text-left">
                    @csrf

                    <!-- Division Input -->
                    <div class="space-y-1.5">
                        <label for="division" class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Division / Department</label>
                        <div class="relative rounded-xl shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <input type="text" name="division" id="division" required
                                placeholder="e.g. OPEC Division, IT Department, HR"
                                class="w-full rounded-xl border border-slate-200 dark:border-slate-800/80 py-2.5 sm:py-3 pl-11 pr-4 text-slate-800 dark:text-slate-200 text-base sm:text-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-white dark:bg-slate-950/60 hover:border-slate-300 dark:hover:border-slate-700/85 shadow-sm transition">
                        </div>
                    </div>

                    <!-- Gender Input (Custom Animated Dropdown) -->
                    <div class="space-y-1.5">
                        <label for="gender" class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Gender</label>
                        <div class="relative custom-setup-dropdown" id="dropdown-wrapper-gender">
                            <!-- Hidden input for standard form submission & validation -->
                            <input type="hidden" name="gender" id="gender" value="" required>

                            <!-- Trigger Button -->
                            <button type="button" id="gender-trigger-btn" onclick="toggleGenderDropdown()"
                                class="w-full rounded-xl border border-slate-200 dark:border-slate-800/80 py-2.5 sm:py-3 pl-11 pr-4 text-left text-base sm:text-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-white dark:bg-slate-950/60 hover:border-slate-300 dark:hover:border-slate-700/85 shadow-sm transition flex items-center justify-between">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 dark:text-slate-500">
                                    <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                                <span id="label-gender" class="text-slate-400 dark:text-slate-500 font-normal truncate">
                                    Select Gender
                                </span>
                                <svg id="chevron-gender" class="w-5 h-5 text-slate-400 dark:text-slate-500 transition-transform duration-200 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <!-- Animated Options Menu -->
                            <div id="menu-gender"
                                class="hidden opacity-0 scale-95 transition-all duration-200 ease-out origin-top absolute left-0 right-0 mt-2 z-40 bg-white/95 dark:bg-slate-900/95 backdrop-blur-xl border border-slate-200 dark:border-slate-800 rounded-2xl shadow-xl p-1.5 space-y-0.5">
                                <button type="button" onclick="selectGenderOption('Male', 'Male')"
                                    class="setup-gender-opt w-full text-left px-3.5 py-2.5 rounded-xl text-xs sm:text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800/60 hover:text-purple-600 dark:hover:text-purple-400 flex items-center justify-between transition" data-value="Male">
                                    <span>Male</span>
                                    <svg class="check-icon hidden w-4 h-4 text-purple-600 dark:text-purple-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                </button>
                                <button type="button" onclick="selectGenderOption('Female', 'Female')"
                                    class="setup-gender-opt w-full text-left px-3.5 py-2.5 rounded-xl text-xs sm:text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800/60 hover:text-purple-600 dark:hover:text-purple-400 flex items-center justify-between transition" data-value="Female">
                                    <span>Female</span>
                                    <svg class="check-icon hidden w-4 h-4 text-purple-600 dark:text-purple-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                </button>
                                <button type="button" onclick="selectGenderOption('LGBTQ', 'LGBTQ')"
                                    class="setup-gender-opt w-full text-left px-3.5 py-2.5 rounded-xl text-xs sm:text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800/60 hover:text-purple-600 dark:hover:text-purple-400 flex items-center justify-between transition" data-value="LGBTQ">
                                    <span>LGBTQ</span>
                                    <svg class="check-icon hidden w-4 h-4 text-purple-600 dark:text-purple-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                </button>
                                <button type="button" onclick="selectGenderOption('Others', 'Others')"
                                    class="setup-gender-opt w-full text-left px-3.5 py-2.5 rounded-xl text-xs sm:text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800/60 hover:text-purple-600 dark:hover:text-purple-400 flex items-center justify-between transition" data-value="Others">
                                    <span>Others</span>
                                    <svg class="check-icon hidden w-4 h-4 text-purple-600 dark:text-purple-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Custom Gender Specification (Initially Hidden) -->
                    <div id="gender_other_container" class="space-y-1.5 hidden">
                        <label for="gender_other" class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Please Specify Gender</label>
                        <div class="relative rounded-xl shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </div>
                            <input type="text" name="gender_other" id="gender_other"
                                placeholder="e.g. Non-binary, Prefer not to say"
                                class="w-full rounded-xl border border-slate-200 dark:border-slate-800/80 py-2.5 sm:py-3 pl-11 pr-4 text-slate-800 dark:text-slate-200 text-base sm:text-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-white dark:bg-slate-950/60 hover:border-slate-300 dark:hover:border-slate-700/85 shadow-sm transition">
                        </div>
                    </div>

                    <!-- Age Input -->
                    <div class="space-y-1.5">
                        <label for="age" class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Age</label>
                        <div class="relative rounded-xl shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <input type="number" name="age" id="age" required min="18" max="120"
                                placeholder="e.g. 25"
                                class="w-full rounded-xl border border-slate-200 dark:border-slate-800/80 py-2.5 sm:py-3 pl-11 pr-4 text-slate-800 dark:text-slate-200 text-base sm:text-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-white dark:bg-slate-950/60 hover:border-slate-300 dark:hover:border-slate-700/85 shadow-sm transition">
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-2">
                        <button type="submit"
                            class="w-full inline-flex items-center justify-center gap-2 bg-purple-600 hover:bg-purple-700 active:scale-[0.98] text-white font-semibold text-sm px-6 py-3 sm:py-3.5 rounded-2xl shadow-md hover:shadow-lg transition">
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
            button.className = "w-full inline-flex items-center justify-center gap-2 bg-purple-600 hover:bg-purple-700 text-white font-semibold text-sm px-6 py-3 sm:py-3.5 rounded-2xl shadow-md hover:shadow-lg transition cursor-pointer active:scale-[0.98]";
        } else {
            button.setAttribute('disabled', 'true');
            button.className = "w-full inline-flex items-center justify-center gap-2 bg-slate-200 dark:bg-slate-800 text-slate-400 dark:text-slate-500 font-semibold text-sm px-6 py-3 sm:py-3.5 rounded-2xl transition cursor-not-allowed";
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

    function toggleGenderDropdown() {
        const menu = document.getElementById('menu-gender');
        const chevron = document.getElementById('chevron-gender');
        if (!menu) return;

        const isHidden = menu.classList.contains('hidden');
        if (isHidden) {
            menu.classList.remove('hidden');
            requestAnimationFrame(() => {
                menu.classList.remove('opacity-0', 'scale-95');
                menu.classList.add('opacity-100', 'scale-100');
                if (chevron) chevron.classList.add('rotate-180');
            });
        } else {
            closeGenderDropdown();
        }
    }

    function closeGenderDropdown() {
        const menu = document.getElementById('menu-gender');
        const chevron = document.getElementById('chevron-gender');
        if (menu && !menu.classList.contains('hidden')) {
            menu.classList.remove('opacity-100', 'scale-100');
            menu.classList.add('opacity-0', 'scale-95');
            if (chevron) chevron.classList.remove('rotate-180');
            setTimeout(() => menu.classList.add('hidden'), 200);
        }
    }

    function selectGenderOption(value, label) {
        const input = document.getElementById('gender');
        const labelEl = document.getElementById('label-gender');
        const trigger = document.getElementById('gender-trigger-btn');

        if (input) input.value = value;
        if (labelEl) {
            labelEl.textContent = label;
            labelEl.classList.remove('text-slate-400', 'dark:text-slate-500');
            labelEl.classList.add('text-slate-800', 'dark:text-slate-200', 'font-medium');
        }
        if (trigger) {
            trigger.classList.remove('border-red-500', 'ring-4', 'ring-red-500/10');
        }

        const options = document.querySelectorAll('.setup-gender-opt');
        options.forEach(opt => {
            const optVal = opt.getAttribute('data-value');
            const check = opt.querySelector('.check-icon');
            if (optVal === value) {
                opt.classList.add('bg-purple-50/70', 'dark:bg-purple-950/40', 'text-purple-600', 'dark:text-purple-400', 'font-semibold');
                opt.classList.remove('text-slate-700', 'dark:text-slate-300', 'font-medium');
                if (check) check.classList.remove('hidden');
            } else {
                opt.classList.remove('bg-purple-50/70', 'dark:bg-purple-950/40', 'text-purple-600', 'dark:text-purple-400', 'font-semibold');
                opt.classList.add('text-slate-700', 'dark:text-slate-300', 'font-medium');
                if (check) check.classList.add('hidden');
            }
        });

        closeGenderDropdown();
        toggleGenderOther();
    }

    function toggleGenderOther() {
        const genderInput = document.getElementById('gender');
        const otherContainer = document.getElementById('gender_other_container');
        const otherInput = document.getElementById('gender_other');

        if (genderInput && genderInput.value === 'Others') {
            otherContainer.classList.remove('hidden');
            otherInput.setAttribute('required', 'true');
            otherInput.focus();
        } else {
            otherContainer.classList.add('hidden');
            otherInput.removeAttribute('required');
            otherInput.value = '';
        }
    }

    // Dismiss on click outside or Escape
    document.addEventListener('click', (e) => {
        if (!e.target.closest('.custom-setup-dropdown')) {
            closeGenderDropdown();
        }
    });
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeGenderDropdown();
        }
    });

    // Form submit validation for custom gender dropdown
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.querySelector('form');
        if (form) {
            form.addEventListener('submit', (e) => {
                const genderInput = document.getElementById('gender');
                if (!genderInput || !genderInput.value) {
                    e.preventDefault();
                    const trigger = document.getElementById('gender-trigger-btn');
                    if (trigger) {
                        trigger.classList.add('border-red-500', 'ring-4', 'ring-red-500/10');
                        trigger.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                    if (window.showToast) {
                        window.showToast('Please select your gender before proceeding.', 'warning');
                    }
                }
            });
        }
    });
</script>
@endsection
