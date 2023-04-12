<?php

namespace App\Filament\Resources\StockDeviceResource\Pages;

use App\Filament\Resources\StockDeviceResource;
use App\Services\StockServices;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Support\Str;
use Filament\Notifications\Notification;
use Filament\Pages\Actions\Action;
use Illuminate\Support\Facades\Response;

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
            Action::make('import stock')
                ->action(fn () => null)
                ->color('success')
                ->requiresConfirmation()
                ->modalSubheading('please download the first sample to check')
                ->modalButton('Upload')
                ->form([
                    FileUpload::make('attachment')
                        ->label('upload excel file')
                        ->required()
                        ->acceptedFileTypes(["application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"])
                ]),
        ];
    }

    public function downloadStockExcelFormat()
    {
        return (new StockServices)->getSampleExcel();
    }
}
