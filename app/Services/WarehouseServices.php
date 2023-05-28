<?php

namespace App\Services;

use App\Models\MainWarehouse;
use App\Models\MainWarehouseDevice;
use App\Models\Role;
use App\Models\User;
use App\Models\Warehouse;
use App\Models\WarehouseDevice;
use App\Models\WarehouseDeviceRequest;
use App\Models\WarehouseDeviceRequestDraft;
use Illuminate\Support\Facades\Auth;

class WarehouseServices
{
    public function notifyUserOnStatusUpdated($warehouseDeviceRequest)
    {
        $districtManager = $warehouseDeviceRequest->campaign->manager->user;
        $otherUsers = User::whereHas('role', function ($query) {
            $query->where('role', Role::STOCK_MANAGER_ROLE)->orWhere('role', Role::ADMIN_ROLE);
        })->get();
        $formattedRequestId = sprintf("%08d", $warehouseDeviceRequest->request_id);
        // if admin/stock manager changes request status
        if ($warehouseDeviceRequest->request_status !== WarehouseDeviceRequest::REQUESTED && $warehouseDeviceRequest->request_status !== WarehouseDeviceRequest::DELIVERED) {
            $title = 'Campaign request # ' . $formattedRequestId . ' updated';
            $message = 'The campaign request has been viewed and updated to ' . $warehouseDeviceRequest->request_status . '';
            (new NotificationsServices)->sendNotificationToUser($districtManager, $title, $message, []);

            // if district manager changes confirmation status
        } else if ($warehouseDeviceRequest->confirmation_status === WarehouseDeviceRequest::RECEIVED && $warehouseDeviceRequest->request_status === WarehouseDeviceRequest::DELIVERED) {
            $devices = WarehouseDeviceRequestDraft::where('warehouse_device_request_id', $warehouseDeviceRequest->id)->get();
            foreach ($devices as $device) {
                $mainDevice = MainWarehouseDevice::whereHas('mainWarehouse', function ($query) {
                    $query->where('name', MainWarehouse::RUGANDOWAREHOUSE);
                })->where('id', $device->device_id)->first();
                $newWarehouseDevice = [
                    'id' => $device->id,
                    'model_id' => $device->model_id,
                    'warehouse_id' => $device->warehouse_id,
                    'district_id' => $warehouseDeviceRequest->campaign->district->id,
                    'screener_id' => null,
                    'device_name' => $mainDevice->device_name,
                    'device_price' => $mainDevice->device_price,
                    'serial_number' => $mainDevice->serial_number,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
                WarehouseDevice::insert($newWarehouseDevice);
                MainWarehouseDevice::whereHas('mainWarehouse', function ($query) {
                    $query->where('name', MainWarehouse::RUGANDOWAREHOUSE);
                })->where('id', $device->device_id)->delete();
            }
            $title = 'Campaign request # ' . $formattedRequestId . ' updated';
            $message = 'The stock request for campaign request # ' . $formattedRequestId . ' has been confirmed and received by the district manager of ' .
                $warehouseDeviceRequest->campaign->district->district . ' district and confirmed by' . Auth::user()->name. '';
            foreach ($otherUsers as $user) {
                (new NotificationsServices)->sendNotificationToUser($user, $title, $message, []);
            }
        }
    }
}
