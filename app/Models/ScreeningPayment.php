<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScreeningPayment extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $fillable = ['id', 'screener_id', 'payment_plan_id', 'amount_paid','remaining_months_to_pay', 'remaining_amount', 'next_payment_date'];

    protected $casts = [
        'id' => 'string',
        'screener_id' => 'string',
        'payment_plan_id' => 'string'
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
}
