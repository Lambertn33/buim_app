<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WarehouseDeviceRequest extends Model
{
    use HasFactory;

    public $incrementing = false;

    const REQUEST_STATUS = ['INITIATED','REQUESTED', 'VERIFIED', 'CONTRACT_PRINTING', 'READY_FOR_LOADING', 'DELIVERED'];
    const CONFIRMATION_STATUS = ['PENDING', 'RECEIVED'];

    const INITIATED = self::REQUEST_STATUS[0];
    const REQUESTED = self::REQUEST_STATUS[1];
    const VERIFIED = self::REQUEST_STATUS[2];
    const CONTRACT_PRINTING = self::REQUEST_STATUS[3];
    const READY_FOR_LOADING = self::REQUEST_STATUS[4];
    const DELIVERED = self::REQUEST_STATUS[5];

    const PENDING = self::CONFIRMATION_STATUS[0];
    const RECEIVED = self::CONFIRMATION_STATUS[1];

    protected $fillable = [
        'id', 'campaign_id', 'request_id', 'request_status', 'confirmation_status' 
    ];
    protected $casts = [
        'id' => 'string',
        'campaign_id' => 'string',
    ];

     /**
     * Get the campaign that owns the StockRequest
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class, 'campaign_id', 'id');
    }
}
