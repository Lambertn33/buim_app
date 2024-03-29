<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MainWarehouse extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $fillable = ['id', 'name', 'description', 'location'];

    const DPWORLDWAREHOUSE = 'DP World warehouse';
    const HQWAREHOUSE = 'HQ warehouse';
    const RUGANDOWAREHOUSE = 'Rugando warehouse';

    protected $casts = [
        'id' => 'string'
    ];

    /**
     * Get all of the mainWarehouseDevices for the StockModel
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function devices(): HasMany
    {
        return $this->hasMany(MainWarehouseDevice::class, 'main_warehouse_id', 'id');
    }
}
