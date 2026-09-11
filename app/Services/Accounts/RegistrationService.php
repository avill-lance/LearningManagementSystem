<?php

namespace App\Services\Accounts;

use App\Models\Account;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RegistrationService
{
    public function registerStudent(array $data): Account
    {
        return DB::transaction(function () use ($data) {
            $student = Student::create([
                'lrn'            => $this->generateLrn(),
                'student_number' => $this->generateStudentNumber(),
                'first_name'     => $data['first_name'],
                'middle_name'    => $data['middle_name'] ?? null,
                'last_name'      => $data['last_name'],
                'gender'         => $data['gender'],
                'birthdate'      => $data['birthdate'],
                'address'        => $data['address'],
                'contact_number' => $data['phone'],
                'email'          => $data['email'],
                'grade_level'    => $data['grade_level'],
                'status'         => 'Active',
                'emergency_contact_name'         => '(To be updated)',
                'emergency_contact_relationship' => '(To be updated)',
                'emergency_contact_number'       => '(To be updated)',
            ]);

            return Account::create([
                'entity_id'            => $student->student_id,
                'entity_type'          => Account::ENTITY_TYPE_STUDENT,
                'username'             => $data['username'],
                'password_hash'        => Hash::make($data['password']),
                'recovery_email'       => $data['email'],
                'status'               => Account::STATUS_ACTIVE,
                'is_active'            => true,
                'must_change_password' => false,
                'password_changed_at'  => now(),
            ]);
        });
    }

    public function registerTeacher(array $data): Account
    {
        return DB::transaction(function () use ($data) {
            $teacher = Teacher::create([
                'teacher_number' => $this->generateTeacherNumber(),
                'first_name'     => $data['first_name'],
                'last_name'      => $data['last_name'],
                'email'          => $data['email'],
                'contact_number' => $data['phone'],
                'specialization' => $data['specialization'] ?? null,
                'status'         => 'Active',
            ]);

            return Account::create([
                'entity_id'            => $teacher->teacher_id,
                'entity_type'          => Account::ENTITY_TYPE_TEACHER,
                'username'             => $data['username'],
                'password_hash'        => Hash::make($data['password']),
                'recovery_email'       => $data['email'],
                'status'               => Account::STATUS_ACTIVE,
                'is_active'            => true,
                'must_change_password' => false,
                'password_changed_at'  => now(),
            ]);
        });
    }

    private function generateLrn(): string
    {
        do {
            $lrn = 'PEND' . strtoupper(bin2hex(random_bytes(4)));
        } while (Student::where('lrn', $lrn)->exists());

        return $lrn;
    }

    private function generateStudentNumber(): string
    {
        $year = now()->year;
        do {
            $candidate = $year . '-' . str_pad((string) random_int(0, 99999), 5, '0', STR_PAD_LEFT);
        } while (Student::where('student_number', $candidate)->exists());

        return $candidate;
    }

    private function generateTeacherNumber(): string
    {
        $year = now()->year;
        do {
            $candidate = 'TCH-' . $year . '-' . str_pad((string) random_int(0, 9999), 4, '0', STR_PAD_LEFT);
        } while (Teacher::where('teacher_number', $candidate)->exists());

        return $candidate;
    }
}