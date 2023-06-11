<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ScreeningPartner extends Model
{
    use HasFactory;

    protected $fillable = [
        'id', 'name', 'description'
    ];

    protected $casts = [
        'id' => 'string'
    ];

    /**
     * Get all of the screenings for the ScreeningPartner
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function screenings(): HasMany
    {
        return $this->hasMany(Screening::class, 'screening_partner_id', 'id');
    }
}
