<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Accounts\AccountResource;
use App\Models\Account;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AccountController extends Controller
{
    /* ================================================================
     *  LIST — paginated, optional filters
     * ============================================================== */
    public function index(Request $request): JsonResponse
    {
        $query = Account::query();

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }
        if ($request->filled('entity_type')) {
            $query->where('entity_type', $request->string('entity_type'));
        }

        $perPage = (int) $request->integer('limit', 25);
        $accounts = $query
            ->orderByDesc('created_at')
            ->orderByDesc('account_id')
            ->paginate($perPage);

        return response()->json([
            'success'  => true,
            'count'    => $accounts->total(),
            'accounts' => AccountResource::collection($accounts),
            'meta'     => [
                'current_page' => $accounts->currentPage(),
                'per_page'     => $accounts->perPage(),
                'last_page'    => $accounts->lastPage(),
            ],
        ]);
    }

    /* ================================================================
     *  SHOW — single account
     * ============================================================== */
    public function show(Account $account): JsonResponse
    {
        return response()->json([
            'success' => true,
            'account' => new AccountResource($account),
        ]);
    }

    /* ================================================================
     *  STORE — admin/registrar provisions an account
     * ============================================================== */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'entity_id'      => ['required', 'integer', 'min:1'],
            'entity_type'    => ['required', Rule::in(Account::ENTITY_TYPES ?? ['student', 'teacher', 'admin'])],
            'username'       => ['required', 'string', 'min:3', 'max:50', 'regex:/^[a-z0-9._-]+$/',
                                 'unique:lms_accounts,username'],
            'password'       => ['required', 'string', 'min:8', 'max:128'],
            'recovery_email' => ['nullable', 'email', 'max:100'],
            'user_id'        => ['nullable', 'integer', 'exists:users,user_id'],
            'status'         => ['nullable', Rule::in([
                Account::STATUS_ACTIVE,
                Account::STATUS_INACTIVE,
                Account::STATUS_LOCKED,
                Account::STATUS_SUSPENDED,
                Account::STATUS_PENDING_VERIFICATION,
            ])],
            'created_by'     => ['nullable', 'integer', 'exists:users,user_id'],
        ]);

        // Reject if an account already exists for this entity.
        $existing = Account::where('entity_id', $data['entity_id'])
            ->where('entity_type', $data['entity_type'])
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'reason'  => 'duplicate',
                'errors'  => ["An account already exists for this {$data['entity_type']}."],
            ], 409);
        }

        $account = Account::create([
            'entity_id'            => $data['entity_id'],
            'entity_type'          => $data['entity_type'],
            'username'             => $data['username'],
            'password_hash'        => Hash::make($data['password']),
            'recovery_email'       => $data['recovery_email'] ?? null,
            'status'               => $data['status'] ?? Account::STATUS_PENDING_VERIFICATION,
            'is_active'            => true,
            'must_change_password' => true,
            'user_id'              => $data['user_id'] ?? null,
            'created_by'           => $data['created_by'] ?? null,
            'updated_by'           => $data['created_by'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Account created successfully.',
            'account' => new AccountResource($account),
        ], 201);
    }

    /* ================================================================
     *  UPDATE — partial update of mutable fields
     * ============================================================== */
    public function update(Request $request, Account $account): JsonResponse
    {
        $data = $request->validate([
            'username'             => ['sometimes', 'string', 'min:3', 'max:50', 'regex:/^[a-z0-9._-]+$/',
                                       Rule::unique('lms_accounts', 'username')->ignore($account->account_id, 'account_id')],
            'recovery_email'       => ['sometimes', 'nullable', 'email', 'max:100'],
            'user_id'              => ['sometimes', 'nullable', 'integer', 'exists:users,user_id'],
            'status'               => ['sometimes', Rule::in([
                Account::STATUS_ACTIVE,
                Account::STATUS_INACTIVE,
                Account::STATUS_LOCKED,
                Account::STATUS_SUSPENDED,
                Account::STATUS_PENDING_VERIFICATION,
            ])],
            'is_active'            => ['sometimes', 'boolean'],
            'must_change_password' => ['sometimes', 'boolean'],
            'updated_by'           => ['sometimes', 'nullable', 'integer', 'exists:users,user_id'],
        ]);

        $account->fill($data);
        $account->save();

        return response()->json([
            'success' => true,
            'message' => 'Account updated successfully.',
            'account' => new AccountResource($account->fresh()),
        ]);
    }

    /* ================================================================
     *  DESTROY — hard delete
     * ============================================================== */
    public function destroy(Account $account): JsonResponse
    {
        $account->delete();

        return response()->json([
            'success' => true,
            'message' => 'Account deleted successfully.',
        ]);
    }

    /* ================================================================
     *  UNLOCK — clear failed logins + reset status to Active
     * ============================================================== */
    public function unlock(Account $account): JsonResponse
    {
        $account->update([
            'failed_login_count' => 0,
            'locked_until'       => null,
            'status'             => Account::STATUS_ACTIVE,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Account unlocked.',
            'account' => new AccountResource($account->fresh()),
        ]);
    }
}