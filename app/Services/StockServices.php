<?php

namespace App\Services;

use App\Models\Role;
use Illuminate\Support\Facades\Response;
use App\Models\StockModel;
use Illuminate\Support\Facades\Auth;
use App\Models\Campaign;
use App\Models\MainWarehouseDevice;
use Illuminate\Support\Str;

class StockServices
{
    // STOCK DEVICE MODEL SERVICES
    public function getSampleExcel()
    {
        $filepath = public_path('files/stock_sample.xlsx');
        return Response::download($filepath);
    }

    public function updateModelQuantityOnDeviceCreatedOrApproved($device)
    {
        $deviceQuantity = StockModel::where('id', $device->model_id)->value('quantity');
        StockModel::where('id', $device->model_id)->update([
            'quantity' => $deviceQuantity + 1
        ]);
    }
    public function updateModelQuantityOnDeviceDeleted($device)
    {
        $deviceQuantity = StockModel::where('id', $device->model_id)->value('quantity');
        StockModel::where('id', $device->model_id)->update([
            'quantity' => $deviceQuantity - 1
        ]);
    }

    //WAREHOUSES SERVICES
    public function transferMainWarehouseDevice($device, $warehouseId)
    {
        return MainWarehouseDevice::find($device->id)->update([
            'main_warehouse_id' => $warehouseId
        ]);
    }
}