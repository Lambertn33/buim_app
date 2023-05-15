<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarehouseDeviceRequestDraft extends Model
{
    use HasFactory;

    protected $fillable = [
        'id','warehouse_id', 'warehouse_device_request_id', 'screener_code', 'device_id', 'model_id', 'quantity'
    ];

    protected $casts = [
        'id' => 'string',
        'warehouse_device_request_id' => 'string',
        'warehouse_id' => 'string',
        'device_id' => 'string',
        'model_id' => 'string'
    ];
}
