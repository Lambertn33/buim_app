<?php

namespace App\Services;

use App\Models\Role;
use App\Models\StockDevice;
use Illuminate\Support\Facades\Response;
use App\Models\StockModel;
use Illuminate\Support\Facades\Auth;
use App\Models\Campaign;
use App\Models\SubStockDevice;
use App\Models\SubStockRequest;
use App\Models\SubStockRequestDevice;
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

    //SUBSTOCK REQUESTS AND DEVICES SERVICES
    public function getLastSubStockRequestIndex()
    {
        $index = 1;
        if (count(SubStockRequest::get()) > 0) {
            $index = intval(SubStockRequest::max('request_id'));
        }
        return $index;
    }
    public function createSubStockRequest($screener)
    {
        $device = StockDevice::where('device_name', $screener['proposed_device_name'])->first();
        $deviceModel = $device->model;
        $campaign = Campaign::find($screener['campaign_id']);

        if (SubStockRequest::where('campaign_id', $campaign->id)->exists()) {
            $campaignSubstockRequest = $campaign->stockRequest;
            $checkDeviceExistence = SubStockRequestDevice::where('sub_stock_request_id', $campaignSubstockRequest->id)
                ->where('device_name', $screener['proposed_device_name']);
            if ($checkDeviceExistence->exists()) {
                $checkDeviceExistence->update([
                    'quantity' => $checkDeviceExistence->value('quantity') + 1
                ]);
                
            } else {
                $newSubStockRequestedDevice = [
                    'id' => Str::uuid()->toString(),
                    'model_id' => $deviceModel->id,
                    'sub_stock_request_id' => $campaignSubstockRequest->id,
                    'screener_code' => $screener['prospect_code'],
                    'device_name' => $screener['proposed_device_name'],
                    'quantity' => 1,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
                SubStockRequestDevice::insert($newSubStockRequestedDevice);
            }
        } else {
            $newSubStockRequest = [
                'id' => Str::uuid()->toString(),
                'campaign_id' => $screener['campaign_id'],
                'request_id' => $this->getLastSubStockRequestIndex(),
                'created_at' => now(),
                'updated_at' => now()
            ];
            $newSubStockRequestedDevice = [
                'id' => Str::uuid()->toString(),
                'model_id' => $deviceModel->id,
                'sub_stock_request_id' => $newSubStockRequest['id'],
                'screener_code' => $screener['prospect_code'],
                'device_name' => $screener['proposed_device_name'],
                'quantity' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ];
            SubStockRequest::insert($newSubStockRequest);
            SubStockRequestDevice::insert($newSubStockRequestedDevice);
        }
    }
}
