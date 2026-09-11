<?php

namespace App\Http\Resources\Accounts;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AccountResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'account_id'           => $this->account_id,
            'user_id'              => $this->user_id,
            'entity_id'            => $this->entity_id,
            'entity_type'          => $this->entity_type,
            'username'             => $this->username,
            'recovery_email'       => $this->recovery_email,
            'status'               => $this->status,
            'is_active'            => $this->is_active,
            'must_change_password' => $this->must_change_password,
            'failed_login_count'   => $this->failed_login_count,
            'locked_until'         => $this->locked_until?->toDateTimeString(),
            'last_login_at'        => $this->last_login_at?->toDateTimeString(),
            'last_login_ip'        => $this->last_login_ip,
            'email_verified_at'    => $this->email_verified_at?->toDateTimeString(),
            'two_factor_enabled'   => $this->two_factor_enabled,
            'created_at'           => $this->created_at?->toDateTimeString(),
            'updated_at'           => $this->updated_at?->toDateTimeString(),
        ];
    }
}