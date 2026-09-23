<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $table = 'announcements';
    protected $primaryKey = 'announcement_id';

    // The announcements table only has posted_at (no created_at/updated_at).
    public $timestamps = false;

    protected $fillable = [
        'posted_by', 'section_id', 'title', 'body', 'posted_at',
    ];

    protected $casts = [
        'posted_at' => 'datetime',
    ];

    public function postedBy()
    {
        return $this->belongsTo(User::class, 'posted_by');
    }

    public function section()
    {
        return $this->belongsTo(ClassSection::class, 'section_id');
    }

    /**
     * School-wide announcements (section_id null) plus any targeted at the given section.
     */
    public function scopeVisibleToSection(Builder $query, int $sectionId): Builder
    {
        return $query->where(function (Builder $q) use ($sectionId) {
            $q->whereNull('section_id')->orWhere('section_id', $sectionId);
        });
    }
}
