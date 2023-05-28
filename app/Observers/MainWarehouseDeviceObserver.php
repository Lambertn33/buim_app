<?php

namespace App\Observers;

use App\Models\MainWarehouseDevice;
use App\Services\StockServices;

class MainWarehouseDeviceObserver
{
    /**
     * Handle the MainWarehouseDevice "created" event.
     */
    public function created(MainWarehouseDevice $mainWarehouseDevice): void
    {
        (new StockServices)->updateDevicePriceOnImport($mainWarehouseDevice);
    }

    /**
     * Handle the MainWarehouseDevice "updated" event.
     */
    public function updated(MainWarehouseDevice $mainWarehouseDevice): void
    {
        //
    }

    /**
     * Handle the MainWarehouseDevice "deleted" event.
     */
    public function deleted(MainWarehouseDevice $mainWarehouseDevice): void
    {
        //
    }

    /**
     * Handle the MainWarehouseDevice "restored" event.
     */
    public function restored(MainWarehouseDevice $mainWarehouseDevice): void
    {
        //
    }

    /**
     * Handle the MainWarehouseDevice "force deleted" event.
     */
    public function forceDeleted(MainWarehouseDevice $mainWarehouseDevice): void
    {
        //
    }
}
