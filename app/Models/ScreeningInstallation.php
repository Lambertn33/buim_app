<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScreeningInstallation extends Model
{
    use HasFactory;

    public $incrementing = false;

    const INSTALLATION_STATUS = ['PENDING', 'INSTALLED'];
    const INSTALLATION_PENDING = self::INSTALLATION_STATUS[0];
    const INSTALLATION_INSTALLED = self::INSTALLATION_STATUS[1];

    const VERIFICATION_STATUS = ['PENDING', 'VERIFIED'];
    const VERIFICATION_PENDING = self::VERIFICATION_STATUS[0];
    const VERIFICATION_VERIFIED = self::VERIFICATION_STATUS[1];

    protected $fillable = [
        'id', 'screener_id', 'latitude', 'longitude', 'installation_status', 'verification_status'
    ];

    protected $casts = [
        'id' => 'string',
        'screener_id' => 'string'
    ];
}
