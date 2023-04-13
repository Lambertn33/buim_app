<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubStockDevice extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $fillable = [
        'id', 'campaign_id', 'model_id', 'name', 'serial_number', 'screener_id'
    ];

    protected $casts = [
        'id' => 'string',
        'model_id' => 'string',
        'screener_id' => 'string',
        'campaign_id' => 'string'
    ];

    /**
     * Get the model that owns the StockDevice
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function model(): BelongsTo
    {
        return $this->belongsTo(StockModel::class, 'model_id', 'id');
    }

    /**
     * Get the screener that owns the StockDevice
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function screener(): BelongsTo
    {
        return $this->belongsTo(Screening::class, 'screener_id', 'id');
    }

    /**
     * Get the campaign that owns the SubStockDevice
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class, 'campaign_id', 'id');
    }
}
