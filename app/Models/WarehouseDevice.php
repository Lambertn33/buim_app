<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class WarehouseDevice extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $fillable = [
        'id', 'model_id', 'warehouse_id','district_id','manager_id','device_name', 'serial_number',
        'screener_id'
    ];

    protected $casts = [
        'id' => 'string',
        'model_id' => 'string',
        'warehouse_id' => 'string',
        'district_id' => 'string',
        'manager_id' => 'string',
        'screener_id' => 'string'
    ];

    /**
     * Get the warehouse that owns the WarehouseDevice
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id', 'id');
    }

    /**
     * Get the model that owns the WarehouseDevice
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function model(): BelongsTo
    {
        return $this->belongsTo(StockModel::class, 'model_id', 'id');
    }

    /**
     * Get the distribution associated with the Screening
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function distribution(): HasOne
    {
        return $this->hasOne(WarehouseDeviceDistribution::class, 'warehouse_device_id', 'id');
    }
}
