<?php

namespace App\Services;

use App\Models\Role;
use Illuminate\Support\Facades\Response;
use App\Models\StockModel;
use Illuminate\Support\Facades\Auth;
use App\Models\Campaign;
use App\Models\MainWarehouse;
use App\Models\MainWarehouseDevice;
use App\Models\Warehouse;
use App\Models\WarehouseDevice;
use App\Models\WarehouseDeviceRequest;
use App\Models\WarehouseDeviceRequestedDevice;
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

    // STOCK INITIALIZATION SERVICES
    public function updateStockDeviceInitialization($initializationCode)
    {
        $approval = '';
        if (Auth::user()->role->role == Role::ADMIN_ROLE) {
            $approval = Auth::user()->id;
        } else {
            $approval = Auth::user()->stockManager->id;
        }
        MainWarehouseDevice::where('initialization_code', $initializationCode)->update([
            'is_approved' => true,
            'approved_by' => $approval
        ]);
    }

    //WAREHOUSES  SERVICES
    public function transferMainWarehouseDevice($device, $warehouseId, $warehouseType)
    {
        $deviceToTransfer = MainWarehouseDevice::with('model')->find($device->id);
        if ($warehouseType === "Main warehouse") {
            // transfer from main warehouse to other main warehouse
            return $deviceToTransfer->update([
                'main_warehouse_id' => $warehouseId
            ]);
        } else {
            //transfer from main warehouse to district warehouse
            $districtWarehouse = Warehouse::find($warehouseId);
            $newDistrictWarehouseDevice = [
                'id' => Str::uuid()->toString(),
                'model_id' => $deviceToTransfer->model->id,
                'warehouse_id' => $warehouseId,
                'district_id' => $districtWarehouse->district->id,
                'manager_id' => $districtWarehouse->manager == null ? null : $districtWarehouse->manager->id,
                'device_name' => $device->device_name,
                'serial_number' => $device->serial_number,
                'created_at' => now(),
                'updated_at' => now()
            ];
            WarehouseDevice::insert($newDistrictWarehouseDevice);
            $deviceToTransfer->delete();
        }
    }

    public function transferDistrictWarehouseDevice($device, $warehouseId) {
        $warehouse = Warehouse::find($warehouseId);
        $manager = $warehouse->manager->user;
        $title = 'New device received';
        $message = 'you have received a new device from '. $device->warehouse->district->district .' district ';

        WarehouseDevice::find($device->id)->update([
            'district_id' => $warehouse->district->id,
            'warehouse_id' => $warehouseId,
            'manager_id' => $warehouse->manager->id
        ]);
        (new NotificationsServices)->sendNotificationToUser($manager, $title, $message);
    }

    //WAREHOUSE DEVICES REQUESTS
    public function getLastSubStockRequestIndex()
    {
        $index = 1;
        if (count(WarehouseDeviceRequest::get()) > 0) {
            $index = intval(WarehouseDeviceRequest::max('request_id'));
        }
        return $index;
    }

    public function createWarehouseDeviceRequest($screener)
    {
        $device = MainWarehouseDevice::where('device_name', $screener['proposed_device_name'])->whereHas('mainWarehouse', function($query){
            $query->where('name', MainWarehouse::DPWORLDWAREHOUSE)
                ->where('is_approved', true);
        })->first();
        $deviceModel = $device->model;
        $campaign = Campaign::find($screener['campaign_id']);

        if (WarehouseDeviceRequest::where('campaign_id', $campaign->id)->exists()) {
            $campaignWarehouseRequest = $campaign->warehouseDeviceRequest;
            $checkDeviceExistence = WarehouseDeviceRequestedDevice::where('warehouse_device_request_id', $campaignWarehouseRequest->id)
                ->where('device_name', $screener['proposed_device_name']);
            if ($checkDeviceExistence->exists()) {
                $checkDeviceExistence->update([
                    'quantity' => $checkDeviceExistence->value('quantity') + 1
                ]);

            } else {
                $newWarehouseRequestedDevice = [
                    'id' => Str::uuid()->toString(),
                    'model_id' => $deviceModel->id,
                    'warehouse_device_request_id' => $campaignWarehouseRequest->id,
                    'screener_code' => $screener['prospect_code'],
                    'device_name' => $screener['proposed_device_name'],
                    'quantity' => 1,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
                WarehouseDeviceRequestedDevice::insert($newWarehouseRequestedDevice);
            }
        } else {
            $newWarehouseDeviceRequest = [
                'id' => Str::uuid()->toString(),
                'campaign_id' => $screener['campaign_id'],
                'request_id' => $this->getLastSubStockRequestIndex(),
                'created_at' => now(),
                'updated_at' => now()
            ];
            $newWarehouseRequestedDevice = [
                'id' => Str::uuid()->toString(),
                'model_id' => $deviceModel->id,
                'warehouse_device_request_id' => $newWarehouseDeviceRequest['id'],
                'screener_code' => $screener['prospect_code'],
                'device_name' => $screener['proposed_device_name'],
                'quantity' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ];
            WarehouseDeviceRequest::insert($newWarehouseDeviceRequest);
            WarehouseDeviceRequestedDevice::insert($newWarehouseRequestedDevice);
        }
    }
}