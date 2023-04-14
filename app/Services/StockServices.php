<?php

namespace App\Services;

use App\Models\Role;
use App\Models\StockDevice;
use Illuminate\Support\Facades\Response;
use App\Models\StockModel;
use Illuminate\Support\Facades\Auth;

class StockServices
{
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

    public function updateStockDeviceInitialization($initializationCode)
    {
        $approval = '';
        if (Auth::user()->role->role == Role::ADMIN_ROLE) {
            $approval = Auth::user()->id;
        } else {
            $approval = Auth::user()->stockManager->id;
        }
        StockDevice::where('initialization_code', $initializationCode)->update([
            'is_approved' => true,
            'approved_by' => $approval
        ]);
    }
}
