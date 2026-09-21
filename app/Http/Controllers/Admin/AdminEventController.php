<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\EventApproved;
use App\Models\Event;
use App\Models\EventRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

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
            'terms_and_policy' => 'required|string',
            'event_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:event_date',
            'location' => 'required|string|max:255',
            'location_type' => 'required|string|in:physical,virtual',
            'arrival_instructions' => 'nullable|string',
            'image' => 'nullable|url|max:2048',
            'cover_file' => 'nullable|image|max:4096',
            'max_participants' => 'nullable|integer|min:1',
            'registration_type' => 'nullable|string|in:admin_approval,venue_confirmation',
            'registration_deadline' => 'nullable|date',
            'registration_fields' => 'nullable|array',
            'committee_id' => 'nullable|exists:committees,id',
            'allow_group_registration' => 'nullable|boolean',
        ], [
            'image.url' => 'Please provide a valid image URL (starting with http/https).',
            'max_participants.min' => 'The capacity limit must be at least 1 seat.',
        ]);

        $validated['registration_type'] = 'admin_approval';
        $validated['allow_group_registration'] = $request->boolean('allow_group_registration');

        if ($request->hasFile('cover_file')) {
            $path = $request->file('cover_file')->store('events', 's3');
            $validated['image'] = Storage::disk('s3')->url($path);
        }

        $event = Event::create($validated);

        return redirect()->route('committees.events.manage', $event)->with('status', 'Event scheduled successfully and is now open for registrations.');
    }

    /**
     * Delete the specified event.
     */
    public function destroy(Event $event)
    {
        // Delete cover image from S3 if it exists in our bucket
        $bucketName = config('filesystems.disks.s3.bucket');
        if ($event->image && !empty($bucketName) && str_contains($event->image, $bucketName)) {
            $parsedUrl = parse_url($event->image);
            $path = ltrim($parsedUrl['path'] ?? '', '/');
            if (!empty($path) && Storage::disk('s3')->exists($path)) {
                Storage::disk('s3')->delete($path);
            }
        }

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
            'terms_and_policy' => 'required|string',
            'event_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:event_date',
            'location' => 'required|string|max:255',
            'location_type' => 'required|string|in:physical,virtual',
            'arrival_instructions' => 'nullable|string',
            'image' => 'nullable|url|max:2048',
            'cover_file' => 'nullable|image|max:4096',
            'max_participants' => 'nullable|integer|min:1',
            'registration_type' => 'nullable|string|in:admin_approval,venue_confirmation',
            'registration_deadline' => 'nullable|date',
            'registration_fields' => 'nullable|array',
            'allow_group_registration' => 'nullable|boolean',
        ], [
            'image.url' => 'Please provide a valid image URL (starting with http/https).',
            'max_participants.min' => 'The capacity limit must be at least 1 seat.',
        ]);

        $validated['registration_type'] = 'admin_approval';
        $validated['allow_group_registration'] = $request->boolean('allow_group_registration');

        $imageUrl = $validated['image'] ?? $event->image;

        if ($request->hasFile('cover_file')) {
            // Delete old S3 cover if it exists in S3
            $bucketName = config('filesystems.disks.s3.bucket');
            if ($event->image && !empty($bucketName) && str_contains($event->image, $bucketName)) {
                $parsedUrl = parse_url($event->image);
                $oldPath = ltrim($parsedUrl['path'] ?? '', '/');
                if (!empty($oldPath) && Storage::disk('s3')->exists($oldPath)) {
                    Storage::disk('s3')->delete($oldPath);
                }
            }
            
            $path = $request->file('cover_file')->store('events', 's3');
            $imageUrl = Storage::disk('s3')->url($path);
        }

        $validated['image'] = $imageUrl;

        $event->update($validated);

        return redirect()->back()->with('status', 'Event details updated successfully.');
    }

    /**
     * Approve the attendee registration and trigger invitation email.
     */
    public function approveRegistration(EventRegistration $registration)
    {
        $updateData = ['status' => 'approved'];

        // Generate secure unique ticket code if empty (Requires Secretariat Approval flow)
        if (empty($registration->ticket_code)) {
            do {
                $ticket_code = 'AC-' . substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 4);
            } while (EventRegistration::where('event_id', $registration->event_id)->where('ticket_code', $ticket_code)->exists());
            
            $updateData['ticket_code'] = $ticket_code;
        }

        $registration->update($updateData);

        // Load relations for template rendering in mail
        $registration->load('event.committee');

        // Dynamically dispatch the premium HTML confirmation mail: route to primary guardian if virtual email
        $targetEmail = $registration->email;
        if (str_contains($targetEmail, '@sako-companion.local')) {
            $primary = EventRegistration::where('group_code', $registration->group_code)
                ->where('is_group_primary', true)
                ->first();
            if ($primary) {
                $targetEmail = $primary->email;
            }
        }

        try {
            Mail::to($targetEmail)->send(new EventApproved($registration));
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
                'status' => 'approved',
                'ticket_code' => $registration->ticket_code
            ]);
        }

        return redirect()->back()->with('status', 'Registration Approved successfully! Invitation confirmation email has been dispatched.');
    }

    /**
     * Decline/Reject the attendee registration request.
     */
    public function declineRegistration(EventRegistration $registration)
    {
        $rejectionReason = request()->input('rejection_reason');

        $registration->update([
            'status' => 'declined',
            'rejection_reason' => $rejectionReason
        ]);

        // Load relations for template rendering in mail
        $registration->load('event.committee');

        // Dynamically dispatch the premium HTML decline mail: route to primary guardian if virtual email
        $targetEmail = $registration->email;
        if (str_contains($targetEmail, '@sako-companion.local')) {
            $primary = EventRegistration::where('group_code', $registration->group_code)
                ->where('is_group_primary', true)
                ->first();
            if ($primary) {
                $targetEmail = $primary->email;
            }
        }

        try {
            Mail::to($targetEmail)->send(new \App\Mail\EventDeclined($registration));
        } catch (\Exception $e) {
            \Log::error('Event declined mail dispatch failed: '.$e->getMessage());
        }

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
        $ticketCodes = [];

        foreach ($ids as $id) {
            $registration = EventRegistration::find($id);
            if ($registration && $registration->status !== 'approved') {
                $updateData = ['status' => 'approved'];

                // Generate secure unique ticket code if empty (Requires Secretariat Approval flow)
                if (empty($registration->ticket_code)) {
                    do {
                        $ticket_code = 'AC-' . substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 4);
                    } while (EventRegistration::where('event_id', $registration->event_id)->where('ticket_code', $ticket_code)->exists());
                    
                    $updateData['ticket_code'] = $ticket_code;
                }

                $registration->update($updateData);
                $approvedCount++;
                $ticketCodes[$id] = $registration->ticket_code;

                // Load relations for template rendering in mail
                $registration->load('event.committee');

                // Route bulk approval email to primary guardian if companion has virtual email address
                $targetEmail = $registration->email;
                if (str_contains($targetEmail, '@sako-companion.local')) {
                    $primary = EventRegistration::where('group_code', $registration->group_code)
                        ->where('is_group_primary', true)
                        ->first();
                    if ($primary) {
                        $targetEmail = $primary->email;
                    }
                }

                try {
                    Mail::to($targetEmail)->send(new EventApproved($registration));
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
                'ticket_codes' => $ticketCodes
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
            'rejection_reason' => 'nullable|string',
        ]);

        $ids = $request->input('ids');
        $rejectionReason = $request->input('rejection_reason');
        $declinedCount = 0;
        $failedEmails = 0;

        foreach ($ids as $id) {
            $registration = EventRegistration::find($id);
            if ($registration && $registration->status !== 'declined') {
                $registration->update([
                    'status' => 'declined',
                    'rejection_reason' => $rejectionReason,
                ]);
                $declinedCount++;

                // Load relations for template rendering in mail
                $registration->load('event.committee');

                // Route bulk decline email to primary guardian if companion has virtual email address
                $targetEmail = $registration->email;
                if (str_contains($targetEmail, '@sako-companion.local')) {
                    $primary = EventRegistration::where('group_code', $registration->group_code)
                        ->where('is_group_primary', true)
                        ->first();
                    if ($primary) {
                        $targetEmail = $primary->email;
                    }
                }

                try {
                    Mail::to($targetEmail)->send(new \App\Mail\EventDeclined($registration));
                } catch (\Exception $e) {
                    \Log::error('Event declined mail dispatch failed for bulk: '.$e->getMessage());
                    $failedEmails++;
                }
            }
        }

        $message = "Successfully declined {$declinedCount} registration requests.";
        if ($failedEmails > 0) {
            $message .= " However, {$failedEmails} decline notification emails failed to dispatch.";
        }

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

    /**
     * Update the event's post-event survey configuration.
     */
    public function updateSurvey(Request $request, Event $event)
    {
        $validated = $request->validate([
            'survey_enabled' => 'nullable|boolean',
            'survey_questions' => 'nullable|array',
        ]);

        $event->update([
            'survey_enabled' => $request->boolean('survey_enabled'),
            'survey_questions' => $validated['survey_questions'] ?? [],
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Post-event survey configuration updated successfully.',
                'survey_enabled' => $event->survey_enabled,
                'survey_questions' => $event->survey_questions ?? [],
            ]);
        }

        return redirect()->back()->with('status', 'Post-event survey configuration updated successfully.');
    }

    /**
     * Broadcast post-event survey emails to approved attendees.
     */
    public function broadcastSurveys(Request $request, Event $event)
    {
        if (!$event->survey_enabled || empty($event->survey_questions)) {
            return redirect()->back()->with('error', 'Post-event survey must be enabled and have questions before broadcasting.');
        }

        // Get approved attendees (we can send to those whose status is 'approved')
        $attendees = $event->registrations()->where('status', 'approved')->get();

        if ($attendees->isEmpty()) {
            return redirect()->back()->with('error', 'No approved attendees found to send the survey to.');
        }

        $sentCount = 0;
        $failedCount = 0;

        foreach ($attendees as $attendee) {
            try {
                Mail::to($attendee->email)->send(new \App\Mail\EventSurvey($attendee));
                $sentCount++;
            } catch (\Exception $e) {
                \Log::error("Post-event survey email dispatch failed for {$attendee->email}: " . $e->getMessage());
                $failedCount++;
            }
        }

        $event->update(['survey_sent' => true]);

        $message = "Successfully dispatched survey emails to {$sentCount} attendees.";
        if ($failedCount > 0) {
            $message .= " However, {$failedCount} emails failed to send. Check SMTP logs.";
        }

        return redirect()->back()->with('status', $message);
    }
}
