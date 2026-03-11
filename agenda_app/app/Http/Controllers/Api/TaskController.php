<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TaskController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $data = $this->validateTask($request);

        $task = Task::create([
            'title' => $data['title'],
            'type' => $data['type'],
            'time_label' => $data['time'] ?? null,
            'due_date' => $data['due_date'],
            'start_time' => $data['start_time'],
            'end_time' => $data['end_time'],
            'color' => $data['color'],
            'icon' => $data['icon'] ?? 'book-open',
            'participants' => $data['participants'] ?? [],
            'completed' => false,
        ]);

        return response()->json([
            'task' => $task->api_payload,
        ], 201);
    }

    public function update(Request $request, Task $task): JsonResponse
    {
        $data = $this->validateTask($request);

        $task->update([
            'title' => $data['title'],
            'type' => $data['type'],
            'time_label' => $data['time'] ?? null,
            'due_date' => $data['due_date'],
            'start_time' => $data['start_time'],
            'end_time' => $data['end_time'],
            'color' => $data['color'],
            'icon' => $data['icon'] ?? $task->icon ?? 'book-open',
            'participants' => $data['participants'] ?? [],
        ]);

        return response()->json([
            'task' => $task->fresh()->api_payload,
        ]);
    }

    public function destroy(Task $task): JsonResponse
    {
        $task->delete();

        return response()->json([
            'deleted' => true,
            'id' => $task->id,
        ]);
    }

    public function toggle(Task $task): JsonResponse
    {
        $task->update([
            'completed' => ! $task->completed,
        ]);

        return response()->json([
            'task' => $task->fresh()->api_payload,
        ]);
    }

    private function validateTask(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:120'],
            'type' => ['required', 'string', 'max:120'],
            'time' => ['nullable', 'string', 'max:30'],
            'due_date' => ['required', 'date'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'color' => ['required', Rule::in(['lime', 'indigo', 'blue'])],
            'icon' => ['nullable', 'string', 'max:50'],
            'participants' => ['nullable', 'array'],
            'participants.*.name' => ['required_with:participants', 'string', 'max:80'],
            'participants.*.avatar' => ['nullable', 'url'],
        ]);
    }
}
