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

    $imagePath = resource_path('views/imgs/header.png');
    $base64Image = '';
    if (file_exists($imagePath)) {
        $imageData = base64_encode(file_get_contents($imagePath));
        $base64Image = 'data:image/png;base64,' . $imageData;
    }
@endphp

<div class="flex flex-col lg:flex-row gap-8 lg:gap-12 items-start">
    <!-- Left Column: Greeting & Illustration (Sticky on Desktop) -->
    <div class="w-full lg:w-[320px] xl:w-[360px] shrink-0 space-y-6 lg:sticky lg:top-24">
        <!-- Greeting & Info -->
        <div class="space-y-3">
            <div class="flex items-center gap-2 text-xs font-semibold text-slate-400 dark:text-slate-500 tracking-wider uppercase">
                <svg class="w-4 h-4 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m12.728 0l-.707-.707M6.343 6.343l-.707-.707M14 12a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <span>{{ now()->format('l, F j, Y') }}</span>
            </div>
            <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight leading-tight">
                {{ $greeting }}, {{ explode(' ', Auth::user()->name)[0] }}!
            </h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed">
                Select a division workspace from the authorized tabs on the right to launch your applications and tools.
            </p>
        </div>

        <!-- Premium Header Illustration Image (Minimalist & Responsive Frame) -->
        @if($base64Image)
            <div class="rounded-2xl overflow-hidden border border-slate-200/60 dark:border-slate-800/60 bg-white dark:bg-slate-900/40 p-2">
                <img src="{{ $base64Image }}" alt="Portal Header Illustration" class="w-full h-32 sm:h-40 md:h-48 lg:h-auto object-cover rounded-xl" />
            </div>
        @endif
    </div>

    <!-- Right Column: Tabs & Applications Grid -->
    <div class="flex-1 min-w-0 space-y-6 w-full">
        <!-- Tabs & Search Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-2">
            <!-- Mobile Horizontal Swipe Accessibility Tip -->
            <div class="flex items-center gap-1.5 text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-0.5 sm:hidden select-none">
                <svg class="w-3.5 h-3.5 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4-4m-4 4l4 4" />
                </svg>
                <span>Swipe left/right to view workspaces</span>
            </div>

            <!-- Premium Capsule Segmented Tabs Control -->
            <div class="bg-slate-100/70 dark:bg-slate-900/60 p-1 rounded-2xl flex items-center gap-1 overflow-x-auto no-scrollbar shrink-0 w-full sm:w-auto border border-slate-200/30 dark:border-slate-800/30">
                <!-- Board of Directors Tab -->
                <button onclick="switchTab('board')" id="tab-btn-board" class="tab-btn flex items-center px-4 py-2 text-xs sm:text-sm font-medium rounded-xl transition-all duration-300 focus:outline-none whitespace-nowrap shrink-0">
                    <i class="fa-solid fa-building mr-1.5 text-slate-400 dark:text-slate-500"></i>
                    Board of Directors
                </button>

                <!-- Management Tab -->
                <button onclick="switchTab('management')" id="tab-btn-management" class="tab-btn flex items-center px-4 py-2 text-xs sm:text-sm font-medium rounded-xl transition-all duration-300 focus:outline-none whitespace-nowrap shrink-0">
                    <i class="fa-solid fa-users mr-1.5 text-slate-400 dark:text-slate-500"></i>
                    Management
                </button>

                <!-- Committees Tab -->
                <button onclick="switchTab('committees')" id="tab-btn-committees" class="tab-btn flex items-center px-4 py-2 text-xs sm:text-sm font-medium rounded-xl transition-all duration-300 focus:outline-none whitespace-nowrap shrink-0">
                    <i class="fa-solid fa-people-group mr-1.5 text-slate-400 dark:text-slate-500"></i>
                    Committees
                </button>

                <!-- Admin Tab (Only for Super Admin) -->
                @if(Auth::user()->isSuperAdmin())
                    <button onclick="switchTab('admin')" id="tab-btn-admin" class="tab-btn flex items-center px-4 py-2 text-xs sm:text-sm font-medium rounded-xl transition-all duration-300 focus:outline-none whitespace-nowrap shrink-0">
                        <i class="fa-solid fa-users-gear mr-1.5 text-slate-400 dark:text-slate-500"></i>
                        Administration
                    </button>
                @endif
            </div>

            <!-- Search Bar (Minimalist) -->
            <div id="search-container" class="pb-3 sm:pb-0 w-full sm:w-60 shrink-0 opacity-0 pointer-events-none transition-all duration-300">
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 dark:text-slate-500">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </span>
                    <input 
                        type="text" 
                        id="app-search" 
                        oninput="filterApps()" 
                        placeholder="Search applications..." 
                        class="w-full pl-9 pr-4 py-2 text-xs bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-lg focus:outline-none focus:ring-1 focus:ring-slate-950/10 dark:focus:ring-white/10 focus:border-slate-400 dark:focus:border-slate-600 text-slate-950 dark:text-slate-50 transition-all placeholder-slate-400 dark:placeholder-slate-500"
                    />
                </div>
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
                    <div class="app-card group relative bg-white dark:bg-slate-900 rounded-xl border border-slate-200/60 dark:border-slate-800/60 p-6 shadow-none hover:shadow-sm hover:border-slate-300 dark:hover:border-slate-700/80 hover:bg-slate-50/20 dark:hover:bg-slate-950/20 transition-all duration-200 flex flex-col items-start justify-between text-left">
                        <div>
                            <!-- Minimal Outline Icon Box -->
                            <div class="w-10 h-10 rounded-xl border border-slate-200/60 dark:border-slate-800 text-slate-400 dark:text-slate-500 flex items-center justify-center shrink-0 mb-4 bg-slate-50/50 dark:bg-slate-950/50 group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors duration-200">
                                <i class="fa-solid fa-calendar-check text-base"></i>
                            </div>

                            <!-- Title -->
                            <h3 class="app-title text-base font-semibold text-slate-900 dark:text-white tracking-tight group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors duration-200">
                                Event Management
                            </h3>

                            <!-- 1-sentence Description -->
                            <p class="app-desc text-xs text-slate-500 dark:text-slate-400 leading-relaxed mt-2">
                                Coordinate assemblies, schedule corporate gatherings, and approve registration RSVPs in a centralized hub.
                            </p>
                        </div>

                        <!-- Click area expansion link -->
                        <a href="{{ route('committees.events.index') }}" class="after:absolute after:inset-0 focus:outline-none"></a>
                    </div>

                    <!-- Election Management Card -->
                    <div class="app-card group relative bg-white dark:bg-slate-900 rounded-xl border border-slate-200/60 dark:border-slate-800/60 p-6 shadow-none hover:shadow-sm hover:border-slate-300 dark:hover:border-slate-700/80 hover:bg-slate-50/20 dark:hover:bg-slate-950/20 transition-all duration-200 flex flex-col items-start justify-between text-left">
                        <div>
                            <!-- Minimal Outline Icon Box -->
                            <div class="w-10 h-10 rounded-xl border border-slate-200/60 dark:border-slate-800 text-slate-400 dark:text-slate-500 flex items-center justify-center shrink-0 mb-4 bg-slate-50/50 dark:bg-slate-950/50 group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors duration-200">
                                <i class="fa-solid fa-people-line text-base"></i>
                            </div>

                            <!-- Title -->
                            <h3 class="app-title text-base font-semibold text-slate-900 dark:text-white tracking-tight group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors duration-200">
                                Election Management
                            </h3>

                            <!-- 1-sentence Description -->
                            <p class="app-desc text-xs text-slate-500 dark:text-slate-400 leading-relaxed mt-2">
                                Establish secure ballots, register designated candidates, and track real-time voter turnout.
                            </p>
                        </div>

                        <!-- Click area expansion link -->
                        <a href="{{ route('committees.election.index') }}" class="after:absolute after:inset-0 focus:outline-none"></a>
                    </div>
                </div>

                <!-- Search Empty State -->
                <div class="search-empty-state hidden bg-white dark:bg-slate-900/50 rounded-[20px] border border-dashed border-slate-200 dark:border-slate-800 p-12 text-center max-w-md mx-auto shadow-sm">
                    <div class="w-12 h-12 rounded-full bg-slate-50 dark:bg-slate-800 text-slate-400 dark:text-slate-500 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-base font-semibold text-slate-900 dark:text-white">No matches found</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">We couldn't find any applications in this tab matching your search query.</p>
                </div>
            </div>

            <!-- Panel 4: Admin (Only for Super Admin) -->
            @if(Auth::user()->isSuperAdmin())
                <div id="panel-admin" class="tab-panel hidden space-y-6">
                    <!-- App Grid -->
                    <div class="app-grid grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">
                        <!-- Platform Administration Cockpit Card -->
                        <div class="app-card group relative bg-white dark:bg-slate-900 rounded-xl border border-slate-200/60 dark:border-slate-800/80 p-6 shadow-none hover:shadow-sm hover:border-slate-300 dark:hover:border-slate-700/80 hover:bg-slate-50/20 dark:hover:bg-slate-950/20 transition-all duration-200 flex flex-col items-start justify-between text-left">
                            <div>
                                <!-- Minimal Outline Icon Box -->
                                <div class="w-10 h-10 rounded-xl border border-slate-200/60 dark:border-slate-800 text-slate-400 dark:text-slate-500 flex items-center justify-center shrink-0 mb-4 bg-slate-50/50 dark:bg-slate-950/50 group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors duration-200">
                                    <i class="fa-solid fa-users-gear text-base"></i>
                                </div>

                                <!-- Title -->
                                <h3 class="app-title text-base font-semibold text-slate-900 dark:text-white tracking-tight group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors duration-200">
                                    Platform Administration
                                </h3>

                                <!-- 1-sentence Description -->
                                <p class="app-desc text-xs text-slate-500 dark:text-slate-400 leading-relaxed mt-2">
                                    Oversee authorized accounts, configure custom committees, and designate corporate titles.
                                </p>
                            </div>

                            <!-- Click area expansion link -->
                            <a href="{{ route('admin.index') }}" class="after:absolute after:inset-0 focus:outline-none"></a>
                        </div>
                    </div>

                    <!-- Search Empty State -->
                    <div class="search-empty-state hidden bg-white dark:bg-slate-900/50 rounded-[20px] border border-dashed border-slate-200 dark:border-slate-800 p-12 text-center max-w-md mx-auto shadow-sm">
                        <div class="w-12 h-12 rounded-full bg-slate-50 dark:bg-slate-800 text-slate-400 dark:text-slate-500 flex items-center justify-center mx-auto mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-base font-semibold text-slate-900 dark:text-white">No matches found</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">We couldn't find any applications in this tab matching your search query.</p>
                    </div>
                </div>
            @endif
        </div>
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

        // Reset tab buttons classes (Premium Capsule)
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('bg-white', 'dark:bg-slate-800', 'text-purple-600', 'dark:text-purple-400', 'shadow-sm', 'border-slate-200/40', 'dark:border-slate-700/40', 'font-bold');
            btn.classList.add('border-transparent', 'text-slate-500', 'dark:text-slate-400', 'hover:text-slate-900', 'dark:hover:text-slate-200', 'font-medium');
        });

        // Show active panel
        const activePanel = document.getElementById('panel-' + tabId);
        if (activePanel) {
            activePanel.classList.remove('hidden');
        }

        // Highlight active button (Premium Capsule)
        const activeBtn = document.getElementById('tab-btn-' + tabId);
        if (activeBtn) {
            activeBtn.classList.remove('border-transparent', 'text-slate-500', 'dark:text-slate-400', 'hover:text-slate-900', 'dark:hover:text-slate-200', 'font-medium');
            activeBtn.classList.add('bg-white', 'dark:bg-slate-800', 'text-purple-600', 'dark:text-purple-400', 'shadow-sm', 'border-slate-200/40', 'dark:border-slate-700/40', 'font-bold');
        }

        // Search Bar Visibility and Reset Logic
        const searchContainer = document.getElementById('search-container');
        const searchInput = document.getElementById('app-search');
        if (searchInput) {
            searchInput.value = '';
        }

        if (activePanel && searchContainer) {
            const hasCards = activePanel.querySelectorAll('.app-card').length > 0;
            if (hasCards) {
                searchContainer.classList.remove('opacity-0', 'pointer-events-none');
                searchContainer.classList.add('opacity-100');
                
                // Reset filter state for the new panel
                const grid = activePanel.querySelector('.app-grid');
                if (grid) grid.classList.remove('hidden');
                const searchEmpty = activePanel.querySelector('.search-empty-state');
                if (searchEmpty) searchEmpty.classList.add('hidden');
                activePanel.querySelectorAll('.app-card').forEach(card => card.classList.remove('hidden'));
            } else {
                searchContainer.classList.add('opacity-0', 'pointer-events-none');
                searchContainer.classList.remove('opacity-100');
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

    function filterApps() {
        const query = document.getElementById('app-search').value.toLowerCase().trim();
        const activePanel = document.querySelector('.tab-panel:not(.hidden)');
        if (!activePanel) return;

        const cards = activePanel.querySelectorAll('.app-card');
        const grid = activePanel.querySelector('.app-grid');
        const searchEmpty = activePanel.querySelector('.search-empty-state');

        let matchCount = 0;

        cards.forEach(card => {
            const title = card.querySelector('.app-title').textContent.toLowerCase();
            const desc = card.querySelector('.app-desc').textContent.toLowerCase();

            if (title.includes(query) || desc.includes(query)) {
                card.classList.remove('hidden');
                matchCount++;
            } else {
                card.classList.add('hidden');
            }
        });

        if (cards.length > 0) {
            if (matchCount === 0) {
                if (grid) grid.classList.add('hidden');
                if (searchEmpty) searchEmpty.classList.remove('hidden');
            } else {
                if (grid) grid.classList.remove('hidden');
                if (searchEmpty) searchEmpty.classList.add('hidden');
            }
        }
    }
</script>
@endsection