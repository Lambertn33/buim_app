<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    use HasFactory;

    public $incrementing = false;

    const ROLES = [
        'ADMINISTRATOR',
        'DISTRICT_MANAGER',
        'SECTOR_LEADER',
    ];

    const ADMIN_ROLE = self::ROLES[0];
    const DISTRICT_MANAGER_ROLE = self::ROLES[1];
    const SECTOR_LEADER_ROLE = self::ROLES[2];

    protected $fillable = [
        'id', 'role'
    ];

    protected $casts = [
        'id' => 'string'
    ];

    /**
     * Get all of the users for the Role
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'role_id', 'id');
    }
}
