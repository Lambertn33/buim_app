<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubStockRequest extends Model
{
    use HasFactory;

    public $incrementing = false;

    const SUB_STOCK_REQUESTSTATUS = ['INITIATED', 'REQUESTED', 'VERIFIED', 'CONTRACT_PRINTING', 'READY_FOR_LOADING', 'DELIVERED'];
    const SUB_STOCK_CONFIRMATION_STATUS = ['PENDING', 'RECEIVED'];

    const INITIATED = self::SUB_STOCK_REQUESTSTATUS[0];
    const REQUESTED = self::SUB_STOCK_REQUESTSTATUS[1];
    const VERIFIED = self::SUB_STOCK_REQUESTSTATUS[2];
    const CONTRACT_PRINTING = self::SUB_STOCK_REQUESTSTATUS[3];
    const READY_FOR_LOADING = self::SUB_STOCK_REQUESTSTATUS[4];
    const DELIVERED = self::SUB_STOCK_REQUESTSTATUS[5];

    const PENDING = self::SUB_STOCK_CONFIRMATION_STATUS[0];
    const RECEIVED = self::SUB_STOCK_CONFIRMATION_STATUS[1];

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

    /**
     * Get all of the requestedDevices for the SubStockRequest
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function requestedDevices(): HasMany
    {
        return $this->hasMany(SubStockRequestDevice::class, 'sub_stock_request_id', 'id');
    }
}