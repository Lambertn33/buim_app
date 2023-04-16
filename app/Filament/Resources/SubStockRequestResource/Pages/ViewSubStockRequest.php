<?php

namespace App\Filament\Resources\SubStockRequestResource\Pages;

use App\Filament\Resources\SubStockRequestResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewSubStockRequest extends ViewRecord
{
    protected static string $resource = SubStockRequestResource::class;

    protected static ?string $pluralModelLabel = 'Available Pending Stock Devices';

    protected function getActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
