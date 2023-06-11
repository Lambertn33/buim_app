<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
        'id', 'campaign_id', 'leader_id', 'screening_date', 'prospect_names', 'prospect_telephone', 'prospect_national_id',
        'prospect_code','payment_plan_id', 'district', 'sector', 'village', 'cell', 'eligibility_status', 'confirmation_status', 'proposed_device_name',
        'total_amount_paid', 'screening_partner_id'
    ];

    protected $casts = [
        'id' => 'string',
        'campaign_id' => 'string',
        'leader_id' => 'string',
        'payment_plan_id' => 'string',
        'screening_partner_id' => 'string'
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
     * Get the paymentPlan that owns the Screening
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function paymentPlan(): BelongsTo
    {
        return $this->belongsTo(PaymentPlan::class, 'payment_plan_id', 'id');
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

    /**
     * Get all of the payments for the Screening
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function payments(): HasMany
    {
        return $this->hasMany(ScreeningPayment::class, 'screener_id', 'id');
    }

    /**
     * Get the installation associated with the Screening
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function installation(): HasOne
    {
        return $this->hasOne(ScreeningInstallation::class, 'screener_id', 'id');
    }

    /**
     * Get the device associated with the Screening
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function device(): HasOne
    {
        return $this->hasOne(WarehouseDevice::class, 'screener_id', 'id');
    }

    /**
     * Get the partner that owns the Screening
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function partner(): BelongsTo
    {
        return $this->belongsTo(ScreeningPartner::class, 'screening_partner_id', 'id');
    }
}
