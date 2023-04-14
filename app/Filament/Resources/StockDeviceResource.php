<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StockDeviceResource\Pages;
use App\Filament\Resources\StockDeviceResource\RelationManagers;
use App\Models\StockDevice;
use App\Services\NavigationBadgesServices;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StockDeviceResource extends Resource
{
    protected static ?string $model = StockDevice::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard';

    protected static ?string $navigationGroup = 'overall stock';

    protected static ?string $navigationLabel = 'Devices';

    protected static function getNavigationBadge(): ?string
    {
        return (new NavigationBadgesServices)->getTotalNumberOfDevices();
    }

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
                    ->columnSpanFull()
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
                IconColumn::make('screener_id')
                    ->label('is linked to customer')
                    ->options([
                        'heroicon-o-x-circle' => fn ($state, $record): bool => $record->screener_id == null,
                        'heroicon-o-check-circle' => fn ($state, $record): bool => $record->screener_id != null
                    ])
                    ->colors([
                        'danger' => fn ($state, $record): bool => $record?->screener_id == null,
                        'success' => fn ($state, $record): bool => $record?->screener_id != null,
                    ])
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageStockDevices::route('/'),
        ];
    }
}
