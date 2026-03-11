<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_endpoint_requires_authentication(): void
    {
        $this->getJson('/api/dashboard')->assertUnauthorized();
    }

    public function test_dashboard_returns_user_info_and_filters_data_by_month(): void
    {
        $user = User::factory()->create([
            'name' => 'Giulia Verdi',
            'plan' => 'Premium Plan',
            'avatar_url' => 'https://example.com/avatar.jpg',
            'timezone' => 'Europe/Rome',
            'preferred_view' => 'year',
        ]);

        Event::create([
            'title' => 'Evento Marzo',
            'type' => 'Meeting',
            'start_at' => '2026-03-12 09:00:00',
            'end_at' => '2026-03-12 10:00:00',
            'color' => 'lime',
            'icon' => 'book-open',
            'participants' => [['name' => 'A']],
        ]);

        Event::create([
            'title' => 'Evento Aprile',
            'type' => 'Meeting',
            'start_at' => '2026-04-12 09:00:00',
            'end_at' => '2026-04-12 10:00:00',
            'color' => 'indigo',
            'icon' => 'book-open',
            'participants' => [['name' => 'B']],
        ]);

        Task::create([
            'title' => 'Task Marzo',
            'type' => 'Operativo',
            'time_label' => '09:00',
            'due_date' => '2026-03-18',
            'start_time' => '09:00',
            'end_time' => '10:00',
            'color' => 'blue',
            'icon' => 'clock-3',
            'participants' => [['name' => 'A']],
            'completed' => false,
        ]);

        Task::create([
            'title' => 'Task Aprile',
            'type' => 'Operativo',
            'time_label' => '09:00',
            'due_date' => '2026-04-18',
            'start_time' => '09:00',
            'end_time' => '10:00',
            'color' => 'blue',
            'icon' => 'clock-3',
            'participants' => [['name' => 'B']],
            'completed' => false,
        ]);

        $response = $this->actingAs($user)->getJson('/api/dashboard?month=2026-03');

        $response->assertOk();
        $response->assertJsonPath('user.name', 'Giulia Verdi');
        $response->assertJsonPath('user.plan', 'Premium Plan');
        $response->assertJsonPath('user.avatar', 'https://example.com/avatar.jpg');
        $response->assertJsonPath('user.preferred_view', 'year');
        $response->assertJsonCount(1, 'calendar.events');
        $response->assertJsonCount(1, 'tasks');
        $response->assertJsonPath('calendar.events.0.title', 'Evento Marzo');
        $response->assertJsonPath('tasks.0.title', 'Task Marzo');
    }
}
