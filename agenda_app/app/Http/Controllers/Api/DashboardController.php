<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $month = $request->query('month');

        $date = $month
            ? Carbon::createFromFormat('Y-m', $month)->startOfMonth()
            : now()->startOfMonth();

        $start = $date->copy()->startOfMonth();
        $end = $date->copy()->endOfMonth();

        $events = Event::query()
            ->whereBetween('start_at', [$start, $end])
            ->orderBy('start_at')
            ->get()
            ->map(fn (Event $event) => $event->api_payload)
            ->values();

        $tasks = Task::query()
            ->whereBetween('due_date', [$start->toDateString(), $end->toDateString()])
            ->orderBy('due_date')
            ->orderBy('completed')
            ->orderBy('id')
            ->get()
            ->map(fn (Task $task) => $task->api_payload)
            ->values();

        return response()->json([
            'user' => [
                'name' => $user?->name ?? 'Utente',
                'plan' => $user?->plan ?? 'Free Plan',
                'avatar' => $this->avatarUrl($user?->avatar_url) ?: 'https://i.pravatar.cc/160?img=59',
                'timezone' => $user?->timezone ?? 'Europe/Rome',
                'preferred_view' => $user?->preferred_view ?? 'month',
            ],
            'calendar' => [
                'month' => $date->locale('it')->isoFormat('MMMM'),
                'year' => $date->year,
                'events' => $events,
            ],
            'tasks' => $tasks,
        ]);
    }

    private function avatarUrl(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        if (str_starts_with($path, 'http')) {
            return $path;
        }

        return Storage::url($path);
    }
}
