<?php

namespace App\Filament\Resources\TechnicianResource\Pages;

use App\Filament\Resources\TechnicianResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTechnician extends EditRecord
{
    protected static string $resource = TechnicianResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
