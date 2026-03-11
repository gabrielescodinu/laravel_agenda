<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_task_store_requires_authentication(): void
    {
        $this->postJson('/api/tasks', [])->assertUnauthorized();
    }

    public function test_authenticated_user_can_create_task_with_details(): void
    {
        $user = User::factory()->create();

        $payload = [
            'title' => 'Design Review Task',
            'type' => 'Creativita',
            'time' => '11:00',
            'due_date' => '2026-03-20',
            'start_time' => '11:00',
            'end_time' => '12:30',
            'color' => 'indigo',
            'icon' => 'palette',
            'participants' => [
                ['name' => 'Alex', 'avatar' => 'https://example.com/alex.jpg'],
                ['name' => 'Marta', 'avatar' => 'https://example.com/marta.jpg'],
            ],
        ];

        $response = $this->actingAs($user)->postJson('/api/tasks', $payload);

        $response->assertCreated();
        $response->assertJsonPath('task.title', 'Design Review Task');
        $response->assertJsonPath('task.type', 'Creativita');
        $response->assertJsonPath('task.due_date', '2026-03-20');
        $response->assertJsonPath('task.start_time', '11:00');
        $response->assertJsonPath('task.end_time', '12:30');
        $response->assertJsonPath('task.color', 'indigo');

        $this->assertDatabaseHas('tasks', [
            'title' => 'Design Review Task',
            'type' => 'Creativita',
            'completed' => 0,
        ]);

        $storedTask = Task::query()->where('title', 'Design Review Task')->firstOrFail();
        $this->assertSame('2026-03-20', $storedTask->due_date?->toDateString());
    }

    public function test_task_store_validates_required_and_time_rules(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/api/tasks', [
            'title' => '',
            'type' => '',
            'due_date' => '',
            'start_time' => '12:30',
            'end_time' => '11:00',
            'color' => 'invalid',
        ]);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors([
            'title',
            'type',
            'due_date',
            'end_time',
            'color',
        ]);
    }

    public function test_authenticated_user_can_toggle_task_completion(): void
    {
        $user = User::factory()->create();

        $task = Task::create([
            'title' => 'Call Client',
            'type' => 'Commerciale',
            'time_label' => '16:30',
            'due_date' => '2026-03-22',
            'start_time' => '16:30',
            'end_time' => '17:00',
            'color' => 'lime',
            'icon' => 'book-open',
            'participants' => [['name' => 'Alex']],
            'completed' => false,
        ]);

        $this->actingAs($user)->patchJson("/api/tasks/{$task->id}/toggle")
            ->assertOk()
            ->assertJsonPath('task.completed', true);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'completed' => 1,
        ]);
    }
}
