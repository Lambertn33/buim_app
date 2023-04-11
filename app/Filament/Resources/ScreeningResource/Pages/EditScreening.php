<?php

namespace App\Filament\Resources\ScreeningResource\Pages;

use App\Filament\Resources\ScreeningResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditScreening extends EditRecord
{
    protected static string $resource = ScreeningResource::class;

    protected function getActions(): array
    {
        return [
            Actions\ViewAction::make(),
            // Actions\DeleteAction::make(),
        ];
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
