<!-- TAB 3: MANAGE QUESTIONS PANEL -->
<div id="tab-panel-questions" class="hidden space-y-6">
    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 p-6 sm:p-8 shadow-sm space-y-6 max-w-2xl mx-auto">
        <div class="space-y-1 text-left">
            <h3 class="font-extrabold text-slate-900 dark:text-white text-lg">RSVP Form Questions</h3>
            <p class="text-xs text-slate-550 dark:text-slate-400 leading-normal">Configure which profile details guests must supply when registering for this assembly.</p>
        </div>

        <form action="{{ route('committees.events.update_fields', $event) }}" method="POST" class="space-y-6 text-left">
            @csrf
            
            <div class="divide-y divide-slate-100 dark:divide-slate-800/60 text-sm mb-4">
                <!-- Full Name (Readonly) -->
                <div class="py-4 flex items-center justify-between text-slate-400 dark:text-slate-500 font-medium">
                    <span class="font-semibold text-slate-550 dark:text-slate-450">Full Name</span>
                    <span class="text-[10px] uppercase font-bold text-purple-650 dark:text-purple-400">Required & Always Active</span>
                </div>

                <!-- Email Address (Readonly) -->
                <div class="py-4 flex items-center justify-between text-slate-400 dark:text-slate-500 font-medium">
                    <span class="font-semibold text-slate-550 dark:text-slate-450">Email Address</span>
                    <span class="text-[10px] uppercase font-bold text-purple-650 dark:text-purple-400">Required & Always Active</span>
                </div>

                <!-- Gender Identity (Readonly) -->
                <div class="py-4 flex items-center justify-between text-slate-400 dark:text-slate-500 font-medium">
                    <span class="font-semibold text-slate-550 dark:text-slate-450">Gender Identity</span>
                    <span class="text-[10px] uppercase font-bold text-purple-650 dark:text-purple-400">Required & Always Active</span>
                </div>
            </div>

            @php
                $fieldsConfig = $event->registration_fields ?? [];
                $normalizedFields = [];
                $isNewFormat = false;
                
                if (is_array($fieldsConfig)) {
                    foreach ($fieldsConfig as $k => $v) {
                        if (is_array($v) && isset($v['label'])) {
                            $isNewFormat = true;
                            break;
                        }
                    }
                }

                if ($isNewFormat) {
                    $normalizedFields = $fieldsConfig;
                } else {
                    if (!empty($fieldsConfig['phone']['enabled'])) {
                        $normalizedFields[] = ['id' => 'phone', 'label' => 'Phone Number', 'required' => !empty($fieldsConfig['phone']['required'])];
                    }
                    if (!empty($fieldsConfig['job_title']['enabled'])) {
                        $normalizedFields[] = ['id' => 'job_title', 'label' => 'Corporate Title / Position', 'required' => !empty($fieldsConfig['job_title']['required'])];
                    }
                    if (!empty($fieldsConfig['company']['enabled'])) {
                        $normalizedFields[] = ['id' => 'company', 'label' => 'Company / Department', 'required' => !empty($fieldsConfig['company']['required'])];
                    }
                    if (!empty($fieldsConfig['birthday']['enabled'])) {
                        $normalizedFields[] = ['id' => 'birthday', 'label' => 'Birth Date', 'required' => !empty($fieldsConfig['birthday']['required'])];
                    }
                }
            @endphp

            <div class="space-y-1.5 text-left pt-4 border-t border-slate-100 dark:border-slate-800">
                <h4 class="font-bold text-slate-850 dark:text-slate-200 text-sm">Custom Questions</h4>
                <p class="text-[11px] text-slate-550 dark:text-slate-400 leading-normal">Add other profile parameters or custom questionnaire fields below.</p>
            </div>

            <div id="custom-questions-container" class="space-y-1.5" data-count="{{ count($normalizedFields) }}">
                @foreach($normalizedFields as $index => $field)
                    <div class="py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 dark:border-slate-800/60" id="field-row-{{ $index }}">
                        <div class="flex-grow flex items-center gap-3">
                            <span class="text-xs font-bold text-slate-400">#</span>
                            <input type="hidden" name="registration_fields[{{ $index }}][id]" value="{{ $field['id'] ?? 'field_'.uniqid() }}">
                            <input type="text" name="registration_fields[{{ $index }}][label]" value="{{ $field['label'] }}" required
                                placeholder="e.g. Address"
                                class="flex-grow rounded-xl border border-slate-300 dark:border-slate-800 py-2.5 px-4 text-slate-700 dark:text-slate-200 text-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-white dark:bg-slate-950 focus:bg-white dark:focus:bg-slate-950 transition-all duration-300">
                        </div>
                        <div class="flex items-center gap-4 justify-between sm:justify-end shrink-0">
                            <label class="inline-flex items-center gap-1.5 cursor-pointer">
                                <input type="checkbox" name="registration_fields[{{ $index }}][required]" value="1"
                                    {{ !empty($field['required']) ? 'checked' : '' }}
                                    class="w-4 h-4 rounded border-slate-300 dark:border-slate-800 text-purple-650 bg-white dark:bg-slate-950 focus:ring-purple-500/20">
                                <span class="text-xs text-slate-550 dark:text-slate-400 font-semibold">Required?</span>
                            </label>
                            <button type="button" onclick="removeQuestionField('field-row-{{ $index }}')"
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
                <button type="button" onclick="addQuestionField()"
                    class="inline-flex items-center gap-1.5 text-xs font-bold py-2.5 px-4 rounded-xl border border-purple-200 dark:border-purple-900/30 text-purple-600 dark:text-purple-400 hover:bg-purple-50 dark:hover:bg-purple-950/25 transition duration-150">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add Custom Question
                </button>
            </div>

            <div class="flex items-center justify-end pt-4 border-t border-slate-100 dark:border-slate-800 shrink-0">
                <button type="submit" class="text-xs font-bold py-3 px-6 rounded-xl bg-purple-600 hover:bg-purple-700 text-white transition duration-150 shadow-md hover:shadow-lg focus:outline-none">
                    Save Form Configuration
                </button>
            </div>
        </form>
    </div>
</div>