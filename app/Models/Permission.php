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

        'payment_plan_create',
        'payment_plan_access',
        'payment_plan_show',
        'payment_plan_edit',
        'payment_plan_delete',

        'user_create',
        'user_access',
        'user_show',
        'user_edit',
        'user_delete',

        'role_create',
        'role_access',
        'role_show',
        'role_edit',
        'role_delete',

        'main_warehouse_access',
        'main_warehouse_show',
        'main_warehouse_edit',
        'main_warehouse_create',
        'main_warehouse_delete',

        'stock_create',
        'stock_access',
        'stock_show',
        'stock_edit',
        'stock_delete',

        'stock_model_create',
        'stock_model_access',
        'stock_model_show',
        'stock_model_edit',
        'stock_model_delete',

        'stock_pending_create',
        'stock_pending_access',
        'stock_pending_show',
        'stock_pending_edit',
        'stock_pending_delete',

        'sub_stock_create',
        'sub_stock_access',
        'sub_stock_show',
        'sub_stock_edit',
        'sub_stock_delete',

        'sub_stock_request_create',
        'sub_stock_request_access',
        'sub_stock_request_show',
        'sub_stock_request_edit',
        'sub_stock_request_delete',
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
