<?php

namespace App\Observers;

use App\Models\WarehouseDeviceRequest;
use App\Services\WarehouseServices;

class WarehouseDeviceRequestObserver
{
    /**
     * Handle the WarehouseDeviceRequest "created" event.
     */
    public function created(WarehouseDeviceRequest $warehouseDeviceRequest): void
    {
        //
    }

    /**
     * Handle the WarehouseDeviceRequest "updated" event.
     */
    public function updated(WarehouseDeviceRequest $warehouseDeviceRequest): void
    {
        (new WarehouseServices)->notifyUserOnStatusUpdated($warehouseDeviceRequest);
    }

    /**
     * Handle the WarehouseDeviceRequest "deleted" event.
     */
    public function deleted(WarehouseDeviceRequest $warehouseDeviceRequest): void
    {
        //
    }

    /**
     * Handle the WarehouseDeviceRequest "restored" event.
     */
    public function restored(WarehouseDeviceRequest $warehouseDeviceRequest): void
    {
        //
    }

    /**
     * Handle the WarehouseDeviceRequest "force deleted" event.
     */
    public function forceDeleted(WarehouseDeviceRequest $warehouseDeviceRequest): void
    {
        //
    }
}
