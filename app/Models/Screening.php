<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Screening extends Model
{
    use HasFactory;

    public $incrementing = false;

    const ELIGIBILITY_STATUS = ['ELIGIBLE', 'NOT ELIGIBLE'];
    const CONFIRMATION_STATUS = ['PROSPECT', 'PRE REGISTERED', 'ACTIVE CUSTOMER'];

    const ELIGIBLE = self::ELIGIBILITY_STATUS[0];
    const NOT_ELIGIBLE = self::ELIGIBILITY_STATUS[1];

    const PROSPECT = self::CONFIRMATION_STATUS[0];
    const PRE_REGISTERED = self::CONFIRMATION_STATUS[1];
    const ACTIVE_CUSTOMER = self::CONFIRMATION_STATUS[2];

    protected $fillable = [
        'id', 'campaign_id','manager_id','leader_id', 'screening_date', 'prospect_names','prospect_telephone', 'prospect_national_id',
        'prospect_code', 'district','sector','village','cell', 'eligibility_status', 'confirmation_status', 'proposed_device_name'
    ];

    protected $casts = [
        'id' => 'string',
        'campaign_id' => 'string',
        'manager_id' => 'string',
        'leader_id' => 'string'
    ];
    
    /**
     * Get the campaign that owns the Screening
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class, 'campaign_id', 'id');
    }

    /**
     * Get the sectorLeader that owns the Screening
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function leader(): BelongsTo
    {
        return $this->belongsTo(Leader::class, 'leader_id', 'id');
    }

    public function getScreeningProvince()
    {
        return $this->campaign->province;
    }

    /**
     * Get the distribution associated with the Screening
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function distribution(): HasOne
    {
        return $this->hasOne(WarehouseDeviceDistribution::class, 'screener_id', 'id');
    }
}
