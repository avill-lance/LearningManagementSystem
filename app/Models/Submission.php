<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    protected $table = 'submissions';
    protected $primaryKey = 'submission_id';

    // The submissions table has no created_at/updated_at columns.
    public $timestamps = false;

    protected $fillable = [
        'assignment_id', 'student_id', 'submitted_at', 'file_url', 'score', 'status',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
    ];

    public function assignment()
    {
        return $this->belongsTo(Assignment::class, 'assignment_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    /**
     * Submissions turned in ('Submitted' or 'Late') but not yet graded, for
     * assignments belonging to the given teacher's schedules — feeds the
     * teacher dashboard's "Pending Grading" tile.
     */
    public function scopeAwaitingGradingForTeacher(Builder $query, int $teacherId): Builder
    {
        return $query
            ->whereIn('status', ['Submitted', 'Late'])
            ->whereHas('assignment.schedule', fn (Builder $q) => $q->where('teacher_id', $teacherId));
    }
}
