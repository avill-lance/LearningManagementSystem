<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ScheduleEvent extends Model
{
    protected $table = 'schedule_events';
    protected $primaryKey = 'event_id';

    // The schedule_events table only has created_at.
    const UPDATED_AT = null;

    protected $fillable = [
        'created_by_role', 'created_by_id', 'section_id', 'subject_id',
        'title', 'description', 'event_type', 'start_datetime', 'end_datetime', 'status',
    ];

    protected $casts = [
        'start_datetime' => 'datetime',
        'end_datetime' => 'datetime',
        'created_at' => 'datetime',
    ];

    public function section()
    {
        return $this->belongsTo(ClassSection::class, 'section_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    /**
     * Scheduled, not-yet-started events visible to a student: their own personal
     * events, plus any events scoped to their active section.
     */
    public function scopeUpcomingForStudent(Builder $query, int $studentId, ?int $sectionId): Builder
    {
        return $query
            ->where('status', 'Scheduled')
            ->where('start_datetime', '>=', now())
            ->where(function (Builder $q) use ($studentId, $sectionId) {
                $q->where(function (Builder $q2) use ($studentId) {
                    $q2->where('created_by_role', 'Student')->where('created_by_id', $studentId);
                });

                if ($sectionId !== null) {
                    $q->orWhere('section_id', $sectionId);
                }
            });
    }

    /**
     * Events visible to a student within an arbitrary date range: their own
     * personal events, plus any events scoped to their active section. Unlike
     * upcomingForStudent() (fixed 14-day dashboard widget), this powers the
     * full calendar's month/week navigation.
     */
    public function scopeForStudentCalendar(Builder $query, int $studentId, ?int $sectionId, $from, $to): Builder
    {
        return $query
            ->where('start_datetime', '<=', $to)
            ->where('end_datetime', '>=', $from)
            ->where(function (Builder $q) use ($studentId, $sectionId) {
                $q->where(function (Builder $q2) use ($studentId) {
                    $q2->where('created_by_role', 'Student')->where('created_by_id', $studentId);
                });

                if ($sectionId !== null) {
                    $q->orWhere('section_id', $sectionId);
                }
            });
    }
}
