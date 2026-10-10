<?php

namespace Tests\Feature;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleBasedAccessControlTest extends TestCase
{
    use RefreshDatabase;

    protected User $superAdmin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class);

        $this->superAdmin = User::factory()->create([
            'email' => config('app.super_admin_email') ?? 'castillojohnlaurence0@gmail.com',
            'is_admin' => true,
            'pin' => bcrypt('123456'),
        ]);

        // Ensure default permissions exist
        Permission::firstOrCreate(
            ['slug' => 'events.access'],
            ['name' => 'Event Management', 'group' => 'Committees']
        );
        Permission::firstOrCreate(
            ['slug' => 'elections.access'],
            ['name' => 'Election Management', 'group' => 'Committees']
        );
    }

    public function test_super_admin_has_unrestricted_access_to_all_workspaces(): void
    {
        $this->actingAs($this->superAdmin)
            ->withSession(['pin_verified' => true]);

        $responseEvents = $this->get(route('committees.events.index'));
        $responseEvents->assertStatus(200);

        $responseElections = $this->get(route('committees.election.index'));
        $responseElections->assertStatus(200);

        $responseDashboard = $this->get(route('dashboard'));
        $responseDashboard->assertStatus(200);
        $responseDashboard->assertSee('Event Management');
        $responseDashboard->assertSee('Election Management');
    }

    public function test_user_with_only_events_access_can_access_events_portal_but_not_elections(): void
    {
        $user = User::factory()->create([
            'pin' => bcrypt('123456'),
            'is_admin' => false,
        ]);
        $user->givePermission('events.access');

        $this->actingAs($user)->withSession(['pin_verified' => true]);

        // Accessing events portal succeeds
        $this->get(route('committees.events.index'))->assertStatus(200);

        // Accessing elections portal redirects to dashboard with error
        $response = $this->get(route('committees.election.index'));
        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('error');

        // Dashboard shows Event Management but hides Election Management
        $dashResponse = $this->get(route('dashboard'));
        $dashResponse->assertSee('Event Management');
        $dashResponse->assertDontSee('Election Management');
    }

    public function test_user_with_only_elections_access_can_access_elections_portal_but_not_events(): void
    {
        $user = User::factory()->create([
            'pin' => bcrypt('123456'),
            'is_admin' => false,
        ]);
        $user->givePermission('elections.access');

        $this->actingAs($user)->withSession(['pin_verified' => true]);

        // Accessing elections portal succeeds
        $this->get(route('committees.election.index'))->assertStatus(200);

        // Accessing events portal redirects to dashboard with error
        $response = $this->get(route('committees.events.index'));
        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('error');

        // Dashboard shows Election Management but hides Event Management
        $dashResponse = $this->get(route('dashboard'));
        $dashResponse->assertSee('Election Management');
        $dashResponse->assertDontSee('Event Management');
    }

    public function test_user_without_any_permissions_sees_restricted_state_and_cannot_access_apps(): void
    {
        $user = User::factory()->create([
            'pin' => bcrypt('123456'),
            'is_admin' => false,
        ]);

        $this->actingAs($user)->withSession(['pin_verified' => true]);

        $this->get(route('committees.events.index'))->assertRedirect(route('dashboard'));
        $this->get(route('committees.election.index'))->assertRedirect(route('dashboard'));

        $dashResponse = $this->get(route('dashboard'));
        $dashResponse->assertSee('Workspace Access Restricted');
        $dashResponse->assertDontSee('Event Management');
        $dashResponse->assertDontSee('Election Management');
    }

    public function test_super_admin_can_whitelist_user_with_specific_permissions(): void
    {
        $this->actingAs($this->superAdmin)->withSession(['pin_verified' => true]);

        $response = $this->post(route('admin.users.store'), [
            'name' => 'Alice Member',
            'email' => 'alice@example.com',
            'permissions' => ['events.access'],
        ]);

        $response->assertRedirect(route('admin.index', ['tab' => 'users']));

        $user = User::where('email', 'alice@example.com')->firstOrFail();
        $this->assertTrue($user->hasPermission('events.access'));
        $this->assertFalse($user->hasPermission('elections.access'));
    }

    public function test_super_admin_can_update_user_permissions_anytime(): void
    {
        $this->actingAs($this->superAdmin)->withSession(['pin_verified' => true]);

        $user = User::factory()->create([
            'email' => 'bob@example.com',
            'is_admin' => false,
        ]);
        $user->givePermission('events.access');

        $this->assertTrue($user->hasPermission('events.access'));
        $this->assertFalse($user->hasPermission('elections.access'));

        // Super admin updates bob to have elections.access instead
        $response = $this->put(route('admin.users.update', $user), [
            'name' => $user->name,
            'email' => $user->email,
            'permissions' => ['elections.access'],
        ]);

        $response->assertRedirect(route('admin.index', ['tab' => 'users']));

        $user->refresh();
        $this->assertFalse($user->hasPermission('events.access'));
        $this->assertTrue($user->hasPermission('elections.access'));
    }
}
