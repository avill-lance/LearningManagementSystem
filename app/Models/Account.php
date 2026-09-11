<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $table      = 'lms_accounts';
    protected $primaryKey = 'account_id';

    /**
     * Columns that are allowed to be set via mass assignment
     * (create(), update(), fill()).
     */
    protected $fillable = [
        'user_id',
        'entity_id',
        'entity_type',
        'username',
        'password_hash',
        'recovery_email',
        'status',
        'is_active',
        'must_change_password',
        'password_changed_at',
        'failed_login_count',
        'locked_until',
        'last_login_at',
        'last_login_ip',
        'email_verified_at',
        'email_verification_token',
        'password_reset_token',
        'password_reset_expires_at',
        'two_factor_enabled',
        'two_factor_secret',
        'remember_token',
        'created_by',
        'updated_by',
    ];

    protected $hidden = [
        'password_hash',
        'remember_token',
        'email_verification_token',
        'password_reset_token',
        'two_factor_secret',
    ];

    protected $casts = [
        'is_active'                 => 'boolean',
        'must_change_password'      => 'boolean',
        'two_factor_enabled'        => 'boolean',
        'failed_login_count'        => 'integer',
        'locked_until'              => 'datetime',
        'last_login_at'             => 'datetime',
        'email_verified_at'         => 'datetime',
        'password_changed_at'       => 'datetime',
        'password_reset_expires_at' => 'datetime',
    ];

    /* ---- Status constants -------------------------------------------- */
    public const STATUS_ACTIVE               = 'Active';
    public const STATUS_INACTIVE             = 'Inactive';
    public const STATUS_LOCKED               = 'Locked';
    public const STATUS_SUSPENDED            = 'Suspended';
    public const STATUS_PENDING_VERIFICATION = 'Pending Verification';

    public const STATUSES = [
        self::STATUS_ACTIVE,
        self::STATUS_INACTIVE,
        self::STATUS_LOCKED,
        self::STATUS_SUSPENDED,
        self::STATUS_PENDING_VERIFICATION,
    ];

    /* ---- Entity-type constants -------------------------------------- */
    public const ENTITY_TYPE_STUDENT = 'student';
    public const ENTITY_TYPE_TEACHER = 'teacher';
    public const ENTITY_TYPE_ADMIN   = 'admin';

    public const ENTITY_TYPES = [
        self::ENTITY_TYPE_STUDENT,
        self::ENTITY_TYPE_TEACHER,
        self::ENTITY_TYPE_ADMIN,
    ];

    /* ---- Rate-limit constants --------------------------------------- */
    public const MAX_FAILED_ATTEMPTS = 5;
    public const LOCKOUT_MINUTES     = 15;

    /* ---- Relationships ---------------------------------------------- */
    public function student()
    {
        return $this->belongsTo(Student::class, 'entity_id', 'student_id');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'entity_id', 'teacher_id');
    }

    /* ---- Helpers ---------------------------------------------------- */
    public function isLocked(): bool
    {
        if ($this->status === self::STATUS_LOCKED) {
            return true;
        }
        return $this->locked_until && $this->locked_until->isFuture();
    }

    public function isActive(): bool
    {
        return $this->is_active && $this->status === self::STATUS_ACTIVE;
    }
}