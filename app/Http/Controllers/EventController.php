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
        ];

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
        $alreadyRegistered = EventRegistration::where('event_id', $event->id)
            ->where('email', $email)
            ->exists();

        if ($alreadyRegistered) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'You have already registered for this event. Duplicate registrations are not allowed.');
        }

        $status = 'pending';
        $ticket_code = null;

        if ($event->registration_type === 'venue_confirmation') {
            $status = 'approved';
            do {
                $ticket_code = 'AC-' . substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 4);
            } while (EventRegistration::where('event_id', $event->id)->where('ticket_code', $ticket_code)->exists());
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

        // Create registration
        $registration = EventRegistration::create([
            'event_id' => $event->id,
            'name' => $validated['name'],
            'email' => $email,
            'gender' => $validated['gender'] ?? null,
            'status' => $status,
            'ticket_code' => $ticket_code,
            'custom_fields' => !empty($customFields) ? $customFields : null,
        ]);

        if ($status === 'approved') {
            try {
                \Illuminate\Support\Facades\Mail::to($registration->email)->send(new \App\Mail\EventApproved($registration));
            } catch (\Exception $e) {
                \Log::error('Event approved mail dispatch failed: '.$e->getMessage());
            }

            return redirect()->back()->with('success', 'Registration Completed Successfully! An entry pass and confirmation details have been dispatched to your email.');
        }

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
        
        return view('committees.events-app.manage_event', compact('event'));
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
            return redirect()->route('events.check_in_success', ['event' => $event->id, 'already' => true]);
        }

        $registration->update([
            'attended' => true,
            'attended_at' => now(),
        ]);

        return redirect()->route('events.check_in_success', ['event' => $event->id]);
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
}
