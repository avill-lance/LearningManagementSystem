<?php

namespace Database\Seeders;

use App\Models\Quiz;
use Illuminate\Database\Seeder;

/**
 * Placeholder quiz data so the student calendar (Module 9) has something to
 * render. Reuses the demo schedule set up by AssignmentSeeder.
 *
 * Safe to re-run: uses firstOrCreate.
 */
class QuizSeeder extends Seeder
{
    public function run(): void
    {
        $schedule = (new AssignmentSeeder())->demoSchedule();

        $dueDates = [
            now()->addDays(1),
            now()->addDays(6),
            now()->addDays(11),
            now()->addDays(16),
        ];

        $titles = [
            'Quiz 1: Vocabulary Check',
            'Quiz 2: Chapter 3 Review',
            'Quiz 3: Formulas and Units',
            'Quiz 4: Midterm Practice',
        ];

        foreach ($titles as $index => $title) {
            Quiz::firstOrCreate(
                ['schedule_id' => $schedule->schedule_id, 'title' => $title],
                [
                    'time_limit_minutes' => 20,
                    'due_date' => $dueDates[$index],
                ]
            );
        }
    }
}
