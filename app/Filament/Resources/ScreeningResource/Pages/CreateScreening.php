<?php

namespace App\Filament\Resources\ScreeningResource\Pages;

use App\Filament\Resources\ScreeningResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;
use App\Models\Campaign;
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
        $campaignProvince = Campaign::where('id', $data['campaign_id'])->value('province');
        $campaignDistrict = Campaign::where('id', $data['campaign_id'])->value('district');
        $codeGenerator = 'C' . $campaignProvince[0] . rand(100000000, 999999999) . '';

        $data['id'] = Str::uuid()->toString();
        $data['leader_id'] = Auth::user()->leader->id;
        $data['district'] = $campaignDistrict;
        $data['manager_id'] = Campaign::where('id', $data['campaign_id'])->value('manager_id');
        $data['screening_date'] = now()->format('Y-m-d');
        $data['confirmation_status'] = Screening::PROSPECT;
        $data['prospect_code'] = $codeGenerator;
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
