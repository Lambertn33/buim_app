<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubStockRequestDevice extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $fillable = [
        'id', 'sub_stock_request_id', 'screener_code', 'device_name', 'model_id', 'quantity'
    ];

    protected $casts = [
        'id' => 'string',
        'sub_stock_request_id' => 'string',
        'model_id' => 'string'
    ];

    /**
     * Get the model that owns the SubStockRequestDevice
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function subStockRequest(): BelongsTo
    {
        return $this->belongsTo(SubStockRequest::class, 'sub_stock_request_id', 'id');
    }

    /**
     * Get the model that owns the SubStockRequestDevice
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function model(): BelongsTo
    {
        return $this->belongsTo(StockModel::class, 'model_id', 'id');
    }

    public function getScreenedPerson()
    {
        return Screening::where('prospect_code', $this->screener_code);
    }
}
