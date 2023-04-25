<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MainWarehouseDeviceResource\Pages;
use App\Filament\Resources\MainWarehouseDeviceResource\RelationManagers;
use App\Models\MainWarehouse;
use App\Models\MainWarehouseDevice;
use App\Services\StockServices;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use PhpParser\Node\Stmt\Label;

class MainWarehouseDeviceResource extends Resource
{
    protected static ?string $model = MainWarehouseDevice::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard';

    protected static ?string $navigationGroup = 'main warehouses inventory';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('device_name')
                    ->required()
                    ->label('device name'),
                Select::make('model_id')
                    ->required()
                    ->placeholder('select model')
                    ->label('device model')
                    ->relationship('model', 'name'),
                TextInput::make('serial_number')
                    ->required()
                    ->unique(ignoreRecord: true)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('device_name')
                    ->sortable()
                    ->searchable()
                    ->label('device name'),
                TextColumn::make('model.name')
                    ->sortable()
                    ->searchable()
                    ->label('device model'),
                TextColumn::make('serial_number')
                    ->sortable()
                    ->searchable()
                    ->label('serial Number'),
                TextColumn::make('mainWarehouse.name')
                    ->sortable()
                    ->searchable()
                    ->label('Main warehouse'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Action::make('transfer')
                    ->color('success')
                    ->action(function (MainWarehouseDevice $record, array $data) {
                        (new StockServices)->transferMainWarehouseDevice($record, $data['main_warehouse_id']);
                    })
                    ->requiresConfirmation()
                    ->modalSubheading('select other main warehouse to transfer this device')
                    ->modalButton('transfer warehouse')
                    ->icon('heroicon-o-paper-airplane')
                    ->label('transfer to other main warehouse')
                    ->form(fn ($record) => [
                        Select::make('main_warehouse_id')
                            ->label('Main warehouse')
                            ->required()
                            ->placeholder('select other main warehouse')
                            ->options(MainWarehouse::whereNot('id', $record->main_warehouse_id)->get()->pluck('name', 'id')->toArray())
                    ])
                    ->successNotification(
                        Notification::make('success')
                            ->title('Device transfered')
                            ->body('device has been successfully transfered.'),
                    )

            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageMainWarehouseDevices::route('/'),
        ];
    }
}
