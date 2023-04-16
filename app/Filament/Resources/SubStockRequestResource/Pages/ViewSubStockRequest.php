<?php

namespace App\Filament\Resources\SubStockRequestResource\Pages;

use App\Filament\Resources\SubStockRequestResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewSubStockRequest extends ViewRecord
{
    protected static string $resource = SubStockRequestResource::class;

    protected function getActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
