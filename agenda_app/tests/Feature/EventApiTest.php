<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_event_store_requires_authentication(): void
    {
        $this->postJson('/api/events', [])->assertUnauthorized();
    }

    public function test_authenticated_user_can_create_event(): void
    {
        $user = User::factory()->create();

        $payload = [
            'title' => 'Kickoff',
            'type' => 'Management',
            'start_at' => '2026-03-10 10:00:00',
            'end_at' => '2026-03-10 11:00:00',
            'color' => 'blue',
            'icon' => 'clock-3',
            'participants' => [
                ['name' => 'Team Lead', 'avatar' => 'https://example.com/a.jpg'],
            ],
        ];

        $response = $this->actingAs($user)->postJson('/api/events', $payload);

        $response->assertCreated();
        $response->assertJsonPath('event.title', 'Kickoff');
        $response->assertJsonPath('event.type', 'Management');
        $response->assertJsonPath('event.color', 'blue');

        $this->assertDatabaseHas('events', [
            'title' => 'Kickoff',
            'type' => 'Management',
            'color' => 'blue',
        ]);
    }

    public function test_event_store_validates_end_after_start(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/api/events', [
            'title' => 'Invalid Event',
            'type' => 'Meeting',
            'start_at' => '2026-03-10 12:00:00',
            'end_at' => '2026-03-10 11:00:00',
            'color' => 'lime',
        ]);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['end_at']);
    }
}
