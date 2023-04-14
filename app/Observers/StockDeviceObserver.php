<?php

namespace App\Observers;

use App\Models\StockDevice;
use App\Services\StockServices;

class StockDeviceObserver
{
    /**
     * Handle the StockDevice "created" event.
     */
    public function created(StockDevice $stockDevice): void
    {
        (new StockServices)->updateModelQuantityOnDeviceCreatedOrApproved($stockDevice);
    }

    /**
     * Handle the StockDevice "updated" event.
     */
    public function updated(StockDevice $stockDevice): void
    {
        (new StockServices)->updateModelQuantityOnDeviceCreatedOrApproved($stockDevice);
    }

    /**
     * Handle the StockDevice "deleted" event.
     */
    public function deleted(StockDevice $stockDevice): void
    {
        (new StockServices)->updateModelQuantityOnDeviceDeleted($stockDevice);
    }

    /**
     * Handle the StockDevice "restored" event.
     */
    public function restored(StockDevice $stockDevice): void
    {
        //
    }

    /**
     * Handle the StockDevice "force deleted" event.
     */
    public function forceDeleted(StockDevice $stockDevice): void
    {
        //
    }
}
