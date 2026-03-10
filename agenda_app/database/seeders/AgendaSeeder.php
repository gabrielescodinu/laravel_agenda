<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Task;
use Illuminate\Database\Seeder;

class AgendaSeeder extends Seeder
{
    public function run(): void
    {
        Event::query()->delete();
        Task::query()->delete();

        Event::insert([
            [
                'title' => 'Design Review',
                'type' => 'Creativita',
                'start_at' => now()->startOfMonth()->addDays(3)->setTime(10, 0),
                'end_at' => now()->startOfMonth()->addDays(3)->setTime(11, 30),
                'color' => 'indigo',
                'icon' => 'palette',
                'participants' => json_encode([
                    ['name' => 'Alex', 'avatar' => 'https://i.pravatar.cc/80?img=12'],
                    ['name' => 'Marta', 'avatar' => 'https://i.pravatar.cc/80?img=22'],
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Lezione Inglese',
                'type' => 'Lezione Privata',
                'start_at' => now()->startOfMonth()->addDays(10)->setTime(14, 0),
                'end_at' => now()->startOfMonth()->addDays(10)->setTime(15, 30),
                'color' => 'lime',
                'icon' => 'book-open',
                'participants' => json_encode([
                    ['name' => 'Alex', 'avatar' => 'https://i.pravatar.cc/80?img=12'],
                    ['name' => 'John', 'avatar' => 'https://i.pravatar.cc/80?img=33'],
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Scrum Daily',
                'type' => 'Management',
                'start_at' => now()->startOfMonth()->addDays(17)->setTime(9, 0),
                'end_at' => now()->startOfMonth()->addDays(17)->setTime(9, 15),
                'color' => 'blue',
                'icon' => 'clock-3',
                'participants' => json_encode([
                    ['name' => 'Team', 'avatar' => 'https://i.pravatar.cc/80?img=51'],
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        Task::insert([
            [
                'title' => 'Daily Workout',
                'type' => 'Benessere',
                'time_label' => '06:00',
                'due_date' => now()->startOfMonth()->addDays(3)->toDateString(),
                'start_time' => '06:00',
                'end_time' => '07:00',
                'color' => 'blue',
                'icon' => 'clock-3',
                'participants' => json_encode([
                    ['name' => 'Alex', 'avatar' => 'https://i.pravatar.cc/80?img=12'],
                ]),
                'completed' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Reading Books',
                'type' => 'Crescita Personale',
                'time_label' => '21:00',
                'due_date' => now()->startOfMonth()->addDays(10)->toDateString(),
                'start_time' => '21:00',
                'end_time' => '22:00',
                'color' => 'indigo',
                'icon' => 'book-open',
                'participants' => json_encode([
                    ['name' => 'Alex', 'avatar' => 'https://i.pravatar.cc/80?img=12'],
                    ['name' => 'Marta', 'avatar' => 'https://i.pravatar.cc/80?img=22'],
                ]),
                'completed' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'System Update',
                'type' => 'Operativo',
                'time_label' => '14:00',
                'due_date' => now()->startOfMonth()->addDays(17)->toDateString(),
                'start_time' => '14:00',
                'end_time' => '15:00',
                'color' => 'lime',
                'icon' => 'palette',
                'participants' => json_encode([
                    ['name' => 'Alex', 'avatar' => 'https://i.pravatar.cc/80?img=12'],
                    ['name' => 'John', 'avatar' => 'https://i.pravatar.cc/80?img=33'],
                ]),
                'completed' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Client Call',
                'type' => 'Commerciale',
                'time_label' => '16:30',
                'due_date' => now()->startOfMonth()->addDays(22)->toDateString(),
                'start_time' => '16:30',
                'end_time' => '17:30',
                'color' => 'indigo',
                'icon' => 'clock-3',
                'participants' => json_encode([
                    ['name' => 'Alex', 'avatar' => 'https://i.pravatar.cc/80?img=12'],
                    ['name' => 'Sara', 'avatar' => 'https://i.pravatar.cc/80?img=44'],
                ]),
                'completed' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
