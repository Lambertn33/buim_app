<?php

namespace App\Filament\Resources\PermissionResource\Pages;

use App\Filament\Resources\PermissionResource;
use Filament\Pages\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Support\Str;

class ManagePermissions extends ManageRecords
{
    protected static string $resource = PermissionResource::class;

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
                    ->title('Permission registered')
                    ->body('The permission has been successfully created.'),
            ),
        ];
    }
}
