<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Warehouse extends Model
{
    use HasFactory;

    public $incrementing = false;

    const STATUS = ['ACTIVE', 'CLOSED'];

    const ACTIVE = self::STATUS[0];
    const CLOSED = self::STATUS[1];

    protected $fillable = ['id', 'district_id', 'manager_id', 'name', 'status'];

    protected $casts = [
        'id' => 'string',
        'district_id' => 'string',
        'manager_id' => 'string'
    ];

    /**
     * Get the district that owns the Warehouse
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class, 'district_id', 'id');
    }

    /**
     * Get the manager that owns the Warehouse
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function manager(): BelongsTo
    {
        return $this->belongsTo(Manager::class, 'manager_id', 'id');
    }

    /**
     * Get all of the devices for the Warehouse
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function devices(): HasMany
    {
        return $this->hasMany(WarehouseDevice::class, 'warehouse_id', 'id');
    }
}
