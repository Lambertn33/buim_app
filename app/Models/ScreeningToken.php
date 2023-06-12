<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScreeningToken extends Model
{
    use HasFactory;

    public $incrementing = false;

    const STATUS = ['SUCCEEDED', 'FAILED'];

    const SUCCEEDED = self::STATUS[0];

    const FAILED = self::STATUS[1];

    protected $fillable = [
        'id', 'screening_payment_id', 'key', 'token', 'validity_days', 'status',
    ];

    protected $casts = [
        'id' => 'string',
        'screening_payment_id' => 'string'
    ];

    /**
     * Get the payment that owns the Screening_token
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function payment(): BelongsTo
    {
        return $this->belongsTo(ScreeningPayment::class, 'screening_payment_id', 'id');
    }
}
