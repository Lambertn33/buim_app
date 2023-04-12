<?php

namespace App\Filament\Resources\StockModelResource\Pages;

use App\Filament\Resources\StockModelResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageStockModels extends ManageRecords
{
    protected static string $resource = StockModelResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
