<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $userId = Str::uuid()->toString();
        $data['id'] = $userId;
        $data['password'] = Hash::make($data['password']);
        if (array_key_exists('district_id', $data)) {
            Session::put('district_id', $data['district_id']);
        }
        if (array_key_exists('warehouse_id', $data)) {
            Session::put('warehouse_id', $data['warehouse_id']);
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
