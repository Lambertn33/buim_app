<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubStockRequest extends Model
{
    use HasFactory;

    public $incrementing = false;

    const STOCKREQUESTSTATUS = ['PENDING', 'PROCESSED', 'DENIED', 'COMPLETED'];

    const PENDING = self::STOCKREQUESTSTATUS[0];
    const PROCESSED = self::STOCKREQUESTSTATUS[1];
    const COMPLETED = self::STOCKREQUESTSTATUS[2];

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
}