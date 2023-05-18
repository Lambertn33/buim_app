<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Manager extends Model
{
    // Manager is like district manager to manage campaigns in certain district
    use HasFactory;

    public $incrementing = false;
    
    protected $fillable = [
        'id', 'user_id', 'district_id'
    ];

    protected $casts = [
        'id' => 'string',
        'user_id' => 'string',
        'district_id' => 'string'
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
     * Get the district that owns the Manager
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class, 'district_id', 'id');
    }

    /**
     * Get all of the campaigns for the Manager
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function campaigns(): HasMany
    {
        return $this->hasMany(Campaign::class, 'manager_id', 'id');
    }

    /**
     * Get all of the warehouses for the Manager
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function warehouses(): HasMany
    {
        return $this->hasMany(Warehouse::class, 'manager_id', 'id');
    }
}
