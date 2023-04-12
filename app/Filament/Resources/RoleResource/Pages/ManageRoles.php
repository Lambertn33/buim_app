<?php

namespace App\Filament\Resources\RoleResource\Pages;

use App\Filament\Resources\RoleResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;
use Filament\Notifications\Notification;
use Illuminate\Support\Str;

class ManageRoles extends ManageRecords
{
    protected static string $resource = RoleResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->mutateFormDataUsing(function (array $data): array {
                $data['id'] = Str::uuid()->toString();
         
                return $data;
            })
            ->successNotification(
                Notification::make()
                    ->success()
                    ->title('Role registered')
                    ->body('The role has been successfully created.'),
            ),
        ];
    }
}
