<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassSection extends Model
{
    protected $table = 'class_sections';
    protected $primaryKey = 'section_id';

    // The class_sections table only has created_at.
    const UPDATED_AT = null;

    protected $fillable = [
        'strand_id', 'adviser_id', 'grade_level', 'section_name', 'school_year', 'max_slots', 'status',
    ];

    public function strand()
    {
        return $this->belongsTo(Strand::class, 'strand_id');
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'section_id');
    }
}
