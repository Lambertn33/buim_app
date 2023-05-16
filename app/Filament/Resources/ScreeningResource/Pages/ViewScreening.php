<?php

namespace App\Filament\Resources\ScreeningResource\Pages;

use App\Filament\Resources\ScreeningResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewScreening extends ViewRecord
{
    protected static string $resource = ScreeningResource::class;

    // protected static string $view = 'vendor.filament.resources.pages.screenings.show';

    protected function getActions(): array
    {
        return [
            // Actions\EditAction::make(),
        ];
    }
}
