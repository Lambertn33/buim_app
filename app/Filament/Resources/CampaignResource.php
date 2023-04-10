<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CampaignResource\Pages;
use App\Models\Campaign;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Forms\Components\Select;
use stdClass;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CampaignResource extends Resource
{
    protected static ?string $model = Campaign::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->required()
                    ->label('Campaign title')
                    ->placeholder('enter title')
                    ->columnSpanFull(),
                Textarea::make('description')
                    ->required()
                    ->label('campaign description')
                    ->columnSpanFull(),
                Select::make('province')
                    ->options(self::$model::PROVINCES)
                    ->placeholder('select district')
                    ->required()
                    ->reactive(),
                // TODO make District Selectable
                // Select::make('district')
                //     ->options(self::$model::PROVINCES)
                //     ->placeholder('select district')
                //     ->required()
                //     ->options(function (callable $get) {
                //         $province = $get('province');
                //         $districts =  json_decode(file_get_contents(base_path() . "/data/provinces.json"), true);
                //         if ($province) {
                //             return $districts[$province];
                //         }
                //     }),
                TextInput::make('district')
                    ->required()
                    ->label('Campaign district')
                    ->placeholder('enter district'),
                DatePicker::make('from')
                    ->label('starting date')
                    ->minDate(now())
                    ->required()
                    ->placeholder('select the starting date')
                    ->reactive(),
                DatePicker::make('to')
                    ->label('ending date')
                    ->required()
                    ->placeholder('select the ending date')
                    ->minDate(function (callable $get) {
                        $from = $get('from');
                        if ($from) {
                            return $from;
                        }
                    }),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCampaigns::route('/'),
            'create' => Pages\CreateCampaign::route('/create'),
            'edit' => Pages\EditCampaign::route('/{record}/edit'),
        ];
    }
}
