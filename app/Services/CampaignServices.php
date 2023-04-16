<?php

namespace App\Services;

use App\Models\Campaign;
use App\Models\SubStockRequest;

class CampaignServices
{
   public function updateStockRequestStatusOnCampaignCompleted($campaign)
   {
        if ($campaign->status === Campaign::FINISHED) {
            $campaign->stockRequest->update([
                'request_status' => SubStockRequest::REQUESTED
            ]);
        }
   }
}
