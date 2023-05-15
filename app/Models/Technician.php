<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Technician extends Model
{
    use HasFactory;

    public $incrementing = false;

    const STATUS = ['ACTIVE', 'INACTIVE'];

    const ACTIVE = self::STATUS[0];

    const INACTIVE = self::STATUS[1];

    protected $fillable = [
        'id', 'names', 'district_id', 'telephone', 'status'
    ];

    /**
     * Get the district that owns the Technician
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class, 'district_id', 'id');
    }
}
