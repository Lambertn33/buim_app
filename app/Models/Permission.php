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

        'device_price_create',
        'device_price_access',
        'device_price_show',
        'device_price_edit',

        'screening_create',
        'screening_access',
        'screening_show',
        'screening_edit',
        'screening_delete',

        'screening_partner_create',
        'screening_partner_access',
        'screening_partner_show',
        'screening_partner_edit',
        'screening_partner_delete',

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

        'technician_access',
        'technician_show',
        'technician_edit',
        'technician_create',
        'technician_delete',

        'distribution_access',
        'distribution_show',
        'distribution_create',

        'warehouse_access',
        'warehouse_show',
        'warehouse_edit',
        'warehouse_create',
        'warehouse_delete',

        'warehouse_device_access',
        'warehouse_device_show',
        'warehouse_device_edit',
        'warehouse_device_create',
        'warehouse_device_delete',

        'warehouse_device_request_access',
        'warehouse_device_request_show',
        'warehouse_device_request_edit',
        'warehouse_device_request_create',
        'warehouse_device_request_delete',

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
