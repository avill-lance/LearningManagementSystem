<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $table = 'schedules';
    protected $primaryKey = 'schedule_id';

    // The schedules table only has created_at.
    const UPDATED_AT = null;

    protected $fillable = [
        'section_id', 'subject_id', 'teacher_id', 'room_id',
        'day_of_week', 'start_time', 'end_time',
    ];

    protected $casts = [
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

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class, 'schedule_id');
    }

    public function quizzes()
    {
        return $this->hasMany(Quiz::class, 'schedule_id');
    }
}
