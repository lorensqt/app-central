<!-- TAB 4: POST-EVENT SURVEY PANEL -->
<div id="tab-panel-survey" class="hidden space-y-6">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 max-w-7xl mx-auto">
        
        <!-- Left 2 Cols: Survey Configuration Form -->
        <div class="lg:col-span-2 bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 p-6 sm:p-8 shadow-sm space-y-6">
            <div class="space-y-1 text-left">
                <h3 class="font-extrabold text-slate-900 dark:text-white text-lg">Post-Event Survey Setup</h3>
                <p class="text-xs text-slate-550 dark:text-slate-400 leading-normal">Configure questions that attendees can answer to share their feedback after the assembly has ended.</p>
            </div>

            <form id="survey-config-form" action="{{ route('committees.events.update_survey', $event) }}" method="POST" class="space-y-6 text-left">
                @csrf
                
                <!-- Toggle Switch -->
                <div class="flex items-center justify-between p-4 sm:p-5 rounded-2xl bg-slate-50/60 dark:bg-slate-950/40 border border-slate-200/60 dark:border-slate-800/80 shadow-sm transition-all duration-300">
                    <div class="space-y-0.5">
                        <label class="font-bold text-slate-800 dark:text-slate-200 text-sm">Enable Survey Integration</label>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400">If enabled, you can define questions and broadcast the feedback form to your attendees.</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer select-none">
                        <input type="checkbox" name="survey_enabled" value="1" id="survey_enabled_toggle" class="sr-only peer"
                            {{ $event->survey_enabled ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-slate-200 dark:bg-slate-800 rounded-full peer peer-focus:ring-4 peer-focus:ring-purple-500/10 dark:peer-focus:ring-purple-500/5 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:after:bg-slate-300 dark:after:border-transparent peer-checked:bg-purple-600"></div>
                    </label>
                </div>

                @php
                    $surveyQuestions = $event->survey_questions ?? [];
                @endphp

                <div class="space-y-1.5 text-left pt-4">
                    <h4 class="font-bold text-slate-850 dark:text-slate-200 text-sm">Feedback Questionnaire</h4>
                    <p class="text-[11px] text-slate-550 dark:text-slate-400 leading-normal">Create clear, descriptive questions. There are no fixed or default questions for this survey.</p>
                </div>

                <!-- Survey Questions Dynamic Container -->
                <div id="survey-questions-container" class="space-y-1.5" data-count="{{ count($surveyQuestions) }}">
                    @foreach($surveyQuestions as $index => $field)
                        <div class="py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 dark:border-slate-800/60" id="survey-field-row-{{ $index }}">
                            <div class="flex-grow flex items-center gap-3">
                                <span class="text-xs font-bold text-slate-400">#</span>
                                <input type="hidden" name="survey_questions[{{ $index }}][id]" value="{{ $field['id'] ?? 'survey_field_'.uniqid() }}">
                                <input type="text" name="survey_questions[{{ $index }}][label]" value="{{ $field['label'] }}" required
                                    placeholder="e.g. How would you rate the overall experience?"
                                    class="flex-grow rounded-xl border border-slate-300 dark:border-slate-800 py-2.5 px-4 text-slate-700 dark:text-slate-200 text-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-white dark:bg-slate-950 focus:bg-white dark:focus:bg-slate-950 transition-all duration-300">
                            </div>
                            <div class="flex items-center gap-4 justify-between sm:justify-end shrink-0">
                                <label class="inline-flex items-center gap-1.5 cursor-pointer">
                                    <input type="checkbox" name="survey_questions[{{ $index }}][required]" value="1"
                                        {{ !empty($field['required']) ? 'checked' : '' }}
                                        class="w-4 h-4 rounded border-slate-300 dark:border-slate-800 text-purple-650 bg-white dark:bg-slate-950 focus:ring-purple-500/20">
                                    <span class="text-xs text-slate-550 dark:text-slate-400 font-semibold">Required?</span>
                                </label>
                                <button type="button" onclick="removeSurveyQuestionField('survey-field-row-{{ $index }}')"
                                    class="text-rose-600 dark:text-rose-400 hover:text-white hover:bg-rose-600 dark:hover:bg-rose-500 bg-rose-50 dark:bg-rose-950/30 p-2 rounded-xl border border-rose-100 dark:border-rose-900/30 transition duration-150 flex items-center justify-center shrink-0 shadow-sm"
                                    title="Remove question">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="pt-2 text-left">
                    <button type="button" onclick="addSurveyQuestionField()"
                        class="inline-flex items-center gap-1.5 text-xs font-bold py-2.5 px-4 rounded-xl border border-purple-200 dark:border-purple-900/30 text-purple-600 dark:text-purple-400 hover:bg-purple-50 dark:hover:bg-purple-950/25 transition duration-150">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Add Survey Question
                    </button>
                </div>

                <div class="flex items-center justify-end pt-4 border-t border-slate-100 dark:border-slate-800 shrink-0">
                    <button type="submit" class="text-xs font-bold py-3 px-6 rounded-xl bg-purple-600 hover:bg-purple-700 active:scale-[0.98] focus:scale-[0.98] text-white transition-all duration-150 shadow-md hover:shadow-lg hover:shadow-purple-500/25 focus:outline-none">
                        Save Survey Configuration
                    </button>
                </div>
            </form>
        </div>

        <!-- Right 1 Col: Dispatch Control & Stats -->
        <div class="space-y-6">
            <!-- Dispatch Card -->
            <div class="relative overflow-hidden bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 p-6 sm:p-8 shadow-sm text-left space-y-6 font-sans">
                <!-- Decorative background elements with rich glowing accents -->
                <div class="absolute -top-10 -right-10 w-24 h-24 bg-gradient-to-tr from-purple-500/10 to-indigo-500/10 dark:from-purple-400/5 dark:to-indigo-400/5 rounded-full blur-2xl pointer-events-none"></div>
                <div class="absolute -bottom-10 -left-10 w-24 h-24 bg-gradient-to-bl from-indigo-500/10 to-purple-500/10 dark:from-indigo-400/5 dark:to-purple-400/5 rounded-full blur-2xl pointer-events-none"></div>

                <div class="relative flex flex-col gap-4">
                    <!-- Status Header / Badge Block -->
                    <div class="flex items-center justify-between gap-2 flex-wrap sm:flex-nowrap">
                        <div class="flex items-center gap-2">
                            <span class="flex items-center justify-center w-8 h-8 rounded-xl bg-purple-500/10 text-purple-600 dark:text-purple-400 text-sm">
                                <i class="fa-solid fa-bullhorn"></i>
                            </span>
                            <h4 class="font-extrabold text-slate-900 dark:text-white text-base tracking-tight">Broadcast Survey</h4>
                        </div>
                        
                        @if(!$event->survey_enabled)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-100 dark:bg-slate-800 text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider shadow-sm">
                                <i class="fa-solid fa-circle-pause text-slate-400"></i>
                                Inactive
                            </span>
                        @elseif(empty($surveyQuestions))
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-500/10 text-[10px] font-bold text-amber-600 dark:text-amber-400 uppercase tracking-wider shadow-sm">
                                <i class="fa-solid fa-triangle-exclamation text-amber-500 animate-pulse"></i>
                                No Questions
                            </span>
                        @elseif($event->survey_sent)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-500/10 text-[10px] font-bold text-blue-600 dark:text-blue-400 uppercase tracking-wider shadow-sm">
                                <i class="fa-solid fa-paper-plane text-blue-500"></i>
                                Sent
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-500/10 text-[10px] font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-wider shadow-sm">
                                <i class="fa-solid fa-circle-check text-emerald-500 animate-pulse"></i>
                                Ready
                            </span>
                        @endif
                    </div>

                    <p class="text-xs text-slate-550 dark:text-slate-400 leading-relaxed">
                        Ready to collect feedback? Once your questions are saved, trigger the broadcast below to securely blast personalized survey emails to all approved invitees.
                    </p>
                </div>

                <!-- Live Checklist checklist metrics -->
                @php
                    $approvedCount = $event->registrations->where('status', 'approved')->count();
                    $questionsCount = count($surveyQuestions);
                @endphp
                <div class="p-4 rounded-2xl bg-slate-50/60 dark:bg-slate-950/40 border border-slate-100/80 dark:border-slate-800/80 space-y-3">
                    <div class="flex items-center justify-between text-xs font-semibold">
                        <span class="text-slate-500 dark:text-slate-400 flex items-center gap-2">
                            <i class="fa-solid fa-users text-purple-500 w-4 text-center"></i>
                            Approved Recipients
                        </span>
                        <span class="text-slate-800 dark:text-slate-200 font-bold font-mono bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 px-2 py-0.5 rounded-lg text-[11px]">{{ $approvedCount }}</span>
                    </div>
                    <div class="h-px bg-slate-100 dark:bg-slate-800/60"></div>
                    <div class="flex items-center justify-between text-xs font-semibold">
                        <span class="text-slate-500 dark:text-slate-400 flex items-center gap-2">
                            <i class="fa-solid fa-list-check text-purple-500 w-4 text-center"></i>
                            Configured Questions
                        </span>
                        <span class="text-slate-800 dark:text-slate-200 font-bold font-mono bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 px-2 py-0.5 rounded-lg text-[11px]">{{ $questionsCount }}</span>
                    </div>
                    <div class="h-px bg-slate-100 dark:bg-slate-800/60"></div>
                    <div class="flex items-center justify-between text-xs font-semibold">
                        <span class="text-slate-500 dark:text-slate-400 flex items-center gap-2">
                            <i class="fa-solid fa-toggle-on text-purple-500 w-4 text-center"></i>
                            Integration Toggle
                        </span>
                        <span class="font-extrabold uppercase tracking-wider text-[10px] {{ $event->survey_enabled ? 'text-emerald-600 dark:text-emerald-400' : 'text-slate-400 dark:text-slate-500' }}">
                            {{ $event->survey_enabled ? 'Enabled' : 'Disabled' }}
                        </span>
                    </div>
                </div>

                @if($event->survey_sent)
                    <div class="p-4 rounded-2xl bg-amber-500/5 dark:bg-amber-500/10 border border-amber-500/20 text-amber-800 dark:text-amber-400 text-xs flex gap-3 shadow-sm">
                        <i class="fa-solid fa-paper-plane text-amber-500 shrink-0 text-base mt-0.5"></i>
                        <div class="space-y-1 text-left">
                            <p class="font-bold">Surveys dispatched!</p>
                            <p class="text-[10px] leading-relaxed text-slate-555 dark:text-slate-400">Feedback request emails were already broadcasted. Initiating a new dispatch will notify any recently approved invitees and re-request responses.</p>
                        </div>
                    </div>
                @endif

                <form id="broadcast-surveys-form" action="{{ route('committees.events.broadcast_surveys', $event) }}" method="POST" class="pt-2">
                    @csrf
                    <button type="button" id="broadcast-surveys-btn" onclick="openBroadcastConfirmationModal()"
                        {{ !$event->survey_enabled || empty($surveyQuestions) ? 'disabled' : '' }}
                        class="w-full inline-flex items-center justify-center gap-2 text-xs font-bold py-3.5 px-5 rounded-2xl bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-750 hover:to-indigo-700 active:scale-[0.98] focus:scale-[0.98] text-white disabled:from-slate-100 disabled:to-slate-100 dark:disabled:from-slate-800 dark:disabled:to-slate-800 disabled:text-slate-400 dark:disabled:text-slate-550 disabled:border-transparent transition-all duration-200 shadow-md hover:shadow-lg hover:shadow-purple-500/25 disabled:shadow-none focus:outline-none cursor-pointer disabled:cursor-not-allowed">
                        <i class="fa-solid fa-bullhorn text-xs"></i>
                        Broadcast Survey Emails
                    </button>
                </form>
            </div>

            <!-- Stats/Responses Card -->
            <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 p-6 sm:p-8 shadow-sm text-left space-y-5">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="flex items-center justify-center w-8 h-8 rounded-xl bg-purple-500/10 text-purple-600 dark:text-purple-400 text-sm animate-pulse">
                            <i class="fa-solid fa-chart-line"></i>
                        </span>
                        <h4 class="font-extrabold text-slate-900 dark:text-white text-base tracking-tight">Response Progress</h4>
                    </div>
                </div>
                <div class="space-y-4 pt-1">
                    @php
                        $responsesCount = $event->registrations->whereNotNull('survey_responses')->count();
                        $responseRate = $approvedCount > 0 ? round(($responsesCount / $approvedCount) * 100) : 0;
                    @endphp
                    <div class="flex justify-between items-end text-xs font-semibold">
                        <div class="flex flex-col gap-0.5">
                            <span class="text-slate-500 dark:text-slate-400 font-medium">Response Rate</span>
                            <span class="text-[10px] text-slate-400 leading-none">Overall feedback ratio</span>
                        </div>
                        <span class="text-slate-900 dark:text-white font-extrabold font-mono text-sm leading-none">{{ $responsesCount }}<span class="text-slate-400 text-xs font-medium">/{{ $approvedCount }}</span></span>
                    </div>
                    
                    <div class="relative">
                        <!-- Progress bar container -->
                        <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-3.5 overflow-hidden p-[1px] border border-slate-200/40 dark:border-slate-800/60">
                            <!-- Gradient progress fill -->
                            <div class="bg-gradient-to-r from-purple-500 to-indigo-600 dark:from-purple-600 dark:to-indigo-500 h-full rounded-full transition-all duration-700 shadow-[0_1px_3px_rgba(147,51,234,0.3)]" style="width: {{ $responseRate }}%"></div>
                        </div>
                        
                        <!-- Percentage bubble -->
                        <div class="flex justify-end mt-1.5">
                            <span class="text-[10px] font-bold text-purple-600 dark:text-purple-400 font-mono tracking-wide bg-purple-500/10 dark:bg-purple-400/10 px-2 py-0.5 rounded-full">{{ $responseRate }}% Submitted</span>
                        </div>
                    </div>
                </div>

                <div class="pt-2 border-t border-slate-100 dark:border-slate-800/60">
                    <button type="button" onclick="openSurveyAudienceModal()"
                        class="w-full inline-flex items-center justify-center gap-2 text-xs font-bold py-3 px-5 rounded-xl bg-slate-50 dark:bg-slate-950 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-800/80 transition-all duration-200 shadow-xs active:scale-[0.98]">
                        <i class="fa-solid fa-users-viewfinder text-purple-500 text-xs"></i>
                        View Completion Roster
                    </button>
                </div>
            </div>
        </div>

    </div>

    <!-- Submitted Survey Responses List -->
    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 shadow-sm p-6 sm:p-8 max-w-7xl mx-auto space-y-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="space-y-1 text-left">
                <h3 class="font-extrabold text-slate-900 dark:text-white text-lg">Survey Feedbacks</h3>
                <p class="text-xs text-slate-550 dark:text-slate-400 leading-normal">Browse detailed responses submitted by the attendees of this session.</p>
            </div>
            
            <!-- Filters & Search Controls -->
            <div class="flex flex-col sm:flex-row gap-3 items-stretch sm:items-center">
                <!-- Search bar -->
                <div class="relative min-w-[240px] bg-slate-50/50 dark:bg-slate-950 rounded-xl border border-slate-200 dark:border-slate-800 focus-within:border-purple-500 focus-within:ring-4 focus-within:ring-purple-500/10 transition duration-150">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </span>
                    <input type="text" id="survey-search-input" onkeyup="filterSurveyFeedbacks()"
                        placeholder="Search attendee or email..."
                        class="w-full pl-9 pr-4 py-2.5 rounded-xl border-0 text-slate-700 dark:text-slate-200 text-xs focus:ring-0 focus:outline-none bg-transparent placeholder-slate-450">
                </div>
                
                <!-- Sort Dropdown/Button -->
                <div class="relative">
                    <select id="survey-sort-select" onchange="sortSurveyFeedbacks()"
                        class="rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950 py-2.5 pl-4 pr-10 text-slate-700 dark:text-slate-200 text-xs font-semibold focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none transition duration-150 appearance-none cursor-pointer">
                        <option value="desc">Newest First</option>
                        <option value="asc">Oldest First</option>
                    </select>
                    <span class="absolute inset-y-0 right-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                        </svg>
                    </span>
                </div>
            </div>
        </div>

        @php
            $respondedRegistrations = $event->registrations()->whereNotNull('survey_responses')->latest()->get();
        @endphp

        <!-- Empty State Container when there are genuinely no responses -->
        <div id="survey-empty-state" class="{{ $respondedRegistrations->isEmpty() ? '' : 'hidden' }} py-12 border border-dashed border-slate-200 dark:border-slate-800 rounded-2xl text-center space-y-2">
            <p class="text-sm font-semibold text-slate-400">No responses yet</p>
            <p class="text-xs text-slate-550 dark:text-slate-400 max-w-xs mx-auto">Once attendees receive the feedback email and submit their responses, their answers will show up right here in real-time.</p>
        </div>

        <!-- No Results Search State Container -->
        <div id="survey-no-results-state" class="hidden py-12 border border-dashed border-slate-200 dark:border-slate-800 rounded-2xl text-center space-y-2">
            <p class="text-sm font-semibold text-slate-400">No matches found</p>
            <p class="text-xs text-slate-550 dark:text-slate-400 max-w-xs mx-auto">We couldn't find any feedbacks matching your search. Try resetting your query.</p>
            <button type="button" onclick="resetSurveySearch()" class="text-xs font-bold text-purple-600 dark:text-purple-400 hover:underline pt-1">Clear Search</button>
        </div>

        @if($respondedRegistrations->isNotEmpty())
            <div id="survey-table-wrapper" class="border border-slate-100 dark:border-slate-800/80 rounded-2xl overflow-hidden shadow-sm">
                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-left text-sm border-collapse min-w-[700px]">
                        <thead>
                            <tr class="bg-slate-50/50 dark:bg-slate-950/30 border-b border-slate-100 dark:border-slate-800/40 text-slate-400 dark:text-slate-500 text-[10px] font-bold uppercase tracking-wider">
                                <th class="py-3 px-6 text-left">Attendee Details</th>
                                <th class="py-3 px-6 text-left">Answers Summary</th>
                                <th class="py-3 px-6 text-center w-48">Submitted Date</th>
                                <th class="py-3 px-6 text-right w-52">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="survey-feedbacks-tbody" class="divide-y divide-slate-150 dark:divide-slate-800/50">
                            @foreach($respondedRegistrations as $registration)
                                @php
                                    $answersCount = is_array($registration->survey_responses) ? count($registration->survey_responses) : 0;
                                    $responsesJson = json_encode($registration->survey_responses);
                                @endphp
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-950/20 cursor-pointer transition duration-150" 
                                    data-name="{{ strtolower($registration->name) }}"
                                    data-email="{{ strtolower($registration->email) }}"
                                    data-timestamp="{{ $registration->updated_at->timestamp }}"
                                    onclick="openFeedbackDetailsModal('{{ addslashes($registration->name) }}', '{{ addslashes($registration->email) }}', '{{ $registration->updated_at->format('M j, Y • g:i A') }}', {{ $responsesJson }})">
                                    <td class="py-3 px-6 text-left align-middle">
                                        <div class="font-bold text-slate-800 dark:text-slate-200 text-xs leading-normal">{{ $registration->name }}</div>
                                        <div class="text-[10px] text-slate-400 font-mono mt-0.5 leading-none">{{ $registration->email }}</div>
                                    </td>
                                    <td class="py-3 px-6 text-left align-middle">
                                        <span class="inline-flex items-center gap-1.5 text-xs text-slate-600 dark:text-slate-350">
                                            <i class="fa-solid fa-clipboard-question text-purple-500 text-xs shrink-0"></i>
                                            <span class="font-semibold text-slate-800 dark:text-slate-200">{{ $answersCount }}</span> Answers
                                        </span>
                                    </td>
                                    <td class="py-3 px-6 text-center text-xs text-slate-500 font-mono align-middle" data-sort-val="{{ $registration->updated_at->timestamp }}">
                                        {{ $registration->updated_at->format('M j, Y • g:i A') }}
                                    </td>
                                    <td class="py-3 px-6 text-right align-middle">
                                        <div class="flex items-center justify-end gap-2" onclick="event.stopPropagation();">
                                            <!-- View Response Icon-Button -->
                                            <button type="button" 
                                                onclick="openFeedbackDetailsModal('{{ addslashes($registration->name) }}', '{{ addslashes($registration->email) }}', '{{ $registration->updated_at->format('M j, Y • g:i A') }}', {{ $responsesJson }})"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[11px] font-bold text-slate-700 dark:text-slate-300 hover:text-purple-600 dark:hover:text-purple-400 bg-slate-50 hover:bg-purple-500/10 dark:bg-slate-950 dark:hover:bg-purple-400/10 border border-slate-200 dark:border-slate-800 hover:border-purple-300 dark:hover:border-purple-900/40 transition duration-150 active:scale-95 shadow-xs"
                                                title="View response details">
                                                <i class="fa-solid fa-eye text-[10px]"></i>
                                                <span>View</span>
                                            </button>

                                            <!-- Delete Response Form -->
                                            <form action="{{ route('committees.registrations.delete_survey_response', $registration) }}" method="POST" class="inline"
                                                data-confirm="Are you sure you want to clear the survey responses submitted by {{ addslashes($registration->name) }}?"
                                                data-confirm-sub="This action will permanently delete their answers and reset their feedback status to pending, allowing them to fill out the survey again."
                                                data-confirm-title="Clear Survey Responses">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[11px] font-bold text-rose-600 dark:text-rose-400 hover:text-white bg-rose-50 hover:bg-rose-600 dark:bg-rose-950/20 dark:hover:bg-rose-500 border border-rose-100 dark:border-rose-900/30 hover:border-rose-600 dark:hover:border-rose-500 transition duration-150 active:scale-95 shadow-xs"
                                                    title="Clear response feedback">
                                                    <i class="fa-solid fa-trash-can text-[10px]"></i>
                                                    <span>Delete</span>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- DYNAMIC DETAILS MODAL -->
<div id="feedback-details-modal" 
    class="fixed inset-0 bg-slate-900/70 dark:bg-slate-950/90 backdrop-blur-[6px] z-[160] hidden items-center justify-center p-4 transition-all duration-300 opacity-0"
    style="top: 0 !important; left: 0 !important; right: 0 !important; bottom: 0 !important; margin: 0 !important;">
    <div class="bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-100 dark:border-slate-800 max-w-2xl w-full shadow-2xl flex flex-col max-h-[85vh] transition-all duration-300 transform scale-95 opacity-0 overflow-hidden"
        id="feedback-details-modal-content">
        
        <!-- Modal Header -->
        <div class="relative overflow-hidden px-6 py-5 sm:px-8 border-b border-slate-100 dark:border-slate-800/60 shrink-0 bg-white dark:bg-slate-900 bg-gradient-to-r from-purple-50/50 via-transparent to-indigo-50/30 dark:from-purple-950/10 dark:to-transparent">
            <!-- Decorative blur background blobs -->
            <div class="absolute -top-12 -left-12 w-24 h-24 bg-purple-400/10 rounded-full blur-2xl pointer-events-none"></div>
            
            <div class="relative flex justify-between items-center">
                <div class="space-y-0.5">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-purple-500/10 dark:bg-purple-400/10 text-[9px] font-bold text-purple-600 dark:text-purple-400 uppercase tracking-wider mb-1">
                        Attendee Feedback
                    </span>
                    <h3 id="modal-attendee-name" class="font-black text-slate-900 dark:text-white text-lg sm:text-xl leading-tight tracking-tight"></h3>
                    <p id="modal-attendee-email" class="text-xs text-slate-400 font-mono mt-0.5"></p>
                </div>
                
                <button type="button" onclick="closeFeedbackDetailsModal()"
                    class="text-slate-400 dark:text-slate-500 hover:text-slate-600 dark:hover:text-slate-200 p-2 hover:bg-slate-50 dark:hover:bg-slate-800/60 rounded-xl transition duration-150 active:scale-95 shadow-sm border border-transparent hover:border-slate-100 dark:hover:border-slate-700/50">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Modal Body (Scrollable dynamic container of Q&A cards) -->
        <div class="flex-grow overflow-y-auto custom-scrollbar p-6 sm:p-8 space-y-5 bg-slate-50/40 dark:bg-slate-950/10 text-left">
            <div class="flex items-center justify-between text-xs text-slate-400 pb-2 border-b border-slate-100 dark:border-slate-850">
                <span class="font-semibold">Survey Questionnaire Answers</span>
                <span id="modal-submitted-date" class="font-mono"></span>
            </div>
            
            <div id="modal-feedback-answers-container" class="space-y-4 pt-1">
                <!-- Dynamically rendered feedback cards will go here -->
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="flex items-center justify-end px-6 py-4 border-t border-slate-100 dark:border-slate-800 shrink-0 bg-slate-50/50 dark:bg-slate-900/40">
            <button type="button" onclick="closeFeedbackDetailsModal()"
                class="text-xs font-bold py-2.5 px-5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800/80 text-slate-700 dark:text-slate-300 transition duration-150 active:scale-[0.98] shadow-sm">
                Close View
            </button>
        </div>
    </div>
</div>

<!-- PREMIUM BACKDROP MODAL: BROADCAST CONFIRMATION -->
<div id="broadcast-confirmation-modal"
    class="fixed inset-0 bg-slate-900/70 dark:bg-slate-950/90 backdrop-blur-[6px] z-[160] hidden items-center justify-center p-4 transition-all duration-300 opacity-0"
    style="top: 0 !important; left: 0 !important; right: 0 !important; bottom: 0 !important; margin: 0 !important;">
    <div class="bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-100 dark:border-slate-800 max-w-md w-full shadow-2xl flex flex-col transition-all duration-300 transform scale-95 opacity-0 overflow-hidden"
        id="broadcast-confirmation-modal-content">
        
        <!-- Premium Header Panel with Soft Warm Warning Gradient -->
        <div class="relative overflow-hidden px-6 py-5 sm:px-8 border-b border-slate-100 dark:border-slate-800/60 shrink-0 bg-gradient-to-r from-amber-50/50 via-transparent to-purple-50/30 dark:from-amber-950/10 dark:to-transparent">
            <!-- Decorative blur balls -->
            <div class="absolute -top-12 -left-12 w-32 h-32 bg-amber-400/10 rounded-full blur-2xl pointer-events-none"></div>
            <div class="absolute -bottom-12 -right-12 w-32 h-32 bg-purple-400/10 rounded-full blur-2xl pointer-events-none"></div>
            
            <div class="relative flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <span class="flex items-center justify-center w-10 h-10 rounded-2xl bg-amber-500/10 text-amber-600 dark:text-amber-400 text-lg">
                        <i class="fa-solid fa-circle-exclamation"></i>
                    </span>
                    <div>
                        <h3 class="font-bold text-slate-900 dark:text-white text-lg leading-tight tracking-tight">
                            Confirm Broadcast
                        </h3>
                        <p class="text-[11px] text-slate-400 mt-0.5">
                            High-volume notification dispatch
                        </p>
                    </div>
                </div>
                
                <button type="button" onclick="closeBroadcastConfirmationModal()"
                    class="text-slate-400 dark:text-slate-500 hover:text-slate-600 dark:hover:text-slate-200 p-2 hover:bg-slate-50 dark:hover:bg-slate-800/60 rounded-xl transition duration-150 active:scale-95">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>
        </div>

        <!-- Confirmation Warning Body -->
        <div class="p-6 sm:p-8 space-y-4 text-left">
            <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                You are about to trigger an email broadcast for <strong class="text-slate-900 dark:text-white font-bold text-sm">{{ $approvedCount }}</strong> approved attendees of this assembly.
            </p>
            
            <div class="p-4 rounded-2xl bg-amber-500/5 dark:bg-amber-500/10 border border-amber-500/10 text-amber-805 dark:text-amber-400 text-xs flex gap-3">
                <i class="fa-solid fa-triangle-exclamation text-base shrink-0 text-amber-500 mt-0.5"></i>
                <div class="space-y-1">
                    <p class="font-bold text-slate-900 dark:text-white">Important Notice</p>
                    <p class="text-[11px] leading-relaxed text-slate-550 dark:text-slate-400 opacity-90">
                        This action will immediately dispatch high-volume, personalized feedback links to their mailboxes. Please make sure your questions are finalized.
                    </p>
                </div>
            </div>
        </div>

        <!-- Footer Action Bar -->
        <div class="px-6 py-5 sm:px-8 bg-slate-50 dark:bg-slate-950 border-t border-slate-100 dark:border-slate-800/60 flex items-center justify-end gap-3 shrink-0">
            <button type="button" onclick="closeBroadcastConfirmationModal()"
                class="text-xs font-bold text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 px-5 py-3 rounded-xl border border-slate-200 dark:border-slate-800 hover:bg-slate-100 dark:hover:bg-slate-900 bg-white dark:bg-slate-900 transition-all duration-150 active:scale-[0.98]">
                Cancel
            </button>
            <button type="button" onclick="submitBroadcastSurveys()"
                class="inline-flex items-center gap-2 text-xs font-bold py-3 px-6 rounded-xl bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white transition-all duration-150 shadow-md hover:shadow-lg hover:shadow-purple-500/20 active:scale-[0.98] focus:outline-none">
                <i class="fa-solid fa-paper-plane"></i>
                Yes, Dispatch Now
            </button>
        </div>
    </div>
</div>

<!-- PREMIUM BACKDROP MODAL: SURVEY COMPLETION ROSTER -->
<div id="survey-audience-modal"
    class="fixed inset-0 bg-slate-900/70 dark:bg-slate-950/90 backdrop-blur-[6px] z-[160] hidden items-center justify-center p-4 transition-all duration-300 opacity-0"
    style="top: 0 !important; left: 0 !important; right: 0 !important; bottom: 0 !important; margin: 0 !important;">
    <div class="bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-100 dark:border-slate-800 max-w-lg w-full shadow-2xl flex flex-col max-h-[85vh] transition-all duration-300 transform scale-95 opacity-0 overflow-hidden"
        id="survey-audience-modal-content">
        
        <!-- Premium Header Panel -->
        <div class="relative overflow-hidden px-6 py-5 sm:px-8 border-b border-slate-100 dark:border-slate-800/60 shrink-0 bg-gradient-to-r from-purple-50/50 via-transparent to-indigo-50/30 dark:from-purple-950/10 dark:to-transparent">
            <!-- Decorative blur balls -->
            <div class="absolute -top-12 -left-12 w-32 h-32 bg-purple-400/10 rounded-full blur-2xl pointer-events-none"></div>
            
            <div class="relative flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <span class="flex items-center justify-center w-10 h-10 rounded-2xl bg-purple-500/10 text-purple-600 dark:text-purple-400 text-lg">
                        <i class="fa-solid fa-users-viewfinder"></i>
                    </span>
                    <div>
                        <h3 class="font-bold text-slate-900 dark:text-white text-lg leading-tight tracking-tight">
                            Completion Roster
                        </h3>
                        <p class="text-[11px] text-slate-400 mt-0.5">
                            Track survey response completion in real-time
                        </p>
                    </div>
                </div>
                
                <button type="button" onclick="closeSurveyAudienceModal()"
                    class="text-slate-400 dark:text-slate-500 hover:text-slate-600 dark:hover:text-slate-200 p-2 hover:bg-slate-50 dark:hover:bg-slate-800/60 rounded-xl transition duration-150 active:scale-95">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>
        </div>

        <!-- Controls: Search & Tabs -->
        <div class="p-5 sm:px-8 border-b border-slate-100 dark:border-slate-800/60 bg-slate-50/50 dark:bg-slate-950/20 space-y-4">
            <!-- Search Input -->
            <div class="relative w-full bg-white dark:bg-slate-950 rounded-xl border border-slate-200 dark:border-slate-800 focus-within:border-purple-500 focus-within:ring-4 focus-within:ring-purple-500/10 transition duration-150 shadow-sm">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-450">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                </span>
                <input type="text" id="roster-search-input" onkeyup="filterRosterList()"
                    placeholder="Search attendee or email..."
                    class="w-full pl-9 pr-4 py-2.5 rounded-xl border-0 text-slate-700 dark:text-slate-200 text-xs focus:ring-0 focus:outline-none bg-transparent placeholder-slate-400">
            </div>

            <!-- Tab Pills -->
            <div class="flex gap-1.5 p-1 bg-slate-100 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl select-none text-[11px] font-bold">
                <button type="button" onclick="setRosterTab('all')" id="roster-tab-all"
                    class="flex-1 text-center py-2 px-3 rounded-lg text-purple-600 dark:text-purple-400 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm transition-all duration-150">
                    All ({{ $approvedCount }})
                </button>
                @php
                    $submittedCount = $event->registrations->where('status', 'approved')->whereNotNull('survey_responses')->count();
                    $pendingRosterCount = $approvedCount - $submittedCount;
                @endphp
                <button type="button" onclick="setRosterTab('submitted')" id="roster-tab-submitted"
                    class="flex-1 text-center py-2 px-3 rounded-lg text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-white transition-all duration-150">
                    Submitted ({{ $submittedCount }})
                </button>
                <button type="button" onclick="setRosterTab('pending')" id="roster-tab-pending"
                    class="flex-1 text-center py-2 px-3 rounded-lg text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-white transition-all duration-150">
                    Pending ({{ $pendingRosterCount }})
                </button>
            </div>
        </div>

        <!-- Scrollable List of Approved Attendees -->
        <div class="flex-grow overflow-y-auto custom-scrollbar p-6 sm:px-8 space-y-3 max-h-[40vh] bg-slate-50/20 dark:bg-slate-950/5">
            <div id="roster-empty-state" class="hidden py-8 text-center text-slate-400 dark:text-slate-500 text-xs font-semibold">
                No matching attendees found.
            </div>

            <div id="roster-list-container" class="space-y-2.5">
                @php
                    $approvedAttendees = $event->registrations->where('status', 'approved');
                @endphp
                @foreach($approvedAttendees as $attendee)
                    @php
                        $hasSubmitted = !empty($attendee->survey_responses);
                        $initials = strtoupper(substr($attendee->name, 0, 1));
                    @endphp
                    <div class="roster-item p-3.5 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-2xl flex items-center justify-between gap-4 transition duration-150 hover:shadow-xs"
                        data-name="{{ strtolower($attendee->name) }}"
                        data-email="{{ strtolower($attendee->email) }}"
                        data-status="{{ $hasSubmitted ? 'submitted' : 'pending' }}">
                        <div class="flex items-center gap-3 min-w-0">
                            <!-- High-end Initials Avatar -->
                            <div class="w-9 h-9 rounded-xl flex items-center justify-center text-xs font-black select-none shrink-0 bg-purple-500/10 text-purple-600 dark:text-purple-400">
                                {{ $initials }}
                            </div>
                            <div class="min-w-0 text-left">
                                <h4 class="font-bold text-slate-800 dark:text-slate-200 text-xs truncate leading-tight">{{ $attendee->name }}</h4>
                                <p class="text-[10px] text-slate-400 font-mono truncate mt-0.5">{{ $attendee->email }}</p>
                            </div>
                        </div>

                        <!-- Status Badge -->
                        <div class="shrink-0">
                            @if($hasSubmitted)
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-emerald-500/10 text-[9px] font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-wider shadow-inner">
                                    <i class="fa-solid fa-circle-check text-[10px]"></i>
                                    Submitted
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-amber-500/10 text-[9px] font-bold text-amber-600 dark:text-amber-400 uppercase tracking-wider shadow-inner">
                                    <i class="fa-solid fa-circle-notch text-[10px] animate-spin"></i>
                                    Pending
                                </span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Footer -->
        <div class="px-6 py-5 sm:px-8 bg-slate-50 dark:bg-slate-950 border-t border-slate-100 dark:border-slate-800/60 flex items-center justify-end shrink-0">
            <button type="button" onclick="closeSurveyAudienceModal()"
                class="text-xs font-bold text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 px-5 py-3 rounded-xl border border-slate-200 dark:border-slate-800 hover:bg-slate-100 dark:hover:bg-slate-900 bg-white dark:bg-slate-900 transition-all duration-150 active:scale-[0.98] shadow-sm">
                Close View
            </button>
        </div>
    </div>
</div>

<script>
    let surveyQuestionIndex = parseInt(document.getElementById('survey-questions-container')?.dataset.count || 0);

    function reindexSurveyQuestions() {
        const container = document.getElementById('survey-questions-container');
        if (!container) return;

        const rows = container.querySelectorAll('[id^="survey-field-row-"]');
        rows.forEach((row, index) => {
            row.id = `survey-field-row-${index}`;
            
            // Update hidden ID input name
            const idInput = row.querySelector('input[type="hidden"]');
            if (idInput) {
                idInput.name = `survey_questions[${index}][id]`;
            }

            // Update label input name
            const labelInput = row.querySelector('input[type="text"]');
            if (labelInput) {
                labelInput.name = `survey_questions[${index}][label]`;
            }

            // Update checkbox name
            const checkboxInput = row.querySelector('input[type="checkbox"]');
            if (checkboxInput) {
                checkboxInput.name = `survey_questions[${index}][required]`;
            }

            // Update remove button onclick
            const removeBtn = row.querySelector('button[onclick^="removeSurveyQuestionField"]');
            if (removeBtn) {
                removeBtn.setAttribute('onclick', `removeSurveyQuestionField('survey-field-row-${index}')`);
            }
        });

        surveyQuestionIndex = rows.length;
        container.dataset.count = rows.length;

        // Disable/Enable Broadcast Button state depending on questions count
        const broadcastBtn = document.getElementById('broadcast-surveys-btn');
        const toggleChecked = document.getElementById('survey_enabled_toggle')?.checked;
        if (broadcastBtn) {
            if (rows.length > 0 && toggleChecked) {
                broadcastBtn.removeAttribute('disabled');
            } else {
                broadcastBtn.setAttribute('disabled', 'disabled');
            }
        }
    }

    function addSurveyQuestionField() {
        const container = document.getElementById('survey-questions-container');
        if (!container) return;

        const uniqueId = 'survey_field_' + Date.now() + Math.random().toString(36).substr(2, 5);
        const index = surveyQuestionIndex++;
        
        const row = document.createElement('div');
        row.className = "py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 dark:border-slate-800/60 animate-fade-in";
        row.id = `survey-field-row-${index}`;
        
        row.innerHTML = `
            <div class="flex-grow flex items-center gap-3">
                <span class="text-xs font-bold text-slate-400">#</span>
                <input type="hidden" name="survey_questions[${index}][id]" value="${uniqueId}">
                <input type="text" name="survey_questions[${index}][label]" required
                    placeholder="e.g. How would you rate the overall experience?"
                    class="flex-grow rounded-xl border border-slate-300 dark:border-slate-800 py-2.5 px-4 text-slate-700 dark:text-slate-200 text-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-white dark:bg-slate-950 focus:bg-white dark:focus:bg-slate-950 transition-all duration-300">
            </div>
            <div class="flex items-center gap-4 justify-between sm:justify-end shrink-0">
                <label class="inline-flex items-center gap-1.5 cursor-pointer">
                    <input type="checkbox" name="survey_questions[${index}][required]" value="1"
                        class="w-4 h-4 rounded border-slate-300 dark:border-slate-800 text-purple-650 bg-white dark:bg-slate-950 focus:ring-purple-500/20">
                    <span class="text-xs text-slate-550 dark:text-slate-400 font-semibold">Required?</span>
                </label>
                <button type="button" onclick="removeSurveyQuestionField('survey-field-row-${index}')"
                    class="text-rose-600 dark:text-rose-400 hover:text-white hover:bg-rose-600 dark:hover:bg-rose-500 bg-rose-50 dark:bg-rose-950/30 p-2 rounded-xl border border-rose-100 dark:border-rose-900/30 transition duration-150 flex items-center justify-center shrink-0 shadow-sm"
                    title="Remove question">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            </div>
        `;
        
        container.appendChild(row);
        reindexSurveyQuestions();
    }

    function removeSurveyQuestionField(id) {
        const element = document.getElementById(id);
        if (element) {
            element.remove();
            reindexSurveyQuestions();
        }
    }

    // Live update broadcast button state when toggling Survey Enable checkbox
    document.addEventListener('DOMContentLoaded', () => {
        const surveyToggle = document.getElementById('survey_enabled_toggle');
        const broadcastBtn = document.getElementById('broadcast-surveys-btn');
        
        if (surveyToggle) {
            surveyToggle.addEventListener('change', () => {
                const count = document.querySelectorAll('[id^="survey-field-row-"]').length;
                if (broadcastBtn) {
                    if (surveyToggle.checked && count > 0) {
                        broadcastBtn.removeAttribute('disabled');
                    } else {
                        broadcastBtn.setAttribute('disabled', 'disabled');
                    }
                }
            });
        }

        // Intercept Survey Settings Form submission to make it AJAX/Live
        const surveyConfigForm = document.getElementById('survey-config-form');
        if (surveyConfigForm) {
            surveyConfigForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const submitBtn = surveyConfigForm.querySelector('button[type="submit"]');
                if (!submitBtn || submitBtn.disabled) return;

                const originalHTML = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerText = 'Saving Configuration...';

                const formData = new FormData(surveyConfigForm);

                fetch(surveyConfigForm.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalHTML;
                    if (data.success) {
                        window.showToast(data.message, 'success');
                        
                        // Live update broadcast button state based on the saved state
                        if (broadcastBtn) {
                            if (data.survey_enabled && data.survey_questions && data.survey_questions.length > 0) {
                                broadcastBtn.removeAttribute('disabled');
                            } else {
                                broadcastBtn.setAttribute('disabled', 'disabled');
                            }
                        }
                    } else {
                        window.showToast(data.message || 'Failed to save configuration.', 'error');
                    }
                })
                .catch(err => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalHTML;
                    window.showToast('Server connection failed.', 'error');
                });
            });
        }
    });

    // Dynamic Feedback Detail Modal Controllers
    function openFeedbackDetailsModal(name, email, date, responses) {
        const modal = document.getElementById('feedback-details-modal');
        const content = document.getElementById('feedback-details-modal-content');
        if (!modal || !content) return;
        
        document.getElementById('modal-attendee-name').innerText = name;
        document.getElementById('modal-attendee-email').innerText = email;
        document.getElementById('modal-submitted-date').innerText = date;
        
        const container = document.getElementById('modal-feedback-answers-container');
        container.innerHTML = '';
        
        if (responses && typeof responses === 'object') {
            Object.entries(responses).forEach(([question, answer]) => {
                const card = document.createElement('div');
                card.className = "p-4 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-2xl shadow-xs space-y-1.5 transition-all duration-200 hover:shadow-sm text-left";
                card.innerHTML = `
                    <div class="text-[10px] font-extrabold text-purple-600 dark:text-purple-400 tracking-wider uppercase">Q: ${question}</div>
                    <p class="text-sm text-slate-800 dark:text-slate-200 leading-relaxed font-medium whitespace-pre-line">${answer || 'N/A'}</p>
                `;
                container.appendChild(card);
            });
        } else {
            container.innerHTML = `<p class="text-xs text-slate-500">No responses provided.</p>`;
        }

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            content.classList.remove('scale-95', 'opacity-0');
        }, 10);
    }

    function closeFeedbackDetailsModal() {
        const modal = document.getElementById('feedback-details-modal');
        const content = document.getElementById('feedback-details-modal-content');
        if (!modal || !content) return;
        
        modal.classList.add('opacity-0');
        content.classList.add('scale-95', 'opacity-0');
        
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 300);
    }

    // Broadcast Survey Confirmation Modal Controllers
    function openBroadcastConfirmationModal() {
        const modal = document.getElementById('broadcast-confirmation-modal');
        const content = document.getElementById('broadcast-confirmation-modal-content');
        if (!modal || !content) return;
        
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            content.classList.remove('scale-95', 'opacity-0');
        }, 10);
    }

    function closeBroadcastConfirmationModal() {
        const modal = document.getElementById('broadcast-confirmation-modal');
        const content = document.getElementById('broadcast-confirmation-modal-content');
        if (!modal || !content) return;
        
        modal.classList.add('opacity-0');
        content.classList.add('scale-95', 'opacity-0');
        
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 300);
    }

    function submitBroadcastSurveys() {
        const form = document.getElementById('broadcast-surveys-form');
        const confirmBtn = document.querySelector('#broadcast-confirmation-modal-content button[onclick="submitBroadcastSurveys()"]');
        if (!form) return;
        
        // Show high-end premium loading feedback on click
        if (confirmBtn) {
            confirmBtn.disabled = true;
            confirmBtn.innerHTML = '<i class="fa-solid fa-spinner animate-spin mr-2"></i> Dispatching...';
        }
        
        form.submit();
    }

    // Survey Completion Roster Modal Controllers
    let currentRosterTab = 'all';

    function openSurveyAudienceModal() {
        const modal = document.getElementById('survey-audience-modal');
        const content = document.getElementById('survey-audience-modal-content');
        if (!modal || !content) return;
        
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            content.classList.remove('scale-95', 'opacity-0');
        }, 10);
    }

    function closeSurveyAudienceModal() {
        const modal = document.getElementById('survey-audience-modal');
        const content = document.getElementById('survey-audience-modal-content');
        if (!modal || !content) return;
        
        modal.classList.add('opacity-0');
        content.classList.add('scale-95', 'opacity-0');
        
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 300);
    }

    function setRosterTab(tab) {
        currentRosterTab = tab;
        
        const tabs = ['all', 'submitted', 'pending'];
        tabs.forEach(t => {
            const btn = document.getElementById('roster-tab-' + t);
            if (btn) {
                if (t === tab) {
                    btn.className = "flex-1 text-center py-2 px-3 rounded-lg text-purple-600 dark:text-purple-400 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm transition-all duration-150";
                } else {
                    btn.className = "flex-1 text-center py-2 px-3 rounded-lg text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:white transition-all duration-150";
                }
            }
        });
        
        filterRosterList();
    }

    function filterRosterList() {
        const query = document.getElementById('roster-search-input').value.trim().toLowerCase();
        const items = document.querySelectorAll('.roster-item');
        let visibleCount = 0;
        
        items.forEach(item => {
            const name = item.getAttribute('data-name') || '';
            const email = item.getAttribute('data-email') || '';
            const status = item.getAttribute('data-status') || '';
            
            const matchesSearch = name.includes(query) || email.includes(query);
            const matchesTab = currentRosterTab === 'all' || status === currentRosterTab;
            
            if (matchesSearch && matchesTab) {
                item.classList.remove('hidden');
                visibleCount++;
            } else {
                item.classList.add('hidden');
            }
        });
        
        const emptyState = document.getElementById('roster-empty-state');
        if (visibleCount === 0) {
            emptyState.classList.remove('hidden');
        } else {
            emptyState.classList.add('hidden');
        }
    }

    // Client-side real-time query searching
    function filterSurveyFeedbacks() {
        const query = document.getElementById('survey-search-input').value.trim().toLowerCase();
        const tbody = document.getElementById('survey-feedbacks-tbody');
        if (!tbody) return;

        const rows = tbody.querySelectorAll('tr');
        let visibleCount = 0;

        rows.forEach(row => {
            const name = row.getAttribute('data-name') || '';
            const email = row.getAttribute('data-email') || '';
            
            if (name.includes(query) || email.includes(query)) {
                row.classList.remove('hidden');
                visibleCount++;
            } else {
                row.classList.add('hidden');
            }
        });

        const noResultsState = document.getElementById('survey-no-results-state');
        const tableWrapper = document.getElementById('survey-table-wrapper');
        const emptyState = document.getElementById('survey-empty-state');

        if (rows.length === 0) {
            if (emptyState) emptyState.classList.remove('hidden');
            if (tableWrapper) tableWrapper.classList.add('hidden');
            if (noResultsState) noResultsState.classList.add('hidden');
        } else if (visibleCount === 0) {
            if (noResultsState) noResultsState.classList.remove('hidden');
            if (tableWrapper) tableWrapper.classList.add('hidden');
            if (emptyState) emptyState.classList.add('hidden');
        } else {
            if (tableWrapper) tableWrapper.classList.remove('hidden');
            if (noResultsState) noResultsState.classList.add('hidden');
            if (emptyState) emptyState.classList.add('hidden');
        }
    }

    function resetSurveySearch() {
        const searchInput = document.getElementById('survey-search-input');
        if (searchInput) searchInput.value = '';
        filterSurveyFeedbacks();
    }

    // Client-side real-time Date sorting
    function sortSurveyFeedbacks() {
        const sortVal = document.getElementById('survey-sort-select').value;
        const tbody = document.getElementById('survey-feedbacks-tbody');
        if (!tbody) return;

        const rows = Array.from(tbody.querySelectorAll('tr'));
        
        rows.sort((a, b) => {
            const timeA = parseInt(a.getAttribute('data-timestamp') || 0);
            const timeB = parseInt(b.getAttribute('data-timestamp') || 0);
            
            if (sortVal === 'asc') {
                return timeA - timeB;
            } else {
                return timeB - timeA;
            }
        });

        rows.forEach(row => tbody.appendChild(row));
    }
</script>
