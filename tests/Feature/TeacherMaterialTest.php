<?php

namespace Tests\Feature;

use App\Models\ClassSection;
use App\Models\LearningMaterial;
use App\Models\Schedule;
use App\Models\Strand;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TeacherMaterialTest extends TestCase
{
    use RefreshDatabase;

    private function actingTeacher(): array
    {
        $user = User::create([
            'first_name' => 'Test', 'last_name' => 'Teacher', 'email' => uniqid() . '@example.com',
            'password' => Hash::make('password'), 'must_change_password' => false,
            'role' => 'Teacher', 'status' => 'Active',
        ]);
        $teacher = Teacher::create(['user_id' => $user->user_id, 'teacher_number' => 'T-' . uniqid(), 'specialization' => 'Mathematics']);

        $trackId = DB::table('tracks')->insertGetId(['track_code' => 'ACAD-' . uniqid(), 'track_name' => 'Academic', 'created_at' => now()]);
        $strand = Strand::create(['track_id' => $trackId, 'strand_code' => 'STEM-' . uniqid(), 'strand_name' => 'STEM']);
        $section = ClassSection::create([
            'strand_id' => $strand->strand_id, 'grade_level' => '11', 'section_name' => uniqid(),
            'school_year' => '2025-2026', 'max_slots' => 40, 'status' => 'Open',
        ]);
        $roomId = DB::table('rooms')->insertGetId(['room_name' => 'Room-' . uniqid(), 'building' => 'Main', 'capacity' => 40, 'created_at' => now()]);
        $subject = Subject::create([
            'strand_id' => $strand->strand_id, 'subject_code' => 'MATH1-' . uniqid(), 'subject_name' => 'Math',
            'subject_type' => 'Core', 'grade_level' => '11', 'semester' => '1st Semester', 'units' => 1.0,
        ]);
        $schedule = Schedule::create([
            'section_id' => $section->section_id, 'subject_id' => $subject->subject_id,
            'teacher_id' => $teacher->teacher_id, 'room_id' => $roomId,
            'day_of_week' => 'Monday', 'start_time' => '08:00:00', 'end_time' => '09:00:00',
        ]);

        return [$user, $teacher, $schedule];
    }

    public function test_teacher_can_upload_material_to_own_schedule(): void
    {
        Storage::fake('local');
        [$user, , $schedule] = $this->actingTeacher();

        $response = $this->actingAs($user)->post('/teacher/materials', [
            'schedule_id' => $schedule->schedule_id,
            'title' => 'Module 1',
            'status' => 'Draft',
            'file' => UploadedFile::fake()->create('module1.pdf', 100),
        ]);

        $response->assertRedirect(route('teacher.materials.index'));
        $this->assertDatabaseHas('learning_materials', ['schedule_id' => $schedule->schedule_id, 'title' => 'Module 1', 'status' => 'Draft']);
        Storage::disk('local')->assertExists(LearningMaterial::first()->file_url);
    }

    public function test_teacher_cannot_upload_material_to_another_teachers_schedule(): void
    {
        Storage::fake('local');
        [$user, , ] = $this->actingTeacher();
        [, , $otherSchedule] = $this->actingTeacher();

        $response = $this->actingAs($user)->post('/teacher/materials', [
            'schedule_id' => $otherSchedule->schedule_id,
            'title' => 'Hijack',
            'status' => 'Draft',
            'file' => UploadedFile::fake()->create('hijack.pdf', 10),
        ]);

        $response->assertForbidden();
        $this->assertDatabaseMissing('learning_materials', ['title' => 'Hijack']);
    }

    public function test_teacher_cannot_update_or_delete_another_teachers_material(): void
    {
        Storage::fake('local');
        [, $otherTeacher, $otherSchedule] = $this->actingTeacher();
        [$user] = $this->actingTeacher();

        $material = LearningMaterial::create([
            'schedule_id' => $otherSchedule->schedule_id, 'title' => 'Not yours', 'status' => 'Draft',
            'file_url' => 'learning_materials/1/file.pdf', 'uploaded_by' => $otherTeacher->user_id,
        ]);

        $this->actingAs($user)->put("/teacher/materials/{$material->material_id}", [
            'title' => 'Hijacked', 'status' => 'Published',
        ])->assertForbidden();

        $this->actingAs($user)->delete("/teacher/materials/{$material->material_id}")->assertForbidden();
    }

    public function test_teacher_can_update_and_delete_own_material(): void
    {
        Storage::fake('local');
        [$user, $teacher, $schedule] = $this->actingTeacher();

        $material = LearningMaterial::create([
            'schedule_id' => $schedule->schedule_id, 'title' => 'Old title', 'status' => 'Draft',
            'file_url' => 'learning_materials/1/file.pdf', 'uploaded_by' => $user->user_id,
        ]);

        $this->actingAs($user)->put("/teacher/materials/{$material->material_id}", [
            'title' => 'New title', 'status' => 'Published',
        ])->assertRedirect(route('teacher.materials.index'));

        $this->assertDatabaseHas('learning_materials', ['material_id' => $material->material_id, 'title' => 'New title', 'status' => 'Published']);

        $this->actingAs($user)->delete("/teacher/materials/{$material->material_id}")->assertRedirect(route('teacher.materials.index'));
        $this->assertDatabaseMissing('learning_materials', ['material_id' => $material->material_id]);
    }

    public function test_teacher_can_preview_own_material(): void
    {
        Storage::fake('local');
        [$user, , $schedule] = $this->actingTeacher();

        Storage::disk('local')->put('learning_materials/1/file.pdf', 'content');
        $material = LearningMaterial::create([
            'schedule_id' => $schedule->schedule_id, 'title' => 'My Material', 'status' => 'Draft',
            'file_url' => 'learning_materials/1/file.pdf', 'uploaded_by' => $user->user_id,
        ]);

        $this->actingAs($user)->get("/materials/{$material->material_id}/preview")->assertOk();
    }

    public function test_teacher_cannot_preview_another_teachers_material(): void
    {
        Storage::fake('local');
        [, $otherTeacher, $otherSchedule] = $this->actingTeacher();
        [$user] = $this->actingTeacher();

        Storage::disk('local')->put('learning_materials/1/file.pdf', 'content');
        $material = LearningMaterial::create([
            'schedule_id' => $otherSchedule->schedule_id, 'title' => 'Not yours', 'status' => 'Draft',
            'file_url' => 'learning_materials/1/file.pdf', 'uploaded_by' => $otherTeacher->user_id,
        ]);

        $this->actingAs($user)->get("/materials/{$material->material_id}/preview")->assertForbidden();
    }

    public function test_non_teacher_role_is_forbidden_from_teacher_materials(): void
    {
        $admin = User::create([
            'first_name' => 'Admin', 'last_name' => 'User', 'email' => 'admin@example.com',
            'password' => Hash::make('password'), 'must_change_password' => false,
            'role' => 'Admin', 'status' => 'Active',
        ]);

        $this->actingAs($admin)->get('/teacher/materials')->assertForbidden();
    }
}
