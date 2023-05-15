<?php

namespace App\Services;

use App\Models\Role;
use App\Models\User;
use App\Models\WarehouseDeviceRequest;

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
        if ($warehouseDeviceRequest->request_status !== WarehouseDeviceRequest::REQUESTED) {
            $title = 'Campaign request # ' . $formattedRequestId . ' updated';
            $message = 'The campaign request has been viewed and updated to ' . $warehouseDeviceRequest->request_status . '';
            (new NotificationsServices)->sendNotificationToUser($districtManager, $title, $message, []);

            // if district manager changes confirmation status
        } else if ($warehouseDeviceRequest->confirmation_status === WarehouseDeviceRequest::RECEIVED && $warehouseDeviceRequest->request_status === WarehouseDeviceRequest::DELIVERED) {
            $title = 'Campaign request # ' . $formattedRequestId . ' updated';
            $message = 'The stock request for campaign request # ' . $formattedRequestId . ' has been confirmed and received by the district manager of ' .
                $warehouseDeviceRequest->campaign->district->district . ' district';
            foreach ($otherUsers as $user) {
                (new NotificationsServices)->sendNotificationToUser($user, $title, $message, []);
            }
        }
    }
}
