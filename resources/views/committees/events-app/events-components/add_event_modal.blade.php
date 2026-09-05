<!-- PREMIUM BACKDROP MODAL: SCHEDULE NEW EVENT -->
<div id="add-event-modal"
    class="fixed inset-0 bg-slate-900/70 dark:bg-slate-950/90 backdrop-blur-[6px] z-50 hidden items-center justify-center p-4 transition-all duration-300 opacity-0">
    <div class="bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-100 dark:border-slate-800 max-w-5xl w-full shadow-2xl flex flex-col max-h-[92vh] transition-all duration-300 transform scale-95 opacity-0 overflow-hidden"
        id="add-event-modal-content">
        
        <!-- Premium Header Panel (Visual Splendor with Subtle Gradients) -->
        <div class="relative overflow-hidden px-6 py-5 sm:px-8 border-b border-slate-100 dark:border-slate-800/60 shrink-0 bg-gradient-to-r from-purple-50/50 via-transparent to-indigo-50/30 dark:from-purple-950/10 dark:to-transparent">
            <!-- Decorative blur balls for background interest -->
            <div class="absolute -top-12 -left-12 w-32 h-32 bg-purple-400/10 rounded-full blur-2xl pointer-events-none"></div>
            <div class="absolute -bottom-12 -right-12 w-32 h-32 bg-indigo-400/10 rounded-full blur-2xl pointer-events-none"></div>
            
            <div class="relative flex justify-between items-center">
                <div>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-purple-500/10 dark:bg-purple-400/10 text-[10px] font-bold text-purple-600 dark:text-purple-400 uppercase tracking-widest mb-2">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                        </svg>
                        Assembly Planner
                    </span>
                    <h3 class="font-bold text-slate-900 dark:text-white text-xl sm:text-2xl leading-tight tracking-tight">
                        Schedule New Assembly
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                        Design a public, highly polished shareable event for the <span class="font-semibold text-purple-600 dark:text-purple-400">{{ $committee->name }}</span>.
                    </p>
                </div>
                
                <button type="button" onclick="closeAddEventModal()"
                    class="text-slate-400 dark:text-slate-500 hover:text-slate-600 dark:hover:text-slate-200 p-2 hover:bg-slate-50 dark:hover:bg-slate-800/60 rounded-xl transition duration-150 active:scale-95 shadow-sm border border-transparent hover:border-slate-100 dark:hover:border-slate-700/50">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <form action="{{ route('committees.events.store') }}" method="POST" class="flex-grow flex flex-col min-h-0">
            @csrf
            
            <!-- Scrollable Premium Form Console -->
            <div class="flex-grow overflow-y-auto custom-scrollbar p-6 sm:p-8 space-y-6 bg-slate-50/40 dark:bg-slate-950/20">
                <!-- Hidden Committee Field -->
                <input type="hidden" name="committee_id" value="{{ $committee->id }}">

                <!-- 2-Column Grid Workspace -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                    
                    <!-- Left Column: Primary Logistics (5 Cols) -->
                    <div class="lg:col-span-5 space-y-5">
                        <div class="flex items-center gap-2 pb-1 border-b border-slate-100 dark:border-slate-800/60">
                            <span class="flex items-center justify-center w-5 h-5 rounded-full bg-purple-500/10 text-purple-600 dark:text-purple-400 font-bold text-[10px]">1</span>
                            <span class="text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">General Settings</span>
                        </div>

                        <!-- Event Title (Stunning Focus Effects) -->
                        <div class="space-y-1.5">
                            <label for="event_title"
                                class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Event Title</label>
                            <input type="text" name="title" id="event_title" required
                                placeholder="e.g. Q3 Strategic Planning Assembly"
                                class="w-full rounded-xl border border-slate-200 dark:border-slate-800/80 py-3 px-4 text-slate-800 dark:text-slate-200 text-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-white dark:bg-slate-950 focus:bg-white dark:focus:bg-slate-950 shadow-sm transition-all duration-300 transform hover:scale-[1.002]">
                        </div>

                        <!-- Event Description -->
                        <div class="space-y-1.5">
                            <label for="event_desc"
                                class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Description</label>
                            <textarea name="description" id="event_desc" required rows="3"
                                placeholder="State the purpose, goal, and requirements of the assembly..."
                                class="w-full rounded-xl border border-slate-200 dark:border-slate-800/80 py-3 px-4 text-slate-800 dark:text-slate-200 text-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-white dark:bg-slate-950 focus:bg-white dark:focus:bg-slate-950 shadow-sm transition-all duration-300 transform hover:scale-[1.002] custom-scrollbar"></textarea>
                        </div>

                        <!-- Event Terms and Policy -->
                        <div class="space-y-1.5">
                            <label for="event_terms_and_policy"
                                class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Terms and Policy</label>
                            <textarea name="terms_and_policy" id="event_terms_and_policy" required rows="3"
                                placeholder="State the terms, rules, and privacy policies for attending this event..."
                                class="w-full rounded-xl border border-slate-200 dark:border-slate-800/80 py-3 px-4 text-slate-800 dark:text-slate-200 text-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-white dark:bg-slate-950 focus:bg-white dark:focus:bg-slate-950 shadow-sm transition-all duration-300 transform hover:scale-[1.002] custom-scrollbar"></textarea>
                        </div>

                        <!-- Event Cover Image URL -->
                        <div class="space-y-1.5">
                            <label for="event_image"
                                class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Cover Image URL (Optional)</label>
                            <div class="relative rounded-xl shadow-sm">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </span>
                                <input type="url" name="image" id="event_image"
                                    placeholder="e.g. https://images.unsplash.com/photo-..."
                                    class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 dark:border-slate-800/80 text-slate-700 dark:text-slate-200 text-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-white dark:bg-slate-950 focus:bg-white dark:focus:bg-slate-950 transition-all duration-300 hover:scale-[1.002]">
                            </div>
                        </div>

                        <!-- Registration Settings Card (Elevated Backdrop Block) -->
                        <div class="p-4 rounded-2xl bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 shadow-[0_4px_20px_rgba(15,23,42,0.02)] space-y-4">
                            <!-- Registration Type Selection -->
                            <div class="space-y-1.5">
                                <label for="registration_type" class="block text-[11px] font-bold text-slate-450 dark:text-slate-400 uppercase tracking-wider">Registration Approval Protocol</label>
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

                            <!-- Registration Deadline -->
                            <div class="space-y-1.5">
                                <label for="registration_deadline" class="block text-[11px] font-bold text-slate-450 dark:text-slate-400 uppercase tracking-wider">Registration Deadline (Optional)</label>
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

                            <!-- Capacity & Date/Time Section -->
                            <div class="space-y-4">
                                <!-- Start - End Date/Time Picker Component (Dual Light & Dark Mode) -->
                                <div class="space-y-1.5">
                                    <label class="block text-[11px] font-bold text-slate-450 dark:text-slate-400 uppercase tracking-wider">Event Schedule (Start - End)</label>
                                    
                                    <div class="bg-slate-100/50 dark:bg-[#1e213a] p-4 rounded-2xl border border-slate-200 dark:border-[#2d3154] flex items-center gap-4 text-left shadow-xs dark:shadow-lg select-none">
                                        <!-- Left side: Vertical Timeline -->
                                        <div class="flex flex-col items-center justify-between h-20 relative py-1 shrink-0">
                                            <!-- Start point: Solid circle -->
                                            <span class="w-3 h-3 rounded-full bg-purple-500 border-2 border-purple-500 shadow-sm shadow-purple-500/30 z-10 shrink-0"></span>
                                            <!-- Vertical dashed line -->
                                            <div class="absolute top-4 bottom-4 w-0.5 border-l-2 border-dashed border-slate-350 dark:border-slate-500/30 z-0"></div>
                                            <!-- End point: Hollow circle -->
                                            <span class="w-3 h-3 rounded-full border-2 border-slate-400 dark:border-slate-400 bg-slate-100/50 dark:bg-[#1e213a] z-10 shrink-0"></span>
                                        </div>

                                        <!-- Right side: Two stacked input rows -->
                                        <div class="flex-grow space-y-3">
                                            <!-- Start Row Pill -->
                                            <div id="start-date-pill" class="flex items-center justify-between bg-white dark:bg-[#272a4a] hover:bg-slate-50 dark:hover:bg-[#2e3158] border border-slate-200 dark:border-[#373b64] rounded-full px-5 py-2 cursor-pointer transition-all duration-200 shadow-xs">
                                                <!-- Left Side: Date Label -->
                                                <div class="flex flex-col">
                                                    <span class="text-[9px] font-extrabold uppercase tracking-widest text-slate-450 dark:text-slate-400 leading-none mb-1">Start Date</span>
                                                    <span id="start-date-label" class="text-xs font-extrabold text-slate-800 dark:text-white leading-none">Choose...</span>
                                                </div>
                                                <!-- Thin Divider -->
                                                <div class="h-6 w-px bg-slate-200 dark:bg-[#3e446d]"></div>
                                                <!-- Right Side: Time Label -->
                                                <div class="flex flex-col text-right">
                                                    <span class="text-[9px] font-extrabold uppercase tracking-widest text-slate-450 dark:text-slate-400 leading-none mb-1">Start Time</span>
                                                    <span id="start-time-label" class="text-xs font-extrabold text-slate-800 dark:text-white leading-none">-- : --</span>
                                                </div>
                                            </div>

                                            <!-- End Row Pill -->
                                            <div id="end-date-pill" class="flex items-center justify-between bg-white dark:bg-[#272a4a] hover:bg-slate-50 dark:hover:bg-[#2e3158] border border-slate-200 dark:border-[#373b64] rounded-full px-5 py-2 cursor-pointer transition-all duration-200 shadow-xs">
                                                <!-- Left Side: Date Label -->
                                                <div class="flex flex-col">
                                                    <span class="text-[9px] font-extrabold uppercase tracking-widest text-slate-450 dark:text-slate-400 leading-none mb-1">End Date</span>
                                                    <span id="end-date-label" class="text-xs font-extrabold text-slate-800 dark:text-white leading-none">Choose...</span>
                                                </div>
                                                <!-- Thin Divider -->
                                                <div class="h-6 w-px bg-slate-200 dark:bg-[#3e446d]"></div>
                                                <!-- Right Side: Time Label -->
                                                <div class="flex flex-col text-right">
                                                    <span class="text-[9px] font-extrabold uppercase tracking-widest text-slate-450 dark:text-slate-400 leading-none mb-1">End Time</span>
                                                    <span id="end-time-label" class="text-xs font-extrabold text-slate-800 dark:text-white leading-none">-- : --</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Actual Hidden Inputs that submit with the form -->
                                <input type="hidden" name="event_date" id="event_date" required>
                                <input type="hidden" name="end_date" id="end_date" required>

                                <!-- Capacity Level -->
                                <div class="space-y-1.5">
                                    <label for="capacity-select" class="block text-[11px] font-bold text-slate-450 dark:text-slate-400 uppercase tracking-wider">Capacity Limit</label>
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

                            <!-- Custom Capacity Limit Input (Revealed Dynamically) -->
                            <div id="capacity-input-wrapper" class="space-y-1.5 hidden border-t border-slate-100 dark:border-slate-800/80 pt-3">
                                <label for="max_participants" class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Maximum Allowed Participants</label>
                                <input type="number" name="max_participants" id="max_participants" min="1" placeholder="e.g. 50"
                                    class="w-full rounded-xl border border-slate-200 dark:border-slate-800/80 py-2.5 px-4 text-slate-700 dark:text-slate-200 text-xs focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-slate-50 dark:bg-slate-950 focus:bg-white dark:focus:bg-slate-950 transition-all duration-300">
                            </div>
                        </div>
                    </div>

                <!-- Right Column: Location Engine & interactive Map (7 Cols) -->
                    <div class="lg:col-span-7 space-y-5">
                        <div class="flex items-center gap-2 pb-1 border-b border-slate-100 dark:border-slate-800/60">
                            <span class="flex items-center justify-center w-5 h-5 rounded-full bg-purple-500/10 text-purple-600 dark:text-purple-400 font-bold text-[10px]">2</span>
                            <span class="text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Location Console</span>
                        </div>

                        <!-- Choice: Physical or Virtual Assembly -->
                        <div class="space-y-2">
                            <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Location Format</label>
                            <div class="grid grid-cols-2 gap-4">
                                <label class="flex flex-col items-center justify-center gap-2 p-4 bg-white dark:bg-slate-900 border-2 border-purple-500 dark:border-purple-500 rounded-2xl cursor-pointer transition-all duration-300 select-none text-slate-850 dark:text-slate-100 hover:shadow-md" id="label_location_type_physical">
                                    <input type="radio" name="location_type" value="physical" checked class="hidden" onchange="toggleLocationType('physical')">
                                    <span class="text-lg">📍</span>
                                    <span class="font-bold text-xs tracking-wide">Physical Venue</span>
                                    <span class="text-[9px] font-medium text-slate-400 dark:text-slate-500 text-center">Interactive Google Map Pin</span>
                                </label>
                                <label class="flex flex-col items-center justify-center gap-2 p-4 bg-white dark:bg-slate-900 border-2 border-transparent hover:border-slate-200 dark:hover:border-slate-800 rounded-2xl cursor-pointer transition-all duration-300 select-none text-slate-500 dark:text-slate-400 hover:shadow-md" id="label_location_type_virtual">
                                    <input type="radio" name="location_type" value="virtual" class="hidden" onchange="toggleLocationType('virtual')">
                                    <span class="text-lg">💻</span>
                                    <span class="font-bold text-xs tracking-wide">Virtual Assembly</span>
                                    <span class="text-[9px] font-medium text-slate-400 dark:text-slate-500 text-center">Custom Meeting URL Link</span>
                                </label>
                            </div>
                        </div>

                        <!-- PHYSICAL VENUE MAP AREA -->
                        <div id="physical-location-wrapper" class="space-y-4">
                            <!-- Venue Search Input with Absolute Floating Suggestions Popup -->
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
                                
                                <!-- Nominatim Autocomplete Suggestions Popup (HIGHLY STYLED, HIGH Z-INDEX PREVENTING OVERLAPS) -->
                                <div id="map-search-suggestions" 
                                     style="z-index: 1100 !important;"
                                     class="absolute left-0 right-0 top-full mt-2 bg-white/95 dark:bg-slate-900/95 backdrop-blur-md border border-slate-200/80 dark:border-slate-800 rounded-2xl shadow-2xl max-h-56 overflow-y-auto hidden custom-scrollbar py-1.5 ring-1 ring-black/5 divide-y divide-slate-100 dark:divide-slate-800/60">
                                </div>
                            </div>

                            <!-- Embedded Leaflet Interactive Map Container (Dark-edged, gorgeous card frame) -->
                            <div class="space-y-1.5 relative">
                                <div class="flex justify-between items-center">
                                    <span class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Interactive Map (Drag marker or click to refine)</span>
                                </div>
                                <div class="relative overflow-hidden rounded-2xl border border-slate-200 dark:border-slate-800/80 shadow-[inset_0_2px_4px_rgba(0,0,0,0.06)]">
                                    <div id="google-map" class="h-52 w-full bg-slate-50 dark:bg-slate-950 z-10"></div>
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

                                <!-- Text Area for Detailed Arrival Instructions (Dynamic slider effect) -->
                                <div id="arrival-instructions-wrapper" class="hidden space-y-1.5 transition-all duration-300">
                                    <label for="arrival_instructions" class="block text-[10px] font-bold text-slate-450 dark:text-slate-500 uppercase tracking-wider">Security, gate, or floor instructions</label>
                                    <textarea name="arrival_instructions" id="arrival_instructions" rows="2"
                                        placeholder="e.g. Enter through Tower B lobby. Approach reception desk and request visitor badge for Floor 8, Suite 804."
                                        class="w-full rounded-xl border border-slate-200 dark:border-slate-800 py-3 px-4 text-slate-700 dark:text-slate-200 text-xs focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-white dark:bg-slate-950 focus:bg-white dark:focus:bg-slate-950 shadow-sm transition-all duration-300 custom-scrollbar"></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- VIRTUAL MEETING AREA -->
                        <div id="virtual-location-wrapper" class="hidden space-y-4">
                            <div class="space-y-1.5">
                                <label for="event_meeting_link" class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Virtual Meeting Link (URL)</label>
                                <div class="relative bg-white dark:bg-slate-950 rounded-xl border border-slate-200 dark:border-slate-800 focus-within:border-purple-500 focus-within:ring-4 focus-within:ring-purple-500/10 shadow-sm transition-all duration-300 hover:scale-[1.002]">
                                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                        <svg class="w-4.5 h-4.5 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                        </svg>
                                    </span>
                                    <input type="url" id="event_meeting_link"
                                        placeholder="e.g. https://zoom.us/j/1234567890"
                                        class="w-full pl-10 pr-4 py-3 rounded-xl border-0 text-slate-700 dark:text-slate-200 text-sm focus:ring-0 focus:outline-none bg-transparent placeholder-slate-400 dark:placeholder-slate-500 font-semibold">
                                </div>
                                <span class="text-[10px] text-slate-400 dark:text-slate-500 block pl-1">Secretariat will instantly review and link this URL for registration confirmation cards.</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Footer Command Panel -->
            <div class="flex items-center justify-end gap-3.5 px-6 py-4 border-t border-slate-100 dark:border-slate-800 shrink-0 bg-slate-50/50 dark:bg-slate-900/40">
                <button type="button" onclick="closeAddEventModal()"
                    class="text-xs font-bold py-3 px-5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800/80 text-slate-700 dark:text-slate-300 transition duration-150 active:scale-[0.98] shadow-sm">
                    Cancel
                </button>
                <button type="submit"
                    class="group inline-flex items-center gap-1.5 text-xs font-bold py-3 px-6 rounded-xl bg-purple-600 hover:bg-purple-700 text-white transition-all duration-200 shadow-md hover:shadow-lg hover:shadow-purple-500/20 active:scale-[0.98]">
                    <span>Schedule Assembly</span>
                    <svg class="w-4 h-4 transition-transform duration-200 group-hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </button>
            </div>
        </form>
    </div>
</div>
