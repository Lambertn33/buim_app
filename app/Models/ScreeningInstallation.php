<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'id', 'screener_id', 'latitude', 'longitude', 'technician_id', 'verified_by', 'installation_status', 'verification_status'
    ];

    protected $casts = [
        'id' => 'string',
        'screener_id' => 'string',
        'installed_by' => 'string',
        'verified_by' => 'string'
    ];

    /**
     * Get the screening that owns the ScreeningInstallation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function screening(): BelongsTo
    {
        return $this->belongsTo(Screening::class, 'screener_id', 'id');
    }

    /**
     * Get the technician that owns the ScreeningInstallation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function technician(): BelongsTo
    {
        return $this->belongsTo(Technician::class, 'technician_id', 'id');
    }

    public function verifiedBy(): string
    {
        $leader = Leader::find($this->verified_by);
        return $leader->user->name;
    }
}
