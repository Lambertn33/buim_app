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
        $this->createScreeningPayment($distribution);
    }

    public function createScreeningPayment($payment)
    {
        $totalAmountToPay = $payment['customer_contribution'];
        $initialPayment = $payment['downpayment_amount'];
        $remainingAmount = $totalAmountToPay - $initialPayment;
        $remainingPaymentMonths = ($payment['duration'] / 30) - 1;
        $nextPaymentDate = date('Y-m-d', strtotime("+1 months", strtotime(date("y-m-d"))));
        $newPayment = [
            'id' => Str::uuid()->toString(),
            'payment_plan_id' => $payment['payment_id'],
            'screener_id' => $payment['screener_id'],
            'amount_paid' => $initialPayment,
            'remaining_amount' => $remainingAmount,
            'next_payment_date' => $nextPaymentDate,
            'remaining_months_to_pay' => $remainingPaymentMonths,
            'created_at' => now(),
            'updated_at' => now()
        ];
        ScreeningPayment::insert($newPayment);
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
        $lastPayment = $screener->payments()->orderBy('created_at', 'desc')->first();
        $newPayment = [
            'id' => Str::uuid()->toString(),
            'screener_id' => $screener->id,
            'payment_plan_id' => $lastPayment->payment_plan_id,
            'amount_paid' => $amount,
            'remaining_amount' => $lastPayment->remaining_amount - $amount,
            'next_payment_date' => date('Y-m-d', strtotime("+1 months", strtotime(date("y-m-d")))),
            'remaining_months_to_pay' => $lastPayment->remaining_months_to_pay - 1,
            'created_at' => now(),
            'updated_at' => now()
        ];
        ScreeningPayment::insert($newPayment);
    }
}
