<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    protected $table = 'enrollments';
    protected $primaryKey = 'enrollment_id';

    // The enrollments table only has date_enrolled (DB default CURRENT_TIMESTAMP).
    public $timestamps = false;

    protected $fillable = [
        'student_id', 'section_id', 'school_year', 'school_year_id', 'semester', 'date_enrolled', 'status',
    ];

    protected $casts = [
        'date_enrolled' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function section()
    {
        return $this->belongsTo(ClassSection::class, 'section_id');
    }

    public function schoolYear()
    {
        return $this->belongsTo(SchoolYear::class, 'school_year_id');
    }
}
