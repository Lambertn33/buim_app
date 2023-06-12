<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ScreeningPayment extends Model
{
    use HasFactory;

    public $incrementing = false;

    const PAYMENT_TYPE = ['DOWNPAYMENT', 'ADVANCED_PAYMENT'];

    const DOWNPAYMENT = self::PAYMENT_TYPE[0];

    const ADVANCED_PAYMENT = self::PAYMENT_TYPE[1];

    const PAYMENT_MODE = ['MANUAL', 'MTN', 'AIRTEL'];

    const MANUAL_PAYMENT = self::PAYMENT_MODE[0];

    const MOMO_PAYMENT = self::PAYMENT_MODE[1];

    const AIRTEL_PAYMENT = self::PAYMENT_MODE[2];

    protected $fillable = ['id', 'screener_id', 'amount', 'payment_type','payment_mode'];

    protected $casts = [
        'id' => 'string',
        'screener_id' => 'string',
    ];

    /**
     * Get the screener that owns the ScreeningPayment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function screener(): BelongsTo
    {
        return $this->belongsTo(Screening::class, 'screener_id', 'id');
    }

    /**
     * Get the token associated with the ScreeningPayment
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function token(): HasOne
    {
        return $this->hasOne(ScreeningToken::class, 'screening_payment_id', 'id');
    }
}
