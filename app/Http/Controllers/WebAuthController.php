<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

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
        return $this->dashboardFor($request, ['Admin', 'Staff', 'Registrar', 'Accounting'], 'admin.dashboard');
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
