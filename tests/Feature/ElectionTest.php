<?php

namespace Tests\Feature;

use App\Models\Election;
use App\Models\ElectionPosition;
use App\Models\ElectionCandidate;
use App\Models\ElectionVoter;
use App\Models\ElectionVote;
use App\Models\User;
use App\Http\Middleware\EnsurePinIsConfigured;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ElectionTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Disable PIN configuration middleware for straightforward testing of election routes
        $this->withoutMiddleware(EnsurePinIsConfigured::class);
        $this->withoutMiddleware(ValidateCsrfToken::class);

        // Create standard authorized admin user
        $this->user = User::create([
            'name' => 'John Admin',
            'email' => 'john@mlhuillier.com',
            'pin' => '1234', // mock pin configured
        ]);
    }

    /**
     * Test unauthenticated users are redirected to login on internal dashboard.
     */
    public function test_unauthenticated_users_are_redirected_to_login_on_elections(): void
    {
        // Remove actingAs user to make guest request
        $response = $this->get('/committees/election-app');
        $response->assertRedirect('/');
    }

    /**
     * Test authenticated users can load the index page.
     */
    public function test_authenticated_users_can_view_elections_index(): void
    {
        Election::create([
            'title' => 'Board Election 2026',
            'description' => 'Elections for Board of Directors.',
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->user)->get('/committees/election-app');

        $response->assertStatus(200);
        $response->assertSee('Board Election 2026');
        $response->assertSee('Elections Dashboard');
    }

    /**
     * Test dynamic status filtering via AJAX.
     */
    public function test_authenticated_users_can_filter_elections_by_status(): void
    {
        Election::create([
            'title' => 'Active Election',
            'status' => 'active',
        ]);

        Election::create([
            'title' => 'Draft Election',
            'status' => 'draft',
        ]);

        // Get active elections only via AJAX
        $response = $this->actingAs($this->user)->getJson('/committees/election-app?status=active', [
            'X-Requested-With' => 'XMLHttpRequest'
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonPath('data.0.title', 'Active Election');

        // Get draft elections only
        $response = $this->actingAs($this->user)->getJson('/committees/election-app?status=draft', [
            'X-Requested-With' => 'XMLHttpRequest'
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('data.0.title', 'Draft Election');
    }

    /**
     * Test search capability via AJAX.
     */
    public function test_authenticated_users_can_search_elections(): void
    {
        Election::create([
            'title' => 'Unique Title Search',
            'status' => 'active',
        ]);

        Election::create([
            'title' => 'Another Generic Election',
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->user)->getJson('/committees/election-app?search=Unique', [
            'X-Requested-With' => 'XMLHttpRequest'
        ]);

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonPath('data.0.title', 'Unique Title Search');
    }

    /**
     * Test election creation via AJAX.
     */
    public function test_authenticated_users_can_create_election_via_ajax(): void
    {
        $response = $this->actingAs($this->user)->postJson('/committees/election-app', [
            'title' => 'New Year Election',
            'description' => 'Select representative officers.',
            'status' => 'draft',
            'start_date' => now()->addDays(1)->toDateTimeString(),
            'end_date' => now()->addDays(5)->toDateTimeString(),
        ], [
            'X-Requested-With' => 'XMLHttpRequest'
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
        $response->assertJsonPath('message', 'Election created successfully!');

        $this->assertDatabaseHas('elections', [
            'title' => 'New Year Election',
            'status' => 'draft',
        ]);
    }

    /**
     * Test election updating via AJAX.
     */
    public function test_authenticated_users_can_update_election_via_ajax(): void
    {
        $election = Election::create([
            'title' => 'Old Title',
            'status' => 'draft',
        ]);

        $response = $this->actingAs($this->user)->putJson("/committees/election-app/{$election->id}", [
            'title' => 'Updated Title',
            'status' => 'active',
        ], [
            'X-Requested-With' => 'XMLHttpRequest'
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
        $response->assertJsonPath('message', 'Election updated successfully!');

        $this->assertDatabaseHas('elections', [
            'id' => $election->id,
            'title' => 'Updated Title',
            'status' => 'active',
        ]);
    }

    /**
     * Test election deletion via AJAX.
     */
    public function test_authenticated_users_can_delete_election_via_ajax(): void
    {
        $election = Election::create([
            'title' => 'To Be Deleted',
            'status' => 'draft',
        ]);

        $response = $this->actingAs($this->user)->deleteJson("/committees/election-app/{$election->id}", [], [
            'X-Requested-With' => 'XMLHttpRequest'
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
        $response->assertJsonPath('message', 'Election deleted successfully!');

        $this->assertDatabaseMissing('elections', [
            'id' => $election->id,
        ]);
    }

    /**
     * Test loading individual election manage portal.
     */
    public function test_authenticated_users_can_view_election_manage_portal(): void
    {
        $election = Election::create([
            'title' => 'Board Election 2026',
            'status' => 'draft',
        ]);

        $response = $this->actingAs($this->user)->get("/committees/election-app/{$election->id}");

        $response->assertStatus(200);
        $response->assertSee('Board Election 2026');
        $response->assertSee('Ballot Structure');
        $response->assertSee('Voter Register');
        $response->assertSee('Election Results');
    }

    /**
     * Test Position creation, update, and deletion via AJAX.
     */
    public function test_positions_crud_management(): void
    {
        $election = Election::create([
            'title' => 'Board Election 2026',
            'status' => 'draft',
        ]);

        // 1. Store position
        $response = $this->actingAs($this->user)->postJson("/committees/election-app/{$election->id}/positions", [
            'name' => 'President',
            'max_votes' => 1,
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
        $response->assertJsonPath('message', 'Position created successfully!');
        
        $this->assertDatabaseHas('election_positions', [
            'election_id' => $election->id,
            'name' => 'President',
            'max_votes' => 1,
        ]);

        $position = ElectionPosition::where('name', 'President')->first();

        // 2. Update position
        $response = $this->actingAs($this->user)->putJson("/committees/election-app/positions/{$position->id}", [
            'name' => 'CEO',
            'max_votes' => 2,
            'sort_order' => 5,
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
        $this->assertDatabaseHas('election_positions', [
            'id' => $position->id,
            'name' => 'CEO',
            'max_votes' => 2,
            'sort_order' => 5,
        ]);

        // 3. Delete position
        $response = $this->actingAs($this->user)->deleteJson("/committees/election-app/positions/{$position->id}");

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
        $this->assertDatabaseMissing('election_positions', [
            'id' => $position->id,
        ]);
    }

    /**
     * Test Candidate creation, update, and deletion via AJAX.
     */
    public function test_candidates_crud_management(): void
    {
        $election = Election::create([
            'title' => 'Board Election 2026',
            'status' => 'draft',
        ]);

        $position = $election->positions()->create([
            'name' => 'Secretary',
            'max_votes' => 1,
        ]);

        // 1. Store candidate
        $response = $this->actingAs($this->user)->postJson("/committees/election-app/positions/{$position->id}/candidates", [
            'name' => 'Jane Candidate',
            'party_affiliation' => 'Reform Party',
            'avatar_path' => 'https://image.url/jane.png',
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
        $response->assertJsonPath('message', 'Candidate created successfully!');

        $this->assertDatabaseHas('election_candidates', [
            'position_id' => $position->id,
            'name' => 'Jane Candidate',
            'party_affiliation' => 'Reform Party',
            'avatar_path' => 'https://image.url/jane.png',
        ]);

        $candidate = ElectionCandidate::where('name', 'Jane Candidate')->first();

        // 2. Update candidate
        $response = $this->actingAs($this->user)->putJson("/committees/election-app/candidates/{$candidate->id}", [
            'name' => 'Jane Updated',
            'party_affiliation' => 'Independence',
            'avatar_path' => 'https://image.url/jane-new.png',
            'sort_order' => 3,
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
        $this->assertDatabaseHas('election_candidates', [
            'id' => $candidate->id,
            'name' => 'Jane Updated',
            'party_affiliation' => 'Independence',
            'avatar_path' => 'https://image.url/jane-new.png',
            'sort_order' => 3,
        ]);

        // 3. Delete candidate
        $response = $this->actingAs($this->user)->deleteJson("/committees/election-app/candidates/{$candidate->id}");

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
        $this->assertDatabaseMissing('election_candidates', [
            'id' => $candidate->id,
        ]);
    }

    // ==========================================
    // PHASE 3: VOTING FLOW TESTS (SESSION-BASED ISOLATED VOTERS)
    // ==========================================

    /**
     * Test non-mlhuillier emails get rejected with 403 on voting views.
     */
    public function test_voter_domain_security_rejection(): void
    {
        $election = Election::create([
            'title' => 'ML Corporate Voting',
            'status' => 'active',
        ]);

        // Imposter voter session
        $response = $this->withSession(['voter_email' => 'spammer@gmail.com'])->get("/elections/{$election->id}/setup");
        $response->assertStatus(403);
    }

    /**
     * Test users cannot view draft or closed elections in setup or ballot.
     */
    public function test_voter_cannot_access_inactive_election(): void
    {
        $election = Election::create([
            'title' => 'Draft Election Portal',
            'status' => 'draft',
        ]);

        $response = $this->withSession(['voter_email' => 'john@mlhuillier.com'])->get("/elections/{$election->id}/setup");
        $response->assertStatus(403);
    }

    /**
     * Test voter profiling onboarding and submission.
     */
    public function test_voter_profiling_and_submission(): void
    {
        $election = Election::create([
            'title' => 'Active Corporate Election',
            'status' => 'active',
        ]);

        // 1. Get Setup View
        $response = $this->withSession(['voter_email' => 'john@mlhuillier.com'])->get("/elections/{$election->id}/setup");
        $response->assertStatus(200);
        $response->assertSee('Voter Verification');

        // 2. Submit Profiling
        $response = $this->withSession(['voter_email' => 'john@mlhuillier.com'])->post("/elections/{$election->id}/setup", [
            'division' => 'OPEC Division',
            'current_position' => 'Area Manager',
        ]);

        $response->assertRedirect("/elections/{$election->id}/vote");

        $this->assertDatabaseHas('election_voters', [
            'election_id' => $election->id,
            'email' => 'john@mlhuillier.com',
            'division' => 'OPEC Division',
            'current_position' => 'Area Manager',
            'voted_at' => null,
        ]);
    }

    /**
     * Test voter is redirected from ballot back to setup if details are missing.
     */
    public function test_voter_is_redirected_to_setup_if_profiling_is_incomplete(): void
    {
        $election = Election::create([
            'title' => 'Active Corporate Election',
            'status' => 'active',
        ]);

        // Access ballot without setup first
        $response = $this->withSession(['voter_email' => 'john@mlhuillier.com'])->get("/elections/{$election->id}/vote");
        $response->assertRedirect("/elections/{$election->id}/setup");
    }

    /**
     * Test casting a secret ballot successfully.
     */
    public function test_voter_can_cast_ballot_successfully(): void
    {
        $election = Election::create([
            'title' => 'Active Corporate Election',
            'status' => 'active',
        ]);

        $position = $election->positions()->create([
            'name' => 'President',
            'max_votes' => 1,
        ]);

        $candidate1 = $position->candidates()->create([
            'name' => 'Candidate Alpha',
        ]);

        // Prepare voter profile
        $voter = $election->voters()->create([
            'email' => 'john@mlhuillier.com',
            'division' => 'IT Department',
            'current_position' => 'Senior Developer',
        ]);

        // Cast Vote via AJAX
        $response = $this->withSession(['voter_email' => 'john@mlhuillier.com'])->postJson("/elections/{$election->id}/vote", [
            'votes' => [
                $position->id => [$candidate1->id]
            ]
        ], [
            'X-Requested-With' => 'XMLHttpRequest'
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
        $response->assertJsonPath('message', 'Ballot submitted and recorded successfully! Thank you for voting.');

        // Verify votes were created
        $this->assertDatabaseHas('election_votes', [
            'voter_id' => $voter->id,
            'position_id' => $position->id,
            'candidate_id' => $candidate1->id,
        ]);

        // Ensure voter is marked as voted
        $this->assertNotNull($voter->fresh()->voted_at);
    }

    /**
     * Test anti-double voting protection.
     */
    public function test_double_voting_protection(): void
    {
        $election = Election::create([
            'title' => 'Active Corporate Election',
            'status' => 'active',
        ]);

        $position = $election->positions()->create([
            'name' => 'Secretary',
            'max_votes' => 1,
        ]);

        $candidate = $position->candidates()->create([
            'name' => 'Candidate Gamma',
        ]);

        // Profile already set up and marked as voted
        $voter = $election->voters()->create([
            'email' => 'john@mlhuillier.com',
            'division' => 'IT',
            'current_position' => 'Manager',
            'voted_at' => now(),
        ]);

        // Attempting to post vote again
        $response = $this->withSession(['voter_email' => 'john@mlhuillier.com'])->postJson("/elections/{$election->id}/vote", [
            'votes' => [
                $position->id => [$candidate->id]
            ]
        ], [
            'X-Requested-With' => 'XMLHttpRequest'
        ]);

        $response->assertStatus(403);
        $response->assertJsonPath('success', false);
        $response->assertJsonPath('message', 'Double-voting protection: You have already cast your ballot in this election.');
    }

    /**
     * Test maximum votes limit verification.
     */
    public function test_voter_cannot_exceed_max_votes_limit(): void
    {
        $election = Election::create([
            'title' => 'Active Corporate Election',
            'status' => 'active',
        ]);

        $position = $election->positions()->create([
            'name' => 'Director',
            'max_votes' => 1, // only 1 allowed!
        ]);

        $candidate1 = $position->candidates()->create([
            'name' => 'Candidate One',
        ]);

        $candidate2 = $position->candidates()->create([
            'name' => 'Candidate Two',
        ]);

        $voter = $election->voters()->create([
            'email' => 'john@mlhuillier.com',
            'division' => 'IT',
            'current_position' => 'Staff',
        ]);

        // Post votes selecting BOTH candidates (limit is 1)
        $response = $this->withSession(['voter_email' => 'john@mlhuillier.com'])->postJson("/elections/{$election->id}/vote", [
            'votes' => [
                $position->id => [$candidate1->id, $candidate2->id]
            ]
        ], [
            'X-Requested-With' => 'XMLHttpRequest'
        ]);

        $response->assertStatus(400);
        $response->assertJsonPath('success', false);
        $response->assertJsonPath('message', 'Voting limit exceeded: You can select up to 1 candidates for the position of Director.');

        // Verify NO votes were saved in DB
        $this->assertEquals(0, ElectionVote::count());
        $this->assertNull($voter->fresh()->voted_at);
    }

    // ==========================================
    // PHASE 4: ANALYTICS & CSV EXPORT TESTS
    // ==========================================

    /**
     * Test exporting election results to CSV.
     */
    public function test_super_admin_can_export_csv_results(): void
    {
        $election = Election::create([
            'title' => 'Active Corporate Election',
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->user)->get("/committees/election-app/{$election->id}/export");

        $response->assertStatus(200);
        $response->assertHeader('Content-type', 'text/csv; charset=utf-8');
        $response->assertHeader('Content-Disposition', 'attachment; filename=election_results_active_corporate_election.csv');
    }
}
