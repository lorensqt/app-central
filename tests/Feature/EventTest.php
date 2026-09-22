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
            'birthday' => '1995-10-24',
            'division' => 'OPEC Visayas Division',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('event_registrations', [
            'event_id' => $event->id,
            'name' => 'John Guest',
            'email' => 'john.guest@example.com',
            'status' => 'pending',
            'birthday' => '1995-10-24 00:00:00',
            'division' => 'OPEC Visayas Division',
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
            'birthday' => '1995-10-24',
            'division' => 'OPEC Visayas Division',
        ]);

        // Attempt duplicate registration
        $response = $this->post("/events/{$event->id}/register", [
            'name' => 'John Imposter',
            'email' => 'john.guest@example.com',
            'gender' => 'Male',
            'birthday' => '1995-10-24',
            'division' => 'OPEC Visayas Division',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Registration Aborted: The following email address(es) are already registered for this event: john.guest@example.com');

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
     * Test admin can decline registrations with a rejection reason.
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

        // Admin declines RSVP with a reason
        $response = $this->actingAs($superAdmin)->post("/committees/registrations/{$registration->id}/decline", [
            'rejection_reason' => 'Already attended the previous session.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status', 'Registration request has been declined.');

        $this->assertDatabaseHas('event_registrations', [
            'id' => $registration->id,
            'status' => 'declined',
            'rejection_reason' => 'Already attended the previous session.',
        ]);
    }

    /**
     * Test guest is registered as pending always.
     */
    public function test_guest_is_initially_pending_always(): void
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
            'birthday' => '1995-10-24',
            'division' => 'OPEC Visayas Division',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Check pending status and ticket code is null
        $this->assertDatabaseHas('event_registrations', [
            'event_id' => $event->id,
            'name' => 'John Guest',
            'email' => 'john.venue@example.com',
            'status' => 'pending',
            'ticket_code' => null,
            'birthday' => '1995-10-24 00:00:00',
            'division' => 'OPEC Visayas Division',
        ]);

        $reg = EventRegistration::where('event_id', $event->id)->where('email', 'john.venue@example.com')->first();

        // Verify pending review email delivery
        Mail::assertSent(\App\Mail\EventPending::class, function ($mail) use ($reg) {
            return $mail->hasTo('john.venue@example.com') && $mail->registration->id === $reg->id;
        });
    }

    /**
     * Test guest can register with companions if group registration is enabled.
     */
    public function test_guest_can_register_with_companions_if_enabled(): void
    {
        Mail::fake();
        $this->withoutMiddleware(ValidateCsrfToken::class);

        $event = Event::create([
            'title' => 'Group GAD Assembly',
            'description' => 'Discuss Gender Action plans together.',
            'event_date' => now()->addDays(5),
            'location' => 'Main Conference Hall',
            'allow_group_registration' => true,
        ]);

        $response = $this->post("/events/{$event->id}/register", [
            'name' => 'John Primary',
            'email' => 'primary@example.com',
            'gender' => 'Male',
            'birthday' => '1990-10-12',
            'division' => 'OPEC Visayas Division',
            'companions' => [
                [
                    'name' => 'Companion Jane',
                    'email' => 'jane.comp@example.com',
                    'gender' => 'Female',
                    'birthday' => '1993-05-04',
                ],
                [
                    'name' => 'Companion Mike',
                    'email' => 'mike.comp@example.com',
                    'gender' => 'Male',
                    'birthday' => '1994-06-15',
                ]
            ]
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Check primary registered and has a group code and is_group_primary true
        $this->assertDatabaseHas('event_registrations', [
            'event_id' => $event->id,
            'name' => 'John Primary',
            'email' => 'primary@example.com',
            'status' => 'pending',
            'is_group_primary' => true,
            'division' => 'OPEC Visayas Division',
        ]);

        $primaryReg = EventRegistration::where('event_id', $event->id)->where('email', 'primary@example.com')->first();
        $this->assertNotNull($primaryReg->group_code);

        // Check companions registered sharing the same group code
        $this->assertDatabaseHas('event_registrations', [
            'event_id' => $event->id,
            'name' => 'Companion Jane',
            'email' => 'jane.comp@example.com',
            'status' => 'pending',
            'group_code' => $primaryReg->group_code,
            'is_group_primary' => false,
        ]);

        $this->assertDatabaseHas('event_registrations', [
            'event_id' => $event->id,
            'name' => 'Companion Mike',
            'email' => 'mike.comp@example.com',
            'status' => 'pending',
            'group_code' => $primaryReg->group_code,
            'is_group_primary' => false,
        ]);

        // Verify emails were dispatched to everyone
        Mail::assertSent(\App\Mail\EventPending::class, 3);
    }

    /**
     * Test guest can register with companions that have blank emails (e.g. children) and routing triggers correctly.
     */
    public function test_guest_can_register_companions_with_blank_emails_guardian_routing(): void
    {
        Mail::fake();
        $this->withoutMiddleware(ValidateCsrfToken::class);

        $event = Event::create([
            'title' => 'Family GAD Assembly',
            'description' => 'Discuss Gender Action plans together as a family.',
            'event_date' => now()->addDays(5),
            'location' => 'Main Conference Hall',
            'allow_group_registration' => true,
        ]);

        $response = $this->post("/events/{$event->id}/register", [
            'name' => 'John Parent',
            'email' => 'parent@example.com',
            'gender' => 'Male',
            'birthday' => '1985-05-10',
            'division' => 'OPEC Visayas Division',
            'companions' => [
                [
                    'name' => 'Jane Kid',
                    'email' => '', // Blank email
                    'gender' => 'Female',
                    'birthday' => '2015-08-20',
                ]
            ]
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $primaryReg = EventRegistration::where('event_id', $event->id)->where('email', 'parent@example.com')->first();
        $this->assertNotNull($primaryReg->group_code);

        // Check companion was registered under a virtual email address
        $companionReg = EventRegistration::where('event_id', $event->id)
            ->where('group_code', $primaryReg->group_code)
            ->where('is_group_primary', false)
            ->first();

        $this->assertNotNull($companionReg);
        $this->assertStringContainsString('@sako-companion.local', $companionReg->email);

        // Verify emails: both primary pending and companion pending mails should be dispatched to primary guardian
        Mail::assertSent(\App\Mail\EventPending::class, function ($mail) {
            return $mail->hasTo('parent@example.com');
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
            'birthday' => '1995-10-24',
            'division' => 'OPEC Visayas Division',
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
            'event_date' => now(),
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
            'event_date' => now(),
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

    /**
     * Test direct check-in with a valid secure signed URL.
     */
    public function test_direct_check_in_with_valid_signed_url(): void
    {
        $event = Event::create([
            'title' => 'GAD Venue Check-In Event',
            'description' => 'Discuss Gender Action plans at venue.',
            'event_date' => now(),
            'location' => 'Main Conference Hall',
            'registration_type' => 'venue_confirmation',
        ]);

        $reg = EventRegistration::create([
            'event_id' => $event->id,
            'name' => 'Direct Checking Guest',
            'email' => 'direct@example.com',
            'gender' => 'Female',
            'status' => 'pending',
            'ticket_code' => 'AC-DIRECT',
        ]);

        // Generate a secure signed URL
        $signedUrl = \Illuminate\Support\Facades\URL::signedRoute('events.direct_check_in', ['registration' => $reg->id]);

        $response = $this->get($signedUrl);

        $response->assertRedirect(route('events.check_in_success', ['event' => $event->id]));
        $response->assertSessionHas('attendee_name', 'Direct Checking Guest');

        $this->assertDatabaseHas('event_registrations', [
            'id' => $reg->id,
            'status' => 'approved',
            'attended' => true,
        ]);
    }

    /**
     * Test direct check-in rejects invalid/unsigned url access.
     */
    public function test_direct_check_in_rejects_invalid_signature(): void
    {
        $event = Event::create([
            'title' => 'GAD Venue Check-In Event',
            'description' => 'Discuss Gender Action plans.',
            'event_date' => now()->addDays(5),
            'location' => 'Main Conference Hall',
        ]);

        $reg = EventRegistration::create([
            'event_id' => $event->id,
            'name' => 'Direct Checking Guest',
            'email' => 'direct@example.com',
            'status' => 'pending',
        ]);

        // Accessing the url without a valid signature
        $response = $this->get(route('events.direct_check_in', ['registration' => $reg->id]));

        $response->assertStatus(401);

        $this->assertDatabaseHas('event_registrations', [
            'id' => $reg->id,
            'status' => 'pending',
            'attended' => false,
        ]);
    }

    /**
     * Test scheduling a new event redirects to the event management portal.
     */
    public function test_scheduling_event_redirects_to_manage_portal(): void
    {
        $this->withoutMiddleware(ValidateCsrfToken::class);
        $this->withoutMiddleware(\App\Http\Middleware\EnsurePinIsConfigured::class);

        $superAdmin = User::create([
            'name' => 'John Castillo',
            'email' => 'castillojohnlaurence0@gmail.com',
        ]);

        $committee = \App\Models\Committee::create([
            'name' => 'GAD Committee Test',
        ]);

        $eventData = [
            'title' => 'New Premium Event',
            'description' => 'A beautifully designed premium community event.',
            'terms_and_policy' => 'Accept all terms.',
            'event_date' => now()->addDays(2)->format('Y-m-d H:i:s'),
            'end_date' => now()->addDays(2)->addHours(4)->format('Y-m-d H:i:s'),
            'location' => 'Main Convention Center',
            'location_type' => 'physical',
            'registration_type' => 'venue_confirmation',
            'committee_id' => $committee->id,
        ];

        $response = $this->actingAs($superAdmin)->post('/committees/events', $eventData);

        // Retrieve the newly created event to assert route redirection
        $event = Event::where('title', 'New Premium Event')->first();
        $this->assertNotNull($event);

        $response->assertRedirect(route('committees.events.manage', $event));
        $response->assertSessionHas('status', 'Event scheduled successfully and is now open for registrations.');
    }
}
