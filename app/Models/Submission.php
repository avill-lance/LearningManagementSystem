<?php

namespace App\Models;

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
}
