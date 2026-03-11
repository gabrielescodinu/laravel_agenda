<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function show(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json([
            'profile' => $this->profilePayload($user),
        ]);
    }

    public function update(Request $request): JsonResponse
    {
        $user = $request->user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'plan' => ['required', 'string', 'max:50'],
            'timezone' => ['required', 'string', 'max:60'],
            'preferred_view' => ['required', Rule::in(['day', 'month', 'year'])],
            'bio' => ['nullable', 'string', 'max:1000'],
        ]);

        $user->update($data);

        return response()->json([
            'profile' => $this->profilePayload($user),
        ]);
    }

    public function uploadAvatar(Request $request): JsonResponse
    {
        $user = $request->user();

        $data = $request->validate([
            'avatar' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        if ($user->avatar_url && ! str_starts_with($user->avatar_url, 'http')) {
            Storage::disk('public')->delete($user->avatar_url);
        }

        $path = $data['avatar']->store('profile-avatars', 'public');

        $user->update([
            'avatar_url' => $path,
        ]);

        return response()->json([
            'profile' => $this->profilePayload($user->fresh()),
        ]);
    }

    private function profilePayload($user): array
    {
        return [
            'name' => $user->name,
            'email' => $user->email,
            'plan' => $user->plan,
            'avatar_url' => $this->avatarUrl($user->avatar_url),
            'timezone' => $user->timezone,
            'preferred_view' => $user->preferred_view,
            'bio' => $user->bio,
        ];
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
