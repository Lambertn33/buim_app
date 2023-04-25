<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class District extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $fillable = [
        'id', 'district', 'province_id', 'manager_id'
    ];

    protected $casts = [
        'id' => 'string',
        'province_id' => 'string',
        'manager_id' => 'string'
    ];

    /**
     * Get all of the warehouses for the District
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function warehouses(): HasMany
    {
        return $this->hasMany(Warehouse::class, 'district_id', 'id');
    }

    /**
     * Get the manager that owns the District
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function manager(): BelongsTo
    {
        return $this->belongsTo(Manager::class, 'manager_id', 'id');
    }

    /**
     * Get all of the campaigns for the District
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function campaigns(): HasMany
    {
        return $this->hasMany(Campaign::class, 'district_id', 'id');
    }
}
