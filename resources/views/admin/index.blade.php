@extends('layouts.app')

@section('title', 'Platform Administration Cockpit')

@section('content')
    <!-- Custom Style Sheet -->
    <style>
        /* Panel transition animation */
        @keyframes panelFadeInUp {
            from {
                opacity: 0;
                transform: translateY(12px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .admin-tab-panel:not(.hidden) {
            animation: panelFadeInUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        /* Custom scrollbar for horizontal sub-tabs and lists */
        .custom-scrollbar::-webkit-scrollbar {
            height: 5px;
            width: 5px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(148, 163, 184, 0.2);
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(148, 163, 184, 0.4);
        }

        .dark .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(148, 163, 184, 0.15);
        }

        .dark .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(148, 163, 184, 0.3);
        }
    </style>

    <div class="space-y-8">
        <!-- Breadcrumbs -->
        <div class="flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400">
            <a href="{{ route('dashboard') }}?tab=admin" class="hover:text-slate-900 dark:hover:text-white transition-colors">Portal</a>
            <svg class="w-4 h-4 text-slate-300 dark:text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
            <span class="text-slate-900 dark:text-slate-300 font-medium">Administration Cockpit</span>
        </div>

        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between border-b border-slate-200/80 dark:border-slate-800 pb-6 gap-6">
            <div class="flex items-start sm:items-center gap-4">
                <!-- Badge Container with Subtle Glow & Gradient -->
                <div class="relative shrink-0">
                    <div
                        class="absolute -inset-1 bg-gradient-to-tr from-purple-600 to-indigo-600 rounded-2xl opacity-20 blur-sm">
                    </div>
                    <span
                        class="relative inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-slate-900 dark:bg-white text-white dark:text-slate-900 font-extrabold text-sm tracking-widest shadow-sm ring-1 ring-white/10">
                        AD
                    </span>
                </div>

                <div>
                    <div class="flex flex-wrap items-center gap-2.5">
                        <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white tracking-tight">
                            Administration Cockpit
                        </h1>
                        <!-- Optional Live Status Indicator -->
                        <span
                            class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 dark:bg-emerald-950/30 text-emerald-700 dark:text-emerald-400 ring-1 ring-inset ring-emerald-600/20 dark:ring-emerald-500/20">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            Active
                        </span>
                    </div>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1.5 max-w-3xl leading-relaxed">
                        Whitelist authorized personnel, manage dynamic corporate committees, and define designation titles
                        in a unified control center.
                    </p>
                </div>
            </div>

            <!-- Action Button -->
            <div class="shrink-0">
                <a href="{{ route('dashboard') }}?tab=admin"
                    class="group inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-semibold text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-900 border border-slate-200/90 dark:border-slate-800 rounded-xl shadow-sm hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white hover:border-slate-300 dark:hover:border-slate-700 active:scale-[0.98] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-all duration-150">
                    <svg class="w-4 h-4 text-slate-500 dark:text-slate-400 transition-transform duration-150 group-hover:-translate-x-0.5"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>Dashboard</span>
                </a>
            </div>
        </div>

        <!-- Inline Validation Errors -->
        @if ($errors->any())
            <div class="p-4 rounded-xl bg-red-50 dark:bg-red-950/20 border border-red-100 dark:border-red-900/40 text-red-600 dark:text-red-400 text-sm flex flex-col gap-1 shadow-sm">
                @foreach ($errors->all() as $error)
                    <div class="flex items-start gap-2">
                        <svg class="w-4 h-4 shrink-0 mt-0.5 text-red-500" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <span>{{ $error }}</span>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Sub-tabs Segmented Controls -->
        <div class="border-b border-slate-200 dark:border-slate-800 overflow-x-auto custom-scrollbar">
            <nav class="-mb-px flex whitespace-nowrap space-x-8" aria-label="Sub-Tabs">
                <button onclick="switchAdminTab('users')" id="tab-btn-users"
                    class="admin-tab-btn border-b-2 py-4 px-1 text-sm font-medium transition-all focus:outline-none flex items-center gap-2">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    Personnel Whitelisting
                </button>

                <button onclick="switchAdminTab('titles')" id="tab-btn-titles"
                    class="admin-tab-btn border-b-2 py-4 px-1 text-sm font-medium transition-all focus:outline-none flex items-center gap-2">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    Designations & Committees
                </button>
            </nav>
        </div>

        <!-- Administrative Panels -->
        <div>
            <!-- SUB-PANEL 1: PERSONNEL WHITELISTING -->
            <div id="panel-users" class="admin-tab-panel hidden space-y-6">
                <!-- Action & Filter Bar -->
                <div
                    class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-800 p-4 rounded-2xl mb-2 shadow-xs">
                    <!-- Filters Left -->
                    <div class="flex flex-wrap items-center gap-3 flex-1 min-w-0">
                        <!-- Real-time Search Input -->
                        <div class="relative w-full sm:w-64 shrink-0">
                            <input type="text" id="whitelist-search-input" onkeyup="filterWhitelist()"
                                placeholder="Search by name or email..."
                                class="w-full rounded-xl border border-slate-200 dark:border-slate-700/80 py-2.5 pl-4 pr-10 text-slate-700 dark:text-slate-200 text-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-white dark:bg-slate-950 transition-all duration-300">
                            <span class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </span>
                        </div>

                        <!-- Division Group Filter Dropdown -->
                        <div class="relative w-full sm:w-52 shrink-0">
                            <select id="filter-group" onchange="filterWhitelist()"
                                class="w-full rounded-xl border border-slate-200 dark:border-slate-700/80 py-2.5 pl-4 pr-10 text-slate-600 dark:text-slate-300 text-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-white dark:bg-slate-950 transition-all duration-300 appearance-none">
                                <option value="">All Groups</option>
                                <option value="guest">Guest / No Group</option>
                                <option value="board of directors">Board of Directors</option>
                                <option value="management">Management</option>
                                @if (isset($committees) && !$committees->isEmpty())
                                    @foreach ($committees as $committee)
                                        <option value="{{ strtolower($committee->name) }}">{{ $committee->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <span class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </span>
                        </div>

                        <!-- Status Filter Dropdown -->
                        <div class="relative w-full sm:w-44 shrink-0">
                            <select id="filter-status" onchange="filterWhitelist()"
                                class="w-full rounded-xl border border-slate-200 dark:border-slate-700/80 py-2.5 pl-4 pr-10 text-slate-600 dark:text-slate-300 text-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-white dark:bg-slate-950 transition-all duration-300 appearance-none">
                                <option value="">All Statuses</option>
                                <option value="linked">Linked</option>
                                <option value="pending">Pending Login</option>
                            </select>
                            <span class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </span>
                        </div>
                    </div>

                    <!-- Actions Right -->
                    <div class="shrink-0">
                        <button type="button" onclick="openWhitelistCreateModal()"
                            class="inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-semibold text-white bg-purple-600 hover:bg-purple-700 active:bg-purple-800 rounded-lg shadow-sm hover:shadow-md hover:shadow-purple-500/20 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-purple-500/30 focus:ring-offset-2 whitespace-nowrap active:scale-[0.98]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            <span>Whitelist User</span>
                        </button>
                    </div>
                </div>

                <!-- Whitelisted Users Directory -->
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/60 dark:border-slate-800/80 overflow-hidden shadow-sm">
                    <div class="p-6 border-b border-slate-100 dark:border-slate-800/80 flex items-center justify-between">
                        <h3 class="font-bold text-slate-900 dark:text-white text-lg">Whitelisted Access Directory</h3>
                        <span class="px-2.5 py-1 bg-purple-50 dark:bg-purple-950/30 text-purple-700 dark:text-purple-400 rounded-lg text-xs font-bold border border-purple-100/50 dark:border-purple-900/30">Authorized:
                            <span id="visible-whitelist-count">{{ $users->count() }}</span>
                        </span>
                    </div>

                    <div id="whitelist-empty-state" class="hidden p-12 text-center text-slate-400 dark:text-slate-500 text-sm">
                        <svg class="w-12 h-12 text-slate-300 dark:text-slate-700 mx-auto mb-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        No matching whitelisted users found for your active filter criteria.
                    </div>

                    @if ($users->isEmpty())
                        <div class="p-12 text-center text-slate-400 dark:text-slate-500 text-sm">
                            <svg class="w-12 h-12 text-slate-300 dark:text-slate-700 mx-auto mb-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            No whitelisted users. Add personnel to grant system access.
                        </div>
                    @else
                        <div class="overflow-x-auto custom-scrollbar">
                            <table class="w-full text-left text-sm border-collapse min-w-[700px]">
                                <thead>
                                    <tr
                                        class="bg-slate-50 dark:bg-slate-950/50 border-b border-slate-100 dark:border-slate-800/60 text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                                        <th class="py-4 px-6">Personnel</th>
                                        <th class="py-4 px-6">Designation / Division</th>
                                        <th class="py-4 px-6">OAuth Link</th>
                                        <th class="py-4 px-6 text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="whitelist-table-body" class="divide-y divide-slate-100 dark:divide-slate-800/50">
                                    @foreach ($users as $user)
                                        <tr class="whitelist-user-row hover:bg-slate-50/40 dark:hover:bg-slate-800/20 border-b border-slate-100 dark:border-slate-800/40 transition duration-150"
                                            data-name="{{ strtolower($user->name) }}"
                                            data-email="{{ strtolower($user->email) }}"
                                            data-group="{{ $user->title ? strtolower($user->title->group) : 'guest' }}"
                                            data-status="{{ $user->google_id ? 'linked' : 'pending' }}">
                                            <td class="py-4 px-6">
                                                <div class="flex items-center gap-3">
                                                    @if ($user->avatar)
                                                        <img class="w-9 h-9 rounded-full border border-slate-200 dark:border-slate-700"
                                                            src="{{ $user->avatar }}" alt="{{ $user->name }}">
                                                    @else
                                                        <div
                                                            class="w-9 h-9 rounded-full bg-slate-900 dark:bg-slate-800 text-white dark:text-slate-300 flex items-center justify-center text-xs font-semibold uppercase">
                                                            {{ substr($user->name, 0, 2) }}
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <div
                                                            class="font-semibold text-slate-900 dark:text-white flex items-center gap-1.5">
                                                            {{ $user->name }}
                                                            @if ($user->isSuperAdmin())
                                                                <span
                                                                    class="px-1.5 py-0.5 bg-red-100 dark:bg-red-950/30 text-red-700 dark:text-red-400 text-[9px] rounded font-bold uppercase tracking-wider">Super
                                                                    Admin</span>
                                                            @endif
                                                        </div>
                                                        <div class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">{{ $user->email }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="py-4 px-6">
                                                @if ($user->title)
                                                    <div class="font-semibold text-slate-800 dark:text-slate-200">{{ $user->title->title }}
                                                    </div>
                                                    <div class="text-[10px] text-slate-400 dark:text-slate-500 mt-0.5 font-medium">
                                                        {{ $user->title->group }}</div>
                                                @else
                                                    <div class="text-slate-400 dark:text-slate-500 italic">No Title Assigned</div>
                                                @endif
                                            </td>
                                            <td class="py-4 px-6">
                                                @if ($user->google_id)
                                                    <span
                                                        class="inline-flex items-center gap-1.5 text-xs text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-100/50 dark:border-emerald-900/30 px-2.5 py-1 rounded-lg">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                        Linked
                                                    </span>
                                                @else
                                                    <span
                                                        class="inline-flex items-center gap-1.5 text-xs text-slate-500 dark:text-slate-400 bg-slate-50 dark:bg-slate-800/30 border border-slate-200/80 dark:border-slate-700/60 px-2.5 py-1 rounded-lg">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-300 dark:bg-slate-600"></span>
                                                        Pending Login
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="py-4 px-6 text-right space-x-1.5 whitespace-nowrap">
                                                <form action="{{ route('admin.users.reset_pin', $user) }}" method="POST"
                                                    data-confirm="Are you sure you want to reset this user's 6-digit access PIN?"
                                                    data-confirm-sub="Their current PIN will be deleted, and they will be forced to configure a new PIN upon their next login."
                                                    data-confirm-title="Reset Access PIN" class="inline">
                                                    @csrf
                                                    <button type="submit"
                                                        class="text-xs font-bold text-amber-600 dark:text-amber-400 hover:text-amber-800 dark:hover:text-amber-300 bg-amber-50 dark:bg-amber-950/20 hover:bg-amber-100/80 dark:hover:bg-amber-950/40 px-3 py-1.5 rounded-lg border border-amber-100/40 dark:border-amber-900/20 transition duration-150">
                                                        Reset PIN
                                                    </button>
                                                </form>

                                                <button onclick="openUserEditModal({{ json_encode($user) }})"
                                                    class="text-xs font-bold text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 bg-slate-50 dark:bg-slate-900 hover:bg-slate-100 dark:hover:bg-slate-800 px-3 py-1.5 rounded-lg border border-slate-200/80 dark:border-slate-700/80 transition duration-150">
                                                    Edit
                                                </button>

                                                @if (!$user->isSuperAdmin())
                                                    <form action="{{ route('admin.users.destroy', $user) }}"
                                                        method="POST"
                                                        data-confirm="Are you sure you want to revoke system access for this user?"
                                                        data-confirm-sub="This will instantly block their Google login and revoke access to all division portals."
                                                        data-confirm-title="Revoke System Access" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="text-xs font-bold text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 bg-red-50 dark:bg-red-950/20 hover:bg-red-100/80 dark:hover:bg-red-950/40 px-3 py-1.5 rounded-lg border border-red-100/40 dark:border-red-900/20 transition duration-150">
                                                            Delete
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            <!-- SUB-PANEL 2: DESIGNATIONS & COMMITTEES -->
            <div id="panel-titles" class="admin-tab-panel hidden space-y-8">
                <!-- Interactive Stats Row -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Stat Card 1: Total Designations -->
                    <div class="relative overflow-hidden bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/60 dark:border-slate-800/80 p-6 flex items-center justify-between shadow-sm transition duration-300 hover:shadow-md">
                        <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 rounded-full bg-blue-500/5 blur-xl"></div>
                        <div class="space-y-1">
                            <span class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Total Designations</span>
                            <div class="text-3xl font-extrabold text-slate-900 dark:text-white">{{ $titles->count() }}</div>
                            <span class="text-[11px] text-slate-400 dark:text-slate-500 font-medium block">Unique corporate titles</span>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-blue-50 dark:bg-blue-950/30 text-blue-600 dark:text-blue-400 flex items-center justify-center font-bold shadow-xs border border-blue-100/10">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                        </div>
                    </div>

                    <!-- Stat Card 2: Custom Committees -->
                    <div class="relative overflow-hidden bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/60 dark:border-slate-800/80 p-6 flex items-center justify-between shadow-sm transition duration-300 hover:shadow-md">
                        <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 rounded-full bg-purple-500/5 blur-xl"></div>
                        <div class="space-y-1">
                            <span class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Custom Committees</span>
                            <div class="text-3xl font-extrabold text-slate-900 dark:text-white">{{ $committees->count() }}</div>
                            <span class="text-[11px] text-slate-400 dark:text-slate-500 font-medium block">Dynamic operational groups</span>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-purple-50 dark:bg-purple-950/30 text-purple-600 dark:text-purple-400 flex items-center justify-center font-bold shadow-xs border border-purple-100/10">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                    </div>

                    <!-- Stat Card 3: Governance Assignments -->
                    <div class="relative overflow-hidden bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/60 dark:border-slate-800/80 p-6 flex items-center justify-between shadow-sm transition duration-300 hover:shadow-md">
                        <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 rounded-full bg-emerald-500/5 blur-xl"></div>
                        <div class="space-y-1">
                            <span class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Assigned Personnel</span>
                            <div class="text-3xl font-extrabold text-slate-900 dark:text-white">{{ $users->whereNotNull('title_id')->count() }}</div>
                            <span class="text-[11px] text-slate-400 dark:text-slate-500 font-medium block">Users with custom designations</span>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-emerald-50 dark:bg-emerald-950/30 text-emerald-600 dark:text-emerald-400 flex items-center justify-center font-bold shadow-xs border border-emerald-100/10">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Workspace Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                    <!-- Left Column: Committees Control Center -->
                    <div class="lg:col-span-5 space-y-6">
                        <!-- Create Custom Committee Card -->
                        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/60 dark:border-slate-800/80 p-6 shadow-sm">
                            <div class="flex items-center gap-2.5 mb-2">
                                <div class="w-8 h-8 rounded-lg bg-purple-50 dark:bg-purple-950/30 text-purple-600 dark:text-purple-400 flex items-center justify-center shrink-0 border border-purple-100/10">
                                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <h3 class="font-bold text-slate-900 dark:text-white text-lg">Register Committee</h3>
                            </div>
                            <p class="text-xs text-slate-400 dark:text-slate-500 mb-6 font-medium">Create custom dynamic committees to scope specific operational groups.</p>

                            <form action="{{ route('admin.committees.store') }}" method="POST" class="space-y-5">
                                @csrf
                                <div class="space-y-2">
                                    <label for="committee_name" class="block text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Committee Name</label>
                                    <input type="text" name="name" id="committee_name" required placeholder="e.g. GAD Committee, BAC Board" class="w-full rounded-xl border border-slate-200 dark:border-slate-700/80 py-3 px-4 text-slate-700 dark:text-slate-200 text-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-slate-50/50 dark:bg-slate-950/50 focus:bg-white dark:focus:bg-slate-950 transition-all duration-300">
                                </div>

                                <button type="submit" class="w-full inline-flex items-center justify-center text-sm font-semibold py-3.5 px-4 rounded-xl bg-purple-600 hover:bg-purple-700 text-white shadow-sm hover:shadow active:scale-[0.98] transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500/20">
                                    Create Committee
                                </button>
                            </form>
                        </div>

                        <!-- Custom Committees Dashboard -->
                        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/60 dark:border-slate-800/80 p-6 shadow-sm space-y-4">
                            <div class="flex items-center justify-between pb-2 border-b border-slate-100 dark:border-slate-800/60">
                                <div>
                                    <h3 class="font-bold text-slate-900 dark:text-white text-lg">Custom Committees Dashboard</h3>
                                    <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5 font-medium">Dynamic custom groups and nested roles.</p>
                                </div>
                                <span class="px-2.5 py-1 bg-purple-50 dark:bg-purple-950/30 text-purple-700 dark:text-purple-400 rounded-lg text-xs font-bold border border-purple-100/50 dark:border-purple-900/30">{{ $committees->count() }}</span>
                            </div>

                            @if ($committees->isEmpty())
                                <div class="py-8 text-center text-slate-400 dark:text-slate-500 text-sm italic">
                                    No custom committees registered yet.
                                </div>
                            @else
                                <div class="space-y-4 max-h-[500px] overflow-y-auto pr-1 custom-scrollbar">
                                    @foreach ($committees as $committee)
                                        @php
                                            $committeeTitles = $titles->where('group', $committee->name);
                                            $titlesCount = $committeeTitles->count();
                                            $assignedPersonnelCount = $committeeTitles->sum('users_count');
                                        @endphp
                                        <div class="relative group bg-slate-50/50 dark:bg-slate-950/40 hover:bg-white dark:hover:bg-slate-900 border border-slate-100 dark:border-slate-800 hover:border-purple-200/60 dark:hover:border-purple-800/40 p-5 rounded-2xl shadow-xs hover:shadow-md transition-all duration-300">
                                            <!-- Card Header -->
                                            <div class="flex items-start justify-between gap-4">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-10 h-10 rounded-xl bg-purple-50 dark:bg-purple-950/30 text-purple-600 dark:text-purple-400 flex items-center justify-center font-bold shrink-0 border border-purple-100/10">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                                        </svg>
                                                    </div>
                                                    <div>
                                                        <h4 class="font-bold text-slate-800 dark:text-slate-200 text-base tracking-tight leading-snug">{{ $committee->name }}</h4>
                                                        <span class="inline-flex items-center gap-1 mt-1 text-[10px] font-bold text-purple-700 dark:text-purple-400 bg-purple-50 dark:bg-purple-950/30 px-2 py-0.5 rounded-full border border-purple-100/50 dark:border-purple-900/30">
                                                            Custom Committee
                                                        </span>
                                                    </div>
                                                </div>

                                                <div class="flex items-center gap-1.5">
                                                    <!-- Edit Committee Button -->
                                                    <button onclick="openCommitteeEditModal({{ json_encode($committee) }})"
                                                        class="text-slate-400 dark:text-slate-500 hover:text-purple-600 dark:hover:text-purple-400 hover:bg-purple-50 dark:hover:bg-purple-950/40 p-1.5 rounded-xl transition duration-150" title="Edit Committee">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                    </button>

                                                    <!-- Delete Committee Form -->
                                                    <form action="{{ route('admin.committees.destroy', $committee) }}" method="POST"
                                                        data-confirm="Are you sure you want to delete this custom committee?"
                                                        data-confirm-sub="All titles and personnel associations under this committee will be permanently removed."
                                                        data-confirm-title="Delete Custom Committee" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-slate-400 dark:text-slate-500 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-950/40 p-1.5 rounded-xl transition duration-150" title="Delete Committee">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>

                                            <!-- Committee Designations & Personnel Stats -->
                                            <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-800/60 flex items-center justify-between text-xs text-slate-500 dark:text-slate-400 font-medium">
                                                <span class="flex items-center gap-1">
                                                    <span class="font-bold text-slate-800 dark:text-slate-200">{{ $titlesCount }}</span> Designations
                                                </span>
                                                <span class="flex items-center gap-1">
                                                    <span class="font-bold text-slate-800 dark:text-slate-200">{{ $assignedPersonnelCount }}</span> Whitelisted Users
                                                </span>
                                            </div>

                                            <!-- Designation Tags Nested in Card -->
                                            @if($committeeTitles->isNotEmpty())
                                                <div class="mt-3 flex flex-wrap gap-1.5">
                                                    @foreach($committeeTitles as $ct)
                                                        <span class="inline-flex items-center text-[10px] px-2 py-0.5 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-bold border border-slate-200/40 dark:border-slate-700/40">
                                                            {{ $ct->title }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            @else
                                                <div class="mt-3 text-[10px] text-slate-400 dark:text-slate-500 italic">No designations registered for this committee yet.</div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Right Column: Designations Control & Active Directory -->
                    <div class="lg:col-span-7 space-y-6">
                        <!-- Search & Add Designation Workspace Card -->
                        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/60 dark:border-slate-800/80 p-6 shadow-sm space-y-6">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                <div>
                                    <h3 class="font-bold text-slate-900 dark:text-white text-lg">Manage Designations</h3>
                                    <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5 font-medium">Filter current designation titles or register new ones below.</p>
                                </div>
                            </div>

                            <!-- Integrated Search Input -->
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                    <svg class="h-4.5 w-4.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                                <input type="text" id="designation-search-input" onkeyup="filterDesignations()" placeholder="Search designations or groups (e.g. CEO, GAD)..." class="pl-10 w-full rounded-xl border border-slate-200 dark:border-slate-700/80 py-2.5 px-4 text-slate-700 dark:text-slate-200 text-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-slate-50/50 dark:bg-slate-950/50 focus:bg-white dark:focus:bg-slate-950 transition-all duration-300">
                            </div>

                            <!-- Interactive Add Designation Nested Form -->
                            <div class="bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-2xl p-5 space-y-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-md bg-purple-50 dark:bg-purple-950/30 text-purple-600 dark:text-purple-400 flex items-center justify-center shrink-0 border border-purple-100/10">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                                        </svg>
                                    </div>
                                    <h4 class="font-bold text-slate-800 dark:text-slate-200 text-sm">Register Title Designation</h4>
                                </div>
                                <form action="{{ route('admin.titles.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @csrf
                                    <div class="space-y-1.5">
                                        <label for="title_group" class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Division / Committee Group</label>
                                        <div class="relative">
                                            <select name="group" id="title_group" required class="w-full rounded-xl border border-slate-200 dark:border-slate-700/80 py-2.5 px-3.5 text-slate-700 dark:text-slate-200 text-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-white dark:bg-slate-900 transition-all duration-300 appearance-none">
                                                <option value="Board of Directors">Board of Directors</option>
                                                <option value="Management">Management</option>
                                                @if (!$committees->isEmpty())
                                                    <optgroup label="Custom Committees">
                                                        @foreach ($committees as $committee)
                                                            <option value="{{ $committee->name }}">{{ $committee->name }}</option>
                                                        @endforeach
                                                    </optgroup>
                                                @endif
                                            </select>
                                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3.5 text-slate-400 dark:text-slate-500">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="space-y-1.5">
                                        <label for="title_designation" class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Title Designation</label>
                                        <input type="text" name="title" id="title_designation" required placeholder="e.g. Chairperson, Secretary" class="w-full rounded-xl border border-slate-200 dark:border-slate-700/80 py-2.5 px-3.5 text-slate-700 dark:text-slate-200 text-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-white dark:bg-slate-900 transition-all duration-300">
                                    </div>
                                    <div class="md:col-span-2 flex justify-end">
                                        <button type="submit" class="inline-flex items-center justify-center gap-1.5 px-5 py-2.5 text-xs font-bold text-white bg-purple-600 hover:bg-purple-700 rounded-xl shadow-sm hover:shadow-md hover:shadow-purple-500/10 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-purple-500/30 whitespace-nowrap active:scale-[0.98]">
                                            Add Designation
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Active Titles Directory Card -->
                        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/60 dark:border-slate-800/80 overflow-hidden shadow-sm">
                            <div class="p-6 border-b border-slate-100 dark:border-slate-800/60 flex items-center justify-between">
                                <div>
                                    <h3 class="font-bold text-slate-900 dark:text-white text-lg">Active Titles Directory</h3>
                                    <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5 font-medium">Full listing of designation titles defined in the organization.</p>
                                </div>
                                <span class="px-2.5 py-1 bg-purple-50 dark:bg-purple-950/30 text-purple-700 dark:text-purple-400 rounded-lg text-xs font-bold border border-purple-100/50 dark:border-purple-900/30">Total: {{ $titles->count() }}</span>
                            </div>

                            @if ($titles->isEmpty())
                                <div class="p-12 text-center text-slate-400 dark:text-slate-500 text-sm">
                                    <svg class="w-12 h-12 text-slate-300 dark:text-slate-700 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                                    </svg>
                                    No corporate titles defined. Use the form above to configure titles.
                                </div>
                            @else
                                <div id="designation-empty-state" class="hidden p-12 text-center text-slate-400 dark:text-slate-500 text-sm">
                                    <svg class="w-12 h-12 text-slate-300 dark:text-slate-700 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                    No matching corporate designations found for your search query.
                                </div>

                                <div class="overflow-x-auto custom-scrollbar">
                                    <table class="w-full text-left text-sm border-collapse min-w-[500px]">
                                        <thead id="designation-table-header">
                                            <tr class="bg-slate-50 dark:bg-slate-950/50 border-b border-slate-100 dark:border-slate-800/60 text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                                                <th class="py-4 px-6">Role / Title</th>
                                                <th class="py-4 px-6">Division Group</th>
                                                <th class="py-4 px-6 text-center">Assigned Users</th>
                                                <th class="py-4 px-6 text-right">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody id="designation-table-body" class="divide-y divide-slate-100 dark:divide-slate-800/50">
                                            @foreach ($titles as $title)
                                                <tr class="designation-row hover:bg-slate-50/40 dark:hover:bg-slate-800/20 border-b border-slate-100 dark:border-slate-800/40 transition duration-150"
                                                    data-title="{{ strtolower($title->title) }}"
                                                    data-group="{{ strtolower($title->group) }}">
                                                    <td class="py-4 px-6">
                                                        <div class="flex items-center gap-2">
                                                            <svg class="w-4 h-4 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                            </svg>
                                                            <span class="font-semibold text-slate-900 dark:text-white">{{ $title->title }}</span>
                                                        </div>
                                                    </td>
                                                    <td class="py-4 px-6">
                                                        @if ($title->group === 'Board of Directors')
                                                            <span class="px-2.5 py-1 bg-blue-50 dark:bg-blue-950/30 text-blue-700 dark:text-blue-400 text-xs font-bold rounded-lg border border-blue-100/50 dark:border-blue-900/30">
                                                                Board of Directors
                                                            </span>
                                                        @elseif($title->group === 'Management')
                                                            <span class="px-2.5 py-1 bg-indigo-50 dark:bg-indigo-950/30 text-indigo-700 dark:text-indigo-400 text-xs font-bold rounded-lg border border-indigo-100/50 dark:border-indigo-900/30">
                                                                Management
                                                            </span>
                                                        @else
                                                            <span class="px-2.5 py-1 bg-purple-50 dark:bg-purple-950/30 text-purple-700 dark:text-purple-400 text-xs font-bold rounded-lg border border-purple-100/50 dark:border-purple-900/30">
                                                                {{ $title->group }}
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td class="py-4 px-6 text-center">
                                                        <span class="inline-flex items-center justify-center min-w-[24px] h-6 px-1.5 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-xs font-bold rounded-full">
                                                            {{ $title->users_count }}
                                                        </span>
                                                    </td>
                                                    <td class="py-4 px-6 text-right space-x-1.5 whitespace-nowrap">
                                                        <button onclick="openTitleEditModal({{ json_encode($title) }})"
                                                            class="text-xs font-bold text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 bg-slate-50 dark:bg-slate-900 hover:bg-slate-100 dark:hover:bg-slate-800 px-3 py-1.5 rounded-lg border border-slate-200/80 dark:border-slate-700/80 transition duration-150">
                                                            Edit
                                                        </button>

                                                        <form action="{{ route('admin.titles.destroy', $title) }}" method="POST"
                                                            data-confirm="Are you sure you want to delete this title?"
                                                            data-confirm-sub="All whitelisted personnel assigned to this title will have their designation set to unassigned."
                                                            data-confirm-title="Delete Title Designation" class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="text-xs font-bold text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 bg-red-50 dark:bg-red-950/20 hover:bg-red-100/80 dark:hover:bg-red-950/40 px-3 py-1.5 rounded-lg border border-red-100/40 dark:border-red-900/20 transition duration-150">
                                                                Delete
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- BACKDROP MODAL: EDITING WHITELISTED PERSONNEL -->
    <div id="user-edit-modal"
        class="fixed inset-0 bg-slate-900/60 dark:bg-slate-950/80 backdrop-blur-[4px] z-50 hidden flex items-center justify-center p-4 transition-all duration-300 opacity-0">
        <div id="user-edit-modal-content"
            class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 max-w-md w-full shadow-2xl p-6 sm:p-8 space-y-6 transform scale-95 opacity-0 transition-all duration-300">
            <div class="flex justify-between items-center pb-4 border-b border-slate-100 dark:border-slate-800/60">
                <h3 class="font-bold text-slate-900 dark:text-white text-lg">Edit User Access</h3>
                <button onclick="closeUserEditModal()"
                    class="text-slate-400 dark:text-slate-500 hover:text-slate-600 dark:hover:text-slate-200 p-1 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 transition duration-150">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form id="user-edit-form" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <!-- Full Name -->
                <div class="space-y-2">
                    <label for="edit_user_name"
                        class="block text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Full Name</label>
                    <input type="text" name="name" id="edit_user_name" required
                        class="w-full rounded-xl border border-slate-200 dark:border-slate-800 py-3 px-4 text-slate-700 dark:text-slate-200 text-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-slate-50/50 dark:bg-slate-950/50 focus:bg-white dark:focus:bg-slate-950 transition-all duration-300">
                </div>

                <!-- Email Address -->
                <div class="space-y-2">
                    <label for="edit_user_email"
                        class="block text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Email Address</label>
                    <input type="email" name="email" id="edit_user_email" required
                        class="w-full rounded-xl border border-slate-200 dark:border-slate-800 py-3 px-4 text-slate-700 dark:text-slate-200 text-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-slate-50/50 dark:bg-slate-950/50 focus:bg-white dark:focus:bg-slate-950 transition-all duration-300">
                </div>

                <!-- Designation Selector -->
                <div class="space-y-2">
                    <label for="edit_user_title"
                        class="block text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Corporate
                        Designation</label>
                    <div class="relative">
                        <select name="title_id" id="edit_user_title"
                            class="w-full rounded-xl border border-slate-200 dark:border-slate-800 py-3 pl-4 pr-10 text-slate-700 dark:text-slate-200 text-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-slate-50/50 dark:bg-slate-950/50 focus:bg-white dark:focus:bg-slate-950 transition-all duration-300 appearance-none">
                            <option value="">No title assigned (Guest Access)</option>
                            @foreach ($groupedTitles as $group => $titlesList)
                                <optgroup label="{{ $group }}">
                                    @foreach ($titlesList as $title)
                                        <option value="{{ $title->id }}">{{ $title->title }}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-400 dark:text-slate-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- 6-digit Access PIN -->
                <div class="space-y-2">
                    <label for="edit_user_pin"
                        class="block text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">6-Digit Access PIN</label>
                    <input type="text" name="pin" id="edit_user_pin" required placeholder="e.g. 123456" pattern="[0-9]{6}" maxlength="6"
                        class="w-full rounded-xl border border-slate-200 dark:border-slate-800 py-3 px-4 text-slate-700 dark:text-slate-200 text-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-slate-50/50 dark:bg-slate-950/50 focus:bg-white dark:focus:bg-slate-950 transition-all duration-300">
                </div>

                <div class="flex items-center justify-end gap-3 pt-5 border-t border-slate-100 dark:border-slate-800">
                    <button type="button" onclick="closeUserEditModal()"
                        class="text-xs font-semibold py-2.5 px-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800/60 text-slate-700 dark:text-slate-300 transition duration-150 active:scale-[0.98]">
                        Cancel
                    </button>
                    <button type="submit"
                        class="text-xs font-semibold py-2.5 px-4 rounded-xl bg-purple-600 hover:bg-purple-700 text-white shadow-sm transition duration-150 active:scale-[0.98]">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- BACKDROP MODAL: WHITELISTING A NEW USER -->
    <div id="whitelist-create-modal"
        class="fixed inset-0 bg-slate-900/60 dark:bg-slate-950/80 backdrop-blur-[4px] z-50 hidden flex items-center justify-center p-4 transition-all duration-300 opacity-0">
        <div id="whitelist-create-modal-content"
            class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 max-w-md w-full shadow-2xl p-6 sm:p-8 space-y-6 transform scale-95 opacity-0 transition-all duration-300">
            <div class="flex justify-between items-center pb-4 border-b border-slate-100 dark:border-slate-800/60">
                <h3 class="font-bold text-slate-900 dark:text-white text-lg">Whitelist Personnel</h3>
                <button type="button" onclick="closeWhitelistCreateModal()"
                    class="text-slate-400 dark:text-slate-500 hover:text-slate-600 dark:hover:text-slate-200 p-1 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 transition duration-150">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-4">
                @csrf
                <!-- Full Name -->
                <div class="space-y-2">
                    <label for="create_user_name"
                        class="block text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Full Name</label>
                    <input type="text" name="name" id="create_user_name" required placeholder="e.g. Jane Doe"
                        class="w-full rounded-xl border border-slate-200 dark:border-slate-800 py-3 px-4 text-slate-700 dark:text-slate-200 text-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-slate-50/50 dark:bg-slate-950/50 focus:bg-white dark:focus:bg-slate-950 transition-all duration-300">
                </div>

                <!-- Email Address -->
                <div class="space-y-2">
                    <label for="create_user_email"
                        class="block text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Email Address</label>
                    <input type="email" name="email" id="create_user_email" required
                        placeholder="e.g. user@domain.com"
                        class="w-full rounded-xl border border-slate-200 dark:border-slate-800 py-3 px-4 text-slate-700 dark:text-slate-200 text-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-slate-50/50 dark:bg-slate-950/50 focus:bg-white dark:focus:bg-slate-950 transition-all duration-300">
                </div>

                <!-- Designation Selector -->
                <div class="space-y-2">
                    <label for="create_user_title"
                        class="block text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Corporate
                        Designation</label>
                    <div class="relative">
                        <select name="title_id" id="create_user_title"
                            class="w-full rounded-xl border border-slate-200 dark:border-slate-800 py-3 pl-4 pr-10 text-slate-700 dark:text-slate-200 text-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-slate-50/50 dark:bg-slate-950/50 focus:bg-white dark:focus:bg-slate-950 transition-all duration-300 appearance-none">
                            <option value="">No title assigned (Guest Access)</option>
                            @foreach ($groupedTitles as $group => $titlesList)
                                <optgroup label="{{ $group }}">
                                    @foreach ($titlesList as $title)
                                        <option value="{{ $title->id }}">{{ $title->title }}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-400 dark:text-slate-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- 6-digit Access PIN -->
                <div class="space-y-2">
                    <label for="create_user_pin"
                        class="block text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">6-Digit Access PIN</label>
                    <input type="text" name="pin" id="create_user_pin" required placeholder="e.g. 123456" pattern="[0-9]{6}" maxlength="6"
                        class="w-full rounded-xl border border-slate-200 dark:border-slate-800 py-3 px-4 text-slate-700 dark:text-slate-200 text-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-slate-50/50 dark:bg-slate-950/50 focus:bg-white dark:focus:bg-slate-950 transition-all duration-300">
                </div>

                <div class="flex items-center justify-end gap-3 pt-5 border-t border-slate-100 dark:border-slate-800">
                    <button type="button" onclick="closeWhitelistCreateModal()"
                        class="text-xs font-semibold py-2.5 px-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800/60 text-slate-700 dark:text-slate-300 transition duration-150 active:scale-[0.98]">
                        Cancel
                    </button>
                    <button type="submit"
                        class="text-xs font-semibold py-2.5 px-4 rounded-xl bg-purple-600 hover:bg-purple-700 text-white shadow-sm transition duration-150 active:scale-[0.98]">
                        Whitelist & Assign Title
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- BACKDROP MODAL: EDITING TITLE DESIGNATION -->
    <div id="title-edit-modal"
        class="fixed inset-0 bg-slate-900/60 dark:bg-slate-950/80 backdrop-blur-[4px] z-50 hidden flex items-center justify-center p-4 transition-all duration-300 opacity-0">
        <div id="title-edit-modal-content"
            class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 max-w-md w-full shadow-2xl p-6 sm:p-8 space-y-6 transform scale-95 opacity-0 transition-all duration-300">
            <div class="flex justify-between items-center pb-4 border-b border-slate-100 dark:border-slate-800/60">
                <h3 class="font-bold text-slate-900 dark:text-white text-lg">Edit Title Designation</h3>
                <button onclick="closeTitleEditModal()"
                    class="text-slate-400 dark:text-slate-500 hover:text-slate-600 dark:hover:text-slate-200 p-1 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 transition duration-150">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form id="title-edit-form" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <!-- Group Select -->
                <div class="space-y-2">
                    <label for="edit_title_group"
                        class="block text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Division Group</label>
                    <div class="relative">
                        <select name="group" id="edit_title_group" required
                            class="w-full rounded-xl border border-slate-200 dark:border-slate-800 py-3 pl-4 pr-10 text-slate-700 dark:text-slate-200 text-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-slate-50/50 dark:bg-slate-950/50 focus:bg-white dark:focus:bg-slate-950 transition-all duration-300 appearance-none">
                            <option value="Board of Directors">Board of Directors</option>
                            <option value="Management">Management</option>
                            @if (!$committees->isEmpty())
                                <optgroup label="Custom Committees">
                                    @foreach ($committees as $committee)
                                        <option value="{{ $committee->name }}">{{ $committee->name }}</option>
                                    @endforeach
                                </optgroup>
                            @endif
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-400 dark:text-slate-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Title Input -->
                <div class="space-y-2">
                    <label for="edit_title_name"
                        class="block text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Title Designation</label>
                    <input type="text" name="title" id="edit_title_name" required
                        class="w-full rounded-xl border border-slate-200 dark:border-slate-800 py-3 px-4 text-slate-700 dark:text-slate-200 text-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-slate-50/50 dark:bg-slate-950/50 focus:bg-white dark:focus:bg-slate-950 transition-all duration-300">
                </div>

                <div class="flex items-center justify-end gap-3 pt-5 border-t border-slate-100 dark:border-slate-800">
                    <button type="button" onclick="closeTitleEditModal()"
                        class="text-xs font-semibold py-2.5 px-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800/60 text-slate-700 dark:text-slate-300 transition duration-150 active:scale-[0.98]">
                        Cancel
                    </button>
                    <button type="submit"
                        class="text-xs font-semibold py-2.5 px-4 rounded-xl bg-purple-600 hover:bg-purple-700 text-white shadow-sm transition duration-150 active:scale-[0.98]">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- BACKDROP MODAL: EDITING CUSTOM COMMITTEE -->
    <div id="committee-edit-modal"
        class="fixed inset-0 bg-slate-900/60 dark:bg-slate-950/80 backdrop-blur-[4px] z-50 hidden flex items-center justify-center p-4 transition-all duration-300 opacity-0">
        <div id="committee-edit-modal-content"
            class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 max-w-md w-full shadow-2xl p-6 sm:p-8 space-y-6 transform scale-95 opacity-0 transition-all duration-300">
            <div class="flex justify-between items-center pb-4 border-b border-slate-100 dark:border-slate-800/60">
                <h3 class="font-bold text-slate-900 dark:text-white text-lg">Edit Custom Committee</h3>
                <button onclick="closeCommitteeEditModal()"
                    class="text-slate-400 dark:text-slate-500 hover:text-slate-600 dark:hover:text-slate-200 p-1 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 transition duration-150">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form id="committee-edit-form" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <!-- Committee Name Input -->
                <div class="space-y-2">
                    <label for="edit_committee_name"
                        class="block text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Committee Name</label>
                    <input type="text" name="name" id="edit_committee_name" required
                        class="w-full rounded-xl border border-slate-200 dark:border-slate-800 py-3 px-4 text-slate-700 dark:text-slate-200 text-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-slate-50/50 dark:bg-slate-950/50 focus:bg-white dark:focus:bg-slate-950 transition-all duration-300">
                </div>

                <div class="flex items-center justify-end gap-3 pt-5 border-t border-slate-100 dark:border-slate-800">
                    <button type="button" onclick="closeCommitteeEditModal()"
                        class="text-xs font-semibold py-2.5 px-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800/60 text-slate-700 dark:text-slate-300 transition duration-150 active:scale-[0.98]">
                        Cancel
                    </button>
                    <button type="submit"
                        class="text-xs font-semibold py-2.5 px-4 rounded-xl bg-purple-600 hover:bg-purple-700 text-white shadow-sm transition duration-150 active:scale-[0.98]">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    @include('admin.scripts')
@endsection
