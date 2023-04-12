<?php

namespace App\Filament\Resources\StockDeviceResource\Pages;

use App\Filament\Resources\StockDeviceResource;
use App\Models\StockDevice;
use App\Models\StockModel;
use App\Services\StockServices;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Support\Str;
use Filament\Notifications\Notification;
use Filament\Pages\Actions\Action;
use Illuminate\Support\Facades\Response;
use Konnco\FilamentImport\Actions\ImportAction;
use Konnco\FilamentImport\Actions\ImportField;

class ManageStockDevices extends ManageRecords
{
    protected static string $resource = StockDeviceResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Create device')
                ->mutateFormDataUsing(function (array $data): array {
                    $data['id'] = Str::uuid()->toString();

                    return $data;
                })
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title('Device registered')
                        ->body('New device has been successfully created.'),
                ),
            Action::make('download excel sample')
                ->action('downloadStockExcelFormat')
                ->requiresConfirmation()
                ->modalSubheading('please fill this downloaded file and upload it')
                ->color('danger')
                ->modalButton('download sample'),
            ImportAction::make()
                ->handleBlankRows(true)
                ->fields([
                    ImportField::make('name')
                        ->label('name'),
                    ImportField::make('serial_number')
                        ->label('serial number'),
                    ImportField::make('model')
                        ->mutateBeforeCreate(fn ($value) => StockModel::where('name', 'LIKE', "%{$value}%")->value('id'))
                        ->label('model')
                ])->handleRecordCreation(function ($data) {
                    $data['id'] = Str::uuid()->toString();
                    $data['model_id'] = $data['model'];
                    $data['created_at'] = now();
                    $data['updated_at'] = now();
                    return StockDevice::create($data);
                })
        ];
    }

    public function downloadStockExcelFormat()
    {
        return (new StockServices)->getSampleExcel();
    }
}
