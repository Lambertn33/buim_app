<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class WarehouseDeviceTransfer extends Model
{
    use HasFactory;

    public $incrementing = false;

    // this is normal transfer between districts

    const STATUS = ['PENDING','APPROVED', 'REJECTED'];

    const PENDING = self::STATUS[0];
    const APPROVED = self::STATUS[1];
    const REJECTED = self::STATUS[2];

    protected $fillable = ['id','warehouse_sender_id', 'manager_sender_id', 'warehouse_receiver_id','manager_receiver_id', 'device_name','serial_number', 'description', 'status'];

    protected $casts = [
        'id' => 'string',
        'warehouse_sender_id' => 'string',
        'warehouse_receiver_id' => 'string',
        'manager_sender_id' => 'string', 
        'manager_receiver_id' => 'string'
    ];

    /**
     * Get the sender that owns the WarehouseDeviceTransfer
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_sender_id', 'id');
    }

    /**
     * Get the receiver that owns the WarehouseDeviceTransfer
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function receiver(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_receiver_id', 'id');
    }

    public function sentBy()
    {
        if (Auth::user()->role->role === Role::DISTRICT_MANAGER_ROLE) {
            if (Auth::user()->manager->id == $this->manager_sender_id) {
                return 'Me';
            } else {
                return $this->sender->district->district.' District';
            }
        } else {
            return $this->sender->district->district.' District';
        }
    }

    public function receivedBy()
    {
        if (Auth::user()->role->role === Role::DISTRICT_MANAGER_ROLE) {
            if (Auth::user()->manager->id == $this->manager_receiver_id) {
                return 'Me';
            } else {
                return $this->receiver->district->district.' District';
            }
        } else {
            return $this->receiver->district->district.' District';
        }
    }
}
