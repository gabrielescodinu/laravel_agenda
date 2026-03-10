<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EventController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:120'],
            'type' => ['required', 'string', 'max:120'],
            'start_at' => ['required', 'date'],
            'end_at' => ['required', 'date', 'after_or_equal:start_at'],
            'color' => ['required', Rule::in(['lime', 'indigo', 'blue'])],
            'icon' => ['nullable', 'string', 'max:50'],
            'participants' => ['nullable', 'array'],
            'participants.*.name' => ['required_with:participants', 'string', 'max:80'],
            'participants.*.avatar' => ['nullable', 'url'],
        ]);

        $event = Event::create([
            ...$data,
            'icon' => $data['icon'] ?? 'book-open',
        ]);

        return response()->json([
            'event' => $event->api_payload,
        ], 201);
    }
}
