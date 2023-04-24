<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\District;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $userId = Str::uuid()->toString();
        $data['id'] = $userId;
        $data['password'] = Hash::make($data['password']);
        if ($data['district_id']) {
            $district = District::find($data['district_id']);
            $district->update([
                'manager_id' => $userId
            ]);
        }
        return $data;
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
        ->success()
        ->title('User registered')
        ->body('The user has been created successfully.');

    }
}
