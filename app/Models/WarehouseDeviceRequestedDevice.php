<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WarehouseDeviceRequestedDevice extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $fillable = [
        'id', 'warehouse_device_request_id', 'screener_code', 'device_name','device_price', 'model_id', 'quantity'
    ];

    protected $casts = [
        'id' => 'string',
        'warehouse_device_request_id' => 'string',
        'model_id' => 'string'
    ];

    /**
     * Get the warehouseDeviceRequest that owns the WarehouseDeviceRequestedDevice
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function warehouseDeviceRequest(): BelongsTo
    {
        return $this->belongsTo(WarehouseDeviceRequest::class, 'warehouse_device_request_id', 'id');
    }

    /**
     * Get the model that owns the WarehouseDeviceRequestedDevice
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function model(): BelongsTo
    {
        return $this->belongsTo(StockModel::class, 'model_id', 'id');
    }

    public function getScreenedPerson()
    {
        return Screening::where('prospect_code', $this->screener_code);
    }
}
