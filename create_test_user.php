<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Http\Kernel')->handle(
    $request = Illuminate\Http\Request::capture()
);

use App\Models\User;
use App\Models\Account;
use App\Models\Student;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

// Check if test user already exists
$existingUser = User::where('email', 'test@example.com')->first();
if (!$existingUser) {
    // Check if student with this LRN already exists
    $existingStudent = Student::where('lrn', '123456789012')->first();
    if (!$existingStudent) {
        // Create student record first
        $student = new Student();
        $student->first_name = 'Test';
        $student->last_name = 'User';
        $student->lrn = '123456789012';
        $student->student_number = '20260001';
        $student->gender = 'Male';
        $student->birthdate = '2008-01-01';
        $student->address = 'Test Address';
        $student->contact_number = '1234567890';
        $student->email = 'test@example.com';
        $student->grade_level = '11';
        $student->status = 'Active';
        $student->emergency_contact_name = 'Emergency Contact';
        $student->emergency_contact_relationship = 'Parent';
        $student->emergency_contact_number = '0987654321';
        $student->save();
    } else {
        $student = $existingStudent;
    }

    // Create test user using query builder to avoid timestamp issues
    $userId = DB::table('users')->insertGetId([
        'full_name' => 'Test User',
        'email' => 'test@example.com',
        'password_hash' => Hash::make('TempPass123!'),
        'role' => 'Student',
        'created_at' => now()
        // Note: users table doesn't have updated_at column
    ]);

    // Create associated account
    $account = new Account();
    $account->user_id = $userId;
    $account->entity_id = $student->student_id;
    $account->entity_type = 'student';
    $account->username = 'testuser';
    $account->password_hash = Hash::make('TempPass123!');
    $account->must_change_password = true;
    $account->status = 'Active';
    $account->is_active = true;
    $account->save();

    echo 'Created test user with ID: ' . $userId . PHP_EOL;
    echo 'Test account ID: ' . $account->account_id . PHP_EOL;
} else {
    echo 'Test user already exists with ID: ' . $existingUser->user_id . PHP_EOL;
    // Update must_change_password to true if needed
    $account = Account::where('user_id', $existingUser->user_id)->first();
    if ($account) {
        $account->must_change_password = true;
        $account->password_hash = Hash::make('TempPass123!');
        $account->save();
        echo 'Updated existing account to require password change' . PHP_EOL;
    }
}