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

<!-- PREMIUM BACKDROP MODAL: ADD ATTENDEE -->
<div id="add-attendee-modal"
    class="fixed inset-0 bg-slate-900/70 dark:bg-slate-950/90 backdrop-blur-[6px] z-[160] hidden items-center justify-center p-4 transition-all duration-300 opacity-0"
    style="top: 0 !important; left: 0 !important; right: 0 !important; bottom: 0 !important; margin: 0 !important;">
    <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 max-w-2xl w-full shadow-2xl flex flex-col max-h-[92vh] transition-all duration-300 transform scale-95 opacity-0 overflow-hidden"
        id="add-attendee-modal-content">
        
        <!-- Premium Header Panel (Visual Splendor with Subtle Gradients) -->
        <div class="relative overflow-hidden px-6 py-5 sm:px-8 border-b border-slate-100 dark:border-slate-800/60 shrink-0 bg-gradient-to-r from-purple-50/50 via-transparent to-indigo-50/30 dark:from-purple-950/10 dark:to-transparent">
            <!-- Decorative blur balls for background interest -->
            <div class="absolute -top-12 -left-12 w-32 h-32 bg-purple-400/10 rounded-full blur-2xl pointer-events-none"></div>
            <div class="absolute -bottom-12 -right-12 w-32 h-32 bg-indigo-400/10 rounded-full blur-2xl pointer-events-none"></div>
            
            <div class="relative flex justify-between items-center">
                <div class="text-left">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-purple-500/10 dark:bg-purple-400/10 text-[10px] font-bold text-purple-600 dark:text-purple-400 uppercase tracking-widest mb-2">
                        <i class="fa-solid fa-user-plus text-[9px]"></i>
                        Organizer Workspace
                    </span>
                    <h3 class="font-bold text-slate-900 dark:text-white text-xl leading-tight tracking-tight">
                        Manually Add Attendee
                    </h3>
                    <p class="text-xs text-slate-550 dark:text-slate-400 mt-1">
                        Register a new attendee individually or as part of a group directly into this assembly.
                    </p>
                </div>
                
                <button type="button" onclick="closeAddAttendeeModal()"
                    class="text-slate-400 dark:text-slate-500 hover:text-slate-600 dark:hover:text-slate-200 p-2 hover:bg-slate-50 dark:hover:bg-slate-800/60 rounded-xl transition duration-150 active:scale-95 shadow-sm border border-transparent hover:border-slate-100 dark:hover:border-slate-700/50">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <form id="add-attendee-form" action="{{ route('events.public_register', $event) }}" method="POST" class="flex-grow flex flex-col min-h-0">
            @csrf
            
            <!-- Scrollable Premium Form Console -->
            <div class="flex-grow overflow-y-auto custom-scrollbar p-6 sm:p-8 space-y-6 bg-slate-50/40 dark:bg-slate-950/20 text-left">
                
                <!-- Section 1: Primary Personal Details -->
                <div class="space-y-4">
                    <div class="flex items-center gap-2 pb-1 border-b border-slate-100 dark:border-slate-800/60">
                        <span class="flex items-center justify-center w-5 h-5 rounded-full bg-purple-500/10 text-purple-600 dark:text-purple-400 font-bold text-[10px]">1</span>
                        <span class="text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Personal Details</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Full Name -->
                        <div class="space-y-1.5">
                            <label for="add_reg_name" class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Full Name <span class="text-rose-500">*</span></label>
                            <input type="text" name="name" id="add_reg_name" required placeholder="e.g. Juan dela Cruz"
                                class="w-full rounded-xl border border-slate-200 dark:border-slate-800/80 py-3 px-4 text-slate-800 dark:text-slate-200 text-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-white dark:bg-slate-950 shadow-sm transition-all duration-300">
                        </div>

                        <!-- Email Address -->
                        <div class="space-y-1.5">
                            <label for="add_reg_email" class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Email Address <span class="text-rose-500">*</span></label>
                            <input type="email" name="email" id="add_reg_email" required placeholder="e.g. juan@example.com"
                                class="w-full rounded-xl border border-slate-200 dark:border-slate-800/80 py-3 px-4 text-slate-800 dark:text-slate-200 text-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-white dark:bg-slate-950 shadow-sm transition-all duration-300">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Gender -->
                        <div class="space-y-1.5">
                            <label for="add_reg_gender" class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Gender Identity <span class="text-rose-500">*</span></label>
                            <select name="gender" id="add_reg_gender" required
                                class="w-full rounded-xl border border-slate-200 dark:border-slate-800/80 py-3 px-4 text-slate-800 dark:text-slate-200 text-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-white dark:bg-slate-950 shadow-sm transition-all duration-300">
                                <option value="" disabled selected>Select gender...</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="LGBTQ+">LGBTQ+</option>
                                <option value="Others">Others</option>
                            </select>
                        </div>

                        <!-- Birthday -->
                        <div class="space-y-1.5">
                            <label for="add_reg_birthday" class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Birth Date <span class="text-rose-500">*</span></label>
                            <input type="date" name="birthday" id="add_reg_birthday" required max="{{ now()->format('Y-m-d') }}"
                                class="w-full rounded-xl border border-slate-200 dark:border-slate-800/80 py-3 px-4 text-slate-800 dark:text-slate-200 text-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-white dark:bg-slate-950 shadow-sm transition-all duration-300 cursor-pointer">
                        </div>
                    </div>

                    <!-- Division -->
                    <div class="space-y-1.5">
                        <label for="add_reg_division" class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Division Assignment <span class="text-rose-500">*</span></label>
                        <input type="text" name="division" id="add_reg_division" required placeholder="e.g. Youth Sector / OPEC Visayas"
                            class="w-full rounded-xl border border-slate-200 dark:border-slate-800/80 py-3 px-4 text-slate-800 dark:text-slate-200 text-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-white dark:bg-slate-950 shadow-sm transition-all duration-300">
                    </div>
                </div>

                <!-- Section 2: Dynamic Custom Fields -->
                @if(count($normalizedFields) > 0)
                    <div class="space-y-4 pt-2">
                        <div class="flex items-center gap-2 pb-1 border-b border-slate-100 dark:border-slate-800/60">
                            <span class="flex items-center justify-center w-5 h-5 rounded-full bg-purple-500/10 text-purple-600 dark:text-purple-400 font-bold text-[10px]">2</span>
                            <span class="text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Questionnaire Answers</span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach($normalizedFields as $field)
                                @php
                                    $fieldId = $field['id'] ?? 'field_' . $loop->index;
                                    $isRequired = !empty($field['required']);
                                    $label = $field['label'];
                                @endphp
                                <div class="space-y-1.5">
                                    <label for="add_reg_custom_{{ $fieldId }}" class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                                        {{ $label }} @if($isRequired)<span class="text-rose-500">*</span>@endif
                                    </label>
                                    
                                    @if($fieldId === 'birthday' || strtolower($label) === 'birth date' || strtolower($label) === 'birthday' || strtolower($label) === 'birthdate')
                                        <input type="date" name="custom_fields[{{ $fieldId }}]" id="add_reg_custom_{{ $fieldId }}" {{ $isRequired ? 'required' : '' }}
                                            class="w-full rounded-xl border border-slate-200 dark:border-slate-800/80 py-3 px-4 text-slate-800 dark:text-slate-200 text-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-white dark:bg-slate-950 shadow-sm transition-all duration-300 cursor-pointer">
                                    @elseif($fieldId === 'phone' || strtolower($label) === 'phone number' || strtolower($label) === 'phone' || strtolower($label) === 'tel')
                                        <input type="tel" name="custom_fields[{{ $fieldId }}]" id="add_reg_custom_{{ $fieldId }}" {{ $isRequired ? 'required' : '' }} placeholder="e.g. +63 917-123-4567"
                                            class="w-full rounded-xl border border-slate-200 dark:border-slate-800/80 py-3 px-4 text-slate-800 dark:text-slate-200 text-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-white dark:bg-slate-950 shadow-sm transition-all duration-300">
                                    @else
                                        <input type="text" name="custom_fields[{{ $fieldId }}]" id="add_reg_custom_{{ $fieldId }}" {{ $isRequired ? 'required' : '' }} placeholder="Enter response..."
                                            class="w-full rounded-xl border border-slate-200 dark:border-slate-800/80 py-3 px-4 text-slate-800 dark:text-slate-200 text-sm focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 focus:outline-none bg-white dark:bg-slate-950 shadow-sm transition-all duration-300">
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Section 3: Group & Companions Registration (Dynamic Repeat Engine) -->
                @if($event->allow_group_registration)
                    <div class="space-y-4 pt-2">
                        <div class="flex items-center gap-2 pb-1 border-b border-slate-100 dark:border-slate-800/60">
                            <span class="flex items-center justify-center w-5 h-5 rounded-full bg-purple-500/10 text-purple-600 dark:text-purple-400 font-bold text-[10px]">
                                {{ count($normalizedFields) > 0 ? '3' : '2' }}
                            </span>
                            <span class="text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Group Settings</span>
                        </div>

                        <!-- Switch Toggle to enable group register -->
                        <div class="flex items-center justify-between p-4 bg-slate-50 dark:bg-slate-900/60 rounded-2xl border border-slate-100 dark:border-slate-800/50">
                            <div class="space-y-0.5">
                                <span class="text-xs font-extrabold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Register with companions / group?</span>
                                <p class="text-[11px] text-slate-450 dark:text-slate-500">Enable this to register multiple people under a single group code.</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer select-none">
                                <input type="checkbox" id="add-reg-group-toggle" onchange="toggleAddAttendeeGroup(this)" class="sr-only peer">
                                <div class="w-11 h-6 bg-slate-200 dark:bg-slate-800 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-slate-650 peer-checked:bg-purple-600"></div>
                            </label>
                        </div>

                        <!-- Repeater Container -->
                        <div id="add-reg-companions-wrapper" class="hidden space-y-4 pt-2">
                            <div id="add-reg-companions-container" class="space-y-4">
                                <!-- Populated dynamically by javascript -->
                            </div>
                            
                            <button type="button" onclick="addModalCompanionField()"
                                class="w-full flex items-center justify-center gap-2 py-3 border-2 border-dashed border-slate-200 dark:border-slate-800 hover:border-purple-500/80 dark:hover:border-purple-400/80 text-slate-550 dark:text-slate-400 hover:text-purple-600 dark:hover:text-purple-400 font-extrabold text-xs rounded-2xl transition duration-150 active:scale-[0.995]">
                                <i class="fa-solid fa-plus text-xs"></i>
                                Add Companion Form
                            </button>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Premium Modal Footer Actions -->
            <div class="px-6 py-5 sm:px-8 border-t border-slate-100 dark:border-slate-800/60 bg-slate-50/50 dark:bg-slate-950/20 shrink-0 flex items-center justify-end gap-3 rounded-b-[2.5rem]">
                <button type="button" onclick="closeAddAttendeeModal()"
                    class="px-5 py-3 rounded-xl border border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-450 hover:bg-slate-50 dark:hover:bg-slate-800 font-bold text-xs transition duration-150 active:scale-95 focus:outline-none">
                    Cancel
                </button>
                <button type="submit"
                    class="px-6 py-3 rounded-xl bg-purple-600 hover:bg-purple-700 dark:bg-purple-500 dark:hover:bg-purple-650 text-white font-extrabold text-xs transition-all duration-150 active:scale-95 shadow-md shadow-purple-500/10 flex items-center gap-1.5 focus:outline-none">
                    <i class="fa-solid fa-circle-check text-sm text-purple-200"></i>
                    Save Attendee
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Group / Companion Registration Repeater Engine for Admin Modal
    let modalCompanionIndex = 0;

    function toggleAddAttendeeGroup(checkbox) {
        const wrapper = document.getElementById('add-reg-companions-wrapper');
        if (!wrapper) return;

        if (checkbox.checked) {
            wrapper.classList.remove('hidden');
            if (modalCompanionIndex === 0) {
                addModalCompanionField();
            }
        } else {
            wrapper.classList.add('hidden');
            document.getElementById('add-reg-companions-container').innerHTML = '';
            modalCompanionIndex = 0;
        }
    }

    function addModalCompanionField() {
        const container = document.getElementById('add-reg-companions-container');
        if (!container) return;

        const card = document.createElement('div');
        card.className = 'bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 p-5 rounded-2xl space-y-4 shadow-sm relative animate-fade-in text-left';
        card.id = `add-companion-card-${modalCompanionIndex}`;

        card.innerHTML = `
            <div class="flex items-center justify-between border-b border-slate-50 dark:border-slate-850 pb-2.5">
                <span class="text-[10px] font-extrabold text-purple-650 dark:text-purple-400 uppercase tracking-widest">Companion #${modalCompanionIndex + 1}</span>
                <button type="button" onclick="removeModalCompanionField(${modalCompanionIndex})" class="text-rose-500 hover:text-rose-650 hover:bg-rose-50 dark:hover:bg-rose-950/25 p-1 rounded-lg transition">
                    <i class="fa-solid fa-trash-can text-sm"></i>
                </button>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                <!-- Companion Name -->
                <div class="space-y-1.5">
                    <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Full Name <span class="text-rose-500">*</span></label>
                    <input type="text" name="companions[${modalCompanionIndex}][name]" required placeholder="Full Name"
                        class="w-full rounded-xl border border-slate-200 dark:border-slate-800/80 py-2.5 px-3.5 text-slate-850 dark:text-slate-200 text-xs focus:border-purple-500 focus:outline-none bg-slate-50/50 dark:bg-slate-950 focus:bg-white transition-all duration-300">
                </div>

                <!-- Companion Email -->
                <div class="space-y-1.5">
                    <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Email Address</label>
                    <input type="email" name="companions[${modalCompanionIndex}][email]" placeholder="Leave blank if children/elderly"
                        class="w-full rounded-xl border border-slate-200 dark:border-slate-800/80 py-2.5 px-3.5 text-slate-850 dark:text-slate-200 text-xs focus:border-purple-500 focus:outline-none bg-slate-50/50 dark:bg-slate-950 focus:bg-white transition-all duration-300">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                <!-- Companion Gender -->
                <div class="space-y-1.5">
                    <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Gender Identity <span class="text-rose-500">*</span></label>
                    <select name="companions[${modalCompanionIndex}][gender]" required
                        class="w-full rounded-xl border border-slate-200 dark:border-slate-800/80 py-2.5 px-3.5 text-slate-850 dark:text-slate-200 text-xs focus:border-purple-500 focus:outline-none bg-slate-50/50 dark:bg-slate-950 focus:bg-white transition-all duration-300 cursor-pointer">
                        <option value="" disabled selected>Select gender...</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="LGBTQ+">LGBTQ+</option>
                        <option value="Others">Others</option>
                    </select>
                </div>

                <!-- Companion Birthday -->
                <div class="space-y-1.5">
                    <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Birth Date <span class="text-rose-500">*</span></label>
                    <input type="date" name="companions[${modalCompanionIndex}][birthday]" required max="${new Date().toISOString().split('T')[0]}"
                        class="w-full rounded-xl border border-slate-200 dark:border-slate-800/80 py-2.5 px-3.5 text-slate-850 dark:text-slate-200 text-xs focus:border-purple-500 focus:outline-none bg-slate-50/50 dark:bg-slate-950 focus:bg-white transition-all duration-300 cursor-pointer">
                </div>
            </div>

            <!-- Companion Division -->
            <div class="space-y-1.5">
                <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Division Placement (Optional)</label>
                <input type="text" name="companions[${modalCompanionIndex}][division]" placeholder="e.g. Youth Sector / OPEC Visayas (Optional)"
                    class="w-full rounded-xl border border-slate-200 dark:border-slate-800/80 py-2.5 px-3.5 text-slate-850 dark:text-slate-200 text-xs focus:border-purple-500 focus:outline-none bg-slate-50/50 dark:bg-slate-950 focus:bg-white transition-all duration-300">
            </div>
        `;

        container.appendChild(card);
        modalCompanionIndex++;
    }

    function removeModalCompanionField(index) {
        const card = document.getElementById(`add-companion-card-${index}`);
        if (card) {
            card.remove();
            reindexModalCompanions();
        }
    }

    function reindexModalCompanions() {
        const container = document.getElementById('add-reg-companions-container');
        if (!container) return;

        const cards = container.children;
        modalCompanionIndex = 0;

        Array.from(cards).forEach((card, idx) => {
            card.id = `add-companion-card-${idx}`;
            
            const title = card.querySelector('span');
            if (title) title.innerText = `Companion #${idx + 1}`;

            const deleteBtn = card.querySelector('button[onclick^="removeModalCompanionField"]');
            if (deleteBtn) deleteBtn.setAttribute('onclick', `removeModalCompanionField(${idx})`);

            // Update input names for correct indexing on array submit
            const inputs = card.querySelectorAll('input, select');
            inputs.forEach(input => {
                const nameAttr = input.getAttribute('name');
                if (nameAttr) {
                    const newName = nameAttr.replace(/companions\[\d+\]/, `companions[${idx}]`);
                    input.setAttribute('name', newName);
                }
            });

            modalCompanionIndex++;
        });

        if (modalCompanionIndex === 0) {
            const toggle = document.getElementById('add-reg-group-toggle');
            if (toggle) toggle.checked = false;
            toggleAddAttendeeGroup(toggle);
        }
    }

    // Form Submit Loader Effect
    document.addEventListener('DOMContentLoaded', () => {
        const addForm = document.getElementById('add-attendee-form');
        if (addForm) {
            addForm.addEventListener('submit', function() {
                const submitBtn = addForm.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = `
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline-block" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Saving Attendee...
                    `;
                }
            });
        }
    });
</script>