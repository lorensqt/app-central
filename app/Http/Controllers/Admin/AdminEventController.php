<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\EventApproved;
use App\Models\Event;
use App\Models\EventRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AdminEventController extends Controller
{
    /**
     * Store a newly created event in database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'event_date' => 'required|date',
            'location' => 'required|string|max:255',
            'location_type' => 'required|string|in:physical,virtual',
            'arrival_instructions' => 'nullable|string',
            'image' => 'nullable|url|max:2048',
            'max_participants' => 'nullable|integer|min:1',
            'registration_type' => 'required|string|in:admin_approval,venue_confirmation',
            'registration_deadline' => 'nullable|date',
            'registration_fields' => 'nullable|array',
            'committee_id' => 'nullable|exists:committees,id',
        ], [
            'image.url' => 'Please provide a valid image URL (starting with http/https).',
            'max_participants.min' => 'The capacity limit must be at least 1 seat.',
        ]);

        Event::create($validated);

        return redirect()->back()->with('status', 'Event scheduled successfully and is now open for registrations.');
    }

    /**
     * Delete the specified event.
     */
    public function destroy(Event $event)
    {
        $event->delete();

        // Redirect back to committee events index instead of dynamic back if deleting from manage screen
        return redirect()->route('committees.events.index', ['committee_id' => $event->committee_id])->with('status', 'Event deleted successfully.');
    }

    /**
     * Update the specified event.
     */
    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'event_date' => 'required|date',
            'location' => 'required|string|max:255',
            'location_type' => 'required|string|in:physical,virtual',
            'arrival_instructions' => 'nullable|string',
            'image' => 'nullable|url|max:2048',
            'max_participants' => 'nullable|integer|min:1',
            'registration_type' => 'required|string|in:admin_approval,venue_confirmation',
            'registration_deadline' => 'nullable|date',
            'registration_fields' => 'nullable|array',
        ], [
            'image.url' => 'Please provide a valid image URL (starting with http/https).',
            'max_participants.min' => 'The capacity limit must be at least 1 seat.',
        ]);

        $event->update($validated);

        return redirect()->back()->with('status', 'Event details updated successfully.');
    }

    /**
     * Approve the attendee registration and trigger invitation email.
     */
    public function approveRegistration(EventRegistration $registration)
    {
        $registration->update(['status' => 'approved']);

        // Load relations for template rendering in mail
        $registration->load('event.committee');

        // Dynamically dispatch the premium HTML confirmation mail
        try {
            Mail::to($registration->email)->send(new EventApproved($registration));
        } catch (\Exception $e) {
            // Log mail failure but allow status change to complete, notifying the user
            \Log::error('Event approved mail dispatch failed: '.$e->getMessage());

            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Registration Approved, but confirmation email failed to dispatch. Check SMTP config.',
                    'status' => 'approved'
                ]);
            }

            return redirect()->back()->with('status', 'Registration Approved, but confirmation email failed to dispatch. Check SMTP config.');
        }

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Registration Approved successfully! Invitation confirmation email has been dispatched.',
                'status' => 'approved'
            ]);
        }

        return redirect()->back()->with('status', 'Registration Approved successfully! Invitation confirmation email has been dispatched.');
    }

    /**
     * Decline/Reject the attendee registration request.
     */
    public function declineRegistration(EventRegistration $registration)
    {
        $registration->update(['status' => 'declined']);

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Registration request has been declined.',
                'status' => 'declined'
            ]);
        }

        return redirect()->back()->with('status', 'Registration request has been declined.');
    }

    /**
     * Bulk Approve selected attendee registrations.
     */
    public function bulkApproveRegistrations(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:event_registrations,id',
        ]);

        $ids = $request->input('ids');
        $approvedCount = 0;
        $failedEmails = 0;

        foreach ($ids as $id) {
            $registration = EventRegistration::find($id);
            if ($registration && $registration->status !== 'approved') {
                $registration->update(['status' => 'approved']);
                $approvedCount++;

                // Load relations for template rendering in mail
                $registration->load('event.committee');

                try {
                    Mail::to($registration->email)->send(new EventApproved($registration));
                } catch (\Exception $e) {
                    \Log::error('Event approved mail dispatch failed for bulk: '.$e->getMessage());
                    $failedEmails++;
                }
            }
        }

        $message = "Successfully approved {$approvedCount} registrations.";
        if ($failedEmails > 0) {
            $message .= " However, {$failedEmails} confirmation emails failed to dispatch.";
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'approved_count' => $approvedCount,
            ]);
        }

        return redirect()->back()->with('status', $message);
    }

    /**
     * Bulk Decline selected attendee registrations.
     */
    public function bulkDeclineRegistrations(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:event_registrations,id',
        ]);

        $ids = $request->input('ids');
        $declinedCount = EventRegistration::whereIn('id', $ids)->update(['status' => 'declined']);

        $message = "Successfully declined {$declinedCount} registration requests.";

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'declined_count' => $declinedCount,
            ]);
        }

        return redirect()->back()->with('status', $message);
    }

    /**
     * Toggle manual check-in attendance.
     */
    public function toggleAttendance(EventRegistration $registration)
    {
        $newAttended = !$registration->attended;
        
        $registration->update([
            'attended' => $newAttended,
            'attended_at' => $newAttended ? now() : null,
        ]);

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $newAttended ? 'Guest marked as Checked-In!' : 'Guest check-in cancelled.',
                'attended' => $newAttended,
                'attended_at' => $registration->attended_at ? $registration->attended_at->format('M j, Y • g:i A') : 'N/A'
            ]);
        }

        return redirect()->back()->with('status', $newAttended ? 'Guest marked as Checked-In!' : 'Guest check-in cancelled.');
    }

    /**
     * Delete/Destroy an attendee registration request.
     */
    public function destroyRegistration(EventRegistration $registration)
    {
        $registration->delete();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Attendee registration deleted successfully.',
            ]);
        }

        return redirect()->back()->with('status', 'Attendee registration deleted successfully.');
    }

    /**
     * Bulk Delete/Destroy selected attendee registrations.
     */
    public function bulkDestroyRegistrations(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No registrations selected for deletion.',
                ], 400);
            }
            return redirect()->back()->with('error', 'No registrations selected for deletion.');
        }

        EventRegistration::whereIn('id', $ids)->delete();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Successfully deleted ' . count($ids) . ' selected registrations.',
            ]);
        }

        return redirect()->back()->with('status', 'Successfully deleted selected registrations.');
    }

    /**
     * Update the event's dynamic registration fields configuration.
     */
    public function updateFields(Request $request, Event $event)
    {
        $validated = $request->validate([
            'registration_fields' => 'nullable|array',
        ]);

        $event->update([
            'registration_fields' => $validated['registration_fields'] ?? [],
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'RSVP registration questions updated successfully.',
                'registration_fields' => $event->fresh()->registration_fields ?? [],
            ]);
        }

        return redirect()->back()->with('status', 'RSVP registration questions updated successfully.');
    }
}
