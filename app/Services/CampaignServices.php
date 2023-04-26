<?php

namespace App\Services;

use App\Models\Campaign;
use App\Models\WarehouseDeviceRequest;

class CampaignServices
{
   public function updateWarehouseDevicesRequestStatusOnCampaignCompleted($campaign)
   {
        if ($campaign->status === Campaign::FINISHED) {
            $campaign->warehouseDeviceRequest->update([
                'request_status' => WarehouseDeviceRequest::REQUESTED
            ]);
        }
   }
}
