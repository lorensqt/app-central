<!-- PREMIUM BACKDROP MODAL: EDIT EVENT DETAILS -->
<div id="edit-event-modal"
    class="fixed inset-0 bg-slate-900/70 dark:bg-slate-950/90 backdrop-blur-[6px] z-50 hidden items-center justify-center p-4 transition-all duration-300 opacity-0">
    <div class="bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-100 dark:border-slate-800 max-w-3xl w-full shadow-2xl flex flex-col max-h-[92vh] transition-all duration-300 transform scale-95 opacity-0 overflow-hidden"
        id="edit-event-modal-content">
        
        <!-- Premium Header Panel (Visual Splendor with Subtle Gradients) -->
        <div class="relative overflow-hidden px-6 py-5 sm:px-8 border-b border-slate-100 dark:border-slate-800/60 shrink-0 bg-gradient-to-r from-purple-50/50 via-transparent to-indigo-50/30 dark:from-purple-950/10 dark:to-transparent">
            <!-- Decorative blur balls for background interest -->
            <div class="absolute -top-12 -left-12 w-32 h-32 bg-purple-400/10 rounded-full blur-2xl pointer-events-none"></div>
            <div class="absolute -bottom-12 -right-12 w-32 h-32 bg-indigo-400/10 rounded-full blur-2xl pointer-events-none"></div>
            
            <div class="relative flex justify-between items-center">
                <div>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-purple-500/10 dark:bg-purple-400/10 text-[10px] font-bold text-purple-600 dark:text-purple-400 uppercase tracking-widest mb-2">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Editor Deck
                    </span>
                    <h3 class="font-bold text-slate-900 dark:text-white text-xl sm:text-2xl leading-tight tracking-tight">
                        Edit Assembly Details
                    </h3>
                    <p class="text-xs text-slate-550 dark:text-slate-400 mt-1">
                        Modify logistics, scheduling, venue location formats, or participation limits.
                    </p>
                </div>
                
                <button type="button" onclick="closeEditEventModal()"
                    class="text-slate-400 dark:text-slate-500 hover:text-slate-600 dark:hover:text-slate-200 p-2 hover:bg-slate-50 dark:hover:bg-slate-800/60 rounded-xl transition duration-150 active:scale-95 shadow-sm border border-transparent hover:border-slate-100 dark:hover:border-slate-700/50">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <form action="{{ route('committees.events.update', $event) }}" method="POST" class="flex-grow flex flex-col min-h-0">
            @csrf
            @method('PUT')
            
            <!-- Scrollable Premium Form Console -->
            <div class="flex-grow overflow-y-auto custom-scrollbar p-6 sm:p-8 space-y-6 bg-slate-50/40 dark:bg-slate-950/20">
                
                <!-- Section 1: General Settings -->
                <div class="space-y-4">
                    <div class="flex items-center gap-2 pb-1 border-b border-slate-100 dark:border-slate-800/60">
                        <span class="flex items-center justify-center w-5 h-5 rounded-full bg-purple-500/10 text-purple-600 dark:text-purple-400 font-bold text-[10px]">1</span>
                        <span class="text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">General Settings</span>
                    </div>

                    <!-- Event Title -->
                    <div class="space-y-1.5">
                        <label for="edit_event_title"
                            class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Event Title</label>
                        <input type="text" name="title" id="edit_event_title" required
                            value="{{ $event->title }}"
                            placeholder="e.g. Q3 Strategic Planning Assembly"
                            class="w-full rounded-xl border border-slate-200 dark:border-slate-800/80 py-3 px-4 text-slate-800 dark:text-slate-200 text-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-white dark:bg-slate-950 focus:bg-white dark:focus:bg-slate-950 shadow-sm transition-all duration-300 transform hover:scale-[1.002]">
                    </div>

                    <!-- Event Description -->
                    <div class="space-y-1.5">
                        <label for="edit_event_desc"
                            class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Description</label>
                        <textarea name="description" id="edit_event_desc" required rows="3"
                            placeholder="State the purpose, goal, and requirements of the assembly..."
                            class="w-full rounded-xl border border-slate-200 dark:border-slate-800/80 py-3 px-4 text-slate-800 dark:text-slate-200 text-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-white dark:bg-slate-950 focus:bg-white dark:focus:bg-slate-950 shadow-sm transition-all duration-300 transform hover:scale-[1.002] custom-scrollbar">{{ $event->description }}</textarea>
                    </div>

                    <!-- Event Terms and Policy -->
                    <div class="space-y-1.5">
                        <label for="edit_event_terms_and_policy"
                            class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Terms and Policy</label>
                        <textarea name="terms_and_policy" id="edit_event_terms_and_policy" required rows="3"
                            placeholder="State the terms, rules, and privacy policies for attending this event..."
                            class="w-full rounded-xl border border-slate-200 dark:border-slate-800/80 py-3 px-4 text-slate-800 dark:text-slate-200 text-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-white dark:bg-slate-950 focus:bg-white dark:focus:bg-slate-950 shadow-sm transition-all duration-300 transform hover:scale-[1.002] custom-scrollbar">{{ $event->terms_and_policy }}</textarea>
                    </div>

                    <!-- Event Cover Image URL -->
                    <div class="space-y-1.5">
                        <label for="edit_event_image"
                            class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Cover Image URL (Optional)</label>
                        <div class="relative rounded-xl shadow-sm">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </span>
                            <input type="url" name="image" id="edit_event_image"
                                value="{{ $event->image }}"
                                placeholder="e.g. https://images.unsplash.com/photo-..."
                                class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 dark:border-slate-800/80 text-slate-700 dark:text-slate-200 text-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-white dark:bg-slate-950 focus:bg-white dark:focus:bg-slate-950 transition-all duration-300 hover:scale-[1.002]">
                        </div>
                    </div>
                </div>

                <!-- Section 2: Scheduling & Access protocols -->
                <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 shadow-[0_4px_20px_rgba(15,23,42,0.02)] space-y-4">
                    <div class="flex items-center gap-2 pb-1 border-b border-slate-100 dark:border-slate-800/60">
                        <span class="flex items-center justify-center w-5 h-5 rounded-full bg-purple-500/10 text-purple-600 dark:text-purple-400 font-bold text-[10px]">2</span>
                        <span class="text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Scheduling & Protocols</span>
                    </div>

                    <!-- Registration Type Selection -->
                    <div class="space-y-1.5">
                        <label for="edit_registration_type" class="block text-[11px] font-bold text-slate-450 dark:text-slate-400 uppercase tracking-wider">Registration Approval Protocol</label>
                        <div class="relative">
                            <select id="edit_registration_type" name="registration_type" required 
                                class="w-full rounded-xl border border-slate-200 dark:border-slate-800 py-2.5 pl-4 pr-10 text-slate-700 dark:text-slate-200 text-xs font-semibold focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-slate-50 dark:bg-slate-950 focus:bg-white dark:focus:bg-slate-950 transition-all duration-300 appearance-none cursor-pointer">
                                <option value="admin_approval" {{ $event->registration_type === 'admin_approval' ? 'selected' : '' }}>Requires Secretariat Approval</option>
                                <option value="venue_confirmation" {{ $event->registration_type === 'venue_confirmation' ? 'selected' : '' }}>Instantly Confirmed on Venue Check-In</option>
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
                        <label for="edit_registration_deadline" class="block text-[11px] font-bold text-slate-455 dark:text-slate-400 uppercase tracking-wider">Registration Deadline (Optional)</label>
                        <div class="relative rounded-xl shadow-sm">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4.5 h-4.5 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </span>
                            <input type="datetime-local" name="registration_deadline" id="edit_registration_deadline"
                                value="{{ $event->registration_deadline ? $event->registration_deadline->format('Y-m-d\TH:i') : '' }}"
                                placeholder="Select registration deadline..."
                                class="w-full rounded-xl border border-slate-200 dark:border-slate-800 py-2.5 pl-10 pr-4 text-slate-700 dark:text-slate-200 text-xs focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-slate-50 dark:bg-slate-950 focus:bg-white dark:focus:bg-slate-950 transition-all duration-300">
                        </div>
                    </div>

                    <!-- Capacity & Date/Time Section -->
                    <div class="space-y-4">
                        <!-- Start - End Date/Time Picker Component (Dual Light & Dark Mode) -->
                        <div class="space-y-1.5">
                            <label class="block text-[11px] font-bold text-slate-455 dark:text-slate-400 uppercase tracking-wider">Event Schedule (Start - End)</label>
                            
                            <div class="bg-slate-100/50 dark:bg-[#1e213a] p-4 rounded-2xl border border-slate-200 dark:border-[#2d3154] flex items-center gap-4 text-left shadow-xs dark:shadow-lg select-none">
                                <!-- Left side: Vertical Timeline -->
                                <div class="flex flex-col items-center justify-between h-20 relative py-1 shrink-0">
                                    <!-- Start point: Solid circle -->
                                    <span class="w-3 h-3 rounded-full bg-purple-500 border-2 border-purple-500 shadow-sm shadow-purple-500/30 z-10 shrink-0"></span>
                                    <!-- Vertical dashed line -->
                                    <div class="absolute top-4 bottom-4 w-0.5 border-l-2 border-dashed border-slate-350 dark:border-slate-500/30 z-0"></div>
                                    <!-- End point: Hollow circle -->
                                    <span class="w-3 h-3 rounded-full border-2 border-slate-400 bg-slate-100/50 dark:bg-[#1e213a] z-10 shrink-0"></span>
                                </div>

                                <!-- Right side: Two stacked input rows -->
                                <div class="flex-grow space-y-3">
                                    <!-- Start Row Pill -->
                                    <div id="edit-start-date-pill" class="flex items-center justify-between bg-white dark:bg-[#272a4a] hover:bg-slate-50 dark:hover:bg-[#2e3158] border border-slate-200 dark:border-[#373b64] rounded-full px-5 py-2 cursor-pointer transition-all duration-200 shadow-xs">
                                        <!-- Left Side: Date Label -->
                                        <div class="flex flex-col">
                                            <span class="text-[9px] font-extrabold uppercase tracking-widest text-slate-450 dark:text-slate-400 leading-none mb-1">Start Date</span>
                                            <span id="edit-start-date-label" class="text-xs font-extrabold text-slate-800 dark:text-white leading-none">{{ $event->event_date->format('D, M j') }}</span>
                                        </div>
                                        <!-- Thin Divider -->
                                        <div class="h-6 w-px bg-slate-200 dark:bg-[#3e446d]"></div>
                                        <!-- Right Side: Time Label -->
                                        <div class="flex flex-col text-right">
                                            <span class="text-[9px] font-extrabold uppercase tracking-widest text-slate-455 dark:text-slate-400 leading-none mb-1">Start Time</span>
                                            <span id="edit-start-time-label" class="text-xs font-extrabold text-slate-800 dark:text-white leading-none">{{ $event->event_date->format('h:i A') }}</span>
                                        </div>
                                    </div>

                                    <!-- End Row Pill -->
                                    <div id="edit-end-date-pill" class="flex items-center justify-between bg-white dark:bg-[#272a4a] hover:bg-slate-50 dark:hover:bg-[#2e3158] border border-slate-200 dark:border-[#373b64] rounded-full px-5 py-2 cursor-pointer transition-all duration-200 shadow-xs">
                                        <!-- Left Side: Date Label -->
                                        <div class="flex flex-col">
                                            <span class="text-[9px] font-extrabold uppercase tracking-widest text-slate-455 dark:text-slate-400 leading-none mb-1">End Date</span>
                                            <span id="edit-end-date-label" class="text-xs font-extrabold text-slate-800 dark:text-white leading-none">{{ $event->end_date ? $event->end_date->format('D, M j') : $event->event_date->format('D, M j') }}</span>
                                        </div>
                                        <!-- Thin Divider -->
                                        <div class="h-6 w-px bg-slate-200 dark:bg-[#3e446d]"></div>
                                        <!-- Right Side: Time Label -->
                                        <div class="flex flex-col text-right">
                                            <span class="text-[9px] font-extrabold uppercase tracking-widest text-slate-455 dark:text-slate-400 leading-none mb-1">End Time</span>
                                            <span id="edit-end-time-label" class="text-xs font-extrabold text-slate-800 dark:text-white leading-none">{{ $event->end_date ? $event->end_date->format('h:i A') : $event->event_date->format('h:i A') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Actual Hidden Inputs that submit with the form -->
                        <input type="hidden" name="event_date" id="edit_event_date" value="{{ $event->event_date->format('Y-m-d H:i') }}" required>
                        <input type="hidden" name="end_date" id="edit_end_date" value="{{ $event->end_date ? $event->end_date->format('Y-m-d H:i') : $event->event_date->format('Y-m-d H:i') }}" required>

                        <!-- Capacity Level -->
                        <div class="space-y-1.5">
                            <label for="edit-capacity-select" class="block text-[11px] font-bold text-slate-455 dark:text-slate-400 uppercase tracking-wider">Capacity Limit</label>
                            <div class="relative">
                                <select id="edit-capacity-select" onchange="handleEditCapacityChange(this)" required 
                                    class="w-full rounded-xl border border-slate-200 dark:border-slate-800 py-2.5 pl-4 pr-10 text-slate-700 dark:text-slate-200 text-xs font-semibold focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-slate-50 dark:bg-slate-950 focus:bg-white dark:focus:bg-slate-950 transition-all duration-300 appearance-none cursor-pointer">
                                    <option value="unlimited" {{ $event->max_participants === null ? 'selected' : '' }}>Unlimited</option>
                                    <option value="limited" {{ $event->max_participants !== null ? 'selected' : '' }}>Limited Seats</option>
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
                    <div id="edit-capacity-input-wrapper" class="space-y-1.5 {{ $event->max_participants === null ? 'hidden' : '' }} border-t border-slate-100 dark:border-slate-800/80 pt-3">
                        <label for="edit_max_participants" class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Maximum Allowed Participants</label>
                        <input type="number" name="max_participants" id="edit_max_participants" min="1" 
                            value="{{ $event->max_participants }}"
                            placeholder="e.g. 50"
                            class="w-full rounded-xl border border-slate-200 dark:border-slate-800/80 py-2.5 px-4 text-slate-700 dark:text-slate-200 text-xs focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-slate-50 dark:bg-slate-950 focus:bg-white dark:focus:bg-slate-950 transition-all duration-300"
                            {{ $event->max_participants !== null ? 'required' : '' }}>
                    </div>
                </div>

                <!-- Section 3: Location Console -->
                <div class="space-y-4">
                    <div class="flex items-center gap-2 pb-1 border-b border-slate-100 dark:border-slate-800/60">
                        <span class="flex items-center justify-center w-5 h-5 rounded-full bg-purple-500/10 text-purple-600 dark:text-purple-400 font-bold text-[10px]">3</span>
                        <span class="text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Location Setup</span>
                    </div>

                    <!-- Location Format Selection -->
                    <div class="space-y-1.5">
                        <label for="edit_location_type" class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Location Format</label>
                        <div class="relative">
                            <select id="edit_location_type" name="location_type" required 
                                class="w-full rounded-xl border border-slate-200 dark:border-slate-800 py-2.5 pl-4 pr-10 text-slate-700 dark:text-slate-200 text-xs font-semibold focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-slate-50 dark:bg-slate-950 focus:bg-white dark:focus:bg-slate-950 transition-all duration-300 appearance-none cursor-pointer">
                                <option value="physical" {{ $event->location_type === 'physical' ? 'selected' : '' }}>📍 Physical Venue (Address / Map location)</option>
                                <option value="virtual" {{ $event->location_type === 'virtual' ? 'selected' : '' }}>💻 Virtual Assembly (Meeting URL link)</option>
                            </select>
                            <span class="absolute inset-y-0 right-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                                </svg>
                            </span>
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label for="edit_event_location"
                            class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Venue Location / Link</label>
                        <div class="relative bg-white dark:bg-slate-950 rounded-xl border border-slate-200 dark:border-slate-800 focus-within:border-purple-500 focus-within:ring-4 focus-within:ring-purple-500/10 shadow-sm transition-all duration-300 hover:scale-[1.002]">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4.5 h-4.5 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </span>
                            <input type="text" name="location" id="edit_event_location" required
                                value="{{ $event->location }}"
                                placeholder="Type city, address, or virtual link..."
                                class="w-full pl-10 pr-4 py-3 rounded-xl border-0 text-slate-700 dark:text-slate-200 text-sm focus:ring-0 focus:outline-none bg-transparent placeholder-slate-400 dark:placeholder-slate-500 font-medium">
                        </div>
                    </div>

                    <!-- Advanced Access Instructions Button and Dropdown -->
                    <div class="space-y-2.5 pt-1">
                        <button type="button" onclick="toggleEditArrivalInstructions()" 
                            class="text-xs font-bold text-purple-600 dark:text-purple-400 hover:text-purple-700 dark:hover:text-purple-300 flex items-center gap-1.5 p-1.5 rounded-lg hover:bg-purple-50 dark:hover:bg-purple-950/20 transition-all duration-200 select-none">
                            <svg class="w-4 h-4 shrink-0 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>Provide entry/room or arrival instructions?</span>
                        </button>

                        <!-- Text Area for Detailed Arrival Instructions (Dynamic slider effect) -->
                        <div id="edit-arrival-instructions-wrapper" class="{{ $event->arrival_instructions ? '' : 'hidden' }} space-y-1.5 transition-all duration-300">
                            <label for="edit_arrival_instructions" class="block text-[10px] font-bold text-slate-450 dark:text-slate-505 uppercase tracking-wider">Security, gate, or floor instructions</label>
                            <textarea name="arrival_instructions" id="edit_arrival_instructions" rows="2"
                                placeholder="e.g. Enter through Tower B lobby. Approach reception desk and request visitor badge for Floor 8, Suite 804."
                                class="w-full rounded-xl border border-slate-200 dark:border-slate-800 py-3 px-4 text-slate-700 dark:text-slate-200 text-xs focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-white dark:bg-slate-950 focus:bg-white dark:focus:bg-slate-950 shadow-sm transition-all duration-300 custom-scrollbar">{{ $event->arrival_instructions }}</textarea>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Footer Command Panel -->
            <div class="flex items-center justify-end gap-3.5 px-6 py-4 border-t border-slate-100 dark:border-slate-800 shrink-0 bg-slate-50/50 dark:bg-slate-900/40">
                <button type="button" onclick="closeEditEventModal()"
                    class="text-xs font-bold py-3 px-5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800/80 text-slate-700 dark:text-slate-300 transition duration-150 active:scale-[0.98] shadow-sm">
                    Cancel
                </button>
                <button type="submit"
                    class="group inline-flex items-center gap-1.5 text-xs font-bold py-3 px-6 rounded-xl bg-purple-600 hover:bg-purple-700 text-white transition-all duration-200 shadow-md hover:shadow-lg hover:shadow-purple-500/20 active:scale-[0.98]">
                    <span>Save Changes</span>
                    <svg class="w-4 h-4 transition-transform duration-200 group-hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </button>
            </div>
        </form>
    </div>
</div>
