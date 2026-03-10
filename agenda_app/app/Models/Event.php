<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Event extends Model
{
    protected $fillable = [
        'title',
        'type',
        'start_at',
        'end_at',
        'color',
        'icon',
        'participants',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'participants' => 'array',
    ];

    protected function apiPayload(): Attribute
    {
        return Attribute::make(
            get: fn () => [
                'id' => $this->id,
                'title' => $this->title,
                'type' => $this->type,
                'start_at' => $this->start_at?->toIso8601String(),
                'end_at' => $this->end_at?->toIso8601String(),
                'day' => $this->start_at?->day,
                'color' => $this->color,
                'icon' => $this->icon,
                'participants' => $this->participants ?? [],
            ],
        );
    }
}
