<?php

namespace App\Filament\Resources\SubStockRequestResource\Pages;

use App\Filament\Resources\SubStockRequestResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSubStockRequest extends EditRecord
{
    protected static string $resource = SubStockRequestResource::class;

    protected function getActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
