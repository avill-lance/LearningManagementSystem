<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $table = 'subjects';
    protected $primaryKey = 'subject_id';

    // The subjects table only has created_at.
    const UPDATED_AT = null;

    protected $fillable = [
        'strand_id', 'teacher_id', 'subject_code', 'subject_name', 'subject_type',
        'grade_level', 'semester', 'units', 'description', 'status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function strand()
    {
        return $this->belongsTo(\App\Models\Strand::class, 'strand_id');
    }

    public function teacher()
    {
        return $this->belongsTo(\App\Models\Teacher::class, 'teacher_id');
    }
}
