<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class District extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $fillable = [
        'id', 'district', 'province_id'
    ];

    protected $casts = [
        'id' => 'string',
        'province_id' => 'string'
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
}
