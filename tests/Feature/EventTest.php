<?php

namespace Tests\Feature;

use App\Mail\EventApproved;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\User;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class EventTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test public can view scheduled event landing page.
     */
    public function test_guest_can_view_public_event_page(): void
    {
        $event = Event::create([
            'title' => 'GAD Assembly Meeting',
            'description' => 'Discuss Gender Action plans.',
            'event_date' => now()->addDays(5),
            'location' => 'Main Conference Hall',
        ]);

        $response = $this->get("/events/{$event->id}");

        $response->assertStatus(200);
        $response->assertSee('GAD Assembly Meeting');
        $response->assertSee('Register Attendance');
    }

    /**
     * Test guest can register for an event successfully.
     */
    public function test_guest_can_rsvp_to_an_event(): void
    {
        $this->withoutMiddleware(ValidateCsrfToken::class);

        $event = Event::create([
            'title' => 'GAD Assembly Meeting',
            'description' => 'Discuss Gender Action plans.',
            'event_date' => now()->addDays(5),
            'location' => 'Main Conference Hall',
        ]);

        $response = $this->post("/events/{$event->id}/register", [
            'name' => 'John Guest',
            'email' => 'john.guest@example.com',
            'gender' => 'Male',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('event_registrations', [
            'event_id' => $event->id,
            'name' => 'John Guest',
            'email' => 'john.guest@example.com',
            'status' => 'pending',
        ]);
    }

    /**
     * Test guest cannot register twice on a specific event (anti-spam protection).
     */
    public function test_guest_cannot_rsvp_twice_to_the_same_event(): void
    {
        $this->withoutMiddleware(ValidateCsrfToken::class);

        $event = Event::create([
            'title' => 'GAD Assembly Meeting',
            'description' => 'Discuss Gender Action plans.',
            'event_date' => now()->addDays(5),
            'location' => 'Main Conference Hall',
        ]);

        // First registration
        $this->post("/events/{$event->id}/register", [
            'name' => 'John Guest',
            'email' => 'john.guest@example.com',
            'gender' => 'Male',
        ]);

        // Attempt duplicate registration
        $response = $this->post("/events/{$event->id}/register", [
            'name' => 'John Imposter',
            'email' => 'john.guest@example.com',
            'gender' => 'Male',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'You have already registered for this event. Duplicate registrations are not allowed.');

        // Verify only 1 registration is in DB
        $this->assertEquals(1, EventRegistration::where('event_id', $event->id)->count());
    }

    /**
     * Test admin can schedule events, review registrations, and approve them, dispatching confirmation email.
     */
    public function test_admin_can_approve_rsvps_triggering_email_delivery(): void
    {
        Mail::fake();
        $this->withoutMiddleware(ValidateCsrfToken::class);
        $this->withoutMiddleware(\App\Http\Middleware\EnsurePinIsConfigured::class);

        $superAdmin = User::create([
            'name' => 'John Castillo',
            'email' => 'castillojohnlaurence0@gmail.com',
        ]);

        $event = Event::create([
            'title' => 'GAD Assembly Meeting',
            'description' => 'Discuss Gender Action plans.',
            'event_date' => now()->addDays(5),
            'location' => 'Main Conference Hall',
        ]);

        $registration = EventRegistration::create([
            'event_id' => $event->id,
            'name' => 'Jane Registrant',
            'email' => 'jane.reg@example.com',
            'status' => 'pending',
        ]);

        // Admin approves RSVP
        $response = $this->actingAs($superAdmin)->post("/committees/registrations/{$registration->id}/approve");

        $response->assertRedirect();
        $response->assertSessionHas('status', 'Registration Approved successfully! Invitation confirmation email has been dispatched.');

        $this->assertDatabaseHas('event_registrations', [
            'id' => $registration->id,
            'status' => 'approved',
        ]);

        // Assert mail was sent to the applicant
        Mail::assertSent(EventApproved::class, function ($mail) use ($registration) {
            return $mail->hasTo('jane.reg@example.com') && $mail->registration->id === $registration->id;
        });
    }

    /**
     * Test admin can decline registrations.
     */
    public function test_admin_can_decline_rsvps(): void
    {
        $this->withoutMiddleware(ValidateCsrfToken::class);
        $this->withoutMiddleware(\App\Http\Middleware\EnsurePinIsConfigured::class);

        $superAdmin = User::create([
            'name' => 'John Castillo',
            'email' => 'castillojohnlaurence0@gmail.com',
        ]);

        $event = Event::create([
            'title' => 'GAD Assembly Meeting',
            'description' => 'Discuss Gender Action plans.',
            'event_date' => now()->addDays(5),
            'location' => 'Main Conference Hall',
        ]);

        $registration = EventRegistration::create([
            'event_id' => $event->id,
            'name' => 'Jane Registrant',
            'email' => 'jane.reg@example.com',
            'status' => 'pending',
        ]);

        // Admin declines RSVP
        $response = $this->actingAs($superAdmin)->post("/committees/registrations/{$registration->id}/decline");

        $response->assertRedirect();
        $response->assertSessionHas('status', 'Registration request has been declined.');

        $this->assertDatabaseHas('event_registrations', [
            'id' => $registration->id,
            'status' => 'declined',
        ]);
    }

    /**
     * Test guest is auto-approved for venue confirmation events.
     */
    public function test_guest_is_auto_approved_for_venue_confirmation_event(): void
    {
        Mail::fake();
        $this->withoutMiddleware(ValidateCsrfToken::class);

        $event = Event::create([
            'title' => 'GAD Venue Assembly',
            'description' => 'Discuss Gender Action plans at venue.',
            'event_date' => now()->addDays(5),
            'location' => 'Main Conference Hall',
            'registration_type' => 'venue_confirmation',
        ]);

        $response = $this->post("/events/{$event->id}/register", [
            'name' => 'John Guest',
            'email' => 'john.venue@example.com',
            'gender' => 'Male',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Check auto-approved status and ticket code generation
        $this->assertDatabaseHas('event_registrations', [
            'event_id' => $event->id,
            'name' => 'John Guest',
            'email' => 'john.venue@example.com',
            'status' => 'approved',
        ]);

        $reg = EventRegistration::where('event_id', $event->id)->where('email', 'john.venue@example.com')->first();
        $this->assertNotNull($reg->ticket_code);
        $this->assertStringStartsWith('AC-', $reg->ticket_code);

        // Verify direct email delivery
        Mail::assertSent(EventApproved::class, function ($mail) use ($reg) {
            return $mail->hasTo('john.venue@example.com') && $mail->registration->id === $reg->id;
        });
    }

    /**
     * Test guest cannot register after cutoff deadline.
     */
    public function test_guest_cannot_register_after_registration_deadline(): void
    {
        $this->withoutMiddleware(ValidateCsrfToken::class);

        $event = Event::create([
            'title' => 'Expired GAD Assembly',
            'description' => 'Closed meeting.',
            'event_date' => now()->addDays(5),
            'location' => 'Main Conference Hall',
            'registration_deadline' => now()->subHours(2), // cutoff passed
        ]);

        $response = $this->post("/events/{$event->id}/register", [
            'name' => 'Late Guest',
            'email' => 'late@example.com',
            'gender' => 'Female',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Registration Closed: The cutoff deadline for this assembly has passed.');

        $this->assertEquals(0, EventRegistration::where('event_id', $event->id)->count());
    }

    /**
     * Test guest can cancel RSVP via secure signed URL before deadline.
     */
    public function test_guest_can_cancel_rsvp_via_signed_url_before_deadline(): void
    {
        $this->withoutMiddleware(ValidateCsrfToken::class);

        $event = Event::create([
            'title' => 'Cancellable GAD Assembly',
            'description' => 'Cancellable meeting.',
            'event_date' => now()->addDays(2),
            'location' => 'Main Conference Hall',
            'registration_deadline' => now()->addDay(),
        ]);

        $reg = EventRegistration::create([
            'event_id' => $event->id,
            'name' => 'Remorseful Guest',
            'email' => 'remorseful@example.com',
            'gender' => 'Male',
            'status' => 'approved',
            'ticket_code' => 'AC-XXXX',
        ]);

        $signedUrl = \Illuminate\Support\Facades\URL::signedRoute('events.cancel_registration', ['registration' => $reg->id]);

        $response = $this->post($signedUrl);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Your RSVP has been successfully cancelled and your seat reservation has been released.');

        // Record should be deleted/cleared
        $this->assertDatabaseMissing('event_registrations', [
            'id' => $reg->id,
        ]);
    }

    /**
     * Test guest cannot cancel RSVP after deadline cutoff.
     */
    public function test_guest_cannot_cancel_rsvp_after_deadline(): void
    {
        $this->withoutMiddleware(ValidateCsrfToken::class);

        $event = Event::create([
            'title' => 'Locked GAD Assembly',
            'description' => 'Locked meeting.',
            'event_date' => now()->addDays(2),
            'location' => 'Main Conference Hall',
            'registration_deadline' => now()->subHours(2), // passed cutoff
        ]);

        $reg = EventRegistration::create([
            'event_id' => $event->id,
            'name' => 'Locked Guest',
            'email' => 'locked@example.com',
            'gender' => 'Male',
            'status' => 'approved',
            'ticket_code' => 'AC-XXXX',
        ]);

        $signedUrl = \Illuminate\Support\Facades\URL::signedRoute('events.cancel_registration', ['registration' => $reg->id]);

        $response = $this->post($signedUrl);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Cancellation Closed: RSVPs for this assembly are locked and can no longer be modified.');

        // Verify record is still present in DB
        $this->assertDatabaseHas('event_registrations', [
            'id' => $reg->id,
        ]);
    }

    /**
     * Test guest self check-in with correct credentials.
     */
    public function test_guest_self_check_in_at_venue_confirmation_event(): void
    {
        $this->withoutMiddleware(ValidateCsrfToken::class);

        $event = Event::create([
            'title' => 'GAD Venue Check-In Event',
            'description' => 'Discuss Gender Action plans at venue.',
            'event_date' => now()->addDays(5),
            'location' => 'Main Conference Hall',
            'registration_type' => 'venue_confirmation',
        ]);

        $reg = EventRegistration::create([
            'event_id' => $event->id,
            'name' => 'Arriving Guest',
            'email' => 'arrived@example.com',
            'gender' => 'Female',
            'status' => 'approved',
            'ticket_code' => 'AC-7890',
        ]);

        $response = $this->post("/events/{$event->id}/check-in", [
            'email' => 'arrived@example.com',
            'ticket_code' => 'AC-7890',
        ]);

        $response->assertRedirect(route('events.check_in_success', ['event' => $event->id]));

        $this->assertDatabaseHas('event_registrations', [
            'id' => $reg->id,
            'attended' => true,
        ]);
    }

    /**
     * Test guest self check-in rejects invalid credentials.
     */
    public function test_guest_self_check_in_rejects_invalid_details(): void
    {
        $this->withoutMiddleware(ValidateCsrfToken::class);

        $event = Event::create([
            'title' => 'GAD Venue Check-In Event',
            'description' => 'Discuss Gender Action plans at venue.',
            'event_date' => now()->addDays(5),
            'location' => 'Main Conference Hall',
            'registration_type' => 'venue_confirmation',
        ]);

        $reg = EventRegistration::create([
            'event_id' => $event->id,
            'name' => 'Arriving Guest',
            'email' => 'arrived@example.com',
            'gender' => 'Female',
            'status' => 'approved',
            'ticket_code' => 'AC-7890',
        ]);

        // Wrong ticket code
        $response = $this->post("/events/{$event->id}/check-in", [
            'email' => 'arrived@example.com',
            'ticket_code' => 'AC-WRONG',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Invalid Details: We could not find a registered guest matching that email and ticket code.');

        $this->assertDatabaseHas('event_registrations', [
            'id' => $reg->id,
            'attended' => false,
        ]);
    }

    /**
     * Test manual attendance check-in toggle action by coordinator.
     */
    public function test_admin_can_manually_toggle_guest_attendance(): void
    {
        $this->withoutMiddleware(ValidateCsrfToken::class);
        $this->withoutMiddleware(\App\Http\Middleware\EnsurePinIsConfigured::class);

        $superAdmin = User::create([
            'name' => 'John Castillo',
            'email' => 'castillojohnlaurence0@gmail.com',
        ]);

        $event = Event::create([
            'title' => 'GAD Venue Check-In Event',
            'description' => 'Discuss Gender Action plans at venue.',
            'event_date' => now()->addDays(5),
            'location' => 'Main Conference Hall',
            'registration_type' => 'venue_confirmation',
        ]);

        $reg = EventRegistration::create([
            'event_id' => $event->id,
            'name' => 'Arriving Guest',
            'email' => 'arrived@example.com',
            'gender' => 'Female',
            'status' => 'approved',
            'ticket_code' => 'AC-7890',
            'attended' => false,
        ]);

        // Toggle to true
        $response = $this->actingAs($superAdmin)->post("/committees/registrations/{$reg->id}/toggle-attendance");

        $response->assertRedirect();
        $response->assertSessionHas('status', 'Guest marked as Checked-In!');

        $this->assertDatabaseHas('event_registrations', [
            'id' => $reg->id,
            'attended' => true,
        ]);

        // Toggle back to false
        $response = $this->actingAs($superAdmin)->post("/committees/registrations/{$reg->id}/toggle-attendance");

        $response->assertRedirect();
        $response->assertSessionHas('status', 'Guest check-in cancelled.');

        $this->assertDatabaseHas('event_registrations', [
            'id' => $reg->id,
            'attended' => false,
        ]);
    }

    /**
     * Test single registration destroy.
     */
    public function test_admin_can_manually_delete_registration(): void
    {
        $this->withoutMiddleware(ValidateCsrfToken::class);
        $this->withoutMiddleware(\App\Http\Middleware\EnsurePinIsConfigured::class);

        $superAdmin = User::create([
            'name' => 'John Castillo',
            'email' => 'castillojohnlaurence0@gmail.com',
        ]);

        $event = Event::create([
            'title' => 'GAD Assembly',
            'description' => 'Discuss Gender Action plans.',
            'event_date' => now()->addDays(5),
            'location' => 'Main Conference Hall',
        ]);

        $reg = EventRegistration::create([
            'event_id' => $event->id,
            'name' => 'Spam Guest',
            'email' => 'spam@example.com',
            'gender' => 'Male',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($superAdmin)->delete("/committees/registrations/{$reg->id}");

        $response->assertRedirect();
        $response->assertSessionHas('status', 'Attendee registration deleted successfully.');

        $this->assertDatabaseMissing('event_registrations', [
            'id' => $reg->id,
        ]);
    }

    /**
     * Test bulk delete registrations.
     */
    public function test_admin_can_bulk_delete_registrations(): void
    {
        $this->withoutMiddleware(ValidateCsrfToken::class);
        $this->withoutMiddleware(\App\Http\Middleware\EnsurePinIsConfigured::class);

        $superAdmin = User::create([
            'name' => 'John Castillo',
            'email' => 'castillojohnlaurence0@gmail.com',
        ]);

        $event = Event::create([
            'title' => 'GAD Assembly',
            'description' => 'Discuss Gender Action plans.',
            'event_date' => now()->addDays(5),
            'location' => 'Main Conference Hall',
        ]);

        $reg1 = EventRegistration::create([
            'event_id' => $event->id,
            'name' => 'Spam Guest 1',
            'email' => 'spam1@example.com',
            'gender' => 'Male',
            'status' => 'pending',
        ]);

        $reg2 = EventRegistration::create([
            'event_id' => $event->id,
            'name' => 'Spam Guest 2',
            'email' => 'spam2@example.com',
            'gender' => 'Female',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($superAdmin)->post("/committees/registrations/bulk-delete", [
            'ids' => [$reg1->id, $reg2->id]
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status', 'Successfully deleted selected registrations.');

        $this->assertDatabaseMissing('event_registrations', [
            'id' => $reg1->id,
        ]);
        $this->assertDatabaseMissing('event_registrations', [
            'id' => $reg2->id,
        ]);
    }
}
