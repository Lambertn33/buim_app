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

    protected static function getNavigationBadge(): ?string
    {
        return (new NavigationBadgesServices)->getTotalNumberOfCampaigns();
    }

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
                BadgeColumn::make('status')
                    ->colors([
                        'primary' => static fn ($state): bool => $state === self::$model::CREATED,
                        'warning' => static fn ($state): bool => $state === self::$model::ONGOING,
                        'success' => static fn ($state): bool => $state === self::$model::FINISHED,
                        'danger' => static fn ($state): bool => $state === self::$model::STOPPED,
                    ]),
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
                    ->visible(auth()->user()->role->role === Role::ADMIN_ROLE)
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
