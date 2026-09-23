<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Strand extends Model
{
    protected $table = 'strands';
    protected $primaryKey = 'strand_id';

    protected $fillable = [
        'track_id', 'strand_code', 'strand_name', 'description',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function track()
    {
        return $this->belongsTo(Track::class, 'track_id');
    }

    public function subjects()
    {
        return $this->hasMany(Subject::class, 'strand_id');
    }
}
