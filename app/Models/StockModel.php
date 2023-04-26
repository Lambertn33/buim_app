<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StockModel extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $fillable = [
        'id', 'name', 'quantity'
    ];

    protected $casts = [
        'id' => 'string'
    ];

    /**
     * Get all of the mainWarehouseDevices for the StockModel
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function mainWarehouseDevices(): HasMany
    {
        return $this->hasMany(MainWarehouseDevice::class, 'model_id', 'id');
    }

    /**
     * Get all of the warehouseDevices for the StockModel
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function warehouseDevices(): HasMany
    {
        return $this->hasMany(WarehouseDevice::class, 'model_id', 'id');
    }

    /**
     * Get all of the warehousedevicerequests for the Campaign
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function warehouseDeviceRequests(): HasMany
    {
        return $this->hasMany(WarehouseDeviceRequest::class, 'model_id', 'id');
    }

    /**
     * Get all of the warehouseDeviceRequestedDevices for the StockModel
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function warehouseDeviceRequestedDevices(): HasMany
    {
        return $this->hasMany(WarehouseDeviceRequestedDevice::class, 'model_id', 'id');
    }
}

