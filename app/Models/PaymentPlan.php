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

}
