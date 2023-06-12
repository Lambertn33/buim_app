<?php

namespace App\Services;

use App\Models\PaymentPlan;
use App\Models\Screening;
use App\Models\ScreeningInstallation;
use App\Models\ScreeningPayment;
use App\Models\WarehouseDevice;
use App\Models\WarehouseDeviceDistribution;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ScreeningServices
{

    public function getLastDistributionIndex()
    {
        $index = 1;
        if (count(WarehouseDeviceDistribution::get()) > 0) {
            $index = count(WarehouseDeviceDistribution::get()) + 1;
        }
        return $index;
    }

    public function createScreeningDistribution($distribution)
    {
        $screener = Screening::find($distribution['screener_id']);
        $screenerCode = $screener->prospect_code;
        $newDistribution = [
            'id' => Str::uuid()->toString(),
            'warehouse_device_id' => $distribution['warehouse_device_id'],
            'screener_id' => $distribution['screener_id'],
            'contract_id' => $screenerCode . '-' . sprintf("%03d", $this->getLastDistributionIndex()),
            'created_at' => now(),
            'updated_at' => now()
        ];
        WarehouseDeviceDistribution::insert($newDistribution);
        $this->createScreeningPayment($distribution, $screener);
    }

    public function createScreeningPayment($payment, $screener)
    {
        $initialPayment = $payment['downpayment_amount'];
        $remainingPaymentDays = ($payment['duration']);
        $newPayment = [
            'id' => Str::uuid()->toString(),
            'screener_id' => $payment['screener_id'],
            'amount' => $initialPayment,
            'payment_type' => ScreeningPayment::ADVANCED_PAYMENT,
            'payment_mode' => ScreeningPayment::MANUAL_PAYMENT,
            // token generation
            'token' => '1234',
            'remaining_days' => $remainingPaymentDays,
            'created_at' => now(),
            'updated_at' => now()
        ];
        ScreeningPayment::insert($newPayment);
        $screener->update([
            'total_amount_paid' => $screener->total_amount_paid + $initialPayment
        ]);
        $this->createScreeningInstallation($payment);
    }

    public function createScreeningInstallation($screener)
    {
        $newInstallation = [
            'id' => Str::uuid()->toString(),
            'screener_id' => $screener['screener_id'],
            'created_at' => now(),
            'updated_at' => now(),
        ];
        Screening::find($screener['screener_id'])->update([
            'confirmation_status' => Screening::PRE_REGISTERED
        ]);
        WarehouseDevice::where('id', $screener['warehouse_device_id'])->update([
            'screener_id' => $screener['screener_id']
        ]);
        ScreeningInstallation::insert($newInstallation);
    }

    public function installScreeningDevice($installationData, $screeningInstallationId)
    {
        $screeningInstallation = ScreeningInstallation::find($screeningInstallationId);
        $screeningInstallation->update([
            'latitude' => $installationData['latitude'],
            'longitude' => $installationData['longitude'],
            'technician_id' => $installationData['technician_id'],
            'installation_status' => ScreeningInstallation::INSTALLATION_INSTALLED
        ]);
    }

    public function verifyScreeningDevice($screeningInstallationId)
    {
        $screeningInstallation = ScreeningInstallation::find($screeningInstallationId);
        $screener = $screeningInstallation->screening;
        $screeningInstallation->update([
            'verified_by' => Auth::user()->leader->id,
            'verification_status' => ScreeningInstallation::VERIFICATION_VERIFIED
        ]);
        $screener->update([
            'confirmation_status' => Screening::ACTIVE_CUSTOMER
        ]);
    }

    public function addNewScreeningPayment($screener, $amount)
    {
        // $newPayment = [
        //     'id' => Str::uuid()->toString(),
        //     'screener_id' => $screener->id,
        //     'amount' => $amount,
        //     'payment_type' => ScreeningPayment::DOWNPAYMENT,
        //     'payment_mode' => ScreeningPayment::MANUAL_PAYMENT,
        //     'token' => '1234',
        //     'remaining_days' => $remainingPaymentDays,
        //     'created_at' => now(),
        //     'updated_at' => now()
        // ];
        // ScreeningPayment::insert($newPayment);
    }
}
