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

    const STOCKREQUESTSTATUS = ['INITIATED', 'REQUESTED', 'VERIFIED', 'CONTRACT_PRINTING', 'READY_FOR_LOADING', 'DELIVERED'];
    const STOCKCONFIRMATIONSTATUS = ['PENDING', 'RECEIVED'];

    const INITIATED = self::STOCKREQUESTSTATUS[0];
    const REQUESTED = self::STOCKREQUESTSTATUS[1];
    const VERIFIED = self::STOCKREQUESTSTATUS[2];
    const CONTRACT_PRINTING = self::STOCKREQUESTSTATUS[3];
    const READY_FOR_LOADING = self::STOCKREQUESTSTATUS[4];
    const DELIVERED = self::STOCKREQUESTSTATUS[5];

    const PENDING = self::STOCKCONFIRMATIONSTATUS[0];
    const RECEIVED = self::STOCKCONFIRMATIONSTATUS[1];

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