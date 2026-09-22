<?php

namespace App\Services\Accounts;

use App\Models\Account;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RegistrationService
{
    public function registerStudent(array $data): Account
    {
        return DB::transaction(function () use ($data) {
            $student = Student::create([
                'lrn'            => !empty($data['lrn']) ? $data['lrn'] : $this->generateLrn(),
                'student_number' => $this->generateStudentNumber(),
                'first_name'     => $data['first_name'],
                'middle_name'    => $data['middle_name'] ?? null,
                'last_name'      => $data['last_name'],
                'gender'         => $data['gender'] ?? 'Male',
                'birthdate'      => $data['birthdate'],
                'address'        => $data['address'] ?? 'Not provided',
                'contact_number' => $data['contact_number'] ?? 'Not provided',
                'email'          => $data['email'],
                'grade_level'    => $data['grade_level'] ?? '11',
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
'must_change_password' => true,
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
                'must_change_password' => true,
                'password_changed_at'  => now(),
            ]);
        });
    }

    public function registerAdmin(array $data): Account
    {
        return DB::transaction(function () use ($data) {
            // Create a User record for the admin
            $user = User::create([
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'role' => 'Admin',
                'status' => 'Active',
                'is_deleted' => false,
                'first_name' => null,
                'last_name' => null,
                'middle_name' => null,
                'contact_number' => null,
                'address' => null,
                'birthdate' => null,
                'gender' => null,
            ]);

            // Create the Account record linked to the user
            return Account::create([
                'user_id' => $user->user_id,
                'entity_id' => null,
                'entity_type' => Account::ENTITY_TYPE_ADMIN,
                'username' => $data['username'],
                'password_hash' => Hash::make($data['password']),
                'recovery_email' => $data['email'],
                'status' => Account::STATUS_ACTIVE,
                'is_active' => true,
                'must_change_password' => true,
                'password_changed_at' => now(),
                'created_by' => auth()->guard('admin')->id(),
                'updated_by' => auth()->guard('admin')->id(),
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