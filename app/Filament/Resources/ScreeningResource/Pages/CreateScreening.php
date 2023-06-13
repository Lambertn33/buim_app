<?php

namespace App\Filament\Resources\ScreeningResource\Pages;

use App\Filament\Resources\ScreeningResource;
use App\Jobs\ScreeningCreated;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;
use App\Models\Campaign;
use App\Models\PaymentPlan;
use App\Models\Screening;
use App\Services\StockServices;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class CreateScreening extends CreateRecord
{
    protected static string $resource = ScreeningResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $campaign = Campaign::find($data['campaign_id']);
        $campaignProvince = $campaign->province->province;
        $campaignDistrict = $campaign->district->district;
        $codeGenerator = 'C' . $campaignProvince[0] . rand(100000000, 999999999) . '';
        $paymentPlanSelected = PaymentPlan::find($data['payment_plan_id']);
        $data['id'] = Str::uuid()->toString();
        $data['leader_id'] = Auth::user()->leader->id;
        $data['district'] = $campaignDistrict;
        $data['manager_id'] = Campaign::where('id', $data['campaign_id'])->value('manager_id');
        $data['screening_date'] = now()->format('Y-m-d');
        $data['confirmation_status'] = Screening::PROSPECT;
        $data['prospect_code'] = $codeGenerator;
        $data['total_days_to_pay'] = $paymentPlanSelected->duration;
        $data['remaining_days_to_pay'] = $paymentPlanSelected->duration;
        $message = 'Dear '.$data['prospect_names'].' thank you for choosing BUIM... you have been screened by '.Auth::user()->name. '';
        (new StockServices)->createWarehouseDeviceRequest($data);
        //send SMS to screener
        ScreeningCreated::dispatch($data['prospect_telephone'], $message);
        return $data;
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Screening created')
            ->body('The screening has been created successfully.');
    }
}
