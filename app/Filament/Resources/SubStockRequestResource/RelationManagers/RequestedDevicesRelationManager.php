<?php

namespace App\Filament\Resources\SubStockRequestResource\RelationManagers;

use App\Models\SubStockRequestDevice;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RequestedDevicesRelationManager extends RelationManager
{
    protected static string $relationship = 'requestedDevices';

    protected static ?string $recordTitleAttribute = 'device_name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('device_name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('device_name'),
                Tables\Columns\TextColumn::make('model.name'),
                Tables\Columns\TextColumn::make('screener_code'),
                Tables\Columns\TextColumn::make('names')
                    ->label('screener names')
                    ->formatStateUsing(fn (SubStockRequestDevice $record): string => $record->getScreenedPerson()->value('prospect_names')),
                Tables\Columns\TextColumn::make('date')
                    ->label('screening date')
                    ->formatStateUsing(fn (SubStockRequestDevice $record): string => $record->getScreenedPerson()->value('screening_date')),

            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
