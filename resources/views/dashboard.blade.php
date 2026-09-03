@extends('layouts.app')

@section('title', 'Select Portal')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="border-b border-slate-200/60 dark:border-slate-800/80 pb-6">
        <h1 class="text-3xl font-bold text-slate-900 dark:text-white tracking-tight">Application Portal</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1.5">Select a division workspace from the authorized tabs below.</p>
    </div>

    <!-- Tabs Segmented Control -->
    <div class="border-b border-slate-200 dark:border-slate-800">
        <nav class="-mb-px flex space-x-8 overflow-x-auto custom-scrollbar" aria-label="Tabs">
            <!-- Board of Directors Tab -->
            <button onclick="switchTab('board')" id="tab-btn-board" class="tab-btn border-b-2 py-4 px-1 text-sm font-medium transition-all focus:outline-none whitespace-nowrap">
                Board of Directors
            </button>

            <!-- Management Tab -->
            <button onclick="switchTab('management')" id="tab-btn-management" class="tab-btn border-b-2 py-4 px-1 text-sm font-medium transition-all focus:outline-none whitespace-nowrap">
                Management
            </button>

            <!-- Committees Tab -->
            <button onclick="switchTab('committees')" id="tab-btn-committees" class="tab-btn border-b-2 py-4 px-1 text-sm font-medium transition-all focus:outline-none whitespace-nowrap">
                Committees
            </button>

            <!-- Admin Tab (Only for Super Admin) -->
            @if(Auth::user()->isSuperAdmin())
                <button onclick="switchTab('admin')" id="tab-btn-admin" class="tab-btn border-b-2 py-4 px-1 text-sm font-medium transition-all focus:outline-none flex items-center gap-1.5 whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    System Admin
                </button>
            @endif
        </nav>
    </div>

    <!-- Tab Panels -->
    <div>
        <!-- Panel 1: Board of Directors -->
        <div id="panel-board" class="tab-panel hidden">
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800/80 p-12 text-center max-w-xl mx-auto shadow-sm">
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
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800/80 p-12 text-center max-w-xl mx-auto shadow-sm">
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
        <div id="panel-committees" class="tab-panel hidden">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Single Committee Events App Card -->
                <div class="group bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800/80 p-6 shadow-sm hover:shadow-md dark:hover:border-slate-700 transition-all duration-300 flex flex-col justify-between text-left">
                    <div>
                        <div class="w-10 h-10 rounded-xl bg-purple-50 dark:bg-purple-950/40 text-purple-600 dark:text-purple-400 flex items-center justify-center font-semibold mb-5 group-hover:bg-purple-600 group-hover:text-white dark:group-hover:text-white transition-all duration-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">Events Management - Committee</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-2 leading-relaxed">Dynamic event scheduler, attendee registration approval requests, and assembly planning dashboards for standing corporate committees.</p>
                    </div>
                    <div class="mt-6 pt-5 border-t border-slate-100 dark:border-slate-800/80">
                        <a href="{{ route('committees.events.index') }}" class="w-full inline-flex items-center justify-center text-sm font-semibold py-2 px-4 rounded-lg bg-slate-900 dark:bg-slate-800 text-white dark:text-slate-200 hover:bg-slate-800 dark:hover:bg-slate-700 transition duration-150">
                            Launch App
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Panel 4: Admin (Only for Super Admin) -->
        @if(Auth::user()->isSuperAdmin())
            <div id="panel-admin" class="tab-panel hidden grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Platform Administration Cockpit Card -->
                <div class="group bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800/80 p-6 shadow-sm hover:shadow-md dark:hover:border-slate-700 transition-all duration-300 flex flex-col justify-between">
                    <div>
                        <div class="w-10 h-10 rounded-xl bg-slate-900 dark:bg-slate-800 text-white dark:text-slate-300 flex items-center justify-center font-semibold mb-5 group-hover:bg-slate-800 dark:group-hover:bg-slate-700 transition-all duration-300 shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white group-hover:text-slate-700 dark:group-hover:text-slate-300 transition-colors">Platform Administration</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-2 leading-relaxed">Consolidated administrative cockpit. Manually authorize email accounts, manage custom corporate committees, and define role designations in a unified cockpit.</p>
                    </div>
                    <div class="mt-6 pt-5 border-t border-slate-100 dark:border-slate-800/80">
                        <a href="{{ route('admin.index') }}" class="w-full inline-flex items-center justify-center text-sm font-semibold py-2 px-4 rounded-lg bg-slate-900 dark:bg-slate-800 text-white dark:text-slate-200 hover:bg-slate-800 dark:hover:bg-slate-700 transition duration-150">
                            Launch Cockpit
                        </a>
                    </div>
                </div>
            </div>
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

        // Reset tab buttons classes
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('border-purple-600', 'dark:border-purple-500', 'text-purple-600', 'dark:text-purple-400', 'font-semibold');
            btn.classList.add('border-transparent', 'text-slate-500', 'dark:text-slate-400', 'hover:border-slate-300', 'dark:hover:border-slate-700', 'hover:text-slate-700', 'dark:hover:text-slate-200');
        });

        // Show active panel
        const activePanel = document.getElementById('panel-' + tabId);
        if (activePanel) {
            activePanel.classList.remove('hidden');
        }

        // Highlight active button
        const activeBtn = document.getElementById('tab-btn-' + tabId);
        if (activeBtn) {
            activeBtn.classList.remove('border-transparent', 'text-slate-500', 'dark:text-slate-400', 'hover:border-slate-300', 'dark:hover:border-slate-700', 'hover:text-slate-700', 'dark:hover:text-slate-200');
            activeBtn.classList.add('border-purple-600', 'dark:border-purple-500', 'text-purple-600', 'dark:text-purple-400', 'font-semibold');
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