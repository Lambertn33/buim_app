<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockManager extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $fillable = [
        'id', 'user_id'
    ];

    protected $casts = [
        'id' => 'string',
        'user_id' => 'string'
    ];

    /**
     * Get the user that owns the StockManager
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
