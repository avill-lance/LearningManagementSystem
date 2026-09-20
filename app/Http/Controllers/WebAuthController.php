<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class WebAuthController extends Controller
{
    public function login(Request $request): RedirectResponse
    {
        if (! $request->filled('identifier') && $request->filled('email')) {
            $request->merge(['identifier' => $request->input('email')]);
        }

        $credentials = $request->validate([
            'identifier' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $user = \App\Models\User::query()
            ->where(function ($query) use ($credentials) {
                $query->where('email', $credentials['identifier']);
            })
            ->where('status', 'Active')
            ->where('is_deleted', false)
            ->first();

        $storedHash = trim((string) $user?->password);

        // Ensure the stored password is a valid Bcrypt hash before checking
        if (! $user || ! $this->isValidBcrypt($storedHash) || ! Hash::check($credentials['password'], $storedHash)) {
            return back()->withErrors([
                'identifier' => 'The provided credentials are incorrect.',
            ])->onlyInput('identifier');
        }

        // Auto-rehash if password hash is outdated
        if (Hash::needsRehash($storedHash)) {
            $user->update(['password' => $credentials['password']]);
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return match ($user->role) {
            'Admin', 'Staff', 'Registrar', 'Accounting' => redirect()->intended('/admin/'),
            'Teacher' => redirect()->intended('/teacher/'),
            'Student' => redirect()->intended('/student/'),
            default => redirect()->intended('/login'),
        };
    }

    /**
     * Check if a string is a valid Bcrypt hash.
     */
    private function isValidBcrypt(?string $password): bool
    {
        if (empty($password)) {
            return false;
        }

        $info = password_get_info(trim($password));

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

    private function dashboardFor(Request $request, array $roles, string $view): View|RedirectResponse
    {
        abort_unless(in_array($request->user()->role, $roles, true), 403);

        return view($view);
    }
}
