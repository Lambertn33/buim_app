<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $fillable = [
        'id', 'district', 'province_id'
    ];

    protected $casts = [
        'id' => 'string',
        'province_id' => 'string'
    ];
}
