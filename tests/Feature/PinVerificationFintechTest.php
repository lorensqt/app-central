<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PinVerificationFintechTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class);
    }

    public function test_user_can_configure_pin_via_ajax(): void
    {
        $user = User::factory()->create([
            'pin' => null,
        ]);

        $response = $this->actingAs($user)->postJson(route('pin.save'), [
            'pin' => '654321',
            'pin_confirmation' => '654321',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'redirect' => route('dashboard'),
        ]);

        $user->refresh();
        $this->assertEquals('654321', $user->pin);
        $this->assertTrue(session('pin_verified'));
    }

    public function test_user_cannot_save_mismatched_pin_via_ajax(): void
    {
        $user = User::factory()->create([
            'pin' => null,
        ]);

        $response = $this->actingAs($user)->postJson(route('pin.save'), [
            'pin' => '654321',
            'pin_confirmation' => '111111',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['pin_confirmation']);
    }

    public function test_user_can_verify_pin_via_ajax_with_auto_check(): void
    {
        $user = User::factory()->create([
            'pin' => '123456',
        ]);

        $this->actingAs($user)->withSession(['pin_verified' => false]);

        $response = $this->postJson(route('pin.verify.submit'), [
            'pin' => '123456',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'redirect' => route('dashboard'),
        ]);

        $this->assertTrue(session('pin_verified'));
    }

    public function test_user_receives_attempts_left_on_incorrect_pin_via_ajax(): void
    {
        $user = User::factory()->create([
            'pin' => '123456',
        ]);

        $this->actingAs($user)->withSession(['pin_verified' => false]);

        // Attempt 1 (wrong)
        $response = $this->postJson(route('pin.verify.submit'), [
            'pin' => '999999',
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
            'locked_out' => false,
            'attempts_left' => 2,
        ]);

        // Attempt 2 (wrong)
        $response = $this->postJson(route('pin.verify.submit'), [
            'pin' => '888888',
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
            'locked_out' => false,
            'attempts_left' => 1,
        ]);

        // Attempt 3 (wrong -> lockout)
        $response = $this->postJson(route('pin.verify.submit'), [
            'pin' => '777777',
        ]);

        $response->assertStatus(403);
        $response->assertJson([
            'success' => false,
            'locked_out' => true,
            'redirect' => route('login'),
        ]);

        $this->assertGuest();
    }
}
