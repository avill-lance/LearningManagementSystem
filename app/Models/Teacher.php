<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $table      = 'teachers';
    protected $primaryKey = 'teacher_id';

    protected $fillable = [
        'teacher_number', 'user_id', 'first_name', 'last_name', 'email',
        'contact_number', 'specialization', 'status',
    ];

    public function account()
    {
        return $this->hasOne(Account::class, 'entity_id', 'teacher_id')
                    ->where('entity_type', Account::ENTITY_TYPE_TEACHER);
    }
}