<?php

namespace App\Filament\Resources\CampaignResource\Pages;

use App\Filament\Resources\CampaignResource;
use App\Models\Province;
use Filament\Notifications\Notification;
use Filament\Pages\Actions;
use Illuminate\Support\Facades\Auth;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreateCampaign extends CreateRecord
{
    protected static string $resource = CampaignResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['id'] = Str::uuid()->toString();
        $data['manager_id'] = Auth::user()->manager->id;
        $data['province'] = Province::where('id', $data['province'])->value('province');
        return $data;
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
        ->success()
        ->title('Campaign registered')
        ->body('The campaign has been created successfully.');
    }
}
