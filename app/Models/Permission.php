<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{
    use HasFactory;

    public $incrementing = false;

    const PERMISSIONS = [
        'campaign_create',
        'campaign_access',
        'campaign_show',
        'campaign_edit',
        'campaign_delete',

        'screening_create',
        'screening_access',
        'screening_show',
        'screening_edit',
        'screening_delete',

        'permission_create',
        'permission_access',
        'permission_show',
        'permission_edit',
        'permission_delete',

        'user_create',
        'user_access',
        'user_show',
        'user_edit',
        'user_delete',

        'stock_create',
        'stock_access',
        'stock_show',
        'stock_edit',
        'stock_delete',
    ];

    protected $fillable = [
        'id', 'permission'
    ];

    protected $casts = [
        'id' => 'string'
    ];

    /**
     * The users that belong to the Permission
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_permissions', 'permission_id', 'user_id');
    }
}
