<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventRegistration;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display the public shareable event page.
     */
    public function showPublic(Event $event)
    {
        $event->load('committee');

        return view('committees.events-app.events-components.public', compact('event'));
    }

    /**
     * Handle public RSVP registration with spam and duplicate prevention.
     */
    public function registerPublic(Request $request, Event $event)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'gender' => 'required|string|max:255',
            'birthday' => 'required|date|before_or_equal:today',
            'division' => 'required|string|max:255',
        ];

        if ($event->allow_group_registration) {
            $rules['companions'] = 'nullable|array';
            $rules['companions.*.name'] = 'required|string|max:255';
            $rules['companions.*.email'] = 'nullable|email|max:255';
            $rules['companions.*.gender'] = 'required|string|max:255';
            $rules['companions.*.birthday'] = 'required|date|before_or_equal:today';
            $rules['companions.*.division'] = 'nullable|string|max:255';
        }

        // Support both old and new formats
        $fieldsConfig = $event->registration_fields ?? [];
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
            foreach ($fieldsConfig as $field) {
                $fieldId = $field['id'] ?? null;
                if ($fieldId) {
                    $requirement = (!empty($field['required'])) ? 'required' : 'nullable';
                    $rules["custom_fields.{$fieldId}"] = "{$requirement}|string|max:255";
                }
            }
        } else {
            // Old fallback format compatibility - map to custom_fields.field
            foreach (['phone', 'job_title', 'company', 'birthday'] as $field) {
                if (isset($fieldsConfig[$field]['enabled']) && $fieldsConfig[$field]['enabled']) {
                    $requirement = (!empty($fieldsConfig[$field]['required'])) ? 'required' : 'nullable';
                    $rules["custom_fields.{$field}"] = "{$requirement}|string|max:255";
                }
            }
        }

        $validated = $request->validate($rules);

        $email = strtolower($validated['email']);
        $companions = $request->input('companions', []);

        // Prevent registration if the deadline has passed
        if ($event->registration_deadline && now()->isAfter($event->registration_deadline)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Registration Closed: The cutoff deadline for this assembly has passed.');
        }

        // Prevent registration if the event is already fully booked
        if ($event->max_participants !== null) {
            $approvedCount = $event->registrations->where('status', 'approved')->count();
            if ($approvedCount >= $event->max_participants) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Registration Closed: This assembly has reached its maximum seat capacity limit.');
            }
        }

        // Prevent dual-registration / duplicate spamming
        $emailsToValidate = [$email];
        if ($event->allow_group_registration && !empty($companions)) {
            foreach ($companions as $companion) {
                if (isset($companion['email'])) {
                    $emailsToValidate[] = strtolower($companion['email']);
                }
            }
        }

        $alreadyRegisteredEmails = EventRegistration::where('event_id', $event->id)
            ->whereIn('email', $emailsToValidate)
            ->pluck('email')
            ->toArray();

        if (!empty($alreadyRegisteredEmails)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Registration Aborted: The following email address(es) are already registered for this event: ' . implode(', ', $alreadyRegisteredEmails));
        }

        // Gather serialized custom fields
        $customFields = [];
        if ($isNewFormat) {
            // New dynamic fields format
            foreach ($fieldsConfig as $field) {
                $fieldId = $field['id'] ?? null;
                $label = $field['label'] ?? '';
                if ($fieldId && $label) {
                    $val = data_get($validated, "custom_fields.{$fieldId}") ?? $request->input("custom_fields.{$fieldId}");
                    if ($val !== null) {
                        $customFields[$label] = $val;
                    }
                }
            }
        } else {
            // Old format
            foreach (['phone', 'job_title', 'company', 'birthday'] as $field) {
                $labelMap = [
                    'phone' => 'Phone Number',
                    'job_title' => 'Corporate Title / Position',
                    'company' => 'Company / Department',
                    'birthday' => 'Birth Date'
                ];
                $label = $labelMap[$field] ?? ucwords(str_replace('_', ' ', $field));
                
                $val = data_get($validated, "custom_fields.{$field}") ?? $request->input("custom_fields.{$field}");
                if ($val !== null) {
                    $customFields[$label] = $val;
                }
            }
        }

        // Save transactions
        \Illuminate\Support\Facades\DB::transaction(function() use ($event, $validated, $email, $companions, $customFields) {
            $groupCode = null;
            $isGroup = $event->allow_group_registration && !empty($companions);

            if ($isGroup) {
                $groupCode = 'GRP-' . substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 4);
            }

            // Create primary registration
            $primary = EventRegistration::create([
                'event_id' => $event->id,
                'name' => $validated['name'],
                'email' => $email,
                'gender' => $validated['gender'] ?? null,
                'birthday' => $validated['birthday'] ?? null,
                'division' => $validated['division'] ?? null,
                'status' => 'pending',
                'group_code' => $groupCode,
                'is_group_primary' => $isGroup,
                'custom_fields' => !empty($customFields) ? $customFields : null,
            ]);

            // Send pending review email to primary attendee
            try {
                \Illuminate\Support\Facades\Mail::to($primary->email)->send(new \App\Mail\EventPending($primary));
            } catch (\Exception $e) {
                \Log::error('Event pending mail dispatch failed for primary: '.$e->getMessage());
            }

            // Create companions if any
            if ($isGroup) {
                $loopIndex = 0;
                foreach ($companions as $companion) {
                    $companionEmail = !empty($companion['email'])
                        ? strtolower($companion['email'])
                        : 'grp-' . strtolower($groupCode) . '-' . $loopIndex++ . '@sako-companion.local';

                    $companionReg = EventRegistration::create([
                        'event_id' => $event->id,
                        'name' => $companion['name'],
                        'email' => $companionEmail,
                        'gender' => $companion['gender'] ?? null,
                        'birthday' => $companion['birthday'] ?? null,
                        'division' => $companion['division'] ?? null,
                        'status' => 'pending',
                        'group_code' => $groupCode,
                        'is_group_primary' => false,
                        'custom_fields' => null, // Companions do not fill out separate custom questions
                    ]);

                    // Send pending review email to companion: route to primary guardian if virtual
                    $targetEmail = $companionReg->email;
                    if (str_contains($targetEmail, '@sako-companion.local')) {
                        $targetEmail = $primary->email;
                    }

                    try {
                        \Illuminate\Support\Facades\Mail::to($targetEmail)->send(new \App\Mail\EventPending($companionReg));
                    } catch (\Exception $e) {
                        \Log::error('Event pending mail dispatch failed for companion: '.$e->getMessage());
                    }
                }
            }
        });

        return redirect()->back()->with('success', 'Registration Submitted! The host will review your request and send an email confirmation.');
    }

    /**
     * Display the unified committee events page.
     */
    public function index(Request $request)
    {
        $committees = \App\Models\Committee::all();

        // If no committee_id is provided, redirect to the user's committee events if they belong to one
        if (!$request->has('committee_id')) {
            $user = auth()->user();
            if ($user && $user->title) {
                $userCommittee = $committees->firstWhere('name', $user->title->group);
                if ($userCommittee) {
                    return redirect()->route('committees.events.index', ['committee_id' => $userCommittee->id]);
                }
            }
        }

        $selectedCommitteeId = $request->input('committee_id', $committees->first()?->id);
        
        $committee = $committees->firstWhere('id', $selectedCommitteeId);
        $events = $committee ? Event::where('committee_id', $committee->id)->latest()->with('registrations')->get() : collect();

        return view('committees.events-app.events', compact('committees', 'committee', 'events'));
    }

    /**
     * Display the create new event page.
     */
    public function create(Request $request)
    {
        $committees = \App\Models\Committee::all();
        $selectedCommitteeId = $request->input('committee_id');
        $committee = $committees->firstWhere('id', $selectedCommitteeId);

        if (!$committee) {
            return redirect()->route('committees.events.index')->with('error', 'Please select a valid committee before scheduling an event.');
        }

        return view('committees.events-app.create', compact('committee'));
    }

    /**
     * Display upcoming events and portals for a specific committee.
     */
    public function committeeEvents(\App\Models\Committee $committee)
    {
        $committees = \App\Models\Committee::all();
        $events = Event::where('committee_id', $committee->id)->latest()->with('registrations')->get();

        return view('committees.events-app.events', compact('committees', 'committee', 'events'));
    }

    /**
     * Display the dedicated event details and management dashboard.
     */
    public function manage(Event $event)
    {
        $event->load(['committee', 'registrations']);
        
        return view('committees.events-app.manage_events.index', compact('event'));
    }

    /**
     * Display the personalized ticket page (Luma-style ticket pass).
     */
    public function showTicket(Request $request, EventRegistration $registration)
    {
        if (! $request->hasValidSignature()) {
            abort(401, 'Unauthorized: Secure ticket link has expired or is invalid.');
        }

        $event = $registration->event->load('committee');

        return view('committees.events-app.events-components.manage_ticket', compact('registration', 'event'));
    }

    /**
     * Cancel an RSVP registration from the signed ticket portal.
     */
    public function cancelRegistration(Request $request, EventRegistration $registration)
    {
        if (! $request->hasValidSignature()) {
            abort(401, 'Unauthorized: Secure action signature is invalid.');
        }

        $event = $registration->event;
        $deadline = $event->registration_deadline ?? $event->event_date;

        if (now()->isAfter($deadline)) {
            return redirect()->back()->with('error', 'Cancellation Closed: RSVPs for this assembly are locked and can no longer be modified.');
        }

        $registration->delete();

        return redirect()->route('events.public_show', $event)
            ->with('success', 'Your RSVP has been successfully cancelled and your seat reservation has been released.');
    }

    /**
     * Display the public self-check-in screen for venue confirmation.
     */
    public function showCheckIn(Event $event)
    {
        if ($event->registration_type !== 'venue_confirmation') {
            return redirect()->route('events.public_show', $event);
        }

        if ($event->isEnded()) {
            return redirect()->route('events.public_show', $event)
                ->with('error', 'Check-In Closed: This assembly has already ended.');
        }

        if (!$event->canCheckIn()) {
            $daysLeft = $event->daysUntilStart();
            $timeMsg = $event->startsTomorrow() ? 'tomorrow' : "in {$daysLeft} days";
            return redirect()->route('events.public_show', $event)
                ->with('error', "Check-In Closed: This assembly is scheduled {$timeMsg}. Check-in will open on the day of the event.");
        }

        return view('committees.events-app.events-components.check_in', compact('event'));
    }

    /**
     * Submit check-in verification for an event.
     */
    public function submitCheckIn(Request $request, Event $event)
    {
        if ($event->registration_type !== 'venue_confirmation') {
            return redirect()->route('events.public_show', $event);
        }

        if (!$event->canCheckIn()) {
            return redirect()->back()
                ->with('error', 'Check-In Closed: Self check-in is not active for this assembly at this time.');
        }

        $validated = $request->validate([
            'email' => 'required|email|max:255',
            'ticket_code' => 'required|string|max:10',
        ]);

        $email = strtolower($validated['email']);
        $code = strtoupper(trim($validated['ticket_code']));

        $registration = EventRegistration::where('event_id', $event->id)
            ->where('email', $email)
            ->where('ticket_code', $code)
            ->first();

        if (!$registration) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Invalid Details: We could not find a registered guest matching that email and ticket code.');
        }

        if ($registration->status !== 'approved') {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Check-In Blocked: Your registration status is current "' . $registration->status . '". Contact host.');
        }

        if ($registration->attended) {
            return redirect()->route('events.check_in_success', ['event' => $event->id, 'already' => true])
                ->with('attendee_name', $registration->name);
        }

        $registration->update([
            'attended' => true,
            'attended_at' => now(),
        ]);

        return redirect()->route('events.check_in_success', ['event' => $event->id])
            ->with('attendee_name', $registration->name);
    }

    /**
     * Directly check-in an attendee via a secure signed link or scanned QR code.
     */
    public function directCheckIn(Request $request, EventRegistration $registration)
    {
        if (! $request->hasValidSignature()) {
            abort(401, 'Unauthorized: Secure check-in link has expired or is invalid.');
        }

        $event = $registration->event;

        if ($event->isEnded()) {
            return redirect()->back()->with('error', 'Check-In Closed: This assembly has already ended.');
        }

        if (!$event->canCheckIn()) {
            $daysLeft = $event->daysUntilStart();
            $timeMsg = $event->startsTomorrow() ? 'tomorrow' : "in {$daysLeft} days";
            return redirect()->back()->with('error', "Check-In Closed: Self check-in is not active yet. This assembly is scheduled {$timeMsg}. Check-in will open on the day of the event.");
        }

        // If already checked in, redirect with a message
        if ($registration->attended) {
            return redirect()->route('events.check_in_success', ['event' => $event->id, 'already' => true])
                ->with('attendee_name', $registration->name);
        }

        // Perform direct check-in, auto-approve if they were pending
        $registration->update([
            'status' => 'approved',
            'attended' => true,
            'attended_at' => now(),
        ]);

        return redirect()->route('events.check_in_success', ['event' => $event->id])
            ->with('attendee_name', $registration->name);
    }

    /**
     * Show check-in success page.
     */
    public function checkInSuccess(Event $event)
    {
        return view('committees.events-app.events-components.check_in_success', compact('event'));
    }

    /**
     * Request access link dynamically if email was lost.
     */
    public function requestAccessLink(Request $request, Event $event)
    {
        $validated = $request->validate([
            'email' => 'required|email|max:255',
        ]);

        $email = strtolower($validated['email']);

        $registration = EventRegistration::where('event_id', $event->id)
            ->where('email', $email)
            ->first();

        if (!$registration) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'We could not find a registration under that email for this event.');
        }

        if ($registration->status !== 'approved') {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Your registration is currently ' . $registration->status . ' and does not have an active pass.');
        }

        try {
            \Illuminate\Support\Facades\Mail::to($registration->email)->send(new \App\Mail\EventApproved($registration));
        } catch (\Exception $e) {
            \Log::error('Access link email dispatch failed: '.$e->getMessage());
            return redirect()->back()->with('error', 'Failed to dispatch email. Check SMTP settings.');
        }

        return redirect()->back()->with('success', 'A fresh check-in pass has been dispatched to your email.');
    }

    /**
     * Display the public feedback survey.
     */
    public function showSurvey(Request $request, EventRegistration $registration)
    {
        if (! $request->hasValidSignature()) {
            abort(401, 'Unauthorized: Secure survey link has expired or is invalid.');
        }

        $event = $registration->event->load('committee');

        if (!$event->survey_enabled || empty($event->survey_questions)) {
            abort(404, 'Not Found: No active post-event survey exists for this event.');
        }

        // If survey is already answered, show success screen with a flag
        if (!empty($registration->survey_responses)) {
            $already_submitted = true;
            return view('committees.events-app.events-components.survey_success', compact('registration', 'event', 'already_submitted'));
        }

        return view('committees.events-app.events-components.survey', compact('registration', 'event'));
    }

    /**
     * Submit feedback survey answers.
     */
    public function submitSurvey(Request $request, EventRegistration $registration)
    {
        if (! $request->hasValidSignature()) {
            abort(401, 'Unauthorized: Secure survey link has expired or is invalid.');
        }

        $event = $registration->event;
        $questions = $event->survey_questions ?? [];

        // Validate answers based on configuration
        $rules = [];
        foreach ($questions as $question) {
            $fieldId = $question['id'] ?? null;
            if ($fieldId) {
                $requirement = (!empty($question['required'])) ? 'required' : 'nullable';
                $rules["answers.{$fieldId}"] = "{$requirement}|string|max:1000";
            }
        }

        $validated = $request->validate($rules);

        // Serialize answers (question label -> answer text)
        $surveyResponses = [];
        foreach ($questions as $question) {
            $fieldId = $question['id'] ?? null;
            $label = $question['label'] ?? '';
            if ($fieldId && $label) {
                $answer = data_get($validated, "answers.{$fieldId}");
                $surveyResponses[$label] = $answer;
            }
        }

        $registration->update([
            'survey_responses' => $surveyResponses,
        ]);

        return view('committees.events-app.events-components.survey_success', compact('registration', 'event'));
    }

    /**
     * Export the event summary metrics as a print-ready PDF using DomPDF.
     */
    public function exportSummaryPDF(Event $event)
    {
        $registrations = $event->registrations()->latest()->get();
        $total = $registrations->count();
        
        $approved = $registrations->where('status', 'approved')->count();
        $pending = $registrations->where('status', 'pending')->count();
        $declined = $registrations->where('status', 'declined')->count();
        $attended = $registrations->where('status', 'approved')->where('attended', true)->count();
        $absent = $registrations->where('status', 'approved')->where('attended', false)->count();

        // Gender demographics
        $males = $registrations->where('gender', 'Male')->count();
        $females = $registrations->where('gender', 'Female')->count();
        $lgbtq = $registrations->where('gender', 'LGBTQ+')->count();
        $unspecified = $registrations->whereNull('gender')->count();
        $others = $total - ($males + $females + $lgbtq + $unspecified);

        // Load premium print-ready PDF template with event summary data
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView(
            'committees.events-app.manage_events.pdf_formats.summary_pdf', 
            compact('event', 'registrations', 'total', 'approved', 'pending', 'declined', 'attended', 'absent', 'males', 'females', 'lgbtq', 'others', 'unspecified')
        );

        $filename = "event_summary_" . str_replace(' ', '_', strtolower($event->title)) . ".pdf";
        return $pdf->stream($filename);
    }
}
