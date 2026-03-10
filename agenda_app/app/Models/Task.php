<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Task extends Model
{
    protected $fillable = [
        'title',
        'type',
        'time_label',
        'due_date',
        'start_time',
        'end_time',
        'color',
        'icon',
        'participants',
        'completed',
    ];

    protected $casts = [
        'due_date' => 'date',
        'participants' => 'array',
        'completed' => 'boolean',
    ];

    protected function apiPayload(): Attribute
    {
        return Attribute::make(
            get: fn () => [
                'id' => $this->id,
                'title' => $this->title,
                'type' => $this->type,
                'time' => $this->time_label,
                'due_date' => $this->due_date?->toDateString(),
                'start_time' => $this->start_time,
                'end_time' => $this->end_time,
                'color' => $this->color,
                'icon' => $this->icon,
                'participants' => $this->participants ?? [],
                'completed' => $this->completed,
            ],
        );
    }
}
