<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WarehouseDeviceDistribution extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $fillable = [
        'id', 'screener_id', 'warehouse_device_id', 'contract_id'
    ];

    protected $casts = [
        'id' => 'string', 
        'screener_id' => 'string',
        'warehouse_device_id' => 'string'
    ];

    /**
     * Get the screener that owns the WarehouseDeviceDistribution
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function screener(): BelongsTo
    {
        return $this->belongsTo(Screening::class, 'screener_id', 'id');
    }

    /**
     * Get the warehouseDevice that owns the WarehouseDeviceDistribution
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function warehouseDevice(): BelongsTo
    {
        return $this->belongsTo(WarehouseDevice::class, 'warehouse_device_id', 'id');
    }
}
