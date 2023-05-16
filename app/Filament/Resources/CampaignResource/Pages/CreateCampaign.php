<?php

namespace App\Filament\Resources\CampaignResource\Pages;

use App\Filament\Resources\CampaignResource;
use App\Models\Campaign;
use App\Models\Province;
use App\Models\Role;
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
        $data['district_id'] = Auth::user()->manager->district->id;
        $data['province_id'] = Province::where('id', Auth::user()->manager->district->province_id)->value('id');
        //draft
        $data['from'] = now()->format('Y-m-d');
        $data['to'] = now()->format('Y-m-d');
        return $data;
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Campaign registered')
            ->body('The campaign has been created successfully.');
    }

    public function mount(): void
    {
        if (Auth::user()->role->role == Role::DISTRICT_MANAGER_ROLE) {
            $check = Campaign::where('manager_id', Auth::user()->manager->id)
                ->where('status', Campaign::ONGOING)->exists();
            abort_unless($check == false, 403);
        }
    }
}
