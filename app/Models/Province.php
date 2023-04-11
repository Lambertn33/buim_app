<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    use HasFactory;

    public $incrementing = false;

    const PROVINCES = [
        'KIGALI', 'NORTHERN', 'SOUTHERN', 'EASTERN', 'WESTERN'
    ];

    const KIGALI = self::PROVINCES[0];
    const NORTHERN = self::PROVINCES[1];
    const SOUTHERN = self::PROVINCES[2];
    const EASTERN = self::PROVINCES[3];
    const WESTERN = self::PROVINCES[4];

    protected $fillable = [
        'id', 'province'
    ];

    protected $casts = [
        'id' => 'string'
    ];
}
