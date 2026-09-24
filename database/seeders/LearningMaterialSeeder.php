<?php

namespace Database\Seeders;

use App\Models\LearningMaterial;
use App\Models\Teacher;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

/**
 * Placeholder learning materials so the demo teacher ("teacher@school.com")
 * has something to manage and the demo student ("student@school.com") has
 * something to view/download. Reuses the demo schedule set up by
 * AssignmentSeeder, and assigns the demo teacher to it if it has none.
 *
 * Safe to re-run: uses firstOrCreate and only assigns the teacher if the
 * schedule doesn't already have one.
 */
class LearningMaterialSeeder extends Seeder
{
    private const DISK = 'local';

    public function run(): void
    {
        $schedule = (new AssignmentSeeder())->demoSchedule();

        $teacherUser = \App\Models\User::where('email', 'teacher@school.com')->first();
        $teacher = $teacherUser ? Teacher::where('user_id', $teacherUser->user_id)->first() : null;

        if ($teacher && $schedule->teacher_id === null) {
            $schedule->update(['teacher_id' => $teacher->teacher_id]);
        }

        if (! $teacher) {
            return;
        }

        $materials = [
            ['title' => 'Week 1 Lecture Notes', 'status' => 'Published', 'content' => 'Placeholder lecture notes for Week 1.'],
            ['title' => 'Week 2 Draft Handout', 'status' => 'Draft', 'content' => 'Placeholder draft handout for Week 2 (not yet published).'],
        ];

        foreach ($materials as $material) {
            $existing = LearningMaterial::where('schedule_id', $schedule->schedule_id)
                ->where('title', $material['title'])
                ->first();

            if ($existing) {
                continue;
            }

            $path = 'learning_materials/' . $schedule->schedule_id . '/' . \Illuminate\Support\Str::slug($material['title']) . '.txt';
            Storage::disk(self::DISK)->put($path, $material['content']);

            LearningMaterial::create([
                'schedule_id' => $schedule->schedule_id,
                'title' => $material['title'],
                'status' => $material['status'],
                'file_url' => $path,
                'uploaded_by' => $teacherUser->user_id,
            ]);
        }
    }
}
