<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizAttempt extends Model
{
    protected $table = 'quiz_attempts';
    protected $primaryKey = 'attempt_id';

    // The quiz_attempts table has no updated_at column; started_at/submitted_at are tracked explicitly.
    public $timestamps = false;

    protected $fillable = [
        'quiz_id', 'student_id', 'score', 'started_at', 'submitted_at',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'submitted_at' => 'datetime',
    ];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class, 'quiz_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}
