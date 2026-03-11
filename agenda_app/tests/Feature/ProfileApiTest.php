<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_endpoints_require_authentication(): void
    {
        $this->getJson('/api/profile')->assertUnauthorized();
        $this->putJson('/api/profile', [])->assertUnauthorized();
    }

    public function test_authenticated_user_can_fetch_profile(): void
    {
        $user = User::factory()->create([
            'name' => 'Luca Bianchi',
            'plan' => 'Premium Plan',
            'avatar_url' => 'https://example.com/luca.jpg',
            'timezone' => 'Europe/Rome',
            'preferred_view' => 'day',
            'bio' => 'Bio test',
        ]);

        $this->actingAs($user)
            ->getJson('/api/profile')
            ->assertOk()
            ->assertJsonPath('profile.name', 'Luca Bianchi')
            ->assertJsonPath('profile.plan', 'Premium Plan')
            ->assertJsonPath('profile.preferred_view', 'day');
    }

    public function test_authenticated_user_can_update_profile(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->putJson('/api/profile', [
            'name' => 'Nuovo Nome',
            'email' => 'nuovo@example.com',
            'plan' => 'Business Plan',
            'avatar_url' => 'https://example.com/avatar.jpg',
            'timezone' => 'Europe/Rome',
            'preferred_view' => 'year',
            'bio' => 'Nuova bio',
        ]);

        $response->assertOk();
        $response->assertJsonPath('profile.name', 'Nuovo Nome');
        $response->assertJsonPath('profile.preferred_view', 'year');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Nuovo Nome',
            'email' => 'nuovo@example.com',
            'plan' => 'Business Plan',
            'preferred_view' => 'year',
        ]);
    }

    public function test_profile_update_validates_unique_email_and_preferred_view(): void
    {
        $user = User::factory()->create(['email' => 'first@example.com']);
        $other = User::factory()->create(['email' => 'other@example.com']);

        $response = $this->actingAs($user)->putJson('/api/profile', [
            'name' => 'Mario',
            'email' => $other->email,
            'plan' => 'Free Plan',
            'avatar_url' => 'https://example.com/avatar.jpg',
            'timezone' => 'Europe/Rome',
            'preferred_view' => 'weekly',
            'bio' => 'test',
        ]);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['email', 'preferred_view']);
    }
}
