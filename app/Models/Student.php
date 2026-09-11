<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $table      = 'students';
    protected $primaryKey = 'student_id';

    protected $fillable = [
        'user_id',
        'lrn', 'student_number', 'first_name', 'middle_name', 'last_name',
        'gender', 'birthdate', 'address', 'bio', 'contact_number', 'email',
        'grade_level', 'status',
        'father_name', 'father_contact_number', 'father_occupation',
        'mother_name', 'mother_contact_number', 'mother_occupation',
        'guardian_name', 'guardian_relationship', 'guardian_contact_number',
        'guardian_address',
        'emergency_contact_name', 'emergency_contact_relationship',
        'emergency_contact_number',
    ];

    protected $casts = [
        'birthdate' => 'date',
    ];

    public function account()
    {
        return $this->hasOne(Account::class, 'entity_id', 'student_id')
                    ->where('entity_type', Account::ENTITY_TYPE_STUDENT);
    }
}