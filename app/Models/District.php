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
        'id', 'district', 'province_id',
    ];

    protected $casts = [
        'id' => 'string',
        'province_id' => 'string',
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
     * Get all of the managers for the District
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function managers(): HasMany
    {
        return $this->hasMany(Manager::class, 'district_id', 'id');
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

    /**
     * Get all of the technicians for the District
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function technicians(): HasMany
    {
        return $this->hasMany(Technician::class, 'district_id', 'id');
    }

    /**
     * Get all of the leaders for the District
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function leaders(): HasMany
    {
        return $this->hasMany(Leader::class, 'district_id', 'id');
    }
}
