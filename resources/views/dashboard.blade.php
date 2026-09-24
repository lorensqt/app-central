@extends('layouts.app')

@section('title', 'Select Portal')

@section('content')
@php
    $hour = now()->hour;
    $greeting = 'Welcome back';
    if ($hour >= 5 && $hour < 12) {
        $greeting = 'Good morning';
    } elseif ($hour >= 12 && $hour < 17) {
        $greeting = 'Good afternoon';
    } elseif ($hour >= 17 || $hour < 5) {
        $greeting = 'Good evening';
    }

    $imagePath = resource_path('views/imgs/dashboard.gif');
    $base64Image = '';
    if (file_exists($imagePath)) {
        $imageData = base64_encode(file_get_contents($imagePath));
        $base64Image = 'data:image/gif;base64,' . $imageData;
    }
@endphp

<div class="flex flex-col lg:flex-row gap-8 lg:gap-12 items-start pb-24 sm:pb-0">
    <!-- Left Column: Greeting & Illustration (Sticky on Desktop) -->
    <div class="w-full lg:w-[320px] xl:w-[360px] shrink-0 space-y-6 lg:sticky lg:top-24">
        <!-- Greeting & Info -->
        <div class="space-y-3.5">
            <div class="flex items-center gap-2 text-[10px] sm:text-xs font-semibold text-slate-400 dark:text-slate-500 tracking-wider uppercase">
                <svg class="w-4 h-4 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m12.728 0l-.707-.707M6.343 6.343l-.707-.707M14 12a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <span>{{ now()->format('l, F j, Y') }}</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight leading-tight">
                {{ $greeting }}, {{ explode(' ', Auth::user()->name)[0] }}!
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 leading-relaxed">
                Select a division workspace from the authorized tabs on the right to launch your applications and tools.
            </p>
            
            <!-- Active Tab Mobile Indicator (Visual Signpost for Mobile View) -->
            <div class="flex sm:hidden items-center gap-2 mt-3 pt-3 border-t border-slate-100 dark:border-slate-800/60">
                <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Active Workspace:</span>
                <span id="active-tab-indicator-mobile" class="text-xs font-extrabold tracking-tight transition-all duration-300"></span>
            </div>
        </div>

        <!-- Premium Header Illustration Image (Minimalist & Responsive Frame) -->
        @if($base64Image)
            <div class="hidden lg:block rounded-2xl overflow-hidden border border-slate-200/60 dark:border-slate-800/60 bg-white dark:bg-slate-900/40 p-2">
                <img src="{{ $base64Image }}" alt="Portal Header Illustration" class="w-full h-32 sm:h-40 md:h-48 lg:h-auto object-cover rounded-xl" />
            </div>
        @endif
    </div>

    <!-- Right Column: Tabs & Applications Grid -->
    <div class="flex-1 min-w-0 space-y-6 w-full">
        <!-- Tabs & Search Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-start gap-4 pb-2">
            <!-- Premium Capsule Segmented Tabs Control -->
            <div class="hidden sm:flex bg-slate-100/60 dark:bg-slate-950/45 backdrop-blur-md p-1.5 rounded-2xl items-center gap-1 overflow-x-auto no-scrollbar shrink-0 w-full sm:w-auto border border-slate-200/50 dark:border-slate-800/40 ring-1 ring-slate-100/10 dark:ring-white/5 shadow-xs">
                <!-- Board of Directors Tab -->
                <button onclick="switchTab('board')" id="tab-btn-board" class="tab-btn tab-btn-desktop flex items-center px-5 py-3 text-sm font-semibold rounded-xl transition-all duration-300 focus:outline-none whitespace-nowrap shrink-0 active:scale-95">
                    <i class="fa-solid fa-building mr-1.5 opacity-85"></i>
                    Board of Directors
                </button>

                <!-- Management Tab -->
                <button onclick="switchTab('management')" id="tab-btn-management" class="tab-btn tab-btn-desktop flex items-center px-5 py-3 text-sm font-semibold rounded-xl transition-all duration-300 focus:outline-none whitespace-nowrap shrink-0 active:scale-95">
                    <i class="fa-solid fa-users mr-1.5 opacity-85"></i>
                    Management
                </button>

                <!-- Committees Tab -->
                <button onclick="switchTab('committees')" id="tab-btn-committees" class="tab-btn tab-btn-desktop flex items-center px-5 py-3 text-sm font-semibold rounded-xl transition-all duration-300 focus:outline-none whitespace-nowrap shrink-0 active:scale-95">
                    <i class="fa-solid fa-people-group mr-1.5 opacity-85"></i>
                    Committees
                </button>

                <!-- Admin Tab (Only for Super Admin) -->
                @if(Auth::user()->isSuperAdmin())
                    <button onclick="switchTab('admin')" id="tab-btn-admin" class="tab-btn tab-btn-desktop flex items-center px-5 py-3 text-sm font-semibold rounded-xl transition-all duration-300 focus:outline-none whitespace-nowrap shrink-0 active:scale-95">
                        <i class="fa-solid fa-users-gear mr-1.5 opacity-85"></i>
                        Administration
                    </button>
                @endif
            </div>
        </div>

        <!-- Tab Panels -->
        <div>
            <!-- Panel 1: Board of Directors -->
            <div id="panel-board" class="tab-panel hidden">
                <div class="bg-white dark:bg-slate-900/50 rounded-[20px] border border-dashed border-slate-200 dark:border-slate-800 p-12 text-center max-w-md mx-auto shadow-sm">
                    <div class="w-12 h-12 rounded-full bg-slate-50 dark:bg-slate-800 text-slate-400 dark:text-slate-500 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                    </div>
                    <h3 class="text-base font-semibold text-slate-900 dark:text-white">No applications yet</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">There are currently no active applications in the Board of Directors workspace.</p>
                </div>
            </div>

            <!-- Panel 2: Management -->
            <div id="panel-management" class="tab-panel hidden">
                <div class="bg-white dark:bg-slate-900/50 rounded-[20px] border border-dashed border-slate-200 dark:border-slate-800 p-12 text-center max-w-md mx-auto shadow-sm">
                    <div class="w-12 h-12 rounded-full bg-slate-50 dark:bg-slate-800 text-slate-400 dark:text-slate-500 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                    </div>
                    <h3 class="text-base font-semibold text-slate-900 dark:text-white">No applications yet</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">There are currently no active applications in the Management workspace.</p>
                </div>
            </div>

            <!-- Panel 3: Committees -->
            <div id="panel-committees" class="tab-panel hidden space-y-6">
                <!-- App Grid -->
                <div class="app-grid grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">
                    <!-- Event Management Card -->
                    <div class="app-card group relative bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800/80 p-6 shadow-none hover:shadow-lg hover:shadow-emerald-500/5 hover:border-emerald-500/40 dark:hover:border-emerald-500/40 hover:bg-emerald-50/5 dark:hover:bg-emerald-950/5 transition-all duration-300 ease-[cubic-bezier(0.16,1,0.3,1)] hover:-translate-y-1.5 flex flex-col justify-between text-left min-h-[220px] overflow-hidden">
                        <!-- Subtle Background Radial Glow -->
                        <div class="absolute -right-10 -bottom-10 w-32 h-32 rounded-full bg-emerald-500/5 dark:bg-emerald-500/2 blur-2xl group-hover:bg-emerald-500/10 transition-colors duration-300"></div>

                        <div class="w-full">
                            <!-- Premium Icon Box -->
                            <div class="w-11 h-11 rounded-xl border border-emerald-100 dark:border-emerald-900/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0 mb-5 bg-emerald-50/30 dark:bg-emerald-950/30 group-hover:bg-gradient-to-tr group-hover:from-emerald-500 group-hover:to-teal-600 group-hover:text-white group-hover:border-transparent group-hover:scale-110 group-hover:rotate-6 transition-all duration-300 shadow-sm shadow-emerald-500/5">
                                <i class="fa-solid fa-calendar-check text-base"></i>
                            </div>

                            <!-- Title -->
                            <h3 class="app-title text-base font-bold text-slate-900 dark:text-white tracking-tight group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors duration-200">
                                Event Management
                            </h3>

                            <!-- 1-sentence Description -->
                            <p class="app-desc text-xs text-slate-500 dark:text-slate-400 leading-relaxed mt-2.5">
                                Coordinate assemblies, schedule corporate gatherings, and approve registration RSVPs in a centralized hub.
                            </p>
                        </div>

                        <!-- Launch Workspace Footer CTA -->
                        <div class="mt-6 w-full flex items-center gap-1.5 text-[11px] font-bold text-emerald-600 dark:text-emerald-400 tracking-wider uppercase select-none opacity-85 group-hover:opacity-100 transition-opacity duration-200">
                            <span>Launch workspace</span>
                            <svg class="w-3.5 h-3.5 transform group-hover:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>

                        <!-- Click area expansion link -->
                        <a href="{{ route('committees.events.index') }}" class="after:absolute after:inset-0 focus:outline-none"></a>
                    </div>

                    <!-- Election Management Card -->
                    <div class="app-card group relative bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800/80 p-6 shadow-none hover:shadow-lg hover:shadow-indigo-500/5 hover:border-indigo-500/40 dark:hover:border-indigo-500/40 hover:bg-indigo-50/5 dark:hover:bg-indigo-950/5 transition-all duration-300 ease-[cubic-bezier(0.16,1,0.3,1)] hover:-translate-y-1.5 flex flex-col justify-between text-left min-h-[220px] overflow-hidden">
                        <!-- Subtle Background Radial Glow -->
                        <div class="absolute -right-10 -bottom-10 w-32 h-32 rounded-full bg-indigo-500/5 dark:bg-indigo-500/2 blur-2xl group-hover:bg-indigo-500/10 transition-colors duration-300"></div>

                        <div class="w-full">
                            <!-- Premium Icon Box -->
                            <div class="w-11 h-11 rounded-xl border border-indigo-100 dark:border-indigo-900/40 text-indigo-600 dark:text-indigo-400 flex items-center justify-center shrink-0 mb-5 bg-indigo-50/30 dark:bg-indigo-950/30 group-hover:bg-gradient-to-tr group-hover:from-indigo-500 group-hover:to-purple-600 group-hover:text-white group-hover:border-transparent group-hover:scale-110 group-hover:rotate-6 transition-all duration-300 shadow-sm shadow-indigo-500/5">
                                <i class="fa-solid fa-people-line text-base"></i>
                            </div>

                            <!-- Title -->
                            <h3 class="app-title text-base font-bold text-slate-900 dark:text-white tracking-tight group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors duration-200">
                                Election Management
                            </h3>

                            <!-- 1-sentence Description -->
                            <p class="app-desc text-xs text-slate-500 dark:text-slate-400 leading-relaxed mt-2.5">
                                Establish secure ballots, register designated candidates, and track real-time voter turnout.
                            </p>
                        </div>

                        <!-- Launch Workspace Footer CTA -->
                        <div class="mt-6 w-full flex items-center gap-1.5 text-[11px] font-bold text-indigo-600 dark:text-indigo-400 tracking-wider uppercase select-none opacity-85 group-hover:opacity-100 transition-opacity duration-200">
                            <span>Launch workspace</span>
                            <svg class="w-3.5 h-3.5 transform group-hover:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>

                        <!-- Click area expansion link -->
                        <a href="{{ route('committees.election.index') }}" class="after:absolute after:inset-0 focus:outline-none"></a>
                    </div>
                </div>
            </div>

            <!-- Panel 4: Admin (Only for Super Admin) -->
            @if(Auth::user()->isSuperAdmin())
                <div id="panel-admin" class="tab-panel hidden space-y-6">
                    <!-- App Grid -->
                    <div class="app-grid grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">
                        <!-- Platform Administration Cockpit Card -->
                        <div class="app-card group relative bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800/80 p-6 shadow-none hover:shadow-lg hover:shadow-purple-500/5 hover:border-purple-500/40 dark:hover:border-purple-500/40 hover:bg-purple-50/5 dark:hover:bg-purple-950/5 transition-all duration-300 ease-[cubic-bezier(0.16,1,0.3,1)] hover:-translate-y-1.5 flex flex-col justify-between text-left min-h-[220px] overflow-hidden">
                            <!-- Subtle Background Radial Glow -->
                            <div class="absolute -right-10 -bottom-10 w-32 h-32 rounded-full bg-purple-500/5 dark:bg-purple-500/2 blur-2xl group-hover:bg-purple-500/10 transition-colors duration-300"></div>

                            <div class="w-full">
                                <!-- Premium Icon Box -->
                                <div class="w-11 h-11 rounded-xl border border-purple-100 dark:border-purple-900/40 text-purple-600 dark:text-purple-400 flex items-center justify-center shrink-0 mb-5 bg-purple-50/30 dark:bg-purple-950/30 group-hover:bg-gradient-to-tr group-hover:from-purple-500 group-hover:to-indigo-600 group-hover:text-white group-hover:border-transparent group-hover:scale-110 group-hover:rotate-6 transition-all duration-300 shadow-sm shadow-purple-500/5">
                                    <i class="fa-solid fa-users-gear text-base"></i>
                                </div>

                                <!-- Title -->
                                <h3 class="app-title text-base font-bold text-slate-900 dark:text-white tracking-tight group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors duration-200">
                                    Platform Administration
                                </h3>

                                <!-- 1-sentence Description -->
                                <p class="app-desc text-xs text-slate-500 dark:text-slate-400 leading-relaxed mt-2.5">
                                    Oversee authorized accounts, configure custom committees, and designate corporate titles.
                                </p>
                            </div>

                            <!-- Launch Workspace Footer CTA -->
                            <div class="mt-6 w-full flex items-center gap-1.5 text-[11px] font-bold text-purple-600 dark:text-purple-400 tracking-wider uppercase select-none opacity-85 group-hover:opacity-100 transition-opacity duration-200">
                                <span>Launch workspace</span>
                                <svg class="w-3.5 h-3.5 transform group-hover:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                                </svg>
                            </div>

                            <!-- Click area expansion link -->
                            <a href="{{ route('admin.index') }}" class="after:absolute after:inset-0 focus:outline-none"></a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Premium Mobile Bottom Floating Nav Bar (Icons & Labels) -->
    <div class="fixed sm:hidden bottom-6 left-1/2 -translate-x-1/2 z-50 w-[calc(100%-2rem)] max-w-sm bg-white/85 dark:bg-slate-900/85 backdrop-blur-xl border border-slate-200/60 dark:border-slate-800/70 p-1.5 rounded-[24px] shadow-2xl shadow-slate-950/15 dark:shadow-black/60 flex items-center justify-around ring-1 ring-white/10">
        <!-- Board of Directors Button -->
        <button onclick="switchTab('board')" id="tab-btn-board-mobile" class="tab-btn-mobile flex-1 flex flex-col items-center justify-center py-2 px-1 rounded-2xl transition-all duration-300 active:scale-95 text-slate-400 dark:text-slate-500 hover:text-slate-900 dark:hover:text-slate-200" title="Board of Directors">
            <i class="fa-solid fa-building text-base mb-1"></i>
            <span class="text-[10px] font-bold tracking-tight leading-none">BOD</span>
        </button>

        <!-- Management Button -->
        <button onclick="switchTab('management')" id="tab-btn-management-mobile" class="tab-btn-mobile flex-1 flex flex-col items-center justify-center py-2 px-1 rounded-2xl transition-all duration-300 active:scale-95 text-slate-400 dark:text-slate-500 hover:text-slate-900 dark:hover:text-slate-200" title="Management">
            <i class="fa-solid fa-users text-base mb-1"></i>
            <span class="text-[10px] font-bold tracking-tight leading-none">Management</span>
        </button>

        <!-- Committees Button -->
        <button onclick="switchTab('committees')" id="tab-btn-committees-mobile" class="tab-btn-mobile flex-1 flex flex-col items-center justify-center py-2 px-1 rounded-2xl transition-all duration-300 active:scale-95 text-slate-400 dark:text-slate-500 hover:text-slate-900 dark:hover:text-slate-200" title="Committees">
            <i class="fa-solid fa-people-group text-base mb-1"></i>
            <span class="text-[10px] font-bold tracking-tight leading-none">Committee</span>
        </button>

        <!-- Admin Button (Only for Super Admin) -->
        @if(Auth::user()->isSuperAdmin())
            <button onclick="switchTab('admin')" id="tab-btn-admin-mobile" class="tab-btn-mobile flex-1 flex flex-col items-center justify-center py-2 px-1 rounded-2xl transition-all duration-300 active:scale-95 text-slate-400 dark:text-slate-500 hover:text-slate-900 dark:hover:text-slate-200" title="Administration">
                <i class="fa-solid fa-users-gear text-base mb-1"></i>
                <span class="text-[10px] font-bold tracking-tight leading-none">Admin</span>
            </button>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
    function switchTab(tabId) {
        // Hide all panels
        document.querySelectorAll('.tab-panel').forEach(panel => {
            panel.classList.add('hidden');
        });

        // 1. Reset and style Desktop tab buttons
        const activeClassesDesktop = ['bg-white', 'dark:bg-slate-800', 'shadow-xs', 'border-slate-200/50', 'dark:border-slate-700/50', 'font-bold'];
        const themeTextsDesktop = [
            'text-blue-600', 'dark:text-blue-400',
            'text-teal-600', 'dark:text-teal-400',
            'text-indigo-600', 'dark:text-indigo-400',
            'text-purple-600', 'dark:text-purple-400'
        ];

        document.querySelectorAll('.tab-btn-desktop').forEach(btn => {
            btn.classList.remove(...activeClassesDesktop, ...themeTextsDesktop);
            btn.classList.add('border-transparent', 'text-slate-500', 'dark:text-slate-400', 'hover:text-slate-900', 'dark:hover:text-slate-200', 'font-medium');
        });

        const activeBtnDesktop = document.getElementById('tab-btn-' + tabId);
        if (activeBtnDesktop) {
            activeBtnDesktop.classList.remove('border-transparent', 'text-slate-500', 'dark:text-slate-400', 'hover:text-slate-900', 'dark:hover:text-slate-200', 'font-medium');
            activeBtnDesktop.classList.add(...activeClassesDesktop);

            // Add dynamic theme accents
            if (tabId === 'board') {
                activeBtnDesktop.classList.add('text-blue-600', 'dark:text-blue-400');
            } else if (tabId === 'management') {
                activeBtnDesktop.classList.add('text-teal-600', 'dark:text-teal-400');
            } else if (tabId === 'committees') {
                activeBtnDesktop.classList.add('text-indigo-600', 'dark:text-indigo-400');
            } else if (tabId === 'admin') {
                activeBtnDesktop.classList.add('text-purple-600', 'dark:text-purple-400');
            }
        }

        // 2. Reset and style Mobile tab buttons (iOS style backdrops)
        const activeClassesMobile = ['bg-slate-100', 'dark:bg-slate-800/80', 'shadow-xs', 'font-bold'];
        const themeTextsMobile = [
            'text-blue-500', 'dark:text-blue-400',
            'text-teal-500', 'dark:text-teal-400',
            'text-indigo-500', 'dark:text-indigo-400',
            'text-purple-500', 'dark:text-purple-400'
        ];

        document.querySelectorAll('.tab-btn-mobile').forEach(btn => {
            btn.classList.remove(...activeClassesMobile, ...themeTextsMobile);
            btn.classList.add('text-slate-400', 'dark:text-slate-500', 'hover:text-slate-900', 'dark:hover:text-slate-200');
        });

        const activeBtnMobile = document.getElementById('tab-btn-' + tabId + '-mobile');
        if (activeBtnMobile) {
            activeBtnMobile.classList.remove('text-slate-400', 'dark:text-slate-500');
            activeBtnMobile.classList.add(...activeClassesMobile);

            // Add dynamic theme accents for mobile buttons
            if (tabId === 'board') {
                activeBtnMobile.classList.add('text-blue-500', 'dark:text-blue-400');
            } else if (tabId === 'management') {
                activeBtnMobile.classList.add('text-teal-500', 'dark:text-teal-400');
            } else if (tabId === 'committees') {
                activeBtnMobile.classList.add('text-indigo-500', 'dark:text-indigo-400');
            } else if (tabId === 'admin') {
                activeBtnMobile.classList.add('text-purple-500', 'dark:text-purple-400');
            }
        }

        // 3. Show active panel
        const activePanel = document.getElementById('panel-' + tabId);
        if (activePanel) {
            activePanel.classList.remove('hidden');
        }

        // 4. Update Mobile Tab Name Indicator
        const indicatorMobile = document.getElementById('active-tab-indicator-mobile');
        if (indicatorMobile) {
            indicatorMobile.className = 'text-xs font-extrabold tracking-tight transition-all duration-300';
            if (tabId === 'board') {
                indicatorMobile.textContent = 'Board of Directors';
                indicatorMobile.classList.add('text-blue-600', 'dark:text-blue-400');
            } else if (tabId === 'management') {
                indicatorMobile.textContent = 'Management';
                indicatorMobile.classList.add('text-teal-600', 'dark:text-teal-400');
            } else if (tabId === 'committees') {
                indicatorMobile.textContent = 'Committees';
                indicatorMobile.classList.add('text-indigo-600', 'dark:text-indigo-400');
            } else if (tabId === 'admin') {
                indicatorMobile.textContent = 'Administration';
                indicatorMobile.classList.add('text-purple-600', 'dark:text-purple-400');
            }
        }

        // Keep current tab in URL state for preservation on redirect/refresh
        const url = new URL(window.location);
        url.searchParams.set('tab', tabId);
        window.history.pushState({}, '', url);
    }

    // Initialize from URL search parameter or default to 'board'
    document.addEventListener('DOMContentLoaded', () => {
        const urlParams = new URLSearchParams(window.location.search);
        let tab = urlParams.get('tab');
        
        // Safety check if tab is admin but user is not admin
        @if(!Auth::user()->isSuperAdmin())
            if (tab === 'admin') tab = 'board';
        @endif

        if (!tab || !document.getElementById('panel-' + tab)) {
            tab = 'board';
        }
        switchTab(tab);
    });
</script>
@endsection