<?php

namespace App\Services\Calendar;

use App\Models\Assignment;
use App\Models\Quiz;
use App\Models\ScheduleEvent;
use App\Models\Student;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class CalendarEventService
{
    /**
     * Build the merged calendar feed for a student within [$from, $to]: their
     * own/section-scoped schedule events, plus assignment and quiz due dates
     * for their active section — normalized into one shape so the front-end
     * calendar doesn't need to know about three different source tables.
     *
     * See modules/09-calendar-events.md and modules/16-student-dashboard.md
     * for the section-scoping rules this reuses.
     */
    public function feedFor(Student $student, Carbon $from, Carbon $to): Collection
    {
        $sectionId = $student->activeEnrollment?->section_id;

        $events = ScheduleEvent::forStudentCalendar($student->student_id, $sectionId, $from, $to)
            ->get()
            ->map(fn (ScheduleEvent $event) => [
                'id' => 'event-' . $event->event_id,
                'title' => $event->title,
                'start' => $event->start_datetime,
                'end' => $event->end_datetime,
                'classNames' => ['fc-event--personal'],
                'extendedProps' => [
                    'source' => 'event',
                    'subject_name' => $event->subject?->subject_name,
                    'description' => $event->description,
                    'editable' => $event->created_by_role === 'Student' && $event->created_by_id === $student->student_id,
                ],
            ]);

        if ($sectionId === null) {
            return $events->values();
        }

        $assignments = Assignment::forSectionCalendar($sectionId)
            ->whereBetween('due_date', [$from, $to])
            ->with('schedule.subject')
            ->get()
            ->map(fn (Assignment $assignment) => [
                'id' => 'assignment-' . $assignment->assignment_id,
                'title' => $assignment->title,
                'start' => $assignment->due_date,
                'end' => $assignment->due_date,
                'url' => route('student.assignments.index'),
                'classNames' => ['fc-event--assignment'],
                'extendedProps' => [
                    'source' => 'assignment',
                    'subject_name' => $assignment->schedule?->subject?->subject_name,
                    'editable' => false,
                ],
            ]);

        $quizzes = Quiz::forSectionCalendar($sectionId)
            ->whereBetween('due_date', [$from, $to])
            ->with('schedule.subject')
            ->get()
            ->map(fn (Quiz $quiz) => [
                'id' => 'quiz-' . $quiz->quiz_id,
                'title' => $quiz->title,
                'start' => $quiz->due_date,
                'end' => $quiz->due_date,
                'url' => route('student.quizzes.index'),
                'classNames' => ['fc-event--quiz'],
                'extendedProps' => [
                    'source' => 'quiz',
                    'subject_name' => $quiz->schedule?->subject?->subject_name,
                    'editable' => false,
                ],
            ]);

        return $events->concat($assignments)->concat($quizzes)->values();
    }
}
