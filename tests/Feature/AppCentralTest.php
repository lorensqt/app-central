<?php

namespace Tests\Feature;

use App\Models\Title;
use App\Models\User;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Facades\Socialite;
use Mockery;
use Tests\TestCase;

class AppCentralTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test landing page has the Google Login link.
     */
    public function test_landing_page_has_google_login_button(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Sign in with Google');
    }

    /**
     * Test unauthenticated users are redirected to login.
     */
    public function test_unauthenticated_users_are_redirected_to_login(): void
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/');

        $response = $this->get('/admin');
        $response->assertRedirect('/');
    }

    /**
     * Test the Super Admin email is ALWAYS allowed to login and automatically created if not present.
     */
    public function test_super_admin_email_is_always_allowed_to_login(): void
    {
        // Mock Google OAuth User
        $googleUser = Mockery::mock('Laravel\Socialite\Two\User');
        $googleUser->shouldReceive('getId')->andReturn('123456789');
        $googleUser->shouldReceive('getEmail')->andReturn('castillojohnlaurence0@gmail.com');
        $googleUser->shouldReceive('getName')->andReturn('John Laurence Castillo');
        $googleUser->shouldReceive('getAvatar')->andReturn('https://avatar.url/john.jpg');

        // Mock Socialite
        Socialite::shouldReceive('driver')
            ->with('google')
            ->andReturnSelf()
            ->shouldReceive('user')
            ->andReturn($googleUser);

        // Call the OAuth callback route
        $response = $this->get('/auth/google/callback');

        $response->assertRedirect('/dashboard');

        // Assert the user was created in the database
        $this->assertDatabaseHas('users', [
            'email' => 'castillojohnlaurence0@gmail.com',
            'google_id' => '123456789',
        ]);

        // Assert the user is authenticated
        $this->assertAuthenticated();
    }

    /**
     * Test whitelisted users are allowed to login.
     */
    public function test_whitelisted_users_are_allowed_to_login(): void
    {
        // Create whitelisted user in DB first
        $user = User::create([
            'name' => 'Jane Whitelisted',
            'email' => 'jane.doe@example.com',
        ]);

        // Mock Google OAuth User
        $googleUser = Mockery::mock('Laravel\Socialite\Two\User');
        $googleUser->shouldReceive('getId')->andReturn('987654321');
        $googleUser->shouldReceive('getEmail')->andReturn('jane.doe@example.com');
        $googleUser->shouldReceive('getName')->andReturn('Jane Doe');
        $googleUser->shouldReceive('getAvatar')->andReturn('https://avatar.url/jane.jpg');

        // Mock Socialite
        Socialite::shouldReceive('driver')
            ->with('google')
            ->andReturnSelf()
            ->shouldReceive('user')
            ->andReturn($googleUser);

        $response = $this->get('/auth/google/callback');

        $response->assertRedirect('/dashboard');

        // Assert their google credentials were saved
        $this->assertDatabaseHas('users', [
            'email' => 'jane.doe@example.com',
            'google_id' => '987654321',
        ]);

        $this->assertAuthenticatedAs($user);
    }

    /**
     * Test unauthorized users (not whitelisted and not super admin) are blocked.
     */
    public function test_non_whitelisted_users_are_blocked_from_login(): void
    {
        // Mock Google OAuth User (not whitelisted)
        $googleUser = Mockery::mock('Laravel\Socialite\Two\User');
        $googleUser->shouldReceive('getId')->andReturn('456123789');
        $googleUser->shouldReceive('getEmail')->andReturn('imposter@example.com');
        $googleUser->shouldReceive('getName')->andReturn('Imposter');
        $googleUser->shouldReceive('getAvatar')->andReturn('https://avatar.url/imposter.jpg');

        // Mock Socialite
        Socialite::shouldReceive('driver')
            ->with('google')
            ->andReturnSelf()
            ->shouldReceive('user')
            ->andReturn($googleUser);

        $response = $this->get('/auth/google/callback');

        $response->assertRedirect('/');
        $response->assertSessionHas('error', 'Access Denied: Your email is not authorized to access this application. Please contact an administrator.');

        // Assert the user was NOT created in DB and is NOT authenticated
        $this->assertDatabaseMissing('users', [
            'email' => 'imposter@example.com',
        ]);
        $this->assertGuest();
    }

    /**
     * Test only super admin can access admin panel.
     */
    public function test_only_super_admin_can_access_admin_panel(): void
    {
        // Create a regular whitelisted user
        $title = Title::create([
            'group' => 'Board of Directors',
            'title' => 'Director',
        ]);

        $user = User::create([
            'name' => 'Regular Director',
            'email' => 'director@example.com',
            'title_id' => $title->id,
        ]);

        // Attempting to access unified admin cockpit route should return 403
        $response = $this->actingAs($user)->get('/admin');
        $response->assertStatus(403);

        // Create the Super Admin user
        $superAdmin = User::create([
            'name' => 'John Castillo',
            'email' => 'castillojohnlaurence0@gmail.com',
        ]);

        // Super Admin gets access to the admin cockpit index
        $response = $this->actingAs($superAdmin)->get('/admin');
        $response->assertStatus(200);
    }

    /**
     * Test Super Admin can configure titles and whitelist users.
     */
    public function test_super_admin_can_configure_titles_and_whitelist_users(): void
    {
        $this->withoutMiddleware(ValidateCsrfToken::class);

        $superAdmin = User::create([
            'name' => 'John Castillo',
            'email' => 'castillojohnlaurence0@gmail.com',
        ]);

        // 1. Create a title
        $response = $this->actingAs($superAdmin)->post('/admin/titles', [
            'group' => 'Management',
            'title' => 'Chief Technology Officer',
        ]);

        $response->assertRedirect('/admin?tab=titles');
        $this->assertDatabaseHas('titles', [
            'group' => 'Management',
            'title' => 'Chief Technology Officer',
        ]);

        $title = Title::where('title', 'Chief Technology Officer')->first();

        // 2. Whitelist a user under that title
        $response = $this->actingAs($superAdmin)->post('/admin/users', [
            'name' => 'Jane Smith',
            'email' => 'jane.smith@example.com',
            'title_id' => $title->id,
        ]);

        $response->assertRedirect('/admin?tab=users');
        $this->assertDatabaseHas('users', [
            'name' => 'Jane Smith',
            'email' => 'jane.smith@example.com',
            'title_id' => $title->id,
        ]);
    }

    /**
     * Test Super Admin can update corporate titles.
     */
    public function test_super_admin_can_edit_corporate_titles(): void
    {
        $this->withoutMiddleware(ValidateCsrfToken::class);

        $superAdmin = User::create([
            'name' => 'John Castillo',
            'email' => 'castillojohnlaurence0@gmail.com',
        ]);

        $title = Title::create([
            'group' => 'Committees',
            'title' => 'Audit Head',
        ]);

        $response = $this->actingAs($superAdmin)->put("/admin/titles/{$title->id}", [
            'group' => 'Management',
            'title' => 'Global Audit Director',
        ]);

        $response->assertRedirect('/admin?tab=titles');
        $this->assertDatabaseHas('titles', [
            'id' => $title->id,
            'group' => 'Management',
            'title' => 'Global Audit Director',
        ]);
    }
}
