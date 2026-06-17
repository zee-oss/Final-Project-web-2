<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class User extends Authenticatable
{
    protected $fillable = [
        'name', 'email', 'password', 'role_id', 'branch_id', 'is_active'
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'is_active'      => 'boolean',
        'last_login_at'  => 'datetime',
        'email_verified_at' => 'datetime',
    ];

    public function role(): BelongsTo   { return $this->belongsTo(Role::class); }
    public function branch(): BelongsTo { return $this->belongsTo(Branch::class); }

    // Helper methods
    public function isOwner(): bool      { return $this->role->name === Role::OWNER; }
    public function isManager(): bool    { return $this->role->name === Role::MANAGER; }
    public function isSupervisor(): bool { return $this->role->name === Role::SUPERVISOR; }
    public function isCashier(): bool    { return $this->role->name === Role::CASHIER; }
    public function isWarehouse(): bool  { return $this->role->name === Role::WAREHOUSE; }

    public function hasRole(string|array $roles): bool
    {
        $roles = is_array($roles) ? $roles : [$roles];
        return in_array($this->role->name, $roles);
    }
}
