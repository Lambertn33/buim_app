<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Leader extends Model
{
    // Leader is like sector leader to manage screenings
    use HasFactory;

    public $incrementing = false;

    protected $fillable = [
        'id', 'user_id', 'district_id', 'warehouse_id'
    ];

    protected $casts = [
        'id' => 'string',
        'user_id' => 'string',
        'district_id' => 'string',
        'warehouse_id' => 'string'
    ];

    /**
     * Get the user that owns the Manager
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Get the district that owns the Leader
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class, 'district_id', 'id');
    }

    /**
     * Get the warehouse that owns the Leader
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id', 'id');
    }

    /**
     * Get all of the screenings for the Leader
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function screenings(): HasMany
    {
        return $this->hasMany(Screening::class, 'leader_id', 'id');
    }
}
