<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Filament\Models\Contracts\FilamentUser;


class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable;

    public $incrementing = false;

    const ACCOUNT_STATUS = ['ACTIVE', 'CLOSED'];

    const ACTIVE = self::ACCOUNT_STATUS[0];
    const CLOSED = self::ACCOUNT_STATUS[1];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'name',
        'email',
        'telephone',
        'account_status',
        'role_id',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'id' => 'string',
        'role_id' => 'string',
        'email_verified_at' => 'datetime'
    ];

    /**
     * The permissions that belong to the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'user_permissions', 'user_id', 'permission_id');
    }

    public function canAccessFilament(): bool
    {
        return true;
    }

    /**
     * Get the role that owns the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

    /**
     * Get the manager associated with the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function manager(): HasOne
    {
        return $this->hasOne(Manager::class, 'user_id', 'id');
    }

    /**
     * Get the leader associated with the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function leader(): HasOne
    {
        return $this->hasOne(Leader::class, 'user_id', 'id');
    }

    /**
     * Get the stockManager associated with the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function stockManager(): HasOne
    {
        return $this->hasOne(StockManager::class, 'user_id', 'id');
    }

    /**
     * Get the manufacturer associated with the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function manufacturer(): HasOne
    {
        return $this->hasOne(Manufacturer::class, 'user_id', 'id');
    }

    public function salesManager(): HasOne
    {
        return $this->hasOne(SalesManager::class, 'user_id', 'id');
    }
    public function hasPermission(string $permission): bool
    {
        $permissionsArray = [];
        foreach($this->permissions as $singlePermission) {
            $permissionsArray[] = $singlePermission->permission;
        }
        return collect($permissionsArray)->unique()->contains($permission);
    }
}
