<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public const ROLE_ADMIN = 'admin';
    public const ROLE_SUPERADMIN = 'superadmin';
    public const ROLE_PESERTA = 'peserta';

    public const STATUS_AKTIF = 'aktif';
    public const STATUS_NONAKTIF = 'nonaktif';

    public const PERMISSION_DASHBOARD = 'dashboard';
    public const PERMISSION_MANAGE_ADMIN = 'manage-admin';
    public const PERMISSION_MANAGE_ROLE = 'manage-role';
    public const PERMISSION_SETTINGS = 'settings';
    public const PERMISSION_VERIFY = 'verify';
    public const PERMISSION_PLACEMENT = 'placement';
    public const PERMISSION_REPORT = 'report';

    /**
     * All permissions available in the system.
     */
    public static $permissions = [
        self::ROLE_ADMIN => [
            self::PERMISSION_DASHBOARD,
            self::PERMISSION_VERIFY,
        ],
        self::ROLE_SUPERADMIN => [
            // Super admin has all permissions
            self::PERMISSION_DASHBOARD,
            self::PERMISSION_MANAGE_ADMIN,
            self::PERMISSION_MANAGE_ROLE,
            self::PERMISSION_SETTINGS,
            self::PERMISSION_VERIFY,
            self::PERMISSION_PLACEMENT,
            self::PERMISSION_REPORT,
        ],
        self::ROLE_PESERTA => [
            // Peserta can only view dashboard
            self::PERMISSION_DASHBOARD,
        ],
    ];

    /**
     * Check if the user has a specific permission.
     *
     * @param  string  $permission
     * @return bool
     */
    public function hasPermissionTo($permission)
    {
        $userRole = strtolower($this->role);

        // Super admin has all permissions
        if ($userRole === self::ROLE_SUPERADMIN) {
            return true;
        }

        // Check if the user's role has this permission
        if (isset(self::$permissions[$userRole]) && in_array($permission, self::$permissions[$userRole], true)) {
            return true;
        }

        return false;
    }

    public function isSuperAdmin(): bool
    {
        return strtolower($this->role) === self::ROLE_SUPERADMIN;
    }

    public function isAdmin(): bool
    {
        return in_array(strtolower($this->role), [self::ROLE_ADMIN, self::ROLE_SUPERADMIN], true);
    }

    public function isActive(): bool
    {
        return strtolower($this->status) === self::STATUS_AKTIF;
    }

    public $fillable = ['name','email','password','role','status'];
    protected $hidden = ['password','remember_token'];
    protected $casts = ['email_verified_at' => 'datetime'];
}