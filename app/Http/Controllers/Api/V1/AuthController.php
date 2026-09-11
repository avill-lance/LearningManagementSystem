<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Accounts\CheckUsernameRequest;
use App\Http\Requests\Accounts\LoginRequest;
use App\Http\Requests\Accounts\RegisterStudentRequest;
use App\Http\Requests\Accounts\RegisterTeacherRequest;
use App\Http\Resources\Accounts\AccountResource;
use App\Models\Account;
use App\Services\Accounts\AuthService;
use App\Services\Accounts\PasswordService;
use App\Services\Accounts\RegistrationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(
        private AuthService         $auth,
        private RegistrationService $registration,
        private PasswordService     $passwords,
    ) {}

    /* ---- Login -------------------------------------------------------- */
    public function login(LoginRequest $request): JsonResponse
    {
        $result = $this->auth->authenticate(
            $request->string('identifier'),
            $request->string('password'),
            $request->ip(),
        );

        $status = match (true) {
            $result['success']                => 200,
            $result['reason'] === 'locked'    => 423,
            default                           => 401,
        };

        // Attach the entity's name for the dashboard greeting.
        $payload = ['account' => $result['account'] ? new AccountResource($result['account']) : null];
        if ($result['success'] && $result['account']) {
            $payload['account'] = array_merge(
                (new AccountResource($result['account']))->resolve(),
                $this->nameFor($result['account'])
            );
        }

        return response()->json([
            'success'            => $result['success'],
            'reason'             => $result['reason'],
            'message'            => $result['message'],
            'account'            => $payload['account'],
            'attempts_remaining' => $result['attempts_remaining'],
            'lockout_until'      => $result['lockout_until'],
        ], $status);
    }

    private function nameFor(Account $account): array
    {
        if ($account->entity_type === Account::ENTITY_TYPE_STUDENT && $account->student) {
            return [
                'first_name'  => $account->student->first_name,
                'middle_name' => $account->student->middle_name,
                'last_name'   => $account->student->last_name,
            ];
        }
        if ($account->entity_type === Account::ENTITY_TYPE_TEACHER && $account->teacher) {
            return [
                'first_name' => $account->teacher->first_name,
                'last_name'  => $account->teacher->last_name,
            ];
        }
        return [];
    }

    /* ---- Registration ------------------------------------------------- */
    public function registerStudent(RegisterStudentRequest $request): JsonResponse
    {
        $account = $this->registration->registerStudent($request->validated());

        return response()->json([
            'success'    => true,
            'message'    => 'Student account created successfully! You can now log in.',
            'student_id' => $account->entity_id,
            'account'    => new AccountResource($account),
        ], 201);
    }

    public function registerTeacher(RegisterTeacherRequest $request): JsonResponse
    {
        $account = $this->registration->registerTeacher($request->validated());

        return response()->json([
            'success'    => true,
            'message'    => 'Teacher account created successfully! You can now log in.',
            'teacher_id' => $account->entity_id,
            'account'    => new AccountResource($account),
        ], 201);
    }

    /* ---- Username availability --------------------------------------- */
    public function checkUsername(CheckUsernameRequest $request): JsonResponse
    {
        $query = Account::where('username', $request->string('username'));
        if ($request->filled('exclude_account_id')) {
            $query->where('account_id', '!=', $request->integer('exclude_account_id'));
        }

        return response()->json([
            'success'   => true,
            'available' => ! $query->exists(),
            'errors'    => [],
        ]);
    }

    /* ---- Password reset ---------------------------------------------- */
    public function forgotPassword(Request $request): JsonResponse
    {
        $request->validate(['email' => ['required', 'email']]);
        $this->passwords->issueResetToken($request->string('email'));

        return response()->json([
            'success' => true,
            'message' => 'If an account exists for that email, a password-reset link has been sent.',
        ]);
    }

    public function resetPassword(Request $request): JsonResponse
    {
        $data = $request->validate([
            'token'    => ['required', 'string'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        if (! $this->passwords->reset($data['token'], $data['password'])) {
            return response()->json([
                'success' => false,
                'reason'  => 'invalid_token',
                'errors'  => ['This password-reset link is invalid or has expired.'],
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Your password has been reset. You can now log in.',
        ]);
    }

    /* ---- Logout ------------------------------------------------------- */
    public function logout(Request $request): JsonResponse
    {
        // If using Sanctum: $request->user()->currentAccessToken()->delete();
        return response()->json(['success' => true, 'message' => 'Logged out.']);
    }
}