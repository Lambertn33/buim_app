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
     * Get all of the devices for the StockModel
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function devices(): HasMany
    {
        return $this->hasMany(StockDevice::class, 'model_id', 'id');
    }

    /**
     * Get all of the subDevices for the StockModel
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subDevices(): HasMany
    {
        return $this->hasMany(SubStockDevice::class, 'model_id', 'id');
    }
}
