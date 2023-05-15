<?php

namespace App\Services;

use App\Models\Campaign;
use App\Models\Role;
use App\Models\User;
use App\Models\WarehouseDeviceRequest;

class CampaignServices
{
   public function updateWarehouseDevicesRequestStatusOnCampaignCompleted($campaign)
   {
        if ($campaign->status === Campaign::FINISHED) {
            $campaign->warehouseDeviceRequest->update([
                'request_status' => WarehouseDeviceRequest::REQUESTED
            ]);
            // send notification to users/stock manager that campaign has been finished
            $users = User::whereHas('role', function($query){
                $query->where('role', Role::STOCK_MANAGER_ROLE)->orWhere('role', Role::ADMIN_ROLE);
            })->get();
            $title = 'Campaign Completed';
            $message = 'The Campaign named '. $campaign->title .' which was taking place in '. $campaign->district->district .' 
            District, has been completed and has requested '. $campaign->warehouseDeviceRequest->requestedDevices->count() .' devices';
            $actions = [];
            foreach($users as $user) {
                (new NotificationsServices)->sendNotificationToUser($user, $title, $message, $actions);
            }
        }
   }
}
