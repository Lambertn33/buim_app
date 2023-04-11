<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaymentPlan extends Model
{
    use HasFactory;

    public $incrementing = false;
    
    protected $fillable = [
        'id','title', 'amount', 'duration'
    ];

    protected $casts = [
        'id' => 'string'
    ];

    /**
     * Get all of the screenings for the PaymentPlan
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function screenings(): HasMany
    {
        return $this->hasMany(Screening::class, 'payment_id', 'id');
    }
}
