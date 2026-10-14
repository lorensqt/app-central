@extends('layouts.voter')

@section('title', 'Voting Terms & Policy')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center py-6 sm:py-12 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
    <!-- Decorative background elements -->
    <div class="absolute top-1/4 left-1/4 w-80 h-72 bg-purple-400/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-1/4 right-1/4 w-80 h-72 bg-indigo-400/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-xl w-full space-y-8 relative">
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-3xl sm:rounded-[2.5rem] shadow-xl p-5 sm:p-10 text-center">
            
            <!-- TERMS & CONDITIONS PANEL -->
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

                <!-- Terms Submission Form -->
                <form action="{{ route('elections.voter.setup.submit', $election->id) }}" method="POST" class="space-y-4">
                    @csrf
                    
                    <!-- Checkbox -->
                    <div class="flex items-start text-left gap-3 pt-2">
                        <input type="checkbox" name="terms_consent" id="terms_consent" onchange="toggleTermsButton()" required
                            class="mt-1 w-4 h-4 rounded text-purple-600 border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900/50 focus:ring-purple-500 dark:focus:ring-purple-500/30 focus:ring-offset-0 dark:focus:ring-offset-0 transition cursor-pointer">
                        <label for="terms_consent" class="text-xs font-medium text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-300 select-none leading-relaxed cursor-pointer transition-colors">
                            I explicitly agree to the CDA & Corporate Voting Policy, certify my standing, understand that my candidate selections remain entirely secret, and acknowledge that my ballot cannot be changed once cast.
                        </label>
                    </div>

                    <!-- Next button -->
                    <div class="pt-2">
                        <button type="submit" id="terms-next-btn" disabled
                            class="w-full inline-flex items-center justify-center gap-2 bg-slate-200 dark:bg-slate-800 text-slate-400 dark:text-slate-500 font-semibold text-sm px-6 py-3 sm:py-3.5 rounded-2xl transition cursor-not-allowed">
                            Accept & Proceed to Ballot
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
</script>
@endsection
