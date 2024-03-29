<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CampaignResource\Pages;
use App\Models\Campaign;
use App\Models\District;
use App\Models\Province;
use App\Models\Role;
use App\Services\NavigationBadgesServices;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Closure;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class CampaignResource extends Resource
{
    protected static ?string $model = Campaign::class;

    protected static ?string $navigationIcon = 'heroicon-o-speakerphone';

    protected static ?string $navigationGroup = 'activities';

    protected static ?string $pluralModelLabel = 'Campaign List';

    protected static ?string $navigationLabel = 'Campaigns';

    protected static function getNavigationBadge(): ?string
    {
        return (new NavigationBadgesServices)->getTotalNumberOfCampaigns();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make([
                    TextInput::make('title')
                        ->required()
                        ->label('Campaign title')
                        ->placeholder('enter title')
                        ->columnSpanFull(),
                    Textarea::make('description')
                        ->required()
                        ->label('campaign description')
                        ->columnSpanFull(),
                    Select::make('status')
                        ->required()
                        ->hiddenOn('create')
                        ->placeholder('Select campaign status')
                        ->options([
                            'CREATED' => 'CREATED',
                            'ONGOING' => 'ONGOING',
                            'FINISHED' => 'FINISHED',
                            'STOPPED' => 'STOPPED',

                        ]),
                    DatePicker::make('from')
                        ->label('starting date')
                        ->minDate(date('Y-m-d', strtotime('+1 day')))
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
                ])->columns(2)

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->sortable()
                    ->searchable()
                    ->description(fn (Campaign $record): string => $record->description),
                TextColumn::make('from')
                    ->label('starting date')
                    ->sortable(),
                TextColumn::make('to')
                    ->label('ending date')
                    ->sortable(),
                SelectColumn::make('status')
                    ->options([
                        'CREATED' => 'CREATED',
                        'ONGOING' => 'ONGOING',
                        'FINISHED' => 'FINISHED',
                        'STOPPED' => 'STOPPED',

                    ])
                    ->disabled(fn ($record) => Auth::user()->role->role != Role::DISTRICT_MANAGER_ROLE ? true : ($record->manager_id != Auth::user()->manager->id ? true : (
                        $record->status === Campaign::FINISHED ? true : false
                    )))
                    ->sortable()
                    ->disablePlaceholderSelection(),
                TextColumn::make('province.province')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('district.district')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('screenings_count')
                    ->label('number of screenings')
                    ->counts('screenings'),
                TextColumn::make('manager.user.name')
                    ->label('campaign manager')
                    ->sortable()
                    ->searchable()
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Filter by status')
                    ->options([
                        'CREATED' => 'CREATED',
                        'ONGOING' => 'ONGOING',
                        'FINISHED' => 'FINISHED',
                        'STOPPED' => 'STOPPED'
                    ]),
                SelectFilter::make('province')
                    ->label('Filter by province')
                    ->options(Province::get()->pluck('province', 'province')->toArray())
                    ->visible(Auth::user()->role->role === Role::ADMIN_ROLE)

            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->hidden(function ($record) {
                        return $record->status == Campaign::FINISHED || (Auth::user()->role->role == Role::DISTRICT_MANAGER_ROLE ? $record->manager_id != Auth::user()->manager->id : false
                        );
                    }),
            ])
            ->bulkActions([]);
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
