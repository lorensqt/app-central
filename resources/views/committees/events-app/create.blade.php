@extends('layouts.app')

@section('title', 'Schedule New Assembly')

@section('content')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <div class="space-y-6 sm:space-y-8 max-w-5xl mx-auto pb-12">
        <!-- Breadcrumbs (Hidden on Mobile for Space Optimization) -->
        <div class="hidden sm:flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400">
            <a href="{{ route('dashboard') }}?tab=committees" class="hover:text-slate-900 dark:hover:text-white transition-colors">Portal</a>
            <svg class="w-4 h-4 text-slate-300 dark:text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
            <a href="{{ route('committees.events.index', ['committee_id' => $committee->id]) }}" class="hover:text-slate-900 dark:hover:text-white transition-colors">{{ $committee->name }}</a>
            <svg class="w-4 h-4 text-slate-300 dark:text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
            <span class="text-slate-900 dark:text-slate-300 font-medium">Schedule Assembly</span>
        </div>

        <!-- Header Panel (Responsive Spacing) -->
        <div class="relative overflow-hidden bg-white dark:bg-slate-900 rounded-2xl sm:rounded-3xl border border-slate-200/80 dark:border-slate-800 p-5 sm:p-8 shadow-sm bg-gradient-to-r from-purple-50/50 via-transparent to-indigo-50/30 dark:from-purple-950/10 dark:to-transparent">
            <div class="absolute -top-12 -left-12 w-32 h-32 bg-purple-400/10 rounded-full blur-2xl pointer-events-none"></div>
            <div class="absolute -bottom-12 -right-12 w-32 h-32 bg-indigo-400/10 rounded-full blur-2xl pointer-events-none"></div>

            <div class="relative flex flex-col md:flex-row justify-between md:items-center gap-4">
                <div>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-purple-500/10 dark:bg-purple-400/10 text-[10px] font-bold text-purple-600 dark:text-purple-400 uppercase tracking-widest mb-2">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                        </svg>
                        Assembly Planner
                    </span>
                    <h1 class="font-bold text-slate-900 dark:text-white text-2xl sm:text-3xl leading-tight tracking-tight">
                        Schedule New Assembly
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1.5">
                        Design a public, highly polished shareable event for the <span class="font-semibold text-purple-600 dark:text-purple-400">{{ $committee->name }}</span>.
                    </p>
                </div>
                
                <a href="{{ route('committees.events.index', ['committee_id' => $committee->id]) }}"
                    class="w-full md:w-auto inline-flex items-center justify-center gap-1.5 text-xs font-bold text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 transition duration-150 active:scale-95 shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Workspace
                </a>
            </div>
        </div>

        <form action="{{ route('committees.events.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <!-- Hidden Committee Field -->
            <input type="hidden" name="committee_id" value="{{ $committee->id }}">

            <!-- 2-Column Grid Workspace -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                <!-- Left Column: Primary Logistics (5 Cols) -->
                <div class="lg:col-span-5 space-y-6">
                    <div class="flex items-center gap-2 pb-1 border-b border-slate-100 dark:border-slate-800/60">
                        <span class="flex items-center justify-center w-5 h-5 rounded-full bg-purple-500/10 text-purple-600 dark:text-purple-400 font-bold text-[10px]">1</span>
                        <span class="text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">General Settings</span>
                    </div>

                    <!-- Event Title -->
                    <div class="space-y-1.5">
                        <label for="event_title" class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Event Title</label>
                        <input type="text" name="title" id="event_title" required
                            placeholder="e.g. Q3 Strategic Planning Assembly"
                            class="w-full rounded-xl border border-slate-200 dark:border-slate-800/80 py-3 px-4 text-slate-800 dark:text-slate-200 text-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-white dark:bg-slate-900 focus:bg-white dark:focus:bg-slate-900 shadow-sm transition-all duration-300">
                    </div>

                    <!-- Event Description -->
                    <div class="space-y-1.5">
                        <label for="event_desc" class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Description</label>
                        <textarea name="description" id="event_desc" required rows="4"
                            placeholder="e.g. A close-up, focused photo of a hand carefully repairing a chipped, vintage ceramic teacup using the Japanese Kintsugi method. Fine lines of bright, shimmering gold lacquer are visible, seamlessly joining the cracks and highlighting the beauty of the repair. The teacup is held against a soft, blurred background of a wooden workshop bench."
                            class="w-full rounded-xl border border-slate-200 dark:border-slate-800/80 py-3 px-4 text-slate-800 dark:text-slate-200 text-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-white dark:bg-slate-900 focus:bg-white dark:focus:bg-slate-900 shadow-sm transition-all duration-300 custom-scrollbar"></textarea>
                    </div>

                    <!-- Event Terms and Policy -->
                    <div class="space-y-1.5">
                        <label for="event_terms_and_policy" class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Terms and Policy</label>
                        <textarea name="terms_and_policy" id="event_terms_and_policy" required rows="4"
                            placeholder="State the terms, rules, and privacy policies for attending this event..."
                            class="w-full rounded-xl border border-slate-200 dark:border-slate-800/80 py-3 px-4 text-slate-800 dark:text-slate-200 text-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-white dark:bg-slate-900 focus:bg-white dark:focus:bg-slate-900 shadow-sm transition-all duration-300 custom-scrollbar"></textarea>
                    </div>

                    <!-- Event Cover Image Upload & URL -->
                    <div class="space-y-4">
                        <div class="space-y-1.5">
                            <label for="cover_file" class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Cover Image File (Optional)</label>
                            <input type="file" name="cover_file" id="cover_file" accept="image/*"
                                class="w-full text-xs text-slate-550 dark:text-slate-400 border border-slate-200 dark:border-slate-800/80 rounded-xl py-2.5 px-4 focus:outline-none file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[10px] file:font-bold file:bg-purple-50 file:text-purple-600 dark:file:bg-purple-950/40 dark:file:text-purple-400 hover:file:bg-purple-100 dark:hover:file:bg-purple-900/40 file:cursor-pointer transition-all">
                            <p class="text-[9px] text-slate-450 dark:text-slate-550 mt-1">Upload a high-resolution PNG, JPG or WEBP image directly.</p>
                        </div>

                        <div class="space-y-1.5">
                            <label for="event_image" class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Or Cover Image URL</label>
                            <div class="relative rounded-xl shadow-sm">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </span>
                                <input type="url" name="image" id="event_image"
                                    placeholder="e.g. https://images.unsplash.com/photo-..."
                                    class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 dark:border-slate-800/80 text-slate-700 dark:text-slate-200 text-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-white dark:bg-slate-900 transition-all duration-300">
                            </div>
                        </div>
                    </div>

                    <!-- Registration Settings Card -->
                    <div class="p-6 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm space-y-5">
                        <div class="space-y-1.5">
                            <label for="registration_type" class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Registration Approval Protocol</label>
                            <div class="relative">
                                <select id="registration_type" name="registration_type" required 
                                    class="w-full rounded-xl border border-slate-200 dark:border-slate-800 py-2.5 pl-4 pr-10 text-slate-700 dark:text-slate-200 text-xs font-semibold focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-slate-50 dark:bg-slate-950 focus:bg-white dark:focus:bg-slate-950 transition-all duration-300 appearance-none cursor-pointer">
                                    <option value="admin_approval" selected>Requires Secretariat Approval</option>
                                    <option value="venue_confirmation">Instantly Confirmed on Venue Check-In</option>
                                </select>
                                <span class="absolute inset-y-0 right-3.5 flex items-center pointer-events-none text-slate-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </span>
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <label for="registration_deadline" class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Registration Deadline (Optional)</label>
                            <div class="relative rounded-xl shadow-sm">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <svg class="w-4 h-4 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </span>
                                <input type="datetime-local" name="registration_deadline" id="registration_deadline"
                                    placeholder="Select registration deadline..."
                                    class="w-full rounded-xl border border-slate-200 dark:border-slate-800 py-2.5 pl-10 pr-4 text-slate-700 dark:text-slate-200 text-xs focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-slate-50 dark:bg-slate-950 focus:bg-white dark:focus:bg-slate-950 transition-all duration-300">
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div class="space-y-1.5">
                                <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Event Schedule (Start - End)</label>
                                
                                <div class="bg-slate-50 dark:bg-slate-950/60 p-2 sm:p-4 rounded-xl sm:rounded-2xl border border-slate-200/80 dark:border-slate-800 flex flex-col sm:flex-row items-stretch sm:items-center gap-3 text-left select-none">
                                    <!-- Left vertical Timeline Dot indicator (Hidden on mobile) -->
                                    <div class="hidden sm:flex flex-col items-center justify-between h-20 relative py-1 shrink-0">
                                        <span class="w-2.5 h-2.5 rounded-full bg-purple-500 border-2 border-purple-500 shadow-sm shadow-purple-500/30 z-10 shrink-0"></span>
                                        <div class="absolute top-4 bottom-4 w-0.5 border-l-2 border-dashed border-slate-300 dark:border-slate-800 z-0"></div>
                                        <span class="w-2.5 h-2.5 rounded-full border-2 border-slate-400 dark:border-slate-600 bg-white dark:bg-slate-900 z-10 shrink-0"></span>
                                    </div>

                                    <div class="flex-grow space-y-2 sm:space-y-3">
                                        <!-- Start Date Pill -->
                                        <div id="start-date-pill" class="flex flex-row items-center justify-between bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800/80 border border-slate-200/80 dark:border-slate-800 rounded-full p-2.5 sm:px-4 sm:py-2 cursor-pointer transition-all duration-200 shadow-xs gap-3">
                                            <div class="flex items-center gap-2">
                                                <i class="fa-solid fa-calendar text-purple-500 text-xs shrink-0"></i>
                                                <div class="flex flex-col">
                                                    <span class="text-[8px] sm:text-[9px] font-extrabold uppercase tracking-widest text-slate-400 dark:text-slate-500 leading-none mb-0.5">Start Date</span>
                                                    <span id="start-date-label" class="text-[10px] sm:text-xs font-bold text-slate-800 dark:text-white leading-none">Choose...</span>
                                                </div>
                                            </div>
                                            <div class="h-5 w-px bg-slate-200 dark:bg-slate-800"></div>
                                            <div class="flex flex-col text-right">
                                                <span class="text-[8px] sm:text-[9px] font-extrabold uppercase tracking-widest text-slate-400 dark:text-slate-500 leading-none mb-0.5">Start Time</span>
                                                <span id="start-time-label" class="text-[10px] sm:text-xs font-bold text-slate-800 dark:text-white leading-none">-- : --</span>
                                            </div>
                                        </div>

                                        <!-- End Date Pill -->
                                        <div id="end-date-pill" class="flex flex-row items-center justify-between bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800/80 border border-slate-200/80 dark:border-slate-800 rounded-full p-2.5 sm:px-4 sm:py-2 cursor-pointer transition-all duration-200 shadow-xs gap-3">
                                            <div class="flex items-center gap-2">
                                                <i class="fa-solid fa-calendar text-slate-400 dark:text-slate-500 text-xs shrink-0"></i>
                                                <div class="flex flex-col">
                                                    <span class="text-[8px] sm:text-[9px] font-extrabold uppercase tracking-widest text-slate-400 dark:text-slate-500 leading-none mb-0.5">End Date</span>
                                                    <span id="end-date-label" class="text-[10px] sm:text-xs font-bold text-slate-800 dark:text-white leading-none">Choose...</span>
                                                </div>
                                            </div>
                                            <div class="h-5 w-px bg-slate-200 dark:bg-slate-800"></div>
                                            <div class="flex flex-col text-right">
                                                <span class="text-[8px] sm:text-[9px] font-extrabold uppercase tracking-widest text-slate-400 dark:text-slate-500 leading-none mb-0.5">End Time</span>
                                                <span id="end-time-label" class="text-[10px] sm:text-xs font-bold text-slate-800 dark:text-white leading-none">-- : --</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" name="event_date" id="event_date" required>
                            <input type="hidden" name="end_date" id="end_date" required>

                            <div class="space-y-1.5">
                                <label for="capacity-select" class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Capacity Limit</label>
                                <div class="relative">
                                    <select id="capacity-select" onchange="handleCapacityChange(this)" required 
                                        class="w-full rounded-xl border border-slate-200 dark:border-slate-800 py-2.5 pl-4 pr-10 text-slate-700 dark:text-slate-200 text-xs font-semibold focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-slate-50 dark:bg-slate-950 focus:bg-white dark:focus:bg-slate-950 transition-all duration-300 appearance-none cursor-pointer">
                                        <option value="unlimited" selected>Unlimited</option>
                                        <option value="limited">Limited Seats</option>
                                    </select>
                                    <span class="absolute inset-y-0 right-3.5 flex items-center pointer-events-none text-slate-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div id="capacity-input-wrapper" class="space-y-1.5 hidden border-t border-slate-100 dark:border-slate-800/80 pt-3">
                            <label for="max_participants" class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Maximum Allowed Participants</label>
                            <input type="number" name="max_participants" id="max_participants" min="1" placeholder="e.g. 50"
                                class="w-full rounded-xl border border-slate-200 dark:border-slate-800/80 py-2.5 px-4 text-slate-700 dark:text-slate-200 text-xs focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-slate-50 dark:bg-slate-950 focus:bg-white dark:focus:bg-slate-950 transition-all duration-300">
                        </div>
                    </div>
                </div>

                <!-- Right Column: Location Engine & interactive Map (7 Cols) -->
                <div class="lg:col-span-7 space-y-6 bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-3xl p-6 sm:p-8 shadow-sm">
                    <div class="flex items-center gap-2 pb-1 border-b border-slate-100 dark:border-slate-800/60">
                        <span class="flex items-center justify-center w-5 h-5 rounded-full bg-purple-500/10 text-purple-600 dark:text-purple-400 font-bold text-[10px]">2</span>
                        <span class="text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Location Console</span>
                    </div>

                    <!-- Choice: Physical or Virtual Assembly -->
                    <div class="space-y-2">
                        <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Location Format</label>
                        <div class="grid grid-cols-2 gap-4">
                            <label class="flex flex-col items-center justify-center gap-2 p-4 bg-slate-50 dark:bg-slate-950 border-2 border-purple-500 dark:border-purple-500 rounded-2xl cursor-pointer transition-all duration-300 select-none text-slate-850 dark:text-slate-100 hover:shadow-sm" id="label_location_type_physical">
                                <input type="radio" name="location_type" value="physical" checked class="hidden" onchange="toggleLocationType('physical')">
                                <span class="text-lg">📍</span>
                                <span class="font-bold text-xs tracking-wide">Physical Venue</span>
                                <span class="text-[9px] font-medium text-slate-400 dark:text-slate-500 text-center">Interactive Google Map Pin</span>
                            </label>
                            <label class="flex flex-col items-center justify-center gap-2 p-4 bg-slate-50 dark:bg-slate-950 border-2 border-transparent hover:border-slate-200 dark:hover:border-slate-800 rounded-2xl cursor-pointer transition-all duration-300 select-none text-slate-500 dark:text-slate-400 hover:shadow-md" id="label_location_type_virtual">
                                <input type="radio" name="location_type" value="virtual" class="hidden" onchange="toggleLocationType('virtual')">
                                <span class="text-lg">💻</span>
                                <span class="font-bold text-xs tracking-wide">Virtual Assembly</span>
                                <span class="text-[9px] font-medium text-slate-400 dark:text-slate-500 text-center">Custom Meeting URL Link</span>
                            </label>
                        </div>
                    </div>

                    <!-- PHYSICAL VENUE MAP AREA -->
                    <div id="physical-location-wrapper" class="space-y-5">
                        <div class="space-y-1.5 relative">
                            <label for="event_location" class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Venue Location Search</label>
                            <div class="relative bg-white dark:bg-slate-950 rounded-xl border border-slate-200 dark:border-slate-800 focus-within:border-purple-500 focus-within:ring-4 focus-within:ring-purple-500/10 shadow-sm transition-all duration-300 hover:scale-[1.002]">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <svg class="w-4.5 h-4.5 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </span>
                                <input type="text" name="location" id="event_location" required
                                    onkeyup="autocompleteLocation(this.value)"
                                    placeholder="Type city, building, or street address..."
                                    class="w-full pl-10 pr-4 py-3 rounded-xl border-0 text-slate-700 dark:text-slate-200 text-sm focus:ring-0 focus:outline-none bg-transparent placeholder-slate-400 dark:placeholder-slate-500 font-medium">
                            </div>
                            
                            <!-- Nominatim Autocomplete Suggestions Popup -->
                            <div id="map-search-suggestions" 
                                 style="z-index: 1100 !important;"
                                 class="absolute left-0 right-0 top-full mt-2 bg-white/95 dark:bg-slate-900/95 backdrop-blur-md border border-slate-200/80 dark:border-slate-800 rounded-2xl shadow-2xl max-h-56 overflow-y-auto hidden custom-scrollbar py-1.5 ring-1 ring-black/5 divide-y divide-slate-100 dark:divide-slate-800/60">
                            </div>
                        </div>

                        <!-- Embedded Leaflet Interactive Map Container -->
                        <div class="space-y-1.5 relative">
                            <span class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Interactive Map (Drag marker or click to refine)</span>
                            <div class="relative overflow-hidden rounded-2xl border border-slate-200 dark:border-slate-800/80 shadow-xs">
                                <div id="google-map" class="h-64 w-full bg-slate-50 dark:bg-slate-950 z-10"></div>
                            </div>
                        </div>

                        <!-- Advanced Access Instructions Button and Dropdown -->
                        <div class="space-y-2.5 pt-1">
                            <button type="button" onclick="toggleArrivalInstructions()" 
                                class="text-xs font-bold text-purple-600 dark:text-purple-400 hover:text-purple-700 dark:hover:text-purple-300 flex items-center gap-1.5 p-1.5 rounded-lg hover:bg-purple-50 dark:hover:bg-purple-950/20 transition-all duration-200 select-none">
                                <svg class="w-4 h-4 shrink-0 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>Provide entry/room or arrival instructions?</span>
                            </button>
                            
                            <div id="arrival-instructions-wrapper" class="space-y-1.5 hidden">
                                <textarea name="arrival_instructions" id="event_arrival_instructions" rows="2" 
                                    placeholder="e.g. Elevators are accessible from the South Lobby. Present ID at Room 302..."
                                    class="w-full rounded-xl border border-slate-200 dark:border-slate-800/80 py-2.5 px-4 text-slate-700 dark:text-slate-200 text-xs focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-slate-50 dark:bg-slate-950 focus:bg-white dark:focus:bg-slate-950 transition-all duration-300 shadow-sm custom-scrollbar"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- VIRTUAL MEETING LINK AREA (Revealed dynamically) -->
                    <div id="virtual-location-wrapper" class="space-y-4 hidden">
                        <div class="space-y-1.5">
                            <label for="event_meeting_link" class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Virtual Meeting URL</label>
                            <div class="relative bg-white dark:bg-slate-950 rounded-xl border border-slate-200 dark:border-slate-800/80 focus-within:border-purple-500 focus-within:ring-4 focus-within:ring-purple-500/10 shadow-sm transition-all duration-300">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                    </svg>
                                </span>
                                <input type="url" name="meeting_link" id="event_meeting_link"
                                    placeholder="e.g. https://zoom.us/j/987654321 or MS Teams link..."
                                    class="w-full pl-10 pr-4 py-3 rounded-xl border-0 text-slate-700 dark:text-slate-200 text-sm focus:ring-0 focus:outline-none bg-transparent placeholder-slate-400 dark:placeholder-slate-500 font-medium font-semibold">
                            </div>
                        </div>
                    </div>

                    <!-- Submit & Actions Panel -->
                    <div class="pt-6 border-t border-slate-100 dark:border-slate-800/60 flex flex-col sm:flex-row items-stretch sm:items-center justify-end gap-3 w-full">
                        <a href="{{ route('committees.events.index', ['committee_id' => $committee->id]) }}"
                            class="w-full sm:w-auto px-5 py-3 rounded-xl border border-slate-200 dark:border-slate-800 text-slate-650 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 text-sm font-semibold transition-all text-center shadow-xs">
                            Cancel
                        </a>
                        <button type="submit"
                            class="w-full sm:w-auto px-6 py-3 rounded-xl bg-purple-600 hover:bg-purple-700 text-white font-bold text-sm shadow-md hover:shadow-purple-500/20 transform active:scale-95 transition-all text-center">
                            Schedule Assembly
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        // Toggle Capacity input field visibility
        function handleCapacityChange(selectEl) {
            const val = selectEl.value;
            const wrapper = document.getElementById('capacity-input-wrapper');
            const input = document.getElementById('max_participants');

            if (val === 'limited') {
                wrapper.classList.remove('hidden');
                input.setAttribute('required', 'required');
            } else {
                wrapper.classList.add('hidden');
                input.removeAttribute('required');
                input.value = '';
            }
        }

        // Leaflet-Google Hybrid Global Instances
        let map;
        let marker;
        let debounceTimeout;

        function initHybridMap() {
            if (map) return;

            const defaultCoords = [10.3157, 123.8854]; // Cebu City Centered

            map = L.map('google-map').setView(defaultCoords, 13);

            L.tileLayer('https://mt1.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
                maxZoom: 21,
                attribution: '© Google Maps'
            }).addTo(map);

            marker = L.marker(defaultCoords, { draggable: true }).addTo(map);

            marker.on('dragend', function() {
                const pos = marker.getLatLng();
                reverseGeocode(pos.lat, pos.lng);
            });

            map.on('click', function(e) {
                marker.setLatLng(e.latlng);
                reverseGeocode(e.latlng.lat, e.latlng.lng);
            });
        }

        // Live Free autocomplete Nominatim searches
        function autocompleteLocation(query) {
            clearTimeout(debounceTimeout);
            const suggestionsContainer = document.getElementById('map-search-suggestions');
            if (!query || query.trim().length < 3) {
                suggestionsContainer.innerHTML = '';
                suggestionsContainer.classList.add('hidden');
                return;
            }

            debounceTimeout = setTimeout(() => {
                fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=10&countrycodes=ph`)
                    .then(res => res.json())
                    .then(data => {
                        suggestionsContainer.innerHTML = '';
                        if (data && data.length > 0) {
                            suggestionsContainer.classList.remove('hidden');
                            data.forEach(item => {
                                const btn = document.createElement('button');
                                btn.type = 'button';
                                btn.className = 'w-full text-left px-4 py-3 text-xs text-slate-700 dark:text-slate-200 hover:bg-purple-50 dark:hover:bg-purple-950/20 transition-all duration-150 flex items-start gap-2.5';
                                
                                const addressParts = item.display_name.split(',');
                                const title = addressParts[0] || '';
                                const subtitle = addressParts.slice(1).join(',').trim();

                                btn.innerHTML = `
                                    <span class="text-purple-500 mt-0.5 select-none">📍</span>
                                    <div class="truncate flex-grow">
                                        <div class="font-bold text-slate-800 dark:text-slate-100 truncate">${title}</div>
                                        ${subtitle ? `<div class="text-[10px] text-slate-400 dark:text-slate-500 truncate mt-0.5">${subtitle}</div>` : ''}
                                    </div>
                                `;
                                btn.onclick = () => {
                                    selectAddress(item.display_name, parseFloat(item.lat), parseFloat(item.lon));
                                };
                                suggestionsContainer.appendChild(btn);
                            });
                        } else {
                            suggestionsContainer.classList.add('hidden');
                        }
                    })
                    .catch(err => console.error(err));
            }, 400);
        }

        function selectAddress(address, lat, lon) {
            document.getElementById('event_location').value = address;
            document.getElementById('map-search-suggestions').innerHTML = '';
            document.getElementById('map-search-suggestions').classList.add('hidden');

            if (map) {
                const latlng = [lat, lon];
                map.setView(latlng, 16);
                marker.setLatLng(latlng);
            }
        }

        function reverseGeocode(lat, lon) {
            fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}`)
                .then(res => res.json())
                .then(data => {
                    if (data && data.display_name) {
                        document.getElementById('event_location').value = data.display_name;
                    }
                })
                .catch(err => console.error(err));
        }

        // Toggle Physical vs Virtual
        function toggleLocationType(type) {
            const physicalWrapper = document.getElementById('physical-location-wrapper');
            const virtualWrapper = document.getElementById('virtual-location-wrapper');
            const inputLocation = document.getElementById('event_location');
            const inputMeetingLink = document.getElementById('event_meeting_link');

            const labelPhysical = document.getElementById('label_location_type_physical');
            const labelVirtual = document.getElementById('label_location_type_virtual');

            if (type === 'physical') {
                physicalWrapper.classList.remove('hidden');
                virtualWrapper.classList.add('hidden');
                inputLocation.setAttribute('required', 'required');
                inputMeetingLink.removeAttribute('required');

                labelPhysical.className = "flex flex-col items-center justify-center gap-2 p-4 bg-slate-50 dark:bg-slate-950 border-2 border-purple-500 dark:border-purple-500 rounded-2xl cursor-pointer transition-all duration-300 select-none text-slate-850 dark:text-slate-100 hover:shadow-sm";
                labelVirtual.className = "flex flex-col items-center justify-center gap-2 p-4 bg-slate-50 dark:bg-slate-950 border-2 border-transparent hover:border-slate-200 dark:hover:border-slate-800 rounded-2xl cursor-pointer transition-all duration-300 select-none text-slate-550 dark:text-slate-400 hover:shadow-md";

                setTimeout(() => {
                    if (map && marker) {
                        map.invalidateSize();
                        map.setView(marker.getLatLng(), map.getZoom());
                    }
                }, 100);
            } else {
                physicalWrapper.classList.add('hidden');
                virtualWrapper.classList.remove('hidden');
                inputLocation.removeAttribute('required');
                inputMeetingLink.setAttribute('required', 'required');

                labelPhysical.className = "flex flex-col items-center justify-center gap-2 p-4 bg-slate-550/5 dark:bg-slate-950 border-2 border-transparent hover:border-slate-200 dark:hover:border-slate-800 rounded-2xl cursor-pointer transition-all duration-300 select-none text-slate-500 dark:text-slate-400 hover:shadow-md";
                labelVirtual.className = "flex flex-col items-center justify-center gap-2 p-4 bg-slate-50 dark:bg-slate-950 border-2 border-purple-500 dark:border-purple-500 rounded-2xl cursor-pointer transition-all duration-300 select-none text-slate-850 dark:text-slate-100 hover:shadow-sm";
            }
        }

        // Toggle Arrival instructions text block
        function toggleArrivalInstructions() {
            const wrapper = document.getElementById('arrival-instructions-wrapper');
            wrapper.classList.toggle('hidden');
        }

        document.addEventListener('DOMContentLoaded', () => {
            initHybridMap();

            const createEventForm = document.querySelector('form');
            if (createEventForm) {
                createEventForm.addEventListener('submit', function(e) {
                    const locType = document.querySelector('input[name="location_type"]:checked').value;
                    if (locType === 'virtual') {
                        const meetingLink = document.getElementById('event_meeting_link').value;
                        document.getElementById('event_location').value = meetingLink;
                    }
                });
            }

            const flatpickrConfig = {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                altInput: true,
                altFormat: "F j, Y • h:i K",
                minDate: "today",
                altInputClass: "w-full rounded-xl border border-slate-200 dark:border-slate-800 py-2.5 pl-10 pr-4 text-slate-700 dark:text-slate-200 text-xs focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-slate-50 dark:bg-slate-950 focus:bg-white dark:focus:bg-slate-950 transition-all duration-300 cursor-pointer shadow-sm hover:scale-[1.002]",
                locale: {
                    firstDayOfWeek: 1
                }
            };

            flatpickr("#registration_deadline", flatpickrConfig);

            const startPicker = flatpickr("#start-date-pill", {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                minDate: "today",
                onChange: function(selectedDates, dateStr, instance) {
                    if (selectedDates.length > 0) {
                        const date = selectedDates[0];
                        const optionsDate = { weekday: 'short', month: 'short', day: 'numeric' };
                        const formattedDate = date.toLocaleDateString('en-US', optionsDate);
                        
                        const optionsTime = { hour: '2-digit', minute: '2-digit', hour12: true };
                        let formattedTime = date.toLocaleTimeString('en-US', optionsTime);

                        document.getElementById('start-date-label').innerText = formattedDate;
                        document.getElementById('start-time-label').innerText = formattedTime;
                        
                        document.getElementById('event_date').value = dateStr;

                        if (endPicker) {
                            endPicker.set('minDate', dateStr);
                        }
                    }
                }
            });

            const endPicker = flatpickr("#end-date-pill", {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                minDate: "today",
                onChange: function(selectedDates, dateStr, instance) {
                    if (selectedDates.length > 0) {
                        const date = selectedDates[0];
                        const optionsDate = { weekday: 'short', month: 'short', day: 'numeric' };
                        const formattedDate = date.toLocaleDateString('en-US', optionsDate);
                        
                        const optionsTime = { hour: '2-digit', minute: '2-digit', hour12: true };
                        let formattedTime = date.toLocaleTimeString('en-US', optionsTime);

                        document.getElementById('end-date-label').innerText = formattedDate;
                        document.getElementById('end-time-label').innerText = formattedTime;
                        
                        document.getElementById('end_date').value = dateStr;
                    }
                }
            });
        });
    </script>
@endsection