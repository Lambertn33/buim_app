<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WarehouseDeviceRequest extends Model
{
    use HasFactory;

    public $incrementing = false;

    const STATUS = ['REQUESTED', 'VERIFIED', 'CONTRACT_PRINTING', 'READY_FOR_LOADING', 'DELIVERED'];

    const REQUESTED = self::STATUS[0];
    const VERIFIED = self::STATUS[1];
    const CONTRACT_PRINTING = self::STATUS[2];
    const READY_FOR_LOADING = self::STATUS[3];
    const DELIVERED = self::STATUS[4];

    protected $fillable = [
        'id', 'model_id', 'campaign_id', 'quantity', 'status', 'denied_note'
    ];
    protected $casts = [
        'id' => 'string',
        'campaign_id' => 'string',
        'model_id' => 'string'
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
     * Get the model that owns the SubStockRequest
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function model(): BelongsTo
    {
        return $this->belongsTo(StockModel::class, 'model_id', 'id');
    }
}
