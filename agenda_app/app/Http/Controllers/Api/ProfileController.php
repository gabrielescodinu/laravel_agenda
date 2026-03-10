<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function show(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json([
            'profile' => [
                'name' => $user->name,
                'email' => $user->email,
                'plan' => $user->plan,
                'avatar_url' => $user->avatar_url,
                'timezone' => $user->timezone,
                'preferred_view' => $user->preferred_view,
                'bio' => $user->bio,
            ],
        ]);
    }

    public function update(Request $request): JsonResponse
    {
        $user = $request->user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'plan' => ['required', 'string', 'max:50'],
            'avatar_url' => ['nullable', 'url', 'max:500'],
            'timezone' => ['required', 'string', 'max:60'],
            'preferred_view' => ['required', Rule::in(['day', 'month', 'year'])],
            'bio' => ['nullable', 'string', 'max:1000'],
        ]);

        $user->update($data);

        return response()->json([
            'profile' => [
                'name' => $user->name,
                'email' => $user->email,
                'plan' => $user->plan,
                'avatar_url' => $user->avatar_url,
                'timezone' => $user->timezone,
                'preferred_view' => $user->preferred_view,
                'bio' => $user->bio,
            ],
        ]);
    }
}
