<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MainWarehouseDevice extends Model
{
    use HasFactory;
    
    public $incrementing = false;

    protected $fillable = [
        'id', 'model_id', 'main_warehouse_id' ,'device_name', 'serial_number', 'initialization_code', 'is_approved',
        'initialized_by', 'approved_by'
    ];

    protected $casts = [
        'id' => 'string',
        'model_id' => 'string',
        'initialized_by' => 'string',
        'main_warehouse_id' => 'string',
        'approved_by' => 'string'
    ];

    /**
     * Get the model that owns the MainWarehouseDevice
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function model(): BelongsTo
    {
        return $this->belongsTo(StockModel::class, 'model_id', 'id');
    }

}
