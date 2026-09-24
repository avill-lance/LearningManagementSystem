<?php

namespace Database\Seeders;

use App\Models\Assignment;
use App\Models\ClassSection;
use App\Models\Enrollment;
use App\Models\Schedule;
use App\Models\SchoolYear;
use App\Models\Strand;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Placeholder assignment data so the student calendar (Module 9) has something
 * to render. Builds the minimal section/subject/schedule chain it depends on,
 * then enrolls the demo student ("student@school.com") into it.
 *
 * Safe to re-run: every step uses firstOrCreate/updateOrCreate.
 */
class AssignmentSeeder extends Seeder
{
    public const DEMO_SUBJECT_CODE = 'CAL101';

    public function run(): void
    {
        $schedule = $this->demoSchedule();

        $dueDates = [
            now()->subDays(3),
            now()->addDays(2),
            now()->addDays(5),
            now()->addDays(9),
            now()->addDays(14),
        ];

        $titles = [
            'Essay: My Summer Vacation',
            'Problem Set 3: Linear Equations',
            'Lab Report: Photosynthesis',
            'Research Outline Draft',
            'Group Project Proposal',
        ];

        foreach ($titles as $index => $title) {
            Assignment::firstOrCreate(
                ['schedule_id' => $schedule->schedule_id, 'title' => $title],
                [
                    'instructions' => 'Placeholder instructions for ' . $title . '.',
                    'due_date' => $dueDates[$index],
                    'max_score' => 100,
                ]
            );
        }
    }

    /**
     * Ensures a minimal demo section/subject/schedule chain exists and that
     * the demo student is enrolled in it, then returns the demo Schedule.
     */
    public function demoSchedule(): Schedule
    {
        $trackId = DB::table('tracks')->where('track_code', 'ACAD')->value('track_id')
            ?? DB::table('tracks')->insertGetId([
                'track_code' => 'ACAD', 'track_name' => 'Academic', 'created_at' => now(),
            ]);

        $strand = Strand::firstOrCreate(
            ['strand_code' => 'STEM'],
            ['track_id' => $trackId, 'strand_name' => 'Science, Technology, Engineering and Mathematics']
        );

        $schoolYear = SchoolYear::firstOrCreate(
            ['year' => '2025-2026'],
            ['status' => 'active']
        );

        $section = ClassSection::firstOrCreate(
            ['section_name' => 'Calendar Demo', 'school_year' => '2025-2026'],
            [
                'strand_id' => $strand->strand_id, 'grade_level' => '11',
                'max_slots' => 40, 'status' => 'Open',
            ]
        );

        $roomId = DB::table('rooms')->where('room_name', 'Room 1')->value('room_id')
            ?? DB::table('rooms')->insertGetId([
                'room_name' => 'Room 1', 'building' => 'Main', 'capacity' => 40, 'created_at' => now(),
            ]);

        $subject = Subject::firstOrCreate(
            ['subject_code' => self::DEMO_SUBJECT_CODE],
            [
                'strand_id' => $strand->strand_id, 'subject_name' => 'Calendar Demo Subject',
                'subject_type' => 'Core', 'grade_level' => '11', 'semester' => '1st Semester', 'units' => 1.0,
            ]
        );

        $schedule = Schedule::firstOrCreate(
            ['section_id' => $section->section_id, 'subject_id' => $subject->subject_id],
            [
                'room_id' => $roomId, 'day_of_week' => 'Monday',
                'start_time' => '08:00:00', 'end_time' => '09:00:00',
            ]
        );

        $student = Student::where('user_id', 30)->first();
        if ($student) {
            if ($student->strand_id !== $strand->strand_id) {
                $student->update(['strand_id' => $strand->strand_id]);
            }

            Enrollment::firstOrCreate(
                ['student_id' => $student->student_id, 'section_id' => $section->section_id, 'semester' => '1st Semester'],
                ['school_year' => '2025-2026', 'school_year_id' => $schoolYear->school_year_id, 'status' => 'Enrolled']
            );
        }

        return $schedule;
    }
}
