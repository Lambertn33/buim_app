<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Campaign extends Model
{
    use HasFactory;

    public $incrementing = false;

    const PROVINCES = [
        'KIGALI', 'NORTHERN', 'SOUTHERN', 'EASTERN', 'WESTERN'
    ];

    const CAMPAIGN_STATUS = [
        'CREATED',
        'ONGOING',
        'FINISHED',
        'STOPPED',
    ];

    const CREATED = self::CAMPAIGN_STATUS[0];
    const ONGOING = self::CAMPAIGN_STATUS[1];
    const FINISHED = self::CAMPAIGN_STATUS[2];
    const STOPPED = self::CAMPAIGN_STATUS[3];

    protected $fillable = [
        'id', 'title', 'description','province','district', 'from', 'to', 'manager_id', 'status'
    ];

    protected $casts = [
        'id' => 'string',
        'manager_id' => 'string'
    ];

     /**
     * Get the manager that owns the Campaign
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function manager(): BelongsTo
    {
        return $this->belongsTo(Manager::class, 'manager_id', 'id');
    }
}
