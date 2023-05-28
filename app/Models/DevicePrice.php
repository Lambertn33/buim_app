<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DevicePrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'id', 'device_name', 'device_price'
    ];
    
    protected $casts = [
        'id' => 'string'
    ];
}
