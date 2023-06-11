<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ScreeningPartnerResource\Pages;
use App\Filament\Resources\ScreeningPartnerResource\RelationManagers;
use App\Models\ScreeningPartner;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ScreeningPartnerResource extends Resource
{
    protected static ?string $model = ScreeningPartner::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'Access control';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->label('Partner title')
                            ->unique(ignoreRecord: true),
                        Textarea::make('description')
                            ->label('Partner description (Optional)')
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->description(fn (ScreeningPartner $record): string => $record->description ? $record->description : ''),
                TextColumn::make('customers')
                    ->label('number of customers')
                    ->formatStateUsing(fn($record) => $record->getNumberOfCustomers())
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
            'index' => Pages\ManageScreeningPartners::route('/'),
        ];
    }
}
