<?php

namespace App\Services;

use App\Models\Role;
use Illuminate\Support\Facades\Response;
use App\Models\StockModel;
use Illuminate\Support\Facades\Auth;
use App\Models\Campaign;
use App\Models\DevicePrice;
use App\Models\MainWarehouse;
use App\Models\MainWarehouseDevice;
use App\Models\Warehouse;
use App\Models\WarehouseDevice;
use App\Models\WarehouseDeviceRequest;
use App\Models\WarehouseDeviceRequestDraft;
use App\Models\WarehouseDeviceRequestedDevice;
use App\Models\WarehouseDeviceTransfer;
use Filament\Notifications\Actions\Action as NotificationAction;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;

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
                'device_name' => $device->device_name,
                'serial_number' => $device->serial_number,
                'created_at' => now(),
                'updated_at' => now()
            ];
            WarehouseDevice::insert($newDistrictWarehouseDevice);
            $deviceToTransfer->delete();
        }
    }

    public function transferDistrictWarehouseDevice($device, $data)
    {

        $deviceSender = $device->warehouse;
        $deviceReceiver = $data['warehouse_id'];
        $reason = $data['reason'];

        $warehouseReceiver = Warehouse::find($data['warehouse_id']);
        $district = $warehouseReceiver->district;
        $districtManagers = $district->managers()->get();
        $pendingTitle = 'New device received';
        $pendingMessage = 'you received a new device with serial number ' . $device->serial_number . ' from ' . $device->warehouse->district->district . ' district ';

        $newDeviceTransfer = [
            'id' => Str::uuid()->toString(),
            'warehouse_sender_id' => $deviceSender->id,
            'warehouse_receiver_id' => $deviceReceiver,
            'serial_number' => $device->serial_number,
            'device_name' => $device->device_name,
            'description' => $reason,
            'created_at' => now(),
            'updated_at' => now()
        ];
        WarehouseDeviceTransfer::insert($newDeviceTransfer);
        $actions = [
            NotificationAction::make('Approve')
                ->color('success')
                ->emit('approveDistrictIncomingDevice', ['warehouse' => $warehouseReceiver, 'warehouseId' => $data['warehouse_id'], 'device' => $device])
                ->button()
                ->close(),
            NotificationAction::make('Reject')
                ->color('danger')
                ->emit('rejectDistrictIncomingDevice', ['device' => $device, 'deviceReceiver' => $deviceReceiver, 'deviceSender' => $deviceSender])
                ->button()
                ->close()
        ];
        foreach ($districtManagers as $manager) {
            (new NotificationsServices)->sendNotificationToUser($manager->user, $pendingTitle, $pendingMessage, $actions);
        }
    }

    public function approveDistrictIncomingDeviceListener($warehouse, $deviceReceiver, $device)
    {
        $device = WarehouseDevice::find($device['id']);
        $warehouse = Warehouse::with('district')->find($deviceReceiver);
        WarehouseDeviceTransfer::where('serial_number', $device->serial_number)->where('warehouse_receiver_id', $deviceReceiver)->update([
            'status' => WarehouseDeviceTransfer::APPROVED
        ]);
        WarehouseDevice::find($device->id)->update([
            'district_id' => $warehouse->district->id,
            'warehouse_id' => $deviceReceiver,
        ]);
    }

    public function rejectDistrictIncomingDeviceListener($device, $deviceReceiver, $deviceSender)
    {
        $device = WarehouseDevice::find($device['id']);
        $warehouseReceiver = Warehouse::with('district')->find($deviceReceiver);
        $warehouseSender = Warehouse::with('district')->find($deviceSender['id']);
        $managers = $warehouseSender->district->managers()->get();

        WarehouseDeviceTransfer::where('serial_number', $device->serial_number)->where('warehouse_receiver_id', $deviceReceiver)->update([
            'status' => WarehouseDeviceTransfer::REJECTED
        ]);

        $title = 'Device Rejected';
        $message = 'a device with serial number ' . $device->serial_number . ' sent to ' . $warehouseReceiver->district->district . ' has been declined and returned back to initial warehouse';

        $actions = [
            NotificationAction::make('Mark as Read')
                ->color('primary')
                ->button()
                ->close(),

        ];
        foreach ($managers as $managerSender) {
            (new NotificationsServices)->sendNotificationToUser($managerSender->user, $title, $message, $actions);
        }
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
        $device = MainWarehouseDevice::where('device_name', $screener['proposed_device_name'])->whereHas('mainWarehouse', function ($query) {
            $query->where('name', MainWarehouse::DPWORLDWAREHOUSE)
                ->where('is_approved', true);
        })->first();
        $deviceModel = $device->model;
        $campaign = Campaign::find($screener['campaign_id']);

        if (WarehouseDeviceRequest::where('campaign_id', $campaign->id)->exists()) {
            $campaignWarehouseRequest = $campaign->warehouseDeviceRequest;
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

    public function approveCampaignRequestedDevices($device, $warehouseId, $request)
    {
        $randomDevice = MainWarehouseDevice::whereHas('mainWarehouse', function ($query) {
            $query->where('name', MainWarehouse::RUGANDOWAREHOUSE);
        })->where('device_name', $device->device_name)->first();

        $newWarehouseDevice = [
            'id' => Str::uuid()->toString(),
            'model_id' => $randomDevice->model->id,
            'warehouse_device_request_id' => $request->id,
            'warehouse_id' => $warehouseId,
            'screener_code' => $device->screener_code,
            'device_id' => $randomDevice->id,
            'quantity' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ];
        WarehouseDeviceRequestDraft::insert($newWarehouseDevice);
        WarehouseDeviceRequest::find($request->id)->update([
            'request_status' => WarehouseDeviceRequest::DELIVERED
        ]);
    }

    public function setDevicePrice($deviceName, $devicePrice) {
        $devices =  MainWarehouseDevice::where('device_name', $deviceName)->get();
        foreach ($devices as $device) {
            $device->update([
                'device_price'=> $devicePrice
            ]);
        }
    }

    public function updateDevicePriceOnImport($device) {
        $devicePrice = DevicePrice::where('device_name', $device->device_name);
        if ($devicePrice->exists()) {
            $device->update([
                'device_price' =>$devicePrice->value('device_price')
            ]);
        }
    }
}
