<?php

namespace App\Services\Dashboard;

use App\Models\Announcement;
use App\Models\Assignment;
use App\Models\Quiz;
use App\Models\ScheduleEvent;
use App\Models\Student;

class StudentDashboardService
{
    /** Upcoming events window and cap — see modules/16-student-dashboard.md Definitions. */
    private const UPCOMING_EVENTS_LIMIT = 5;
    private const UPCOMING_EVENTS_WITHIN_DAYS = 14;

    /**
     * Build the summary data for the student dashboard: pending assignment/quiz
     * counts, the next few upcoming calendar events, and the latest announcement.
     *
     * All scoping (section, visibility) is delegated to Eloquent scopes on the
     * respective models so the same logic is reusable by the real Assignments,
     * Quizzes, and Calendar modules later.
     */
    public function summaryFor(Student $student): array
    {
        $sectionId = $student->activeEnrollment?->section_id;

        return [
            'pendingAssignmentsCount' => $sectionId
                ? Assignment::pendingForStudent($student->student_id, $sectionId)->count()
                : 0,
            'pendingQuizzesCount' => $sectionId
                ? Quiz::pendingForStudent($student->student_id, $sectionId)->count()
                : 0,
            'upcomingEvents' => ScheduleEvent::upcomingForStudent($student->student_id, $sectionId)
                ->where('start_datetime', '<=', now()->addDays(self::UPCOMING_EVENTS_WITHIN_DAYS))
                ->orderBy('start_datetime')
                ->limit(self::UPCOMING_EVENTS_LIMIT)
                ->get(),
            'latestAnnouncement' => $sectionId
                ? Announcement::visibleToSection($sectionId)->latest('posted_at')->first()
                : Announcement::whereNull('section_id')->latest('posted_at')->first(),
        ];
    }
}
