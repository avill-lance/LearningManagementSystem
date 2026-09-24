<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class LearningMaterial extends Model
{
    protected $table = 'learning_materials';
    protected $primaryKey = 'material_id';

    // The learning_materials table only has uploaded_at (no updated_at).
    const CREATED_AT = 'uploaded_at';
    const UPDATED_AT = null;

    protected $fillable = [
        'schedule_id', 'title', 'file_url', 'status', 'uploaded_by',
    ];

    protected $casts = [
        'uploaded_at' => 'datetime',
    ];

    public function schedule()
    {
        return $this->belongsTo(Schedule::class, 'schedule_id');
    }

    public function uploadedBy()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Materials belonging to any of the given teacher's schedules — feeds
     * the teacher's Learning Materials list.
     */
    public function scopeForTeacher(Builder $query, int $teacherId): Builder
    {
        return $query->whereHas('schedule', fn (Builder $q) => $q->where('teacher_id', $teacherId));
    }

    /**
     * Published materials belonging to schedules in the given section — feeds
     * the student's Learning Materials list. Draft/Archived stay teacher-only.
     */
    public function scopeVisibleToSection(Builder $query, int $sectionId): Builder
    {
        return $query
            ->where('status', 'Published')
            ->whereHas('schedule', fn (Builder $q) => $q->where('section_id', $sectionId));
    }
}
