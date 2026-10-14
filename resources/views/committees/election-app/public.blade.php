<!DOCTYPE html>
<html lang="en" class="min-h-screen bg-slate-50 dark:bg-slate-950 transition-colors duration-300">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $election->title }} - Official SAKO Election Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class'
        }
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
        }
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: rgba(156, 163, 175, 0.25);
            border-radius: 20px;
        }
        @keyframes pulseGlow {
            0%, 100% {
                box-shadow: 0 0 25px rgba(147, 51, 234, 0.25);
            }
            50% {
                box-shadow: 0 0 45px rgba(147, 51, 234, 0.45);
            }
        }
        .pulse-glow {
            animation: pulseGlow 3s infinite ease-in-out;
        }
    </style>
</head>

<body class="min-h-screen flex flex-col justify-between relative bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 overflow-x-hidden transition-colors duration-300">
    <!-- Decorative Glowing Orbs -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none z-0">
        <div class="absolute -top-40 -left-40 w-[550px] h-[550px] bg-purple-300/25 dark:bg-purple-900/15 rounded-full blur-[120px]"></div>
        <div class="absolute top-1/3 right-0 w-[450px] h-[450px] bg-indigo-300/20 dark:bg-indigo-900/15 rounded-full blur-[100px]"></div>
        <div class="absolute -bottom-20 left-1/3 w-[600px] h-[600px] bg-fuchsia-300/15 dark:bg-fuchsia-900/10 rounded-full blur-[130px]"></div>
    </div>

    <!-- Top Sticky Header -->
    <header class="sticky top-0 z-40 bg-white/85 dark:bg-slate-900/85 backdrop-blur-xl border-b border-slate-200/70 dark:border-slate-800/70 transition-colors duration-300">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 h-18 py-3 flex items-center justify-between">
            <!-- Brand / Logo -->
            <a href="{{ route('elections.public_show', $election) }}" class="flex items-center gap-3 group select-none">
                <img src="{{ asset('img/sako-logo-nobg.png') }}" alt="SAKO Logo" class="h-10 sm:h-11 w-auto object-contain transition-transform duration-300 group-hover:scale-105 drop-shadow-xs" />
                <div class="hidden sm:block text-left">
                    <span class="block text-xs font-black tracking-wider uppercase text-purple-600 dark:text-purple-400">SAKO Cooperative</span>
                    <span class="block text-sm font-extrabold text-slate-900 dark:text-white leading-tight">Election Portal</span>
                </div>
            </a>

            <!-- Right Controls: Status & Theme Toggle -->
            <div class="flex items-center gap-3">
                <!-- Theme Toggle Button -->
                <button id="theme-toggle" type="button" aria-label="Toggle Theme"
                    class="text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-100 p-2 sm:p-2.5 rounded-xl bg-slate-100/80 dark:bg-slate-800/80 border border-slate-200/80 dark:border-slate-700/80 shadow-xs hover:bg-slate-200/60 dark:hover:bg-slate-700/60 transition focus:outline-none shrink-0">
                    <i id="theme-toggle-dark-icon" class="fas fa-moon hidden w-4 h-4 flex items-center justify-center"></i>
                    <i id="theme-toggle-light-icon" class="fas fa-sun hidden w-4 h-4 flex items-center justify-center"></i>
                </button>

                @if($email)
                    <div class="hidden md:flex items-center gap-2 px-3.5 py-1.5 rounded-xl bg-purple-50 dark:bg-purple-950/40 border border-purple-200/60 dark:border-purple-800/60 text-xs">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        <span class="font-medium text-slate-600 dark:text-slate-300 max-w-[150px] truncate">{{ $email }}</span>
                    </div>
                @endif

                @if($election->status === 'active' && !$hasVoted)
                    <a href="{{ route('elections.voter.login', $election) }}"
                        class="inline-flex items-center gap-2 bg-purple-600 hover:bg-purple-700 active:scale-[0.98] text-white font-bold text-xs sm:text-sm px-4 sm:px-5 py-2 sm:py-2.5 rounded-xl shadow-md shadow-purple-500/20 transition">
                        <i class="fa-solid fa-check-to-slot"></i>
                        <span>Vote Now</span>
                    </a>
                @elseif($hasVoted)
                    <span class="inline-flex items-center gap-1.5 px-3 sm:px-4 py-2 rounded-xl text-xs font-bold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">
                        <i class="fa-solid fa-circle-check"></i>
                        <span>Ballot Cast</span>
                    </span>
                @endif
            </div>
        </div>
    </header>

    <!-- Main Content Container -->
    <main class="flex-grow z-10 max-w-6xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12 space-y-10 sm:space-y-14">

        <!-- Flash Notices (Masterlist Alert, Voter Status, Error) -->
        @if(session('masterlist_error'))
            <div class="rounded-2xl bg-amber-500/10 border-2 border-amber-500/30 p-5 sm:p-6 text-amber-800 dark:text-amber-200 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 shadow-sm animate-fade-in-up">
                <div class="flex items-start gap-3.5">
                    <div class="w-10 h-10 rounded-xl bg-amber-500/20 text-amber-600 dark:text-amber-400 flex items-center justify-center shrink-0 mt-0.5">
                        <i class="fa-solid fa-triangle-exclamation text-lg"></i>
                    </div>
                    <div>
                        <h4 class="font-extrabold text-sm sm:text-base text-amber-900 dark:text-amber-100">Masterlist Verification Notice</h4>
                        <p class="text-xs sm:text-sm mt-0.5 opacity-90 leading-relaxed">{{ session('masterlist_error') }}</p>
                    </div>
                </div>
                <div class="shrink-0 self-end sm:self-center">
                    <span class="text-[11px] font-bold uppercase tracking-wider px-3 py-1.5 rounded-lg bg-amber-500/20 text-amber-700 dark:text-amber-300">Contact SAKO Committee</span>
                </div>
            </div>
        @endif

        @if(session('voter_info'))
            <div class="rounded-2xl bg-emerald-500/10 border border-emerald-500/30 p-5 sm:p-6 text-emerald-800 dark:text-emerald-200 flex items-center gap-3.5 shadow-sm animate-fade-in-up">
                <div class="w-10 h-10 rounded-xl bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-circle-check text-xl"></i>
                </div>
                <div>
                    <h4 class="font-extrabold text-sm sm:text-base text-emerald-900 dark:text-emerald-100">Official Ballot Cast</h4>
                    <p class="text-xs sm:text-sm mt-0.5 opacity-90 leading-relaxed">{{ session('voter_info') }}</p>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="rounded-2xl bg-rose-500/10 border border-rose-500/30 p-5 text-rose-800 dark:text-rose-200 flex items-center gap-3">
                <i class="fa-solid fa-circle-xmark text-lg text-rose-600"></i>
                <span class="text-xs sm:text-sm font-semibold">{{ session('error') }}</span>
            </div>
        @endif

        <!-- HERO SECTION -->
        <section class="text-center relative space-y-6 pt-2 sm:pt-4">
            <div class="flex items-center justify-center gap-2.5 flex-wrap">
                @if($election->status === 'active')
                    <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 border border-emerald-500/25">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        Live Official Election
                    </span>
                @elseif($election->status === 'closed')
                    <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider bg-slate-500/15 text-slate-600 dark:text-slate-400 border border-slate-500/25">
                        <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                        Election Concluded
                    </span>
                @else
                    <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider bg-purple-500/15 text-purple-600 dark:text-purple-400 border border-purple-500/25">
                        Scheduled / Upcoming
                    </span>
                @endif

                @if ($election->start_date || $election->end_date)
                    <span class="text-xs text-slate-500 dark:text-slate-400 font-medium inline-flex items-center gap-1.5">
                        <i class="fa-regular fa-calendar text-slate-400"></i>
                        {{ $election->start_date ? $election->start_date->format('M d, Y') : 'Start' }} –
                        {{ $election->end_date ? $election->end_date->format('M d, Y') : 'End' }}
                    </span>
                @endif
            </div>

            <div class="space-y-3 max-w-3xl mx-auto">
                <h1 class="text-3xl sm:text-5xl font-black text-slate-900 dark:text-white tracking-tight leading-tight">
                    {{ $election->title }}
                </h1>
                <p class="text-sm sm:text-base text-slate-600 dark:text-slate-400 leading-relaxed font-normal">
                    {{ $election->description ?? 'Welcome to the official democratic election portal of SAKO Cooperative. Review the candidate profiles and standings, verify your standing on the voter masterlist, and securely cast your secret ballot.' }}
                </p>
            </div>

            <!-- PRIMARY "VOTE NOW" CTA CARD -->
            <div class="max-w-xl mx-auto pt-2 sm:pt-4">
                <div class="relative rounded-3xl bg-white dark:bg-slate-900 border-2 border-purple-500/30 dark:border-purple-500/30 p-6 sm:p-8 shadow-xl shadow-purple-500/5 text-center space-y-4 pulse-glow">
                    @if($election->status === 'active')
                        @if($hasVoted)
                            <div class="space-y-2">
                                <div class="w-14 h-14 rounded-2xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center mx-auto text-2xl">
                                    <i class="fa-solid fa-check"></i>
                                </div>
                                <h3 class="text-lg sm:text-xl font-extrabold text-slate-900 dark:text-white">Your Ballot Has Been Cast</h3>
                                <p class="text-xs text-slate-500 dark:text-slate-400 max-w-sm mx-auto">
                                    Thank you for exercising your right to vote. Your selections are securely sealed and encrypted in the audit database.
                                </p>
                            </div>
                        @else
                            <div class="space-y-2">
                                <span class="text-[11px] font-black uppercase tracking-widest text-purple-600 dark:text-purple-400">
                                    Official Secret Ballot Booth
                                </span>
                                <h3 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white tracking-tight">
                                    Ready to Cast Your Vote?
                                </h3>
                                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 max-w-md mx-auto">
                                    Click below to verify your email on the masterlist, review the terms of agreement, and vote for your chosen candidates.
                                </p>
                            </div>

                            <div class="pt-2">
                                <a href="{{ route('elections.voter.login', $election) }}"
                                    class="w-full sm:w-auto inline-flex items-center justify-center gap-3 bg-gradient-to-r from-purple-600 via-indigo-600 to-purple-700 hover:from-purple-700 hover:to-indigo-700 active:scale-[0.98] text-white font-extrabold text-sm sm:text-base px-8 py-4 rounded-2xl shadow-lg shadow-purple-500/30 hover:shadow-xl transition-all cursor-pointer">
                                    <i class="fa-solid fa-check-to-slot text-lg"></i>
                                    <span>Vote Now in This Election</span>
                                    <i class="fa-solid fa-arrow-right text-xs"></i>
                                </a>
                            </div>

                            <p class="text-[11px] text-slate-400 dark:text-slate-500 pt-1">
                                <i class="fa-solid fa-shield-halved mr-1 text-purple-500"></i>
                                100% Secret Ballot • One Member, One Vote • Governed by SAKO & CDA Rules
                            </p>
                        @endif
                    @elseif($election->status === 'closed')
                        <div class="space-y-2 py-2">
                            <i class="fa-solid fa-lock text-3xl text-slate-400"></i>
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white">Voting Has Concluded</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400">This election is closed. You can view the final anonymous counts and candidate standings below.</p>
                        </div>
                    @else
                        <div class="space-y-2 py-2">
                            <i class="fa-solid fa-clock text-3xl text-purple-500"></i>
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white">Voting Opens Soon</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400">The ballot booth has not yet opened. Please check back when the election committee starts the voting period.</p>
                        </div>
                    @endif
                </div>
            </div>
        </section>

        <!-- ANONYMOUS ELECTION STANDINGS -->
        <section class="space-y-6 pt-2">
            <div class="text-center max-w-xl mx-auto space-y-1.5 pb-2">
                <span class="text-[11px] font-black uppercase tracking-widest text-purple-600 dark:text-purple-400">
                    <i class="fa-solid fa-user-secret mr-1"></i> Anonymous Standings
                </span>
                <h2 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white tracking-tight">
                    Current Race Standings
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400">
                    Candidate identities remain confidential until official results are declared. Follow the live vote tallies and race momentum below.
                </p>
            </div>

            <!-- Positions with Candidates Anonymous Standings -->
            <div class="space-y-6">
                @forelse($positions as $position)
                    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800/80 p-6 sm:p-8 shadow-sm space-y-6">
                        <!-- Position Header -->
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-4 border-b border-slate-100 dark:border-slate-800/60 gap-2">
                            <div>
                                <span class="text-[10px] font-bold uppercase tracking-widest text-purple-600 dark:text-purple-400">Position</span>
                                <h3 class="text-xl font-black text-slate-900 dark:text-white tracking-tight">
                                    {{ $position->name }}
                                </h3>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-semibold px-3 py-1 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300">
                                    Choose Up To {{ $position->max_votes }} {{ Str::plural('Candidate', $position->max_votes) }}
                                </span>
                                <span class="text-xs font-bold px-3 py-1 rounded-full bg-purple-50 dark:bg-purple-950/40 text-purple-600 dark:text-purple-400 border border-purple-200/40 dark:border-purple-800/40">
                                    {{ number_format($position->votes_count) }} {{ Str::plural('vote', $position->votes_count) }} cast
                                </span>
                            </div>
                        </div>

                        <!-- Candidates Grid (Anonymous Mystery Cards) -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5">
                            @forelse($position->candidates as $candidate)
                                @php
                                    $candShare = $position->votes_count > 0 
                                        ? round(($candidate->votes_count / $position->votes_count) * 100, 1) 
                                        : 0;
                                @endphp
                                <div class="relative rounded-2xl border border-slate-200/80 dark:border-slate-800/80 bg-slate-50/50 dark:bg-slate-950/40 p-5 space-y-4 hover:border-purple-500/40 dark:hover:border-purple-500/40 transition group">
                                    <!-- Mystery Candidate Header -->
                                    <div class="flex items-center gap-3.5">
                                        <div class="w-13 h-13 sm:w-14 sm:h-14 rounded-2xl bg-gradient-to-br from-purple-500/10 to-indigo-500/10 dark:from-purple-500/20 dark:to-indigo-500/20 border border-purple-500/20 text-purple-600 dark:text-purple-400 overflow-hidden shrink-0 shadow-xs flex items-center justify-center text-xl group-hover:scale-105 transition-transform duration-200">
                                            <i class="fa-solid fa-user-secret"></i>
                                        </div>
                                        <div class="min-w-0 flex-grow">
                                            <div class="flex items-center gap-2">
                                                <h4 class="font-bold text-sm sm:text-base text-slate-900 dark:text-white truncate">
                                                    Candidate #{{ $loop->iteration }}
                                                </h4>
                                                @if($loop->iteration === 1 && $candidate->votes_count > 0)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider bg-amber-500/15 text-amber-600 dark:text-amber-400 border border-amber-500/25">
                                                        Leading
                                                    </span>
                                                @endif
                                            </div>
                                            <span class="inline-flex items-center gap-1 text-xs text-slate-400 dark:text-slate-500 font-medium">
                                                <i class="fa-solid fa-mask text-[10px] text-purple-400"></i> Identity Hidden
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Live Anonymous Vote Metric -->
                                    <div class="space-y-1.5 pt-1">
                                        <div class="flex justify-between items-baseline text-xs">
                                            <span class="font-extrabold text-slate-900 dark:text-white flex items-center gap-1.5">
                                                <i class="fa-solid fa-check-to-slot text-purple-600 text-[11px]"></i>
                                                {{ number_format($candidate->votes_count) }} {{ Str::plural('vote', $candidate->votes_count) }}
                                            </span>
                                            <span class="font-black text-purple-600 dark:text-purple-400">{{ $candShare }}%</span>
                                        </div>
                                        <div class="w-full bg-slate-200 dark:bg-slate-800 rounded-full h-2.5 overflow-hidden">
                                            <div class="bg-gradient-to-r from-purple-600 to-indigo-600 h-2.5 rounded-full transition-all duration-700 ease-out" style="width: {{ min(100, $candShare) }}%;"></div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-span-full py-8 text-center text-xs text-slate-400 italic">
                                    No candidates registered under this position yet.
                                </div>
                            @endforelse
                        </div>
                    </div>
                @empty
                    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 p-12 text-center text-sm text-slate-400">
                        No election structure defined yet.
                    </div>
                @endforelse
            </div>
        </section>

        <!-- HOW TO VOTE (4-STEP TUTORIAL) -->
        <section class="space-y-6 pt-4">
            <div class="text-center max-w-xl mx-auto space-y-2">
                <span class="text-[11px] font-black uppercase tracking-widest text-purple-600 dark:text-purple-400">
                    Step-by-Step Voting Guide
                </span>
                <h2 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white tracking-tight">
                    How To Cast Your Ballot
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400">
                    Follow these four simple steps to participate in this SAKO cooperative election.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <!-- Step 1 -->
                <div class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 p-6 space-y-4 shadow-sm relative group hover:border-purple-500/50 transition duration-200">
                    <div class="w-12 h-12 rounded-2xl bg-purple-500/10 text-purple-600 dark:text-purple-400 flex items-center justify-center font-black text-base shadow-xs">
                        1
                    </div>
                    <div class="space-y-1.5">
                        <h4 class="font-bold text-base text-slate-900 dark:text-white">Access Portal</h4>
                        <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                            Open this official election page on any desktop, tablet, or smartphone browser anytime during the election period.
                        </p>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 p-6 space-y-4 shadow-sm relative group hover:border-purple-500/50 transition duration-200">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-black text-base shadow-xs">
                        2
                    </div>
                    <div class="space-y-1.5">
                        <h4 class="font-bold text-base text-slate-900 dark:text-white">Verify Masterlist</h4>
                        <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                            Ensure your email is on the official masterlist. If your email is not yet registered, reach out to the <strong class="text-slate-700 dark:text-slate-200">Election Committee of SAKO</strong>.
                        </p>
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 p-6 space-y-4 shadow-sm relative group hover:border-purple-500/50 transition duration-200">
                    <div class="w-12 h-12 rounded-2xl bg-fuchsia-500/10 text-fuchsia-600 dark:text-fuchsia-400 flex items-center justify-center font-black text-base shadow-xs">
                        3
                    </div>
                    <div class="space-y-1.5">
                        <h4 class="font-bold text-base text-slate-900 dark:text-white">Sign In with Google</h4>
                        <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                            Click <strong class="text-slate-700 dark:text-slate-200">Vote Now</strong> to sign in. The system immediately checks your email against the masterlist without creating external accounts.
                        </p>
                    </div>
                </div>

                <!-- Step 4 -->
                <div class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 p-6 space-y-4 shadow-sm relative group hover:border-purple-500/50 transition duration-200">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center font-black text-base shadow-xs">
                        4
                    </div>
                    <div class="space-y-1.5">
                        <h4 class="font-bold text-base text-slate-900 dark:text-white">Agree & Vote</h4>
                        <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                            Read and accept the CDA voting terms, choose your preferred candidates in the ballot booth, and securely submit your vote.
                        </p>
                    </div>
                </div>
            </div>

            <!-- SAKO Election Committee Help Box -->
            <div class="rounded-3xl bg-gradient-to-r from-purple-500/5 via-indigo-500/5 to-purple-500/5 border border-purple-500/20 p-6 sm:p-7 flex flex-col sm:flex-row items-center justify-between gap-4 text-center sm:text-left">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-purple-600 text-white flex items-center justify-center text-xl shrink-0 shadow-md shadow-purple-500/20">
                        <i class="fa-solid fa-users-gear"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-sm sm:text-base text-slate-900 dark:text-white">Not on the Masterlist or Need Assistance?</h4>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                            Please contact your local OPEC coordinator or the <strong class="text-purple-600 dark:text-purple-400">Election Committee of SAKO</strong> for enrollment verification.
                        </p>
                    </div>
                </div>
                @if($election->status === 'active' && !$hasVoted)
                    <a href="{{ route('elections.voter.login', $election) }}"
                        class="shrink-0 inline-flex items-center gap-2 bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs px-5 py-3 rounded-xl shadow-sm transition">
                        <span>Authenticate & Vote</span>
                        <i class="fa-solid fa-chevron-right text-[10px]"></i>
                    </a>
                @endif
            </div>
        </section>

    </main>

    <!-- Footer -->
    <footer class="w-full border-t border-slate-200/80 dark:border-slate-800/80 bg-white/50 dark:bg-slate-900/50 backdrop-blur-md py-6 sm:py-8 mt-12 z-10 transition-colors">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-center sm:text-left">
            <div class="flex items-center gap-3">
                <img src="{{ asset('img/sako-logo-nobg.png') }}" alt="SAKO Logo" class="h-7 w-auto object-contain opacity-80" />
                <p class="text-xs text-slate-500 dark:text-slate-400">
                    &copy; {{ date('Y') }} SAKO Cooperative. Conducted under CDA Standard Election Guidelines.
                </p>
            </div>
            <div class="flex items-center gap-4 text-xs font-semibold text-slate-400 dark:text-slate-500">
                <span>Secret Ballot Protocol</span>
                <span>•</span>
                <span>One Member, One Vote</span>
            </div>
        </div>
    </footer>

    <!-- Theme Toggle Script -->
    <script>
        const themeToggleBtn = document.getElementById('theme-toggle');
        const themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
        const themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

        function updateThemeIcons() {
            if (document.documentElement.classList.contains('dark')) {
                themeToggleLightIcon.classList.remove('hidden');
                themeToggleDarkIcon.classList.add('hidden');
            } else {
                themeToggleLightIcon.classList.add('hidden');
                themeToggleDarkIcon.classList.remove('hidden');
            }
        }

        updateThemeIcons();

        if (themeToggleBtn) {
            themeToggleBtn.addEventListener('click', function() {
                if (document.documentElement.classList.contains('dark')) {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('theme', 'light');
                } else {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('theme', 'dark');
                }
                updateThemeIcons();
            });
        }
    </script>
</body>
</html>
