<?php

namespace App\Services\Accounts;

use App\Models\Account;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PasswordService
{
    public function issueResetToken(string $email): bool
    {
        $account = Account::where('recovery_email', $email)->first();
        if (! $account) {
            return true; // Don't leak existence.
        }

        $account->update([
            'password_reset_token'      => hash('sha256', $token = Str::random(64)),
            'password_reset_expires_at' => now()->addMinutes(60),
        ]);

        // TODO: Mail::to($email)->send(new PasswordResetMail($token));
        return true;
    }

    public function reset(string $plainToken, string $newPassword): bool
    {
        $account = Account::where('password_reset_token', hash('sha256', $plainToken))
            ->where('password_reset_expires_at', '>=', now())
            ->first();

        if (! $account) {
            return false;
        }

        $account->update([
            'password_hash'             => Hash::make($newPassword),
            'password_changed_at'       => now(),
            'must_change_password'      => false,
            'failed_login_count'        => 0,
            'locked_until'              => null,
            'password_reset_token'      => null,
            'password_reset_expires_at' => null,
        ]);

        return true;
    }
}