<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use App\Models\User;

class WebAuthController extends Controller
{
    /** Map of roles to their respective dashboard routes */
    protected array $roleDashboardMap = [
        'Admin' => '/admin/',
        'Staff' => '/admin/',
        'Registrar' => '/admin/',
        'Accounting' => '/admin/',
        'Teacher' => '/teacher/',
        'Student' => '/student/',
    ];

    public function login(Request $request): RedirectResponse
    {
        // Handle case where 'email' field is submitted instead of 'identifier'
        if (! $request->filled('identifier') && $request->filled('email')) {
            $request->merge(['identifier' => $request->input('email')]);
        }

        $credentials = $request->validate([
            'identifier' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        // Simplified query: find active, non-deleted user by email
        $user = \App\Models\User::query()
            ->where('email', $credentials['identifier'])
            ->where('status', 'Active')
            ->where('is_deleted', false)
            ->first();

        // Validate user credentials
        $storedHash = $user?->password ?? '';

        if (! $user || ! $this->isValidBcrypt($storedHash) || ! Hash::check($credentials['password'], $storedHash)) {
            // Log failed login attempt for security monitoring
            Log::warning('Failed web login attempt', [
                'identifier' => $credentials['identifier'],
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return back()->withErrors([
                'identifier' => 'The provided credentials are incorrect.',
            ])->onlyInput('identifier');
        }

        // Auto-rehash if password hash is outdated
        if (Hash::needsRehash($storedHash)) {
            $user->update(['password' => $credentials['password']]);
        }

        // Log successful login
        Log::info('Successful web login', [
            'user_id' => $user->user_id,
            'identifier' => $credentials['identifier'],
            'ip' => $request->ip(),
        ]);

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        // Fetch associated account
        $account = \App\Models\Account::where('user_id', $user->user_id)->first();

        // If account exists and password change is required (first login)
        if ($account && $account->must_change_password) {
            return redirect()->route('password.change')
                ->with('warning', 'Please change your password for security reasons.');
        }

        

        // Redirect to appropriate dashboard based on role
        $redirectUrl = $this->roleDashboardMap[$user->role] ?? '/login';
        
        return redirect()->intended($redirectUrl);
    }

    /**
     * Check if a string is a valid Bcrypt hash.
     */
    private function isValidBcrypt(?string $password): bool
    {
        if (empty($password)) {
            return false;
        }

        $info = password_get_info($password);

        return ($info['algoName'] ?? '') === 'bcrypt';
    }

    public function adminDashboard(Request $request): View|RedirectResponse
    {
        abort_unless(in_array($request->user()->role, ['Admin', 'Staff', 'Registrar', 'Accounting'], true), 403);

        $latestUsers = User::query()
            ->orderByDesc('created_at')
            ->orderByDesc('user_id')
            ->limit(10)
            ->get();

        // Chart data for dashboard
        $usersByRole = User::select('role', DB::raw('count(*) as total'))
            ->groupBy('role')
            ->orderByDesc('total')
            ->get();

        $attendanceByStatus = DB::table('attendance_records')
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->orderBy('status')
            ->get();

        $studentsByGrade = DB::table('students')
            ->select('grade_level', DB::raw('count(*) as total'))
            ->groupBy('grade_level')
            ->orderBy('grade_level')
            ->get();

        // Students by created_at month and grade_level for line/bar chart
        $studentsByCreatedAt = DB::table('students')
            ->select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as period'),
                'grade_level',
                DB::raw('count(*) as total')
            )
            ->groupBy('period', 'grade_level')
            ->orderBy('period')
            ->orderBy('grade_level')
            ->get();

        $studentsByStatus = DB::table('students')
            ->join('users', 'students.user_id', '=', 'users.user_id')
            ->select('users.status', DB::raw('count(*) as total'))
            ->groupBy('users.status')
            ->orderBy('users.status')
            ->get();

        $studentsByGender = DB::table('students')
            ->join('users', 'students.user_id', '=', 'users.user_id')
            ->select('users.gender', DB::raw('count(*) as total'))
            ->groupBy('users.gender')
            ->orderBy('users.gender')
            ->get();

        $enrollmentBySemester = DB::table('enrollments')
            ->select('semester', DB::raw('count(*) as total'))
            ->groupBy('semester')
            ->orderBy('semester')
            ->get();

        $enrollmentByStatus = DB::table('enrollments')
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->orderBy('status')
            ->get();

        $enrollmentTrends = DB::table('enrollments')
            ->select('school_year', DB::raw('count(*) as total'))
            ->groupBy('school_year')
            ->orderBy('school_year')
            ->get();

        $teachersBySpecialization = DB::table('teachers')
            ->select('specialization', DB::raw('count(*) as total'))
            ->whereNotNull('specialization')
            ->groupBy('specialization')
            ->orderBy('specialization')
            ->get();

        $subjectCount = DB::table('subjects')->count();
        $subjectsByType = DB::table('subjects')
            ->select('subject_type', DB::raw('count(*) as total'))
            ->groupBy('subject_type')
            ->orderBy('subject_type')
            ->get();

        return view('admin.dashboard', compact(
            'latestUsers',
            'usersByRole',
            'attendanceByStatus',
            'studentsByGrade',
            'studentsByStatus',
            'studentsByGender',
            'studentsByCreatedAt',
            'enrollmentBySemester',
            'enrollmentByStatus',
            'enrollmentTrends',
            'teachersBySpecialization',
            'subjectCount',
            'subjectsByType'
        ));
    }

    public function adminUsers(Request $request): View|RedirectResponse
    {
        abort_unless(in_array($request->user()->role, ['Admin', 'Staff', 'Registrar', 'Accounting'], true), 403);

        $query = User::query();

        // Search by name or email
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('first_name', 'like', '%' . $request->search . '%')
                  ->orWhere('last_name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Filter by is_deleted
        if ($request->filled('deleted') && $request->deleted === '1') {
            $query->where('is_deleted', true);
        } elseif ($request->filled('deleted') && $request->deleted === '0') {
            $query->where('is_deleted', false);
        }

        $users = $query->orderByDesc('created_at')
                       ->orderByDesc('user_id')
                       ->paginate(10)
                       ->withQueryString();

        // Chart data: users by role (separate query)
        $roleQuery = User::query();
        if ($request->filled('search')) {
            $roleQuery->where(function ($q) use ($request) {
                $q->where('first_name', 'like', '%' . $request->search . '%')
                  ->orWhere('last_name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }
        if ($request->filled('role')) {
            $roleQuery->where('role', $request->role);
        }
        if ($request->filled('deleted') && $request->deleted === '1') {
            $roleQuery->where('is_deleted', true);
        } elseif ($request->filled('deleted') && $request->deleted === '0') {
            $roleQuery->where('is_deleted', false);
        }
        $usersByRole = $roleQuery->selectRaw('role, COUNT(*) as total')
                                  ->groupBy('role')
                                  ->get();

        // Chart data: users by status (separate query)
        $statusQuery = User::query();
        if ($request->filled('search')) {
            $statusQuery->where(function ($q) use ($request) {
                $q->where('first_name', 'like', '%' . $request->search . '%')
                  ->orWhere('last_name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }
        if ($request->filled('role')) {
            $statusQuery->where('role', $request->role);
        }
        if ($request->filled('deleted') && $request->deleted === '1') {
            $statusQuery->where('is_deleted', true);
        } elseif ($request->filled('deleted') && $request->deleted === '0') {
            $statusQuery->where('is_deleted', false);
        }
        $usersByStatus = $statusQuery->selectRaw('status, COUNT(*) as total')
                                      ->groupBy('status')
                                      ->get();

        return view('admin.users.index', compact('users', 'usersByRole', 'usersByStatus'));
    }

    public function adminUsersCreate(): View|RedirectResponse
    {
        abort_unless(in_array(auth()->user()->role, ['Admin', 'Staff', 'Registrar', 'Accounting'], true), 403);

        return view('admin.users.create');
    }

    public function adminUsersStore(Request $request): RedirectResponse
    {
        abort_unless(in_array(auth()->user()->role, ['Admin', 'Staff', 'Registrar', 'Accounting'], true), 403);

        $validated = $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'middle_name' => 'nullable|string|max:50',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:Admin,Staff,Registrar,Accounting,Teacher,Student',
            'status' => 'required|in:Active,Inactive,Suspended,Locked',
            'is_deleted' => 'boolean',
            'contact_number' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'birthdate' => 'nullable|date',
            'gender' => 'nullable|in:Male,Female,Other',
            // Student fields
            'student_lrn' => 'nullable|string|max:12',
            'student_number' => 'nullable|string|max:20',
            'grade_level' => 'nullable|in:11,12',
            'strand_id' => 'nullable|integer',
            'student_guardian_id' => 'nullable|integer',
            // Teacher fields
            'teacher_number' => 'nullable|string|max:20',
            'specialization' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($validated) {
            $user = User::create([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'middle_name' => $validated['middle_name'] ?? null,
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => $validated['role'],
                'status' => $validated['status'],
                'is_deleted' => $validated['is_deleted'] ?? false,
                'contact_number' => $validated['contact_number'] ?? null,
                'address' => $validated['address'] ?? null,
                'birthdate' => $validated['birthdate'] ?? null,
                'gender' => $validated['gender'] ?? null,
            ]);

            if ($validated['role'] === 'Teacher') {
                $teacherData = [
                    'user_id' => $user->user_id,
                    'teacher_number' => $validated['teacher_number'] ?? '',
                    'specialization' => $validated['specialization'] ?? '',
                ];
                \App\Models\Teacher::create($teacherData);
            } elseif ($validated['role'] === 'Student') {
                $studentData = [
                    'user_id' => $user->user_id,
                    'lrn' => $validated['student_lrn'] ?? '',
                    'student_number' => $validated['student_number'] ?? '',
                    'grade_level' => $validated['grade_level'] ?? '11',
                ];
                if ($validated['student_guardian_id']) {
                    $studentData['guardian_id'] = $validated['student_guardian_id'];
                }
                \App\Models\Student::create($studentData);
            }
        });

        return redirect()->route('admin.users.index')->with('success', 'Account created successfully.');
    }

    public function adminUsersShow($user_id): View|RedirectResponse
    {
        abort_unless(in_array(auth()->user()->role, ['Admin', 'Staff', 'Registrar', 'Accounting'], true), 403);

        $user = User::with(['teacher', 'student'])->findOrFail($user_id);

        return view('admin.users.show', compact('user'));
    }

    public function adminUsersEdit(Request $request, $user_id): View|RedirectResponse
    {
        abort_unless(in_array(auth()->user()->role, ['Admin', 'Staff', 'Registrar', 'Accounting'], true), 403);

        $user = User::findOrFail($user_id);
        $teacher = $user->role === 'Teacher' ? \App\Models\Teacher::where('user_id', $user_id)->first() : null;
        $student = $user->role === 'Student' ? \App\Models\Student::where('user_id', $user_id)->first() : null;

        return view('admin.users.edit', compact('user', 'teacher', 'student'));
    }

    public function adminUsersUpdate(Request $request, $user_id): RedirectResponse
    {
        abort_unless(in_array(auth()->user()->role, ['Admin', 'Staff', 'Registrar', 'Accounting'], true), 403);

        $user = User::findOrFail($user_id);

        $validated = $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'middle_name' => 'nullable|string|max:50',
            'email' => 'required|string|email|max:100|unique:users,email,' . $user_id . ',user_id',
            'password' => 'nullable|string|min:8',
            'role' => 'required|in:Admin,Staff,Registrar,Accounting,Teacher,Student',
            'status' => 'required|in:Active,Inactive,Suspended,Locked',
            'contact_number' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'birthdate' => 'nullable|date',
            'gender' => 'nullable|in:Male,Female,Other',
            // Student fields
            'student_lrn' => 'nullable|string|max:12',
            'student_number' => 'nullable|string|max:20',
            'grade_level' => 'nullable|in:11,12',
            'student_guardian_id' => 'nullable|integer',
            // Teacher fields
            'teacher_number' => 'nullable|string|max:20',
            'specialization' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($user, $validated) {
            $user->update([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'middle_name' => $validated['middle_name'] ?? null,
                'email' => $validated['email'],
                'password' => $validated['password'] ? Hash::make($validated['password']) : $user->password,
                'role' => $validated['role'],
                'status' => $validated['status'],
                'contact_number' => $validated['contact_number'] ?? null,
                'address' => $validated['address'] ?? null,
                'birthdate' => $validated['birthdate'] ?? null,
                'gender' => $validated['gender'] ?? null,
            ]);

            // Handle role change / teacher/student data
            $existingTeacher = \App\Models\Teacher::where('user_id', $user->user_id)->first();
            $existingStudent = \App\Models\Student::where('user_id', $user->user_id)->first();

            if ($validated['role'] === 'Teacher') {
                if ($existingTeacher) {
                    $existingTeacher->update([
                        'teacher_number' => $validated['teacher_number'] ?? '',
                        'specialization' => $validated['specialization'] ?? '',
                    ]);
                } else {
                    \App\Models\Teacher::create([
                        'user_id' => $user->user_id,
                        'teacher_number' => $validated['teacher_number'] ?? '',
                        'specialization' => $validated['specialization'] ?? '',
                    ]);
                }
                if ($existingStudent) {
                    $existingStudent->delete();
                }
            } elseif ($validated['role'] === 'Student') {
                if ($existingStudent) {
                    $existingStudent->update([
                        'lrn' => $validated['student_lrn'] ?? '',
                        'student_number' => $validated['student_number'] ?? '',
                        'grade_level' => $validated['grade_level'] ?? '11',
                        'guardian_id' => $validated['student_guardian_id'] ?? null,
                    ]);
                } else {
                    \App\Models\Student::create([
                        'user_id' => $user->user_id,
                        'lrn' => $validated['student_lrn'] ?? '',
                        'student_number' => $validated['student_number'] ?? '',
                        'grade_level' => $validated['grade_level'] ?? '11',
                        'guardian_id' => $validated['student_guardian_id'] ?? null,
                    ]);
                }
                if ($existingTeacher) {
                    $existingTeacher->delete();
                }
            } else {
                if ($existingTeacher) $existingTeacher->delete();
                if ($existingStudent) $existingStudent->delete();
            }
        });

        return redirect()->route('admin.users.index')->with('success', 'Account updated successfully.');
    }

    public function adminUsersDelete($user_id): RedirectResponse
    {
        abort_unless(in_array(auth()->user()->role, ['Admin', 'Staff', 'Registrar', 'Accounting'], true), 403);

        $user = User::findOrFail($user_id);
        $user->update(['is_deleted' => true]);

        return redirect()->back()->with('success', 'Account deleted successfully.');
    }

    public function adminUsersRestore($user_id): RedirectResponse
    {
        abort_unless(in_array(auth()->user()->role, ['Admin', 'Staff', 'Registrar', 'Accounting'], true), 403);

        $user = User::findOrFail($user_id);
        $user->update(['is_deleted' => false]);

        return redirect()->back()->with('success', 'Account restored successfully.');
    }

    public function teacherDashboard(Request $request): View|RedirectResponse
    {
        return $this->dashboardFor($request, ['Teacher'], 'teacher.dashboard');
    }

    public function studentDashboard(Request $request): View|RedirectResponse
    {
        return $this->dashboardFor($request, ['Student'], 'student.dashboard');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
     * Show the student registration form.
     */
    public function showStudentRegistrationForm(): View
    {
        return view('auth.signup.student');
    }

    /**
     * Show the teacher registration form.
     */
    public function showTeacherRegistrationForm(): View
    {
        return view('auth.signup.teacher');
    }

    /**
     * Handle student registration form submission.
     */
    public function registerStudent(Request $request): RedirectResponse
    {
        // Validate the request
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'lrn' => ['nullable', 'string', 'max:20'],
            'birth_date' => ['nullable', 'date'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Prepare data for registration service
        $data = [
            'first_name' => $validated['first_name'],
            'middle_name' => $validated['middle_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'birthdate' => $validated['birth_date'] ?? null,
            'gender' => 'Male', // Default value, could be made configurable
            'address' => 'Not provided', // Default value
            'contact_number' => 'Not provided', // Default value
            'grade_level' => '11', // Default value
            'phone' => 'Not provided',
        ];

        try {
            // Use the registration service to create the account
            $account = app(\App\Services\Accounts\RegistrationService::class)->registerStudent($data);

            return redirect()->route('login')
                ->with('success', 'Student account created successfully! Please check your email for verification and log in.');
        } catch (\Exception $e) {
            return back()->withInput()
                ->withErrors(['registration' => 'An error occurred during registration. Please try again.']);
        }
    }

    /**
     * Handle teacher registration form submission.
     */
    public function registerTeacher(Request $request): RedirectResponse
    {
        // Validate the request
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'specialization' => ['nullable', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Prepare data for registration service
        $data = [
            'first_name' => $validated['first_name'],
            'middle_name' => $validated['middle_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'specialization' => $validated['specialization'],
            'phone' => 'Not provided',
        ];

        try {
            // Use the registration service to create the account
            $account = app(\App\Services\Accounts\RegistrationService::class)->registerTeacher($data);

            return redirect()->route('login')
                ->with('success', 'Teacher account created successfully! Please check your email for verification and log in.');
        } catch (\Exception $e) {
            return back()->withInput()
                ->withErrors(['registration' => 'An error occurred during registration. Please try again.']);
        }
    }

    /**
     * Show the password change form.
     */
    public function editPassword(Request $request): View
    {
        return view('auth.change-password');
    }

    /**
     * Handle the password change request.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Get user's account
        $account = \App\Models\Account::where('user_id', $request->user()->user_id)->first();

        if (!$account) {
            return back()->withErrors(['account' => 'Account not found.']);
        }

        // Verify current password
        if (!Hash::check($request->input('current_password'), $account->password_hash)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }

        // Update password and reset flags
        $account->update([
            'password_hash' => Hash::make($request->input('password')),
            'must_change_password' => false,
            'password_changed_at' => now(),
        ]);

        return redirect()->intended($this->roleDashboardMap[$request->user()->role] ?? '/')
            ->with('success', 'Your password has been changed successfully.');
    }

    private function dashboardFor(Request $request, array $roles, string $view): View|RedirectResponse
    {
        abort_unless(in_array($request->user()->role, $roles, true), 403);

        return view($view);
    }
}
