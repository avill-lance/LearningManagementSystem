<?php

namespace App\Services\Accounts;

use App\Models\Account;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function authenticate(string $identifier, string $password, ?string $ip): array
    {
        $account = Account::where('username', $identifier)
            ->orWhere('recovery_email', $identifier)
            ->first();

        if (! $account) {
            // Dummy verify — keeps timing roughly constant to prevent user enumeration.
            Hash::check($password, '$2y$10$invalidinvalidinvalidinvalidinvalidinvalidinvalidinvalida');
            return $this->fail('no_user', 'Invalid username/email or password.');
        }

        if ($account->isLocked()) {
            return $this->fail('locked', 'This account is temporarily locked. Try again later.', $account);
        }

        if (! Hash::check($password, $account->password_hash)) {
            return $this->handleFailedAttempt($account);
        }

        $account->update([
            'last_login_at'      => now(),
            'last_login_ip'      => $ip,
            'failed_login_count' => 0,
            'locked_until'       => null,
        ]);

        if ($account->must_change_password) {
            return $this->ok('must_change_password', 'Please change your temporary password before continuing.', $account);
        }
        if ($account->status === Account::STATUS_PENDING_VERIFICATION) {
            return $this->ok('pending_verification', 'Please verify your email before continuing.', $account);
        }
        if (! $account->isActive()) {
            return $this->ok('inactive', 'This account is not active. Please contact the registrar.', $account);
        }

        return $this->ok('ok', 'Login successful.', $account);
    }

    private function handleFailedAttempt(Account $account): array
    {
        $account->increment('failed_login_count');
        $count = $account->fresh()->failed_login_count;
        $remaining = max(0, Account::MAX_FAILED_ATTEMPTS - $count);

        if ($count >= Account::MAX_FAILED_ATTEMPTS) {
            $account->update([
                'status'       => Account::STATUS_LOCKED,
                'locked_until' => now()->addMinutes(Account::LOCKOUT_MINUTES),
            ]);

            return [
                'success'            => false,
                'reason'             => 'wrong_password',
                'message'            => 'Too many failed attempts. Your account is now locked.',
                'account'            => $account,
                'attempts_remaining' => 0,
                'lockout_until'      => now()->addMinutes(Account::LOCKOUT_MINUTES)->toDateTimeString(),
            ];
        }

        return [
            'success'            => false,
            'reason'             => 'wrong_password',
            'message'            => 'Invalid username/email or password.',
            'account'            => $account,
            'attempts_remaining' => $remaining,
            'lockout_until'      => null,
        ];
    }

    private function fail(string $reason, string $message, ?Account $account = null): array
    {
        return [
            'success'            => false,
            'reason'             => $reason,
            'message'            => $message,
            'account'            => $account,
            'attempts_remaining' => 0,
            'lockout_until'      => null,
        ];
    }

    private function ok(string $reason, string $message, Account $account): array
    {
        return [
            'success'            => true,
            'reason'             => $reason,
            'message'            => $message,
            'account'            => $account,
            'attempts_remaining' => Account::MAX_FAILED_ATTEMPTS,
            'lockout_until'      => null,
        ];
    }
}