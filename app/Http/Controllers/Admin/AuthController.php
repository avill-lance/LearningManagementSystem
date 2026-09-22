<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Accounts\RegistrationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    protected $registrationService;

    public function __construct(RegistrationService $registrationService)
    {
        $this->registrationService = $registrationService;
    }

    public function showRegistrationForm()
    {
        return view('admin.auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:lms_accounts,username'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:lms_accounts,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $data = $request->only('username', 'email', 'password');

        $admin = $this->registrationService->registerAdmin($data);

        // Optionally, you can log the admin in after registration
        // Auth::guard('admin')->login($admin);

        return redirect()->route('admin.dashboard')
            ->with('success', 'Admin account created successfully. Please login with the provided credentials and change your password on first login.');
    }
}